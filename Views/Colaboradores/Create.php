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
    <title>Agregar Colaborador | Sistema de Gestión</title>
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
            font-family: "Segoe UI", Arial, sans-serif;
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
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        h1 {
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
        
        h1 i {
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        h1::after {
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
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        
        input:focus, select:focus, textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(44, 123, 229, 0.25);
            outline: none;
        }

        input[disabled], input[readonly] {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
        
        /* Ayuda visual para campos obligatorios */
        .required::after {
            content: ' *';
            color: var(--danger-color);
            font-weight: bold;
        }
        
        /* Estructura de dos columnas para el formulario */
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -10px;
            margin-left: -10px;
        }
        
        .form-col {
            flex: 1 0 50%;
            padding: 0 10px;
        }
        
        @media (max-width: 768px) {
            .form-col {
                flex: 1 0 100%;
            }
        }

        /* Botones */
        .btn-group {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            gap: 15px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: var(--success-color);
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
        
        .button i {
            margin-right: 8px;
        }

        .button.cancel {
            background-color: var(--secondary-color);
        }
        
        .button.search {
            background-color: var(--primary-color);
            margin-top: 8px;
        }

        .button:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        
        .button:active {
            transform: translateY(1px);
        }

        /* Tooltip para ayuda */
        .tooltip {
            position: relative;
            display: inline-block;
            margin-left: 5px;
            cursor: help;
        }
        
        .tooltip i {
            color: var(--secondary-color);
        }
        
        .tooltip .tooltip-text {
            visibility: hidden;
            width: 200px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -100px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 0.8rem;
            font-weight: normal;
        }
        
        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <a href="/colaboradores"><i class="fas fa-arrow-left"></i> Retornar a Gestión de Colaboradores</a>
        <div class="user-info">
            <i class="fas fa-user-circle"></i>
            <span><?= htmlspecialchars($nombreUsuario) ?> | Perfil: <?= htmlspecialchars($rolUsuario) ?></span>
        </div>
    </div>
    
    <div class="container">
        <h1><i class="fas fa-user-plus"></i> Agregar Colaborador</h1>
        
        <form action="/colaboradores" method="POST">
            <div class="form-row">
                <!-- Tipo de Documento -->
                <div class="form-col">
                    <div class="form-group">
                        <label for="tipo_documento" class="required">Tipo Documento</label>
                        <select name="tipo_documento" id="tipo_documento" required>
                            <option value="">Seleccione un tipo</option>
                            <option value="1">DNI - Documento Nacional de Identidad</option>
                            <option value="4">CE - Carné de Extranjería</option>
                            <option value="7">PAS - Pasaporte</option>
                        </select>
                    </div>
                </div>
                
                <!-- Número de Documento -->
                <div class="form-col">
                    <div class="form-group">
                        <label for="n_documento" class="required">Número Documento</label>
                        <input type="text" name="n_documento" id="n_documento" 
                               required pattern="[0-9A-Za-z]+" 
                               title="Solo se permiten números y letras sin espacios">
                    </div>
                </div>
            </div>

            <!-- Botón Buscar Nombre -->
            <button type="button" id="btnBuscar" class="button search" onclick="buscarNombre()">
                <i class="fas fa-search"></i> Buscar Nombre
            </button>

            <!-- Nombre Completo -->
            <div class="form-group">
                <label for="apellidos_nombres" class="required">Nombre Completo</label>
                <div style="display: flex; align-items: center;">
                    <input type="text" name="apellidos_nombres" id="apellidos_nombres" required readonly>
                    <div class="tooltip">
                        <i class="fas fa-info-circle"></i>
                        <span class="tooltip-text">Este campo se completa automáticamente al buscar el documento</span>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <!-- Fecha de Ingreso -->
                <div class="form-col">
                    <div class="form-group">
                        <label for="fecha_ingreso" class="required">Fecha de Ingreso</label>
                        <input type="date" name="fecha_ingreso" id="fecha_ingreso" required>
                    </div>
                </div>
                
                <!-- Área -->
                <div class="form-col">
                    <div class="form-group">
                        <label for="area" class="required">Área</label>
                        <select name="area" id="area" required>
                            <option value="">Seleccione un área</option>
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
                </div>
            </div>

            <!-- Correo -->
            <div class="form-group">
                <label for="correo" class="required">Correo Electrónico</label>
                <input type="email" name="correo" id="correo" required>
            </div>
            
            <!-- Teléfono (nuevo campo) -->
            <div class="form-group">
                <label for="telefono">Teléfono / Celular</label>
                <input type="tel" name="telefono" id="telefono" pattern="[0-9]+" title="Solo se permiten números">
            </div>
            
            <!-- Estado (nuevo campo) -->
            <div class="form-group">
                <label for="estado" class="required">Estado</label>
                <select name="estado" id="estado" required>
                    <option value="ACTIVO" selected>ACTIVO</option>
                    <option value="INACTIVO">INACTIVO</option>
                </select>
            </div>

            <!-- Botones -->
            <div class="btn-group">
                <a href="/colaboradores" class="button cancel"><i class="fas fa-times"></i> Cancelar</a>
                <button type="submit" class="button">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>

    <script>
        // Configurar fecha máxima (hoy) para fecha de ingreso
        document.addEventListener('DOMContentLoaded', function() {
            const fechaIngreso = document.getElementById('fecha_ingreso');
            const hoy = new Date();
            const fechaFormateada = hoy.toISOString().split('T')[0];
            fechaIngreso.max = fechaFormateada;
            
            // Al cambiar el tipo de documento, limpiar el número y nombre
            document.getElementById('tipo_documento').addEventListener('change', function() {
                document.getElementById('n_documento').value = '';
                document.getElementById('apellidos_nombres').value = '';
            });
        });
        
        // Función simple para buscar nombre, utilizando alert en lugar de AJAX
        async function buscarNombre() {
            const tipoDocumento = document.getElementById("tipo_documento").value;
            const nDocumento = document.getElementById("n_documento").value;

            if (!tipoDocumento || !nDocumento) {
                alert("Debe completar Tipo Documento y Número Documento.");
                return;
            }
            
            // Validar longitud del DNI (8 dígitos) si es DNI
            if (tipoDocumento === "1" && nDocumento.length !== 8) {
                alert("El DNI debe tener exactamente 8 dígitos.");
                return;
            }

            try {
                // 1. Verificar si el colaborador ya existe en el sistema
                const searchUrl = `/colaboradores/search?tipo_documento=${tipoDocumento}&n_documento=${nDocumento}`;
                const searchResponse = await fetch(searchUrl);
                const searchResult = await searchResponse.json();

                // 2. Validar si la data contiene el colaborador
                if (Array.isArray(searchResult.data) && searchResult.data.length > 0) {
                    const colaborador = searchResult.data[0];
                    alert(`El colaborador ya existe en el sistema: ${colaborador.APELLIDOS_NOMBRES}`);
                    return;
                }

                // 3. Si no existe, buscar el nombre en el API externo
                const tipoFormateado = tipoDocumento.padStart(2, '0');
                const externalUrl = `https://master-database.vercel.app/masterDoc?DNIType=${tipoFormateado}&DNINumber=${nDocumento}`;
                
                const response = await fetch(externalUrl);
                const result = await response.json();

                // 4. Verificar si la respuesta contiene el nombre
                if (Array.isArray(result) && result.length > 0 && result[0].Apellidosy3Nombres) {
                    document.getElementById("apellidos_nombres").value = result[0].Apellidosy3Nombres;
                    
                    // Sugerir correo electrónico basado en el nombre
                    sugerirCorreo(result[0].Apellidosy3Nombres);
                    
                    alert("Información recuperada exitosamente.");
                } else {
                    alert("No se encontró información del colaborador en la base de datos maestra.");
                    document.getElementById("apellidos_nombres").value = "";
                }
            } catch (error) {
                console.error("Error en la búsqueda:", error);
                alert("Ocurrió un error durante la búsqueda. Intente nuevamente.");
            }
        }
        
        // Función para generar una sugerencia de correo
        function sugerirCorreo(nombreCompleto) {
            // Solo sugerir si el campo está vacío
            if (document.getElementById('correo').value !== '') {
                return;
            }
            
            const nombre = nombreCompleto.toLowerCase()
                .normalize("NFD").replace(/[\u0300-\u036f]/g, "") // Eliminar acentos
                .split(' ');
            
            // Si hay al menos 2 palabras, usar inicial del primer nombre + apellido
            if (nombre.length >= 2) {
                // El último elemento se considera el apellido paterno
                const apellido = nombre[nombre.length - 1];
                // El primer elemento se considera el primer nombre
                const primeraInicial = nombre[0].charAt(0);
                
                const sugerencia = `${primeraInicial}${apellido}@empresa.com`;
                document.getElementById('correo').value = sugerencia;
            }
        }
    </script>
</body>
</html>