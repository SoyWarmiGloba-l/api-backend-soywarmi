<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\MedicalCenter;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MedicalService extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function medicalCenters(): BelongsToMany
    {
        return $this->belongsToMany(MedicalCenter::class);
    }
}