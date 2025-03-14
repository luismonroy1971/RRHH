<?php

namespace Controllers;

use Models\Legajo;
use Libs\Response;
use Libs\Database;
require_once __DIR__ . '/../Libs/EmailService.php';

use Libs\EmailService; // Colocar el use después del require
use Exception;

class LegajoController
{

    // En LegajoController.php
    public static function verificarExistencia()
    {
        $tipoDocumento = $_GET['tipo_documento'] ?? null;
        $nDocumento = $_GET['n_documento'] ?? null;
        $documentoId = $_GET['documento_id'] ?? null;
        $ejercicio = $_GET['ejercicio'] ?? null;
        $periodo = $_GET['periodo'] ?? null;

        $exists = Legajo::existeCombinacion([
            'tipo_documento' => $tipoDocumento,
            'n_documento' => $nDocumento,
            'documento_id' => $documentoId,
            'ejercicio' => $ejercicio,
            'periodo' => $periodo
        ]);

        return Response::json(['exists' => $exists]);
    }

    public static function validateFileName($fileName) {
        // Verifica que el nombre del archivo siga el patrón esperado y tenga extensión PDF
        $pattern = '/^.+ - \d{4} - \d{1,2} - .+ - \d{14}\.pdf$/i';
        return preg_match($pattern, $fileName);
    }
    
    public static function getAllSimple()
    {
        // Verificar si la sesión ya está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $usuarioActual = $_SESSION['user_id'] ?? null;
    
        if (!$usuarioActual) {
            header('Location: /login');
            exit;
        }
    
        try {
            // Obtener los datos
            $sql = "SELECT * FROM LEGAJO ORDER BY ID DESC";
            $legajos = Database::query($sql);
            
            // Verificar si hay registros
            if ($legajos === false) {
                throw new \Exception("Error al obtener los legajos");
            }
            
            // Cargar la vista con los datos
            require_once __DIR__ . '/../Views/Legajo/Simple.php';
            
        } catch (\Exception $e) {
            echo "<div style='color: red; margin: 20px; padding: 20px; border: 1px solid red;'>";
            echo "Error: " . $e->getMessage();
            echo "</div>";
        }
    }

    public static function getAll()
    {
        // Verificar si la sesión ya está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $usuarioActual = $_SESSION['user_id'] ?? null;
    
        if (!$usuarioActual) {
            if (self::isAjaxRequest()) {
                header('HTTP/1.1 401 Unauthorized');
                return Response::json(['error' => 'Usuario no autenticado']);
            }
            header('Location: /login');
            exit;
        }
    
        // Si es una solicitud AJAX, devolver datos JSON
        if (self::isAjaxRequest()) {
            try {
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
                
                $filters = [
                    'tipo_documento' => $_GET['tipo_documento'] ?? null,
                    'n_documento' => $_GET['n_documento'] ?? null,
                    'apellidos_nombres' => $_GET['apellidos_nombres'] ?? null,
                    'documento' => $_GET['documento'] ?? null,  // Este es el ID del documento
                    'ejercicio' => $_GET['ejercicio'] ?? null,
                    'periodo' => $_GET['periodo'] ?? null,
                    'emitido' => isset($_GET['emitido']) && $_GET['emitido'] !== '' ? $_GET['emitido'] === '1' : null,
                    'subido' => isset($_GET['subido']) && $_GET['subido'] !== '' ? $_GET['subido'] === '1' : null,
                    'fisico' => isset($_GET['fisico']) && $_GET['fisico'] !== '' ? intval($_GET['fisico']) : null
                ];
    
                // Eliminar filtros vacíos
                $filters = array_filter($filters, function($value) {
                    return $value !== null && $value !== '';
                });
    
                $result = Legajo::getAll($page, $perPage, $filters);
    
                if ($result === false) {
                    throw new Exception('Error al obtener los datos');
                }
    
                // Procesar los datos para el formato correcto
                if (!empty($result['data'])) {
                    foreach ($result['data'] as &$legajo) {
                        $legajo['emitido'] = $legajo['EMITIDO'] ?? null;
                        $legajo['subido'] = $legajo['SUBIDO'] ?? null;
                        $legajo['fisico'] = $legajo['FISICO'] ? true : false;
                    }
                }
    
                header('Content-Type: application/json');
                return Response::json($result);
            } catch (Exception $e) {
                error_log("Error en getAll: " . $e->getMessage());
                header('HTTP/1.1 500 Internal Server Error');
                return Response::json(['error' => 'Error interno del servidor']);
            }
        }
    
        // Si no es AJAX, mostrar la vista
        require_once __DIR__ . '/../Views/Legajo/Index.php';
    }
    
