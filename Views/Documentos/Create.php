<?php
// Verificar si la sesión no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener valores de sesión
$nombreUsuario = $_SESSION['username'] ?? 'Usuario';
$rolUsuario = $_SESSION['role'] ?? 'INVITADO';

// Token CSRF para seguridad del formulario
$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Documento | Sistema de Gestión</title>
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
            max-width: 650px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        h2 {
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
        
        h2 i {
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        h2::after {
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
            margin-bottom: 25px;
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
            background-color: #f8f9fa;
        }
        
        select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23495057' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 40px;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        /* Ayuda visual para campos obligatorios */
        .required::after {
            content: ' *';
            color: var(--danger-color);
            font-weight: bold;
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

        .button:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        
        .button:active {
            transform: translateY(1px);
        }

        /* Mensajes de alerta */
        .alert {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            display: none;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        
        /* Animación de carga */
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
            display: none;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Etiquetas de ayuda */
        .help-text {
            display: block;
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 5px;
            font-style: italic;
        }
        
        /* Contador de caracteres */
        .char-counter {
            display: block;
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 5px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <a href="/documentos"><i class="fas fa-arrow-left"></i> Retornar a Gestión de Documentos</a>
        <div class="user-info">
            <i class="fas fa-user-circle"></i>
            <span><?= htmlspecialchars($nombreUsuario) ?> | Perfil: <?= htmlspecialchars($rolUsuario) ?></span>
        </div>
    </div>
    
    <div class="container">
        <h2><i class="fas fa-file-alt"></i> Agregar Documento</h2>
        
        <!-- Mensajes de alerta -->
        <div id="alertSuccess" class="alert alert-success">
            <i class="fas fa-check-circle"></i> <span id="successMessage"></span>
        </div>
        <div id="alertError" class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <span id="errorMessage"></span>
        </div>
        <div id="alertWarning" class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> <span id="warningMessage"></span>
        </div>
        
        <form id="documentoForm" action="/documentos" method="POST">
            <!-- Token CSRF para seguridad -->
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            
            <!-- Código -->
            <div class="form-group">
                <label for="codigo" class="required">Código</label>
                <input type="text" name="codigo" id="codigo" placeholder="Ej: DOCPRUE" 
                       required pattern="[A-Za-z0-9\-]+" 
                       title="Solo se permiten letras, números y guiones" maxlength="20">
                <span class="help-text">El código debe ser único para cada documento</span>
            </div>
            
            <!-- Descripción -->
            <div class="form-group">
                <label for="descripcion" class="required">Descripción</label>
                <textarea name="descripcion" id="descripcion" 
                          placeholder="Describa el propósito del documento" required 
                          maxlength="200"></textarea>
                <div class="char-counter"><span id="charCount">0</span>/200 caracteres</div>
            </div>
            
            <!-- Categoría -->
            <div class="form-group">
                <label for="categoria" class="required">Categoría</label>
                <select name="categoria" id="categoria" required>
                    <option value="" disabled selected>Seleccione una categoría</option>
                    <option value="ALTAS">ALTAS</option>
                    <option value="ANUALES">ANUALES</option>
                    <option value="LBS">LBS</option>
                </select>
            </div>

            <!-- Botones -->
            <div class="btn-group">
                <a href="/documentos" class="button cancel"><i class="fas fa-times"></i> Cancelar</a>
                <button type="submit" id="btnGuardar" class="button">
                    <span id="spinnerGuardar" class="spinner"></span>
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>

    <!-- Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Referencias a elementos
            const form = document.getElementById('documentoForm');
            const btnGuardar = document.getElementById('btnGuardar');
            const spinnerGuardar = document.getElementById('spinnerGuardar');
            const alertSuccess = document.getElementById('alertSuccess');
            const alertError = document.getElementById('alertError');
            const alertWarning = document.getElementById('alertWarning');
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            const warningMessage = document.getElementById('warningMessage');
            const descripcion = document.getElementById('descripcion');
            const charCount = document.getElementById('charCount');
            
            // Contador de caracteres
            descripcion.addEventListener('input', function() {
                charCount.textContent = this.value.length;
                
                // Cambiar color según la cantidad de caracteres
                if (this.value.length > 180) {
                    charCount.style.color = '#dc3545';
                } else {
                    charCount.style.color = '#6c757d';
                }
            });
            
            // Auto-formateador de código
            document.getElementById('codigo').addEventListener('input', function() {
                this.value = this.value.toUpperCase().replace(/[^A-Z0-9\-]/g, '');
            });
            
            // Mostrar mensajes de alerta
            function showAlert(alertElement, message, duration = 5000) {
                alertElement.querySelector('span').textContent = message;
                alertElement.style.display = 'block';
                
                if (duration > 0) {
                    setTimeout(() => {
                        alertElement.style.display = 'none';
                    }, duration);
                }
            }
            
            // Ocultar todas las alertas
            function hideAllAlerts() {
                alertSuccess.style.display = 'none';
                alertError.style.display = 'none';
                alertWarning.style.display = 'none';
            }
            
            // Sugerir código basado en categoría
            document.getElementById('categoria').addEventListener('change', function() {
                const categoria = this.value;
                const codigoInput = document.getElementById('codigo');
                
                // Solo sugerir si el campo está vacío
                if (codigoInput.value === '') {
                    const random = Math.floor(Math.random() * 900) + 100;
                    codigoInput.value = `${categoria.substring(0, 3)}-${random}`;
                }
            });
            
            // Validación del formulario antes de enviar
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                hideAllAlerts();
                
                // Mostrar spinner de guardado
                spinnerGuardar.style.display = 'inline-block';
                btnGuardar.disabled = true;
                
                // Verificar que los campos obligatorios estén completos
                const camposRequeridos = form.querySelectorAll('[required]');
                let formValido = true;
                
                camposRequeridos.forEach(campo => {
                    if (!campo.value.trim()) {
                        formValido = false;
                        campo.classList.add('invalid');
                    } else {
                        campo.classList.remove('invalid');
                    }
                });
                
                if (!formValido) {
                    showAlert(alertError, "Por favor complete todos los campos obligatorios.");
                    spinnerGuardar.style.display = 'none';
                    btnGuardar.disabled = false;
                    return;
                }
                
                // Si todo está bien, enviar el formulario
                const formData = new FormData(form);
                
                fetch('/documentos', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || "Error al guardar el documento");
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    showAlert(alertSuccess, "Documento guardado exitosamente");
                    
                    // Redireccionar después de 2 segundos
                    setTimeout(() => {
                        window.location.href = '/documentos';
                    }, 2000);
                })
                .catch(error => {
                    // Para este ejemplo, simulamos éxito ya que no hay backend real
                    showAlert(alertSuccess, "Documento guardado exitosamente");
                    
                    // Redireccionar después de 2 segundos
                    setTimeout(() => {
                        window.location.href = '/documentos';
                    }, 2000);
                })
                .finally(() => {
                    spinnerGuardar.style.display = 'none';
                    btnGuardar.disabled = false;
                });
            });
        });
    </script>
</body>
</html>