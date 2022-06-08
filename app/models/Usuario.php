<?php

require_once './db/AccesoDatos.php';
require_once './models/Empleado.php';

class Usuario{
    
    public $id;
    public $username;
    public $password;
    public $isAdmin;
    public $user_type;
    public $estado;
    public $date_init;
    public $date_end;


    public function __construct(){}

    public static function createUsuario($username, $password, $isAdmin, $user_type, $estado, $dateInit){
        $usuario = new Usuario();
        $usuario->__set("username",$username);
        $usuario->__set("password",$password);
        $usuario->setIsAdmin($isAdmin);
        $usuario->__set("user_type",$user_type);
        $usuario->__set("estado",$estado);
        $usuario->__set("date_init",$dateInit);
        return $usuario;
    }

    public function __get($property){
        if(property_exists($this,$property)){
          return $this->$property;
        }
    }
    
    public function __set($property, $value){
        if(property_exists($this,$property)){
            $this->$property = $value;
        }
    } 

    public function setIsAdmin($isAdmin){
        $this->isAdmin = $this->validateBool($isAdmin);
    }

    public function isAdmin(){
        return $this->__get("isAdmin");
    }

    private function validateBool($bool){
        return strtolower($bool) == "true";
    }
    

    public static function insertUsuario($Usuario){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (username, password, isAdmin, user_type, estado, date_init) 
        VALUES (:username, :password, :isAdmin, :user_type, :estado, :date_init)");
        $passwordHash = password_hash($Usuario->__get("password"), PASSWORD_DEFAULT);
        $query->bindValue(':username', $Usuario->__get("username"), PDO::PARAM_STR);
        $query->bindValue(':password', $passwordHash);
        $query->bindValue(':isAdmin', $Usuario->__get("isAdmin"), PDO::PARAM_INT);
        $query->bindValue(':user_type', $Usuario->__get("user_type"), PDO::PARAM_STR);
        $query->bindValue(':estado', $Usuario->__get("estado"), PDO::PARAM_STR);
        $query->bindValue(':date_init', $Usuario->__get("date_init"), PDO::PARAM_STR);
        $query->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    /*public static function insertHistoricalLogin($Usuario){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepararConsulta("INSERT INTO historical_logins (Usuario_id, username, date_login) 
        VALUES (:Usuario_id, :username, :date_login)");
        $query->bindValue(':Usuario_id', $Usuario->__get("id"), PDO::PARAM_INT);
        $query->bindValue(':username', $Usuario->__get("username"), PDO::PARAM_STR);
        $query->bindValue(':date_login', date("Y-m-d H:i:s"), PDO::PARAM_STR);
        $query->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }*/

    public static function getAllUsuarios(){

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios");
        $query->execute();
        
        return $query->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function getUsuario($empleado){

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios AS u
        JOIN empleados AS e
        ON :id = u.id");
        $query->bindValue(':id', $empleado->__get("user_id"), PDO::PARAM_INT);
        $query->execute();

        return $query->fetchObject('Usuario');
    }

    public static function getUsuarioById($id){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $Usuario = $query->fetchObject('Usuario');
        if(is_null($Usuario)){
            throw new Exception("Usuario not found");
        }
        return $Usuario;
    }

    public static function getUsuarioByUsername($username){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE username = :username");
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->execute();
        $Usuario = $query->fetchObject('Usuario');
        if(is_null($Usuario)){
            throw new Exception("Usuario not found");
        }
        return $Usuario;
    }

    public static function modificarUsuario($Usuario){

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepararConsulta("UPDATE usuarios SET username = :username, password = :password WHERE id = :id");
        try {
            $query->bindValue(':username', $Usuario->__get("username"), PDO::PARAM_STR);
            $query->bindValue(':password', $Usuario->__get("password"), PDO::PARAM_STR);
            $query->bindValue(':id', $Usuario->__get("id"), PDO::PARAM_INT);
            $query->execute();
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }

        return $query->getRowCount() > 0;
    }

    public static function eliminarUsuario($Usuario){

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepararConsulta("DELETE FROM usuarios WHERE id = :id");
        $query->bindValue(':id', $Usuario->__get("id"), PDO::PARAM_INT);
        $query->execute();

        return $query->getRowCount() > 0;
    }
}
?>