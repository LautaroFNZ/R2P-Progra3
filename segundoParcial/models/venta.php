<?php
require_once "./utilidades/accesoDatos.php";

class Venta
{
    public $idCliente;
    public $idCripto;
    public $cantidad;
    public $fecha;
    public $fotoRef;

    public function alta()
    {
        $instancia = AccesoDatos::instance();
        $command = $instancia->preparer("INSERT INTO ventas (idCliente,idCripto,cantidad,fecha,fotoRef) VALUES (:idCliente,:idCripto,:cantidad,:fecha,:fotoRef)");
        
        $command->bindValue(':idCliente',$this->idCliente,PDO::PARAM_STR);
        $command->bindValue(':idCripto',$this->idCripto,PDO::PARAM_STR);
        $command->bindValue(':cantidad',$this->cantidad,PDO::PARAM_STR);
        $command->bindValue(':fecha',$this->fecha,PDO::PARAM_STR);
        $command->bindValue(':fotoRef',$this->fotoRef,PDO::PARAM_STR);
        $command->execute();

        return $instancia->lastId();
    }

    public static function listar()
    {
        $instancia = AccesoDatos::instance();
        $command = $instancia->preparer("SELECT * FROM ventas");
        $command->execute();

        return $command->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }

    public static function traerVentasNacionalidad($nacionalidad)
    {
        $instancia = AccesoDatos::instance();
        $command = $instancia->preparer("SELECT * FROM ventas v JOIN criptos c ON v.idCripto = c.id WHERE c.nacionalidad = :nacionalidad AND v.fecha BETWEEN '2023-07-10' AND '2023-07-13';        ");
        
        $command->bindValue(':nacionalidad',strtolower($nacionalidad),PDO::PARAM_STR);
        $command->execute();

        return $command->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }

    public static function traerUsuariosNombre($nombre)
    {
        $instancia = AccesoDatos::instance();

        $command = $instancia->preparer("SELECT u.id, u.mail, u.tipo, u.clave FROM usuarios u JOIN ventas v ON u.id = v.idCliente JOIN criptos c ON v.idCripto = c.id WHERE c.nombre = :nombre;");
        $command->bindValue(':nombre',strtolower($nombre),PDO::PARAM_STR);
        $command->execute();

        return $command->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }
}


?>