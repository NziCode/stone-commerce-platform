<?php

namespace App\Console\Commands;

use App\Models\ReservationRequest;
use Illuminate\Console\Command;

class ExpireReservations extends Command
{
    protected $signature = 'reservations:expire';
    protected $description = 'Release products whose approved reservation window has passed';

    public function handle(): int
    {
        $expired = ReservationRequest::query()
            ->where('status', 'approved')
            ->where('expires_at', '<=', now())
            ->get();

        foreach ($expired as $reservation) {
            $reservation->update(['status' => 'expired']);

            if ($reservation->product && $reservation->product->isReserved()) {
                $reservation->product->markAsAvailable();
            }
        }

        $this->info("Expired {$expired->count()} reservation(s).");

        return self::SUCCESS;
    }
}
