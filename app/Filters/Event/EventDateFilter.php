<?php

namespace App\Filters\Event;

use Closure;
use App\Filters\Pipe;

class EventDateFilter implements Pipe
{
    public function apply($events, Closure $next)
    {
        if (request()->has('date')) {     
            $events->whereDate('date', request()->query('date'));
        }

        return $next($events);
    }
}
