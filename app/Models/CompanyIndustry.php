<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanyIndustry extends Pivot
{
    protected $table = 'company_industries';
}
