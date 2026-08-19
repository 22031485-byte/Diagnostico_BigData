<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title ?? 'Portal de Empleados') ?> · Big Data</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="public/css/style.css">
<script src="public/js/chart.umd.js"></script>
</head>
<body>
<header class="topbar">
    <div class="brand">
        <svg class="brand-mark" width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <circle cx="13" cy="13" r="12" stroke="#5FD3E0" stroke-width="2"/>
            <path d="M8 14.5L11 17.5L18 9.5" stroke="#5FD3E0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>Analítica de Empleados</span>
    </div>
    <?php $currentPage = $_GET['page'] ?? 'dashboard'; ?>
    <nav class="mainnav">
        <a href="index.php?page=dashboard" class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
        <a href="index.php?page=reports" class="<?= $currentPage === 'reports' ? 'active' : '' ?>">Reportes</a>
        <a href="index.php?page=graphs" class="<?= $currentPage === 'graphs' ? 'active' : '' ?>">Gráficos</a>
        <a href="index.php?page=employees" class="<?= $currentPage === 'employees' ? 'active' : '' ?>">Consulta de Empleados</a>
    </nav>
</header>
<main class="content">