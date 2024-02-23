<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use App\Firebase\FirebaseToken;
use App\Events\RespuestaReceptor;
use App\Events\RegistroMensaje;
use App\Events\MensajesSinLeer;
use App\Models\Person;
use App\Models\ChatMensagesParticipation;
use App\Models\ChatParticipations;

class ChatMessagesParticipation extends Controller
{
    public function checkReadMessage(Request $request,$conversationId): JsonResponse{

        $token = new FirebaseToken($request->bearerToken());

        try {
            $payload = $token->verify_other(config('services.firebase.project_id'));
        } catch (\Exception $e) {
            return responseJSON(null, 401, $e->getMessage());
        }
        $email = $payload->authenticated_user->email;
        $userId = (Person::where('email', $email)->first())->id;

        $results = DB::table('chat_mensages_participation as cmp')
        ->select('cmp.id_chat_mensages_participation', 'cmp.read_message_participants')
        ->join('chat_participations as cp', 'cmp.id_chat_participation', '=', 'cp.id_chat_participation')
        ->join('chat_conversations as cc', 'cc.id_chat_conversation', '=', 'cp.id_chat_conversation')
        ->join('people as u', 'u.id', '=', 'cp.id_user')
        ->where('cc.id_chat_conversation', $conversationId)
        ->get();
        foreach ($results as $data) {
            $id = $data->id_chat_mensages_participation;
            $json = json_decode($data->read_message_participants, true);
        
            $json[(string)$userId] = 1;
        
            DB::table('chat_mensages_participation')
                ->where('id_chat_mensages_participation', $id)
                ->update(['read_message_participants' => json_encode($json)]);
        }
        return response()->json(['message' => 'Mensaje marcados con check correctamente.'], 200);

    }
    public function obtenerChatMessagesConversation(Request $request,$chat): JsonResponse
    {
        $id_chat_conversation = $chat;
        $results = DB::table('chat_mensages_participation as cmp')
        ->select('cmp.id_chat_mensages_participation as id','cmp.content', 'cmp.created_at', 'u.id as owner_id','u.email as owner_email','u.photo as owner_photo')
        ->join('chat_participations as cp', 'cp.id_chat_participation', '=', 'cmp.id_chat_participation')
        ->join('people as u', 'cp.id_user', '=', 'u.id')
        ->join('chat_conversations as cc', 'cc.id_chat_conversation', '=', 'cp.id_chat_conversation')
        ->where('cc.id_chat_conversation', '=', $id_chat_conversation)
        ->where('cmp.deleted_at', '=', null)
        ->orderBy('cmp.created_at', 'DESC')
        ->get();
        return responseJSON($results, 200, 'ChatConversations fetched successfully.');
    }
    public function postMessage(Request $request,$chat): JsonResponse
    {


        $token = new FirebaseToken($request->bearerToken());

        try {
            $payload = $token->verify_other(config('services.firebase.project_id'));
        } catch (\Exception $e) {
            return responseJSON(null, 401, $e->getMessage());
        }
        $email = $payload->authenticated_user->email;
        $userId = (Person::where('email', $email)->first())->id;

        $id_chat_participation = DB::table('chat_participations as cp')
        ->select('cp.id_chat_participation')
        ->where('cp.id_chat_conversation', '=', (string)$chat)
        ->where('cp.id_user', '=',$userId)
        ->get();
        
        $fecha_actual_gmt_4 = Carbon::now('GMT-4')->toDateTimeString();
        $contenido = $request->input('content');

        $userIds = DB::table('chat_conversations as cc')
        ->join('chat_participations as cp', 'cc.id_chat_conversation', '=', 'cp.id_chat_conversation')
        ->join('people as u', 'u.id', '=', 'cp.id_user')
        ->where('cc.id_chat_conversation', $chat)
        ->pluck('u.id')
        ->toArray();

        $jsonData = collect($userIds)->mapWithKeys(function ($userId) {
            return [$userId => 0];
        })->toJson();
        

        $resultado_insercion=DB::table('chat_mensages_participation')->insert([
            'id_chat_participation' => $id_chat_participation[0]->id_chat_participation,
            'content' => (string)$contenido,
            'read_message_participants' => $jsonData,
            'created_at' => (string)$fecha_actual_gmt_4,
            'updated_at' => (string)$fecha_actual_gmt_4,
        ]);
        $id_usuarios_destino=DB::table('people as u')
        ->select('u.id')
        ->join('chat_participations as cp', 'u.id', '=', 'cp.id_user')
        ->join('chat_conversations as cc', 'cp.id_chat_conversation', '=', 'cc.id_chat_conversation')
        ->where('cc.id_chat_conversation', '=', (string)$chat)
        ->where('cp.id_user', '!=',$userId)
        ->get();
        
        

        if ($resultado_insercion) {
            //RespuestaReceptor::dispatch('c1fa8fb1-8598-4824-aeb5-fcc05c54ca11', ['order' => 123]);
            RegistroMensaje::dispatch((string)$chat);
            foreach ($id_usuarios_destino as $id) {
                MensajesSinLeer::dispatch((string)$id->id);
            }
        } 
        //return responseJSON($id_usuarios_destino, 200, 'ChatConversations fetched successfully.');
        return response()->json(['message' => 'Mensaje insertado correctamente.'], 200);
    }
    public function destroy(ChatMensagesParticipation $chatMensagesParticipation): JsonResponse
    {
        try {
            $chatParticipation = ChatParticipations::where('id_chat_participation', $chatMensagesParticipation->id_chat_participation)->first();
            $chatMensagesParticipation->delete();
            RegistroMensaje::dispatch((string)$chatParticipation->id_chat_conversation);
            return responseJSON(null, 200, 'Chat Conversation deleted successfully.');
        } catch (\Exception $e) {
            return responseJSON(null, 400, $e->getMessage());
        }
    }
}
