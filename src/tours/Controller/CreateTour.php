<?php

namespace App\Tours\Controller;

use Exception;
use App\Tours\Input;
use App\Tours\Storage;
use App\Responses\JsonResponse;
use App\Tours\Exceptions\TipoDoesntExist;
use App\Tours\Tour;
use Psr\Http\Message\ServerRequestInterface;

final class CreateTour
{
    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $input = new Input($request);
        $input->createTourValidate();
        
        return $this->storage->createTour($input->name(), $input->date(), $input->state(),
                                            (int) $input->number_p(), (float) $input->price(),
                                            (int) $input->cupo(), $input->type())
            ->then(
                function (){
                    return JsonResponse::CREATED();
                })

            ->otherwise(
                function(TipoDoesntExist $exception){
                    return JsonResponse::BAD_REQUEST('No se encontro el tipo');
                })

            ->otherwise(
                function (Exception $exception){
                    return JsonResponse::INTERNAL_ERROR($exception->getMessage());
                }
            );
    }
}