<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Verificando permisos:<br>";
$baseDir = __DIR__;

// Verificar permisos de directorios
$directories = ['/', '/Libs', '/Controllers', '/Models', '/Views'];
foreach ($directories as $dir) {
    $fullPath = $baseDir . $dir;
    $perms = fileperms($fullPath);
    echo "Directorio $dir: " . substr(sprintf('%o', $perms), -4) . "<br>";
}

// Verificar permisos de archivos críticos
$files = [
    '/Libs/Database.php',
    '/Libs/Response.php',
    '/Controllers/AuthController.php'
];

foreach ($files as $file) {
    $fullPath = $baseDir . $file;
    $perms = fileperms($fullPath);
    echo "Archivo $file: " . substr(sprintf('%o', $perms), -4) . "<br>";
}