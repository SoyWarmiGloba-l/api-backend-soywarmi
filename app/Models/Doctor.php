<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
