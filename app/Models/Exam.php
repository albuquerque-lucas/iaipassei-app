<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'examination_id',
        'title',
        'date',
        'description',
    ];

    protected function casts() {
        return [
            'date' => 'date',
        ];
    }

    public function examination(): BelongsTo
    {
        return $this->belongsTo(Examination::class);
    }

    public function booklet(): BelongsTo
    {
        return $this->belongsTo(Booklet::class);
    }

    public function examQuestions(): HasMany
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function subjects(): BelongsToMany
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
