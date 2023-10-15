<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class CompanyType extends Model
{
    use HasFactory, AsSource, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array<string, string>
     */
    protected $allowedFilters = [
        'id',
        'title',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'title',
        'updated_at',
        'created_at',
    ];
}
