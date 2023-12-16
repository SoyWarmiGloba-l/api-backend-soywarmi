<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje
{
    public $contenido;
    public $destino;

    public function __construct($contenido, $destino)
    {
        $this->contenido = $contenido;
        $this->destino = $destino;
    }

}
