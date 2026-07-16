<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ReleaseExpiredHoldsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(
        \App\Repositories\HoldRepository $holdRepository,
        \App\Repositories\ProductRepository $productRepository
    ): void {
        $expiredHolds = $holdRepository->getExpiredActiveHolds();

        foreach ($expiredHolds as $hold) {

            DB::transaction(function () use (
                $hold,
                $holdRepository,
                $productRepository
            ) {

                $lockedHold = $holdRepository
                    ->findByIdForUpdate($hold->id);

                if (!$lockedHold || $lockedHold->status !== 'active') {
                    return;
                }

                $product = $productRepository
                    ->findByIdForUpdate($lockedHold->product_id);

                if (!$product) {
                    return;
                }

                if ($product->reserved_stock < $lockedHold->qty) {
                    return;
                }

                $product->reserved_stock -= $lockedHold->qty;
                $productRepository->save($product);

                $lockedHold->status = 'expired';
                $holdRepository->save($lockedHold);
            });
        }
    }
}
