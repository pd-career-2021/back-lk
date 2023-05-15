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
        return [
            'id' => $this->id,
            'desc' => $this->desc,
            'student_id' => $this->student()->pluck('id'),
            'vacancy_id' => $this->vacancy()->pluck('id'),
            'application_status_id' => $this->application_status()->pluck('id'),
        ]
    }
}