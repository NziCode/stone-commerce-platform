<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items', 'latestPayment'])
            ->latest()
            ->paginate(10);

        return view('front.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['items.product.media', 'payments']);

        return view('front.orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        $this->authorize('view', $order);

        if (!$order->isPending() && !in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'این سفارش قابل لغو نیست.');
        }

        $order->cancel();

        return back()->with('success', 'سفارش با موفقیت لغو شد.');
    }
}
