<?php

namespace App\Filters\Vacancy;

use Closure;
use App\Filters\Pipe;
use Illuminate\Database\Eloquent\Builder;

class VacancyCoreSkillsFilter implements Pipe
{
    public function apply($vacancies, Closure $next)
    {
        if (request()->has('skills')) {
            $vacancies->whereHas('skills', function (Builder $query) {
                $query->whereIn('title', request()->query('skills'));
            });
        }

        return $next($vacancies);
    }
}
