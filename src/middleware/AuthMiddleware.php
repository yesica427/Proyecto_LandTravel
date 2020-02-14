<?php

namespace App\Middleware;

use App\Responses\JsonResponse;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use React\MySQL\ConnectionInterface;
use React\Promise\PromiseInterface;
use Respect\Validation\Validator;

final class AuthMiddleware{
    
    public static function Validate(ServerRequestInterface $request) {
        $emailValidator = Validator::key('email', 
            Validator::allOf(
                Validator::notEmpty(),
                Validator::stringType(),
                Validator::email()
            ));
        
        $nacionalidadValidator = Validator::key('nacionalidad',
            Validator::allOf(
                Validator::notEmpty(),
                Validator::stringType()
            ));
    }
}