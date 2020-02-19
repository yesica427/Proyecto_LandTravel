<?php

namespace App\Auth;

use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;

final class Input{
    
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function signUpValidate() : void {

        $fnameValidator = Validator::key('pnombre',
            Validator::allOf(
                Validator::notEmpty(),
                Validator::noWhitespace(),
                Validator::stringType()
            ))->setName('pnombre');

        $lnameValidator = Validator::key('papellido',
            Validator::allOf(
                Validator::notEmpty(),
                Validator::noWhitespace(),
                Validator::stringType()
            ))->setName('papellido'); 

        $emailValidator = Validator::key('correo', 
            Validator::allOf(
                Validator::notEmpty(),
                Validator::stringType(),
                Validator::email()
            ))->setName('correo');
        
        $passwordValidator = Validator::key('contraseña',
            Validator::allOf(
                Validator::notEmpty(),
                Validator::stringType()
            ))->setName('contraseña');
        
        $validator = Validator::allOf($fnameValidator, $lnameValidator, $emailValidator, $passwordValidator);
        $validator->assert($this->request->getParsedBody());
    }

    public function lostPasswordValidate() : void {

        $emailValidator = Validator::key('correo', 
            Validator::allOf(
                Validator::notEmpty(),
                Validator::stringType(),
                Validator::email()
            ))->setName('correo');
        
        $validator = Validator::allOf($emailValidator);
        $validator->assert($this->request->getParsedBody());
    }

    public function fname() : string {
        return $this->request->getParsedBody()['pnombre'];
    }

    public function lname() : string {
        return $this->request->getParsedBody()['papellido'];
    }

    public function email() : string {
        return $this->request->getParsedBody()['correo'];
    }

    public function hashedPassword() : string {
        return password_hash($this->request->getParsedBody()['contraseña'], PASSWORD_DEFAULT);
    }
}