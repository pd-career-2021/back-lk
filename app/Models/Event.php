<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'date',
        'desc',
        'img_path',
    ];

    public function audience(): HasOne
    {
        return $this->hasOne(Audience::class);
    }

    public function employers(): BelongsToMany
    {
        return $this->belongsToMany(Employer::class)->using(Partner::class);
    }
}
