<?php

namespace Models;

use \Libs\Database;

class Documento
{
    public static function getAll()
    {
        return Database::query("SELECT * FROM documentos");
    }

    public static function findById($id)
    {
        $result = Database::query("SELECT * FROM documentos WHERE ID = ?", [$id]);
        
        // Verificar si la consulta retorna algún resultado
        if (empty($result)) {
            return null; // Retornar null si no hay resultados
        }

        return $result[0]; // Retorna la primera fila como un arreglo asociativo
    }
    
    public static function findByCodigo($codigo)
    {
        return Database::query("SELECT * FROM documentos WHERE CODIGO = ?", [$codigo]);
    }

    public static function create($data)
    {
    
        // Insertar el documento
        return Database::insert(
            "INSERT INTO documentos (CODIGO, DESCRIPCION, CATEGORIA) VALUES (?, ?, ?)",
            [$data['codigo'], $data['descripcion'], $data['categoria']]
        );
    }
    

    public static function update($id, $data)
    {

    
        // Ejecutar la actualización
        return Database::update(
            "UPDATE documentos SET CODIGO = ?, DESCRIPCION = ?, CATEGORIA = ? WHERE ID = ?",
            [$data['codigo'], $data['descripcion'], $data['categoria'], $id]
        );
    }
    
    

    public static function delete($id)
    {
        return Database::delete("DELETE FROM documentos WHERE ID = ?", [$id]);
    }

}
