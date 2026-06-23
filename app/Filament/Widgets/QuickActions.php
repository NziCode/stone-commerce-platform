<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Review;
use App\Models\ContactMessage;
use App\Models\Payment;
use Filament\Widgets\Widget;

class QuickActions extends Widget
{
    protected static ?int $sort   = 0;
    protected static bool $isLazy = false;
    protected int|string|array $columnSpan = 'full';

    protected static string $view = 'filament.widgets.quick-actions';

    public function getViewData(): array
    {
        return [
            'pendingOrders'   => Order::where('status', 'pending')->count(),
            'pendingReceipts' => Payment::where('status', 'pending')->where('type', 'receipt')->count(),
            'pendingReviews'  => Review::where('status', 'pending')->count(),
            'newMessages'     => ContactMessage::where('status', 'new')->count(),
        ];
    }
}
