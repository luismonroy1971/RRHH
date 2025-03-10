<?php

namespace Models;

use Libs\Database;

class Usuario
{
    public static function getAll()
    {
        return Database::query("SELECT * FROM usuarios");
    }

    public static function create($data)
    {
        // Si la contraseña ya está hasheada (por ejemplo, cuando no se cambia), no la hasheamos de nuevo
        $password = $data['contrasena'];
        if (strpos($password, '$2y$') !== 0) { // No es un hash de bcrypt
            $password = password_hash($password, PASSWORD_BCRYPT);
        }

        return Database::insert("INSERT INTO usuarios (NOMBRE_USUARIO, CONTRASENA, ROL) VALUES (?, ?, ?)", [
            $data['nombre_usuario'],
            $password,
            $data['rol']
        ]);
    }

    public static function update($id, $data)
    {
        // Si la contraseña ya está hasheada (por ejemplo, cuando no se cambia), no la hasheamos de nuevo
        $password = $data['contrasena'];
        if (strpos($password, '$2y$') !== 0) { // No es un hash de bcrypt
            $password = password_hash($password, PASSWORD_BCRYPT);
        }

        return Database::update("UPDATE usuarios SET NOMBRE_USUARIO = ?, CONTRASENA = ?, ROL = ? WHERE ID = ?", [
            $data['nombre_usuario'],
            $password,
            $data['rol'],
            $id
        ]);
    }

    public static function delete($id)
    {
        return Database::delete("DELETE FROM usuarios WHERE ID = ?", [$id]);
    }

    // Agregar este método para buscar el usuario por nombre de usuario
    public static function findByUsername($username)
    {
        $result = Database::query("SELECT * FROM usuarios WHERE NOMBRE_USUARIO = ?", [$username]);
        return $result ? $result[0] : null;
    }

    public static function getById($id)
    {
        $result = Database::query("SELECT * FROM usuarios WHERE ID = ?", [$id]);
        return $result ? $result[0] : null;
    }
}