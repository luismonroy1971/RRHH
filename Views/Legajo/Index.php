<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tema Litoclean - Gestión de Legajos</title>
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
            color: var(--danger-color);
        }
        
        .logout-btn:hover {
            background-color: var(--gray-300);
        }
        
        /* Main Content */
        .main-content {
            padding: 2rem;
            max-width: 1800px;
            margin: 0 auto;
            width: 100%;
        }
        
        .panel {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--gray-300);
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
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
        
        /* Buttons */
        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: var(--border-radius);
            font-weight: 500;
            font-size: 0.875rem;
            text-decoration: none;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            line-height: 1.5;
        }
        
        .btn-primary {
            background-color: var(--success-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2e7d32;
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            color: white;
        }
        
        .btn-warning:hover {
            background-color: #e68a00;
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #d32f2f;
        }
        
        /* Filters Section */
        .filters-section {
            margin-bottom: 1.5rem;
        }
        
        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .filter-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-700);
        }
        
        .filter-input, 
        .filter-select {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius);
            font-size: 0.875rem;
            background-color: white;
            transition: var(--transition);
        }
        
        .filter-input:focus,
        .filter-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.1);
        }
        
        /* Table Styles */
        .table-container {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            margin-bottom: 1.5rem;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table th {
            background-color: var(--gray-100);
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--gray-700);
            border-bottom: 2px solid var(--gray-300);
            white-space: nowrap;
        }
        
        .data-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--gray-200);
            color: var(--gray-800);
        }
        
        .data-table tr:last-child td {
            border-bottom: none;
        }
        
        .data-table tr:hover td {
            background-color: var(--gray-100);
        }
        
        .view-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .view-link:hover {
            text-decoration: underline;
        }
        
        .actions-cell {
            display: flex;
            gap: 0.5rem;
            white-space: nowrap;
        }
        
        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-align: center;
        }
        
        .badge-success {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        
        .badge-warning {
            background-color: #fff8e1;
            color: #f57f17;
        }
        
        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        
        .per-page-control {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .per-page-select {
            padding: 0.375rem 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius);
            font-size: 0.875rem;
            background-color: white;
        }
        
        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .page-btn {
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius);
            background-color: white;
            color: var(--gray-700);
            font-size: 0.875rem;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .page-btn:hover:not(:disabled) {
            background-color: var(--gray-100);
            border-color: var(--gray-400);
        }
        
        .page-btn:disabled {
            background-color: var(--gray-100);
            color: var(--gray-400);
            cursor: not-allowed;
        }
        
        .page-input {
            width: 3rem;
            height: 2rem;
            text-align: center;
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius);
            font-size: 0.875rem;
        }
        
        /* Empty State */
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
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
        
        /* Responsive Adjustments */
        @media (max-width: 1200px) {
            .main-content {
                padding: 1.5rem;
            }
            
            .filters-grid {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            }
        }
        
        @media (max-width: 768px) {
            .navbar {
                padding: 0.5rem 1rem;
            }
            
            .main-content {
                padding: 1rem;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .filters-grid {
                grid-template-columns: 1fr;
            }
            
            .pagination-container {
                flex-direction: column;
                gap: 1rem;
            }
            
            .table-container {
                overflow-x: auto;
            }
            
            .data-table {
                min-width: 800px;
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
        <div class="panel">
            <div class="page-header">
                <h2 class="page-title">Gestión de Legajos</h2>
                
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'NOMINAS'): ?>
                    <a href="/legajo/create" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Agregar Nuevo Legajo
                    </a>
                <?php endif; ?>
            </div>
            
            <?php if (isset($_GET['message'])): ?>
                <div class="alert alert-success" id="alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($_GET['message']) ?>
                </div>
                <script>
                    setTimeout(function() {
                        document.getElementById('alert-success').style.display = 'none';
                    }, 3000);
                </script>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error" id="alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
                <script>
                    setTimeout(function() {
                        document.getElementById('alert-error').style.display = 'none';
                    }, 3000);
                </script>
            <?php endif; ?>
            
            <!-- Filters Section -->
            <div class="filters-section">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label for="tipoDocumento" class="filter-label">Tipo Documento</label>
                        <select id="tipoDocumento" class="filter-select">
                            <option value="">Todos</option>
                            <option value="1">DNI</option>
                            <option value="4">CE</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="numeroDocumento" class="filter-label">Número Documento</label>
                        <input type="text" id="numeroDocumento" class="filter-input" placeholder="Buscar...">
                    </div>
                    
                    <div class="filter-group">
                        <label for="apellidosNombres" class="filter-label">Apellidos y Nombres</label>
                        <input type="text" id="apellidosNombres" class="filter-input" placeholder="Buscar...">
                    </div>
                    
                    <div class="filter-group">
                        <label for="documento" class="filter-label">Documento</label>
                        <select id="documento" class="filter-select">
                            <option value="">Todos</option>
                            <?php foreach (Controllers\LegajoController::getDocumentos() as $doc): ?>
                                <option value="<?= htmlspecialchars($doc['ID']) ?>"><?= htmlspecialchars($doc['DESCRIPCION']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="ejercicio" class="filter-label">Ejercicio</label>
                        <select id="ejercicio" class="filter-select">
                            <option value="">Todos</option>
                            <?php for($year = 2014; $year <= 2025; $year++): ?>
                                <option value="<?= $year ?>"><?= $year ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="periodo" class="filter-label">Periodo</label>
                        <select id="periodo" class="filter-select">
                            <option value="">Todos</option>
                            <option value="1">Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="emitido" class="filter-label">Emitido</label>
                        <select id="emitido" class="filter-select">
                            <option value="">Todos</option>
                            <option value="1">Registrado</option>
                            <option value="0">Pendiente</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="subido" class="filter-label">Subido</label>
                        <select id="subido" class="filter-select">
                            <option value="">Todos</option>
                            <option value="1">Registrado</option>
                            <option value="0">Pendiente</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="fisico" class="filter-label">Físico</label>
                        <select id="fisico" class="filter-select">
                            <option value="">Todos</option>
                            <option value="1">Si</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Table Section -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo Doc.</th>
                            <th>Número Doc.</th>
                            <th>Apellidos y Nombres</th>
                            <th>Documento</th>
                            <th>Ejercicio</th>
                            <th>Periodo</th>
                            <th>Emitido</th>
                            <th>Firmado</th>
                            <th>Físico</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="legajosTableBody">
                        <!-- Placeholder para la carga inicial -->
                        <tr>
                            <td colspan="11">
                                <div style="padding: 2rem; text-align: center;">
                                    <i class="fas fa-spinner fa-pulse" style="font-size: 1.5rem; color: var(--gray-500);"></i>
                                    <p style="margin-top: 0.5rem; color: var(--gray-600);">Cargando datos...</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="pagination-container">
                <div class="per-page-control">
                    <label for="perPage">Mostrar:</label>
                    <select id="perPage" class="per-page-select">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                    <span>registros por página</span>
                </div>
                
                <div class="pagination-controls">
                    <button id="firstPage" class="page-btn" title="Primera página">
                        <i class="fas fa-angle-double-left"></i>
                    </button>
                    <button id="prevPage" class="page-btn" title="Página anterior">
                        <i class="fas fa-angle-left"></i>
                    </button>
                    <span>Página</span>
                    <input type="number" id="currentPage" class="page-input" min="1" value="1">
                    <span>de <span id="totalPages">1</span></span>
                    <button id="nextPage" class="page-btn" title="Página siguiente">
                        <i class="fas fa-angle-right"></i>
                    </button>
                    <button id="lastPage" class="page-btn" title="Última página">
                        <i class="fas fa-angle-double-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Variables de estado
        let currentPage = 1;
        let perPage = 10;
        let totalPages = 1;
        let legajos = [];

        // Elementos del DOM
        const tableBody = document.getElementById('legajosTableBody');
        const perPageSelect = document.getElementById('perPage');
        const currentPageInput = document.getElementById('currentPage');
        const totalPagesSpan = document.getElementById('totalPages');
        const firstPageBtn = document.getElementById('firstPage');
        const prevPageBtn = document.getElementById('prevPage');
        const nextPageBtn = document.getElementById('nextPage');
        const lastPageBtn = document.getElementById('lastPage');

        // Elementos de filtros
        const filters = {
            tipoDocumento: document.getElementById('tipoDocumento'),
            numeroDocumento: document.getElementById('numeroDocumento'),
            apellidosNombres: document.getElementById('apellidosNombres'),
            documento: document.getElementById('documento'),
            ejercicio: document.getElementById('ejercicio'),
            periodo: document.getElementById('periodo'),
            emitido: document.getElementById('emitido'),
            subido: document.getElementById('subido'),
            fisico: document.getElementById('fisico')
        };

        // Función para obtener el nombre del mes
        function getNombreMes(numero) {
            const meses = {
                '1': 'Enero', '2': 'Febrero', '3': 'Marzo', '4': 'Abril',
                '5': 'Mayo', '6': 'Junio', '7': 'Julio', '8': 'Agosto',
                '9': 'Septiembre', '10': 'Octubre', '11': 'Noviembre', '12': 'Diciembre'
            };
            return meses[numero] || '-';
        }

        // Función para renderizar la tabla
        function renderTable() {
            tableBody.innerHTML = '';

            if (!legajos || legajos.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="11">
                            <div class="empty-state">
                                <i class="fas fa-folder-open"></i>
                                <p>No se encontraron legajos con los criterios seleccionados</p>
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'NOMINAS'): ?>
                                    <a href="/legajo/create" class="btn btn-primary">
                                        <i class="fas fa-plus"></i>
                                        Agregar Nuevo Legajo
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            legajos.forEach(legajo => {
                const row = document.createElement('tr');
                
                row.innerHTML = `
                    <td>${legajo.ID}</td>
                    <td>${legajo.TIPO_DOCUMENTO === 1 ? 'DNI' : 'CE'}</td>
                    <td>${legajo.N_DOCUMENTO}</td>
                    <td>${legajo.APELLIDOS_NOMBRES || '-'}</td>
                    <td>${legajo.DOCUMENTO_DESCRIPCION || '-'}</td>
                    <td>${legajo.EJERCICIO}</td>
                    <td>${getNombreMes(legajo.PERIODO)}</td>
                    <td>${legajo.EMITIDO ? 
                        `<a href="${legajo.EMITIDO}" class="view-link" target="_blank">
                            <i class="fas fa-file-pdf"></i> Ver
                        </a>` : 
                        '<span class="badge badge-warning">Pendiente</span>'}</td>
                    <td>${legajo.SUBIDO ? 
                        `<a href="${legajo.SUBIDO}" class="view-link" target="_blank">
                            <i class="fas fa-file-pdf"></i> Ver
                        </a>` : 
                        '<span class="badge badge-warning">Pendiente</span>'}</td>
                    <td>${legajo.FISICO == 1 ? 
                        '<span class="badge badge-success">Si</span>' : 
                        '<span class="badge badge-warning">No</span>'}</td>
                    <td class="actions-cell">
                        <a href="/legajo/edit/${legajo.ID}" class="btn btn-warning">
                            <i class="fas fa-edit"></i>
                            Editar
                        </a>
                        <button onclick="deleteLegajo(${legajo.ID})" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i>
                            Eliminar
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Función para cargar datos
        async function loadData() {
            // Mostrar estado de carga
            tableBody.innerHTML = `
                <tr>
                    <td colspan="11">
                        <div style="padding: 2rem; text-align: center;">
                            <i class="fas fa-spinner fa-pulse" style="font-size: 1.5rem; color: var(--gray-500);"></i>
                            <p style="margin-top: 0.5rem; color: var(--gray-600);">Cargando datos...</p>
                        </div>
                    </td>
                </tr>
            `;
            
            const filterParams = new URLSearchParams({
                page: currentPage,
                per_page: perPage,
                tipo_documento: filters.tipoDocumento.value,
                n_documento: filters.numeroDocumento.value,
                apellidos_nombres: filters.apellidosNombres.value,
                documento: filters.documento.value,
                ejercicio: filters.ejercicio.value,
                periodo: filters.periodo.value,
                emitido: filters.emitido.value,
                subido: filters.subido.value,
                fisico: filters.fisico.value
            });

            try {
                const response = await fetch(`/legajo/api?${filterParams.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    if (response.status === 401) {
                        window.location.href = '/login';
                        return;
                    }
                    throw new Error(`Error HTTP: ${response.status}`);
                }

                const result = await response.json();
                
                if (result && result.data) {
                    legajos = result.data;
                    totalPages = result.pages || 1;
                    renderTable();
                    updatePaginationControls();
                } else {
                    console.error('Formato de respuesta inválido:', result);
                    throw new Error('Formato de respuesta inválido');
                }
            } catch (error) {
                console.error('Error al cargar datos:', error);
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="11">
                            <div class="empty-state">
                                <i class="fas fa-exclamation-triangle" style="color: var(--danger-color);"></i>
                                <p style="color: var(--danger-color);">Error al cargar los datos. Por favor, intente nuevamente.</p>
                                <button onclick="loadData()" class="btn btn-primary">
                                    <i class="fas fa-sync-alt"></i> Reintentar
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                
                // Resetear controles de paginación en caso de error
                totalPages = 1;
                updatePaginationControls();
                
                // Mostrar notificación de error
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-error';
                errorDiv.innerHTML = `
                    <i class="fas fa-exclamation-circle"></i> 
                    Error al conectar con el servidor. Verifique su conexión a internet.
                `;
                
                // Insertar la notificación al inicio del panel
                const panel = document.querySelector('.panel');
                panel.insertBefore(errorDiv, panel.firstChild);
                
                // Eliminar la notificación después de 5 segundos
                setTimeout(() => {
                    errorDiv.remove();
                }, 5000);
            }
        }

        // Función para actualizar controles de paginación
        function updatePaginationControls() {
            totalPagesSpan.textContent = totalPages;
            currentPageInput.value = currentPage;
            currentPageInput.max = totalPages;
            
            firstPageBtn.disabled = currentPage === 1;
            prevPageBtn.disabled = currentPage === 1;
            nextPageBtn.disabled = currentPage === totalPages;
            lastPageBtn.disabled = currentPage === totalPages;
            
            // Actualizar la clase visual de los botones
            [firstPageBtn, prevPageBtn, nextPageBtn, lastPageBtn].forEach(btn => {
                if (btn.disabled) {
                    btn.classList.add('disabled');
                } else {
                    btn.classList.remove('disabled');
                }
            });
        }

        // Función para eliminar legajo
        async function deleteLegajo(id, event) {
            if (!confirm('¿Está seguro de eliminar este legajo? Esta acción no se puede deshacer.')) {
                return;
            }

            try {
                // Mostrar indicador de carga en el botón de eliminar
                const deleteButton = event.currentTarget;
                const originalContent = deleteButton.innerHTML;
                deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Eliminando...';
                deleteButton.disabled = true;
                
                const response = await fetch('/legajo/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ id: id })
                });

                if (response.ok) {
                    const result = await response.json();
                    if (result.message) {
                        // Mostrar mensaje de éxito temporal
                        const successDiv = document.createElement('div');
                        successDiv.className = 'alert alert-success';
                        successDiv.innerHTML = '<i class="fas fa-check-circle"></i> Legajo eliminado correctamente';
                        document.querySelector('.panel').insertBefore(successDiv, document.querySelector('.filters-section'));
                        
                        setTimeout(() => {
                            successDiv.remove();
                        }, 3000);
                        
                        // Si estamos en la última página y es el último registro,
                        // retroceder una página
                        if (currentPage > 1 && legajos.length === 1) {
                            currentPage--;
                        }
                        await loadData();
                    } else {
                        showErrorMessage(result.error || 'Error al eliminar el legajo');
                        // Restaurar el botón
                        deleteButton.innerHTML = originalContent;
                        deleteButton.disabled = false;
                    }
                } else {
                    showErrorMessage('Error al eliminar el legajo');
                    // Restaurar el botón
                    deleteButton.innerHTML = originalContent;
                    deleteButton.disabled = false;
                }
            } catch (error) {
                console.error('Error:', error);
                showErrorMessage('Error al eliminar el legajo: ' + error.message);
                // Asegurarse de que el botón se restaure en caso de error
                const deleteButton = event.currentTarget;
                deleteButton.innerHTML = '<i class="fas fa-trash-alt"></i> Eliminar';
                deleteButton.disabled = false;
            }
        }
        
        // Función para mostrar mensajes de error
        function showErrorMessage(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'alert alert-error';
            errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
            document.querySelector('.panel').insertBefore(errorDiv, document.querySelector('.filters-section'));
            
            setTimeout(() => {
                errorDiv.remove();
            }, 3000);
        }

        // Event Listeners para paginación
        perPageSelect.addEventListener('change', () => {
            perPage = parseInt(perPageSelect.value);
            currentPage = 1;
            loadData();
        });

        firstPageBtn.addEventListener('click', () => {
            if (currentPage !== 1) {
                currentPage = 1;
                loadData();
                // Scroll suave al inicio de la tabla
                document.querySelector('.table-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });

        prevPageBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                loadData();
                // Scroll suave al inicio de la tabla
                document.querySelector('.table-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });

        nextPageBtn.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                loadData();
                // Scroll suave al inicio de la tabla
                document.querySelector('.table-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });

        lastPageBtn.addEventListener('click', () => {
            if (currentPage !== totalPages) {
                currentPage = totalPages;
                loadData();
                // Scroll suave al inicio de la tabla
                document.querySelector('.table-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });

        currentPageInput.addEventListener('change', () => {
            const newPage = parseInt(currentPageInput.value);
            if (newPage && newPage >= 1 && newPage <= totalPages) {
                currentPage = newPage;
                loadData();
                // Scroll suave al inicio de la tabla
                document.querySelector('.table-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                currentPageInput.value = currentPage;
            }
        });
        
        // Prevenir valores no válidos en el input de página
        currentPageInput.addEventListener('keydown', (e) => {
            // Permitir: backspace, delete, tab, escape, enter y números
            if (e.key === 'Backspace' || e.key === 'Delete' || e.key === 'Tab' || 
                e.key === 'Escape' || e.key === 'Enter' || 
                (e.key >= '0' && e.key <= '9')) {
                // Permitir la tecla
                return;
            }
            // Bloquear cualquier otra tecla
            e.preventDefault();
        });

        // Event Listeners para filtros
        Object.values(filters).forEach(filter => {
            filter.addEventListener('change', () => {
                currentPage = 1;
                loadData();
                
            });

            // Para los campos de texto, filtrar mientras se escribe (con debounce)
            if (filter === filters.numeroDocumento || filter === filters.apellidosNombres) {
                let timeout = null;
                filter.addEventListener('keyup', () => {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        currentPage = 1;
                        loadData();
                        
                    }, 300); // Esperar 300ms después de la última pulsación de tecla
                });
            }
        });
        
        // Inicializar tooltips para los elementos interactivos
        function initTooltips() {
            // Se aplica a elementos generados dinámicamente
            document.addEventListener('mouseover', function(e) {
                // Enlaces para ver documentos
                if (e.target.closest('.view-link')) {
                    e.target.closest('.view-link').title = 'Ver documento';
                }
                // Botones de edición
                if (e.target.closest('.btn-warning')) {
                    e.target.closest('.btn-warning').title = 'Editar legajo';
                }
                // Botones de eliminación
                if (e.target.closest('.btn-danger')) {
                    e.target.closest('.btn-danger').title = 'Eliminar legajo';
                }
                // Botones de paginación
                if (e.target.closest('#firstPage')) {
                    e.target.closest('#firstPage').title = 'Primera página';
                }
                if (e.target.closest('#prevPage')) {
                    e.target.closest('#prevPage').title = 'Página anterior';
                }
                if (e.target.closest('#nextPage')) {
                    e.target.closest('#nextPage').title = 'Página siguiente';
                }
                if (e.target.closest('#lastPage')) {
                    e.target.closest('#lastPage').title = 'Última página';
                }
            });
        }
        
        // Actualizar el HTML de la tabla al renderizar
        function renderTable() {
            tableBody.innerHTML = '';

            if (!legajos || legajos.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="11">
                            <div class="empty-state">
                                <i class="fas fa-folder-open"></i>
                                <p>No se encontraron legajos con los criterios seleccionados</p>
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'NOMINAS'): ?>
                                    <a href="/legajo/create" class="btn btn-primary">
                                        <i class="fas fa-plus"></i>
                                        Agregar Nuevo Legajo
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            legajos.forEach(legajo => {
                const row = document.createElement('tr');
                
                row.innerHTML = `
                    <td>${legajo.ID}</td>
                    <td>${legajo.TIPO_DOCUMENTO === 1 ? 'DNI' : 'CE'}</td>
                    <td>${legajo.N_DOCUMENTO}</td>
                    <td>${legajo.APELLIDOS_NOMBRES || '-'}</td>
                    <td>${legajo.DOCUMENTO_DESCRIPCION || '-'}</td>
                    <td>${legajo.EJERCICIO}</td>
                    <td>${getNombreMes(legajo.PERIODO)}</td>
                    <td>${legajo.EMITIDO ? 
                        `<a href="${legajo.EMITIDO}" class="view-link" target="_blank">
                            <i class="fas fa-file-pdf"></i> Ver
                        </a>` : 
                        '<span class="badge badge-warning">Pendiente</span>'}</td>
                    <td>${legajo.SUBIDO ? 
                        `<a href="${legajo.SUBIDO}" class="view-link" target="_blank">
                            <i class="fas fa-file-pdf"></i> Ver
                        </a>` : 
                        '<span class="badge badge-warning">Pendiente</span>'}</td>
                    <td>${legajo.FISICO == 1 ? 
                        '<span class="badge badge-success">Si</span>' : 
                        '<span class="badge badge-warning">No</span>'}</td>
                    <td class="actions-cell">
                        <a href="/legajo/edit/${legajo.ID}" class="btn btn-warning">
                            <i class="fas fa-edit"></i>
                            Editar
                        </a>
                        <button onclick="deleteLegajo(${legajo.ID}, event)" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i>
                            Eliminar
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }
        
        // Mostrar una notificación al usuario cuando se aplican filtros
        function showFilterNotification() {
            
        }
        
        // Función para limpiar todos los filtros
        function clearFilters() {
            Object.values(filters).forEach(filter => {
                filter.value = '';
            });
            
            currentPage = 1;
            loadData();
        
        }
        
        // Manejar el evento de cambio en el tamaño de la ventana
        window.addEventListener('resize', function() {
            // Ajustar cualquier elemento que necesite responder al cambio de tamaño
            const tableContainer = document.querySelector('.table-container');
            if (window.innerWidth < 768) {
                tableContainer.style.overflowX = 'auto';
            } else {
                tableContainer.style.overflowX = 'visible';
            }
        });
        
        // Inicializar tooltips
        initTooltips();
        
        // Verificar filtros aplicados al cargar
        window.addEventListener('load', () => {
            // Eliminamos la verificación de filtros activos al cargar
            
            // Disparar evento resize para ajustar la tabla inicialmente
            window.dispatchEvent(new Event('resize'));
            
            // Eliminar cualquier notificación existente (por si acaso)
            const existingNotices = document.querySelectorAll('.alert-info');
            existingNotices.forEach(notice => notice.remove());
        });
        
        // Cargar datos iniciales
        loadData();
    </script>
</body>
</html>