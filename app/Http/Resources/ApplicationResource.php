<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
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
            'id' => $this->id,
            'desc' => $this->desc,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => new ApplicationStatusResource($this->application_status),
            'student' => new StudentResource($this->student),
            'vacancy' => new VacancyResource($this->vacancy),
        ];
    }
}