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
        'short_desc',
        'link',
        'salary',
        'workplace',
        'level',
        'skills',
        'map',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'img_path',
    ];

    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }

    public function vacancyType(): BelongsTo
    {
        return $this->belongsTo(VacancyType::class);
    }

    public function faculties(): BelongsToMany
    {
        return $this->belongsToMany(Faculty::class, 'faculties_vacancies')->using(FacultyVacancy::class);
    }

    public function functions(): BelongsToMany
    {
        return $this->belongsToMany(VacancyFunction::class, 'vacancies_functions')->using(VacanciesFunctions::class);
    }
}
