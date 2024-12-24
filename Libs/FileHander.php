<?php

namespace Libs;

class FileHandler
{
    public static function processFile($file, $data, $uploadDir)
    {
        $filePath = $file['tmp_name'];

        // Crear carpeta si no existe
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Renombrar el archivo
        $nuevoNombre = "{$data['apellidos_nombres']} - {$data['documento_id']} - {$data['ejercicio']}{$data['periodo']} - " . date('YmdHis') . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $destino = $uploadDir . $nuevoNombre;

        // Mover el archivo
        if (move_uploaded_file($filePath, $destino)) {
            return ['error' => false, 'path' => $destino];
        }

        return ['error' => true, 'message' => 'No se pudo mover el archivo'];
    }
}
