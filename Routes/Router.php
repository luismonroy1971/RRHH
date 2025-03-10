<?php
// routes.php
namespace Routes;

class Router {
    private $routes = [];
    private $middleware = [];

    /**
     * Añade una ruta al router
     * 
     * @param string $method Método HTTP (GET, POST, etc.)
     * @param string $path Ruta URL
     * @param string $controller Nombre del controlador
     * @param string $action Nombre del método del controlador
     * @param array $middleware Array de middleware a aplicar
     * @return Router
     */
    public function add($method, $path, $controller, $action, $middleware = []) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action,
            'middleware' => $middleware
        ];
        
        return $this;
    }

    /**
     * Añade middleware global para todas las rutas
     * 
     * @param callable|string $middleware Middleware a aplicar
     * @return Router
     */
    public function middleware($middleware) {
        $this->middleware[] = $middleware;
        return $this;
    }

    /**
     * Grupo de rutas con un prefijo común y middleware opcional
     * 
     * @param string $prefix Prefijo de ruta
     * @param callable $callback Función que define las rutas
     * @param array $middleware Middleware a aplicar a todas las rutas del grupo
     * @return Router
     */
    public function group($prefix, $callback, $middleware = []) {
        $originalRoutes = $this->routes;
        $this->routes = [];
        
        call_user_func($callback, $this);
        
        $groupRoutes = $this->routes;
        $this->routes = $originalRoutes;
        
        foreach ($groupRoutes as $route) {
            $route['path'] = $prefix . $route['path'];
            $route['middleware'] = array_merge($middleware, $route['middleware']);
            $this->routes[] = $route;
        }
        
        return $this;
    }

    /**
     * Despacha la solicitud a la ruta correspondiente
     * 
     * @param string $method Método HTTP
     * @param string $path Ruta URL
     * @return bool True si se encontró y ejecutó la ruta, false en caso contrario
     */
    public function dispatch($method, $path) {
        // Aplicar middleware global
        foreach ($this->middleware as $middleware) {
            $this->executeMiddleware($middleware);
        }

        // Buscar la ruta correspondiente
        foreach ($this->routes as $route) {
            $pattern = $this->convertRouteToRegex($route['path']);
            if ($route['method'] === $method && preg_match($pattern, $path, $matches)) {
                array_shift($matches); // Eliminar la coincidencia completa
                
                // Aplicar middleware específico de la ruta
                foreach ($route['middleware'] as $middleware) {
                    $this->executeMiddleware($middleware);
                }
                
                // Obtener el nombre completo del controlador
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

    /**
     * Convierte una ruta con parámetros a una expresión regular
     * 
     * @param string $route Ruta con parámetros opcionales
     * @return string Expresión regular
     */
    private function convertRouteToRegex($route) {
        // Convertir parámetros de la ruta (por ejemplo, /users/:id) a expresiones regulares
        $route = preg_replace('/\/:([^\/]+)/', '/([^/]+)', $route);
        return '#^' . $route . '$#';
    }

    /**
     * Ejecuta un middleware
     * 
     * @param callable|string $middleware Middleware a ejecutar
     * @return void
     */
    private function executeMiddleware($middleware) {
        if (is_callable($middleware)) {
            call_user_func($middleware);
        } elseif (is_string($middleware)) {
            // Si el middleware es una cadena, asumimos que es una clase::método
            list($class, $method) = explode('::', $middleware);
            $fullClassName = "\\Middleware\\" . $class;
            call_user_func([$fullClassName, $method]);
        }
    }
}