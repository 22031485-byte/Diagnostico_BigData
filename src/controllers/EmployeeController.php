<?php
/**
 * controllers/EmployeeController.php
 * Controlador de vistas (páginas HTML). No contiene SQL.
 */

class EmployeeController
{
    private Employee $model;

    public function __construct(Employee $model)
    {
        $this->model = $model;
    }

    public function dashboard(): void
    {
        $this->render('dashboard', ['title' => 'Dashboard']);
    }

    public function reports(): void
    {
        $this->render('reports/index', ['title' => 'Reportes']);
    }

    public function graphs(): void
    {
        $this->render('graphs/index', ['title' => 'Gráficos']);
    }

    public function employees(): void
    {
        $this->render('employees/search', ['title' => 'Consulta de Empleados']);
    }

    private function render(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . "/../views/{$view}.php";
        require __DIR__ . '/../views/layouts/footer.php';
    }
}