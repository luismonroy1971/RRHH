<?php

namespace Controllers;

use Models\Usuario;

class UsuariosController {
    public static function getAll() {
        $usuarios = Usuario::getAll();
        include __DIR__ . '/../Views/Usuarios/Index.php';
    }

    public static function create() {
        $data = [
            'nombre_usuario' => $_POST['nombre_usuario'] ?? null,
            'contrasena' => $_POST['contrasena'] ?? null,
            'rol' => $_POST['rol'] ?? null
        ];

        $result = Usuario::create($data);

        if ($result) {
            // Redirigir a /usuarios con mensaje de éxito
            header('Location: /usuarios?message=Usuario creado correctamente.');
            exit;
        } else {
            // Redirigir a /usuarios con mensaje de error
            header('Location: /usuarios?message=Error al crear usuario.');
            exit;
        }
    }

    public static function update($id = null) {
        // Si no se proporciona ID como parámetro en la URL, intentar obtenerlo del POST
        if ($id === null) {
            $id = $_POST['id'] ?? null;
        }

        if (!$id) {
            header('Location: /usuarios?message=ID no proporcionado');
            exit;
        }

        $data = [
            'nombre_usuario' => $_POST['nombre_usuario'] ?? null,
            'contrasena' => $_POST['contrasena'] ?? null,
            'rol' => $_POST['rol'] ?? null
        ];

        // Si la contraseña está vacía, no actualizarla
        if (empty($data['contrasena'])) {
            // Obtener el usuario actual
            $currentUser = Usuario::getById($id);
            if ($currentUser) {
                $data['contrasena'] = $currentUser['CONTRASENA'];
            } else {
                header('Location: /usuarios?message=Error al obtener el usuario');
                exit;
            }
        }

        $result = Usuario::update($id, $data);

        if ($result !== false) {
            header('Location: /usuarios?message=Usuario actualizado correctamente.');
            exit;
        } else {
            header('Location: /usuarios?message=Error al actualizar el usuario.');
            exit;
        }
    }

    public static function delete() {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            header('Location: /usuarios?message=ID no proporcionado');
            exit;
        }

        $result = Usuario::delete($id);

        if ($result !== false) {
            header('Location: /usuarios?message=Usuario eliminado correctamente.');
            exit;
        } else {
            header('Location: /usuarios?message=Error al eliminar el usuario.');
            exit;
        }
    }

    public static function showEditForm($id) {
        if (!$id) {
            header('Location: /usuarios?message=ID no proporcionado');
            exit;
        }

        $usuario = Usuario::getById($id);
        if (!$usuario) {
            header('Location: /usuarios?message=Usuario no encontrado');
            exit;
        }

        include __DIR__ . '/../Views/Usuarios/Edit.php';
    }

    public static function showCreateForm() {
        include __DIR__ . '/../Views/Usuarios/Create.php';
    }
}