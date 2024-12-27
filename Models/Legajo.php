<?php

namespace Models;

use Libs\Database;
use Exception; // Añadimos esta línea para importar Exception

class Legajo
{
    public static function getAllSimple()
    {
        try {
            // Consulta simple para verificar la existencia de la tabla
            $checkTable = Database::query("SHOW TABLES LIKE 'legajo'");
            if (empty($checkTable)) {
                throw new \Exception("La tabla legajo no existe en la base de datos");
            }
    
            // Consulta principal con error handling mejorado
            $sql = "SELECT * FROM legajo ORDER BY ID DESC";
            $result = Database::query($sql);
            
            if ($result === false) {
                throw new \Exception("Error al ejecutar la consulta principal");
            }
            
            // Log del número de registros encontrados
            $count = count($result);
            error_log("Número de registros encontrados en legajo: " . $count);
            
            return $result;
        } catch (\Exception $e) {
            error_log("Error en getAllSimple del modelo Legajo: " . $e->getMessage());
            throw $e;
        }
    }


    public static function getAll($page = 1, $perPage = 10, $filters = [])
    {
        try {
            // Construir la consulta base con JOIN para obtener las descripciones
            $baseSql = "FROM legajo L 
                        LEFT JOIN documentos D ON L.DOCUMENTO_ID = D.ID 
                        WHERE 1=1";
            $params = [];

            // Aplicar filtros
            if (!empty($filters['tipo_documento'])) {
                $baseSql .= " AND L.TIPO_DOCUMENTO = ?";
                $params[] = $filters['tipo_documento'];
            }

            if (!empty($filters['n_documento'])) {
                $baseSql .= " AND L.N_DOCUMENTO LIKE ?";
                $params[] = "%{$filters['n_documento']}%";
            }

            if (!empty($filters['apellidos_nombres'])) {
                $baseSql .= " AND L.APELLIDOS_NOMBRES LIKE ?";
                $params[] = "%{$filters['apellidos_nombres']}%";
            }

            // Filtro por ID del documento (cambiado de descripción a ID)
            if (!empty($filters['documento'])) {
                $baseSql .= " AND L.DOCUMENTO_ID = ?";
                $params[] = $filters['documento'];
            }

            if (!empty($filters['ejercicio'])) {
                $baseSql .= " AND L.EJERCICIO = ?";
                $params[] = $filters['ejercicio'];
            }

            if (!empty($filters['periodo'])) {
                $baseSql .= " AND L.PERIODO = ?";
                $params[] = $filters['periodo'];
            }

            if (isset($filters['emitido']) && $filters['emitido'] !== '') {
                $baseSql .= $filters['emitido'] ? " AND L.EMITIDO IS NOT NULL" : " AND L.EMITIDO IS NULL";
            }

            if (isset($filters['subido']) && $filters['subido'] !== '') {
                $baseSql .= $filters['subido'] ? " AND L.SUBIDO IS NOT NULL" : " AND L.SUBIDO IS NULL";
            }

        // Ajuste para el filtro FISICO
            if (isset($filters['fisico']) && $filters['fisico'] !== '') {
                error_log("Aplicando filtro FISICO. Valor recibido: " . $filters['fisico']);
                
                // Convertir a número para asegurar comparación correcta
                $fisicoValue = intval($filters['fisico']);
                
                if ($fisicoValue === 0) {
                    $baseSql .= " AND (L.FISICO = 0 OR L.FISICO IS NULL)";
                } elseif ($fisicoValue === 1) {
                    $baseSql .= " AND L.FISICO = 1";
                }
                error_log("SQL después de filtro FISICO: " . $baseSql);
            }

            // Contar total de registros
            $countSql = "SELECT COUNT(DISTINCT L.ID) as total " . $baseSql;
            $totalResult = Database::query($countSql, $params);
            $total = (int)$totalResult[0]['total'];

            // Calcular paginación
            $totalPages = ceil($total / $perPage);
            $offset = ($page - 1) * $perPage;

            // Consulta principal con paginación y selección de campos necesarios
            $mainSql = "SELECT L.*, D.DESCRIPCION as DOCUMENTO_DESCRIPCION " . $baseSql . 
                    " ORDER BY L.ID DESC LIMIT " . (int)$offset . ", " . (int)$perPage;

            $data = Database::query($mainSql, $params);

            return [
                'data' => $data,
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'pages' => $totalPages
            ];
        } catch (Exception $e) {
            error_log("Error in getAll: " . $e->getMessage());
            return [
                'data' => [],
                'page' => $page,
                'per_page' => $perPage,
                'total' => 0,
                'pages' => 0
            ];
        }
    }
    public static function findById($id)
    {
        try {
            error_log("Buscando legajo con ID: " . $id);
            $sql = "SELECT L.*, D.DESCRIPCION as DOCUMENTO_DESCRIPCION 
                    FROM legajo L 
                    LEFT JOIN documentos D ON L.DOCUMENTO_ID = D.ID 
                    WHERE L.ID = ?";
            error_log("SQL Query: " . $sql);
            
            $result = Database::query($sql, [$id]);
            error_log("Resultado de la consulta: " . print_r($result, true));
            
            if (empty($result)) {
                error_log("No se encontró legajo con ID: " . $id);
                return null;
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("Error en findById: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }

    public static function create($data)
    {
        // Campos básicos que siempre deben estar presentes
        $fields = [
            'TIPO_DOCUMENTO',
            'N_DOCUMENTO',
            'DOCUMENTO_ID',
            'EJERCICIO',
            'PERIODO',
            'APELLIDOS_NOMBRES'  // Movido al final
        ];
        
        $values = [
            $data['tipo_documento'],
            $data['n_documento'],
            $data['documento_id'],
            $data['ejercicio'],
            $data['periodo'],
            $data['apellidos_nombres'] 
        ];
    
        // Agregar campos opcionales si están presentes
        if (isset($data['emitido'])) {
            $fields[] = 'EMITIDO';
            $values[] = $data['emitido'];
        }
        
        if (isset($data['emitido_usuario'])) {
            $fields[] = 'EMITIDO_USUARIO';
            $values[] = $data['emitido_usuario'];
        }
        
        if (isset($data['emitido_hora'])) {
            $fields[] = 'EMITIDO_HORA';
            $values[] = $data['emitido_hora'];
        }
        
        if (isset($data['emitido_observacion'])) {
            $fields[] = 'EMITIDO_OBSERVACION';
            $values[] = $data['emitido_observacion'];
        }
        
        // Crear los placeholders para la consulta SQL
        $placeholders = array_fill(0, count($fields), '?');
        
        // Construir la consulta SQL
        $sql = "INSERT INTO legajo (" . implode(", ", $fields) . ") 
                VALUES (" . implode(", ", $placeholders) . ")";
        
        try {
            return Database::insert($sql, $values);
        } catch (Exception $e) {
            error_log("Error creating legajo: " . $e->getMessage());
            return false;
        }
    }
    
    public static function existeCombinacion($data)
    {
        $sql = "SELECT COUNT(*) as count 
                FROM legajo 
                WHERE TIPO_DOCUMENTO = ? 
                AND N_DOCUMENTO = ? 
                AND DOCUMENTO_ID = ? 
                AND EJERCICIO = ? 
                AND PERIODO = ?";
                
        $values = [
            $data['tipo_documento'],
            $data['n_documento'],
            $data['documento_id'],
            $data['ejercicio'],
            $data['periodo']
        ];
    
        try {
            $result = Database::query($sql, $values);
            return isset($result[0]['count']) && $result[0]['count'] > 0;
        } catch (Exception $e) {
            error_log("Error checking combination: " . $e->getMessage());
            return false;
        }
    }
    

    public static function update($id, $data)
    {
        try {
            // Verificar que el legajo existe
            $existingLegajo = self::findById($id);
            if (empty($existingLegajo)) {
                throw new Exception("Legajo no encontrado");
            }
    
            // Construir la consulta SQL dinámicamente
            $setClauses = [];
            $params = [];
            
            foreach ($data as $key => $value) {
                if ($value !== null) {
                    $setClauses[] = strtoupper($key) . " = ?";
                    $params[] = $value;
                }
            }
            
            if (empty($setClauses)) {
                return false;
            }
            
            // Agregar el ID al final de los parámetros
            $params[] = $id;
            
            $sql = "UPDATE legajo SET " . implode(", ", $setClauses) . " WHERE ID = ?";
            
            return Database::update($sql, $params);
        } catch (Exception $e) {
            error_log("Error updating legajo: " . $e->getMessage());
            return false;
        }
    }
    

    public static function delete($id) {
        try {
            return Database::delete("DELETE FROM legajo WHERE ID = ?", [$id]);
        } catch (Exception $e) {
            error_log("Error al eliminar legajo: " . $e->getMessage());
            throw $e;
        }
    }
    // En Legajo.php
    public static function deleteByCombination($tipoDocumento, $nDocumento, $documentoId, $ejercicio, $periodo)
    {
        try {
            $sql = "DELETE FROM legajo WHERE 
                    TIPO_DOCUMENTO = ? AND 
                    N_DOCUMENTO = ? AND 
                    DOCUMENTO_ID = ? AND 
                    EJERCICIO = ? AND 
                    PERIODO = ?";
                    
            return Database::delete($sql, [
                $tipoDocumento,
                $nDocumento,
                $documentoId,
                $ejercicio,
                $periodo
            ]);
        } catch (Exception $e) {
            error_log("Error en deleteByCombination: " . $e->getMessage());
            throw $e;
        }
    }
}
