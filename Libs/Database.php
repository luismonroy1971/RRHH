<?php
// /Libs/Database.php
namespace Libs;
use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;

    // Cambiado de private a protected
    protected function __construct()
    {
        $host = 'localhost';
        $dbname = 'gestor_rrhh';
        $username = 'root';
        $password = '';

        try {
            $this->connection = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error en la conexión: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // El resto de los métodos permanecen igual
    public static function query($sql, $params = [])
    {
        $instance = self::getInstance();
        try {
            $stmt = $instance->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error en la consulta: " . $e->getMessage());
        }
    }

    public static function insert($sql, $params = [])
    {
        $instance = self::getInstance();
        try {
            $stmt = $instance->connection->prepare($sql);
            $stmt->execute($params);
            return $instance->connection->lastInsertId();
        } catch (PDOException $e) {
            die("Error en la inserción: " . $e->getMessage());
        }
    }

    public static function update($sql, $params = [])
    {
        $instance = self::getInstance();
        try {
            $stmt = $instance->connection->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            die("Error en la actualización: " . $e->getMessage());
        }
    }

    public static function delete($sql, $params = [])
    {
        $instance = self::getInstance();
        try {
            $stmt = $instance->connection->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            die("Error en la eliminación: " . $e->getMessage());
        }
    }
}