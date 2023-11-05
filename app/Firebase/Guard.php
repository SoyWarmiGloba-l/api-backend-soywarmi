<?php

namespace App\Firebase;

use Kreait\Firebase\JWT\Error\IdTokenVerificationFailed;
use Kreait\Firebase\JWT\IdTokenVerifier;
use App\Firebase\User;

class Guard
{
    public function user($request)
    {
        $verifier = IdTokenVerifier::createWithProjectId(config('services.firebase.project_id'));
        $token = $request->bearerToken();
        try {
            $token = $verifier->verifyIdToken($token);
            return new User($token);
        } catch (IdTokenVerificationFailed $e) {
            echo $e->getMessage();
            // Example Output:
            // The value 'eyJhb...' is not a verified ID token:
            // - The token is expired.
            exit;
        }
    }
}
