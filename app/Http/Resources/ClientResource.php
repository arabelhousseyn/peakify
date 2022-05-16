<?php

namespace App\Http\Resources;

use App\Models\City;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'city' => new CityResource(City::find($this->city_id)),
            'city_id' =>$this->city_id,
            'address' => $this->address
        ];
    }
}
