<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->customer->name ?? null,
            'phone_number' => $this->customer->phone_number ?? null,
            'reservation_date' => $this->reservation_date,
            'reservation_time' => $this->reservation_time,
            'created_at' => $this->created_at,
        ];
    }
}
