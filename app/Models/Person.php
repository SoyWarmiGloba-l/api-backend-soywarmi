<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Person extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function doctor(): HasOne
    {
        return $this->hasOne(Doctor::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
