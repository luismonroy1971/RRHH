<?php 
// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

try {
    // Requires con rutas absolutas
    require_once __DIR__ . '/Libs/Database.php';
    require_once __DIR__ . '/Libs/Response.php';
    require_once __DIR__ . '/Controllers/AuthController.php';
    require_once __DIR__ . '/Controllers/ColaboradoresController.php';
    require_once __DIR__ . '/Controllers/DocumentosController.php';
    require_once __DIR__ . '/Controllers/LegajoController.php';
    require_once __DIR__ . '/Controllers/UsuariosController.php';
    require_once __DIR__ . '/Controllers/DashboardController.php';

    require_once __DIR__ . '/Models/Usuario.php';
    require_once __DIR__ . '/Models/Colaborador.php';
    require_once __DIR__ . '/Models/DocumentoIdentidad.php';
    require_once __DIR__ . '/Models/Documentos.php';
    require_once __DIR__ . '/Models/Legajo.php';

    // Iniciar sesión una sola vez (aquí en index.php)
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Obtener la ruta solicitada
    $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = str_replace($basePath, '', $path);
    $path = trim($path, '/');

    // Cargar el router
    $router = require_once __DIR__ . '/routes.php';
    
    // Despachar la ruta - el router se encargará de verificar la autenticación
    $router->dispatch($_SERVER['REQUEST_METHOD'], '/' . $path);

} catch (Exception $e) {
    error_log("Error en index.php: " . $e->getMessage());
    echo "Error del sistema: " . $e->getMessage();
}