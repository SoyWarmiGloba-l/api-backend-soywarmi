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
        $results = DB::table('chat_conversations as cc')
        ->select('cc.name', 'cc.id_chat_conversation', 'cc.id_type_chat_conversations')
        ->join('chat_participations as cp', 'cp.id_chat_conversation', '=', 'cc.id_chat_conversation')
        ->join('users as u', 'u.id', '=', 'cp.id_user')
        ->where('u.id', '=', $payload->authenticated_user->uid)
        ->get();
        $i=0;
        foreach ($results as $chat_conversation) {
            if($chat_conversation->id_type_chat_conversations==1){
                $results[$i]->name= DB::table('chat_participations as cp')
                ->select('u.name')
                ->join('users as u', 'cp.id_user', '=', 'u.id')
                ->where('cp.id_chat_conversation', '=', $chat_conversation->id_chat_conversation)
                ->where('cp.id_user', '!=', $payload->authenticated_user->uid)
                ->get()[0]->name;
            }
            $i++;
        }
        return responseJSON($results, 200, 'ChatConversations fetched successfully.');
    }
    public function index(): JsonResponse
    {
        return responseJSON(ChatConversations::get(), 200, 'ChatConversations fetched successfully.');
    }
}
