<?php

namespace App\Auth;

use App\Responses\JsonResponse;
use Exception;
use React\MySQL\ConnectionInterface;

final class Storage{

    private $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function create(string $name, string $password)
    {
        return $this->connection->query(
            'INSERT INTO 
                user (name, password) 
            VALUES 
                (?, ?)',
            [$name, $password]
            )->then(
                function (){
                    return JsonResponse::CREATED();
                },
                
                function (Exception $exception){
                    return JsonResponse::INTERNAL_ERROR($exception->getMessage());
                }
        );
    }
}