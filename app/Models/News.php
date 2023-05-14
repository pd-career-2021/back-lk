<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class News extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    protected $table = 'news';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'preview_text',
        'detail_text',
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
        'preview_text',
        'detail_text',
        'employer_id',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'title',
        'preview_text',
        'detail_text',
        'employer_id',
        'updated_at',
        'created_at',
    ];


    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }
}
