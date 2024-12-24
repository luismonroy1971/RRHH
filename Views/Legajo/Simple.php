<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Simple de Legajos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f3f4f6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #2563eb;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="/legajo" class="back-link">← Volver a la vista principal</a>
        <h1>Lista Simple de Legajos</h1>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo Documento</th>
                    <th>Número Documento</th>
                    <th>Apellidos y Nombres</th>
                    <th>Ejercicio</th>
                    <th>Periodo</th>
                    <th>Emitido</th>
                    <th>Subido</th>
                    <th>Físico</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($legajos) && !empty($legajos)): ?>
                    <?php foreach ($legajos as $legajo): ?>
                        <tr>
                            <td><?= $legajo['ID'] ?></td>
                            <td><?= $legajo['TIPO_DOCUMENTO'] === 1 ? 'DNI' : 'CE' ?></td>
                            <td><?= $legajo['N_DOCUMENTO'] ?></td>
                            <td><?= $legajo['APELLIDOS_NOMBRES'] ?: '-' ?></td>
                            <td><?= $legajo['EJERCICIO'] ?></td>
                            <td><?= $legajo['PERIODO'] ?></td>
                            <td><?= $legajo['EMITIDO'] ? 'Sí' : 'No' ?></td>
                            <td><?= $legajo['SUBIDO'] ? 'Sí' : 'No' ?></td>
                            <td><?= $legajo['FISICO'] == 1 ? 'Sí' : 'No' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">No hay registros disponibles</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>