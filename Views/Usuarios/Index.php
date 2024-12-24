<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <style>
        body {
            font-family: "Open Sans", Arial, sans-serif;
            margin: 0;
            background: #f8f9fa;
            color: #333;
        }

        /* Barra superior */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #343a40;
            color: #fff;
            padding: 10px 20px;
        }

        .top-bar a {
            text-decoration: none;
            color: #f8d7da;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background 0.3s ease, color 0.3s ease;
        }

        .top-bar a:hover {
            background: #495057;
            color: #fff;
        }

        /* Contenedor principal */
        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .container h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 1.5rem;
            color: #495057;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
        }

        .actions {
            margin-bottom: 20px;
        }

        .actions .button {
            display: inline-block;
            text-decoration: none;
            background: #007bff;
            color: #fff;
            font-weight: 600;
            padding: 10px 15px;
            border-radius: 4px;
            transition: background 0.3s ease;
        }

        .actions .button:hover {
            background: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            border-radius: 5px;
            overflow: hidden;
        }

        table thead {
            background: #f1f3f5;
        }

        table thead th {
            text-align: left;
            padding: 12px 15px;
            font-weight: 600;
            color: #495057;
            border-bottom: 1px solid #dee2e6;
        }

        table tbody td {
            padding: 12px 15px;
            color: #333;
        }

        .table-actions a, .table-actions button {
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 3px;
            font-weight: 600;
            margin-right: 5px;
            font-size: 0.9rem;
            display: inline-block;
            border: none;
            cursor: pointer;
        }

        .table-actions .edit {
            background: #ffc107;
            color: #212529;
        }

        .table-actions .edit:hover {
            background: #e0a800;
        }

        .table-actions .delete {
            background: #dc3545;
            color: #fff;
        }

        .table-actions .delete:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
    <!-- Barra superior -->
    <div class="top-bar">
        <a href="/">← Ir al Panel de Administración</a>
        <a href="/logout">Cerrar Sesión</a>
    </div>

    <!-- Contenido principal -->
    <div class="container">
        <?php if (isset($_GET['message'])): ?>
            <div id="alert-message" class="alert" style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
                <?= htmlspecialchars($_GET['message']) ?>
            </div>
            <script>
                // Ocultar el mensaje después de 3 segundos
                setTimeout(function() {
                    document.getElementById('alert-message').style.display = 'none';
                }, 3000);
            </script>
        <?php endif; ?>

        <h2>Gestión de Usuarios</h2>
        <div class="actions">
            <a href="/usuarios/create" class="button">Agregar Nuevo Usuario</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Usuario</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= htmlspecialchars($usuario['ID']) ?></td>
                        <td><?= htmlspecialchars($usuario['NOMBRE_USUARIO']) ?></td>
                        <td><?= htmlspecialchars($usuario['ROL']) ?></td>
                        <td class="table-actions">
                            <a href="/usuarios/update/<?= urlencode($usuario['ID']) ?>" class="edit">Editar</a>
                            <form action="/usuarios/delete/<?= urlencode($usuario['ID']) ?>" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este usuario?')" style="display:inline;">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['ID']) ?>">
                                <button type="submit" class="delete">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
