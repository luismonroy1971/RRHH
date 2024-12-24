<?php

// /Models/DocumentoIdentidad.php

namespace Models;

use \Libs\Database;

class DocumentoIdentidad
{
    public static function getAll()
    {
        return Database::query("SELECT * FROM documentos_identidad");
    }

    public static function findById($id)
    {
        return Database::query("SELECT * FROM documentos_identidad WHERE ID = ?", [$id]);
    }

    public static function create($data)
    {
        return Database::insert("INSERT INTO documentos_identidad (TIPO_DOCUMENTO) VALUES (?)", [$data['tipo_documento']]);
    }

    public static function update($id, $data)
    {
        return Database::update("UPDATE documentos_identidad SET TIPO_DOCUMENTO = ? WHERE ID = ?", [$data['tipo_documento'], $id]);
    }

    public static function delete($id)
    {
        return Database::delete("DELETE FROM documentos_identidad WHERE ID = ?", [$id]);
    }
}
