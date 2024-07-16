<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Cviebrock\EloquentSluggable\Sluggable; // Importando a trait Sluggable

class User extends Authenticatable
{
    use HasFactory, Notifiable, Sluggable; // Usando a trait Sluggable

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_plan_id',
        'first_name',
        'last_name',
        'username',
        'profile_img',
        'email',
        'phone_number',
        'password',
        'sex',
        'sexual_orientation',
        'gender',
        'race',
        'disability',
        'slug', // Adicionando slug aos fillables
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Retorna a configuração para geração de slugs.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['first_name', 'last_name']
            ]
        ];
    }

    public function examinations(): BelongsToMany
    {
        return $this->belongsToMany(Examination::class);
    }

    public function accountPlan(): BelongsTo
    {
        return $this->belongsTo(AccountPlan::class);
    }

    public static function getAll(string $order, string $orderBy = 'id'): LengthAwarePaginator
    {
        return self::orderBy($orderBy, $order)->paginate();
    }

    public static function getByName(string $name, string $order = 'desc'): LengthAwarePaginator
    {
        return self::where('full_name', 'like', "%{$name}%")->orderBy('id', $order)->paginate();
    }

    public static function getById(int $id): self | null
    {
        return self::where('id', $id)->first();
    }
}
