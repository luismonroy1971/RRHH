<?php

namespace Controllers;

class DashboardController
{
    public static function index()
    {
        session_start();

        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // Si está autenticado, mostrar la vista del dashboard
        require __DIR__ . '/../Views/Dashboard/index.php';
    }
}
