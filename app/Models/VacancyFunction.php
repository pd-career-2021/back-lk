<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VacancyFunction extends Model
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
        // return $this->belongsToMany(Vacancy::class)->using(VacanciesFunctions::class);
        return $this->belongsToMany(
            Vacancy::class,
            'vacancies_functions',
            'vacancy_function_id',
            'vacancy_id'
        );
    }
}
