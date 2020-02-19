<?php

namespace App\Tours\Controller;

use Exception;
use App\Tours\Input;
use App\Tours\Storage;
use App\Responses\JsonResponse;
use App\Tours\Exceptions\TipoDoesntExist;
use App\Tours\Tour;
use Psr\Http\Message\ServerRequestInterface;

final class UpdateTour
{
    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(ServerRequestInterface $request, $id)
    {
        $input = new Input($request);
        $input->updateOrCreateValidate($id);
        
        return $this->storage->updateOrCreate($input->name(), $input->date(), $input->state(),
                                            (int) $input->number_p(), (float) $input->price(),
                                            (int) $input->cupo(), $input->type(), $id);
            
    }
}