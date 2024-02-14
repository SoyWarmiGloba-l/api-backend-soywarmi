<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ChatConversations extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];
    protected $primaryKey = 'id_chat_conversation';
    public function chatParticipations(): HasMany
    {
        return $this->hasMany(ChatParticipations::class);
    }
}
