<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::get();

        if ($reservations->count() > 0) {
            return ReservationResource::collection($reservations);
        } else {
            return response()->json(['message' => 'No record available'], 200);
        }

        // This only returns one of the tables information (reservation_dates). In this table only the date is being displayed. We need to also return the table with the names.
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'All fields are mandatory',
                'error' => $validator->messages(),
            ], 422);
        }

        $reservation = Reservation::create($validator->validated());

        return response()->json([
            'message' => 'Reservation created successfully',
            'data' => new ReservationResource($reservation),
        ], 200);
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
