<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once "./models/cripto.php";

class MWVerificarExistenciaCripto
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $params = $request->getParsedBody();


        if(isset($params['precio']) && isset($params['nombre']) && isset($params['nacionalidad']))
        {
            $nombre = $params['nombre'];

            if(!Cripto::buscarNombre($nombre))
            {
                $response = $handler->handle($request);
            }else{
                $response->getBody()->write(json_encode(array('mensaje'=>"Esta cripto ya fue dada de alta!")));
            }

        }else{
            $response->getBody()->write("Verifique los parametros ingresados");
        }


        return $response;
    }
}