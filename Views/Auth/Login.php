<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <style>
        /* Estilos para el formulario de login */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            margin-bottom: 1rem;
            font-size: 1.5rem;
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 1rem;
            position: relative; /* Para poder posicionar el ícono sobre el input */
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem;
            padding-right: 2.5rem; /* Dejar espacio para el ícono */
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-group input:focus {
            border-color: #007bff;
            outline: none;
        }

        /* Ícono para mostrar/ocultar contraseña */
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 0.8rem;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            cursor: pointer;
            opacity: 0.7;
        }

        .toggle-password:hover {
            opacity: 1;
        }

        .btn {
            display: inline-block;
            width: 100%;
            padding: 0.8rem;
            font-size: 1rem;
            color: #fff;
            background: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #0056b3;
        }

        .error-message {
            color: #d9534f;
            margin-top: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Inicio de Sesión</h2>
        <form method="POST" action="/login">
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" placeholder="Ingrese su usuario" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>
                <!-- Ícono para mostrar/ocultar -->
                <img src="/assets/mostrar.png" alt="Mostrar contraseña" id="togglePassword">
            </div>
            <button type="submit" class="btn">Iniciar Sesión</button>

            <?php if (isset($_GET['error'])): ?>
                <div class="error-message">
                    <?= htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
        // Aquí puedes cambiar la imagen si quieres, por ejemplo:
        if (type === 'text') {
            // Si ahora está en texto (visible), mostramos el ícono de ocultar
            this.src = '/assets/ocultar.png';
            this.alt = 'Ocultar contraseña';
        } else {
        // Si ahora está en modo password (oculto), mostramos el ícono de mostrar
            this.src = '/assets/mostrar.png';
            this.alt = 'Mostrar contraseña';
        }
        });
    </script>
</body>
</html>
