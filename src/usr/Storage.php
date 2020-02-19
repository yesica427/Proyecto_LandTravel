<?php

namespace App\Usr;

use App\Responses\JsonResponse;
use React\MySQL\QueryResult;
use React\MySQL\ConnectionInterface;

final class Storage
{
    private $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function getAllUsers()
    {
        return $this->connection->query('SELECT * FROM usuario')
            ->then(
                function (QueryResult $result) 
                {
                    if (isset($result->resultRows)) 
                    {
                        return JsonResponse::OK(["data" => $result->resultRows]);
                    }
                }
            );
    }

    public function getOneUser(string $id)
    {
        return $this->connection->query('SELECT usuario FROM usuario WHERE idUsuario = ? ', [$id])
            ->then(
                function (QueryResult $result) 
                {
                    if (isset($result->resultRows)) 
                    {
                        return JsonResponse::OK(["data" => $result->resultRows]);
                    }
                }
            );
    }

    public function findUser(int $id, string $email)
    {
        return $this->connection->query('SELECT email FROM test.user WHERE id = 1 OR email LIKE ? ', [$id, $email])
            ->then(
                function (QueryResult $result) 
                {
                    if (isset($result->resultRows)) 
                    {
                        return JsonResponse::OK(["data" => $result->resultRows]);
                    }
                }
            );
    }
}
