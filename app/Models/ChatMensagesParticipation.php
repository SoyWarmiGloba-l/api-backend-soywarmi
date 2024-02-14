<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ChatMensagesParticipation extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];
    protected $table='chat_mensages_participation';
    protected $primaryKey = 'id_chat_mensages_participation';
    
}
