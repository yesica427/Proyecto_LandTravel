<?php

namespace App\Core;

use App\Auth\Guard;
use \React\Socket;
use App\Auth\Login;
use App\Auth\SignUp;
use App\Core\Router;
use \React\Http\Server;
use App\Auth\LostPassword;
use App\Core\ErrorHandler;
use \React\EventLoop\Factory;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use App\Middleware\JsonDecoder;
use App\Usr\Controller\GetOneUser;
use App\Usr\Controller\GetAllUsers;
use App\Usr\Storage as UserStorage;
use App\Auth\Storage as AuthStorage;
use App\Tours\Controller\CreateTour;
use App\Tours\Controller\DeleteTour;
use App\Tours\Controller\UpdateTour;
use App\Tours\Controller\GetAllTours;
use App\Tours\Controller\GetOneTour;
use App\Tours\Storage as ToursStorage;
use FastRoute\DataGenerator\GroupCountBased;

final class ReactServer{

    private $port;
    private $uri;
    private $guard;

    private $loop;

    public function __construct(int $port = 8080, $uri)
    {
        $this->port = $port;       
        $this->uri = $uri;

        $this->loop = Factory::create();
        $mysql = new \React\MySQL\Factory($this->loop);
        $connection = $mysql->createLazyConnection($uri);

        // Middleware
        $this->guard = new Guard(getenv('JWT_KEY'));
        $auth_storage = new AuthStorage($connection);
        $users = new UserStorage($connection);
        $tours = new ToursStorage($connection);

        // Creacion de rutas
        $this->routes = new RouteCollector(new Std(), new GroupCountBased());

        // Rutas Usuario
        $this->routes->addGroup('/users', function ()  use($users) {
            $this->routes->get('', $this->guard->protect(new GetAllUsers($users)));
            $this->routes->get('/{id:\d+}', $this->guard->protect(new GetOneUser($users)));
        });
        
        // Rutas Tours
        $this->routes->addGroup('/tours', function ()  use($tours) {
            $this->routes->get('', new GetAllTours($tours));
            $this->routes->get('/{id:\d+}', new GetOneTour($tours));
            $this->routes->delete('/{id:\d+}', $this->guard->protect(new DeleteTour($tours)));
            $this->routes->post('', $this->guard->protect(new CreateTour($tours)));
            $this->routes->put('/{id:\d+}', $this->guard->protect(new UpdateTour($tours)));
        });

        // Rutas Auth
        $this->routes->post('/auth/signup', new SignUp($auth_storage));
        $this->routes->post('/auth/login', new Login($auth_storage, getenv('JWT_KEY')));
        $this->routes->post('/auth/lost', new LostPassword($auth_storage));
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