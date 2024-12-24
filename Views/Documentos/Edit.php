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
        /* General container styling */
    body { font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 0; }
    .container {
        max-width: 600px;
        margin: 50px auto;
        background: #fff;
        padding: 20px 30px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    /* Form styling */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: bold;
        margin-bottom: 8px;
        color: #495057;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        font-size: 1rem;
        border: 1px solid #ced4da;
        border-radius: 4px;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }

    .form-group input:focus {
        border-color: #80bdff;
        outline: none;
        background-color: #f8f9fa;
    }

    /* Buttons styling */
    .button {
        display: inline-block;
        padding: 10px 20px;
        font-size: 1rem;
        font-weight: bold;
        text-decoration: none;
        border-radius: 4px;
        text-align: center;
        transition: background 0.3s ease;
    }

    .button.save {
        background-color: #28a745;
        color: white;
        border: none;
        cursor: pointer;
    }

    .button.save:hover {
        background-color: #218838;
    }

    .button.cancel {
        background-color: #6c757d;
        color: white;
        margin-left: 10px;
    }

    .button.cancel:hover {
        background-color: #5a6268;
    }

    button[type="submit"] {
        cursor: pointer;
        border: none;
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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Documento</title>
</head>
<body>
    <div class="top-bar">
            <a href="/documentos">← Retornar Gestión de Documentos</a>
            <div><?= htmlspecialchars($nombreUsuario) ?> | Perfil: <?= htmlspecialchars($rolUsuario) ?></div>
    </div>
    <div class="container">
        <h2>Editar Documento</h2>
        <form action="/documentos/update" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($ID) ?>">

            <div class="form-group">
                <label for="codigo">Código</label>
                <input type="text" name="codigo" id="codigo" value="<?= htmlspecialchars($CODIGO) ?>" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <input type="text" name="descripcion" id="descripcion" value="<?= htmlspecialchars($DESCRIPCION) ?>" required>
            </div>

            <div class="form-group">
                <label for="categoria">Categoría</label>
                <select name="categoria" id="categoria" required>
                    <option value="ALTAS" <?= $CATEGORIA === 'ALTAS' ? 'selected' : '' ?>>ALTAS</option>
                    <option value="ANUALES" <?= $CATEGORIA === 'ANUALES' ? 'selected' : '' ?>>ANUALES</option>
                    <option value="LBS" <?= $CATEGORIA === 'LBS' ? 'selected' : '' ?>>LBS</option>
                </select>
            </div>

            <button type="submit" class="button save">Guardar Cambios</button>
            <a href="/documentos" class="button cancel">Cancelar</a>
        </form>
    </div>
</body>
</html>
