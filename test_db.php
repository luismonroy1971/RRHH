<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/Libs/Database.php';

try {
    $db = new \Libs\Database();
    echo "Conexión exitosa a la base de datos";
} catch (Exception $e) {
    echo "Error de conexión: " . $e->getMessage();
}