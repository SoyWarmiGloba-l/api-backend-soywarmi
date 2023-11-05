<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Firebase\FirebaseToken;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Auth::viaRequest('firebase', function (Request $request) {
            $token = $request->bearerToken();

            try {
                $payload = (new FirebaseToken($token))->verify(
                    config('services.firebase.project_id')
                );

                return User::find($payload->user_id);
            } catch (\Exception $e) {
                return null;
            }
        });
    }
}
