<?php

namespace App\Tours;

use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;

final class Input
{
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function createTourValidate()
    {
        $nombre_validate = Validator::key('nombre',
            Validator::allOf(
                Validator::stringType(),
                Validator::notEmpty()
            ))->setName('nombre');

        $estado_validate = Validator::key('estado',
            Validator::allOf(
                Validator::stringType(),
                Validator::notEmpty()
            ))->setName('estado');

        $fecha_i_validate = Validator::key('fecha_i',
            Validator::allOf(
                Validator::date('Y-m-d'),
                Validator::notEmpty()
            ))->setName('fecha_i');
        
        $cant_p_validate = Validator::key('cant_p',
            Validator::allOf(
                Validator::intVal(),
                Validator::notEmpty()
            ))->setName('cant_p');

        $precio_validate = Validator::key('precio',
            Validator::allOf(
                Validator::floatVal(),
                Validator::notEmpty()
            ))->setName('precio');
        
        $cupo_validate = Validator::key('cupo',
            Validator::allOf(
                Validator::intVal(),
                Validator::notEmpty()
            ))->setName('cupo');    
        
        $validator = Validator::allOf($nombre_validate, $estado_validate, $fecha_i_validate, $cant_p_validate, $precio_validate, $cupo_validate);
        $validator->assert($this->request->getParsedBody());
    }

    public function updateOrCreateValidate(string $id)
    {
        $this->createTourValidate();
        $id_validate = Validator::key('id',
            Validator::allOf(
                Validator::stringType(),
                Validator::notEmpty()
            ))->setName('id');
        $validator = Validator::allOf($id_validate);
        $validator->assert(['id' => $id]);
    }

    public function name() : string {
        return $this->request->getParsedBody()['nombre'];
    }

    public function state() : string {
        return $this->request->getParsedBody()['estado'];
    }

    public function date() : string {
        return $this->request->getParsedBody()['fecha_i'];
    }

    public function number_p() : string {
        return $this->request->getParsedBody()['cant_p'];
    }

    public function price() : string {
        return $this->request->getParsedBody()['precio'];
    }

    public function cupo() : string {
        return $this->request->getParsedBody()['cupo'];
    }

    public function type() : string {
        return $this->request->getParsedBody()['tipo'];
    }
}