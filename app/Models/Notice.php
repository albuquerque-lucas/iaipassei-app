<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'examination_id',
        'file_path',
        'file_name',
        'extension',
    ];

    protected $casts = [
        'publication_date' => 'date',
    ];

    public function examination(): BelongsTo
    {
        return $this->belongsTo(Examination::class);
    }

    public static function getAll(string $order, string $orderBy = 'id', array $params = []): LengthAwarePaginator
    {
        // dd($params);
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
