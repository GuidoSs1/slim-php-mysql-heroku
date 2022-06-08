<?php

class Logger
{
    public static function LogOperacion($request, $handler)
    {
        $requestType=$request->getMethod();
        $response=$handler->handle($request);
        $response->getBody()->write("Hola, la peticion se hizo con " . $requestType);
        return $response;

    }

    public static function VerificadorDeCredenciales($request, $handler)
    {
        $requestType=$request->getMethod();
        $response=$handler->handle($request);
        if($requestType == 'GET'){
            $response->getBody()->write("Metodo: " . $requestType . " no verificar.");
        }else{
            $response->getBody()->write("Metodo: " . $requestType . " verificar.");
            $dataParseada = $request->getParsedBody();//Devuelve el body parseado en un array asociativo
            $nombre = $dataParseada['nombre'];
            $perfil = $dataParseada['perfil'];
            if($perfil == 'admin'){
                $response->getBody()->write("Bienvenido ".$nombre);
            }else{
                $response->getBody()->write("Usuario no autorizado");
            }
        }
        
        return $response;

    }
}