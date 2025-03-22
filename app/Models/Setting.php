<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /** @use HasFactory<\Database\Factories\SettingFactory> */
    use HasFactory;
    protected $fillable = [
        'time_update_service',
        'time_update_order',
        'time_deny_order',
        'account_no',
        'time_exchange_rate',
        'phone',
        'facebook',
        'zalo'
    ];
}
