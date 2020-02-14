<?php

namespace App\Responses;

final class JsonResponse extends \React\Http\Response{

    function __construct(int $code, $data = null)
    {
        $data = $data ? json_encode($data) : null;

        parent::__construct(
            $code,
            ['Content-type' => 'application/json'],
            $data
        );
    }

    public static function OK(Array $data = []) : self{
        return new self(200, $data);
    }

    public static function NOT_FOUND(Array $data = []) : self{
        return new self(404, ['message' => 'Not found']);
    }

    public static function METHOD_NOT_ALLOWED() : self{
        return new self(405, ['message' => 'Method not allowed']);
    }

    public static function CREATED() : self{
        return new self(201);
    }

    public static function INTERNAL_ERROR($data) : self{
        return new self(500, json_encode($data));
    }
}