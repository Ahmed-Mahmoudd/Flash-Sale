<?php

namespace App\Repositories;

use App\Models\Hold;
use Carbon\Carbon;

class HoldRepository
{
    public function create(array $data): Hold
    {
        return Hold::create($data);
    }

    public function save(Hold $hold): bool
    {
        return $hold->save();
    }

    public function findById(int $id): ?Hold
    {
        return Hold::find($id);
    }

    public function findByIdForUpdate(int $id)
    {
        return Hold::lockForUpdate()->find($id);
    }

    public function getExpiredActiveHolds()
    {
        return Hold::where('status', 'active')
            ->where('expires_at', '<=', Carbon::now())
            ->get();
    }
}
