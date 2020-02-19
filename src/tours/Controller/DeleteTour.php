<?php

namespace App\Tours\Controller;

use App\Responses\JsonResponse;
use App\Tours\Storage;
use Psr\Http\Message\ServerRequestInterface;

final class DeleteTour
{
    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(ServerRequestInterface $request, string $id)
    {
        return $this->storage->deleteTour($id)
            ->then(
                function()
                {
                    return JsonResponse::ACCEPTED();
                }
            );
    }
}