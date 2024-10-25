<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class CustomerReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::get();

        if ($reservations) {

        } else {
            return response()->json(['message' => 'No record available'], 200);
        }
    }

    public function store()
    {

    }

    public function show()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}
