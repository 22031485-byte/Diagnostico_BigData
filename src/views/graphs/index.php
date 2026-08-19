<style>

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600&display=swap');

.grp-page {
    --gp-cyan: #0EA5B7;
    --gp-cyan-dark: #0B7285;
    --gp-navy: #123C5A;
    --gp-navy-deep: #0C2A40;
    --gp-text: #12222B;
    --gp-text-dim: #5D7480;
    --gp-line: #E6EEF0;
    --gp-radius: 16px;
    font-family: 'Inter', system-ui, sans-serif;
    color: var(--gp-text);
}
.grp-page h1, .grp-page h2 { font-family: 'Poppins', sans-serif; }


.grp-page .page-header {
    display: block;
    border-bottom: none;
    padding-bottom: 0;
    margin-bottom: 1.8rem;
}
.grp-page .page-header h1 {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--gp-navy-deep);
    margin: 0 0 0.35rem;
}
.grp-page .page-header p {
    color: var(--gp-text-dim);
    margin: 0;
    font-size: 0.95rem;
}


.grp-page .tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    background: #F2F9FA;
    border: 1px solid var(--gp-line);
    border-radius: 999px;
    padding: 0.4rem;
    margin-bottom: 1.6rem;
}
.grp-page .tab-btn {
    background: transparent;
    border: none;
    color: var(--gp-text-dim);
    padding: 0.6rem 1.1rem;
    border-radius: 999px;
    cursor: pointer;
    font-family: 'Inter', sans-serif;
    font-size: 0.83rem;
    font-weight: 600;
    transition: all 0.18s;
    white-space: nowrap;
}
.grp-page .tab-btn:hover { color: var(--gp-navy-deep); background: rgba(14,165,183,0.08); }
.grp-page .tab-btn.active {
    background: linear-gradient(135deg, var(--gp-cyan), var(--gp-navy));
    color: #fff;
    box-shadow: 0 4px 14px -4px rgba(11,114,133,0.45);
}


.grp-page .graph-panel {
    background: #fff;
    border: 1px solid var(--gp-line);
    border-radius: var(--gp-radius);
    padding: 1.9rem 2rem 2.2rem;
    box-shadow: 0 6px 24px -14px rgba(18,60,90,0.18);
}
.grp-page .graph-panel h2 {
    margin: 0 0 1.2rem;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--gp-navy-deep);
}
.grp-page .hidden { display: none !important; }


.grp-page .filter-row {
    display: flex;
    align-items: center;
    gap: 0.7rem;
    flex-wrap: wrap;
    background: #F2F9FA;
    border: 1px solid var(--gp-line);
    border-radius: 12px;
    padding: 0.8rem 1rem;
    margin-bottom: 1.5rem;
}
.grp-page .filter-row label {
    color: var(--gp-navy-deep);
    font-size: 0.85rem;
    font-weight: 600;
}
.grp-page .filter-row input {
    background: #fff;
    border: 1px solid var(--gp-line);
    color: var(--gp-text);
    padding: 0.5rem 0.8rem;
    border-radius: 8px;
    font-size: 0.9rem;
}
.grp-page .filter-row input:focus {
    outline: none;
    border-color: var(--gp-cyan);
    box-shadow: 0 0 0 3px rgba(14,165,183,0.15);
}
.grp-page .btn-primary {
    background: linear-gradient(135deg, var(--gp-cyan), var(--gp-cyan-dark));
    color: #fff;
    border: none;
    padding: 0.55rem 1.3rem;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.85rem;
    transition: filter 0.15s, transform 0.15s;
}
.grp-page .btn-primary:hover { filter: brightness(1.08); transform: translateY(-1px); }

.grp-page .chart-box {
    max-width: 900px;
    margin: 0 auto;
    background: #F8FBFC;
    border: 1px solid var(--gp-line);
    border-radius: 12px;
    padding: 1.5rem;
}

@media (max-width: 640px) {
    .grp-page .graph-panel { padding: 1.3rem 1.2rem; }
}
</style>

<div class="grp-page">

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

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('fechaRefG4').valueAsDate = new Date();
    initGraphTabs();
    loadGraph(1);
});
</script>