<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function person(): HasOne
    {
        return $this->hasOne(Person::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    protected $casts = [
        'social_networks' => 'json',
    ];
}
