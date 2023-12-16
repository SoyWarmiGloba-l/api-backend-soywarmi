<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatParticipations extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function chatConversations(): BelongsTo
    {
        return $this->belongsTo(ChatConversations::class);
    }
}
