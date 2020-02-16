<?php

namespace App\Core;

use App\Responses\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

final class ErrorHandler{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        try {
           return $next($request);
        } catch(Throwable $error){
            return JsonResponse::INTERNAL_ERROR($error->getMessage());
        }
    }
}