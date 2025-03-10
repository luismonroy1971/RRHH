<?php

namespace Controllers;

class DashboardController
{
    public static function index()
    {
        // La verificación de autenticación ya se realiza en el router
        // No es necesario iniciar la sesión nuevamente aquí
        
        // Obtener datos del usuario para la vista (si es necesario)
        $user_id = $_SESSION['user_id'] ?? null;
        $username = $_SESSION['username'] ?? null;
        $role = $_SESSION['role'] ?? null;

        // Mostrar la vista del dashboard
        require __DIR__ . '/../Views/Dashboard/index.php';
    }
}