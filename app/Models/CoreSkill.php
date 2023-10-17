<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class CoreSkill extends Model
{
    use HasFactory, AsSource, Filterable;

    /**
    * The attributes for which you can use filters in url.
    *
    * @var array<string, string>
    */
   protected $allowedFilters = [
       'id',
       'title',
   ];

   /**
    * The attributes for which can use sort in url.
    *
    * @var array
    */
   protected $allowedSorts = [
       'id',
       'title',
       'updated_at',
       'created_at',
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