<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title ?? 'Portal de Empleados') ?> · Big Data</title>
<link rel="stylesheet" href="public/css/style.css">
<script src="public/js/chart.umd.js"></script>
</head>
<body>
<header class="topbar">
    <div class="brand">
        <span class="brand-mark">●</span>
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