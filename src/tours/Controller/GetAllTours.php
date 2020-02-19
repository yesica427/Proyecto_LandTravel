<?php

namespace App\Tours\Controller;

use App\Tours\Storage;
use Psr\Http\Message\ServerRequestInterface;

final class GetAllTours{

    private $storage;

    function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    function __invoke(ServerRequestInterface $request)
    {
        return $this->storage->getAllTours();
    }
}