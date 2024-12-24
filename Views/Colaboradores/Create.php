<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Colaborador</title>
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
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            padding: 20px 30px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #495057;
        }

        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[disabled] {
            background-color: #e9ecef;
        }

        .button {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }

        .button.cancel {
            background-color: #6c757d;
        }

        .button:hover {
            background-color: #218838;
        }

        .button.cancel:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Agregar Colaborador</h1>
        <form action="/colaboradores" method="POST">
            <!-- Tipo de Documento -->
            <div class="form-group">
                <label for="tipo_documento">Tipo Documento</label>
                <select name="tipo_documento" id="tipo_documento" required>
                    <option value="1">Documento Nacional de Identidad</option>
                    <option value="4">Carné de Extranjería</option>
                </select>
            </div>

            <!-- Número de Documento -->
            <div class="form-group">
                <label for="n_documento">Número Documento</label>
                <input type="text" name="n_documento" id="n_documento" required>
            </div>

            <!-- Botón Buscar -->
            <div class="form-group">
                <button type="button" class="button" onclick="buscarNombre()">Buscar Nombre</button>
            </div>

            <!-- Nombre Completo -->
            <div class="form-group">
                <label for="apellidos_nombres">Nombre Completo</label>
                <input type="text" name="apellidos_nombres" id="apellidos_nombres" readonly>
            </div>

            <!-- Fecha de Ingreso -->
            <div class="form-group">
                <label for="fecha_ingreso">Fecha de Ingreso</label>
                <input type="date" name="fecha_ingreso" id="fecha_ingreso" required>
            </div>

            <!-- Área -->
            <div class="form-group">
                <label for="area">Área</label>
                <select name="area" id="area" required>
                    <option value="Administración y Finanzas">Administración y Finanzas</option>
                    <option value="Comercial">Comercial</option>
                    <option value="Gerencia General">Gerencia General</option>
                    <option value="Gestión & QHSE">Gestión & QHSE</option>
                    <option value="Gestión de Proyectos e Innovación">Gestión de Proyectos e Innovación</option>
                    <option value="Medio Ambiente, Suelos & Remediación">Medio Ambiente, Suelos & Remediación</option>
                    <option value="Recursos Humanos">Recursos Humanos</option>
                    <option value="Seguridad Industrial & de Procesos">Seguridad Industrial & de Procesos</option>
                </select>
            </div>

            <!-- Correo -->
            <div class="form-group">
                <label for="correo">Correo</label>
                <input type="email" name="correo" id="correo" required>
            </div>

            <!-- Botones -->
            <button type="submit" class="button">Guardar</button>
            <a href="/colaboradores" class="button cancel">Cancelar</a>
        </form>
    </div>

    <!-- Script -->
    <script>
        async function buscarNombre() {
            const tipoDocumento = document.getElementById("tipo_documento").value;
            const nDocumento = document.getElementById("n_documento").value;

            if (!tipoDocumento || !nDocumento) {
                alert("Debe completar Tipo Documento y Número Documento.");
                return;
            }

            try {
                // 1. Verificar si el colaborador ya existe en el sistema
                const searchUrl = `/colaboradores/search?tipo_documento=${tipoDocumento}&n_documento=${nDocumento}`;
                const searchResponse = await fetch(searchUrl);

                if (!searchResponse.ok) {
                    throw new Error("Error al verificar el colaborador existente.");
                }

                const searchResult = await searchResponse.json();

                // 2. Validar si la data contiene el colaborador
                if (Array.isArray(searchResult.data) && searchResult.data.length > 0) {
                    const colaborador = searchResult.data[0];
                    alert(`El colaborador ya existe: ${colaborador.APELLIDOS_NOMBRES}`);
                    return;
                }

                // 3. Si no existe, buscar el nombre en el API externo
                const tipoFormateado = tipoDocumento.padStart(2, '0');
                const externalUrl = `https://master-database.vercel.app/masterDoc?DNIType=${tipoFormateado}&DNINumber=${nDocumento}`;
                
                const response = await fetch(externalUrl);

                if (!response.ok) {
                    throw new Error("Error al consultar el API externo.");
                }

                const result = await response.json();

                // 4. Verificar si la respuesta contiene el nombre
                if (Array.isArray(result) && result.length > 0 && result[0].Apellidosy3Nombres) {
                    document.getElementById("apellidos_nombres").value = result[0].Apellidosy3Nombres;
                } else {
                    alert("No se encontró el nombre de trabajador en la máster");
                    document.getElementById("apellidos_nombres").value = "";
                }
            } catch (error) {
                console.error("Error en la búsqueda:", error);
                alert("Ocurrió un error durante la búsqueda.");
            }
        }

    </script>
</body>
</html>
