<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CoreSkill extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
    ];

    public function vacancies(): BelongsToMany
    {
        // return $this->belongsToMany(Vacancy::class)->using(CoreSkill::class);
        return $this->belongsToMany(
            Vacancy::class,
            'vacancies_skills',
            'core_skill_id',
            'vacancy_id'
        );
    }
}
