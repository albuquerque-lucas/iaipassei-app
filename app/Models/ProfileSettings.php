<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'show_username',
        'show_email',
        'show_sex',
        'show_sexual_orientation',
        'show_gender',
        'show_race',
        'show_disability',
    ];

    protected $casts = [
        'show_username' => 'boolean',
        'show_email' => 'boolean',
        'show_sex' => 'boolean',
        'show_sexual_orientation' => 'boolean',
        'show_gender' => 'boolean',
        'show_race' => 'boolean',
        'show_disability' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
