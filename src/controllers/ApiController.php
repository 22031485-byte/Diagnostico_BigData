<?php
/**
 * controllers/ApiController.php
 * Atiende peticiones AJAX (fetch) y devuelve JSON.
 * Endpoint real: api.php?action=NOMBRE_ACCION&...parametros
 */

class ApiController
{
    private Employee $model;

    public function __construct(Employee $model)
    {
        $this->model = $model;
    }

    public function handle(string $action, array $params): array
    {
        try {
            switch ($action) {
                case 'hiring_by_year_gender':
                    return ['data' => $this->model->hiringByYearAndGender()];

                case 'avg_salary_by_department':
                    return ['data' => $this->model->avgSalaryByDepartment()];

                case 'employee_count_by_department':
                    return ['data' => $this->model->employeeCountByDepartment()];

                case 'employees_by_age_range':
                    $fecha = $params['fecha_ref'] ?? date('Y-m-d');
                    if (!$this->isValidDate($fecha)) {
                        return ['error' => 'Fecha de referencia inválida. Usa formato AAAA-MM-DD.'];
                    }
                    return ['data' => $this->model->employeesByAgeRangeAndGender($fecha)];

                case 'top_salary_increase':
                    $top = isset($params['top']) ? (int)$params['top'] : 10;
                    return ['data' => $this->model->topSalaryIncrease($top)];

                case 'department_rotation':
                    return ['data' => $this->model->departmentRotationAnalysis()];

                case 'employee_search':
                    $term = trim($params['q'] ?? '');
                    if ($term === '') {
                        return ['data' => ['results' => [], 'has_more' => false]];
                    }
                    return ['data' => $this->model->search($term)];

                case 'employee_detail':
                    $id = (int)($params['emp_no'] ?? 0);
                    $detail = $this->model->detail($id);
                    if ($detail === null) {
                        return ['error' => 'Empleado no encontrado'];
                    }
                    return ['data' => $detail];

                default:
                    http_response_code(404);
                    return ['error' => "Acción no reconocida: {$action}"];
            }
        } catch (Throwable $e) {
            http_response_code(500);
            return ['error' => 'Error al procesar la solicitud: ' . $e->getMessage()];
        }
    }

    private function isValidDate(string $date): bool
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}