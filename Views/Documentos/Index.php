<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tema Litoclean - Gestión de Documentos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <style>
        :root {
            --primary-color: #1e88e5;
            --primary-dark: #005cb2;
            --primary-light: #e3f2fd;
            --secondary-color: #607d8b;
            --success-color: #4caf50;
            --danger-color: #f44336;
            --warning-color: #ff9800;
            --info-color: #2196f3;
            --dark-color: #343a40;
            --light-color: #f8f9fa;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
            --border-radius: 0.5rem;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: var(--gray-100);
            color: var(--gray-800);
            line-height: 1.6;
            min-height: 100vh;
            display: grid;
            grid-template-rows: auto 1fr;
        }
        
        /* Navbar */
        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 0.5rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .navbar-brand img {
            height: 40px;
            width: auto;
        }
        
        .navbar-brand h1 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
        }
        
        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .back-btn, .logout-btn {
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        
        .back-btn {
            background-color: var(--gray-200);
            color: var(--gray-700);
        }
        
        .back-btn:hover {
            background-color: var(--gray-300);
        }
        
        .logout-btn {
            background-color: var(--gray-200);
            color: var(--gray-700);
        }
        
        .logout-btn:hover {
            background-color: var(--gray-300);
        }
        
        /* Main Content */
        .main-content {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--gray-300);
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-800);
        }
        
        .add-button {
            padding: 0.5rem 1.25rem;
            background-color: var(--primary-color);
            color: white;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
        }
        
        .add-button:hover {
            background-color: var(--primary-dark);
        }
        
        /* Alert Messages */
        .alert {
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .alert-success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #2e7d32;
        }
        
        .alert-error {
            background-color: #ffebee;
            color: #c62828;
            border-left: 4px solid #c62828;
        }
        
        .alert-warning {
            background-color: #fff8e1;
            color: #f57f17;
            border-left: 4px solid #f57f17;
        }
        
        /* Data Table */
        .data-table-wrapper {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table th {
            background-color: var(--gray-100);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--gray-700);
            border-bottom: 2px solid var(--gray-300);
        }
        
        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--gray-200);
            color: var(--gray-800);
        }
        
        .data-table tr:last-child td {
            border-bottom: none;
        }
        
        .data-table tr:hover {
            background-color: var(--gray-100);
        }
        
        .table-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .action-btn {
            padding: 0.375rem 0.75rem;
            border-radius: var(--border-radius);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.25rem;
            border: none;
            text-decoration: none;
        }
        
        .edit-btn {
            background-color: var(--warning-color);
            color: white;
        }
        
        .edit-btn:hover {
            background-color: #e68a00;
        }
        
        .delete-btn {
            background-color: var(--danger-color);
            color: white;
        }
        
        .delete-btn:hover {
            background-color: #d32f2f;
        }
        
        /* Category badge */
        .category-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .category-legales {
            background-color: var(--primary-light);
            color: var(--primary-dark);
        }
        
        .category-rrhh {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        
        .category-finanzas {
            background-color: #fff8e1;
            color: #f57f17;
        }
        
        .category-otros {
            background-color: var(--gray-200);
            color: var(--gray-700);
        }
        
        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--gray-600);
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--gray-400);
        }
        
        .empty-state p {
            font-size: 1.125rem;
            margin-bottom: 1.5rem;
        }
        
        /* Search and filters */
        .filters-section {
            margin-bottom: 1.5rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .search-box {
            display: flex;
            flex: 1;
            min-width: 300px;
        }
        
        .search-input {
            flex: 1;
            padding: 0.5rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius) 0 0 var(--border-radius);
            font-size: 0.875rem;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .search-button {
            padding: 0.5rem 1rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            cursor: pointer;
        }
        
        .filter-dropdown {
            padding: 0.5rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius);
            background-color: white;
            font-size: 0.875rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                padding: 0.5rem 1rem;
            }
            
            .navbar-brand h1 {
                font-size: 1rem;
            }
            
            .main-content {
                padding: 1.5rem 1rem;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .data-table {
                display: block;
                overflow-x: auto;
            }
            
            .table-actions {
                flex-direction: column;
                gap: 0.25rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">
            <img src="/assets/logo.png" alt="Tema Litoclean">
            <h1>Sistema de Gestión</h1>
        </div>
        
        <div class="navbar-actions">
            <a href="/" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Panel de Administración
            </a>
            
            <a href="/logout" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                Cerrar Sesión
            </a>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="main-content">
        <?php if (isset($_GET['message'])): ?>
            <div id="alert-message" class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_GET['message']) ?>
            </div>
            <script>
                // Ocultar el mensaje después de 3 segundos
                setTimeout(function() {
                    document.getElementById('alert-message').style.display = 'none';
                }, 3000);
            </script>
        <?php endif; ?>
        
        <div class="page-header">
            <h2 class="page-title">Gestión de Documentos</h2>
            <a href="/documentos/create" class="add-button">
                <i class="fas fa-file-plus"></i>
                Agregar Nuevo Documento
            </a>
        </div>
        
        
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($documentos)): ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="fas fa-file-alt"></i>
                                    <p>No se encontraron documentos en el sistema</p>
                                    <a href="/documentos/create" class="add-button">
                                        <i class="fas fa-file-plus"></i>
                                        Agregar Nuevo Documento
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($documentos as $doc): ?>
                            <tr>
                                <td><?= htmlspecialchars($doc['ID']) ?></td>
                                <td><strong><?= htmlspecialchars($doc['CODIGO']) ?></strong></td>
                                <td><?= htmlspecialchars($doc['DESCRIPCION']) ?></td>
                                <td>
                                    <?php
                                    $categoryClass = 'category-otros';
                                    $categoria = htmlspecialchars($doc['CATEGORIA']);
                                    
                                    if (stripos($categoria, 'legal') !== false) {
                                        $categoryClass = 'category-legales';
                                    } elseif (stripos($categoria, 'rrhh') !== false) {
                                        $categoryClass = 'category-rrhh';
                                    } elseif (stripos($categoria, 'finanz') !== false) {
                                        $categoryClass = 'category-finanzas';
                                    }
                                    ?>
                                    <span class="category-badge <?= $categoryClass ?>"><?= $categoria ?></span>
                                </td>
                                <td class="table-actions">
                                    <a href="/documentos/update/<?= urlencode($doc['ID']) ?>" class="action-btn edit-btn">
                                        <i class="fas fa-edit"></i>
                                        Editar
                                    </a>
                                    <form action="/documentos/delete" method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($doc['ID']) ?>">
                                        <button type="submit" class="action-btn delete-btn" onclick="return confirm('¿Está seguro de eliminar este documento? Esta acción no se puede deshacer.');">
                                            <i class="fas fa-trash-alt"></i>
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>