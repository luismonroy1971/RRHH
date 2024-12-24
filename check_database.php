<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$file = __DIR__ . '/Libs/Database.php';
if (file_exists($file)) {
    echo "Contenido de Database.php:<br>";
    echo "<pre>";
    highlight_file($file);
    echo "</pre>";
} else {
    echo "El archivo Database.php no existe";
}