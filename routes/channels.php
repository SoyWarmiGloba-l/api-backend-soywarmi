<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('mensajes.{uuid}', function ($user, $uuid) {
    // As I set the user in $request->user()
    // Laravel already sends this user's data in the $user parameter
    // return $user->uuid === $uuid;
    return true;
});

Broadcast::channel('mensajes', function ($user) {
    // As I set the user in $request->user()
    // Laravel already sends this user's data in the $user parameter
    // return $user->uuid === $uuid;
    return true;
});