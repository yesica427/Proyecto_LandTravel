<?php

namespace App\Auth;

use App\Auth\Exceptions\EmailAlreadyTaken;
use App\Mail\Mailer;
use Exception;
use App\Responses\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

final class SignUp {

    private $storage;

    function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(ServerRequestInterface $request){
        $input = new Input($request);
        $input->signUpValidate();

        return $this->storage->create($input->fname(), $input->lname(), $input->hashedPassword(), $input->email())
            ->then(
                function () use ($input) {
                    $mail = Mailer::confirmationEmail($input->email(), '');
                    return JsonResponse::CREATED();
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