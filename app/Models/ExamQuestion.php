<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;

class ExamQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'subject_id',
        'topic_id',
        'question_number',
        'statement',
    ];

    protected function casts() {
        return [
            'statement' => 'string',
        ];
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function alternatives(): HasMany
    {
        return $this->hasMany(QuestionAlternative::class)->orderBy('letter', 'asc');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ExamQuestionImage::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public static function getAll(string $order, string $orderBy = 'id', $params = []): LengthAwarePaginator
    {
        // dd($params);
        $query = self::orderBy($orderBy, $order);
        foreach ($params as $key => $value) {
            if (!is_null($value)) {
                $query = $query->where($key, 'like', "%$value%");
            }
        }
        return $query->paginate(5);
    }

    public static function getById(int $id): self | null
    {
        return self::where('id', $id)->first();
    }

    public static function getByStatement(string $statement, string $order = 'desc'): LengthAwarePaginator
    {
        return self::where('statement', 'like', "%$statement%")->orderBy('id', $order)->paginate();
    }
}
