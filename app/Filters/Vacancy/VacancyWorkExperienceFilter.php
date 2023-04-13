<?php

namespace App\Filters\Vacancy;

use Closure;
use App\Filters\Pipe;

class VacancyWorkExperienceFilter implements Pipe
{
    public function apply($vacancies, Closure $next)
    {
        if (request()->has('exp')) {
            $queryFilters = array();
            $experience = array(
                "no" => "Без опыта",
                "nomatter" => "Не имеет значения",
                "1-3" => "От 1 года до 3 лет",
                "3-6" => "От 3 до 6 лет",
                ">6" => "Более 6 лет"
            );
            foreach (request()->query('exp') as $exp)
                array_push($queryFilters, $experience[$exp]);

            $vacancies->whereIn('work_experience', $queryFilters);
        }

        return $next($vacancies);
    }
}
