<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MWAcceso {
  private $userTypes = [
      "Admin", "Mozo", "Cocinero", "Bartender", "Cervecero"
  ];

  public function validateToken($request, $rHandler)
  {
    $header = $request->getHeaderLine('Authorization');
    $response = new Response();
    if (!empty($header)) {
      $token = trim(explode("Bearer", $header)[1]);
      AuthJWT::VerificarToken($token);
      $response = $rHandler->handle($request);
    } else {
      $response->getBody()->write(json_encode(array("Token error" => "You need the token")));
      $response = $response->withStatus(401);
    }
    return  $response->withHeader('Content-Type', 'application/json');
  }

  public function isAdmin($request, $handler)
  {
    $header = $request->getHeaderLine('authorization');
    $response = new Response();
    if (!empty($header)) {
      $token = trim(explode("Bearer", $header)[1]);
      $data = AuthJWT::ObtenerData($token);
      
      if ($data->User_Type == 'Admin') {
        $response = $handler->handle($request);
      } else {
        $response->getBody()->write(json_encode(array("error" => "Only admin has access")));
        $response = $response->withStatus(401);
      }
    } else {
      $response->getBody()->write(json_encode(array("Admin error" => "You need the token as Admin")));
      $response = $response->withStatus(401);
    }

    return $response->withHeader('Content-Type', 'application/json');
  }

  public function isEmpleado($request, $handler)
  {
    $header = $request->getHeaderLine('authorization');
    $response = new Response();
    try {
      if (!empty($header)) {
        $token = trim(explode("Bearer", $header)[1]);
        $data = AuthJWT::ObtenerData($token);
        if (in_array($data->User_Type, $this->userTypes)) {
          if ($data->User_Type != "Admin") {
            $response = $handler->handle($request);
          }
        } else {
          $response->getBody()->write(json_encode(array("error" => "Solo personal autorizado")));
          $response = $response->withStatus(401);
        }
      } else {
        $response->getBody()->write(json_encode(array("error" => "Necesitas el token")));
        $response = $response->withStatus(401);
      }
    } catch (\Throwable $th) {
      echo $th->getMessage();
    }
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function isBartender($request, $handler)
  {
    $header = $request->getHeaderLine('authorization');
    $response = new Response();
    if (!empty($header)) {
      $token = trim(explode("Bearer", $header)[1]);
      $data = AuthJWT::ObtenerData($token);
      if ($data->User_Type == "Bartender" 
      || $data->User_Type == "Admin") {
        $response = $handler->handle($request);
      } else {
        $response->getBody()->write(json_encode(array("error" => "Solo Bartender y Admin tienen acceso")));
        $response = $response->withStatus(401);
      }
    } else {
      $response->getBody()->write(json_encode(array("Admin error" => "Necesitas el token como Bartender o Admin")));
      $response = $response->withStatus(401);
    }

    return $response->withHeader('Content-Type', 'application/json');
  }

  public function isCocinero($request, $handler)
  {
    $header = $request->getHeaderLine('authorization');
    $response = new Response();
    if (!empty($header)) {
      $token = trim(explode("Bearer", $header)[1]);
      $data = AuthJWT::ObtenerData($token);
      if ($data->User_Type == "Cocinero"
      || $data->User_Type == "Admin") {
        $response = $handler->handle($request);
      } else {
        $response->getBody()->write(json_encode(array("error" => "Solo Cocinero y Admin tienen acceso")));
        $response = $response->withStatus(401);
      }
    } else {
      $response->getBody()->write(json_encode(array("Admin error" => "Necesitas un token como Cocinero o Admin")));
      $response = $response->withStatus(401);
    }

    return $response->withHeader('Content-Type', 'application/json');
  }

  public function isCervecero($request, $handler)
  {
    $header = $request->getHeaderLine('authorization');
    $response = new Response();
    if (!empty($header)) {
      $token = trim(explode("Bearer", $header)[1]);
      $data = AuthJWT::ObtenerData($token);
      if ($data->User_Type == "Cervecero"
      || $data->User_Type == "Admin") {
        $response = $handler->handle($request);
      } else {
        $response->getBody()->write(json_encode(array("error" => "Solo Cervecero o Admin tienen acceso")));
        $response = $response->withStatus(401);
      }
    } else {
      $response->getBody()->write(json_encode(array("Admin error" => "Necesitas un token como Cervecero o Admin")));
      $response = $response->withStatus(401);
    }

    return $response->withHeader('Content-Type', 'application/json');
  }

  public function isMozo($request, $handler)
  {
    $header = $request->getHeaderLine('authorization');
    $response = new Response();
    if (!empty($header)) {
      $token = trim(explode("Bearer", $header)[1]);
      $data = AuthJWT::ObtenerData($token);
      if ($data->User_Type == "Mozo"
      || $data->User_Type == "Admin") {
        $response = $handler->handle($request);
      } else {
        $response->getBody()->write(json_encode(array("error" => "Solo Mozo o Admin tienen acceso")));
        $response = $response->withStatus(401);
      }
    } else {
      $response->getBody()->write(json_encode(array("Admin error" => "Necesitas un token como Mozo o Admin")));
      $response = $response->withStatus(401);
    }

    return $response->withHeader('Content-Type', 'application/json');
  }
}
?>