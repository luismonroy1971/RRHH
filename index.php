<?php 
// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

try {
    // Requires con rutas absolutas
    require_once __DIR__ . '/Libs/Database.php';
    require_once __DIR__ . '/Libs/Response.php';
    require_once __DIR__ . '/Controllers/AuthController.php';
    require_once __DIR__ . '/Controllers/ColaboradoresController.php';
    require_once __DIR__ . '/Controllers/DocumentosController.php';
    require_once __DIR__ . '/Controllers/LegajoController.php';
    require_once __DIR__ . '/Controllers/UsuariosController.php';
    require_once __DIR__ . '/Controllers/DashboardController.php';

    require_once __DIR__ . '/Models/Usuario.php';
    require_once __DIR__ . '/Models/Colaborador.php';
    require_once __DIR__ . '/Models/DocumentoIdentidad.php';
    require_once __DIR__ . '/Models/Documentos.php';
    require_once __DIR__ . '/Models/Legajo.php';

    // Obtener la ruta solicitada
    $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = str_replace($basePath, '', $path);
    $path = trim($path, '/');

    // Dividir la ruta en segmentos
    $segments = explode('/', $path);

    // Segmentos de la URL
    $controller = $segments[0] ?? '';
    $action = $segments[1] ?? '';
    $id = $segments[2] ?? '';

    switch ($controller) {
        case '':
            // Ruta raíz - Dashboard
            Controllers\DashboardController::index();
            break;

        case 'login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                Controllers\AuthController::login();
            } else {
                require_once __DIR__ . '/Views/Auth/Login.php';
            }
            break;

        case 'logout':
            Controllers\AuthController::logout();
            break;

        case 'colaboradores':
            if ($action === '') {
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    Controllers\ColaboradoresController::index();
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    Controllers\ColaboradoresController::create();
                }
            } elseif ($action === 'list' && $_SERVER['REQUEST_METHOD'] === 'GET') {
                Controllers\ColaboradoresController::listAll();
            } elseif ($action === 'create') {
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    Controllers\ColaboradoresController::showCreateForm();
                }
            } elseif ($action === 'search') {
                $tipoDocumento = $_GET['tipo_documento'] ?? null;
                $nDocumento = $_GET['n_documento'] ?? null;
                Controllers\ColaboradoresController::search($tipoDocumento, $nDocumento);
            } elseif ($action === 'update') {
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $id = $_GET['id'] ?? null;
                    if ($id) {
                        Controllers\ColaboradoresController::showEditForm($id);
                    } else {
                        header('Location: /colaboradores?error=ID no proporcionado');
                        exit;
                    }
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    Controllers\ColaboradoresController::update();
                }
            } elseif ($action === 'delete') {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    Controllers\ColaboradoresController::delete();
                }
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Ruta no encontrada', 'ruta' => $path]);
            }
            break;

        case 'documentos':
            if ($action === '') {
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    Controllers\DocumentosController::index();
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    Controllers\DocumentosController::create();
                }
            } elseif ($action === 'list' && $_SERVER['REQUEST_METHOD'] === 'GET') {
                Controllers\DocumentosController::listAll();
            } elseif ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                Controllers\DocumentosController::create();
            } elseif ($action === 'create') {
                require_once __DIR__ . '/Views/Documentos/Create.php';
            } elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'GET' && isset($id)) {
                Controllers\DocumentosController::edit($id);
            } elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                Controllers\DocumentosController::update();
            } elseif ($action === 'delete') {
                Controllers\DocumentosController::delete();
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Ruta no encontrada']);
            }
            break;

        case 'legajo':
            if ($action === '') {
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    Controllers\LegajoController::getAll();
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    Controllers\LegajoController::create();
                }
            } elseif ($action === 'api' && $_SERVER['REQUEST_METHOD'] === 'GET') {
                Controllers\LegajoController::getAll();
            } elseif ($action === 'create') {
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    require_once __DIR__ . '/Views/Legajo/Create.php';
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    Controllers\LegajoController::create();
                }
            } elseif ($action === 'update') {
                if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($id)) {
                    $legajo = Models\Legajo::findById($id);
                    if ($legajo) {
                        require_once __DIR__ . '/Views/Legajo/Edit.php';
                    } else {
                        header('Location: /legajo?error=Legajo no encontrado');
                    }
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    Controllers\LegajoController::update();
                }
            } elseif ($action === 'simple' && $_SERVER['REQUEST_METHOD'] === 'GET') {
                Controllers\LegajoController::getAllSimple();
            } elseif ($action === 'delete') {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    Controllers\LegajoController::delete();
                }
            } elseif ($action === 'edit' && isset($id)) {
                Controllers\LegajoController::edit($id);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Ruta no encontrada', 'ruta' => $path]);
            }
            break;

        case 'usuarios':
            if ($action === '') {
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    Controllers\UsuariosController::getAll();
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    Controllers\UsuariosController::create();
                }
            } elseif ($action === 'create') {
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    Controllers\UsuariosController::showCreateForm();
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    Controllers\UsuariosController::create();
                } else {
                    http_response_code(405);
                    echo json_encode(['error' => 'Método no permitido']);
                }
            } elseif ($action === 'update') {
                if ($id && $_SERVER['REQUEST_METHOD'] === 'GET') {
                    Controllers\UsuariosController::showEditForm($id);
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    Controllers\UsuariosController::update();
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Ruta no encontrada', 'ruta' => $path]);
                }
            } elseif ($action === 'delete') {
                if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
                    Controllers\UsuariosController::delete();
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Ruta no encontrada', 'ruta' => $path]);
                }
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Ruta no encontrada', 'ruta' => $path]);
            }
            break;

        default:
            http_response_code(404);
            echo json_encode(['error' => 'Ruta no encontrada', 'ruta' => $path]);
            break;
    }

} catch (Exception $e) {
   error_log("Error en index.php: " . $e->getMessage());
    echo "Error del sistema: " . $e->getMessage();
}