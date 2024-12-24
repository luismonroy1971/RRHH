<?php
header('Content-Type: text/plain');

echo "== Diagnóstico de Sistema ==\n";
echo "Fecha: " . date('Y-m-d H:i:s') . "\n";
echo "Usuario PHP: " . get_current_user() . "\n";
echo "Directorio actual: " . __DIR__ . "\n";
echo "Permisos directorio: " . substr(sprintf('%o', fileperms(__DIR__)), -4) . "\n";
echo "Directorio escribible: " . (is_writable(__DIR__) ? "SI" : "NO") . "\n";
echo "PHP version: " . phpversion() . "\n";
echo "Directorio temp: " . sys_get_temp_dir() . "\n";

$testFile = __DIR__ . '/test_write.tmp';
try {
    file_put_contents($testFile, 'test');
    echo "Prueba escritura: OK\n";
    unlink($testFile);
} catch (Exception $e) {
    echo "Error escritura: " . $e->getMessage() . "\n";
}