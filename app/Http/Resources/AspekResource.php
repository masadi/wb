<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class AspekResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'komponen_id' => $this->komponen_id,
            'nama' => $this->nama,
            'created_at' => Carbon::parse($this->created_at)->format('M-d-Y'),
            'updated_at' => Carbon::parse($this->created_at)->diffForHumans(),
        ];
    }
}
