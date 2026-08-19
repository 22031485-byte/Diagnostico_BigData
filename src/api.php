<?php
/**
 * api.php — Front Controller de la API
 * Único punto de entrada para las peticiones AJAX. Devuelve siempre JSON.
 */

header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/config/database.php';
require __DIR__ . '/models/Employee.php';
require __DIR__ . '/controllers/ApiController.php';

$model      = new Employee($pdo);
$controller = new ApiController($model);

$action = $_GET['action'] ?? '';
$result = $controller->handle($action, $_GET);

echo json_encode($result, JSON_UNESCAPED_UNICODE);