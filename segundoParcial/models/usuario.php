<?php

require_once "./utilidades/accesoDatos.php";

class Usuario{
    public $id;
    public $mail;
    public $tipo;
    public $clave;

    public function setter($mail,$tipo,$clave)
    {
        $this->mail = $mail;
        $this->tipo = $tipo;
        $this->clave = $clave;
    }

    public function alta()
    {
        $instancia = AccesoDatos::instance();
        $command = $instancia->preparer("INSERT INTO usuarios (mail,tipo,clave) VALUES (:mail,:tipo,:clave)");
        
        $command->bindValue(':mail',strtolower($this->mail),PDO::PARAM_STR);
        $command->bindValue(':tipo',strtolower($this->tipo),PDO::PARAM_STR);
        $command->bindValue(':clave',strtolower($this->clave),PDO::PARAM_STR);
        $command->execute();

        return $instancia->lastId();
    }

    public static function listar()
    {
        $instancia = AccesoDatos::instance();
        $command = $instancia->preparer("SELECT * FROM usuarios");
        $command->execute();

        return $command->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function buscarMail($mail)
    {
        $instancia = AccesoDatos::instance();
        $command = $instancia->preparer("SELECT * FROM usuarios WHERE mail = :mail");
        $command->bindValue(':mail',$mail);
        $command->execute();

        return $command->fetchObject('Usuario');
    }
}


?>