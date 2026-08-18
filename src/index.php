<?php
/**
 * Examen Diagnóstico - Big Data
 * Portal de Analítica y Reportes de Empleados
 */

// ==========================================
// 1. CONFIGURACIÓN DE CONEXIÓN A BASE DE DATOS
// ==========================================
$db_host = getenv('DB_HOST') ?: '127.0.0.1';
$db_port = getenv('DB_PORT') ?: '3306';
$db_name = getenv('DB_DATABASE') ?: 'employeesdb';
$db_user = getenv('DB_USERNAME') ?: 'bigdata';
$db_pass = getenv('DB_PASSWORD') ?: 'BigData#$';

try {
    $pdo = new PDO("mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    die("<div style='font-family: sans-serif; padding: 20px; background: #fee2e2; color: #991b1b; border-radius: 8px;'>
            <h3>Error de conexión a la base de datos</h3>
            <p>" . htmlspecialchars($e->getMessage()) . "</p>
         </div>");
}

// ==========================================
// 2. ENDPOINTS AJAX (API JSON)
// ==========================================
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    $action = $_GET['action'];

    try {
        switch ($action) {
            // R1: Contrataciones por año y género
            case 'get_hirings':
                $stmt = $pdo->query("
                    SELECT YEAR(hire_date) AS anio, gender AS genero, COUNT(*) AS total
                    FROM employees
                    GROUP BY YEAR(hire_date), gender
                    ORDER BY anio ASC, gender ASC
                ");
                echo json_encode($stmt->fetchAll());
                exit;

            // R2: Salario promedio por departamento
            case 'get_avg_salary':
                $stmt = $pdo->query("
                    SELECT d.dept_name AS departamento, ROUND(AVG(s.salary), 2) AS salario_promedio
                    FROM departments d
                    JOIN dept_emp de ON d.dept_no = de.dept_no AND de.to_date = '9999-01-01'
                    JOIN salaries s ON de.emp_no = s.emp_no AND s.to_date = '9999-01-01'
                    GROUP BY d.dept_no, d.dept_name
                    ORDER BY salario_promedio DESC
                ");
                echo json_encode($stmt->fetchAll());
                exit;

            // R3: Total empleados por departamento
            case 'get_dept_employees':
                $stmt = $pdo->query("
                    SELECT d.dept_name AS departamento, COUNT(de.emp_no) AS total_empleados
                    FROM departments d
                    JOIN dept_emp de ON d.dept_no = de.dept_no AND de.to_date = '9999-01-01'
                    GROUP BY d.dept_no, d.dept_name
                    ORDER BY total_empleados DESC
                ");
                echo json_encode($stmt->fetchAll());
                exit;

            // R4: Rangos de edad y género (Parametrizable)
            case 'get_age_ranges':
                $ref_year = isset($_GET['year']) ? intval($_GET['year']) : (int)date('Y');
                $stmt = $pdo->prepare("
                    SELECT 
                        CASE 
                            WHEN (:year - YEAR(birth_date)) < 30 THEN '<30'
                            WHEN (:year - YEAR(birth_date)) BETWEEN 30 AND 39 THEN '30-39'
                            WHEN (:year - YEAR(birth_date)) BETWEEN 40 AND 49 THEN '40-49'
                            WHEN (:year - YEAR(birth_date)) BETWEEN 50 AND 59 THEN '50-59'
                            ELSE '>=60'
                        END AS rango_edad,
                        gender AS genero,
                        COUNT(*) AS total
                    FROM employees
                    GROUP BY rango_edad, genero
                    ORDER BY FIELD(rango_edad, '<30', '30-39', '40-49', '50-59', '>=60'), genero
                ");
                $stmt->execute(['year' => $ref_year]);
                echo json_encode($stmt->fetchAll());
                exit;

            // R5: Top N incremento salarial
            case 'get_top_salary_growth':
                $limit = isset($_GET['limit']) ? max(1, min(100, intval($_GET['limit']))) : 10;
                $stmt = $pdo->prepare("
                    SELECT 
                        e.emp_no,
                        CONCAT(e.first_name, ' ', e.last_name) AS empleado,
                        MIN(s.salary) AS salario_min,
                        MAX(s.salary) AS salario_max,
                        ROUND(((MAX(s.salary) - MIN(s.salary)) / MIN(s.salary)) * 100, 2) AS pct_incremento,
                        TIMESTAMPDIFF(YEAR, MIN(s.from_date), MAX(s.to_date)) AS anios_carrera
                    FROM employees e
                    JOIN salaries s ON e.emp_no = s.emp_no
                    GROUP BY e.emp_no, e.first_name, e.last_name
                    HAVING salario_max > salario_min
                    ORDER BY pct_incremento DESC
                    LIMIT :lim
                ");
                $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
                $stmt->execute();
                echo json_encode($stmt->fetchAll());
                exit;

            // R6: Propuesta de consulta avanzada (Brecha salarial por depto y género)
            case 'get_custom_query':
                $stmt = $pdo->query("
                    SELECT 
                        d.dept_name AS departamento,
                        ROUND(AVG(CASE WHEN e.gender = 'M' THEN s.salary END), 2) AS salario_m,
                        ROUND(AVG(CASE WHEN e.gender = 'F' THEN s.salary END), 2) AS salario_f,
                        ROUND(AVG(CASE WHEN e.gender = 'M' THEN s.salary END) - AVG(CASE WHEN e.gender = 'F' THEN s.salary END), 2) AS brecha
                    FROM departments d
                    JOIN dept_emp de ON d.dept_no = de.dept_no AND de.to_date = '9999-01-01'
                    JOIN employees e ON de.emp_no = e.emp_no
                    JOIN salaries s ON e.emp_no = s.emp_no AND s.to_date = '9999-01-01'
                    GROUP BY d.dept_no, d.dept_name
                    ORDER BY d.dept_name
                ");
                echo json_encode($stmt->fetchAll());
                exit;

            // R7: Búsqueda y detalle de empleado
            case 'search_employee':
                $q = trim($_GET['q'] ?? '');
                if (empty($q)) {
                    echo json_encode([]);
                    exit;
                }
                if (is_numeric($q)) {
                    $stmt = $pdo->prepare("SELECT emp_no, first_name, last_name, gender, birth_date, hire_date FROM employees WHERE emp_no = :id LIMIT 10");
                    $stmt->execute(['id' => $q]);
                } else {
                    $stmt = $pdo->prepare("SELECT emp_no, first_name, last_name, gender, birth_date, hire_date FROM employees WHERE first_name LIKE :q1 OR last_name LIKE :q2 LIMIT 10");
                    $stmt->execute(['q1' => "%$q%", 'q2' => "%$q%"]);
                }
                echo json_encode($stmt->fetchAll());
                exit;

            case 'get_employee_details':
                $emp_no = intval($_GET['emp_no'] ?? 0);
                
                // Info básica
                $st1 = $pdo->prepare("SELECT * FROM employees WHERE emp_no = ?");
                $st1->execute([$emp_no]);
                $emp = $st1->fetch();

                if (!$emp) {
                    echo json_encode(['error' => 'Empleado no encontrado']);
                    exit;
                }

                // Departamentos
                $st2 = $pdo->prepare("
                    SELECT d.dept_name, de.from_date, de.to_date 
                    FROM dept_emp de 
                    JOIN departments d ON de.dept_no = d.dept_no 
                    WHERE de.emp_no = ? ORDER BY de.from_date DESC
                ");
                $st2->execute([$emp_no]);
                $deptos = $st2->fetchAll();

                // Puestos
                $st3 = $pdo->prepare("SELECT title, from_date, to_date FROM titles WHERE emp_no = ? ORDER BY from_date DESC");
                $st3->execute([$emp_no]);
                $titles = $st3->fetchAll();

                // Salarios
                $st4 = $pdo->prepare("SELECT salary, from_date, to_date FROM salaries WHERE emp_no = ? ORDER BY from_date DESC");
                $st4->execute([$emp_no]);
                $salaries = $st4->fetchAll();

                echo json_encode([
                    'employee' => $emp,
                    'departments' => $deptos,
                    'titles' => $titles,
                    'salaries' => $salaries
                ]);
                exit;
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen Diagnóstico - Big Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
        .sidebar { min-height: 100vh; background-color: #0f172a; color: #fff; }
        .sidebar .nav-link { color: #94a3b8; border-radius: 6px; margin: 4px 0; font-weight: 500; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #1e293b; }
        .sidebar .nav-link.active { border-left: 4px solid #38bdf8; }
        .card { border: none; border-radius: 10px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .chart-container { position: relative; min-height: 380px; width: 100%; }
        .table-responsive { max-height: 450px; overflow-y: auto; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Barra Lateral -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar py-3 px-3">
            <div class="d-flex align-items-center mb-4 text-white">
                <i class="bi bi-database-fill-gear fs-3 me-2 text-info"></i>
                <span class="fs-5 fw-bold">Big Data Analytics</span>
            </div>
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                <button class="nav-link active text-start" data-bs-toggle="pill" data-bs-target="#tab-r1"><i class="bi bi-graph-up me-2"></i>1. Contrataciones</button>
                <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#tab-r2"><i class="bi bi-cash-stack me-2"></i>2. Salario Promedio</button>
                <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#tab-r3"><i class="bi bi-people-fill me-2"></i>3. Empleados / Depto</button>
                <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#tab-r4"><i class="bi bi-pie-chart-fill me-2"></i>4. Demografía y Edad</button>
                <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#tab-r5"><i class="bi bi-trophy-fill me-2"></i>5. Top Crecimiento</button>
                <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#tab-r6"><i class="bi bi-lightbulb-fill me-2"></i>6. Propuesta Avanzada</button>
                <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#tab-r7"><i class="bi bi-person-bounding-box me-2"></i>7. Búsqueda Empleado</button>
            </div>
        </nav>

        <!-- Contenido Principal -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="tab-content" id="v-pills-tabContent">
                
                <!-- R1: Contrataciones por año y género -->
                <div class="tab-pane fade show active" id="tab-r1">
                    <h3 class="fw-bold mb-3">1. Evolución de Contrataciones por Año y Género</h3>
                    <div class="row g-3">
                        <div class="col-lg-7"><div class="card p-3"><div class="chart-container"><canvas id="chartR1"></canvas></div></div></div>
                        <div class="col-lg-5"><div class="card p-3"><div class="table-responsive"><table class="table table-hover table-sm"><thead><tr><th>Año</th><th>Género</th><th>Total</th></tr></thead><tbody id="tableR1"></tbody></table></div></div></div>
                    </div>
                </div>

                <!-- R2: Salarios Promedio por Depto -->
                <div class="tab-pane fade" id="tab-r2">
                    <h3 class="fw-bold mb-3">2. Salario Promedio por Departamento</h3>
                    <div class="row g-3">
                        <div class="col-lg-7"><div class="card p-3"><div class="chart-container"><canvas id="chartR2"></canvas></div></div></div>
                        <div class="col-lg-5"><div class="card p-3"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Departamento</th><th>Salario Promedio</th></tr></thead><tbody id="tableR2"></tbody></table></div></div></div>
                    </div>
                </div>

                <!-- R3: Empleados por Depto -->
                <div class="tab-pane fade" id="tab-r3">
                    <h3 class="fw-bold mb-3">3. Total de Empleados por Departamento</h3>
                    <div class="row g-3">
                        <div class="col-lg-7"><div class="card p-3"><div class="chart-container"><canvas id="chartR3"></canvas></div></div></div>
                        <div class="col-lg-5"><div class="card p-3"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Departamento</th><th>Total Empleados</th></tr></thead><tbody id="tableR3"></tbody></table></div></div></div>
                    </div>
                </div>

                <!-- R4: Demografía y Rango de Edad -->
                <div class="tab-pane fade" id="tab-r4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="fw-bold m-0">4. Distribución por Rango de Edad y Género</h3>
                        <div class="d-flex align-items-center gap-2">
                            <label class="form-label m-0 fw-semibold">Año Comparativo:</label>
                            <input type="number" id="yearR4" class="form-control form-control-sm" style="width: 100px;" value="<?= date('Y') ?>">
                            <button class="btn btn-sm btn-primary" onclick="loadR4()"><i class="bi bi-arrow-clockwise"></i></button>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-lg-7"><div class="card p-3"><div class="chart-container"><canvas id="chartR4"></canvas></div></div></div>
                        <div class="col-lg-5"><div class="card p-3"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Rango Edad</th><th>Género</th><th>Total</th></tr></thead><tbody id="tableR4"></tbody></table></div></div></div>
                    </div>
                </div>

                <!-- R5: Top Crecimiento Salarial -->
                <div class="tab-pane fade" id="tab-r5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="fw-bold m-0">5. Top Empleados con Mayor Crecimiento Salarial</h3>
                        <div class="d-flex align-items-center gap-2">
                            <label class="form-label m-0 fw-semibold">Top N:</label>
                            <input type="number" id="limitR5" class="form-control form-control-sm" style="width: 80px;" value="10" min="1" max="100">
                            <button class="btn btn-sm btn-primary" onclick="loadR5()"><i class="bi bi-arrow-clockwise"></i></button>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-lg-7"><div class="card p-3"><div class="chart-container"><canvas id="chartR5"></canvas></div></div></div>
                        <div class="col-lg-5"><div class="card p-3"><div class="table-responsive"><table class="table table-hover table-sm"><thead><tr><th>Empleado</th><th>Min</th><th>Max</th><th>% Inc.</th><th>Años</th></tr></thead><tbody id="tableR5"></tbody></table></div></div></div>
                    </div>
                </div>

                <!-- R6: Propuesta de Consulta Avanzada -->
                <div class="tab-pane fade" id="tab-r6">
                    <h3 class="fw-bold mb-3">6. Análisis de Brecha Salarial de Género por Departamento</h3>
                    <p class="text-muted">Consulta de propuesta propia: Evalúa el salario promedio entre hombres y mujeres y calcula la brecha resultante.</p>
                    <div class="row g-3">
                        <div class="col-lg-7"><div class="card p-3"><div class="chart-container"><canvas id="chartR6"></canvas></div></div></div>
                        <div class="col-lg-5"><div class="card p-3"><div class="table-responsive"><table class="table table-hover table-sm"><thead><tr><th>Departamento</th><th>Prom. M</th><th>Prom. F</th><th>Brecha</th></tr></thead><tbody id="tableR6"></tbody></table></div></div></div>
                    </div>
                </div>

                <!-- R7: Búsqueda y Detalle 360 -->
                <div class="tab-pane fade" id="tab-r7">
                    <h3 class="fw-bold mb-3">7. Expediente y Búsqueda de Empleados</h3>
                    <div class="card p-3 mb-3">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar por ID de empleado o nombre (ej. 10001, Georgi)...">
                            <button class="btn btn-primary" onclick="searchEmployees()"><i class="bi bi-search"></i> Buscar</button>
                        </div>
                        <div id="searchResults" class="list-group mt-2"></div>
                    </div>

                    <div id="empDetailCard" class="card p-4 d-none">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="fw-bold text-primary m-0" id="detName"></h4>
                            <span class="badge bg-secondary fs-6" id="detEmpNo"></span>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Género:</strong> <span id="detGender"></span></div>
                            <div class="col-md-3"><strong>F. Nacimiento:</strong> <span id="detBirth"></span></div>
                            <div class="col-md-3"><strong>F. Contratación:</strong> <span id="detHire"></span></div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4"><h6>Departamentos Asignados</h6><ul class="list-group list-group-flush" id="detDeptList"></ul></div>
                            <div class="col-md-4"><h6>Historial de Puestos</h6><ul class="list-group list-group-flush" id="detTitleList"></ul></div>
                            <div class="col-md-4"><h6>Historial de Salarios</h6><ul class="list-group list-group-flush" id="detSalaryList" style="max-height: 200px; overflow-y: auto;"></ul></div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let charts = {};

function renderChart(id, type, data, options = {}) {
    if (charts[id]) charts[id].destroy();
    const ctx = document.getElementById(id).getContext('2d');
    charts[id] = new Chart(ctx, { type, data, options: { responsive: true, maintainAspectRatio: false, ...options } });
}

// 1. Cargar R1
async function loadR1() {
    const res = await fetch('index.php?action=get_hirings');
    const data = await res.json();
    const table = document.getElementById('tableR1');
    table.innerHTML = data.map(r => `<tr><td>${r.anio}</td><td>${r.genero === 'M' ? 'Hombre' : 'Mujer'}</td><td>${parseInt(r.total).toLocaleString()}</td></tr>`).join('');

    const years = [...new Set(data.map(r => r.anio))];
    const dataM = years.map(y => (data.find(r => r.anio === y && r.genero === 'M') || {}).total || 0);
    const dataF = years.map(y => (data.find(r => r.anio === y && r.genero === 'F') || {}).total || 0);

    renderChart('chartR1', 'bar', {
        labels: years,
        datasets: [
            { label: 'Hombres', data: dataM, backgroundColor: '#3b82f6' },
            { label: 'Mujeres', data: dataF, backgroundColor: '#ec4899' }
        ]
    }, { scales: { x: { stacked: true }, y: { stacked: true } } });
}

// 2. Cargar R2
async function loadR2() {
    const res = await fetch('index.php?action=get_avg_salary');
    const data = await res.json();
    document.getElementById('tableR2').innerHTML = data.map(r => `<tr><td>${r.departamento}</td><td>$${parseFloat(r.salario_promedio).toLocaleString()}</td></tr>`).join('');

    renderChart('chartR2', 'bar', {
        labels: data.map(r => r.departamento),
        datasets: [{ label: 'Salario Promedio ($)', data: data.map(r => r.salario_promedio), backgroundColor: '#10b981' }]
    }, { indexAxis: 'y' });
}

// 3. Cargar R3
async function loadR3() {
    const res = await fetch('index.php?action=get_dept_employees');
    const data = await res.json();
    document.getElementById('tableR3').innerHTML = data.map(r => `<tr><td>${r.departamento}</td><td>${parseInt(r.total_empleados).toLocaleString()}</td></tr>`).join('');

    renderChart('chartR3', 'bar', {
        labels: data.map(r => r.departamento),
        datasets: [{ label: 'Total Empleados', data: data.map(r => r.total_empleados), backgroundColor: '#6366f1' }]
    });
}

// 4. Cargar R4
async function loadR4() {
    const year = document.getElementById('yearR4').value;
    const res = await fetch(`index.php?action=get_age_ranges&year=${year}`);
    const data = await res.json();
    document.getElementById('tableR4').innerHTML = data.map(r => `<tr><td>${r.rango_edad}</td><td>${r.genero === 'M' ? 'Hombre' : 'Mujer'}</td><td>${parseInt(r.total).toLocaleString()}</td></tr>`).join('');

    const ranges = ['<30', '30-39', '40-49', '50-59', '>=60'];
    const dataM = ranges.map(rng => (data.find(r => r.rango_edad === rng && r.genero === 'M') || {}).total || 0);
    const dataF = ranges.map(rng => (data.find(r => r.rango_edad === rng && r.genero === 'F') || {}).total || 0);

    renderChart('chartR4', 'bar', {
        labels: ranges,
        datasets: [
            { label: 'Hombres', data: dataM, backgroundColor: '#0284c7' },
            { label: 'Mujeres', data: dataF, backgroundColor: '#f43f5e' }
        ]
    });
}

// 5. Cargar R5
async function loadR5() {
    const limit = document.getElementById('limitR5').value;
    const res = await fetch(`index.php?action=get_top_salary_growth&limit=${limit}`);
    const data = await res.json();
    document.getElementById('tableR5').innerHTML = data.map(r => `<tr><td>${r.empleado}</td><td>$${r.salario_min}</td><td>$${r.salario_max}</td><td><span class="badge bg-success">+${r.pct_incremento}%</span></td><td>${r.anios_carrera}</td></tr>`).join('');

    renderChart('chartR5', 'bar', {
        labels: data.map(r => r.empleado),
        datasets: [{ label: '% Crecimiento Salarial', data: data.map(r => r.pct_incremento), backgroundColor: '#f59e0b' }]
    }, { indexAxis: 'y' });
}

// 6. Cargar R6
async function loadR6() {
    const res = await fetch('index.php?action=get_custom_query');
    const data = await res.json();
    document.getElementById('tableR6').innerHTML = data.map(r => `<tr><td>${r.departamento}</td><td>$${parseFloat(r.salario_m).toLocaleString()}</td><td>$${parseFloat(r.salario_f).toLocaleString()}</td><td>$${parseFloat(r.brecha).toLocaleString()}</td></tr>`).join('');

    renderChart('chartR6', 'bar', {
        labels: data.map(r => r.departamento),
        datasets: [
            { label: 'Hombres ($)', data: data.map(r => r.salario_m), backgroundColor: '#3b82f6' },
            { label: 'Mujeres ($)', data: data.map(r => r.salario_f), backgroundColor: '#ec4899' }
        ]
    });
}

// 7. Búsqueda y Detalle de Empleados
async function searchEmployees() {
    const q = document.getElementById('searchInput').value;
    if (!q) return;
    const res = await fetch(`index.php?action=search_employee&q=${encodeURIComponent(q)}`);
    const list = await res.json();
    const cont = document.getElementById('searchResults');
    cont.innerHTML = list.map(e => `<button type="button" class="list-group-item list-group-item-action" onclick="viewEmployee(${e.emp_no})"><strong>#${e.emp_no}</strong> - ${e.first_name} ${e.last_name} (${e.gender})</button>`).join('');
}

async function viewEmployee(empNo) {
    const res = await fetch(`index.php?action=get_employee_details&emp_no=${empNo}`);
    const data = await res.json();
    if (data.error) return alert(data.error);

    document.getElementById('empDetailCard').classList.remove('d-none');
    document.getElementById('detName').textContent = `${data.employee.first_name} ${data.employee.last_name}`;
    document.getElementById('detEmpNo').textContent = `ID: #${data.employee.emp_no}`;
    document.getElementById('detGender').textContent = data.employee.gender === 'M' ? 'Hombre' : 'Mujer';
    document.getElementById('detBirth').textContent = data.employee.birth_date;
    document.getElementById('detHire').textContent = data.employee.hire_date;

    document.getElementById('detDeptList').innerHTML = data.departments.map(d => `<li class="list-group-item py-1"><strong>${d.dept_name}</strong><br><small class="text-muted">${d.from_date} al ${d.to_date}</small></li>`).join('');
    document.getElementById('detTitleList').innerHTML = data.titles.map(t => `<li class="list-group-item py-1"><strong>${t.title}</strong><br><small class="text-muted">${t.from_date} al ${t.to_date}</small></li>`).join('');
    document.getElementById('detSalaryList').innerHTML = data.salaries.map(s => `<li class="list-group-item py-1 d-flex justify-content-between"><span>$${parseFloat(s.salary).toLocaleString()}</span><small class="text-muted">${s.from_date}</small></li>`).join('');
}

// Inicializar vistas al cambiar de pestaña
document.addEventListener('DOMContentLoaded', () => {
    loadR1();
    document.querySelectorAll('button[data-bs-toggle="pill"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', (e) => {
            const target = e.target.getAttribute('data-bs-target');
            if (target === '#tab-r1') loadR1();
            if (target === '#tab-r2') loadR2();
            if (target === '#tab-r3') loadR3();
            if (target === '#tab-r4') loadR4();
            if (target === '#tab-r5') loadR5();
            if (target === '#tab-r6') loadR6();
        });
    });
});
</script>
</body>
</html>