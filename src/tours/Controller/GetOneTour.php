<?php

namespace App\Tours\Controller;

use App\Tours\Storage;
use Psr\Http\Message\ServerRequestInterface;

final class GetOneTour{

    private $storage;

    function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    function __invoke(ServerRequestInterface $request, string $id)
    {
        return $this->storage->getOneTour($id);
    }
}