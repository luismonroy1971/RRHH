<?php
$logPath = '/home/u220252535/domains/temalitoclean.com/public_html/rrhh/app.log';

try {
    echo "Intentando escribir en: " . $logPath . "<br>";
    
    if (!file_exists($logPath)) {
        echo "Archivo no existe, creando...<br>";
        touch($logPath);
        chmod($logPath, 0666);
    }
    
    $message = "Test log entry: " . date('Y-m-d H:i:s') . "\n";
    $result = error_log($message, 3, $logPath);
    
    echo "Resultado de escritura: " . ($result ? "Exitoso" : "Fallido") . "<br>";
    echo "Contenido actual del log:<br><pre>";
    echo file_exists($logPath) ? file_get_contents($logPath) : "Archivo no existe";
    echo "</pre>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}