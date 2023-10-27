<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalCenter extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function medicalServices(): BelongsToMany
    {
        return $this->belongsToMany(MedicalService::class, 'center_services', 'center_id', 'service_id');
    }
}
