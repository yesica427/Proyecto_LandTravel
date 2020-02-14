<?php

namespace App\Usr\Controller;

use App\Responses\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;

final class GetOneUser{

    function __invoke(ServerRequestInterface $request, string $id)
    {
        $User = [
            'name' => $request->getParsedBody()['name']
        ];

        return JsonResponse::OK(['message' => "GET ONE USER {$id}", 'user' => $User]);
    }
}