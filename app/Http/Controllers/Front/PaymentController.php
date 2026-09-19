<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\AdminNotifier;
use Illuminate\Http\Request;
use Shetabit\Multipay\Invoice;
use Shetabit\Multipay\Payment as PaymentGateway;

class PaymentController extends Controller
{
    /**
     * shetabit/multipay v2 is a plain library — it ships no Laravel Facade
     * and no service-container binding, so `Shetabit\Multipay\Facade\Payment`
     * (used here previously) doesn't exist and every call to it was a fatal
     * "class not found" error. Build a fresh instance from config/payment.php
     * instead, exactly as the package's own docs show.
     */
    private function gateway(): PaymentGateway
    {
        return new PaymentGateway(config('payment'));
    }

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

            return $this->gateway()->purchase($invoice, function ($driver, $transactionId) use ($payment) {
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

        AdminNotifier::dispatch('فیش بانکی جدید', [
            'شماره سفارش: ' . $order->order_number,
            'بانک: ' . $request->bank_name,
            'شماره پیگیری: ' . $request->transfer_reference,
        ], AdminNotifier::adminUrl('orders'));

        return redirect()->route('orders.show', $order)
            ->with('success', 'فیش بانکی با موفقیت ارسال شد. پس از تأیید، سفارش شما تأیید می‌شود.');
    }

    public function callback(Request $request, string $gateway)
    {
        $payment = Payment::where('transaction_id', $request->Authority ?? $request->trackId)->first();

        if (!$payment) {
            return redirect()->route('home')->with('error', 'پرداخت یافت نشد.');
        }

        // Idempotency: a refreshed callback page or a gateway retry must not
        // re-run verify() on an already-settled payment — many gateway
        // drivers reject a second verify() and throw, which previously fell
        // into the catch block and flipped an already-paid payment to
        // "failed" even though the order was already confirmed.
        if ($payment->isPaid()) {
            return redirect()->route('orders.show', $payment->order)
                ->with('success', 'پرداخت با موفقیت انجام شد.');
        }

        if ($payment->status === 'failed') {
            return redirect()->route('orders.show', $payment->order)
                ->with('error', 'پرداخت ناموفق بود.');
        }

        try {
            $receipt = $this->gateway()
                ->amount($payment->amount)
                ->transactionId($payment->transaction_id)
                ->verify();

            // Defense in depth: refuse to confirm if the order total has
            // since diverged from what this payment was created against.
            if (bccomp((string) $payment->amount, (string) $payment->order->fresh()->total, 2) !== 0) {
                $payment->fail(['error' => 'Amount mismatch between payment and order total at verification time.']);

                return redirect()->route('orders.show', $payment->order)
                    ->with('error', 'مبلغ پرداخت با مبلغ سفارش مطابقت ندارد.');
            }

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
