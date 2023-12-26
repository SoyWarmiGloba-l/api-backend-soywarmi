<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\MensajesAux\StoreMensajesAuxController;
use App\Models\MensajesAuxService;
use Illuminate\Http\Request;
use App\Events\RespuestaReceptor;
use App\Models\Mensaje;

class MensajesAuxController extends Controller
{
    public function index()
    {
        $elementos = MensajesAuxService::all();

        return response()->json($elementos);
    }
    public function posts_mensajes()
    {
        return response(null, 200, ['Success']);
    }
    public function get_1()
    {
        return response(null, 200, ['Success']);
    }
    public function save(Request $request):self
    {
        $mensaje = new Mensaje('Hola, esto es un mensaje', 'DestinoEjemplo');
        RespuestaReceptor::dispatch('c1fa8fb1-8598-4824-aeb5-fcc05c54ca11', ['order' => 123]);
        return response('Ok');
    }
}
