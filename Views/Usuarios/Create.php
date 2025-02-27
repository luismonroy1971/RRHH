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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario | Sistema de Gestión</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos Generales */
        :root {
            --primary-color: #2c7be5;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --secondary-color: #6c757d;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --border-color: #ced4da;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: "Segoe UI", "Open Sans", Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        /* Barra Superior */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--dark-color);
            color: #fff;
            padding: 15px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .top-bar a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            transition: color 0.3s;
        }
        
        .top-bar a i {
            margin-right: 8px;
        }
        
        .top-bar a:hover {
            color: #f8d7da;
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .user-info i {
            margin-right: 8px;
            font-size: 1.1rem;
        }

        /* Contenedor Principal */
        .container {
            max-width: 650px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        h2 {
            font-size: 1.8rem;
            color: var(--dark-color);
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
            text-align: center;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        h2 i {
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        h2::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: var(--primary-color);
        }

        /* Formulario */
        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        
        input:focus, select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(44, 123, 229, 0.25);
            outline: none;
            background-color: #f0f8ff;
        }
        
        select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23495057' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 40px;
        }
        
        /* Botones */
        button[type="submit"] {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-color);
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.1s;
            min-width: 120px;
        }
        
        button[type="submit"] i {
            margin-right: 8px;
        }

        button[type="submit"]:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        
        button[type="submit"]:active {
            transform: translateY(1px);
        }
        
        /* Input de contraseña con botón de mostrar/ocultar */
        .password-container {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--secondary-color);
            font-size: 1rem;
            padding: 4px;
            transition: color 0.3s;
        }
        
        .password-toggle:hover {
            color: var(--primary-color);
        }
        
        /* Etiquetas de rol */
        .role-tag {
            display: inline-block;
            padding: 4px 8px;
            margin: 4px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            color: white;
        }
        
        .role-tag-admin {
            background-color: #6b21a8;
        }
        
        .role-tag-nominas {
            background-color: #0891b2;
        }
        
        .role-tag-rrhh {
            background-color: #15803d;
        }
        
        .role-tag-recepcion {
            background-color: #b45309;
        }

        /* Estilos para modo responsivo */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 20px auto;
            }
            
            h2 {
                font-size: 1.5rem;
            }
            
            input, select, button[type="submit"] {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <a href="/usuarios"><i class="fas fa-arrow-left"></i> Retornar a Gestión de Usuarios</a>
        <div class="user-info">
            <i class="fas fa-user-circle"></i>
            <span><?= htmlspecialchars($nombreUsuario) ?> | Perfil: <?= htmlspecialchars($rolUsuario) ?></span>
        </div>
    </div>
    
    <div class="container">
        <h2><i class="fas fa-user-plus"></i> Agregar Usuario</h2>
        
        <form action="/usuarios" method="POST">
            <!-- Nombre de Usuario -->
            <div class="form-group">
                <label for="nombre_usuario">Nombre de Usuario</label>
                <input type="text" name="nombre_usuario" id="nombre_usuario" 
                       required minlength="4"
                       placeholder="Ingrese el nombre de usuario">
            </div>

            <!-- Contraseña -->
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <div class="password-container">
                    <input type="password" name="contrasena" id="contrasena" 
                           required placeholder="Ingrese la contraseña">
                    <button type="button" class="password-toggle" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Rol -->
            <div class="form-group">
                <label for="rol">Rol</label>
                <select name="rol" id="rol" required>
                    <option value="">Seleccione un rol</option>
                    <option value="ADMIN">ADMIN</option>
                    <option value="NOMINAS">NOMINAS</option>
                    <option value="RRHH">RRHH</option>
                    <option value="RECEPCION">RECEPCION</option>
                </select>
                <div id="role-description" style="margin-top: 8px; font-size: 0.85rem; color: #6c757d;"></div>
            </div>

            <!-- Botón Guardar -->
            <button type="submit"><i class="fas fa-save"></i> Guardar</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle para mostrar/ocultar contraseña
            const password = document.getElementById('contrasena');
            const togglePassword = document.getElementById('togglePassword');
            
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                togglePassword.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
            
            // Descripciones de roles
            const rolSelect = document.getElementById('rol');
            const roleDescription = document.getElementById('role-description');
            
            rolSelect.addEventListener('change', function() {
                const role = this.value;
                
                switch(role) {
                    case 'ADMIN':
                        roleDescription.innerHTML = '<span class="role-tag role-tag-admin">ADMIN</span> Acceso completo al sistema, gestión de usuarios y configuración.';
                        break;
                    case 'NOMINAS':
                        roleDescription.innerHTML = '<span class="role-tag role-tag-nominas">NOMINAS</span> Gestión de nóminas, emisión de documentos y reportes financieros.';
                        break;
                    case 'RRHH':
                        roleDescription.innerHTML = '<span class="role-tag role-tag-rrhh">RRHH</span> Gestión de recursos humanos, expedientes de personal y documentación laboral.';
                        break;
                    case 'RECEPCION':
                        roleDescription.innerHTML = '<span class="role-tag role-tag-recepcion">RECEPCION</span> Recepción de documentos físicos y registro de entradas.';
                        break;
                    default:
                        roleDescription.innerHTML = '';
                        break;
                }
            });
        });
    </script>
</body>
</html>