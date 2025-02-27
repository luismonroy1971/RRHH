<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tema Litoclean - Gestión de Colaboradores</title>
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
            max-width: 1400px;
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
        
        /* Table Controls */
        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .add-button {
            padding: 0.5rem 1.25rem;
            background-color: var(--success-color);
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
            background-color: #2e7d32;
        }
        
        .rows-selector {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .rows-selector label {
            font-size: 0.875rem;
            color: var(--gray-700);
        }
        
        .rows-selector select {
            padding: 0.375rem 0.75rem;
            border-radius: var(--border-radius);
            border: 1px solid var(--gray-300);
            background-color: white;
            font-size: 0.875rem;
            color: var(--gray-800);
        }
        
        /* Table and Filters */
        .table-container {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table th {
            background-color: var(--gray-100);
            padding: 1rem 0.75rem;
            text-align: left;
            font-weight: 600;
            color: var(--gray-700);
            position: relative;
        }
        
        .data-table td {
            padding: 0.75rem;
            border-top: 1px solid var(--gray-200);
            color: var(--gray-800);
        }
        
        .data-table tr:hover td {
            background-color: var(--gray-100);
        }
        
        .data-table tr:last-child td {
            border-bottom: none;
        }
        
        .filter-input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius);
            font-size: 0.75rem;
            margin-top: 0.5rem;
        }
        
        .filter-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.1);
        }
        
        .edit-btn {
            padding: 0.375rem 0.75rem;
            background-color: var(--warning-color);
            color: white;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            transition: var(--transition);
        }
        
        .edit-btn:hover {
            background-color: #e68a00;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }
        
        .page-link {
            min-width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 0.5rem;
            border-radius: var(--border-radius);
            background-color: white;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            border: 1px solid var(--gray-300);
        }
        
        .page-link:hover {
            background-color: var(--primary-light);
        }
        
        .page-link.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            cursor: default;
        }
        
        /* Empty state */
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
        
        /* Skeleton loading */
        .skeleton {
            background: linear-gradient(90deg, var(--gray-200) 25%, var(--gray-300) 50%, var(--gray-200) 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s infinite;
            border-radius: var(--border-radius);
            height: 1.25rem;
        }
        
        @keyframes skeleton-loading {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .main-content {
                padding: 1.5rem;
            }
        }
        
        @media (max-width: 768px) {
            .navbar {
                padding: 0.5rem 1rem;
            }
            
            .navbar-brand h1 {
                font-size: 1rem;
            }
            
            .main-content {
                padding: 1rem;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .table-controls {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .data-table {
                display: block;
                overflow-x: auto;
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
        <div class="page-header">
            <h2 class="page-title">Gestión de Colaboradores</h2>
        </div>
        
        <div class="table-controls">
            <a href="/colaboradores/create" class="add-button">
                <i class="fas fa-user-plus"></i>
                Adicionar Colaborador
            </a>
            
            <div class="rows-selector">
                <label for="rows_per_page">Datos por página:</label>
                <select id="rows_per_page" onchange="changeRowsPerPage(this.value)">
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
        
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>
                            ID
                            <input type="text" id="filter_id" class="filter-input" placeholder="Filtrar por ID">
                        </th>
                        <th>
                            Tipo Documento
                            <input type="text" id="filter_tipo" class="filter-input" placeholder="Filtrar por tipo">
                        </th>
                        <th>
                            Número Documento
                            <input type="text" id="filter_numero" class="filter-input" placeholder="Filtrar por número">
                        </th>
                        <th>
                            Nombre
                            <input type="text" id="filter_nombre" class="filter-input" placeholder="Filtrar por nombre">
                        </th>
                        <th>
                            Fecha Ingreso
                            <input type="text" id="filter_fecha" class="filter-input" placeholder="YYYY-MM-DD">
                        </th>
                        <th>
                            Área
                            <input type="text" id="filter_area" class="filter-input" placeholder="Filtrar por área">
                        </th>
                        <th>
                            Correo
                            <input type="text" id="filter_correo" class="filter-input" placeholder="Filtrar por correo">
                        </th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="table_body">
                    <!-- Placeholder para la carga -->
                    <tr>
                        <td colspan="8">
                            <div style="padding: 1rem; text-align: center;">
                                <i class="fas fa-spinner fa-pulse" style="font-size: 1.5rem; margin-right: 0.5rem;"></i>
                                Cargando colaboradores...
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div id="pagination_controls" class="pagination"></div>
    </main>

    <script>
        let colaboradores = [];
        let filteredData = [];
        let currentPage = 1;
        let rowsPerPage = 10;

        // Función para cargar los colaboradores
        async function fetchColaboradores() {
            try {
                const tableBody = document.getElementById('table_body');
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="8">
                            <div style="padding: 1rem; text-align: center;">
                                <i class="fas fa-spinner fa-pulse" style="font-size: 1.5rem; margin-right: 0.5rem;"></i>
                                Cargando colaboradores...
                            </div>
                        </td>
                    </tr>
                `;
                
                const response = await fetch('/colaboradores/list');
                const result = await response.json();
                colaboradores = result.data || [];
                filteredData = colaboradores;
                displayTable();
            } catch (error) {
                console.error("Error al obtener colaboradores:", error);
                document.getElementById('table_body').innerHTML = `
                    <tr>
                        <td colspan="8">
                            <div style="padding: 1rem; text-align: center; color: var(--danger-color);">
                                <i class="fas fa-exclamation-triangle" style="font-size: 1.5rem; margin-right: 0.5rem;"></i>
                                Error al cargar los datos. Intente nuevamente.
                            </div>
                        </td>
                    </tr>
                `;
            }
        }

        // Función para mostrar la tabla
        function displayTable() {
            const tableBody = document.getElementById("table_body");
            tableBody.innerHTML = "";

            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const paginatedItems = filteredData.slice(start, end);

            if (filteredData.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-users-slash"></i>
                                <p>No se encontraron colaboradores con los filtros aplicados</p>
                                <a href="/colaboradores/create" class="add-button">
                                    <i class="fas fa-user-plus"></i>
                                    Adicionar Colaborador
                                </a>
                            </div>
                        </td>
                    </tr>
                `;
                document.getElementById('pagination_controls').innerHTML = '';
                return;
            }

            paginatedItems.forEach(col => {
                tableBody.innerHTML += `
                    <tr>
                        <td>${col.ID}</td>
                        <td>${col.TIPO_DOCUMENTO}</td>
                        <td>${col.N_DOCUMENTO}</td>
                        <td>${col.APELLIDOS_NOMBRES}</td>
                        <td>${col.FECHA_INGRESO}</td>
                        <td>${col.AREA}</td>
                        <td>${col.CORREO}</td>
                        <td>
                            <a href="/colaboradores/update?id=${col.ID}" class="edit-btn">
                                <i class="fas fa-edit"></i>
                                Editar
                            </a>
                        </td>
                    </tr>
                `;
            });

            displayPagination();
        }

        // Función para mostrar la paginación
        function displayPagination() {
            const paginationControls = document.getElementById("pagination_controls");
            paginationControls.innerHTML = "";
            const totalPages = Math.ceil(filteredData.length / rowsPerPage);
            
            // Botón anterior
            if (totalPages > 1) {
                paginationControls.innerHTML += `
                    <a href="#" onclick="changePage(${Math.max(1, currentPage - 1)})" class="page-link ${currentPage === 1 ? 'disabled' : ''}">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                `;
            }
            
            // Números de página
            const maxPagesToShow = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
            let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);
            
            // Ajustar startPage si estamos cerca del final
            if (endPage - startPage + 1 < maxPagesToShow && startPage > 1) {
                startPage = Math.max(1, endPage - maxPagesToShow + 1);
            }
            
            // Mostrar "..." si hay muchas páginas al inicio
            if (startPage > 1) {
                paginationControls.innerHTML += `
                    <a href="#" onclick="changePage(1)" class="page-link">1</a>
                `;
                
                if (startPage > 2) {
                    paginationControls.innerHTML += `
                        <span class="page-link">...</span>
                    `;
                }
            }
            
            // Mostrar páginas numeradas
            for (let i = startPage; i <= endPage; i++) {
                paginationControls.innerHTML += `
                    <a href="#" onclick="changePage(${i})" class="page-link ${i === currentPage ? 'active' : ''}">${i}</a>
                `;
            }
            
            // Mostrar "..." si hay muchas páginas al final
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    paginationControls.innerHTML += `
                        <span class="page-link">...</span>
                    `;
                }
                
                paginationControls.innerHTML += `
                    <a href="#" onclick="changePage(${totalPages})" class="page-link">${totalPages}</a>
                `;
            }
            
            // Botón siguiente
            if (totalPages > 1) {
                paginationControls.innerHTML += `
                    <a href="#" onclick="changePage(${Math.min(totalPages, currentPage + 1)})" class="page-link ${currentPage === totalPages ? 'disabled' : ''}">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                `;
            }
        }

        // Función para cambiar de página
        function changePage(page) {
            currentPage = page;
            displayTable();
            // Scroll al inicio de la tabla
            document.querySelector('.table-container').scrollIntoView({ behavior: 'smooth' });
            return false; // Prevenir comportamiento predeterminado del enlace
        }

        // Función para cambiar registros por página
        function changeRowsPerPage(value) {
            rowsPerPage = parseInt(value);
            currentPage = 1;
            displayTable();
        }

        // Configurar los filtros de la tabla
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll(".filter-input").forEach(input => {
                input.addEventListener("input", filterTable);
            });
        });

        // Función para filtrar la tabla
        function filterTable() {
            const filters = {
                id: document.getElementById("filter_id").value.toLowerCase(),
                tipo: document.getElementById("filter_tipo").value.toUpperCase(),
                numero: document.getElementById("filter_numero").value,
                nombre: document.getElementById("filter_nombre").value.toUpperCase(),
                fecha: document.getElementById("filter_fecha").value,
                area: document.getElementById("filter_area").value.toUpperCase(),
                correo: document.getElementById("filter_correo").value.toUpperCase()
            };

            filteredData = colaboradores.filter(col =>
                (filters.id === "" || col.ID.toString().includes(filters.id)) &&
                (filters.tipo === "" || col.TIPO_DOCUMENTO.includes(filters.tipo)) &&
                (filters.numero === "" || col.N_DOCUMENTO.includes(filters.numero)) &&
                (filters.nombre === "" || col.APELLIDOS_NOMBRES.toUpperCase().includes(filters.nombre)) &&
                (filters.fecha === "" || col.FECHA_INGRESO.includes(filters.fecha)) &&
                (filters.area === "" || col.AREA.toUpperCase().includes(filters.area)) &&
                (filters.correo === "" || col.CORREO.toUpperCase().includes(filters.correo))
            );

            currentPage = 1;
            displayTable();
        }

        // Cargar los datos al iniciar
        fetchColaboradores();
    </script>
</body>
</html>