<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'desc',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function speciality(): BelongsToMany
    {
        return $this->belongsToMany(
            Speciality::class,
            'students_specialities',
            'student_id',
            'speciality_id'
        );
    }
}