    private static function isAjaxRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' ||
               strpos($_SERVER['REQUEST_URI'], '/api') !== false; // También considera peticiones a /api como AJAX
    }
 
    public static function create()
    {
        error_log("=== Inicio de método create() en LegajoController ===");
        
        // Establecer encabezado JSON
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }
        
        try {
            // Verificar si la sesión ya está iniciada
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            $usuarioActual = $_SESSION['user_id'] ?? null;
            $rolUsuario = $_SESSION['role'] ?? null;
            
            error_log("Usuario: " . $usuarioActual . ", Rol: " . $rolUsuario);
        
            if (!$usuarioActual) {
                error_log("Error: Usuario no autenticado");
                return Response::json(['error' => 'No autorizado. Usuario no autenticado.'], 403);
            }
            
            if ($rolUsuario !== 'NOMINAS') {
                error_log("Error: Usuario no tiene rol NOMINAS, tiene rol " . $rolUsuario);
                return Response::json(['error' => 'No autorizado para crear legajos. Se requiere rol NOMINAS.'], 403);
            }
        
            // Registrar datos POST recibidos
            error_log("Datos POST recibidos: " . print_r($_POST, true));
            error_log("Archivos recibidos: " . print_r($_FILES, true));
            
            // Asegurarnos de capturar apellidos_nombres
            $apellidosNombres = $_POST['apellidos_nombres'] ?? null;
            if (!$apellidosNombres) {
                error_log("Error: Nombre del trabajador no proporcionado");
                return Response::json(['error' => 'El nombre del trabajador es requerido'], 400);
            }
        
            // Obtener la descripción del documento
            $documentoDescripcion = '';
            try {
                $docResult = Database::query("SELECT DESCRIPCION FROM documentos WHERE ID = ?", [$_POST['documento_id']]);
                if (!empty($docResult)) {
                    $documentoDescripcion = $docResult[0]['DESCRIPCION'];
                    error_log("Descripción del documento obtenida: " . $documentoDescripcion);
                } else {
                    error_log("Advertencia: No se encontró descripción para el documento ID: " . $_POST['documento_id']);
                }
            } catch (Exception $e) {
                error_log("Error al obtener la descripción del documento: " . $e->getMessage());
                return Response::json(['error' => 'Error al obtener la descripción del documento: ' . $e->getMessage()], 500);
            }
        
            $data = [
                'tipo_documento' => $_POST['tipo_documento'] ?? null,
                'n_documento' => $_POST['n_documento'] ?? null,
                'documento_id' => $_POST['documento_id'] ?? null,
                'ejercicio' => $_POST['ejercicio'] ?? null,
                'periodo' => $_POST['periodo'] ?? null,
                'apellidos_nombres' => $apellidosNombres
            ];
            
            error_log("Datos para validación de existencia: " . print_r($data, true));
        
            // Validar existencia previa
            if (Legajo::existeCombinacion($data)) {
                error_log("Error: Ya existe un legajo con esta combinación de datos");
                return Response::json(['error' => 'Ya existe un legajo con esta combinación de datos'], 400);
            }
        
            try {
                // Procesar archivo si fue subido
                if (isset($_FILES['emitido']) && $_FILES['emitido']['error'] === UPLOAD_ERR_OK) {
                    error_log("Procesando archivo emitido...");
                    
                    // Verificar directorio de uploads
                    $uploadDir = __DIR__ . "/../Uploads/";
                    if (!is_dir($uploadDir)) {
                        error_log("Error: El directorio de uploads no existe: " . $uploadDir);
                        mkdir($uploadDir, 0755, true);
                        error_log("Directorio de uploads creado");
                    }
                    
                    if (!is_writable($uploadDir)) {
                        error_log("Error: El directorio de uploads no tiene permisos de escritura: " . $uploadDir);
                        return Response::json(['error' => 'Error de configuración del servidor: el directorio de uploads no tiene permisos de escritura'], 500);
                    }
                    
                    $data['emitido'] = self::uploadFile(
                        'emitido', 
                        $apellidosNombres, 
                        $data['ejercicio'], 
                        str_pad($data['periodo'], 2, '0', STR_PAD_LEFT),
                        $documentoDescripcion
                    );
                    $data['emitido_usuario'] = $usuarioActual;
                    $data['emitido_hora'] = date('Y-m-d H:i:s');
                    $data['emitido_observacion'] = $_POST['emitido_observacion'] ?? '';
                    
                    error_log("Archivo procesado correctamente. Ruta: " . $data['emitido']);
                }
        
                error_log("Intentando crear legajo en la base de datos");
                $result = Legajo::create($data);
                
                if ($result) {
                    error_log("Legajo creado correctamente. ID: " . $result);
                    
                    // Verificar si se debe enviar correo
                    $enviarCorreo = isset($_POST['enviar_correo']) && $_POST['enviar_correo'] === '1';
                    
                    if ($enviarCorreo) {
                        error_log("Enviando notificación por correo...");
                        $legajoData = [
                            'APELLIDOS_NOMBRES' => $apellidosNombres,
                            'N_DOCUMENTO' => $data['n_documento']
                        ];
                        
                        try {
                            EmailService::enviarNotificacionLegajo($rolUsuario, $legajoData, $documentoDescripcion);
                            error_log("Correo enviado correctamente");
                        } catch (Exception $e) {
                            error_log("Error al enviar correo: " . $e->getMessage());
                            // No interrumpir el flujo principal por error de correo
                        }
                    }
                    
                    error_log("Proceso completado. Devolviendo respuesta de éxito");
                    return Response::json(['success' => true, 'message' => 'Legajo creado correctamente']);
                } else {
                    error_log("Error: No se pudo crear el legajo en la base de datos");
                    return Response::json(['error' => 'Error al crear el legajo en la base de datos'], 500);
                }
            } catch (Exception $e) {
                error_log("Excepción capturada: " . $e->getMessage());
                error_log("Stack trace: " . $e->getTraceAsString());
                return Response::json(['error' => $e->getMessage()], 400);
            }
        } catch (Exception $e) {
            error_log("Excepción no controlada: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return Response::json(['error' => 'Error inesperado: ' . $e->getMessage()], 500);
        }
    }

    public static function edit($id)
    {
        // Verificar si la sesión ya está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $usuarioActual = $_SESSION['user_id'] ?? null;
        $rolUsuario = $_SESSION['role'] ?? null;
    
        if (!$usuarioActual) {
            header('Location: /login');
            exit;
        }
    
        try {
            // Obtener datos del legajo
            $result = Legajo::findById($id);
            
            error_log("ID recibido: " . $id);
            error_log("Resultado de la consulta: " . print_r($result, true));
            
            if (empty($result)) {
                header('Location: /legajo?error=' . urlencode('Legajo no encontrado'));
                exit;
            }
            
            // Obtener el primer resultado
            $legajo = $result[0];
    
            // Obtener descripción del documento
            $documentoDescripcion = '';
            try {
                $docResult = Database::query("SELECT DESCRIPCION FROM documentos WHERE ID = ?", [$legajo['DOCUMENTO_ID']]);
                if (!empty($docResult)) {
                    $documentoDescripcion = $docResult[0]['DESCRIPCION'];
                }
            } catch (Exception $e) {
                error_log("Error al obtener descripción del documento: " . $e->getMessage());
                $documentoDescripcion = 'No disponible';
            }
    
            error_log("Documento descripción: " . $documentoDescripcion);
    
            // Array de meses para mostrar el periodo
            $meses = [
                'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ];
    
            // Debug de los datos antes de pasarlos a la vista
            error_log("Datos finales del legajo: " . print_r([
                'legajo' => $legajo,
                'documentoDescripcion' => $documentoDescripcion,
                'meses' => $meses
            ], true));
    
            require_once __DIR__ . '/../Views/Legajo/Edit.php';
        } catch (Exception $e) {
            error_log("Error en edit: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            header('Location: /legajo?error=' . urlencode('Error al cargar el legajo'));
            exit;
        }
    }

    public static function update()
    {
        header('Content-Type: application/json');
        
        // Verificar si la sesión ya está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $usuarioActual = $_SESSION['user_id'] ?? null;
        $rolUsuario = $_SESSION['role'] ?? null;
        
        // Registrar información de depuración
        error_log("Iniciando update de legajo. Usuario: $usuarioActual, Rol: $rolUsuario");
        error_log("Datos POST recibidos: " . print_r($_POST, true));
    
        if (!$usuarioActual) {
            return Response::json(['error' => 'Usuario no autenticado'], 401);
        }
    
        if (!in_array($rolUsuario, ['RRHH', 'RECEPCION'])) {
            return Response::json(['error' => 'No tiene permisos para editar legajos'], 403);
        }
    
        $id = $_POST['id'] ?? null;
        if (!$id) {
            return Response::json(['error' => 'ID no proporcionado'], 400);
        }
    
        try {
            // Obtener el legajo actual
            $legajoActual = Legajo::findById($id);
            if (empty($legajoActual)) {
                return Response::json(['error' => 'Legajo no encontrado'], 404);
            }
            $legajoActual = $legajoActual[0];
    
            // Obtener la descripción del documento
            $documentoDescripcion = $_POST['documento_descripcion'] ?? '';
            if (empty($documentoDescripcion)) {
                try {
                    $docResult = Database::query("SELECT DESCRIPCION FROM documentos WHERE ID = ?", [$_POST['documento_id']]);
                    if (!empty($docResult)) {
                        $documentoDescripcion = $docResult[0]['DESCRIPCION'];
                    }
                } catch (Exception $e) {
                    return Response::json(['error' => 'Error al obtener la descripción del documento'], 500);
                }
            }
    
            $data = [];
            try {
                switch ($rolUsuario) {
                    case 'RRHH':
                        if (isset($_FILES['subido']) && $_FILES['subido']['error'] === UPLOAD_ERR_OK) {
                            $data['subido'] = self::uploadFile(
                                'subido', 
                                $legajoActual['APELLIDOS_NOMBRES'] ?? 'Sin_Nombre',
                                $legajoActual['EJERCICIO'],
                                str_pad($legajoActual['PERIODO'], 2, '0', STR_PAD_LEFT),
                                $documentoDescripcion
                            );
                            $data['subido_usuario'] = $usuarioActual;
                            $data['subido_hora'] = date('Y-m-d H:i:s');
                            $data['subido_observacion'] = $_POST['subido_observacion'] ?? '';
                        } elseif (isset($_POST['subido_observacion']) && $_POST['subido_observacion'] !== $legajoActual['SUBIDO_OBSERVACION']) {
                            $data['subido_observacion'] = $_POST['subido_observacion'];
                        }
                        break;
    
                    case 'RECEPCION':
                        // Verificar si se envió el campo fisico (podría ser 0 o 1)
                        if (isset($_POST['fisico'])) {
                            // Convertir a entero para asegurar un valor limpio
                            $fisico = intval($_POST['fisico']);
                            $data['fisico'] = $fisico;
                            $data['fisico_usuario'] = $usuarioActual;
                            $data['fisico_hora'] = date('Y-m-d H:i:s');
                            
                            // Actualizar observación si está establecida
                            if (isset($_POST['fisico_observacion'])) {
                                $data['fisico_observacion'] = $_POST['fisico_observacion'];
                            }
                            
                            // Registrar datos para depuración
                            error_log("Datos de fisico para actualizar: " . print_r($data, true));
                        }
                        break;
                }
    
                if (empty($data)) {
                    return Response::json(['error' => 'No hay datos para actualizar'], 400);
                }
    
                $result = Legajo::update($id, $data);
    
                if ($result) {
                    // Verificar si se debe enviar correo
                    $enviarCorreo = isset($_POST['enviar_correo']) && $_POST['enviar_correo'] === '1';
                    
                    error_log("¿Enviar correo? " . ($enviarCorreo ? "SÍ" : "NO"));
                    
                    if ($enviarCorreo) {
                        // Usar los datos actualizados del legajo
                        $legajoData = [
                            'APELLIDOS_NOMBRES' => $legajoActual['APELLIDOS_NOMBRES'],
                            'N_DOCUMENTO' => $legajoActual['N_DOCUMENTO']
                        ];
                        
                        error_log("Intentando enviar correo para legajo: " . print_r($legajoData, true));
                        
                        try {
                            // Llamar al servicio de correo
                            $emailResult = EmailService::enviarNotificacionLegajo($rolUsuario, $legajoData, $documentoDescripcion);
                            error_log("Resultado del envío de correo: " . ($emailResult ? "Exitoso" : "Fallido"));
                        } catch (Exception $e) {
                            error_log("Error al enviar correo: " . $e->getMessage());
                            // No retornamos error aquí para no interrumpir el flujo principal
                        }
                    }
                    
                    return Response::json(['success' => true, 'message' => 'Legajo actualizado correctamente']);
                } else {
                    return Response::json(['error' => 'Error al actualizar el legajo'], 500);
                }
            } catch (Exception $e) {
                error_log("Error en actualización: " . $e->getMessage());
                return Response::json(['error' => $e->getMessage()], 400);
            }
        } catch (Exception $e) {
            error_log("Error en update: " . $e->getMessage());
            return Response::json(['error' => 'Error al procesar la actualización: ' . $e->getMessage()], 500);
        }
    }

    private static function uploadFile($inputName, $apellidos_nombres, $ejercicio, $periodo, $documento)
    {
        $file = $_FILES[$inputName];
        $fechaHora = date("YmdHis"); // Fecha y hora en el formato solicitado
        $fileExtension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        
        // Verificar que la extensión sea PDF
        if ($fileExtension !== 'pdf') {
            throw new Exception("Solo se permiten archivos PDF");
        }
        
        // Verificar el tipo MIME del archivo
        $fileMimeType = mime_content_type($file["tmp_name"]);
        if ($fileMimeType !== 'application/pdf') {
            throw new Exception("El archivo debe ser un PDF válido");
        }
    
        // Crear nombre del archivo con el formato deseado
        $fileName = "{$apellidos_nombres} - {$ejercicio} - {$periodo} - {$documento} - {$fechaHora}.pdf";
    
        $targetDir = __DIR__ . "/../Uploads/";
        $targetFile = $targetDir . $fileName;
    
        // Mover el archivo al directorio de destino
        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return "/Uploads/" . $fileName; // Retorna la ruta del archivo subido
        }
        
        throw new Exception("Error al subir el archivo");
    }
    

    public static function delete() {
        // Verificar si la sesión ya está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $usuarioActual = $_SESSION['user_id'] ?? null;
    
        if (!$usuarioActual) {
            return Response::json(['error' => 'Usuario no autenticado.'], 403);
        }
    
        // Obtener el contenido JSON del cuerpo de la petición
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        $id = $data['id'] ?? null;
    
        if (!$id) {
            return Response::json(['error' => 'ID no proporcionado.'], 400);
        }
    
        // Intentar eliminar el registro
        try {
            $result = Legajo::delete($id);
            
            if ($result) {
                return Response::json(['message' => 'Legajo eliminado correctamente.']);
            } else {
                return Response::json(['error' => 'Error al eliminar el legajo.'], 500);
            }
        } catch (Exception $e) {
            return Response::json(['error' => 'Error al eliminar el legajo: ' . $e->getMessage()], 500);
        }
    }

    public static function getDocumentos()
    {
        try {
            $sql = "SELECT ID, DESCRIPCION FROM documentos ORDER BY DESCRIPCION";
            $result = Database::query($sql);
            return $result;
        } catch (Exception $e) {
            error_log("Error al obtener documentos: " . $e->getMessage());
            return [];
        }
    }
    // En LegajoController.php
    public static function deleteByCombination()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data) {
                return Response::json(['error' => 'Datos no proporcionados'], 400);
            }

            $result = Legajo::deleteByCombination(
                $data['tipo_documento'],
                $data['n_documento'],
                $data['documento_id'],
                $data['ejercicio'],
                $data['periodo']
            );

            if ($result) {
                return Response::json(['message' => 'Registro eliminado correctamente']);
            } else {
                return Response::json(['error' => 'Error al eliminar el registro'], 500);
            }
        } catch (Exception $e) {
            return Response::json(['error' => $e->getMessage()], 500);
        }
    }
    // In Controllers/LegajoController.php

    public static function listAll() {
        try {
            $legajos = Legajo::getAll();
            header('Content-Type: application/json');
            echo json_encode(['data' => $legajos]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'Error al obtener la lista de legajos: ' . $e->getMessage()]);
        }
    }

    // Add this method to LegajoController.php
    public static function showCreateForm()
    {
        // Verificar si la sesión ya está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $usuarioActual = $_SESSION['user_id'] ?? null;

        if (!$usuarioActual) {
            header('Location: /login');
            exit;
        }

        // Load the Create view
        require_once __DIR__ . '/../Views/Legajo/Create.php';
    }

}