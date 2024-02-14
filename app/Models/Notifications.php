<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Musonza\Chat\Traits\Messageable;

class Notifications extends Model
{
    use HasFactory,Messageable;
    protected $guarded = [];

    public function peopleNotifications(): HasMany
    {
        return $this->hasOne(PeopleNotifications::class,"id","id_notifications");
    }

}
