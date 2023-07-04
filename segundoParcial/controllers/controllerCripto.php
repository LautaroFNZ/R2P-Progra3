<?php

require_once "./models/cripto.php";
require_once "./utilidades/jwt.php";
require_once "./utilidades/archivos.php";


class ControllerCripto extends Cripto
{
    public function altaCripto($request,$response,$args)
    {
        $params = $request->getParsedBody();

        if(isset($params['precio']) && isset($params['nombre']) && isset($params['nacionalidad']))
        {
            $precio = $params['precio'];
            $nombre = $params['nombre'];
            $nacionalidad = $params['nacionalidad'];

            try
            {
                $cripto = new Cripto();
                $cripto->setter($precio,$nombre,$nacionalidad);
                $cripto->alta();
                $payload= json_encode(array('Mensaje'=>'Cripto dado de alta con exito'));

            }catch(Exception $e)
            {
                $payload = json_encode(array("mensaje" => $e->getMessage()));
            }

        }else $payload = 'Verifique los datos proporcionados';

        $response->getBody()->write($payload);
            return $response->withHeader('Content-Type','application/json');
    }

    public function listarCripto($request,$response,$args)
    {
        try{
            $criptos = Cripto::listar();
            $payload = json_encode(array("listaDeCriptos" => $criptos));

        }catch(Exception $e)
        {
            $payload = json_encode(array('mensaje'=> $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type','application/json');
    }

    public function traerCriptoId($request,$response,$args)
    {
        try{
            if($criptos = Cripto::buscarId($args['id_cripto']))
            {
                $payload = json_encode($criptos);

            }else $payload = json_encode(array('mensaje'=>'No encontramos la Cripto')); 
            

        }catch(Exception $e)
        {
            $payload = json_encode(array('mensaje'=> $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type','application/json');
    }

    public function traerCriptoNacionalidad($request,$response,$args)
    {
        try{
            if($criptos = Cripto::buscarNacionalidad($args['nacionalidad']))
            {
                $payload = json_encode(array("Criptos por {$args['nacionalidad']}" => $criptos));

            }else $payload = json_encode(array('mensaje'=>'No encontramos la Cripto')); 

        }catch(Exception $e)
        {
            $payload = json_encode(array('mensaje'=> $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type','application/json');
    }


   
}

?>