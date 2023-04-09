<?php

namespace App\Filters\Vacancy;

use Closure;
use App\Filters\Pipe;

class VacancySalaryFilter implements Pipe {
    public function apply($vacancies, Closure $next) {
        if (request()->has('salary')) {
            $vacancies->whereBetween('salary', json_decode(request()->query('salary')[0], false));
        }

        return $next($vacancies);
    }
}