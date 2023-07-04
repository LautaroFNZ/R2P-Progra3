<?php
require_once "./utilidades/accesoDatos.php";

class Cripto
{   
    public $id;
    public $precio;
    public $nombre;
    public $nacionalidad;

    public function setter($precio,$nombre,$nacionalidad)
    {
        $this->precio = $precio;
        $this->nombre = $nombre;
        $this->nacionalidad = $nacionalidad;
    }

    public function alta()
    {
        $instancia = AccesoDatos::instance();
        $command = $instancia->preparer("INSERT INTO criptos (precio,nombre,nacionalidad) VALUES (:precio,:nombre,:nacionalidad)");
        
        $command->bindValue(':precio',$this->precio,PDO::PARAM_STR);
        $command->bindValue(':nombre',strtolower($this->nombre),PDO::PARAM_STR);
        $command->bindValue(':nacionalidad',strtolower($this->nacionalidad),PDO::PARAM_STR);
        $command->execute();

        return $instancia->lastId();
    }

    public static function listar()
    {
        $instancia = AccesoDatos::instance();
        $command = $instancia->preparer("SELECT * FROM criptos");
        $command->execute();

        return $command->fetchAll(PDO::FETCH_CLASS, 'Cripto');
    }

    public static function buscarId($id)
    {
        $instancia = AccesoDatos::instance();
        $command = $instancia->preparer("SELECT * FROM criptos WHERE id = :id");
        $command->bindValue(':id',$id);

        $command->execute();

        return $command->fetchObject('Cripto');
    }

    public static function buscarNacionalidad($nacionalidad)
    {
        $instancia = AccesoDatos::instance();
        $command = $instancia->preparer("SELECT * FROM criptos WHERE nacionalidad = :nacionalidad");
        $command->bindValue(':nacionalidad',$nacionalidad);

        $command->execute();

        return $command->fetchObject('Cripto');
    }



    //NO NECESARIAS PARA CONSIGNA PRINCIPAL

    public static function buscarNombre($nombre)
    {
        $instancia = AccesoDatos::instance();
        $command = $instancia->preparer("SELECT * FROM criptos WHERE nombre = :nombre");
        $command->bindValue(':nombre',$nombre);

        $command->execute();

        return $command->fetchObject('Cripto');
    }

    public static function borrarId($id)
    {
        $instancia = AccesoDatos::instance();
        $command = $instancia->preparer("DELETE FROM criptos WHERE id = :id");
        $command->bindValue(':id',$id);

        $filasAfectadas = $command->execute();

        return $filasAfectadas > 0;
    }


}

?>