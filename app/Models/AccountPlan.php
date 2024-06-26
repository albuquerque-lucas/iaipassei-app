<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'access_level',
        'name',
        'description',
        'price',
        'duration_days',
        'is_public',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function accessLevel(): BelongsTo
    {
        return $this->belongsTo(AccessLevel::class);
    }

    public static function getAllOrdered(string $order, string $orderBy = 'id'): LengthAwarePaginator
    {
        return self::orderBy($orderBy, $order)->paginate();
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
