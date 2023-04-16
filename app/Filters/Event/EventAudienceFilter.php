<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;
use Illuminate\Database\Eloquent\Builder;

class EventAudienceFilter implements Pipe
{
    public function apply($events, Closure $next)
    {
        if (request()->has('audience')) {
            $queryFilters = array();
            $audiences = array(
                "applicants" => "Абитуриенты",
                "students" => "Студенты",
                "graduates" => "Выпускники",
                "masters" => "Магистры",
                "postgraduates" => "Аспиранты"
            );
            foreach (request()->query('audience') as $audience)
                array_push($queryFilters, $audiences[$audience]);
            
            $events->whereHas('audience', function (Builder $query) use ($queryFilters) {
                $query->whereIn('name', $queryFilters);
            });
        }

        return $next($events);
    }
}
