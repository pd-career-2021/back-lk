<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Faculty extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'desc',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'img_path',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array<string, string>
     */
    protected $allowedFilters = [
        'id',
        'title',
        'desc',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'title',
        'desc',
        'updated_at',
        'created_at',
    ];

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function vacancies(): BelongsToMany
    {
        return $this->belongsToMany(Vacancy::class, 'faculties_vacancies')->using(FacultyVacancy::class)->withTimestamps();
    }

    // public function image()
    // {
    //     return $this->hasOne(Attachment::class, 'id', 'img_path')->withDefault();
    // }
}
