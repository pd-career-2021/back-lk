<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Industry extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
    ];

    public function employers(): BelongsToMany
    {
        // return $this->belongsToMany(Employer::class)->using(CompanyIndustry::class);
        return $this->belongsToMany(
            Industry::class,
            'company_industries',
            'industry_id',
            'employer_id'
        );
    }
}
