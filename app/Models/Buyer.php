<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buyer extends Model
{
    /**
     * Get the transactions for the buyer.
     */
    public function transactions() : HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
