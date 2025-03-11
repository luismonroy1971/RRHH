<?php

namespace Controllers;

use Libs\Database;
use Models\Colaborador;

class ColaboradoresController
{
    // Listar todos los colaboradores
    public static function index()
    {
        $colaboradores = self::getAll();
        require_once __DIR__ . '/../Views/Colaboradores/Index.php';
    }

    // Obtener todos los colaboradores
    public static function getAll()
    {
        return Database::query("SELECT * FROM colaboradores");
    }

    // Mostrar formulario de creación
    public static function showCreateForm()
    {
        require_once __DIR__ . '/../Views/Colaboradores/Create.php';
    }

    // Crear un nuevo colaborador
    public static function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Recopilar y validar los datos
                $data = [
                    'tipo_documento' => trim($_POST['tipo_documento'] ?? ''),
                    'n_documento' => trim($_POST['n_documento'] ?? ''),
                    'apellidos_nombres' => trim($_POST['apellidos_nombres'] ?? ''),
                    'fecha_ingreso' => trim($_POST['fecha_ingreso'] ?? ''),
                    'area' => trim($_POST['area'] ?? '') ?: null, // Permitir NULL
                    'correo' => trim($_POST['correo'] ?? '') ?: null // Permitir NULL
                ];
    
                // Validar campos requeridos
                foreach (['tipo_documento', 'n_documento', 'apellidos_nombres', 'fecha_ingreso'] as $field) {
                    if (empty($data[$field])) {
                        throw new \Exception("El campo $field es obligatorio.");
                    }
                }
    
                // Intentar la inserción
                Database::insert(
                    "INSERT INTO colaboradores (TIPO_DOCUMENTO, N_DOCUMENTO, APELLIDOS_NOMBRES, FECHA_INGRESO, AREA, CORREO)
                     VALUES (?, ?, ?, ?, ?, ?)",
                    array_values($data)
                );
    
                header('Location: /colaboradores?message=Colaborador creado exitosamente');
                exit;
    
            } catch (\PDOException $e) {
                // Mostrar el error exacto de la base de datos
                echo "Error en la base de datos: " . $e->getMessage();
                exit;
            } catch (\Exception $e) {
                // Mostrar errores de validación personalizados
                echo "Error: " . $e->getMessage();
                exit;
            }
        }
    
        self::showCreateForm();
    }
    

    public static function showEditForm($id)
    {
        if (!$id) {
            // Redirige si el ID no está definido
            header('Location: /colaboradores?error=ID no proporcionado');
            exit;
        }
    
        // Buscar colaborador
        $colaborador = Database::query("SELECT * FROM colaboradores WHERE ID = ?", [$id]);
        
        if (empty($colaborador)) {
            // Redirige si no se encuentra el colaborador
            header('Location: /colaboradores?error=Colaborador no encontrado');
            exit;
        }
    
        // Pasar el colaborador a la vista
        $colaborador = $colaborador[0];
        require_once __DIR__ . '/../Views/Colaboradores/Edit.php';
    }
    

    // Actualizar un colaborador
    // Actualizar un colaborador existente
public static function update()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Location: /colaboradores?error=ID no proporcionado');
            exit;
        }

        // Recopilar y validar los datos
        $data = [
            'tipo_documento' => trim($_POST['tipo_documento'] ?? ''),
            'n_documento' => trim($_POST['n_documento'] ?? ''),
            'apellidos_nombres' => trim($_POST['apellidos_nombres'] ?? ''),
            'fecha_ingreso' => trim($_POST['fecha_ingreso'] ?? ''),
            'area' => trim($_POST['area'] ?? ''),
            'correo' => trim($_POST['correo'] ?? '')
        ];

        // Validar que ningún campo esté vacío
        if (in_array('', $data, true)) {
            header('Location: /colaboradores/edit?id=' . urlencode($id) . '&error=Todos los campos son obligatorios');
            exit;
        }

        // Actualizar en la base de datos
        Database::update(
            "UPDATE colaboradores 
             SET TIPO_DOCUMENTO = ?, N_DOCUMENTO = ?, APELLIDOS_NOMBRES = ?, FECHA_INGRESO = ?, AREA = ?, CORREO = ?
             WHERE ID = ?",
            [...array_values($data), $id]
        );

        header('Location: /colaboradores?message=Colaborador actualizado exitosamente');
        exit;
    }

    // Si no es POST, mostrar el formulario de edición
    $id = $_GET['id'] ?? null;
    self::showEditForm($id);
}

    // Eliminar un colaborador
    public static function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                header('Location: /colaboradores?error=ID no proporcionado');
                exit;
            }

            Database::delete("DELETE FROM colaboradores WHERE ID = ?", [$id]);

            header('Location: /colaboradores?message=Colaborador eliminado exitosamente');
            exit;
        }
    }
    public static function search()
    {
        // Configurar el encabezado de la respuesta
        header('Content-Type: application/json; charset=utf-8');
    
        // Obtener parámetros de la URL
        $tipo_documento = $_GET['tipo_documento'] ?? null;
        $n_documento = $_GET['n_documento'] ?? null;
    
        // Validar parámetros
        if (!$tipo_documento || !$n_documento) {
            http_response_code(400); // Código de error Bad Request
            echo json_encode(['error' => 'Debe proporcionar tipo_documento y n_documento']);
            return;
        }
    
        // Buscar colaborador
        $colaborador = Colaborador::findByDocument($tipo_documento, $n_documento);
    
        if ($colaborador) {
            http_response_code(200); // Código de éxito OK
            echo json_encode(['data' => $colaborador]);
        } else {
            http_response_code(200); // Código Not Found
            echo json_encode([
                'data' => [],
                'message' => 'El colaborador no existe en la base de datos.'
            ]);
        }
    }

    public static function listAll()
    {
        // Configurar encabezado JSON
        header('Content-Type: application/json; charset=utf-8');
    
        // Obtener todos los documentos desde el modelo
        $colaboradores = Colaborador::getAll();
    
        // Devolver la lista de documentos en JSON
        if (!empty($colaboradores)) {
            echo json_encode(['data' => $colaboradores]);
        } else {
            echo json_encode(['data' => [], 'message' => 'No hay documentos disponibles']);
        }
        exit;
    }
    
    public static function searchByName($nombre)
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!$nombre) {
            http_response_code(400);
            echo json_encode(['error' => 'Debe proporcionar el nombre']);
            return;
        }

        // Buscar colaborador por nombre
        $colaborador = Colaborador::findByName($nombre);

        if ($colaborador) {
            http_response_code(200);
            echo json_encode(['data' => $colaborador]);
        } else {
            http_response_code(200);
            echo json_encode([
                'data' => [],
                'message' => 'El colaborador no existe en la base de datos.'
            ]);
        }
    }
}
