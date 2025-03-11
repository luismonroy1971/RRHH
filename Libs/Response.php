<?php

namespace Libs;

class Response
{
    /**
     * Envía una respuesta en formato JSON
     * 
     * @param mixed $data Los datos a enviar como JSON
     * @param int $statusCode El código de estado HTTP
     * @return void
     */
    public static function json($data, $statusCode = 200)
    {
        // Registrar los datos que se intentan enviar para depuración
        error_log("Response::json - Intentando enviar datos: " . print_r($data, true));
        
        // Establecer cabeceras solo si no se han enviado ya
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code($statusCode);
        }
        
        // Intentar codificar los datos como JSON con manejo de errores
        $json = json_encode($data);
        
        // Verificar si hubo un error en la codificación
        if ($json === false) {
            error_log("Error en json_encode: " . json_last_error_msg());
            
            // Enviar un error genérico en caso de fallo en la codificación
            echo json_encode([
                'error' => 'Error interno del servidor al procesar la respuesta',
                'debug' => 'Error de codificación JSON: ' . json_last_error_msg()
            ]);
        } else {
            // Enviar la respuesta JSON
            echo $json;
        }
        
        // Terminar la ejecución del script
        exit;
    }
}