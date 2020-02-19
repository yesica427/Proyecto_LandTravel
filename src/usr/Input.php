<?php

namespace App\Usr;

use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;

final class Input{

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function searchValidate(string $id) {
        
    }

    /*
    public function searchValidate() : void{
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
    }*/

}