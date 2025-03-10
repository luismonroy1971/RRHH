<?php
namespace Controllers;

use Models\Usuario;
use Libs\Response;

class AuthController
{
    /**
     * Muestra el formulario de login
     */
    public static function showLoginForm()
    {
        // Verificar si el usuario ya está autenticado
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
        
        require_once __DIR__ . '/../Views/Auth/Login.php';
    }

    /**
     * Procesa el login de usuarios
     */
    public static function login()
    {
        // Activar reporte de errores para debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: /login');
                exit;
            }
            
            $username = $_POST['username'] ?? null;
            $password = $_POST['password'] ?? null;
    
            if (!$username || !$password) {
                header('Location: /login?error=Usuario y contraseña son requeridos');
                exit;
            }
    
            $user = Usuario::findByUsername($username);
                
            if (!$user || !password_verify($password, $user['CONTRASENA'])) {
                header('Location: /login?error=Usuario o contraseña incorrectos');
                exit;
            }
        
            // Iniciar sesión
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['username'] = $user['NOMBRE_USUARIO'];
            $_SESSION['role'] = $user['ROL'];
            
            // Manejar "Recordar sesión" si está marcado
            if (isset($_POST['remember']) && $_POST['remember'] == 'on') {
                // Configurar cookies para recordar al usuario por 30 días
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    session_id(),
                    time() + 60*60*24*30, // 30 días
                    $params["path"],
                    $params["domain"],
                    $params["secure"],
                    $params["httponly"]
                );
            }
        
            header('Location: /');
            exit;
            
        } catch (\Exception $e) {
            error_log("Error en login: " . $e->getMessage());
            header('Location: /login?error=Error del sistema: ' . $e->getMessage());
            exit;
        }
    }

    /**
     * Cierra la sesión del usuario
     */
    public static function logout()
    {
        try {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            session_unset();
            session_destroy();
            
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            
            header('Location: /login?message=Sesión cerrada exitosamente');
            exit;
        } catch (\Exception $e) {
            error_log("Error en logout: " . $e->getMessage());
            header('Location: /login?error=Error al cerrar sesión');
            exit;
        }
    }

    /**
     * Verifica si el usuario está autenticado para uso en API
     */
    public static function isAuthenticated()
    {
        try {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            if (isset($_SESSION['user_id'])) {
                return Response::json([
                    'authenticated' => true,
                    'user' => [
                        'id' => $_SESSION['user_id'],
                        'username' => $_SESSION['username'],
                        'role' => $_SESSION['role']
                    ]
                ]);
            }
            return Response::json(['authenticated' => false], 401);
        } catch (\Exception $e) {
            error_log("Error en isAuthenticated: " . $e->getMessage());
            return Response::json(['error' => 'Error del sistema'], 500);
        }
    }
}