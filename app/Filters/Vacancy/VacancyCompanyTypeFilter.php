<?php

namespace App\Filters\Vacancy;

use Closure;
use App\Filters\Pipe;
use Illuminate\Database\Eloquent\Builder;

class VacancyCompanyTypeFilter implements Pipe
{
    public function apply($vacancies, Closure $next)
    {
        if (request()->has('companyType')) {
            $vacancies->whereHas(
                'employer',
                function (Builder $query) {
                    $query->whereHas(
                        'companyType',
                        function (Builder $query) {
                            $query->whereIn('title', request()->query('companyType'));
                        }
                    );
                }
            );
        }

        return $next($vacancies);
    }
}
