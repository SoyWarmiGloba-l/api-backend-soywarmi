<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Firebase\FirebaseToken;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Person;

//use Kreait\Firebase\Auth;


class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $token = new FirebaseToken($request->bearerToken());

        try {
            $payload = $token->verify_other(config('services.firebase.project_id'));
        } catch (\Exception $e) {
            return responseJSON(null, 401, $e->getMessage());
        }
        //dd($payload);
        if (User::where('id', $payload->authenticated_user->uid)->exists()) {
            $user = User::where('id', $payload->authenticated_user->uid)->first();
            $user->update([
                'id' => $payload->authenticated_user->uid,
                'name' => $payload->authenticated_user->displayName,
                'email' => $payload->authenticated_user->email,
                'password' => Hash::make($payload->authenticated_user->uid),
                'role' => 'Admin',
            ]);

            //$auth_login = Auth::guard('api')->loginUsingId($user->id);

            return responseJSON([], 401, 'User already exists but updated');
        }
        $user = User::create([
            'id' => $payload->authenticated_user->uid,
            'name' => $payload->authenticated_user->displayName,
            'email' => $payload->authenticated_user->email,
            'password' => Hash::make($payload->authenticated_user->uid),
            'role' => 'Admin',
        ]);

        //auth('api')->login($user);

        return responseJSON($user, 200, 'Success');
    }
    public function createGeneralPublicUser(Request $request){
        $token = new FirebaseToken($request->bearerToken());
        try {
            $payload = $token->verify_other(config('services.firebase.project_id'));
        } catch (\Exception $e) {
            return responseJSON(null, 401, $e->getMessage());
        }
        $email = $payload->authenticated_user->email;
        $user = Person::where('email', $email)->first();
        if($user==null){
            $phone=70000000;
            $user = Person::create([
                'role_id' => 3,
                'team_id' => 11,
                'name' => $request->name,
                'lastname' => $request->lastname,
                'email' => (string)$payload->authenticated_user->email,
                'password' => Hash::make($request->password),
                'birthday' => "1995-12-04",
                'phone' => $phone,
                'photo'=>""
            ]);
        }
        return responseJSON($user, 200, 'Success');
    }
    public function paswordRecoveryFirebase(Request $request){
        $email = $request->email;
        $auth = app('firebase.auth');
        $link = $auth->sendPasswordResetLink($email);
        return responseJSON($email, 200, 'Success');
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return responseJSON([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ], 200, 'Success');
    }
}
