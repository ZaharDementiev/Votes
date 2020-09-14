<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaction';

    public const TRANSACTION_WAIT = 1;
    public const TRANSACTION_DONE = 2;
    public const TRANSACTION_DENY = 3;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'to_id', 'id');
    }
}
