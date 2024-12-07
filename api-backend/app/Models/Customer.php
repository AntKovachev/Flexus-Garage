<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer_reservations';

    protected $fillable = [
        'name',
        'phone_number',
        'reservation_date',
        'reservation_time',
    ];
}
