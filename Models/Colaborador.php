<?php

// /Models/Colaborador.php

namespace Models;

use \Libs\Database;

class Colaborador
{
    public static function getAll()
    {
        return Database::query("SELECT * FROM colaboradores");
    }

    public static function findByDocument($tipoDocumento, $nDocumento)
    {
        return Database::query("SELECT * FROM colaboradores WHERE TIPO_DOCUMENTO = ? AND N_DOCUMENTO = ?", [$tipoDocumento, $nDocumento]);
    }

    public static function searchByName($query)
    {
        return Database::query("SELECT * FROM colaboradores WHERE APELLIDOS_NOMBRES LIKE ?", ["%$query%"]);
    }

    public static function create($data)
    {
        return Database::insert(
            "INSERT INTO colaboradores (TIPO_DOCUMENTO, N_DOCUMENTO, APELLIDOS_NOMBRES, FECHA_INGRESO, AREA, CORREO) 
             VALUES (?, ?, ?, ?, ?, ?)",
            [
                $data['tipo_documento'],
                $data['n_documento'],
                $data['apellidos_nombres'],
                $data['fecha_ingreso'],
                $data['area'],
                $data['correo']
            ]
        );
    }
    

    public static function update($id, $data)
    {
        return Database::update("UPDATE colaboradores SET TIPO_DOCUMENTO = ?, N_DOCUMENTO = ?, APELLIDOS_NOMBRES = ?, FECHA_INGRESO = ?, AREA = ?, CORREO = ? WHERE ID = ?", [
            $data['tipo_documento'],
            $data['n_documento'],
            $data['apellidos_nombres'],
            $data['fecha_ingreso'],
            $data['area'],
            $data['correo'],
            $id
        ]);
    }

    public static function delete($id)
    {
        return Database::delete("DELETE FROM colaboradores WHERE ID = ?", [$id]);
    }
}
