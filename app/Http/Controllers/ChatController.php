<?php

namespace App\Http\Controllers;

use App\Models\Person;

class ChatController extends Controller
{
    public function view()
    {
        $persona = Person::where('id', 2)->first();
        dd($persona->getParticipantDetailsAttribute());

        return responseJSON($persona->getParticipantDetailsAttribute(), 200, 'ok');
    }
}
