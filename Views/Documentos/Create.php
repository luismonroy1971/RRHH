<style>
    .container {
        max-width: 600px;
        margin: 50px auto;
        background: #fff;
        padding: 20px 30px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: bold;
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
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #80bdff;
        outline: none;
        background-color: #f8f9fa;
    }

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


</style>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Documento</title>
</head>
<body>
    <div class="container">
        <h2>Agregar Documento</h2>
        <form action="/documentos" method="POST">
            <div class="form-group">
                <label for="codigo">Código</label>
                <input type="text" name="codigo" id="codigo" placeholder="Ingrese el código" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <input type="text" name="descripcion" id="descripcion" placeholder="Ingrese la descripción" required>
            </div>

            <div class="form-group">
                <label for="categoria">Categoría</label>
                <select name="categoria" id="categoria" required>
                    <option value="" disabled selected>Seleccione una categoría</option>
                    <option value="ALTAS">ALTAS</option>
                    <option value="ANUALES">ANUALES</option>
                    <option value="LBS">LBS</option>
                </select>
            </div>

            <button type="submit" class="button save">Guardar</button>
            <a href="/documentos" class="button cancel">Cancelar</a>
        </form>
    </div>
</body>
</html>
