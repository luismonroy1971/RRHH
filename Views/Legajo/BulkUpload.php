<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nombreUsuario = $_SESSION['username'] ?? 'Usuario';
$rolUsuario = $_SESSION['role'] ?? 'INVITADO';

if ($rolUsuario !== 'NOMINAS') {
    header('Location: /legajo');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carga Masiva de Legajos</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f8f9fa; 
            margin: 0; 
            padding: 0; 
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #343a40;
            color: #fff;
            padding: 10px 20px;
        }
        .top-bar a { 
            color: #f8d7da; 
            text-decoration: none; 
            font-weight: 600; 
        }
        .top-bar a:hover { 
            color: #fff; 
        }
        .container { 
            max-width: 800px; 
            margin: 50px auto; 
            background: #fff; 
            padding: 20px; 
            border-radius: 8px; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
        }
        h1 { 
            text-align: center; 
            margin-bottom: 20px; 
            color: #007bff; 
        }
        .upload-area {
            border: 2px dashed #ccc;
            padding: 40px;
            text-align: center;
            border-radius: 8px;
            margin: 20px 0;
            cursor: pointer;
            position: relative;
        }
        .upload-area.dragover {
            border-color: #059669;
            background-color: #f0fdf4;
        }
        .upload-area p {
            margin: 0;
            font-size: 1.1em;
            color: #666;
        }
        .upload-area small {
            display: block;
            margin-top: 10px;
            color: #888;
        }
        .file-input {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }
        .result-box {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            display: none;
        }
        .success-box {
            background-color: #d1fae5;
            color: #065f46;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .error-box {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 15px;
            border-radius: 8px;
        }
        .error-list {
            margin: 10px 0;
            padding-left: 20px;
        }
        .error-list li {
            margin-bottom: 5px;
        }
        .processing {
            display: none;
            text-align: center;
            padding: 20px;
            font-weight: bold;
            color: #059669;
        }
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
        <h1>Carga Masiva de Legajos</h1>
        
        <div class="upload-area" id="uploadArea">
            <input type="file" id="fileInput" class="file-input" webkitdirectory directory multiple accept=".pdf">
            <p>Haga clic aquí para seleccionar una carpeta</p>
            <small>O arrastre y suelte una carpeta aquí</small>
            <div class="replace-option" style="text-align: center; margin: 20px 0;">
                <label style="cursor: pointer;">
                    <input type="checkbox" id="replaceIfExists" style="margin-right: 8px;">
                    Reemplazar si Existe
                </label>
            </div>
        </div>

        <div id="downloadArea" style="text-align: center; margin: 20px 0; display: none;">
            <button id="downloadButton" class="add-button">
                Descargar Registros No Procesados
            </button>
        </div>

        <div id="processing" class="processing">
            Procesando archivos...
        </div>

        <div id="resultBox" class="result-box">
            <div id="successBox" class="success-box">
                <p>Archivos procesados exitosamente: <span id="successCount">0</span></p>
            </div>
            <div id="errorBox" class="error-box" style="display: none;">
                <p>Archivos con errores: <span id="errorCount">0</span></p>
                <ul id="errorList" class="error-list"></ul>
            </div>
        </div>
    </div>

    <script>
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');
        const processing = document.getElementById('processing');
        const resultBox = document.getElementById('resultBox');
        const successBox = document.getElementById('successBox');
        const errorBox = document.getElementById('errorBox');
        const successCount = document.getElementById('successCount');
        const errorCount = document.getElementById('errorCount');
        const errorList = document.getElementById('errorList');

        // Variables globales para el registro de no procesados
        let notProcessedRecords = [];
        const replaceCheckbox = document.getElementById('replaceIfExists');
        const downloadArea = document.getElementById('downloadArea');
        const downloadButton = document.getElementById('downloadButton');

        // Función para procesar el nombre del archivo
        function processFileName(fileName) {
            const parts = fileName.slice(0, -4).split(' - ');
            if (parts.length !== 5) {
                throw new Error('Formato de nombre inválido');
            }
            return {
                apellidosNombres: parts[0],
                ejercicio: parts[1],
                periodo: parts[2],
                documento: parts[3],
                fechaHora: parts[4]
            };
        }

    // En la parte del script, vamos a modificar la función handleFiles:

        async function buscarColaborador(apellidosNombres) {
            try {
                console.log('Buscando colaborador:', apellidosNombres);
                
                const response = await fetch(`/colaboradores/search-by-name?nombre=${encodeURIComponent(apellidosNombres)}`);
                if (!response.ok) {
                    throw new Error('Error al buscar colaborador');
                }
                const data = await response.json();
                
                if (!data.data || data.data.length === 0) {
                    throw new Error(`No se encontró el colaborador: ${apellidosNombres}`);
                }
                
                return {
                    tipoDocumento: data.data[0].TIPO_DOCUMENTO,
                    nDocumento: data.data[0].N_DOCUMENTO
                };
            } catch (error) {
                console.error('Error completo:', error);
                throw new Error(`Error buscando colaborador: ${error.message}`);
            }
        }
        // Función para buscar documento
        async function buscarDocumento(descripcionDocumento) {
            try {
                const response = await fetch('/documentos/list');
                if (!response.ok) {
                    throw new Error('Error al obtener documentos');
                }
                const data = await response.json();
                
                const documento = data.data.find(doc => 
                    doc.DESCRIPCION.toLowerCase() === descripcionDocumento.toLowerCase()
                );
                
                if (!documento) {
                    throw new Error(`No se encontró el documento: ${descripcionDocumento}`);
                }
                
                return documento.ID;
            } catch (error) {
                throw new Error(`Error buscando documento: ${error.message}`);
            }
        }


        // Función para validar ejercicio y periodo
        function validateNumericData(ejercicio, periodo) {
            const ejercicioNum = parseInt(ejercicio);
            const periodoNum = parseInt(periodo);

            if (isNaN(ejercicioNum) || ejercicioNum < 2014 || ejercicioNum > 2025) {
                throw new Error(`Ejercicio inválido: ${ejercicio}. Debe ser un número entre 2014 y 2025`);
            }

            if (isNaN(periodoNum) || periodoNum < 1 || periodoNum > 12) {
                throw new Error(`Periodo inválido: ${periodo}. Debe ser un número entre 1 y 12`);
            }

            return {
                ejercicioNum,
                periodoNum
            };
        }

        // Función para verificar si existe la combinación
        async function verificarExistencia(datos) {
            try {
                const params = new URLSearchParams({
                    tipo_documento: datos.tipo_documento,
                    n_documento: datos.n_documento,
                    documento_id: datos.documento_id,
                    ejercicio: datos.ejercicio,
                    periodo: datos.periodo
                });

                const response = await fetch(`/legajo/verificar-existencia?${params}`);
                if (!response.ok) {
                    throw new Error('Error al verificar existencia');
                }
                const data = await response.json();
                return data.exists;
            } catch (error) {
                throw new Error(`Error en verificación: ${error.message}`);
            }
        }

        // Función para generar y descargar el archivo de registros no procesados
        function generateDownloadFile() {
            if (notProcessedRecords.length === 0) {
                downloadArea.style.display = 'none';
                return;
            }

            const content = notProcessedRecords.map(record => {
                return `Archivo: ${record.fileName}\n` +
                    `Colaborador: ${record.apellidosNombres}\n` +
                    `Documento: ${record.documento}\n` +
                    `Ejercicio: ${record.ejercicio}\n` +
                    `Periodo: ${record.periodo}\n` +
                    `Motivo: ${record.reason}\n` +
                    '-'.repeat(50);
            }).join('\n\n');

            const blob = new Blob([content], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            
            downloadButton.onclick = function() {
                const a = document.createElement('a');
                a.href = url;
                a.download = `registros_no_procesados_${new Date().toISOString().slice(0,10)}.txt`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            };

            downloadArea.style.display = 'block';
        }

        // Modificar la función handleFiles
        async function handleFiles(files) {
            processing.style.display = 'block';
            resultBox.style.display = 'none';
            downloadArea.style.display = 'none';
            notProcessedRecords = []; // Limpiar registros anteriores
            
            let success = 0;
            let errors = [];

            for (const file of files) {
                if (!file.name.toLowerCase().endsWith('.pdf')) {
                    errors.push(`${file.name} no es un archivo PDF`);
                    continue;
                }

                try {
                    const fileInfo = processFileName(file.name);
                    const colaborador = await buscarColaborador(fileInfo.apellidosNombres);
                    const documentoId = await buscarDocumento(fileInfo.documento);
                    
                    // Validar ejercicio y periodo
                    const { ejercicioNum, periodoNum } = validateNumericData(fileInfo.ejercicio, fileInfo.periodo);
                    
                    const datos = {
                        tipo_documento: colaborador.tipoDocumento,
                        n_documento: colaborador.nDocumento,
                        documento_id: documentoId,
                        ejercicio: ejercicioNum,    // Ahora aseguramos que es numérico
                        periodo: periodoNum         // Ahora aseguramos que es numérico
                    };
                    // Verificar existencia
                    const exists = await verificarExistencia(datos);
                    
                    if (exists && !replaceCheckbox.checked) {
                        // Agregar a registros no procesados
                        notProcessedRecords.push({
                            fileName: file.name,
                            apellidosNombres: fileInfo.apellidosNombres,
                            documento: fileInfo.documento,
                            ejercicio: fileInfo.ejercicio,
                            periodo: fileInfo.periodo,
                            reason: 'Registro existente y no se marcó reemplazo'
                        });
                        continue;
                    }

                    const formData = new FormData();
                    for (let [key, value] of Object.entries(datos)) {
                        formData.append(key, value);
                    }
                    formData.append('apellidos_nombres', fileInfo.apellidosNombres);
                    formData.append('emitido', file);
                    formData.append('replace_if_exists', replaceCheckbox.checked);

                    const response = await fetch('/legajo/create', {
                        method: 'POST',
                        body: formData
                    });

                    if (!response.ok) {
                        throw new Error((await response.json()).error || 'Error al procesar el archivo');
                    }

                    success++;
                } catch (error) {
                    errors.push(`Error en ${file.name}: ${error.message}`);
                    notProcessedRecords.push({
                        fileName: file.name,
                        apellidosNombres: fileInfo?.apellidosNombres || 'No disponible',
                        documento: fileInfo?.documento || 'No disponible',
                        ejercicio: fileInfo?.ejercicio || 'No disponible',
                        periodo: fileInfo?.periodo || 'No disponible',
                        reason: error.message
                    });
                }
            }

            // Mostrar resultados
            processing.style.display = 'none';
            resultBox.style.display = 'block';
            successCount.textContent = success;

            if (errors.length > 0) {
                errorBox.style.display = 'block';
                errorCount.textContent = errors.length;
                errorList.innerHTML = errors.map(error => `<li>${error}</li>`).join('');
            } else {
                errorBox.style.display = 'none';
            }

            // Generar archivo de descarga si hay registros no procesados
            generateDownloadFile();
        }
        // Event Listeners
        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            const items = e.dataTransfer.items;
            if (items) {
                const files = [];
                for (let i = 0; i < items.length; i++) {
                    const item = items[i].webkitGetAsEntry();
                    if (item) {
                        traverseFileTree(item, files);
                    }
                }
            }
        });

        function traverseFileTree(item, files) {
            if (item.isFile) {
                item.file(file => {
                    if (file.name.toLowerCase().endsWith('.pdf')) {
                        files.push(file);
                    }
                });
            } else if (item.isDirectory) {
                const dirReader = item.createReader();
                dirReader.readEntries(entries => {
                    entries.forEach(entry => traverseFileTree(entry, files));
                });
            }
        }

        // Funciones del modal
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