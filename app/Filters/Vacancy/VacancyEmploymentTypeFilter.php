<?php

namespace App\Filters\Vacancy;

use Closure;
use App\Filters\Pipe;

class VacancyEmploymentTypeFilter implements Pipe
{
    public function apply($vacancies, Closure $next)
    {
        if (request()->has('empType')) {
            $queryFilters = array();
            $types = array(
                "project" => "Проектная работа",
                "intern" => "Стажировка",
                "part" => "Частичная занятость",
                "full" => "Полная занятость",
            );
            foreach (request()->query('empType') as $type)
                array_push($queryFilters, $types[$type]);

            $vacancies->whereIn('employment_type', $queryFilters);
        }

        return $next($vacancies);
    }
}
