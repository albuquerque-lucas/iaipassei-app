<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;
use Cviebrock\EloquentSluggable\Sluggable;

class Examination extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'education_level_id',
        'title',
        'slug',
        'institution',
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function notice(): HasMany
    {
        return $this->hasMany(Notice::class);
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    public function studyAreas(): BelongsToMany
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
}
