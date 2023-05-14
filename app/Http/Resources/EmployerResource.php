<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $path = ($this->img_path) ? $this->img_path : 'img/blank.jpg';
        
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'short_name' => $this->short_name,
            'desc' => $this->desc,
            'image' => asset('public/storage/' . $path),
            'user_id' => $this->user()->pluck('id'),
            'company_type_id' => $this->company_type()->pluck('id'),
            'industry' => $this->industry()->pluck('title')->implode(', '),
        ]
    }
}