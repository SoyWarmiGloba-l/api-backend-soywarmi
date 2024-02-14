<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Musonza\Chat\Traits\Messageable;
use Illuminate\Database\Eloquent\SoftDeletes;


class PeopleNotifications extends Model
{
    use HasFactory,Messageable,SoftDeletes;

    protected $guarded = [];


    public function notifications(): BelongsTo
    {
        return $this->belongsTo(Notifications::class,"id_notifications","id");
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class,"id_people","id");
    }
}
