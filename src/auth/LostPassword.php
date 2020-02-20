<?php

namespace App\Auth;

use Exception;
use App\Mail\Mailer;
use React\MySQL\QueryResult;
use App\Responses\JsonResponse;
use App\Auth\Exceptions\EmailDoesntExist;
use App\Usr\User;
use Psr\Http\Message\ServerRequestInterface;

final class LostPassword
{
    private $storage;

    function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    function __invoke(ServerRequestInterface $request)
    {
        $input = new Input($request);
        $input->emailValidate();

        return $this->storage->lostPassword($input->email())
            ->then(
                function (User $user) {
                    return JsonResponse::OK(['message' => 'Se han enviado las credenciales, revise su correo']);
                })

            ->otherwise(
                function(EmailDoesntExist $exception){
                    return JsonResponse::BAD_REQUEST('Ese correo no existe en la base de datos');
                })

            ->otherwise(
                function (Exception $exception){
                    return JsonResponse::INTERNAL_ERROR($exception->getMessage());
                }
            );
    }
}