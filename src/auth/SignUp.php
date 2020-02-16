<?php

namespace App\Auth;

use App\Auth\Exceptions\EmailAlreadyTaken;
use Exception;
use App\Auth\Storage;
use App\Middleware\AuthMiddleware;
use App\Responses\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

final class SignUp{
    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(ServerRequestInterface $request){
        $input = new AuthMiddleware($request);
        $input->signUpValidate();

        return $this->storage->create($input->email(), $input->hashedPassword())
            ->then(
                function (){
                    return JsonResponse::CREATED();
                })

            ->otherwise(
                function(EmailAlreadyTaken $exception){
                    return JsonResponse::BAD_REQUEST('Email is taken');
                })

            ->otherwise(
                function (Exception $exception){
                    return JsonResponse::INTERNAL_ERROR($exception->getMessage());
                }
        );
    }

   
}