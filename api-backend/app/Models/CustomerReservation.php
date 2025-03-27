<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerReservation extends Model
{
    use HasFactory;

    protected $table = 'customer_reservations';

    protected $fillable = [
        'user_id',
        'name',
        'phone_number',
        'note',
        'reservation_date',
        'reservation_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
