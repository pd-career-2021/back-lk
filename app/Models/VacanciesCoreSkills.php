<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class VacanciesCoreSkills extends Pivot
{
    protected $table = 'vacancies_skills';
}
