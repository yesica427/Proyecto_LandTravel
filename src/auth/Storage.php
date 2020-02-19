<?php

namespace App\Auth;

use App\Auth\Exceptions\EmailAlreadyTaken;
use App\Auth\Exceptions\EmailDoesntExist;
use App\Mail\Mailer;
use React\MySQL\ConnectionInterface;
use React\MySQL\QueryResult;

use function React\Promise\reject;
use function React\Promise\resolve;

final class Storage
{

    private $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function create(string $fname, $lname, string $password, $email)
    {
        return $this->checkEmail($email)
            ->then(
                function (QueryResult $result) {
                    return empty($result->resultRows) ? resolve() : reject(new EmailAlreadyTaken());
                }
            )
            ->then(
                function () use ($fname, $lname, $password, $email) {
                    return $this->connection->query('call landtravel.nuevo_usuario(?, ?, ?, ?);', [$password, $fname, $lname, $email]);
                }
            );
    }

    public function checkEmail(string $email)
    {
        return $this->connection->query(
            'SELECT 1 FROM Correo WHERE correo = ?',
            [$email]
        );
    }

    public function lostPassword(string $email)
    {
        return $this->checkEmail($email)
            ->then(
                function(QueryResult $result)
                {
                    return !empty($result->resultRows) ? resolve() : reject(new EmailDoesntExist());
                } 
            )
            ->then(
                function () use($email)
                {
                    return $this->connection->query('SELECT usuario, contraseña from usuarios WHERE correo = ?', [$email])
                        ->then(
                            function(QueryResult $result) use ($email) {
                                
                                $usuario = $result->resultRows[0]["usuario"];
                                $contraseña = $result->resultRows[0]["contraseña"];
                                Mailer::lostPasswordEmail('noreply.landtravel@gmail.com', $usuario, $contraseña);
                                return; 
                            }
                        );
                }
            );
    }

    public function login()
    {
    }

    public function refresh()
    {
    }
}
