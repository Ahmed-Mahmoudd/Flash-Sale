<?php

namespace App\Repositories;

use App\Models\Hold;

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
}
