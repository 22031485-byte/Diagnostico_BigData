<section class="page-header">
    <h1>Reportes</h1>
    <p>Selecciona un reporte del menú para ver la tabla de datos correspondiente.</p>
</section>

<div class="tabs" id="reportTabs">
    <button class="tab-btn active" data-report="1">1. Contrataciones por año/género</button>
    <button class="tab-btn" data-report="2">2. Salario promedio por depto.</button>
    <button class="tab-btn" data-report="3">3. Empleados por depto.</button>
    <button class="tab-btn" data-report="4">4. Edad y género</button>
    <button class="tab-btn" data-report="5">5. Top incremento salarial</button>
    <button class="tab-btn" data-report="6">6. Rotación interna (avanzado)</button>
</div>

<div class="report-panel" id="panel-1">
    <h2>Evolución de contrataciones por año y género</h2>
    <p class="report-desc">Identifica tendencias de contratación y evolución de la diversidad de género.</p>
    <div class="table-wrap"><table id="table-1"><thead><tr><th>Año</th><th>Género</th><th>Total contrataciones</th></tr></thead><tbody></tbody></table></div>
</div>

<div class="report-panel hidden" id="panel-2">
    <h2>Salario promedio por departamento</h2>
    <p class="report-desc">Para decisiones de presupuesto y compensación.</p>
    <div class="table-wrap"><table id="table-2"><thead><tr><th>Departamento</th><th>Salario promedio</th></tr></thead><tbody></tbody></table></div>
</div>

<div class="report-panel hidden" id="panel-3">
    <h2>Número de empleados por departamento</h2>
    <p class="report-desc">Dimensiona cada departamento para planificación de recursos.</p>
    <div class="table-wrap"><table id="table-3"><thead><tr><th>Departamento</th><th>Total empleados</th></tr></thead><tbody></tbody></table></div>
</div>

<div class="report-panel hidden" id="panel-4">
    <h2>Empleados por rango de edad y género</h2>
    <p class="report-desc">Distribución demográfica para planes de sucesión y beneficios.</p>
    <div class="filter-row">
        <label for="fechaRef4">Fecha de referencia:</label>
        <input type="date" id="fechaRef4">
        <button id="btnFiltrar4" class="btn-primary">Aplicar</button>
    </div>
    <div class="table-wrap"><table id="table-4"><thead><tr><th>Rango de edad</th><th>Género</th><th>Total empleados</th></tr></thead><tbody></tbody></table></div>
</div>

<div class="report-panel hidden" id="panel-5">
    <h2>Top empleados con mayor incremento salarial en su carrera</h2>
    <p class="report-desc">Reconocimiento y evaluación de políticas salariales.</p>
    <div class="filter-row">
        <label for="topN5">Cantidad a mostrar:</label>
        <input type="number" id="topN5" value="10" min="1" max="100">
        <button id="btnFiltrar5" class="btn-primary">Aplicar</button>
    </div>
    <div class="table-wrap"><table id="table-5"><thead><tr><th>Empleado</th><th>Salario mínimo</th><th>Salario máximo</th><th>% Incremento</th><th>Años de carrera</th></tr></thead><tbody></tbody></table></div>
</div>

<div class="report-panel hidden" id="panel-6">
    <h2>Rotación interna y antigüedad promedio por departamento</h2>
    <p class="report-desc">Consulta propia (nivel intermedio-avanzado, usa CTE): mide qué porcentaje de empleados de cada
    departamento ha pasado por más de un departamento a lo largo de su carrera, y la antigüedad promedio en el puesto actual.
    Útil para detectar departamentos con fuga de talento o alta movilidad interna.</p>
    <div class="table-wrap"><table id="table-6"><thead><tr><th>Departamento</th><th>Total empleados</th><th>Con rotación</th><th>% Rotación</th><th>Antigüedad prom. (años)</th></tr></thead><tbody></tbody></table></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('fechaRef4').valueAsDate = new Date();
    initReportTabs();
    loadReport(1);
});
</script>