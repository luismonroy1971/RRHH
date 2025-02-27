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
                'sdelgado@tema.com.pe',
                'jrodriguez@tema.com.pe',
                'sjamanca@tema.com.pe'
                //'lmonroy1971@gmail.com',
                //'lmonroy@tema.com.pe'
            ];
        } elseif ($rol === 'NOMINAS') {
            $destinatarios = [
                'aqueque@tema.com.pe',
                'rmunoz@tema.com.pe'
                //'lmonroy1971@gmail.com',
                //'lmonroy@tema.com.pe'
            ];
        } elseif ($rol === 'RECEPCION') {
            // En caso de querer añadir correos para RECEPCION en el futuro
            $destinatarios = [
                'sdelgado@tema.com.pe',
                'jrodriguez@tema.com.pe',
                'sjamanca@tema.com.pe'
                //'lmonroy1971@gmail.com',
                //'lmonroy@tema.com.pe'
            ];
        }
        
        if (empty($destinatarios)) {
            self::logDebug("No hay destinatarios para el rol: $rol");
            return false;
        }
        
        self::logDebug("Destinatarios: " . implode(", ", $destinatarios));
        
        // Construir el asunto y cuerpo del correo
        $asunto = "Notificación de cambios en Legajo Personal";
        
        $cuerpo = "Trabajador: " . htmlspecialchars($legajoData['APELLIDOS_NOMBRES']) . "\r\n";
        $cuerpo .= "DNI: " . htmlspecialchars($legajoData['N_DOCUMENTO']) . "\r\n";
        $cuerpo .= "Documento: " . htmlspecialchars($documentoDescripcion) . "\r\n";
        
        // Agregar mensaje de estado según el rol
        $cuerpo .= "Estado: ";
        if ($rol === 'RRHH') {
            $cuerpo .= "SE SUBIÓ DOCUMENTO FIRMADO";
        } elseif ($rol === 'NOMINAS') {
            $cuerpo .= "SE SUBIÓ EL DOCUMENTO EMITIDO";
        } elseif ($rol === 'RECEPCION') {
            $cuerpo .= "SE RECEPCIONÓ EL DOCUMENTO FIRMADO";
        }
        $cuerpo .= "\r\n\r\n";
        
        $cuerpo .= "Atentamente,\r\nAplicación de Evidencias de RRHH";
        
        self::logDebug("Asunto: $asunto");
        self::logDebug("Cuerpo del correo: \n$cuerpo");
        
        // Cabeceras del correo
        $cabeceras = "From: evidencias.rrhh@temalitoclean.com\r\n";
        $cabeceras .= "Reply-To: evidencias.rrhh@temalitoclean.com\r\n";
        $cabeceras .= "X-Mailer: PHP/" . phpversion();
        
        self::logDebug("Cabeceras: \n$cabeceras");
        
        // Para entorno local, podemos guardar en archivo en lugar de enviar
        // Esto permite ver qué se habría enviado sin necesidad de un servidor de correo
        if (self::esEntornoLocal()) {
            return self::simularEnvioLocal($destinatarios, $asunto, $cuerpo, $cabeceras);
        }
        
        // Enviar el correo a todos los destinatarios
        $enviado = true;
        foreach ($destinatarios as $email) {
            self::logDebug("Intentando enviar correo a: $email");
            
            if (!mail($email, $asunto, $cuerpo, $cabeceras)) {
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
    private static function simularEnvioLocal($destinatarios, $asunto, $cuerpo, $cabeceras) 
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
        $contenido .= "CUERPO:\n$cuerpo\n";
        
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