<?php

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
use \Dotenv\Dotenv;
use App\Auth\Storage as Users;

require 'vendor/autoload.php';

// Env, Mysql y conexion
$env = Dotenv::createImmutable(__DIR__);
$env->load();
$loop = Factory::create();
$mysql = new \React\MySQL\Factory($loop);
$uri = getenv('DB_USER'). ':' . getenv('DB_PASS') . '@'. getenv('DB_HOST'). '/' . getenv('DB_NAME');
$connection = $mysql->createLazyConnection($uri);

// Storage aka Middleware
$users = new Users($connection);


// Rutas Usuario
$routes = new RouteCollector(new Std(), new GroupCountBased());
$routes->get('/usr', new GetAllUsers());
$routes->get('/usr/{id:\d+}', new GetOneUser()); 
$routes->post('/usr/{id:\d+}', new GetOneUser());


// Rutas Auth
$routes->post('/auth/signup', new SignUp($users));


// Server
$middleware = [new ErrorHandler(), new JsonDecoder(), new Router($routes)];
$server = new Server($middleware);
$socket = new Socket\Server(8080, $loop);
$server->listen($socket);

echo 'Listening on '. str_replace('tcp', 'http', $socket->getAddress()) . PHP_EOL;
$loop->run();
?>