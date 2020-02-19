<?php

namespace App\Tours;

final class Tour
{
    private $id;
    private $nombre;
    private $fecha_i;
    private $cantidad_personas;
    private $precio;
    private $cupo;
    private $estado;
    private $descripcion;

    public function __construct(string $nombre_tour, string $fecha_inicio, string $cantidad_personas,
                                float $precio, int $cupo, string $estado, string $descripcion = "", string $id = "")
    {
        $this->nombre = $nombre_tour;
        $this->fecha_i = $fecha_inicio;
        $this->cantidad_personas = $cantidad_personas;
        $this->precio = $precio;
        $this->cupo = $cupo;
        $this->estado = $estado;
        $this->descripcion = $descripcion;
        $this->$id = $id;
    }
}