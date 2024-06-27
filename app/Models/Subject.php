<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'education_level_id',
        'title',
    ];

    public function exams(): BelongsToMany
    {
        return $this->belongsToMany(Exam::class);
    }

    public function examQuestions(): HasMany
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class);
    }

    public function studyArea(): BelongsToMany
    {
        return $this->belongsToMany(StudyArea::class);
    }

    public function educationLevel(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public static function getAll(string $order, string $orderBy = 'id', array $params = []): LengthAwarePaginator
    {
        $query = self::orderBy($orderBy, $order);

        foreach ($params as $key => $value) {
            if (!is_null($value)) {
                $query = $query->where($key, 'like', "%$value%");
            }
        }

        return $query->paginate();
    }

    public static function getById(int $id): self | null
    {
        return self::where('id', $id)->first();
    }

    public static function getByTitle(string $title, string $order): LengthAwarePaginator
    {
        return self::where('title', 'like', "%$title%")->orderBy('id', $order)->paginate();
    }

    public static function getByArea(array $studyAreaIds, string $order = 'desc'): Collection
    {
        return self::whereIn('study_area_id', $studyAreaIds)
            ->orderBy('id', $order)
            ->get();
    }
}
