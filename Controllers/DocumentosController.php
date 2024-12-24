<?php

namespace Controllers;

use Models\Documento;
use Libs\Database;

class DocumentosController
{
    public static function getAll()
    {
        return Database::query("SELECT * FROM colaboradores");
    }

    public static function index()
    {
        $documentos = Documento::getAll();
        require_once __DIR__ . '/../Views/Documentos/Index.php';
    }

    public static function create()
    {
        $codigo = $_POST['codigo'] ?? null;
        $descripcion = $_POST['descripcion'] ?? null;
        $categoria = $_POST['categoria'] ?? null;
    
        // Validación básica
        if (!$codigo || !$descripcion || !$categoria) {
            header('Location: /documentos/create?error=Todos los campos son obligatorios');
            exit;
        }
    
        $data = [
            'codigo' => $codigo,
            'descripcion' => $descripcion,
            'categoria' => $categoria
        ];
    
        // Intentar crear el documento
        if (!\Models\Documento::create($data)) {
            header('Location: /documentos/create?error=El código ya existe');
            exit;
        }
    
        header('Location: /documentos?message=Documento creado exitosamente');
        exit;
    }
    
    

    public static function update()
    {
        $id = $_POST['id'] ?? null;
        $codigo = $_POST['codigo'] ?? null;
        $descripcion = $_POST['descripcion'] ?? null;
        $categoria = $_POST['categoria'] ?? null;
    
        // Validación de campos obligatorios
        if (!$id || !$codigo || !$descripcion || !$categoria) {
            header('Location: /documentos/update/' . $id . '?error=Todos los campos son obligatorios');
            exit;
        }
    
        $data = [
            'codigo' => $codigo,
            'descripcion' => $descripcion,
            'categoria' => $categoria,
        ];
    
        // Intentar actualizar el documento
        if (!\Models\Documento::update($id, $data)) {
            header('Location: /documentos/update/' . $id . '?error=El código ya existe en otro documento');
            exit;
        }
    
        // Redirigir con mensaje de éxito
        header('Location: /documentos?message=Documento actualizado exitosamente');
        exit;
    }
    

    public static function delete()
    {
        $id = $_POST['id'] ?? null;
        Documento::delete($id);
        header('Location: /documentos?message=Documento eliminado exitosamente');
        exit;
    }

    public static function edit($id)
    {
        $doc = \Models\Documento::findById($id);
    
        // Verificar si el documento existe
        if (!$doc) {
            header('Location: /documentos?error=Documento no encontrado');
            exit;
        }
    
        // Extraer $doc a variables para que la vista lo reconozca
        extract($doc);
    
        // Cargar la vista
        require_once __DIR__ . '/../Views/Documentos/Edit.php';
    }
    
    public static function listAll()
    {
        // Configurar encabezado JSON
        header('Content-Type: application/json; charset=utf-8');
    
        // Obtener todos los documentos desde el modelo
        $documentos = \Models\Documento::getAll();
    
        // Devolver la lista de documentos en JSON
        if (!empty($documentos)) {
            echo json_encode(['data' => $documentos]);
        } else {
            echo json_encode(['data' => [], 'message' => 'No hay documentos disponibles']);
        }
        exit;
    }
    

    
}
