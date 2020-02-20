<?php 

namespace App\Auth;

use Firebase\JWT\JWT;
use App\Responses\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

final class ProtectedRoute
{   
    private $key;
    private $middleware;

    public function __construct(string $jwt, callable $middleware)
    {
        $this->key = $jwt;
        $this->middleware = $middleware;
    }

    public function __invoke(ServerRequestInterface $request, ... $params)
    {
        if ($this->authorize($request))
        {
            return call_user_func($this->middleware, $request, $params[0]);
        }

        return JsonResponse::UNAUTHORIZED();
    }

    private function authorize(ServerRequestInterface $request) : bool
    {
        $header = $request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $header);
        if(empty($token))
        {
            return false;
        }

        return JWT::decode($token, $this->key, ['HS256']) !== null;
    }
}