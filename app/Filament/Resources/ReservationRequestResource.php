<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationRequestResource\Pages;
use App\Models\ReservationRequest;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReservationRequestResource extends Resource
{
    protected static ?string $model = ReservationRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return __('admin.reservation_requests');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.orders');
    }

    public static function getModelLabel(): string
    {
        return __('admin.reservation_request');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.reservation_requests');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    // Requests are only ever created from the storefront.
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make(__('admin.reservation_request'))->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Select::make('product_id')
                        ->label(__('admin.product'))
                        ->relationship('product', 'sku')
                        ->getOptionLabelFromRecordUsing(fn ($record) => $record->getTranslation('name', app()->getLocale()))
                        ->disabled(),

                    Forms\Components\TextInput::make('name')->label(__('admin.name'))->disabled(),
                    Forms\Components\TextInput::make('phone_country')->label(__('admin.country_code'))->disabled(),
                    Forms\Components\TextInput::make('phone')->label(__('admin.phone'))->disabled(),
                    Forms\Components\TextInput::make('contact_method')->label(__('admin.contact_method'))->disabled(),
                ]),
                Forms\Components\Textarea::make('note')->label(__('admin.customer_note'))->disabled()->rows(3)->columnSpanFull(),
            ]),

            Forms\Components\Section::make(__('admin.reservation_payment_section'))->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('deposit_amount')->label(__('admin.deposit_amount'))->disabled(),
                    Forms\Components\TextInput::make('deposit_currency')->label(__('admin.deposit_currency'))->disabled(),
                    Forms\Components\DateTimePicker::make('deposit_received_at')->label(__('admin.reservation_deposit_received_at'))->disabled(),
                    Forms\Components\DateTimePicker::make('final_paid_at')->label(__('admin.reservation_final_paid_at'))->disabled(),
                ]),
            ])->collapsible(),

            Forms\Components\Section::make(__('admin.decision'))->schema([
                Forms\Components\Select::make('status')
                    ->label(__('admin.status'))
                    ->options([
                        'pending'   => __('admin.reservation_status_pending'),
                        'approved'  => __('admin.reservation_status_approved'),
                        'rejected'  => __('admin.reservation_status_rejected'),
                        'expired'   => __('admin.reservation_status_expired'),
                        'cancelled' => __('admin.reservation_status_cancelled'),
                    ])
                    ->required(),
                Forms\Components\DateTimePicker::make('expires_at')->label(__('admin.reserved_until')),
                Forms\Components\Textarea::make('admin_note')->label(__('admin.admin_note'))->rows(2)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label(__('admin.product'))
                    ->formatStateUsing(fn ($record) => $record->product?->getTranslation('name', app()->getLocale()))
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('admin.name'))
                    ->default('—')
                    ->searchable(),

                Tables\Columns\TextColumn::make('full_phone')
                    ->label(__('admin.phone'))
                    ->icon('heroicon-o-phone')
                    ->copyable(),

                Tables\Columns\TextColumn::make('contact_method')
                    ->label(__('admin.contact_method'))
                    ->badge()
                    ->color(fn (string $state) => $state === 'whatsapp' ? 'success' : 'gray')
                    ->formatStateUsing(fn (string $state) => $state === 'whatsapp'
                        ? __('admin.whatsapp')
                        : __('admin.phone_call')),

                Tables\Columns\TextColumn::make('stage')
                    ->label(__('admin.reservation_stage'))
                    ->badge()
                    ->color(fn ($record) => $record->stage_color)
                    ->formatStateUsing(fn ($record) => $record->stage_label),

                Tables\Columns\TextColumn::make('deposit_amount')
                    ->label(__('admin.deposit_amount'))
                    ->state(fn ($record) => $record->hasDeposit()
                        ? (trim(($record->deposit_amount ? number_format((float) $record->deposit_amount) : '') . ' ' . $record->deposit_currency) ?: '✔')
                        : null)
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label(__('admin.reserved_until'))
                    ->dateTime('Y-m-d H:i')
                    ->placeholder('—')
                    ->color(fn ($record) => $record->expires_at && $record->expires_at->isPast() && $record->stage !== 'completed' ? 'danger' : null),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('admin.requested_at'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('stage')
                    ->label(__('admin.reservation_stage'))
                    ->options([
                        'awaiting_deposit' => __('admin.reservation_stage_awaiting_deposit'),
                        'deposit_paid'     => __('admin.reservation_stage_deposit_paid'),
                        'completed'        => __('admin.reservation_stage_completed'),
                    ])
                    ->query(fn (Builder $query, array $data) => match ($data['value'] ?? null) {
                        'awaiting_deposit' => $query->where('status', 'approved')->whereNull('deposit_received_at'),
                        'deposit_paid'     => $query->where('status', 'approved')->whereNotNull('deposit_received_at')->whereNull('final_paid_at'),
                        'completed'        => $query->where('status', 'approved')->whereNotNull('final_paid_at'),
                        default            => $query,
                    }),

                Tables\Filters\SelectFilter::make('status')
                    ->label(__('admin.status'))
                    ->options([
                        'pending'   => __('admin.reservation_status_pending'),
                        'approved'  => __('admin.reservation_status_approved'),
                        'rejected'  => __('admin.reservation_status_rejected'),
                        'expired'   => __('admin.reservation_status_expired'),
                        'cancelled' => __('admin.reservation_status_cancelled'),
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('whatsapp')
                    ->label(__('admin.whatsapp_customer'))
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->iconButton()
                    ->url(fn (ReservationRequest $record) => $record->whatsapp_url, shouldOpenInNewTab: true)
                    ->visible(fn (ReservationRequest $record) => filled($record->whatsapp_url)),

                Tables\Actions\Action::make('call')
                    ->label(__('admin.call_customer'))
                    ->icon('heroicon-o-phone')
                    ->color('gray')
                    ->iconButton()
                    ->url(fn (ReservationRequest $record) => 'tel:' . $record->call_number)
                    ->visible(fn (ReservationRequest $record) => filled($record->call_number)),

                Tables\Actions\Action::make('approve')
                    ->label(__('admin.approve'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (ReservationRequest $record) => $record->isPending())
                    ->requiresConfirmation()
                    ->action(function (ReservationRequest $record) {
                        // approve() atomically re-checks + reserves the product
                        // itself (tryReserve), so this can't race with another
                        // admin approving a different request for the same product.
                        if (! $record->approve(auth()->id())) {
                            Notification::make()
                                ->title(__('admin.reservation_product_unavailable'))
                                ->danger()
                                ->send();
                            return;
                        }

                        Notification::make()
                            ->title(__('admin.reservation_approved'))
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label(__('admin.reject'))
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (ReservationRequest $record) => $record->isPending())
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('admin_note')->label(__('admin.admin_note'))->rows(2),
                    ])
                    ->action(function (ReservationRequest $record, array $data) {
                        $record->reject($data['admin_note'] ?? null);

                        Notification::make()
                            ->title(__('admin.reservation_rejected'))
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('depositReceived')
                    ->label(__('admin.deposit_received_action'))
                    ->icon('heroicon-o-banknotes')
                    ->color('info')
                    ->visible(fn (ReservationRequest $record) => $record->stage === 'awaiting_deposit')
                    ->form([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('deposit_amount')
                                ->label(__('admin.deposit_amount'))
                                ->numeric()
                                ->minValue(0),
                            Forms\Components\Select::make('deposit_currency')
                                ->label(__('admin.deposit_currency'))
                                ->options(['USD' => 'USD ($)', 'EUR' => 'EUR (€)', 'AED' => 'AED', 'IRT' => 'تومان', 'IRR' => 'ریال'])
                                ->default('USD')
                                ->required(),
                        ]),
                        Forms\Components\DateTimePicker::make('final_deadline')
                            ->label(__('admin.final_payment_deadline'))
                            ->seconds(false)
                            ->default(fn () => now()->addDays(ReservationRequest::DEFAULT_FINAL_PAYMENT_DAYS)),
                        Forms\Components\Textarea::make('note')->label(__('admin.admin_note'))->rows(2),
                    ])
                    ->action(function (ReservationRequest $record, array $data) {
                        $amount = isset($data['deposit_amount']) && $data['deposit_amount'] !== '' ? (float) $data['deposit_amount'] : null;

                        $record->markDepositReceived(
                            $amount,
                            $data['deposit_currency'] ?? null,
                            filled($data['final_deadline'] ?? null) ? Carbon::parse($data['final_deadline']) : null,
                            $data['note'] ?? null,
                        );

                        Notification::make()
                            ->title(__('admin.deposit_recorded'))
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('finalPaid')
                    ->label(__('admin.final_paid_action'))
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (ReservationRequest $record) => $record->stage === 'deposit_paid')
                    ->requiresConfirmation()
                    ->modalDescription(__('admin.final_paid_confirm'))
                    ->action(function (ReservationRequest $record) {
                        $record->markFinalPaid();

                        Notification::make()
                            ->title(__('admin.final_paid_recorded'))
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('release')
                    ->label(__('admin.release_reservation'))
                    ->icon('heroicon-o-lock-open')
                    ->color('gray')
                    ->visible(fn (ReservationRequest $record) => $record->isApproved() && $record->stage !== 'completed')
                    ->requiresConfirmation()
                    ->action(function (ReservationRequest $record) {
                        $record->release();

                        Notification::make()
                            ->title(__('admin.reservation_released'))
                            ->success()
                            ->send();
                    }),

                Tables\Actions\EditAction::make()
                    ->label(__('admin.details')),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservationRequests::route('/'),
            'edit'  => Pages\EditReservationRequest::route('/{record}/edit'),
        ];
    }
}
