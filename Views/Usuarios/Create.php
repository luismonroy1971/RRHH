<?php
// Verificar si la sesión no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener valores de sesión
$nombreUsuario = $_SESSION['username'] ?? 'Usuario';
$rolUsuario = $_SESSION['role'] ?? 'INVITADO';
?>
<style>
    body {
        font-family: "Open Sans", Arial, sans-serif;
        background: #f8f9fa;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 40px auto;
        background: #fff;
        padding: 20px 30px;
        border-radius: 5px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .container h2 {
        margin: 0 0 20px 0;
        font-size: 1.5rem;
        color: #495057;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 10px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #495057;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px;
        font-size: 1rem;
        border: 1px solid #ced4da;
        border-radius: 4px;
        background: #fff;
        transition: border-color 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #80bdff;
        outline: none;
        background: #f0f8ff;
    }

    button[type="submit"] {
        display: inline-block;
        background: #007bff;
        color: #fff;
        padding: 10px 20px;
        font-size: 1rem;
        font-weight: 600;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    button[type="submit"]:hover {
        background: #0056b3;
    }
    .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #343a40;
            color: #fff;
            padding: 10px 20px;
    }
    .top-bar a { color: #f8d7da; text-decoration: none; font-weight: 600; }
    .top-bar a:hover { color: #fff; }
</style>
 <!-- Barra Superior -->
 <div class="top-bar">
        <a href="/usuarios">← Retornar Gestión de Usuarios</a>
        <div><?= htmlspecialchars($nombreUsuario) ?> | Perfil: <?= htmlspecialchars($rolUsuario) ?></div>
    </div>


<div class="container">
    <h2>Agregar Usuario</h2>
    <form action="/usuarios" method="POST">
        <div class="form-group">
            <label for="nombre_usuario">Nombre de Usuario</label>
            <input type="text" name="nombre_usuario" id="nombre_usuario" required>
        </div>

        <div class="form-group">
            <label for="contrasena">Contraseña</label>
            <input type="password" name="contrasena" id="contrasena" required>
        </div>

        <div class="form-group">
            <label for="rol">Rol</label>
            <select name="rol" id="rol" required>
                <option value="">Seleccione un rol</option>
                <option value="NOMINAS">NOMINAS</option>
                <option value="RRHH">RRHH</option>
                <option value="RECEPCION">RECEPCION</option>
                <option value="ADMIN">ADMIN</option>
            </select>
        </div>

        <button type="submit">Guardar</button>
    </form>
</div>
