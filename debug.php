<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    require_once __DIR__ . '/Controllers/AuthController.php';
    echo "AuthController cargado correctamente<br>";
    
    require_once __DIR__ . '/Models/Usuario.php';
    echo "Usuario Model cargado correctamente<br>";
    
    require_once __DIR__ . '/Libs/Database.php';
    echo "Database cargado correctamente<br>";
    
    require_once __DIR__ . '/Libs/Response.php';
    echo "Response cargado correctamente<br>";
    
    echo "Directorio actual: " . __DIR__ . "<br>";
    echo "Script actual: " . $_SERVER['SCRIPT_NAME'] . "<br>";
    
    // Verificar si existen los archivos
    $files = [
        '/Controllers/AuthController.php',
        '/Models/Usuario.php',
        '/Libs/Database.php',
        '/Libs/Response.php',
        '/Views/Auth/Login.php'
    ];
    
    foreach ($files as $file) {
        echo "Archivo $file: " . (file_exists(__DIR__ . $file) ? "Existe" : "No existe") . "<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
    echo "Archivo: " . $e->getFile() . "<br>";
    echo "Línea: " . $e->getLine() . "<br>";
    echo "Trace: <pre>" . $e->getTraceAsString() . "</pre>";
}