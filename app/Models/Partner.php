<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Partner extends Pivot
{
    protected $table = 'partners';
}
