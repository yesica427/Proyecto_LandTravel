<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;

final class AuthMiddleware{
    
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function signUpValidate() : void{
        $emailValidator = Validator::key('email', 
            Validator::allOf(
                Validator::notEmpty(),
                Validator::stringType(),
                Validator::email()
            ))->setName('email');
        
        $passwordValidator = Validator::key('password',
            Validator::allOf(
                Validator::notEmpty(),
                Validator::stringType()
            ))->setName('password');
        
        $validator = Validator::allOf($emailValidator, $passwordValidator);
        $validator->assert($this->request->getParsedBody());
    }

    public function email() : string {
        return $this->request->getParsedBody()['email'];
    }

    public function hashedPassword() : string {
        return password_hash($this->request->getParsedBody()['password'], PASSWORD_DEFAULT);
    }
}