<?php

namespace App\Auth;

use App\Auth\Exceptions\EmailAlreadyTaken;
use React\MySQL\ConnectionInterface;
use React\MySQL\QueryResult;

use function React\Promise\reject;
use function React\Promise\resolve;

final class Storage{

    private $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function create(string $email, string $password)
    {
        return $this->checkEmail($email)
            ->then(
                function() use ($email, $password){
                    return $this->connection->query('INSERT INTO user (email, password) VALUES (?, ?)',[$email, $password]); 
                }
            );
    }

    public function checkEmail(string $email){
        return $this->connection->query(
            'SELECT 1 FROM user WHERE email = ?',
            [$email]
            )->then(
                function(QueryResult $result){
                    return empty($result->resultRows) ? resolve() : reject(new EmailAlreadyTaken());
                }
                
        );
    }

    public function login(){
    }

    public function refresh(){
    }
}