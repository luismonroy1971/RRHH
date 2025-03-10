<?php
namespace Middleware;

class Auth {
    /**
     * Verifica si el usuario está autenticado
     * @return bool
     */
    public static function check() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }

    /**
     * Verifica si el usuario tiene un rol específico
     * @param string|array $roles Rol o array de roles permitidos
     * @return bool
     */
    public static function hasRole($roles) {
        if (!self::check()) {
            return false;
        }
        
        // Convertir a array si es un string
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        
        return in_array($_SESSION['role'], $roles);
    }

    /**
     * Middleware que verifica si el usuario está autenticado
     * Si no lo está, redirige al login
     */
    public static function requireAuth() {
        if (!self::check()) {
            header('Location: /login');
            exit;
        }
    }

    /**
     * Middleware que verifica si el usuario tiene un rol específico
     * Si no lo tiene, redirige a una página de acceso denegado
     * @param string|array $roles Rol o array de roles permitidos
     */
    public static function requireRole($roles) {
        self::requireAuth();
        
        if (!self::hasRole($roles)) {
            header('Location: /acceso-denegado');
            exit;
        }
    }
}