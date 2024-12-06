<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationResource;
use App\Models\Customer;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('customer')->get();

        if ($reservations->count() > 0) {
            return ReservationResource::collection($reservations);
        } else {
            return response()->json(['message' => 'No record available'], 200);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|min:10',
            'reservation_date' => 'required|date_format:Y-m-d',
            'reservation_time' => 'required|date_format:H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'All fields are mandatory',
                'error' => $validator->messages(),
            ], 422);
        }

        $customer = Customer::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
        ]);

        $reservation = Reservation::create([
            'reservation_id' => $customer->id,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
        ]);

        return response()->json([
            'message' => 'Reservation created successfully',
            'data' => new ReservationResource($reservation),
        ], 200);
    }

    public function show(Reservation $reservation)
    {
        return new ReservationResource($reservation);
    }

    // update function returns null for name and phone number on customer
    public function update(Request $request, Reservation $reservation)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|min:10',
            'reservation_date' => 'required|date_format:Y-m-d',
            'reservation_time' => 'required|date_format:H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'All fields are mandatory',
                'error' => $validator->messages(),
            ], 422);
        }

        $customer = $reservation->customer;

        if (!$customer) {
            return response()->json([
                'message' => 'Associated customer not found.',
            ], 404);
        }

        $customer->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
        ]);

        $reservation->update([
            'reservation_id' => $customer->id,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
        ]);

        return response()->json([
            'message' => 'Reservation updated successfully',
            'data' => new ReservationResource($reservation),
        ], 200);
    }

    public function destroy() {}
}
