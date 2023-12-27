<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function obtenerUsuarios(Request $request,$busqueda): JsonResponse
    {
        $token = new FirebaseToken($request->bearerToken());

        try {
            $payload = $token->verify_other(config('services.firebase.project_id'));
        } catch (\Exception $e) {
            return responseJSON(null, 401, $e->getMessage());
        }
        $users = DB::table('users')
        ->select('email', 'id')
        ->where('id', '!=', $payload->authenticated_user->uid)
        ->where('email', 'like', '%'.$busqueda.'%')
        ->get();

        return responseJSON($users, 200, 'ChatConversations fetched successfully.');
    }
}
