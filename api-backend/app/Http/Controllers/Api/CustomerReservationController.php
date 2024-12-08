<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerReservationController extends Controller
{
    public function index()
    {
        $customer = Customer::get();
        return ReservationResource::collection($customer);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|min:10',
            'note' => 'nullable|string|max:255',
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
            'note' => $request->note ?? null,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
        ]);

        return response()->json([
            'message' => 'Reservation created successfully',
            'data' => new ReservationResource($customer),
        ], 200);
    }

    public function show($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return new ReservationResource($customer);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|min:10',
            'note' => 'nullable|string|max:255',
            'reservation_date' => 'required|date_format:Y-m-d',
            'reservation_time' => 'required|date_format:H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'All fields are mandatory',
                'error' => $validator->messages(),
            ], 422);
        }

        $customer = Customer::find($id);

        $customer->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'note' => $request->note ?? null,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
        ]);

        return response()->json([
            'message' => 'Reservation updated successfully',
            'data' => new ReservationResource($customer),
        ], 200);
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'message' => 'Reservation does not exist',
            ], 404);
        }

        $customer->delete();

        return response()->json([
            'message' => 'Reservation deleted successfully',
        ], 200);
    }
}
