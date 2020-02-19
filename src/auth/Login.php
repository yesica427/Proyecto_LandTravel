<?php

namespace App\Auth;

use Exception;
use App\Usr\User;
use Firebase\JWT\JWT;
use App\Responses\JsonResponse;
use App\Auth\Exceptions\UserNotFound;
use Psr\Http\Message\ServerRequestInterface;

final class Login
{
    private $storage;
    private $key;

    function __construct(Storage $storage, string $jwt)
    {
        $this->storage = $storage;
        $this->key = $jwt;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $input = new Input($request);
        $input->loginValidate();
        
        return $this->storage->login($input->user())
            ->then(
                function (User $user) use ($input)
                {
                    if(password_verify($input->password(), $user->contraseña)) 
                    {
                        $payload = [
                            'id' => $user->id,
                            'usuario' => $user->usuario,
                            'exp' => time() + 60 * 60
                        ];

                        $token = JWT::encode($payload, $this->key);

                        return JsonResponse::OK(['token' => $token]);
                    }

                    return JsonResponse::UNAUTHORIZED();
                }
            )
            ->otherwise(
                function (UserNotFound $exception)
                {
                    return JsonResponse::UNAUTHORIZED();
                }
            )
            ->otherwise(
                function (Exception $exception)
                {
                    return JsonResponse::INTERNAL_ERROR($exception->getMessage());
                }
            );
    }
}