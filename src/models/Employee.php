<?php
/**
 * models/Employee.php
 * Capa de acceso a datos (Model). Todas las consultas SQL viven aquí.
 *
 * Esquema (base "employees" estándar de MySQL):
 *   employees(emp_no, birth_date, first_name, last_name, gender, hire_date)
 *   departments(dept_no, dept_name)
 *   dept_emp(emp_no, dept_no, from_date, to_date)
 *   dept_manager(dept_no, emp_no, from_date, to_date)
 *   titles(emp_no, title, from_date, to_date)
 *   salaries(emp_no, salary, from_date, to_date)
 *
 * Convención: to_date = '9999-01-01' significa "registro vigente".
 */

class Employee
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** 1. Evolución de contrataciones por año y género */
    public function hiringByYearAndGender(): array
    {
        $sql = "SELECT YEAR(hire_date) AS anio,
                       gender,
                       COUNT(*) AS total_contrataciones
                FROM employees
                GROUP BY anio, gender
                ORDER BY anio ASC, gender ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    /** 2. Salario promedio por departamento (empleados activos en el depto) */
    public function avgSalaryByDepartment(): array
    {
        $sql = "SELECT d.dept_name AS departamento,
                       ROUND(AVG(s.salary), 2) AS salario_promedio
                FROM departments d
                JOIN dept_emp de ON de.dept_no = d.dept_no AND de.to_date = '9999-01-01'
                JOIN salaries  s  ON s.emp_no  = de.emp_no AND s.to_date  = '9999-01-01'
                GROUP BY d.dept_name
                ORDER BY salario_promedio DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    /** 3. Número de empleados por departamento (activos) */
    public function employeeCountByDepartment(): array
    {
        $sql = "SELECT d.dept_name AS departamento,
                       COUNT(*) AS total_empleados
                FROM departments d
                JOIN dept_emp de ON de.dept_no = d.dept_no AND de.to_date = '9999-01-01'
                GROUP BY d.dept_name
                ORDER BY total_empleados DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    /** 4. Empleados por rango de edad y género, contra una fecha de referencia configurable */
    public function employeesByAgeRangeAndGender(string $referenceDate): array
    {
        $sql = "SELECT
                    CASE
                        WHEN edad < 30 THEN '<30'
                        WHEN edad BETWEEN 30 AND 39 THEN '30-39'
                        WHEN edad BETWEEN 40 AND 49 THEN '40-49'
                        WHEN edad BETWEEN 50 AND 59 THEN '50-59'
                        ELSE '>=60'
                    END AS rango_edad,
                    gender,
                    COUNT(*) AS total_empleados
                FROM (
                    SELECT emp_no, gender,
                           TIMESTAMPDIFF(YEAR, birth_date, :fecha_ref) AS edad
                    FROM employees
                ) t
                GROUP BY rango_edad, gender
                ORDER BY FIELD(rango_edad, '<30','30-39','40-49','50-59','>=60'), gender";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['fecha_ref' => $referenceDate]);
        return $stmt->fetchAll();
    }

    /** 5. Top N empleados con mayor incremento salarial en su carrera */
    public function topSalaryIncrease(int $topN = 10): array
    {
        $topN = max(1, min($topN, 100));
        $sql = "SELECT
                    e.emp_no,
                    CONCAT(e.first_name, ' ', e.last_name) AS empleado,
                    MIN(s.salary) AS salario_minimo,
                    MAX(s.salary) AS salario_maximo,
                    ROUND(
                        (MAX(s.salary) - MIN(s.salary)) / NULLIF(MIN(s.salary), 0) * 100, 2
                    ) AS pct_incremento,
                    TIMESTAMPDIFF(
                        YEAR, e.hire_date,
                        IF(MAX(s.to_date) = '9999-01-01', CURDATE(), MAX(s.to_date))
                    ) AS anios_carrera
                FROM employees e
                JOIN salaries s ON s.emp_no = e.emp_no
                GROUP BY e.emp_no, e.first_name, e.last_name, e.hire_date
                HAVING COUNT(s.salary) > 1
                ORDER BY pct_incremento DESC
                LIMIT $topN";
        return $this->pdo->query($sql)->fetchAll();
    }

    /**
     * 6. Consulta avanzada propia: Rotación interna y antigüedad promedio por departamento.
     */
    public function departmentRotationAnalysis(): array
    {
        $sql = "WITH emp_dept_count AS (
                    SELECT emp_no, COUNT(DISTINCT dept_no) AS num_deptos
                    FROM dept_emp
                    GROUP BY emp_no
                )
                SELECT
                    d.dept_name AS departamento,
                    COUNT(DISTINCT de.emp_no) AS total_empleados,
                    SUM(CASE WHEN edc.num_deptos > 1 THEN 1 ELSE 0 END) AS empleados_con_rotacion,
                    ROUND(
                        SUM(CASE WHEN edc.num_deptos > 1 THEN 1 ELSE 0 END)
                        / COUNT(DISTINCT de.emp_no) * 100, 2
                    ) AS pct_rotacion,
                    ROUND(
                        AVG(DATEDIFF(IF(de.to_date = '9999-01-01', CURDATE(), de.to_date), de.from_date)) / 365.25,
                        2
                    ) AS antiguedad_promedio_anios
                FROM dept_emp de
                JOIN departments d ON d.dept_no = de.dept_no
                JOIN emp_dept_count edc ON edc.emp_no = de.emp_no
                GROUP BY d.dept_name
                ORDER BY pct_rotacion DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    /** Búsqueda de empleados por número (parcial), nombre o apellido */
    public function search(string $term): array
    {
        $like = "%{$term}%";
        $sql = "SELECT emp_no, first_name, last_name, gender, hire_date
                FROM employees
                WHERE CAST(emp_no AS CHAR) LIKE :like_no
                OR first_name LIKE :like1
                OR last_name LIKE :like2
                OR CONCAT(first_name, ' ', last_name) LIKE :like3
                ORDER BY last_name, first_name
                LIMIT 51";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'like_no' => $like,
            'like1'   => $like,
            'like2'   => $like,
            'like3'   => $like,
        ]);
        $rows = $stmt->fetchAll();

        $hasMore = count($rows) > 50;
        if ($hasMore) {
            array_pop($rows);
        }

        return ['results' => $rows, 'has_more' => $hasMore];
    }

    /** Ficha detallada de un empleado: datos generales + histórico completo */
    public function detail(int $empNo): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT emp_no, first_name, last_name, gender, birth_date, hire_date
             FROM employees WHERE emp_no = :id"
        );
        $stmt->execute(['id' => $empNo]);
        $general = $stmt->fetch();
        if (!$general) {
            return null;
        }

        $stmt = $this->pdo->prepare(
            "SELECT s.salary, s.from_date, s.to_date
             FROM salaries s WHERE s.emp_no = :id
             ORDER BY s.from_date DESC"
        );
        $stmt->execute(['id' => $empNo]);
        $salaries = $stmt->fetchAll();

        $stmt = $this->pdo->prepare(
            "SELECT t.title, t.from_date, t.to_date
             FROM titles t WHERE t.emp_no = :id
             ORDER BY t.from_date DESC"
        );
        $stmt->execute(['id' => $empNo]);
        $titles = $stmt->fetchAll();

        $stmt = $this->pdo->prepare(
            "SELECT d.dept_name, de.from_date, de.to_date
             FROM dept_emp de
             JOIN departments d ON d.dept_no = de.dept_no
             WHERE de.emp_no = :id
             ORDER BY de.from_date DESC"
        );
        $stmt->execute(['id' => $empNo]);
        $departments = $stmt->fetchAll();

        return [
            'general'     => $general,
            'salaries'    => $salaries,
            'titles'      => $titles,
            'departments' => $departments,
        ];
    }
}