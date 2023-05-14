<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Vacancy extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'desc',
        'salary',
        'salary_type',
        'employment_type',
        'work_experience',
        'duties',
        'conditions',
        'requirements',
        'workplace',
        'map_link',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'img_path',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array<string, string>
     */
    protected $allowedFilters = [
        'id',
        'title',
        'desc',
        'salary',
        'employment_type',
        'work_experience',
        'workplace'
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'title',
        'desc',
        'salary',
        'employment_type',
        'work_experience',
        'updated_at',
        'created_at',
    ];

    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }

    public function faculties(): BelongsToMany
    {
        return $this->belongsToMany(Faculty::class, 'faculties_vacancies')->using(FacultyVacancy::class)->withTimestamps();
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(CoreSkill::class, 'vacancies_skills')->using(VacanciesCoreSkills::class)->withTimestamps();
    }
}
