<?php

namespace App\Console\Commands;

use App\Models\Cart;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpireCarts extends Command
{
    protected $signature = 'carts:expire';
    protected $description = 'Release products reserved by abandoned carts whose reservation window has passed';

    public function handle(): int
    {
        $expiredCarts = Cart::query()
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->with('items.product')
            ->get();

        $released = 0;

        foreach ($expiredCarts as $cart) {
            DB::transaction(function () use ($cart, &$released) {
                foreach ($cart->items as $item) {
                    if ($item->product && $item->product->isReserved()) {
                        $item->product->markAsAvailable();
                        $released++;
                    }
                }

                $cart->clear();
                $cart->update(['expires_at' => null]);
            });
        }

        $this->info("Expired {$expiredCarts->count()} cart(s), released {$released} product(s).");

        return self::SUCCESS;
    }
}
