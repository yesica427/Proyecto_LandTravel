<?php

namespace App\Auth;

use Exception;
use App\Usr\User;
use App\Mail\Mailer;
use App\Responses\JsonResponse;
use App\Auth\Exceptions\EmailAlreadyTaken;
use Psr\Http\Message\ServerRequestInterface;

final class SignUp {

    private $storage;

    function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(ServerRequestInterface $request){
        $input = new Input($request);
        $input->Validate();

        return $this->storage->create($input->fname(), $input->lname(), $input->hashedPassword(), $input->email())
            ->then(
                function (User $user) use ($input) {
                    Mailer::confirmationEmail($input->email(), $user->usuario, $input->password());
                    return JsonResponse::CREATED(['message' => 'Exito','signed' => true]);
                })

            ->otherwise(
                function(EmailAlreadyTaken $exception){
                    return JsonResponse::BAD_REQUEST('El correo ya existe');
                })

            ->otherwise(
                function (Exception $exception){
                    return JsonResponse::INTERNAL_ERROR($exception->getMessage());
                }
        );
    }

   
}