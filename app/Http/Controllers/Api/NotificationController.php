<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifications;
use App\Models\Person;

use App\Models\PeopleNotifications;
use App\Firebase\FirebaseToken;
use App\Events\RegistroNotificacion;


class NotificationController extends Controller
{
    //
    public function showMyNotificationsNotRead(Request $request)
    {
        $token = new FirebaseToken($request->bearerToken());

        try {
            $payload = $token->verify_other(config('services.firebase.project_id'));
        } catch (\Exception $e) {
            return responseJSON(null, 401, $e->getMessage());
        }
        $email = $payload->authenticated_user->email;
        $userId = Person::where('email', $email)->first()->value('id');

        return responseJSON(PeopleNotifications::with('notifications')->where('id_people', $userId)->get(),200,'Notifications obtained');
    }
    public function deleteNotificationOfMyListNotificacion(Request $request,Notifications $notification)
    {
        try {
            $token = new FirebaseToken($request->bearerToken());
            $payload = $token->verify_other(config('services.firebase.project_id'));
            $email = $payload->authenticated_user->email;
            $userId = Person::where('email', $email)->first()->value('id');
            $peopleNotifications = PeopleNotifications::where('id_people', $userId)->where('id_notifications', $notification->id)->first();
            $peopleNotifications->delete();
            RegistroNotificacion::dispatch((string)($userId));
            return responseJSON("ok", 200, 'Publication deleted successfully');
        } catch (\Exception $exception) {
            return responseJSON(null, 500, $exception->getMessage());
        }
    }
}
