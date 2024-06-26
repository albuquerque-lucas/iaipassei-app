<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;

class StudyArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function examinations(): BelongsToMany
    {
        return $this->belongsToMany(Examination::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class);
    }

    public function topics(): HasManyThrough
    {
        return $this->hasManyThrough(Topic::class, Subject::class);
    }

    public function examQuestions(): HasManyThrough
    {
        return $this->hasManyThrough(ExamQuestion::class, Subject::class);
    }

    public static function getAll(string $order, string $orderBy = 'id', array $params = []): Collection | LengthAwarePaginator
    {
        $query = self::orderBy($orderBy, $order);
        $pagination = $params['pagination'] ?? true;
        unset($params['pagination']);
        foreach ($params as $key => $value) {
            if (!is_null($value)) {
                $query = $query->where($key, 'like', "%$value%");
            }
        }


        return $pagination ? $query->paginate() : $query->get();
    }

    public static function getByArea(string $area, string $order = 'desc'): LengthAwarePaginator
    {
        return self::where('area', 'like', "%{$area}%")->orderBy('id', $order)->paginate();
    }

    public static function getById(int $id): self | null
    {
        return self::where('id', $id)->first();
    }
}
