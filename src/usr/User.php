<?php

namespace App\Usr;

final class User
{
    public $id;
    public $usuario;
    public $contraseña;
    
    public function __construct(int $id_user, $user, $pass)
    {
        $this->id = $id_user;
        $this->usuario = $user;
        $this->contraseña = $pass;
    }

    public function toArray() : Array
    {
        return [
            'id' => $this->id,
            'usuario' => $this->usuario,
            'contraseña' => $this->contraseña
        ];
    }
}