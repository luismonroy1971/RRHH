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
    <title>Agregar Legajo</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 0; }
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
        .container { max-width: 800px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        h1 { text-align: center; margin-bottom: 20px; color: #007bff; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select, textarea { width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px 15px; border: none; color: #fff; border-radius: 4px; cursor: pointer; }
      
        .button.cancel { background-color: #6c757d; }
        .add-button {
            display: inline-block;
            background-color: #059669;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            text-decoration: none;
            margin-bottom: 2rem;
            transition: background-color 0.2s;
        }
        .cancel-button {
            display: inline-block;
            background-color:rgb(236, 48, 23);
            color: white;
            padding: 0.6rem 1rem;
            border-radius: 0.375rem;
            text-decoration: none;
            margin-bottom: 2rem;
            transition: background-color 0.2s;
        }
        .button.save { display: inline-block;
            background-color: #059669;
            color: white;
            padding: 0.6rem 1rem;
            border-radius: 0.375rem;
            text-decoration: none;
            margin-bottom: 2rem;
            transition: background-color 0.2s; 
        }
        /* Estilos para el modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .modal-content {
            position: relative;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            width: 90%;
            text-align: center;
        }

        .modal-error {
            color: #dc2626;
            margin-bottom: 20px;
        }

        .modal-button {
            background-color: #dc2626;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 5px;
        }

        .modal-button.confirm {
            background-color: #059669;
        }

        .modal-button:hover {
            opacity: 0.9;
        }

        /* Estilos para mensaje de alerta */
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.375rem;
            display: none;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }
    </style>
</head>
<body>
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <h2 style="color: #dc2626; margin-bottom: 1rem;">Error</h2>
            <p id="errorMessage" class="modal-error"></p>
            <button onclick="closeErrorModal()" class="modal-button">Aceptar</button>
        </div>
    </div>

    <!-- Modal de confirmación para envío de correo -->
    <div id="emailConfirmModal" class="modal">
        <div class="modal-content">
            <h2 style="color: #0f766e; margin-bottom: 1rem;">Confirmación</h2>
            <p>¿Desea enviar un correo de notificación de esta grabación?</p>
            <div style="margin-top: 20px;">
                <button onclick="submitWithEmail(true)" class="modal-button confirm">Sí</button>
                <button onclick="submitWithEmail(false)" class="modal-button" style="background-color: #6c757d;">No</button>
            </div>
        </div>
    </div>
    
    <!-- Barra Superior -->
    <div class="top-bar">
        <a href="/legajo">← Retornar Gestión de Legajos</a>
        <div><?= htmlspecialchars($nombreUsuario) ?> | Perfil: <?= htmlspecialchars($rolUsuario) ?></div>
    </div>

    <div class="container">
        <!-- Alerta para mensajes de error -->
        <div id="alertMessage" class="alert alert-error"></div>
        <h1>Agregar Legajo</h1>
        <form id="legajoForm" onsubmit="return confirmEmail(event)" enctype="multipart/form-data">
            <div class="form-group">
                <label for="tipo_documento">Tipo Documento</label>
                <select name="tipo_documento" id="tipo_documento" required>
                    <option value="1">Documento Nacional de Identidad</option>
                    <option value="4">Carné de Extranjería</option>
                </select>
            </div>

            <div class="form-group">
                <label for="n_documento">Número Documento</label>
                <input type="text" name="n_documento" id="n_documento" required>
            </div>

            <button class="add-button" type="button" id="buscar_nombre" onclick="buscarNombre()">Buscar Nombre Completo</button>

            <div class="form-group">
                <label for="apellidos_nombres">Apellidos y Nombres</label>
                <input type="text" name="apellidos_nombres" id="apellidos_nombres" readonly>
            </div>

            <!-- Campos comunes -->
            <div class="form-group">
                <label for="documento_id">Documento</label>
                <select name="documento_id" id="documento_id" required></select>
            </div>
            <div class="form-group">
                <label for="ejercicio">Ejercicio</label>
                <select name="ejercicio" id="ejercicio" required>
                    <option value="2014">2014</option>
                    <option value="2015">2015</option>
                    <option value="2016">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                </select>
            </div>
            <div class="form-group">
                <label for="periodo">Periodo</label>
                <select name="periodo" id="periodo" required>
                    <?php
                        $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                        foreach ($meses as $index => $mes) {
                            echo "<option value='" . ($index + 1) . "'>$mes</option>";
                        }
                    ?>
                </select>
            </div>

            <!-- Campos específicos por perfil -->
            <?php if ($rolUsuario === 'NOMINAS'): ?>
                <div class="form-group">
                    <label for="emitido">Subir Archivo (Emitido) - solo formato PDF</label>
                    <input type="file" name="emitido" id="emitido" accept=".pdf">
                </div>
                <div class="form-group">
                    <label for="emitido_observacion">Observación Emitido</label>
                    <textarea name="emitido_observacion" id="emitido_observacion" rows="2"></textarea>
                </div>
            <?php elseif ($rolUsuario === 'RRHH'): ?>
                <div class="form-group">
                    <label for="subido">Subir Archivo (Subido) - solo formato PDF</label>
                    <input type="file" name="subido" id="subido" accept=".pdf">
                </div>
                <div class="form-group">
                    <label for="subido_observacion">Observación Subido</label>
                    <textarea name="subido_observacion" id="subido_observacion" rows="2"></textarea>
                </div>
            <?php elseif ($rolUsuario === 'RECEPCION'): ?>
                <div class="form-group">
                    <label for="fisico">Documento Físico</label>
                    <select name="fisico" id="fisico" required>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fisico_observacion">Observación Física</label>
                    <textarea name="fisico_observacion" id="fisico_observacion" rows="2"></textarea>
                </div>
            <?php endif; ?>

            <button type="submit" class="button save">Guardar</button>
            <a href="/legajo" class="cancel-button">Cancelar</a>
            
            <!-- Campo oculto para indicar si enviar correo -->
            <input type="hidden" name="enviar_correo" id="enviar_correo" value="0">
        </form>
    </div>

    <script>
        // Función para confirmar envío de correo
        function confirmEmail(event) {
            event.preventDefault(); // Detiene el envío del formulario
            
            // Valida que el formulario esté completo
            const form = document.getElementById('legajoForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return false;
            }
            
            // Mostrar modal de confirmación
            document.getElementById('emailConfirmModal').style.display = 'block';
            return false;
        }
        
        // Función para manejar la respuesta del usuario sobre el correo
        function submitWithEmail(sendEmail) {
            try {
                document.getElementById('emailConfirmModal').style.display = 'none';
                document.getElementById('enviar_correo').value = sendEmail ? "1" : "0";
                // Llamar a submitForm como una función normal, no como una promesa
                submitForm();
            } catch (error) {
                console.error('Error en submitWithEmail:', error);
                showErrorModal('Error al procesar la solicitud de correo.');
            }
        }
        
        // Función para enviar el formulario
        async function submitForm() {
            const form = document.getElementById('legajoForm');
            const formData = new FormData(form);

            try {
                console.log("Enviando formulario...");
                
                // Intentar con la ruta /legajo
                const response = await fetch('/legajo', {
                    method: 'POST',
                    body: formData
                });

                console.log("Respuesta recibida. Status:", response.status);
                console.log("Headers:", response.headers);
                
                // Obtener el tipo de contenido
                const contentType = response.headers.get('content-type');
                console.log("Content-Type:", contentType);
                
                // Capturar el texto de la respuesta primero
                const rawText = await response.text();
                console.log("Respuesta cruda:", rawText);
                
                let result;
                
                // Intentar parsear como JSON solo si parece un JSON
                if (contentType && contentType.includes('application/json')) {
                    try {
                        result = JSON.parse(rawText);
                        console.log("Respuesta parseada como JSON:", result);
                    } catch (e) {
                        console.error("Error al parsear JSON:", e);
                        showErrorModal("Error en la comunicación con el servidor. La respuesta no es JSON válido.");
                        return false;
                    }
                } else {
                    // Si no es JSON, mostrar el error con el texto crudo
                    console.error("La respuesta no es JSON:", rawText);
                    showErrorModal("Error en la comunicación con el servidor. Respuesta no válida.");
                    return false;
                }

                // Procesar la respuesta
                if (response.ok && result.success) {
                    // Éxito - redireccionar
                    window.location.href = '/legajo';
                    return false;
                } else {
                    // Error con mensaje del servidor
                    showErrorModal(result.error || 'Error al crear el legajo');
                    return false;
                }
            } catch (error) {
                console.error('Error completo en submitForm:', error);
                showErrorModal('Error al procesar la solicitud. Por favor, intente nuevamente.');
                return false;
            }
        }

        function showErrorModal(message) {
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('errorModal').style.display = 'block';
        }

        function closeErrorModal() {
            document.getElementById('errorModal').style.display = 'none';
        }

        // Cerrar el modal si se hace clic fuera de él
        window.onclick = function(event) {
            const modal = document.getElementById('errorModal');
            const emailModal = document.getElementById('emailConfirmModal');
            if (event.target == modal) {
                closeErrorModal();
            }
            if (event.target == emailModal) {
                emailModal.style.display = 'none';
            }
        }
        
        async function buscarNombre() {
            const tipoDocumento = document.getElementById("tipo_documento").value;
            const nDocumento = document.getElementById("n_documento").value;
            const url = `/colaboradores/search?tipo_documento=${tipoDocumento}&n_documento=${nDocumento}`;
            try {
                const response = await fetch(url);
                const result = await response.json();
                if (result.data && result.data.length > 0) {
                    document.getElementById("apellidos_nombres").value = result.data[0].APELLIDOS_NOMBRES;
                } else {
                    showErrorModal("No existe trabajador con ese documento.");
                    document.getElementById("apellidos_nombres").value = "";
                }
            } catch (error) {
                showErrorModal("Error al consultar nombre.");
                console.error(error);
            }
        }

        async function cargarDocumentos() {
            const response = await fetch('/documentos/list');
            const result = await response.json();
            const select = document.getElementById('documento_id');
            select.innerHTML = '<option value="">Seleccione un documento</option>';
            result.data.forEach(doc => {
                const option = document.createElement('option');
                option.value = doc.ID;
                option.textContent = `${doc.DESCRIPCION} (${doc.CODIGO})`;
                select.appendChild(option);
            });
        }

        cargarDocumentos();
    </script>
</body>
</html>