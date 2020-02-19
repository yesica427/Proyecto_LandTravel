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

    public function createValidate()
    {
        $nombre_validator = Validator::key(
            'nombre',
            Validator::allOf(
                Validator::stringType(),
                Validator::notEmpty()
            )
        )->setName('nombre');

        $estado_validator = Validator::key(
            'estado',
            Validator::allOf(
                Validator::stringType(),
                Validator::noWhitespace(),
                Validator::notEmpty()
            )
        )->setName('estado');

        $fecha_i_validator = Validator::key(
            'fecha_i',
            Validator::allOf(
                Validator::date('Y-m-d'),
                Validator::notEmpty()
            )
        )->setName('fecha_i');

        $cant_p_validator = Validator::key(
            'cant_p',
            Validator::allOf(
                Validator::intVal(),
                Validator::noWhitespace(),
                Validator::notEmpty()
            )
        )->setName('cant_p');

        $precio_validator = Validator::key(
            'precio',
            Validator::allOf(
                Validator::floatVal(),
                Validator::noWhitespace(),
                Validator::notEmpty()
            )
        )->setName('precio');

        $cupo_validator = Validator::key(
            'cupo',
            Validator::allOf(
                Validator::intVal(),
                Validator::noWhitespace(),
                Validator::notEmpty()
            )
        )->setName('cupo');

        $validator = Validator::allOf($nombre_validator, $estado_validator, $fecha_i_validator, $cant_p_validator, $precio_validator, $cupo_validator);
        $validator->assert($this->request->getParsedBody());
    }

    public function updateValidate($id)
    {
        $this->createValidate();
        $id_validator = Validator::key(
            'id',
            Validator::allOf(
                Validator::intVal(),
                Validator::noWhitespace(),
                Validator::notEmpty()
            )
        )->setName('id');
        $validator = Validator::allOf($id_validator);
        $validator->assert(['id' => $id]);
    }

    public function name(): string
    {
        return $this->request->getParsedBody()['nombre'];
    }

    public function state(): string
    {
        return $this->request->getParsedBody()['estado'];
    }

    public function date(): string
    {
        return $this->request->getParsedBody()['fecha_i'];
    }

    public function number_p(): string
    {
        return $this->request->getParsedBody()['cant_p'];
    }

    public function price(): string
    {
        return $this->request->getParsedBody()['precio'];
    }

    public function cupo(): string
    {
        return $this->request->getParsedBody()['cupo'];
    }

    public function type(): string
    {
        return $this->request->getParsedBody()['tipo'];
    }
}
