<?php

namespace App\Actions\Contracts;

use App\Models\Contract;
use Illuminate\Support\Facades\DB;

class UpdateContractAction
{
    public function execute(Contract $contract, array $payload): Contract
    {
        return DB::transaction(function () use ($contract, $payload): Contract {
            $contract->update($payload);

            return $contract->refresh();
        });
    }
}
