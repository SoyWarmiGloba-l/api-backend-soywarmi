<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ChatParticipations extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $primaryKey = "id_chat_participation";

    public function chatConversations(): BelongsTo
    {
        return $this->belongsTo(ChatConversations::class);
    }
}
