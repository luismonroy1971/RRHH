<?php
// Verificar si la sesión no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener valores de sesión
$nombreUsuario = $_SESSION['username'] ?? 'Usuario';
$rolUsuario = $_SESSION['role'] ?? 'INVITADO';

// Definir array de meses
$meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
          "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

// Verificar y sanitizar los datos del legajo
if (!isset($legajo) || !is_array($legajo)) {
    error_log("Legajo no definido o no es un array");
    $legajo = [];
}

// Verificar cada clave necesaria
$defaults = [
    'ID' => '',
    'TIPO_DOCUMENTO' => '',
    'N_DOCUMENTO' => '',
    'APELLIDOS_NOMBRES' => '',
    'DOCUMENTO_ID' => '',
    'EJERCICIO' => '',
    'PERIODO' => '',
    'EMITIDO' => '',
    'SUBIDO' => '',
    'FISICO' => 0,
    'EMITIDO_OBSERVACION' => '',
    'SUBIDO_OBSERVACION' => '',
    'FISICO_OBSERVACION' => ''
];

// Combinar con los valores por defecto
foreach ($defaults as $key => $value) {
    if (!isset($legajo[$key])) {
        $legajo[$key] = $value;
        error_log("Clave no encontrada en legajo: {$key}, usando valor por defecto");
    }
}

// Asegurarse de que documentoDescripcion esté definida
if (!isset($documentoDescripcion)) {
    error_log("documentoDescripcion no definida, usando valor por defecto");
    $documentoDescripcion = 'No disponible';
}?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Legajo</title>
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
        }

        .modal-button:hover {
            background-color: #b91c1c;
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

    <div class="top-bar">
        <a href="/legajo">← Retornar Gestión de Legajos</a>
        <div><?= htmlspecialchars($nombreUsuario) ?> | Perfil: <?= htmlspecialchars($rolUsuario) ?></div>
    </div>

    <div class="container">
        <div id="alertMessage" class="alert alert-error"></div>
        
        <h1>Editar Legajo</h1>
        <form id="legajoForm" onsubmit="return submitForm(event)" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($legajo['ID']) ?>">
            
            <!-- Campos comunes (readonly) -->
            <div class="form-group">
                <label for="tipo_documento">Tipo Documento</label>
                <select name="tipo_documento" id="tipo_documento" disabled>
                    <option value="1" <?= $legajo['TIPO_DOCUMENTO'] === '1' ? 'selected' : '' ?>>Documento Nacional de Identidad</option>
                    <option value="4" <?= $legajo['TIPO_DOCUMENTO'] === '4' ? 'selected' : '' ?>>Carné de Extranjería</option>
                </select>
                <input type="hidden" name="tipo_documento" value="<?= htmlspecialchars($legajo['TIPO_DOCUMENTO']) ?>">
            </div>

            <div class="form-group">
                <label for="n_documento">Número Documento</label>
                <input type="text" id="n_documento" value="<?= htmlspecialchars($legajo['N_DOCUMENTO']) ?>" readonly>
                <input type="hidden" name="n_documento" value="<?= htmlspecialchars($legajo['N_DOCUMENTO']) ?>">
            </div>

            <div class="form-group">
                <label for="apellidos_nombres">Apellidos y Nombres</label>
                <input type="text" id="apellidos_nombres" value="<?= htmlspecialchars($legajo['APELLIDOS_NOMBRES']) ?>" readonly>
            </div>

            <div class="form-group">
                <label for="documento_id">Documento</label>
                <select id="documento_id" readonly>
                    <option value="<?= htmlspecialchars($legajo['DOCUMENTO_ID']) ?>"><?= htmlspecialchars($documentoDescripcion) ?></option>
                </select>
                <input type="hidden" name="documento_id" value="<?= htmlspecialchars($legajo['DOCUMENTO_ID']) ?>">
            </div>

            <div class="form-group">
                <label for="ejercicio">Ejercicio</label>
                <select id="ejercicio" disabled>
                    <?php for($i = 2014; $i <= 2025; $i++): ?>
                        <option value="<?= $i ?>" <?= $legajo['EJERCICIO'] == $i ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
                <input type="hidden" name="ejercicio" value="<?= htmlspecialchars($legajo['EJERCICIO']) ?>">
            </div>

            <div class="form-group">
                <label for="periodo">Periodo</label>
                <select id="periodo" disabled>
                    <?php foreach ($meses as $index => $mes): ?>
                        <option value="<?= $index + 1 ?>" <?= $legajo['PERIODO'] == ($index + 1) ? 'selected' : '' ?>><?= $mes ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="periodo" value="<?= htmlspecialchars($legajo['PERIODO']) ?>">
            </div>

            <?php if ($rolUsuario === 'RRHH'): ?>
                <div class="form-group">
                    <label for="subido">Subir Archivo (Subido)</label>
                    <input type="file" name="subido" id="subido" accept=".pdf">
                    <?php if (!empty($legajo['SUBIDO'])): ?>
                        <div style="margin-top: 0.5rem;">
                            <a href="<?= htmlspecialchars($legajo['SUBIDO']) ?>" target="_blank">Ver archivo actual</a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="subido_observacion">Observación Subido</label>
                    <textarea name="subido_observacion" id="subido_observacion" rows="2"><?= htmlspecialchars($legajo['SUBIDO_OBSERVACION']) ?></textarea>
                </div>
            <?php elseif ($rolUsuario === 'RECEPCION'): ?>
                <div class="form-group">
                    <label for="fisico">Documento Físico</label>
                    <select name="fisico" id="fisico">
                        <option value="1" <?= $legajo['FISICO'] == 1 ? 'selected' : '' ?>>Sí</option>
                        <option value="0" <?= $legajo['FISICO'] == 0 || $legajo['FISICO'] === null ? 'selected' : '' ?>>No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fisico_observacion">Observación Física</label>
                    <textarea name="fisico_observacion" id="fisico_observacion" rows="2"><?= htmlspecialchars($legajo['FISICO_OBSERVACION']) ?></textarea>
                </div>
            <?php endif; ?>

            <button type="submit" class="button save">Guardar Cambios</button>
            <a href="/legajo" class="cancel-button">Cancelar</a>
        </form>
    </div>

    <script>
    async function submitForm(event) {
        event.preventDefault();
        
        const form = document.getElementById('legajoForm');
        const formData = new FormData(form);

        try {
            const response = await fetch('/legajo/update', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            
            if (!response.ok) {
                showErrorModal(result.error || 'Error al actualizar el legajo');
                return false;
            }

            if (result.success) {
                // Redirigir solo si la actualización fue exitosa
                window.location.href = '/legajo?message=' + encodeURIComponent(result.message);
            } else {
                showErrorModal(result.error || 'Error al actualizar el legajo');
            }

            return false;
        } catch (error) {
            console.error('Error:', error);
            showErrorModal('Error al procesar la solicitud');
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

    window.onclick = function(event) {
        const modal = document.getElementById('errorModal');
        if (event.target == modal) {
            closeErrorModal();
        }
    }
    </script>
</body>
</html>
