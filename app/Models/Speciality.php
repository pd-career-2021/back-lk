<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Speciality extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'desc',
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function student(): BelongsToMany
    {
        return $this->belongsToMany(
            Student::class,
            'students_specialities',
            'speciality_id',
            'student_id'
        );
    }

    public function vacancy(): BelongsToMany
    {
        return $this->belongsToMany(
            Student::class,
            'vacancies_specialities',
            'speciality_id',
            'vacancy_id'
        );
    }
}
