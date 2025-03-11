<?php

namespace Libs;

class EmailService
{
    /**
     * Envía un correo de notificación sobre cambios en legajos
     * 
     * @param string $rol Rol del usuario (RRHH o NOMINAS)
     * @param array $legajoData Datos del legajo
     * @param string $documentoDescripcion Descripción del documento
     * @return bool Resultado de la operación
     */
    public static function enviarNotificacionLegajo($rol, $legajoData, $documentoDescripcion)
    {
        // Log detallado para depuración
        self::logDebug("Iniciando envío de correo para rol: $rol");
        self::logDebug("Datos del legajo: " . json_encode($legajoData));
        self::logDebug("Documento: $documentoDescripcion");
        
        // Determinar destinatarios según el rol
        $destinatarios = [];
        
        if ($rol === 'RRHH') {
            $destinatarios = [
                //'sdelgado@tema.com.pe',
                //'jrodriguez@tema.com.pe',
                //'sjamanca@tema.com.pe'
                'lhoyos@tema.com.pe',
                'bmunoz@tema.com.pe',
                'lmonroy@tema.com.pe',
                'lmonroy1971@gmail.com'
            ];
        } elseif ($rol === 'NOMINAS') {
            $destinatarios = [
                //'aqueque@tema.com.pe',
                //'rmunoz@tema.com.pe'
                'lhoyos@tema.com.pe',
                'bmunoz@tema.com.pe',
                'lmonroy@tema.com.pe',
                'lmonroy1971@gmail.com'
            ];
        } elseif ($rol === 'RECEPCION') {
            // En caso de querer añadir correos para RECEPCION en el futuro
            $destinatarios = [
                //'sdelgado@tema.com.pe',
                //'jrodriguez@tema.com.pe',
                //'sjamanca@tema.com.pe'
                'lhoyos@tema.com.pe',
                'bmunoz@tema.com.pe',
                'lmonroy@tema.com.pe',
                'lmonroy1971@gmail.com'
            ];
        }
        
        if (empty($destinatarios)) {
            self::logDebug("No hay destinatarios para el rol: $rol");
            return false;
        }
        
        self::logDebug("Destinatarios: " . implode(", ", $destinatarios));
        
        // Construir el asunto y cuerpo del correo con codificación correcta
        $asunto = "Notificación de cambios en Legajo Personal";
        
        // Convertir caracteres del asunto para evitar problemas en cabeceras
        $asunto = "=?UTF-8?B?" . base64_encode($asunto) . "?=";
        
        // Construir el cuerpo HTML para mejor presentación y soporte de caracteres
        $cuerpoHTML = "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Notificación de Legajo</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px; }
        .footer { border-top: 1px solid #ddd; padding-top: 10px; margin-top: 20px; font-size: 12px; color: #777; }
        .info { margin-bottom: 15px; }
        .label { font-weight: bold; }
        .estado { margin: 20px 0; padding: 10px; background-color: #f8f9fa; border-left: 4px solid #0d6efd; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>Notificación de cambios en Legajo Personal</h2>
        </div>
        
        <div class='info'>
            <p><span class='label'>Trabajador:</span> " . htmlspecialchars($legajoData['APELLIDOS_NOMBRES']) . "</p>
            <p><span class='label'>DNI:</span> " . htmlspecialchars($legajoData['N_DOCUMENTO']) . "</p>
            <p><span class='label'>Documento:</span> " . htmlspecialchars($documentoDescripcion) . "</p>
        </div>
        
        <div class='estado'>
            <p><span class='label'>Estado:</span> ";

        // Agregar mensaje de estado según el rol
        if ($rol === 'RRHH') {
            $cuerpoHTML .= "SE SUBIÓ DOCUMENTO FIRMADO";
        } elseif ($rol === 'NOMINAS') {
            $cuerpoHTML .= "SE SUBIÓ EL DOCUMENTO EMITIDO";
        } elseif ($rol === 'RECEPCION') {
            $cuerpoHTML .= "SE RECEPCIONÓ EL DOCUMENTO FIRMADO";
        }
        
        $cuerpoHTML .= "</p>
        </div>
        
        <div class='footer'>
            <p>Atentamente,<br>Aplicación de Evidencias de RRHH</p>
        </div>
    </div>
</body>
</html>";

        // También crear una versión de texto plano como alternativa
        $cuerpoTexto = "Trabajador: " . $legajoData['APELLIDOS_NOMBRES'] . "\r\n";
        $cuerpoTexto .= "DNI: " . $legajoData['N_DOCUMENTO'] . "\r\n";
        $cuerpoTexto .= "Documento: " . $documentoDescripcion . "\r\n";
        $cuerpoTexto .= "Estado: ";
        
        if ($rol === 'RRHH') {
            $cuerpoTexto .= "SE SUBIÓ DOCUMENTO FIRMADO";
        } elseif ($rol === 'NOMINAS') {
            $cuerpoTexto .= "SE SUBIÓ EL DOCUMENTO EMITIDO";
        } elseif ($rol === 'RECEPCION') {
            $cuerpoTexto .= "SE RECEPCIONÓ EL DOCUMENTO FIRMADO";
        }
        
        $cuerpoTexto .= "\r\n\r\nAtentamente,\r\nAplicación de Evidencias de RRHH";
        
        self::logDebug("Asunto: $asunto");
        self::logDebug("Cuerpo HTML preparado");
        
        // Generar un límite para el contenido multiparte
        $boundary = md5(time());
        
        // Cabeceras del correo
        $cabeceras = "From: evidencias.rrhh@temalitoclean.com\r\n";
        $cabeceras .= "Reply-To: evidencias.rrhh@temalitoclean.com\r\n";
        $cabeceras .= "MIME-Version: 1.0\r\n";
        $cabeceras .= "Content-Type: multipart/alternative; boundary=\"$boundary\"\r\n";
        $cabeceras .= "X-Mailer: PHP/" . phpversion();
        
        // Cuerpo multiparte (texto plano + HTML)
        $mensaje = "--$boundary\r\n";
        $mensaje .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $mensaje .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $mensaje .= $cuerpoTexto . "\r\n\r\n";
        
        $mensaje .= "--$boundary\r\n";
        $mensaje .= "Content-Type: text/html; charset=UTF-8\r\n";
        $mensaje .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $mensaje .= $cuerpoHTML . "\r\n\r\n";
        
        $mensaje .= "--$boundary--";
        
        self::logDebug("Cabeceras: \n$cabeceras");
        
        // Para entorno local, podemos guardar en archivo en lugar de enviar
        // Esto permite ver qué se habría enviado sin necesidad de un servidor de correo
        if (self::esEntornoLocal()) {
            return self::simularEnvioLocal($destinatarios, $asunto, $mensaje, $cabeceras);
        }
        
        // Enviar el correo a todos los destinatarios
        $enviado = true;
        foreach ($destinatarios as $email) {
            self::logDebug("Intentando enviar correo a: $email");
            
            if (!mail($email, $asunto, $mensaje, $cabeceras)) {
                self::logDebug("Error al enviar correo a: $email");
                $enviado = false;
            } else {
                self::logDebug("Correo enviado exitosamente a: $email");
            }
        }
        
        return $enviado;
    }
    
    /**
     * Detecta si estamos en entorno local
     */
    private static function esEntornoLocal() 
    {
        return in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']) || 
               strpos($_SERVER['SERVER_NAME'], '.local') !== false ||
               strpos($_SERVER['SERVER_NAME'], '.test') !== false;
    }
    
    /**
     * Simula el envío de correo guardando en archivo
     */
    private static function simularEnvioLocal($destinatarios, $asunto, $mensaje, $cabeceras) 
    {
        $logDir = __DIR__ . '/../Logs';
        
        // Crear directorio de logs si no existe
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $timestamp = date('YmdHis');
        $logFile = $logDir . "/email_$timestamp.txt";
        
        $contenido = "DESTINATARIOS: " . implode(", ", $destinatarios) . "\n";
        $contenido .= "ASUNTO: $asunto\n";
        $contenido .= "CABECERAS:\n$cabeceras\n";
        $contenido .= "CUERPO:\n$mensaje\n";
        
        $resultado = file_put_contents($logFile, $contenido);
        
        self::logDebug("Simulación de correo guardada en: $logFile");
        
        return $resultado !== false;
    }
    
    /**
     * Registra mensajes de depuración
     */
    private static function logDebug($mensaje) 
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] EMAIL: $mensaje\n";
        
        // Escribir en el archivo de error log de PHP
        error_log($logMessage);
        
        // También guardar en un archivo específico de depuración
        $logDir = __DIR__ . '/../Logs';
        
        // Crear directorio de logs si no existe
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logFile = $logDir . "/email_debug.log";
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}