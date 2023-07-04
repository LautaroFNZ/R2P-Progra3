<?php

require_once "./models/venta.php";
require_once "./utilidades/jwt.php";


class ControllerVenta extends Venta
{
    public function altaVenta($request,$response,$args)
    {
        $params = $request->getParsedBody();

        if(isset($params['idCripto']) && isset($params['cantidad']))
        {
            $idCripto = $params['idCripto'];
            $cantidad = $params['cantidad'];

            try{

                if(Cripto::buscarId($idCripto))
                {
                    $header = $request->getHeaderLine('Authorization');

                    if ($header != null)
                    {
                        $token = trim(explode("Bearer", $header)[1]);
                        $datos = AutentificadorJWT::ObtenerData($token);
                        
                        if($datos->id)
                        {   
                            $venta = new Venta();

                            $venta->idCliente = $datos->id;
                            $venta->idCripto = $idCripto;
                            $venta->cantidad = $cantidad;
                            $venta->fecha = date('y-m-d');
                            $venta->fotoRef = $datos->mail . $venta->fecha . '.jpg';

                            $venta->alta();

                            $payload = json_encode(array('mensaje'=>'Venta Generada con exito!'));
                        }
            
                        }
                    
                    }else $payload = json_encode(array('mensaje'=>'Cripto no encontrada!'));

            }catch(Exception $e)
            {
                $payload = json_encode(array("mensaje" => $e->getMessage()));
            }
        }else $payload = 'Verifique los datos proporcionados';

        $response->getBody()->write($payload);
        
        return $response->withHeader('Content-Type','application/json');

    }

    public function traerVentasporNacionalidad($request,$response,$args)
    {
        try{
            
            if($ventasNacionalidad = Venta::traerVentasNacionalidad($args['nacionalidad']))
            {
                $payload = json_encode(array("Ventas de nacionalidad {$args['nacionalidad']}" => $ventasNacionalidad));

            }else $payload = json_encode(array("mensaje" => 'No se encontraron ventas entre las fechas estipuladas'));
            

        }catch(Exception $e)
        {
            $payload = json_encode(array('mensaje'=> $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type','application/json');
    
    }

    public function traerVentasNombre($request,$response,$args)
    {
        try{
            
            if($ventasxNombre = Venta::traerUsuariosNombre($args['nombre']))
            {
                $payload = json_encode(array("Usuarios que compraron {$args['nombre']}" => $ventasxNombre));

            }else $payload = json_encode(array("mensaje" => 'No se encontraron ventas entre las fechas estipuladas'));
            

        }catch(Exception $e)
        {
            $payload = json_encode(array('mensaje'=> $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type','application/json');
    
    }

    

}

?>