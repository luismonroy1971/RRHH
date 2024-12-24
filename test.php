<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Verificando estructura de directorios:<br>";
$baseDir = __DIR__;
echo "Directorio base: " . $baseDir . "<br>";

$dirs = [
    'Libs',
    'Controllers',
    'Models',
    'Views',
];

foreach ($dirs as $dir) {
    $fullPath = $baseDir . '/' . $dir;
    echo "$dir: " . (is_dir($fullPath) ? "✓ Existe" : "✗ No existe") . " ($fullPath)<br>";
}

echo "<br>Verificando archivos críticos:<br>";
$files = [
    '/Libs/Database.php',
    '/Libs/Response.php',
    '/Controllers/AuthController.php',
    // Agrega aquí los demás archivos importantes
];

foreach ($files as $file) {
    $fullPath = $baseDir . $file;
    echo "$file: " . (file_exists($fullPath) ? "✓ Existe" : "✗ No existe") . "<br>";
}