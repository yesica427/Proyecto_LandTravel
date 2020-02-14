<?php

namespace App\Auth;

use App\Auth\Storage;
use Psr\Http\Message\ServerRequestInterface;

final class SignUp{

    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(ServerRequestInterface $request){
        $name = $request->getParsedBody()['name'];
        $password = password_hash($request->getParsedBody()['password'], PASSWORD_DEFAULT);

        return $this->storage->create($name, $password);
    }
}