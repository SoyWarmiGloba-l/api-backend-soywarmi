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


class ChatMessagesParticipation extends Controller
{
    public function obtenerChatMessagesConversation(Request $request,$chat): JsonResponse
    {
        $id_chat_conversation = $chat;
        $results = DB::table('chat_mensages_participation as cmp')
        ->select('cmp.content', 'cmp.created_at', 'u.id','u.email')
        ->join('chat_participations as cp', 'cp.id_chat_participation', '=', 'cmp.id_chat_participation')
        ->join('users as u', 'cp.id_user', '=', 'u.id')
        ->join('chat_conversations as cc', 'cc.id_chat_conversation', '=', 'cp.id_chat_conversation')
        ->where('cc.id_chat_conversation', '=', $id_chat_conversation)
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
        $id_chat_participation = DB::table('chat_participations as cp')
        ->select('cp.id_chat_participation')
        ->where('cp.id_chat_conversation', '=', (string)$chat)
        ->where('cp.id_user', '=', $payload->authenticated_user->uid)
        ->get();
        
        $fecha_actual_gmt_4 = Carbon::now('GMT-4')->toDateTimeString();
        $contenido = $request->input('content');

        $resultado_insercion=DB::table('chat_mensages_participation')->insert([
            'id_chat_participation' => $id_chat_participation[0]->id_chat_participation,
            'content' => (string)$contenido,
            'read' => 0,
            'created_at' => (string)$fecha_actual_gmt_4,
            'updated_at' => (string)$fecha_actual_gmt_4,
        ]);
        if ($resultado_insercion) {
            //RespuestaReceptor::dispatch('c1fa8fb1-8598-4824-aeb5-fcc05c54ca11', ['order' => 123]);
            MensajesSinLeer::dispatch($payload->authenticated_user->uid);
            RegistroMensaje::dispatch((string)$chat);
        } 
        return response()->json(['message' => 'Mensaje insertado correctamente.'], 200);
    }
}
