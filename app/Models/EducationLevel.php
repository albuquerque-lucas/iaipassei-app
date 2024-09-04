<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;

class EducationLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public static function getAll(string $order, string $orderBy = 'id'): Collection
    {
        return self::orderBy($orderBy, $order)->get();
    }

    public static function getById(int $id): self | null
    {
        return self::where('id', $id)->first();
    }

    public static function getByName(string $name, string $order): LengthAwarePaginator
    {
        return self::where('name', 'like', "%$name%")->orderBy('id', $order)->paginate();
    }
}
