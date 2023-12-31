<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventType extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}
