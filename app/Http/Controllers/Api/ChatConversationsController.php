<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatConversations;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Firebase\FirebaseToken;
use App\Models\Person;
use App\Events\MensajesSinLeer;
use App\Models\ChatParticipations;


class ChatConversationsController extends Controller
{
    public function destroy(ChatConversations $chatConversation): JsonResponse
    {
        try {
            $token = new FirebaseToken($request->bearerToken());
            $payload = $token->verify_other(config('services.firebase.project_id'));
            $email = $payload->authenticated_user->email;
            $userId = Person::where('email', $email)->first()->value('id');
            $chat_partipation=ChatParticipations::where('id_chat_conversation',$chatConversation->id_chat_conversation)->where('id_user',$userId);
            if($chat_partipation->id_type_chat_participations==1){
                $chatConversation->delete();
                $id_usuarios_destino=DB::table('people as u')
                ->select('u.id')
                ->join('chat_participations as cp', 'u.id', '=', 'cp.id_user')
                ->join('chat_conversations as cc', 'cp.id_chat_conversation', '=', 'cc.id_chat_conversation')
                ->where('cc.id_chat_conversation', '=', $chatConversation->id_chat_conversation)
                ->get();
                foreach ($id_usuarios_destino as $id) {
                    MensajesSinLeer::dispatch((string)$id->id);
                }
            }else{
                return responseJSON(null, 403, "No tiene permiso de eliminar el chat");
            }
            return responseJSON(null, 200, 'Chat Conversation deleted successfully.');
        } catch (\Exception $e) {
            return responseJSON(null, 400, $e->getMessage());
        }
    }
    public function obtenerConversacionesUsuario(Request $request): JsonResponse
    {
        $token = new FirebaseToken($request->bearerToken());
        try {
            $payload = $token->verify_other(config('services.firebase.project_id'));
        } catch (\Exception $e) {
            return responseJSON(null, 401, $e->getMessage());
        }
        $email = $payload->authenticated_user->email;
        $userId = Person::where('email', $email)->first()->value('id');
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
                        AND JSON_UNQUOTE(JSON_EXTRACT(cmp4.read_message_participants, \'$."'.$userId.'"\')) = 0 
                        AND cp4.id_user != "'.$userId.'") AS unread_messages_count'),
                DB::raw('
                    (
                        SELECT cmp5.content 
                        FROM chat_mensages_participation cmp5 
                        JOIN chat_participations cp5 ON cmp5.id_chat_participation = cp5.id_chat_participation 
                        JOIN chat_conversations cc5 ON cc5.id_chat_conversation = cp5.id_chat_conversation 
                        WHERE cc5.id_chat_conversation = cc.id_chat_conversation 
                        AND cmp5.deleted_at = null
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
                        AND cmp6.deleted_at = null
                        ORDER BY cmp6.created_at DESC 
                        LIMIT 1
                    ) AS last_message_date
                ')
            )
            ->join('chat_participations as cp', 'cp.id_chat_conversation', '=', 'cc.id_chat_conversation')
            ->join('people as u', 'u.id', '=', 'cp.id_user')
            ->where('u.id', '=', $userId)
            ->where('cc.deleted_at', '=', null)
            ->orderByDesc('last_message_date')
            ->get();
       
        return responseJSON($result, 200, 'ChatConversations fetched successfully.');
    }
    public function index(): JsonResponse
    {
        return responseJSON(ChatConversations::get(), 200, 'ChatConversations fetched successfully.');
    }
    public function registerChatConversation(Request $request): JsonResponse
    {
        $token = new FirebaseToken($request->bearerToken());
        try {
            $payload = $token->verify_other(config('services.firebase.project_id'));
        } catch (\Exception $e) {
            return responseJSON(null, 401, $e->getMessage());
        }
        $email = $payload->authenticated_user->email;
        $userId = Person::where('email', $email)->first()->value('id');
        $users = [];        
        
        foreach ($request->users as $user) {
            array_push($users,$user);
        }
        $length = count($users);
        $type=2;
        $name="";
        $crear_nuevo=true;
        $chat_conversation_id=null;
        if($length>2){
            $name=(string)$request->name;
            $type=2;
        }else{
            $name="";
            $type=1;
            $result = DB::table('chat_conversations AS cc')
            ->join('chat_participations AS cp', 'cp.id_chat_conversation', '=', 'cc.id_chat_conversation')
            ->join('people AS u', 'u.id', '=', 'cp.id_user')
            ->select('cp.id_chat_conversation', DB::raw('GROUP_CONCAT(u.id) AS users_ids'))
            ->groupBy('cp.id_chat_conversation')
            ->havingRaw('COUNT(cp.id_chat_participation) = 2')
            ->get();
            foreach ($result as $r) {
                $arrayResultante = array_map('trim', explode(',', $r->users_ids));
                if (in_array($users[0], $arrayResultante) && in_array($users[1], $arrayResultante)) {
                    $crear_nuevo=false;
                    $chat_conversation_id = $r->id_chat_conversation;
                } 
            }

        }
        if($crear_nuevo){
                       
            $chat_conversation_id = DB::table('chat_conversations')->insertGetId([
                'name' => $name,
                'id_type_chat_conversations' => $type,
            ]);
            $chat_participation_creator=['id_user' => $userId, 'id_chat_conversation' => $chat_conversation_id,'id_type_chat_participations'=>1];
            DB::table('chat_participations')->insert($chat_participation_creator);

            $chat_participations = [];
            foreach ($users as $user) {
                array_push($chat_participations,['id_user' => $user, 'id_chat_conversation' => $chat_conversation_id,'id_type_chat_participations'=>2]);
            }
            DB::table('chat_participations')->insert($chat_participations);
        }
        return responseJSON($chat_conversation_id, 200, 'ChatConversations fetched successfully.');
    }
}
