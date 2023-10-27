<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalService extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function medicalCenters(): BelongsToMany
    {
        return $this->belongsToMany(MedicalCenter::class, 'center_services', 'service_id', 'center_id');
    }
}
