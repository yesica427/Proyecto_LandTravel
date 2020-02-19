<?php

namespace App\Usr\Controller;

use Psr\Http\Message\ServerRequestInterface;
use App\Templates\ControllerTemplate;
use App\Usr\Storage;

final class GetOneUser
{
    private $storage;

    function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    function __invoke(ServerRequestInterface $request, string $id)
    {
        return $this->storage->getOneUser($id);
    }
}