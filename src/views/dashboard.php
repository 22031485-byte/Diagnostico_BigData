<section class="page-header">
    <h1>Dashboard</h1>
    <p>Vista general de la fuerza laboral. Consulta los reportes y gráficos detallados desde el menú superior.</p>
</section>

<section class="kpi-grid" id="kpiGrid">
    <div class="kpi-card"><span class="kpi-label">Departamentos</span><span class="kpi-value" id="kpiDepts">—</span></div>
    <div class="kpi-card"><span class="kpi-label">Empleados activos (total)</span><span class="kpi-value" id="kpiEmployees">—</span></div>
    <div class="kpi-card"><span class="kpi-label">Salario promedio más alto</span><span class="kpi-value" id="kpiTopSalaryDept">—</span></div>
    <div class="kpi-card"><span class="kpi-label">Depto. con más rotación</span><span class="kpi-value" id="kpiRotation">—</span></div>
</section>

<section class="quick-links">
    <a class="quick-card" href="index.php?page=reports">
        <h3>📊 Reportes</h3>
        <p>Tablas detalladas: contrataciones, salarios, plantilla, edades, top salarial y análisis de rotación.</p>
    </a>
    <a class="quick-card" href="index.php?page=graphs">
        <h3>📈 Gráficos</h3>
        <p>Visualizaciones interactivas con Chart.js para cada uno de los reportes.</p>
    </a>
    <a class="quick-card" href="index.php?page=employees">
        <h3>🔍 Consulta de Empleados</h3>
        <p>Busca a un empleado y revisa su ficha completa: salarios, puestos y departamentos históricos.</p>
    </a>
</section>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    try {
        const [deptCount, avgSalary, rotation] = await Promise.all([
            fetch('api.php?action=employee_count_by_department').then(r => r.json()),
            fetch('api.php?action=avg_salary_by_department').then(r => r.json()),
            fetch('api.php?action=department_rotation').then(r => r.json()),
        ]);

        const depts = deptCount.data || [];
        document.getElementById('kpiDepts').textContent = depts.length;
        document.getElementById('kpiEmployees').textContent =
            depts.reduce((sum, d) => sum + Number(d.total_empleados), 0).toLocaleString('es-MX');

        const salarios = avgSalary.data || [];
        if (salarios.length) {
            document.getElementById('kpiTopSalaryDept').textContent =
                `${salarios[0].departamento} ($${Number(salarios[0].salario_promedio).toLocaleString('es-MX')})`;
        }

        const rot = rotation.data || [];
        if (rot.length) {
            document.getElementById('kpiRotation').textContent =
                `${rot[0].departamento} (${rot[0].pct_rotacion}%)`;
        }
    } catch (err) {
        console.error('Error cargando KPIs del dashboard:', err);
    }
});
</script>