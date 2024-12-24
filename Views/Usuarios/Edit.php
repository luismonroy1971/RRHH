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

    .form-group input {
        width: 100%;
        padding: 10px;
        font-size: 1rem;
        border: 1px solid #ced4da;
        border-radius: 4px;
        background: #fff;
        transition: border-color 0.3s ease;
    }

    .form-group input:focus {
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
</style>

<div class="container">
    <h2>Editar Usuario</h2>
    <form action="/usuarios/update/<?= htmlspecialchars($usuario['ID']) ?>" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['ID']) ?>">

        <div class="form-group">
            <label for="nombre_usuario">Nombre de Usuario:</label>
            <input type="text" name="nombre_usuario" id="nombre_usuario" 
                   value="<?= htmlspecialchars($usuario['NOMBRE_USUARIO']) ?>" required>
        </div>

        <div class="form-group">
            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" id="contrasena" placeholder="Dejar en blanco para no cambiar">
        </div>

        <div class="form-group">
            <label for="rol">Rol:</label>
            <select name="rol" id="rol" required>
                <option value="">Seleccione un rol</option>
                <option value="NOMINAS" <?= $usuario['ROL'] === 'NOMINAS' ? 'selected' : '' ?>>NOMINAS</option>
                <option value="RRHH" <?= $usuario['ROL'] === 'RRHH' ? 'selected' : '' ?>>RRHH</option>
                <option value="RECEPCION" <?= $usuario['ROL'] === 'RECEPCION' ? 'selected' : '' ?>>RECEPCION</option>
                <option value="ADMIN" <?= $usuario['ROL'] === 'ADMIN' ? 'selected' : '' ?>>ADMIN</option>
            </select>
        </div>

        <button type="submit">Guardar Cambios</button>
    </form>
</div>
