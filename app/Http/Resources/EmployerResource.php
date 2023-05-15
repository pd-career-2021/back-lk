<?php

namespace App\Http\Resources;

use App\Models\CompanyType;
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
            'company_type' => CompanyType::find($this->company_type_id)->title,
            'industries' => new IndustryCollection($this->industries),
            'socials' => new SocialCollection($this->socials),
            'user' => new UserResource($this->user),
            'image' => asset('public/storage/' . $path),
        ];
    }
}