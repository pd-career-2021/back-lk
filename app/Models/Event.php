<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'date',
        'desc',
        'img_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'audience_id',
        'img_path',
    ];

    public function audience(): BelongsTo
    {
        return $this->belongsTo(Audience::class);
    }

    public function employers(): BelongsToMany
    {
        return $this->belongsToMany(Employer::class, 'partners')->using(Partner::class);
    }
}
