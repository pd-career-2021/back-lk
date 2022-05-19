<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vacancy extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'desc',
        'img_path',
        'link',
        'salary',
        'workplace',
        'level'
    ];

    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function speciality(): BelongsToMany
    {
        return $this->belongsToMany(
            Speciality::class,
            'vacancies_specialities',
            'vacancy_id',
            'speciality_id'
        );
    }

    public function faculties(): BelongsToMany
    {
        return $this->belongsToMany(Faculty::class)->using(FacultyVacancy::class);
    }

    public function functions(): BelongsToMany
    {
        return $this->belongsToMany(VacancyFunction::class)->using(VacanciesFunctions::class);
    }

}
