<?php

namespace App\Usr\Controller;

use App\Usr\Storage;
use Psr\Http\Message\ServerRequestInterface;

final class GetAllUsers
{
    private $storage;

    function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    function __invoke(ServerRequestInterface $request)
    {
        return $this->storage->getAllUsers();
    }
}
