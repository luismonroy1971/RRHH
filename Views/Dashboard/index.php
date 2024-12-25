<?php
// Verificar si la sesión no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener valores de sesión
$nombreUsuario = $_SESSION['username'] ?? 'Usuario';
$rolUsuario = $_SESSION['role'] ?? 'INVITADO';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <style>
        /* Fuente base */
        body {
            font-family: "Open Sans", Arial, sans-serif;
            margin: 0;
            background: #f8f9fa;
            color: #333;
        }

        /* Encabezado Principal */
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #343a40;
            color: #fff;
            padding: 15px 20px;
        }

        header h1 { margin: 0; font-size: 1.5rem; }

        header .logout {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        header .user-name {
            font-weight: 600;
            color: #f8d7da;
        }

        header .logout a {
            color: #f8d7da;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        header .logout a:hover { color: #fff; }

        /* Contenedor principal */
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        /* Títulos */
        .container h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 1.3rem;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
            color: #495057;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        nav li {
            background: #f1f3f5;
            padding: 15px;
            border-radius: 5px;
            transition: background 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            align-items: center;
        }

        nav li:hover {
            background: #e9ecef;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        nav a {
            text-decoration: none;
            color: #007bff;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }

        nav a span.icon {
            display: inline-block;
            width: 24px;
            text-align: center;
            margin-right: 10px;
            font-size: 1.2rem;
            color: #495057;
        }

        nav a:hover { color: #0056b3; }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <header>
        <h1>Panel de Administración</h1>
        <div class="logout">
            <span class="user-name"><?= htmlspecialchars($nombreUsuario); ?> (<?= htmlspecialchars($rolUsuario); ?>)</span>
            <a href="/logout">Cerrar Sesión</a>
        </div>
    </header>

    <!-- Contenedor Principal -->
    <div class="container">
        <h2>Secciones</h2>
        <nav>
            <ul>
                <?php if ($rolUsuario === 'ADMIN'): ?>
                    <li>
                        <a href="/usuarios">
                            <span class="icon">👤</span>
                            Administrar Usuarios
                        </a>
                    </li>
                    <li>
                        <a href="/documentos">
                            <span class="icon">📄</span>
                            Administrar Documentos
                        </a>
                    </li>
                    <li>
                        <a href="/colaboradores">
                            <span class="icon">🤝</span>
                            Administrar Colaboradores
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($rolUsuario === 'NOMINAS'): ?>
                    <li>
                    <a href="/legajo/bulk-upload">
                        <span class="icon">📁</span>
                        Subida Masiva de Documentos
                    </a>
                </li>

                <?php endif; ?>
                <li>
                    <a href="/legajo">
                        <span class="icon">📁</span>
                        Administrar Legajos
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</body>
</html>
