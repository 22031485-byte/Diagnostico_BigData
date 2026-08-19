<style>

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600&display=swap');

.emp-page {
    --ep-cyan: #0EA5B7;
    --ep-cyan-dark: #0B7285;
    --ep-navy: #123C5A;
    --ep-navy-deep: #0C2A40;
    --ep-sky: #5FD3E0;
    --ep-text: #12222B;
    --ep-text-dim: #5D7480;
    --ep-line: #E6EEF0;
    --ep-radius: 16px;
    font-family: 'Inter', system-ui, sans-serif;
    color: var(--ep-text);
}
.emp-page h1, .emp-page h2, .emp-page h3 { font-family: 'Poppins', sans-serif; }

.emp-page .page-header { margin-bottom: 1.8rem; }
.emp-page .page-header h1 {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--ep-navy-deep);
    margin: 0 0 0.35rem;
}
.emp-page .page-header p {
    color: var(--ep-text-dim);
    margin: 0;
    font-size: 0.95rem;
    max-width: 65ch;
}

.emp-page .search-row {
    display: flex;
    gap: 0.7rem;
    background: #F2F9FA;
    border: 1px solid var(--ep-line);
    border-radius: 999px;
    padding: 0.5rem;
    margin-bottom: 1.7rem;
}
.emp-page .search-row input {
    flex: 1;
    background: #fff;
    border: 1px solid var(--ep-line);
    color: var(--ep-text);
    padding: 0.7rem 1.1rem;
    border-radius: 999px;
    font-size: 0.95rem;
}
.emp-page .search-row input:focus {
    outline: none;
    border-color: var(--ep-cyan);
    box-shadow: 0 0 0 3px rgba(14,165,183,0.15);
}
.emp-page .btn-primary {
    background: linear-gradient(135deg, var(--ep-cyan), var(--ep-cyan-dark));
    color: #fff;
    border: none;
    padding: 0.7rem 1.6rem;
    border-radius: 999px;
    cursor: pointer;
    font-weight: 700;
    font-size: 0.9rem;
    transition: filter 0.15s, transform 0.15s;
}
.emp-page .btn-primary:hover { filter: brightness(1.08); transform: translateY(-1px); }

.emp-page .table-wrap {
    overflow-x: auto;
    border: 1px solid var(--ep-line);
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 6px 24px -16px rgba(18,60,90,0.18);
}
.emp-page table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
.emp-page thead th {
    text-align: left;
    background: var(--ep-navy-deep);
    color: #fff;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.72rem;
    letter-spacing: 0.04em;
    padding: 0.8rem 1rem;
}
.emp-page tbody td { padding: 0.7rem 1rem; border-bottom: 1px solid var(--ep-line); }
.emp-page tbody tr:nth-child(even) { background: #F8FBFC; }
.emp-page tbody tr:hover { background: rgba(14,165,183,0.08); }

.emp-page .btn-link {
    background: var(--ep-navy);
    color: #fff;
    border: none;
    padding: 0.35rem 0.9rem;
    border-radius: 999px;
    cursor: pointer;
    font-size: 0.78rem;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.15s;
}
.emp-page .btn-link:hover { background: var(--ep-cyan-dark); }

#searchMoreNote { color: var(--ep-text-dim); font-size: 0.85rem; margin-top: 0.7rem; font-style: italic; }

.emp-page .employee-detail {
    margin-top: 2.2rem;
    background: linear-gradient(135deg, var(--ep-navy-deep), var(--ep-navy));
    border-radius: var(--ep-radius);
    overflow: hidden;
    display: grid;
    grid-template-columns: 240px 1fr;
    box-shadow: 0 14px 40px -18px rgba(12,42,64,0.55);
}
.emp-page .ed-photo {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.9rem;
    padding: 2rem 1.5rem;
    text-align: center;
}
.emp-page .ed-avatar-fallback {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    border: 3px solid var(--ep-sky);
    background: rgba(95,211,224,0.12);
    color: var(--ep-sky);
    font-size: 2rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}
.emp-page .ed-photo img {
    width: 110px;
    height: 110px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid var(--ep-sky);
}
.emp-page .ed-photo-label {
    color: rgba(255,255,255,0.6);
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.emp-page .ed-body {
    background: #fff;
    padding: 2rem 2.2rem;
    border-radius: var(--ep-radius) 0 0 var(--ep-radius);
}
.emp-page .ed-body #edNombre {
    margin: 0 0 0.35rem;
    font-size: 1.35rem;
    color: var(--ep-navy-deep);
}
.emp-page .ed-general {
    color: var(--ep-text-dim);
    margin-bottom: 1.5rem;
    font-size: 0.88rem;
}
.emp-page .ed-columns {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.6rem;
    margin-bottom: 1.6rem;
}
.emp-page .ed-columns h3, .emp-page .ed-body h3 {
    font-size: 0.92rem;
    color: var(--ep-navy-deep);
    border-bottom: 2px solid var(--ep-cyan);
    padding-bottom: 0.4rem;
    margin-bottom: 0.7rem;
}
.emp-page .ed-body .table-wrap { box-shadow: none; border: 1px solid var(--ep-line); }

@media (max-width: 680px) {
    .emp-page .employee-detail { grid-template-columns: 1fr; }
    .emp-page .ed-body { border-radius: 0; }
    .emp-page .search-row { flex-direction: column; border-radius: 16px; }
    .emp-page .search-row input, .emp-page .btn-primary { border-radius: 12px; }
}
</style>

<div class="emp-page">

    <section class="page-header">
        <h1>Consulta de Empleados</h1>
        <p>Busca por número de empleado, nombre o apellido. La base tiene un gran volumen de registros, así que la búsqueda
           muestra hasta 50 coincidencias a la vez.</p>
    </section>

    <div class="search-row">
        <input type="text" id="employeeSearchInput" placeholder="Ej. 10001, Georgi, Facello...">
        <button id="btnSearchEmployee" class="btn-primary">Buscar</button>
    </div>

    <div class="table-wrap">
        <table id="table-search-results">
            <thead><tr><th>No. Empleado</th><th>Nombre</th><th>Género</th><th>Fecha de contratación</th><th></th></tr></thead>
            <tbody></tbody>
        </table>
    </div>

    <div id="employeeDetail" class="employee-detail hidden">
        <div class="ed-photo">
            <div class="ed-avatar-fallback" id="edAvatarFallback">--</div>
            <span class="ed-photo-label">Ficha del empleado</span>
        </div>
        <div class="ed-body">
            <h2 id="edNombre"></h2>
            <div class="ed-general" id="edGeneral"></div>

            <div class="ed-columns">
                <div>
                    <h3>Historial de puestos</h3>
                    <div class="table-wrap"><table id="table-titles"><thead><tr><th>Puesto</th><th>Desde</th><th>Hasta</th></tr></thead><tbody></tbody></table></div>
                </div>
                <div>
                    <h3>Historial de departamentos</h3>
                    <div class="table-wrap"><table id="table-depts"><thead><tr><th>Departamento</th><th>Desde</th><th>Hasta</th></tr></thead><tbody></tbody></table></div>
                </div>
            </div>

            <h3>Historial salarial</h3>
            <div class="table-wrap"><table id="table-salaries"><thead><tr><th>Salario</th><th>Desde</th><th>Hasta</th></tr></thead><tbody></tbody></table></div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => initEmployeeSearch());
</script>