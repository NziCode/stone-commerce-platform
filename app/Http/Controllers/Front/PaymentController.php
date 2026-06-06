<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Shetabit\Multipay\Facade\Payment as PaymentGateway;
use Shetabit\Multipay\Invoice;

class PaymentController extends Controller
{
    public function index(Order $order)
    {
        $this->authorize('view', $order);

        return view('front.payment.index', compact('order'));
    }

    public function payOnline(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        $payment = Payment::create([
            'order_id' => $order->id,
            'type'     => 'online',
            'status'   => 'pending',
            'gateway'  => config('payment.default'),
            'amount'   => $order->total,
            'currency' => $order->currency,
        ]);

        try {
            $invoice = (new Invoice)->amount($order->total);

            return PaymentGateway::purchase($invoice, function ($driver, $transactionId) use ($payment) {
                $payment->update(['transaction_id' => $transactionId]);
            })->pay()->render();

        } catch (\Exception $e) {
            $payment->fail();
            return back()->with('error', 'خطا در اتصال به درگاه پرداخت.');
        }
    }

    public function uploadReceipt(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        $request->validate([
            'receipt_file'       => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'bank_name'          => 'required|string|max:100',
            'bank_country'       => 'required|string|max:5',
            'transfer_reference' => 'required|string|max:100',
            'receipt_date'       => 'required|date',
            'receipt_notes'      => 'nullable|string',
        ]);

        $payment = Payment::create([
            'order_id'           => $order->id,
            'type'               => 'receipt',
            'status'             => 'pending',
            'amount'             => $order->total,
            'currency'           => $order->currency,
            'bank_name'          => $request->bank_name,
            'bank_country'       => $request->bank_country,
            'transfer_reference' => $request->transfer_reference,
            'receipt_date'       => $request->receipt_date,
            'receipt_notes'      => $request->receipt_notes,
        ]);

        if ($request->hasFile('receipt_file')) {
            $payment->addMediaFromRequest('receipt_file')
                ->toMediaCollection('receipt');
        }

        $order->update(['status' => 'processing']);

        return redirect()->route('orders.show', $order)
            ->with('success', 'فیش بانکی با موفقیت ارسال شد. پس از تأیید، سفارش شما تأیید می‌شود.');
    }

    public function callback(Request $request, string $gateway)
    {
        $payment = Payment::where('transaction_id', $request->Authority ?? $request->trackId)->first();

        if (!$payment) {
            return redirect()->route('home')->with('error', 'پرداخت یافت نشد.');
        }

        try {
            $receipt = PaymentGateway::amount($payment->amount)
                ->transactionId($payment->transaction_id)
                ->verify();

            $payment->update([
                'reference_id'     => $receipt->getReferenceId(),
                'gateway_response' => $receipt->getDetail(),
            ]);

            $payment->markAsPaid();

            return redirect()->route('orders.show', $payment->order)
                ->with('success', 'پرداخت با موفقیت انجام شد.');

        } catch (\Exception $e) {
            $payment->fail(['error' => $e->getMessage()]);

            return redirect()->route('orders.show', $payment->order)
                ->with('error', 'پرداخت ناموفق بود.');
        }
    }
}
