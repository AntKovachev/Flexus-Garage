<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\CustomerReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CustomerReservationController extends Controller
{
    public function index()
    {
        $customer = CustomerReservation::get();
        return CustomerResource::collection($customer);
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

        $customer = CustomerReservation::create(array_merge([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'note' => $request->note ?? null,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
        ], [
            'user_id' => auth()->id()
        ]));

        return response()->json([
            'message' => 'Reservation created successfully',
            'data' => new CustomerResource($customer),
        ], 201);
    }

    public function show($id)
    {
        $customer = CustomerReservation::find($id);

        if (!$customer) {
            return response()->json(['message' => 'CustomerReservation not found'], 404);
        }

        return new CustomerResource($customer);
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

        $customer = CustomerReservation::find($id);

        if($customer->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }


        $customer->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'note' => $request->note ?? null,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
        ]);

        return response()->json([
            'message' => 'Reservation updated successfully',
            'data' => new CustomerResource($customer),
        ], 201);
    }

    public function destroy($id)
    {
        $customer = CustomerReservation::find($id);

        if (!$customer) {
            return response()->json([
                'message' => 'Reservation does not exist',
            ], 404);
        }

        if ($customer->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $customer->delete();

        return response()->json([
            'message' => 'Reservation deleted successfully',
        ], 200);
    }
}
