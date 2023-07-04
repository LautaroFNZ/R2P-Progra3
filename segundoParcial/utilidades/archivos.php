<?php

require_once './models/cripto.php';

class Archivos
{
    public function descargarCriptosCSV()
    {   
        $registros = Cripto::listar();

        $ar = fopen("criptos.csv", "w");

        if ($ar === false) {
            return "Error al abrir el archivo";
        }

        foreach ($registros as $cripto) {
            if (fputcsv($ar, $cripto) == false) 
            {
                fclose($ar);
                return "Error al escribir en el archivo";
            }
        }

        fclose($ar);
        return "Datos guardados con éxito!";
    }
}

