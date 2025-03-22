<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashflow extends Model
{
    /** @use HasFactory<\Database\Factories\CashflowFactory> */
    use HasFactory;
    protected $fillable = [
        'email',
        'balance',
        'fluctuation',
        'action'
    ];
}
