<?php

namespace App\Core;

use App\Auth\SignUp;
use App\Core\Router;
use App\Core\ErrorHandler;
use App\Middleware\JsonDecoder;
use App\Usr\Controller\GetOneUser;
use App\Usr\Controller\GetAllUsers;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use \React\Http\Server;
use \React\EventLoop\Factory;
use \React\Socket;
use App\Auth\Storage as Users;

final class ReactServer{

    private $port;
    private $uri;

    private $loop;

    public function __construct(int $port = 8080, $uri)
    {
        $this->port = $port;       
        $this->uri = $uri;

        $this->loop = Factory::create();
        $mysql = new \React\MySQL\Factory($this->loop);
        $connection = $mysql->createLazyConnection($uri);
        $users = new Users($connection);

        // Rutas Usuario
        $this->routes = new RouteCollector(new Std(), new GroupCountBased());
        $this->routes->get('/usr', new GetAllUsers());
        $this->routes->get('/usr/{id:\d+}', new GetOneUser()); 

        // Rutas Auth
        $this->routes->post('/auth/signup', new SignUp($users));
           
    }

    public function loop(){

        // Server
        $middleware = [new ErrorHandler(), new JsonDecoder(), new Router($this->routes)];
        $server = new Server($middleware);
        $socket = new Socket\Server($this->port, $this->loop);
        $server->listen($socket);

        // Loop
        echo 'Listening on '. str_replace('tcp', 'http', $socket->getAddress()) . PHP_EOL;
        $this->loop->run();
    }
}
?>