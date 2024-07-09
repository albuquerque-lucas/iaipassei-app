<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;

class Exam extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'examination_id',
        'title',
        'date',
        'description',
        'slug',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['title', 'examination.title']
            ]
        ];
    }

    public function examination()
    {
        return $this->belongsTo(Examination::class);
    }

    public function examQuestions()
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    public static function getAll(string $order, string $orderBy = 'id'): LengthAwarePaginator
    {
        return self::orderBy($orderBy, $order)->paginate();
    }

    public static function getById(int $id): self | null
    {
        return self::where('id', $id)->first();
    }
}
