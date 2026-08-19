<style>
/* =========================================================
   REPORTES — mismo sistema turquesa/navy que el dashboard
   Nota: se sobreescriben las clases que ya usa app.js
   (tab-btn, report-panel, filter-row, btn-primary, table-wrap,
   hidden, page-header, report-desc) — no cambian los IDs.
   ========================================================= */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600&display=swap');

.rep-page {
    --rp-cyan: #0EA5B7;
    --rp-cyan-dark: #0B7285;
    --rp-navy: #123C5A;
    --rp-navy-deep: #0C2A40;
    --rp-sky: #5FD3E0;
    --rp-text: #12222B;
    --rp-text-dim: #5D7480;
    --rp-line: #E6EEF0;
    --rp-radius: 16px;
    font-family: 'Inter', system-ui, sans-serif;
    color: var(--rp-text);
}
.rep-page h1, .rep-page h2 { font-family: 'Poppins', sans-serif; }

/* ---------- HEADER ---------- */
.rep-page .page-header {
    display: block;
    border-bottom: none;
    padding-bottom: 0;
    margin-bottom: 1.8rem;
}
.rep-page .page-header h1 {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--rp-navy-deep);
    margin: 0 0 0.35rem;
}
.rep-page .page-header p {
    color: var(--rp-text-dim);
    margin: 0;
    font-size: 0.95rem;
}

/* ---------- TABS (píldora segmentada) ---------- */
.rep-page .tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    background: #F2F9FA;
    border: 1px solid var(--rp-line);
    border-radius: 999px;
    padding: 0.4rem;
    margin-bottom: 1.6rem;
}
.rep-page .tab-btn {
    background: transparent;
    border: none;
    color: var(--rp-text-dim);
    padding: 0.6rem 1.1rem;
    border-radius: 999px;
    cursor: pointer;
    font-family: 'Inter', sans-serif;
    font-size: 0.83rem;
    font-weight: 600;
    transition: all 0.18s;
    white-space: nowrap;
}
.rep-page .tab-btn:hover { color: var(--rp-navy-deep); background: rgba(14,165,183,0.08); }
.rep-page .tab-btn.active {
    background: linear-gradient(135deg, var(--rp-cyan), var(--rp-navy));
    color: #fff;
    box-shadow: 0 4px 14px -4px rgba(11,114,133,0.45);
}

/* ---------- PANEL ---------- */
.rep-page .report-panel {
    background: #fff;
    border: 1px solid var(--rp-line);
    border-radius: var(--rp-radius);
    padding: 1.9rem 2rem;
    box-shadow: 0 6px 24px -14px rgba(18,60,90,0.18);
}
.rep-page .report-panel h2 {
    margin: 0 0 0.4rem;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--rp-navy-deep);
}
.rep-page .report-desc {
    color: var(--rp-text-dim);
    font-size: 0.9rem;
    margin: 0 0 1.4rem;
    max-width: 70ch;
}
.rep-page .hidden { display: none !important; }

/* ---------- FILTROS ---------- */
.rep-page .filter-row {
    display: flex;
    align-items: center;
    gap: 0.7rem;
    flex-wrap: wrap;
    background: #F2F9FA;
    border: 1px solid var(--rp-line);
    border-radius: 12px;
    padding: 0.8rem 1rem;
    margin-bottom: 1.5rem;
}
.rep-page .filter-row label {
    color: var(--rp-navy-deep);
    font-size: 0.85rem;
    font-weight: 600;
}
.rep-page .filter-row input {
    background: #fff;
    border: 1px solid var(--rp-line);
    color: var(--rp-text);
    padding: 0.5rem 0.8rem;
    border-radius: 8px;
    font-size: 0.9rem;
}
.rep-page .filter-row input:focus {
    outline: none;
    border-color: var(--rp-cyan);
    box-shadow: 0 0 0 3px rgba(14,165,183,0.15);
}
.rep-page .btn-primary {
    background: linear-gradient(135deg, var(--rp-cyan), var(--rp-cyan-dark));
    color: #fff;
    border: none;
    padding: 0.55rem 1.3rem;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.85rem;
    transition: filter 0.15s, transform 0.15s;
}
.rep-page .btn-primary:hover { filter: brightness(1.08); transform: translateY(-1px); }

/* ---------- TABLA ---------- */
.rep-page .table-wrap {
    overflow-x: auto;
    border: 1px solid var(--rp-line);
    border-radius: 12px;
}
.rep-page table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
.rep-page thead th {
    text-align: left;
    background: var(--rp-navy-deep);
    color: #fff;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.72rem;
    letter-spacing: 0.04em;
    padding: 0.8rem 1rem;
}
.rep-page tbody td {
    padding: 0.7rem 1rem;
    border-bottom: 1px solid var(--rp-line);
    color: var(--rp-text);
}
.rep-page tbody tr:nth-child(even) { background: #F8FBFC; }
.rep-page tbody tr:hover { background: rgba(14,165,183,0.08); }

@media (max-width: 640px) {
    .rep-page .report-panel { padding: 1.3rem 1.2rem; }
}
</style>

<div class="rep-page">

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

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('fechaRef4').valueAsDate = new Date();
    initReportTabs();
    loadReport(1);
});
</script>