<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservation_dates';

    protected $fillable = [
        'reservation_id',
        'reservation_date',
        'reservation_time',
    ];

    public function reservation() {
        return $this->belongsTo(Customer::class, 'reservation_id');
    }
}
