<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Documentos</title>
    <style>
        /* General */
        body {
            font-family: "Open Sans", Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Encabezado */
        h2 {
            margin-bottom: 20px;
            font-size: 1.8rem;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
        }

        /* Botón de agregar */
        .button {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .button:hover {
            background: #0056b3;
        }

        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table thead {
            background: #f1f3f5;
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        table tbody tr:hover {
            background: #f8f9fa;
        }

        /* Acciones */
        .actions button, .actions a {
            display: inline-block;
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .actions .edit {
            background-color: #ffc107;
            color: #212529;
        }

        .actions .edit:hover {
            background-color: #e0a800;
        }

        .actions .delete {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .actions .delete:hover {
            background-color: #c82333;
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

        <h2>Gestión de Documentos</h2>
        <a href="/documentos/create" class="button">Agregar Nuevo Documento</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documentos as $doc): ?>
                    <tr>
                        <td><?= htmlspecialchars($doc['ID']) ?></td>
                        <td><?= htmlspecialchars($doc['CODIGO']) ?></td>
                        <td><?= htmlspecialchars($doc['DESCRIPCION']) ?></td>
                        <td><?= htmlspecialchars($doc['CATEGORIA']) ?></td>
                        <td class="actions">
                            <a href="/documentos/update/<?= urlencode($doc['ID']) ?>" class="edit">Editar</a>
                            <form action="/documentos/delete" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($doc['ID']) ?>">
                                <button type="submit" class="delete" onclick="return confirm('¿Está seguro de eliminar este documento?');">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
