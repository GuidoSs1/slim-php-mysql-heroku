<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';

require_once './controllers/UsuarioController.php';
require_once './controllers/EmpleadoController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/MesaController.php';

require_once './middlewares/Logger.php';
require_once './middlewares/AuthJWT.php';
require_once './middlewares/MWAcceso.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Set base path
//$app->setBasePath('/comanda');

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes
$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->get('[/]', \UsuarioController::class . ':TraerTodos');
  $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
  $group->post('[/]', \UsuarioController::class .':CargarUno');
  $group->delete('/{id}', \UsuarioController::class . ':BorrarUno');
  //$group->put('/', \UsuarioController::class . ':ModificarUno');
})->add(\MWAcceso::class . ':idAdmin');

$app->group('/empleados', function (RouteCollectorProxy $group) {
  $group->get('[/]', \EmpleadoController::class . ':TraerTodos');
  $group->get('/{id}', \EmpleadoController::class . ':TraerUno');
  $group->post('[/]', \EmpleadoController::class . ':CargarUno');
  //$group->put('/', \EmpleadoController::class . ':ModificarUno');
  $group->delete('/{id}', \EmpleadoController::class . ':BorrarUno');
})->add(\MWAcceso::class . ':isAdmin');

$app->group('/productos', function(RouteCollectorProxy $group){
  $group->get('[/]', \ProductoController::class . ':TraerTodos')->add(\MWAcceso::class . ':isEmpleado');
  $group->get('/{id}', \ProductoController::class . ':TraerUno');
  $group->post('[/]', \ProductoController::class . ':CargarUno')->add(\MWAcceso::class . ':isMozo');
  $group->put('/modificar', \ProductoController::class . ':ModificarUno')->add(\MWAcceso::class . ':isEmpleado');
  $group->delete('/{id}', \ProductoController::class . ':BorrarUno')->add(\MWAcceso::class . ':isAdmin');
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerTodos')->add(\MWAcceso::class . ':isMozo');
  $group->get('/{id}', \PedidoController::class . ':TraerUno')->add(\MWAcceso::class . 'isEmpleado');
  $group->post('[/]', \PedidoController::class . ':CargarUno')->add(\MWAcceso::class . ':isMozo');
  $group->put('/', \PedidoController::class . ':ModificarUno')->add(\MWAcceso::class . ':isEmpleado');
  $group->delete('/{id}', \PedidoController::class . ':BorrarUno')->add(\MWAcceso::class . ':isAdmin');
});

$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos')->add(\MWAcceso::class . ':isMozo');
  $group->get('/admin', \MesaController::class . ':TraerTodos')->add(\MWAcceso::class . ':isAdmin');
  //$group->get('/{id}', \MesaController::class . ':TraerUno');
  $group->post('[/]', \MesaController::class . ':CargarUno');
  $group->put('/cobrar', \MesaController::class . ':CobrarUno')->add(\MWAccess::class . ':isMozo');
  $group->put('/modificar', \MesaController::class . ':ModificarUno')->add(\MWAccess::class . ':isMozo');
  $group->put('/cerrar', \MesaController::class . ':ModificarUnoAdmin')->add(\MWAccess::class . ':isAdmin');
});



$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("Slim Framework 4 PHP");
    return $response;

});

$app->run();
