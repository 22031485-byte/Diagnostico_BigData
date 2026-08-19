<section class="page-header">
    <h1>Gráficos</h1>
    <p>Visualización estadística de cada reporte.</p>
</section>

<div class="tabs" id="graphTabs">
    <button class="tab-btn active" data-graph="1">1. Contrataciones</button>
    <button class="tab-btn" data-graph="2">2. Salario por depto.</button>
    <button class="tab-btn" data-graph="3">3. Empleados por depto.</button>
    <button class="tab-btn" data-graph="4">4. Pirámide de edad</button>
    <button class="tab-btn" data-graph="5">5. Top incremento salarial</button>
    <button class="tab-btn" data-graph="6">6. Rotación interna</button>
</div>

<div class="graph-panel" id="gpanel-1">
    <h2>Evolución de contrataciones por año y género</h2>
    <div class="chart-box"><canvas id="chart-1"></canvas></div>
</div>

<div class="graph-panel hidden" id="gpanel-2">
    <h2>Salario promedio por departamento</h2>
    <div class="chart-box"><canvas id="chart-2"></canvas></div>
</div>

<div class="graph-panel hidden" id="gpanel-3">
    <h2>Número de empleados por departamento</h2>
    <div class="chart-box"><canvas id="chart-3"></canvas></div>
</div>

<div class="graph-panel hidden" id="gpanel-4">
    <h2>Pirámide poblacional (edad y género)</h2>
    <div class="filter-row">
        <label for="fechaRefG4">Fecha de referencia:</label>
        <input type="date" id="fechaRefG4">
        <button id="btnFiltrarG4" class="btn-primary">Aplicar</button>
    </div>
    <div class="chart-box"><canvas id="chart-4"></canvas></div>
</div>

<div class="graph-panel hidden" id="gpanel-5">
    <h2>Top empleados con mayor incremento salarial</h2>
    <div class="filter-row">
        <label for="topNG5">Cantidad a mostrar:</label>
        <input type="number" id="topNG5" value="10" min="1" max="100">
        <button id="btnFiltrarG5" class="btn-primary">Aplicar</button>
    </div>
    <div class="chart-box"><canvas id="chart-5"></canvas></div>
</div>

<div class="graph-panel hidden" id="gpanel-6">
    <h2>% de rotación interna por departamento</h2>
    <div class="chart-box"><canvas id="chart-6"></canvas></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('fechaRefG4').valueAsDate = new Date();
    initGraphTabs();
    loadGraph(1);
});
</script>