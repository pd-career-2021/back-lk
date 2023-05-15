<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VacancyResource extends JsonResource
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
            'desc' => $this->desc,
            'salary' => $this->salary,
            'salary_type' => $this->salary_type,
            'employment_type' => $this->employment_type,
            'work_experience' => $this->work_experience,
            'duties' => $this->duties,
            'conditions' => $this->conditions,
            'requirements' => $this->requirements,
            'workplace' => $this->workplace,
            'map_link' => $this->map_link,
            'image' => asset('public/storage/' . $path),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'skills' => new CoreSkillCollection($this->skills),
            'faculties' => new FacultyCollection($this->faculties),
            'employer' => new EmployerResource($this->employer),
        ];
    }
}
