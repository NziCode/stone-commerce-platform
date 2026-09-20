<?php

namespace App\Filament\Pages;

use App\Filament\Support\StoneSaleActions;
use App\Models\MainCategory;
use App\Models\Mine;
use App\Models\Owner;
use App\Models\Warehouse;
use App\Services\StoneReport;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

/**
 * Back-office report: stock and sales of the stones per warehouse, owner, mine or main category,
 * filterable and downloadable as CSV. Administrators only.
 */
class StoneReports extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'انبار و فروش';
    protected static ?string $navigationLabel = 'گزارش انبار و فروش';
    protected static ?string $title = 'گزارش انبار و فروش';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.stone-reports';

    /** @var array<string, mixed> */
    public ?array $filters = [
        'from' => null, 'to' => null,
        'main_category_id' => null, 'owner_id' => null, 'mine_id' => null, 'warehouse_id' => null,
    ];

    public string $dimension = 'warehouse';

    public static function canAccess(): bool
    {
        return StoneSaleActions::allowed();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('filters')
            ->columns(6)
            ->schema([
                Forms\Components\DatePicker::make('from')->label('فروش از تاریخ')->live(),
                Forms\Components\DatePicker::make('to')->label('تا تاریخ')->live(),
                Forms\Components\Select::make('main_category_id')->label('دسته اصلی')->placeholder('همه')->live()
                    ->options(fn () => MainCategory::ordered()->get()->mapWithKeys(fn ($m) => [$m->id => $m->getTranslation('name', 'fa', false)])),
                Forms\Components\Select::make('warehouse_id')->label('انبار')->placeholder('همه')->live()
                    ->options(fn () => Warehouse::orderBy('name')->pluck('name', 'id')),
                Forms\Components\Select::make('owner_id')->label('مالک')->placeholder('همه')->live()
                    ->options(fn () => Owner::orderBy('name')->pluck('name', 'id')),
                Forms\Components\Select::make('mine_id')->label('معدن')->placeholder('همه')->live()
                    ->options(fn () => Mine::orderBy('name')->pluck('name', 'id')),
            ]);
    }

    public function mount(): void
    {
        $this->form->fill($this->filters);
    }

    public function report(): StoneReport
    {
        return new StoneReport(array_filter($this->filters ?? [], fn ($v) => filled($v)));
    }

    public function setDimension(string $dimension): void
    {
        if (array_key_exists($dimension, StoneReport::DIMENSIONS)) {
            $this->dimension = $dimension;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('csv')
                ->label('دانلود CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    $csv = $this->report()->toCsv($this->dimension);
                    $name = 'stone-report-' . $this->dimension . '-' . now()->format('Ymd') . '.csv';

                    return response()->streamDownload(function () use ($csv) {
                        echo $csv;
                    }, $name, ['Content-Type' => 'text/csv; charset=UTF-8']);
                }),
        ];
    }

    /** @return array<string, mixed> */
    protected function getViewData(): array
    {
        $report = $this->report();

        return [
            'rows'       => $report->rows($this->dimension),
            'totals'     => $report->totals(),
            'dimensions' => StoneReport::DIMENSIONS,
        ];
    }
}
