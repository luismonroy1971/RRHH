<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Colaboradores</title>
    <style>
        /* Estilos Generales */
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            margin: 0;
            background-color: #f8f9fa;
            color: #333;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #343a40;
            color: #fff;
            padding: 10px 20px;
        }

        .top-bar a {
            color: #f8d7da;
            text-decoration: none;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 4px;
        }

        .top-bar a:hover {
            background: #495057;
            color: #fff;
        }

        .container {
            max-width: 90%;
            margin: 40px auto;
            background: #fff;
            padding: 20px 30px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-bottom: 20px;
            color: #343a40;
        }

        .controls {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #dee2e6;
            text-align: left;
        }

        table th {
            background-color: #f1f3f5;
        }

        table input {
            width: 100%;
            padding: 5px;
            box-sizing: border-box;
            text-transform: uppercase;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }

        .pagination a.disabled {
            background: #ccc;
            pointer-events: none;
        }

        .add-button {
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: bold;
        }

        .add-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <!-- Barra Superior -->
    <div class="top-bar">
        <a href="/">← Ir al Panel de Administración</a>
        <a href="/logout">Cerrar Sesión</a>
    </div>

    <!-- Contenido Principal -->
    <div class="container">
        <h1>Gestión de Colaboradores</h1>

        <!-- Botón Adicionar -->
        <div class="controls">
            <a href="/colaboradores/create" class="add-button">Adicionar Colaborador</a>
            <div>
                <label for="rows_per_page">Datos por página:</label>
                <select id="rows_per_page" onchange="changeRowsPerPage(this.value)">
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        <!-- Filtros por columnas -->
        <table>
            <thead>
                <tr>
                    <th><input type="text" id="filter_id" placeholder="ID"></th>
                    <th><input type="text" id="filter_tipo" placeholder="Tipo Documento"></th>
                    <th><input type="text" id="filter_numero" placeholder="Número Documento"></th>
                    <th><input type="text" id="filter_nombre" placeholder="Nombre"></th>
                    <th><input type="text" id="filter_fecha" placeholder="Fecha Ingreso"></th>
                    <th><input type="text" id="filter_area" placeholder="Área"></th>
                    <th><input type="text" id="filter_correo" placeholder="Correo"></th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="table_body"></tbody>
        </table>

        <!-- Controles de Paginación -->
        <div class="pagination" id="pagination_controls"></div>
    </div>

    <script>
        let colaboradores = [];
        let filteredData = [];
        let currentPage = 1;
        let rowsPerPage = 10;

        async function fetchColaboradores() {
            try {
                const response = await fetch('/colaboradores/list');
                const result = await response.json();
                colaboradores = result.data || [];
                filteredData = colaboradores;
                displayTable();
            } catch (error) {
                console.error("Error al obtener colaboradores:", error);
            }
        }

        function displayTable() {
            const tableBody = document.getElementById("table_body");
            tableBody.innerHTML = "";

            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const paginatedItems = filteredData.slice(start, end);

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
                            <a href="/colaboradores/update?id=${col.ID}" class="add-button">Editar</a>
                        </td>
                    </tr>
                `;
            });

            displayPagination();
        }

        function displayPagination() {
            const paginationControls = document.getElementById("pagination_controls");
            paginationControls.innerHTML = "";
            const totalPages = Math.ceil(filteredData.length / rowsPerPage);

            for (let i = 1; i <= totalPages; i++) {
                paginationControls.innerHTML += `
                    <a href="#" onclick="changePage(${i})" class="${i === currentPage ? 'disabled' : ''}">${i}</a>
                `;
            }
        }

        function changePage(page) {
            currentPage = page;
            displayTable();
        }

        function changeRowsPerPage(value) {
            rowsPerPage = parseInt(value);
            currentPage = 1;
            displayTable();
        }

        document.querySelectorAll("input").forEach(input => {
            input.addEventListener("input", filterTable);
        });

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
                (filters.tipo === "" || col.TIPO_DOCUMENTO.toString().includes(filters.tipo)) &&
                (filters.numero === "" || col.N_DOCUMENTO.includes(filters.numero)) &&
                (filters.nombre === "" || col.APELLIDOS_NOMBRES.toUpperCase().includes(filters.nombre)) &&
                (filters.fecha === "" || col.FECHA_INGRESO.includes(filters.fecha)) &&
                (filters.area === "" || col.AREA.toUpperCase().includes(filters.area)) &&
                (filters.correo === "" || col.CORREO.toUpperCase().includes(filters.correo))
            );

            currentPage = 1;
            displayTable();
        }

        fetchColaboradores();
    </script>
</body>
</html>
