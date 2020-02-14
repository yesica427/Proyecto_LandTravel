<?php

namespace App\Usr\Controller;

use App\Responses\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;

final class GetAllUsers{

    function __invoke(ServerRequestInterface $request)
    {
        return JsonResponse::OK(['message' => "GET ALL USERS"]);
    }
}

?>