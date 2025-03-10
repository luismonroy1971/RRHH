<?php
// routes.php - Versión mejorada para manejar parámetros correctamente

class Router {
    private $routes = [];
    private $publicRoutes = ['login'];

    public function add($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch($method, $path) {
        // Si la ruta no es pública, verificar autenticación
        $segments = explode('/', trim($path, '/'));
        $firstSegment = $segments[0] ?? '';
        
        if (!in_array($firstSegment, $this->publicRoutes) && $firstSegment !== 'logout') {
            // Iniciar sesión si no está iniciada
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            if (!isset($_SESSION['user_id'])) {
                header('Location: /login');
                exit;
            }
        }

        // Buscar la ruta correspondiente
        foreach ($this->routes as $route) {
            $pattern = $this->pathToRegex($route['path']);
            if ($route['method'] === $method && preg_match($pattern, $path, $matches)) {
                // Eliminar la coincidencia completa
                array_shift($matches);
                
                $controllerName = "\\Controllers\\" . $route['controller'];
                $action = $route['action'];
                
                // Llamar a la acción del controlador con los parámetros
                call_user_func_array([$controllerName, $action], $matches);
                return true;
            }
        }
        
        // Si no se encuentra ninguna ruta
        http_response_code(404);
        echo json_encode(['error' => 'Ruta no encontrada', 'ruta' => $path]);
        return false;
    }

    private function pathToRegex($path) {
        // Convertir parámetros de ruta (:param) a grupos de captura de regex
        $path = preg_replace('/\/:([^\/]+)/', '/([^/]+)', $path);
        return '@^' . $path . '$@';
    }
}

// Definir rutas
$router = new Router();

// Rutas públicas
$router->add('GET', '/login', 'AuthController', 'showLoginForm');
$router->add('POST', '/login', 'AuthController', 'login');
$router->add('GET', '/logout', 'AuthController', 'logout');

// Dashboard
$router->add('GET', '/', 'DashboardController', 'index');

// Rutas de usuarios
$router->add('GET', '/usuarios', 'UsuariosController', 'getAll');
$router->add('POST', '/usuarios', 'UsuariosController', 'create');
$router->add('GET', '/usuarios/create', 'UsuariosController', 'showCreateForm');
$router->add('POST', '/usuarios/create', 'UsuariosController', 'create');
$router->add('GET', '/usuarios/update/:id', 'UsuariosController', 'showEditForm');
$router->add('POST', '/usuarios/update', 'UsuariosController', 'update');
$router->add('POST', '/usuarios/delete', 'UsuariosController', 'delete');

// Rutas de colaboradores
$router->add('GET', '/colaboradores', 'ColaboradoresController', 'index');
$router->add('POST', '/colaboradores', 'ColaboradoresController', 'create');
$router->add('GET', '/colaboradores/list', 'ColaboradoresController', 'listAll');
$router->add('GET', '/colaboradores/create', 'ColaboradoresController', 'showCreateForm');
$router->add('GET', '/colaboradores/search', 'ColaboradoresController', 'search');
$router->add('GET', '/colaboradores/update', 'ColaboradoresController', 'showEditForm');
$router->add('POST', '/colaboradores/update', 'ColaboradoresController', 'update');
$router->add('POST', '/colaboradores/delete', 'ColaboradoresController', 'delete');
$router->add('GET', '/colaboradores/search-by-name', 'ColaboradoresController', 'searchByName');

// Rutas de documentos
$router->add('GET', '/documentos', 'DocumentosController', 'index');
$router->add('POST', '/documentos', 'DocumentosController', 'create');
$router->add('GET', '/documentos/list', 'DocumentosController', 'listAll');
$router->add('GET', '/documentos/create', 'DocumentosController', 'showCreateForm');
$router->add('GET', '/documentos/update/:id', 'DocumentosController', 'edit');
$router->add('POST', '/documentos/update', 'DocumentosController', 'update');
$router->add('POST', '/documentos/delete', 'DocumentosController', 'delete');

// Rutas de legajo
$router->add('GET', '/legajo', 'LegajoController', 'getAll');
$router->add('POST', '/legajo', 'LegajoController', 'create');
$router->add('GET', '/legajo/list', 'LegajoController', 'listAll');
$router->add('GET', '/legajo/api', 'LegajoController', 'getAll');
$router->add('GET', '/legajo/create', 'LegajoController', 'showCreateForm');
$router->add('POST', '/legajo/create', 'LegajoController', 'create');
$router->add('GET', '/legajo/update/:id', 'LegajoController', 'edit');
$router->add('POST', '/legajo/update', 'LegajoController', 'update');
$router->add('GET', '/legajo/verificar-existencia', 'LegajoController', 'verificarExistencia');
$router->add('GET', '/legajo/bulk-upload', 'LegajoController', 'showBulkUploadForm');
$router->add('GET', '/legajo/simple', 'LegajoController', 'getAllSimple');
$router->add('POST', '/legajo/delete', 'LegajoController', 'delete');
$router->add('GET', '/legajo/edit/:id', 'LegajoController', 'edit');
$router->add('POST', '/legajo/delete-by-combination', 'LegajoController', 'deleteByCombination');

return $router;