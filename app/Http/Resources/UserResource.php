<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            '_id' => $this->_id,
            'full_name' => $this->full_name,
            'email' =>$this->email,
            'phone' => $this->phone,
            'username' => $this->username,
            'job' => $this->job
        ];
    }
}
