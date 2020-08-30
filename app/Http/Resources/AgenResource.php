<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'kd_agen'=>$this->kd_agen,
            'store_name'=>$this->store_name,
            'store_owner'=>$this->store_owner,
            'address'=>$this->address,
            'latitude'=>$this->latitude,
            'logitude'=>$this->logitude,
            'photo_store'=>env('ASSET_URL')."/uploads/".$this->photo_store
        ];
    }
}
