<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/vendor/autoload.php';

//Controllers
require_once "./controllers/controllerUsuario.php";
require_once "./controllers/controllerCripto.php";
require_once "./controllers/controllerVenta.php";

//MW
require_once "./MW/MWverificarAdmin.php";
require_once "./MW/MWverificarTokenValido.php";
require_once "./MW/MWverificarExistenciaCripto.php";

// Instantiate App
$app = AppFactory::create();


// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

date_default_timezone_set("America/Argentina/Buenos_Aires");

// Routes

//Usuarios

$app->group('/usuarios', function (RouteCollectorProxy $group){
    $group->post('/login', \ControllerUsuario::class . ':login');
    $group->post('/registrar', \ControllerUsuario::class . ':registrar')->add(new MWVerificarAdmin());
    $group->get('[/]', \ControllerUsuario::class . ':listarTodos')->add(new MWVerificarAdmin());
});

$app->group('/cripto', function (RouteCollectorProxy $group){
    $group->post('/alta', \ControllerCripto::class . ':altaCripto')
    ->add(new MWverificarExistenciaCripto())
    ->add(new MWVerificarAdmin());
    $group->get('[/]', \ControllerCripto::class . ':listarCripto');
    $group->get('/traerId/{id_cripto}', \ControllerCripto::class . ':traerCriptoId')->add(new MWVerificarTokenValido());
    $group->get('/traerNacionalidad/{nacionalidad}', \ControllerCripto::class . ':traerCriptoNacionalidad');
});

$app->group('/venta', function (RouteCollectorProxy $group){
    $group->post('/alta', \ControllerVenta::class . ':altaVenta')->add(new MWVerificarTokenValido());
    $group->get('/traerNacionalidad/{nacionalidad}', \ControllerVenta::class . ':traerVentasporNacionalidad')->add(new MWVerificarAdmin());;
    $group->get('/traerNombre/{nombre}', \ControllerVenta::class . ':traerVentasNombre')->add(new MWVerificarAdmin());;
});


$app->run();
