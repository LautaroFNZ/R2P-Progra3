<?php

require_once "./models/usuario.php";
require_once "./utilidades/jwt.php";


class ControllerUsuario extends Usuario
{
    public function login($request,$response,$args)
    {
        $parametros = $request->getParsedBody();

        $usuario = new Usuario();
        $usuario->mail = $parametros['mail'];
        $usuario->clave = $parametros['clave'];

        if($usuarioEncontrado = Usuario::buscarMail($usuario->mail))
        { 
            if($usuario->mail == $usuarioEncontrado->mail)
            {
                if($usuario->clave == $usuarioEncontrado->clave)
                {
                    $info = array('id'=> $usuarioEncontrado->id,'mail'=>$usuarioEncontrado->mail,'tipo'=>$usuarioEncontrado->tipo);
                    $token = AutentificadorJWT::CrearToken($info);
                    $payload = json_encode(array('jwt' => $token));
                    $response->getBody()->write($payload);
            
                }else $response->getBody()->write('Verifique la clave ingresada para continuar');

            }else $response->getBody()->write('Verifique el usuario ingresado para continuar');
        
        }else{
            $response->getBody()->write('Usuario no encontrado');
        }
        

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function registrar($request,$response,$args)
    {
        $parametros = $request->getParsedBody();

        if(isset($parametros['mail']) && isset($parametros['clave']) && isset($parametros['tipo']))
        {  
            $usuario = new Usuario();

            $mail = $parametros['mail'];
            $clave = $parametros['clave'];
            $tipo = $parametros['tipo'];
            
            try
            {
                $usuario->setter($mail,$tipo,$clave);

            if(!Usuario::buscarMail($usuario->mail))
            { 
                $usuario->alta();
                $payload = json_encode(array('Usuario'=> Usuario::buscarMail($usuario->mail)));
                $response->getBody()->write($payload);
                
                    
            }else{
                $payload = 'Ingrese otro correo';
            }


            }catch(Exception $e)
            {
                $payload = json_encode(array("mensaje" => $e->getMessage()));
            }
        }
            
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function listarTodos($request,$response,$args)
    {
        try{
            $usuarios = Usuario::listar();
            $payload = json_encode(array("listaDeUsuarios" => $usuarios));

        }catch(Exception $e)
        {
            $payload = json_encode(array('mensaje'=> $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type','application/json');
    }

   
}

?>