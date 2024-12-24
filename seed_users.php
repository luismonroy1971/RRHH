<?php

// Cargar el autoloader de Composer
require __DIR__ . '/vendor/autoload.php';

// Conexión a la base de datos (ajusta según tu configuración)
$host = 'localhost';      // Cambia esto por el host de tu base de datos
$dbname = 'gestor_rrhh';       // Cambia esto por el nombre de tu base de datos
$username = 'root';       // Cambia esto por el usuario de tu base de datos
$password = '';           // Cambia esto por la contraseña de tu base de datos

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}

// Usuarios que queremos insertar
$usuarios = [
    [
        'nombre_usuario' => 'admin',
        'contrasena' => 'admin123',
        'rol' => 'admin'
    ],
    [
        'nombre_usuario' => 'rrhh_user',
        'contrasena' => 'rrhh123',
        'rol' => 'rrhh'
    ],
    [
        'nombre_usuario' => 'nominas_user',
        'contrasena' => 'nominas123',
        'rol' => 'nominas'
    ]
];

// Consulta para insertar usuarios
$sql = "INSERT INTO USUARIOS (NOMBRE_USUARIO, CONTRASENA, ROL) VALUES (:nombre_usuario, :contrasena, :rol)";

try {
    $stmt = $pdo->prepare($sql);

    foreach ($usuarios as $usuario) {
        $hashedPassword = password_hash($usuario['contrasena'], PASSWORD_BCRYPT);
        $stmt->execute([
            ':nombre_usuario' => $usuario['nombre_usuario'],
            ':contrasena' => $hashedPassword,
            ':rol' => $usuario['rol']
        ]);
    }

    echo "Usuarios insertados correctamente.\n";
} catch (PDOException $e) {
    die("Error al insertar usuarios: " . $e->getMessage());
}
