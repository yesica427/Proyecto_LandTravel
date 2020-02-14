<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;

final class JsonDecoder{

    public function __invoke(ServerRequestInterface $request, callable $next){
        if($request->getHeaderLine('Content-type') === 'application/json'){
            $request = $request->getParsedBody(
                json_decode($request->getBody()->getContents(), true)
            );
        }
        return $next($request);
    }
}