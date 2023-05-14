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
        $path = ($this->img_path) ? $this->img_path : 'img/blank.jpg';
        
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => asset('public/storage/' . $path),
            'date' => $this->date,
            'desc' => $this->desc,
            'audiences' => $this->audiencec(),
            'employers' => $this->employers(),
            
        ];
    }
}
