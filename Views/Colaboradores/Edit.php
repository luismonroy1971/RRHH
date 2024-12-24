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
/* Estilos Generales */
body {
    font-family: "Segoe UI", Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f9fa;
    color: #333;
}

h1 {
    font-size: 1.8rem;
    color: #343a40;
    margin-bottom: 20px;
}

.container {
    max-width: 1200px;
    margin: 40px auto;
    background: #fff;
    padding: 20px 30px;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Botones */
.button {
    display: inline-block;
    padding: 8px 15px;
    font-size: 0.9rem;
    text-decoration: none;
    border: none;
    border-radius: 4px;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-weight: 600;
}

.button.save {
    background-color: #28a745;
    color: white;
}

.button.save:hover {
    background-color: #218838;
}

.button.edit {
    background-color: #ffc107;
    color: #212529;
}

.button.edit:hover {
    background-color: #e0a800;
}

.button.delete {
    background-color: #dc3545;
    color: white;
}

.button.delete:hover {
    background-color: #c82333;
}

.button.cancel {
    background-color: #6c757d;
    color: white;
}

.button.cancel:hover {
    background-color: #5a6268;
}

/* Tabla */
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    margin-top: 20px;
}

table thead {
    background-color: #f1f3f5;
}

table th, table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

table th {
    font-weight: bold;
    color: #495057;
}

table tr:nth-child(even) {
    background-color: #f8f9fa;
}

table tr:hover {
    background-color: #e9ecef;
}

table td.actions {
    white-space: nowrap;
}

/* Formularios */
form .form-group {
    margin-bottom: 20px;
}

form label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #495057;
}

form input, form select, form textarea {
    width: 100%;
    padding: 10px;
    font-size: 1rem;
    border: 1px solid #ced4da;
    border-radius: 4px;
    box-sizing: border-box;
    transition: border-color 0.3s ease;
}

form input:focus, form select:focus, form textarea:focus {
    border-color: #80bdff;
    outline: none;
    background-color: #f0f8ff;
}

form button {
    margin-right: 10px;
}

.alert {
    padding: 10px 15px;
    margin-bottom: 20px;
    border-radius: 4px;
    font-size: 0.9rem;
}

.alert.success {
    background-color: #d4edda;
    color: #155724;
}

.alert.error {
    background-color: #f8d7da;
    color: #721c24;
}
.button {
        padding: 6px 12px;
        font-size: 0.8rem;
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
/* Responsive */
@media (max-width: 768px) {
    .container {
        padding: 15px;
    }

    table th, table td {
        padding: 8px 10px;
    }

    form input, form select, form textarea {
        padding: 8px;
    }

    .button {
        padding: 6px 12px;
        font-size: 0.8rem;
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
}

</style>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Colaborador</title>
</head>
<body>
<div class="top-bar">
        <a href="/colaboradores">← Retornar Gestión de Colaboradores</a>
        <div><?= htmlspecialchars($nombreUsuario) ?> | Perfil: <?= htmlspecialchars($rolUsuario) ?></div>
</div>
    <div class="container">
        <h2>Editar Colaborador</h2>
        <form action="/colaboradores/update" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($colaborador['ID']) ?>">

            <div class="form-group">
                <label for="tipo_documento">Tipo Documento</label>
                <input type="text" name="tipo_documento" id="tipo_documento" 
                       value="<?= htmlspecialchars($colaborador['TIPO_DOCUMENTO']) ?>" required>
            </div>

            <div class="form-group">
                <label for="n_documento">Número Documento</label>
                <input type="text" name="n_documento" id="n_documento" 
                       value="<?= htmlspecialchars($colaborador['N_DOCUMENTO']) ?>" required>
            </div>

            <div class="form-group">
                <label for="apellidos_nombres">Apellidos y Nombres</label>
                <input type="text" name="apellidos_nombres" id="apellidos_nombres" 
                       value="<?= htmlspecialchars($colaborador['APELLIDOS_NOMBRES']) ?>" readonly required>
            </div>

            <div class="form-group">
                <label for="fecha_ingreso">Fecha de Ingreso</label>
                <input type="date" name="fecha_ingreso" id="fecha_ingreso" 
                       value="<?= htmlspecialchars($colaborador['FECHA_INGRESO']) ?>">
            </div>

            <div class="form-group">
                <label for="area">Área</label>
                <input type="text" name="area" id="area" 
                       value="<?= htmlspecialchars($colaborador['AREA']) ?>">
            </div>

            <div class="form-group">
                <label for="correo">Correo</label>
                <input type="email" name="correo" id="correo" 
                       value="<?= htmlspecialchars($colaborador['CORREO']) ?>">
            </div>

            <button type="submit" class="button save">Guardar Cambios</button>
            <a href="/colaboradores" class="button cancel">Cancelar</a>
        </form>
    </div>
</body>
</html>

