<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Cviebrock\EloquentSluggable\Sluggable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, Sluggable;

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
        'slug',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted()
    {
        static::created(function ($user) {
            $user->profileSettings()->create([
                'show_username' => true,
                'show_email' => true,
                'show_sex' => false,
                'show_sexual_orientation' => false,
                'show_gender' => false,
                'show_race' => false,
                'show_disability' => false,
            ]);
        });
    }

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

    public function profileSettings(): HasOne
    {
        return $this->hasOne(ProfileSettings::class);
    }
}
