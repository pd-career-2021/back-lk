<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'short_name',
        'img_path',
        'desc'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function companyType(): BelongsTo
    {
        return $this->belongsTo(CompanyType::class);
    }

    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }

    public function industries(): BelongsToMany
    {
        return $this->belongsToMany(Industry::class)->using(CompanyIndustry::class);
    }

    public function event(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)->using(Partner::class);
    }
}
