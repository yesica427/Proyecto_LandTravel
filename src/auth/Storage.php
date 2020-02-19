<?php

namespace App\Auth;

use App\Usr\User;
use App\Mail\Mailer;
use React\MySQL\QueryResult;
use function React\Promise\reject;
use function React\Promise\resolve;
use React\Promise\PromiseInterface;
use React\MySQL\ConnectionInterface;

use App\Auth\Exceptions\UserNotFound;
use App\Auth\Exceptions\EmailDoesntExist;
use App\Auth\Exceptions\EmailAlreadyTaken;

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
                    return $this->connection->query('call landtravel.nuevo_usuario(?, ?, ?, ?);', [$password, $fname, $lname, $email])
                        ->then(
                            function() use ($email, $password)
                            {
                                return $this->connection->query('SELECT id, usuario FROM usuarios WHERE correo = ?', [$email])
                                    ->then(
                                        function (QueryResult $result) use ($password)
                                        {
                                            if (!empty($result->resultRows))
                                            {
                                                $id = $result->resultRows[0]['id'];
                                                $usuario = $result->resultRows[0]['usuario'];
                                                $user = new User ((int)$id, $usuario, $password);

                                                var_dump($user);
                                                return $user;
                                            }
                                        }
                                    );
                            }
                        );
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
                   return $this->getUserByEmail($email)
                    ->then(
                        function(Array $user) use ($email) {
                            $id = (int) $user["id"];
                            $usuario = $user["usuario"];
                            $contraseña = $user["contraseña"];
                            Mailer::lostPasswordEmail($email, $usuario, $contraseña);
                            return new User($id, $usuario, $contraseña); 
                        }
                    );
                }
            );
    }

    public function getUserByEmail($email) : PromiseInterface
    {
        return $this->connection->query('SELECT id, usuario, contraseña from usuarios WHERE correo = ?', [$email])
            ->then(
                function(QueryResult $result)
                {
                    if (!empty($result->resultRows))
                    {
                        return $result->resultRows[0];
                    }else{
                        throw new UserNotFound();
                    }
                }
            );
    }

    public function login(string $usuario)
    {
        return $this->connection->query('SELECT id, usuario, contraseña from usuarios WHERE usuario = ?', [$usuario])
            ->then(
                function(QueryResult $result)
                {
                    if (!empty($result->resultRows))
                    {
                        $id = (int) $result->resultRows[0]['id'];
                        $usuario = $result->resultRows[0]['usuario'];
                        $contraseña = $result->resultRows[0]['contraseña'];
                        return new User($id, $usuario, $contraseña);
                    }else{
                        throw new UserNotFound();
                    }
                }
            );
    }

    public function refresh()
    {
    }
}
