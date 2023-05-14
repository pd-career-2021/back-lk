<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Application extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'desc',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array<string, string>
     */
    protected $allowedFilters = [
        'id',
        'desc',
        'vacancy_id',
        'student_id',
        'application_status_id',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'desc',
        'vacancy_id',
        'student_id',
        'application_status_id',
        'updated_at',
        'created_at',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function application_status(): BelongsTo
    {
        return $this->belongsTo(ApplicationStatus::class);
    }
}
