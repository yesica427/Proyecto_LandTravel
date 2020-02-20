<?php

namespace App\Responses;

final class JsonResponse extends \React\Http\Response
{

    function __construct(int $code, $data = null)
    {
        $data = $data ? json_encode($data) : null;

        parent::__construct(
            $code,
            ['Content-type' => 'application/json'],
            $data
        );
    }

    public static function OK(array $data = []): self
    {
        return new self(200, $data);
    }

    public static function NOT_FOUND(): self
    {
        return new self(404, ['message' => 'Not found']);
    }

    public static function METHOD_NOT_ALLOWED(): self
    {
        return new self(405, ['message' => 'Method not allowed']);
    }

    public static function CREATED(array $data = []): self
    {
        return new self(201, $data);
    }

    public static function ACCEPTED(): self
    {
        return new self(202);
    }

    public static function UNAUTHORIZED(): self
    {
        return new self(401);
    }

    public static function INTERNAL_ERROR($data): self
    {
        return new self(500, $data);
    }

    public static function BAD_REQUEST($data): self
    {
        return new self(400, ['errors' => $data]);
    }
}
