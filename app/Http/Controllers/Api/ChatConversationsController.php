<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatConversations;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Firebase\FirebaseToken;


class ChatConversationsController extends Controller
{
    public function obtenerConversacionesUsuario(Request $request): JsonResponse
    {
        $token = new FirebaseToken($request->bearerToken());
        try {
            $payload = $token->verify_other(config('services.firebase.project_id'));
        } catch (\Exception $e) {
            return responseJSON(null, 401, $e->getMessage());
        }
        $userId = $payload->authenticated_user->uid;

        $result = DB::table('chat_conversations as cc')
            ->select(
                'cc.id_chat_conversation',
                'cc.id_type_chat_conversations',
                DB::raw('
                    CASE
                        WHEN (cc.name = "" OR cc.name IS NULL) AND cc.id_type_chat_conversations = 1
                        THEN (
                            SELECT u2.email 
                            FROM chat_participations cp2 
                            JOIN users u2 ON u2.id = cp2.id_user 
                            WHERE cp2.id_user != "'.$userId.'" AND cp2.id_chat_conversation = cc.id_chat_conversation 
                            LIMIT 1
                        )
                        ELSE cc.name
                    END AS name
                '),
                DB::raw('
                    (
                        SELECT COUNT(cmp4.id_chat_mensages_participation) 
                        FROM chat_mensages_participation cmp4 
                        JOIN chat_participations cp4 ON cmp4.id_chat_participation = cp4.id_chat_participation 
                        JOIN chat_conversations cc4 ON cc4.id_chat_conversation = cp4.id_chat_conversation 
                        JOIN users u4 ON u4.id = cp4.id_user 
                        WHERE cc4.id_chat_conversation = cc.id_chat_conversation 
                        AND cmp4.read = 0 
                        AND cp4.id_user != "'.$userId.'") AS unread_messages_count'),
                DB::raw('
                    (
                        SELECT cmp5.content 
                        FROM chat_mensages_participation cmp5 
                        JOIN chat_participations cp5 ON cmp5.id_chat_participation = cp5.id_chat_participation 
                        JOIN chat_conversations cc5 ON cc5.id_chat_conversation = cp5.id_chat_conversation 
                        WHERE cc5.id_chat_conversation = cc.id_chat_conversation 
                        ORDER BY cmp5.created_at DESC 
                        LIMIT 1
                    ) AS last_message
                '),
                DB::raw('
                    (
                        SELECT cmp6.created_at 
                        FROM chat_mensages_participation cmp6 
                        JOIN chat_participations cp6 ON cmp6.id_chat_participation = cp6.id_chat_participation 
                        JOIN chat_conversations cc6 ON cc6.id_chat_conversation = cp6.id_chat_conversation 
                        WHERE cc6.id_chat_conversation = cc.id_chat_conversation 
                        ORDER BY cmp6.created_at DESC 
                        LIMIT 1
                    ) AS last_message_date
                ')
            )
            ->join('chat_participations as cp', 'cp.id_chat_conversation', '=', 'cc.id_chat_conversation')
            ->join('users as u', 'u.id', '=', 'cp.id_user')
            ->where('u.id', '=', $userId)
            ->orderByDesc('last_message_date')
            ->get();
       
        return responseJSON($result, 200, 'ChatConversations fetched successfully.');
    }
    public function index(): JsonResponse
    {
        return responseJSON(ChatConversations::get(), 200, 'ChatConversations fetched successfully.');
    }
}
