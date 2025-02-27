<?php
// Verificar si la sesión no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener valores de sesión
$nombreUsuario = $_SESSION['username'] ?? 'Usuario';
$rolUsuario = $_SESSION['role'] ?? 'INVITADO';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tema Litoclean - Panel de Administración</title>
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
        
        .navbar-user {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1rem;
        }
        
        .user-name {
            display: flex;
            flex-direction: column;
        }
        
        .user-name span:first-child {
            font-weight: 600;
            color: var(--gray-800);
        }
        
        .user-name span:last-child {
            font-size: 0.75rem;
            color: var(--gray-600);
        }
        
        .logout-btn {
            padding: 0.5rem 1rem;
            background-color: var(--gray-200);
            color: var(--gray-700);
            border: none;
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
        
        .welcome-section {
            margin-bottom: 2rem;
        }
        
        .welcome-section h2 {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
        }
        
        .welcome-section p {
            color: var(--gray-600);
        }
        
        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .dashboard-card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
            transition: var(--transition);
            border-top: 4px solid transparent;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        
        .dashboard-card.admin {
            border-top-color: var(--primary-color);
        }
        
        .dashboard-card.nominas {
            border-top-color: var(--warning-color);
        }
        
        .dashboard-card.all {
            border-top-color: var(--success-color);
        }
        
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        
        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-800);
        }
        
        .card-icon {
            font-size: 1.5rem;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .card-icon.admin {
            background-color: var(--primary-light);
            color: var(--primary-color);
        }
        
        .card-icon.nominas {
            background-color: #fff8e1;
            color: var(--warning-color);
        }
        
        .card-icon.all {
            background-color: #e8f5e9;
            color: var(--success-color);
        }
        
        .card-description {
            color: var(--gray-600);
            margin-bottom: 1.5rem;
            min-height: 60px;
        }
        
        .card-action {
            text-decoration: none;
            padding: 0.75rem 1rem;
            background-color: var(--gray-100);
            color: var(--gray-800);
            border-radius: var(--border-radius);
            font-weight: 500;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
        }
        
        .card-action:hover {
            background-color: var(--gray-200);
        }
        
        .card-action.admin:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .card-action.nominas:hover {
            background-color: var(--warning-color);
            color: white;
        }
        
        .card-action.all:hover {
            background-color: var(--success-color);
            color: white;
        }
        
        /* Quick Stats */
        .quick-stats {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stats-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .stats-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-800);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }
        
        .stat-item {
            padding: 1rem;
            background-color: var(--gray-100);
            border-radius: var(--border-radius);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            color: var(--gray-600);
            font-size: 0.875rem;
        }
        
        /* For small screens */
        @media (max-width: 768px) {
            .navbar {
                padding: 0.5rem 1rem;
                flex-direction: column;
                gap: 1rem;
            }
            
            .navbar-user {
                width: 100%;
                justify-content: space-between;
            }
            
            .main-content {
                padding: 1.5rem;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .user-name {
                display: none;
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
        
        <div class="navbar-user">
            <div class="user-info">
                <div class="user-avatar">
                    <?= substr(htmlspecialchars($nombreUsuario), 0, 1); ?>
                </div>
                <div class="user-name">
                    <span><?= htmlspecialchars($nombreUsuario); ?></span>
                    <span><?= htmlspecialchars($rolUsuario); ?></span>
                </div>
            </div>
            
            <a href="/logout" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                Cerrar sesión
            </a>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Welcome Section -->
        <section class="welcome-section">
            <h2>Bienvenido, <?= htmlspecialchars($nombreUsuario); ?></h2>
            <p>Panel de control del Sistema de Gestión de Evidencias Documentarias de RRHH</p>
        </section>
        
        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <?php if ($rolUsuario === 'ADMIN'): ?>
            <!-- Admin Cards -->
            <div class="dashboard-card admin">
                <div class="card-header">
                    <h3 class="card-title">Usuarios</h3>
                    <div class="card-icon admin">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <p class="card-description">Gestiona los usuarios del sistema, asigna roles y permisos.</p>
                <a href="/usuarios" class="card-action admin">
                    <i class="fas fa-user-cog"></i>
                    Administrar Usuarios
                </a>
            </div>
            
            <div class="dashboard-card admin">
                <div class="card-header">
                    <h3 class="card-title">Documentos</h3>
                    <div class="card-icon admin">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
                <p class="card-description">Administra los tipos de documentos y sus plantillas.</p>
                <a href="/documentos" class="card-action admin">
                    <i class="fas fa-folder-open"></i>
                    Administrar Documentos
                </a>
            </div>
            
            <div class="dashboard-card admin">
                <div class="card-header">
                    <h3 class="card-title">Colaboradores</h3>
                    <div class="card-icon admin">
                        <i class="fas fa-handshake"></i>
                    </div>
                </div>
                <p class="card-description">Gestiona la información de los colaboradores de la empresa.</p>
                <a href="/colaboradores" class="card-action admin">
                    <i class="fas fa-user-friends"></i>
                    Administrar Colaboradores
                </a>
            </div>
            <?php endif; ?>
            
            <?php if ($rolUsuario === 'NOMINAS'): ?>
            <!-- Nominas Card -->
            <div class="dashboard-card nominas">
                <div class="card-header">
                    <h3 class="card-title">Subida Masiva</h3>
                    <div class="card-icon nominas">
                        <i class="fas fa-upload"></i>
                    </div>
                </div>
                <p class="card-description">Sube múltiples documentos a los legajos de forma simultánea.</p>
                <a href="/legajo/bulk-upload" class="card-action nominas">
                    <i class="fas fa-cloud-upload-alt"></i>
                    Subida Masiva de Documentos
                </a>
            </div>
            <?php endif; ?>
            
            <!-- Card for all roles -->
            <div class="dashboard-card all">
                <div class="card-header">
                    <h3 class="card-title">Legajos</h3>
                    <div class="card-icon all">
                        <i class="fas fa-folder"></i>
                    </div>
                </div>
                <p class="card-description">Accede y gestiona los legajos documentales de los colaboradores.</p>
                <a href="/legajo" class="card-action all">
                    <i class="fas fa-folder-plus"></i>
                    Administrar Legajos
                </a>
            </div>
        </div>
        
    
    </main>
</body>
</html>