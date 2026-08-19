<?php


require __DIR__ . '/config/database.php';
require __DIR__ . '/models/Employee.php';
require __DIR__ . '/controllers/EmployeeController.php';

$model      = new Employee($pdo);
$controller = new EmployeeController($model);

$page = $_GET['page'] ?? 'dashboard';

switch ($page) {
    case 'reports':
        $controller->reports();
        break;
    case 'graphs':
        $controller->graphs();
        break;
    case 'employees':
        $controller->employees();
        break;
    case 'dashboard':
    default:
        $controller->dashboard();
        break;
}