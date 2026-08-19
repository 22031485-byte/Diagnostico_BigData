<style>

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600&display=swap');

.dash-page {
    --dp-cyan: #0EA5B7;
    --dp-cyan-dark: #0B7285;
    --dp-navy: #123C5A;
    --dp-navy-deep: #0C2A40;
    --dp-sky: #5FD3E0;
    --dp-bg-soft: #F2F9FA;
    --dp-text: #12222B;
    --dp-text-dim: #5D7480;
    --dp-white: #FFFFFF;
    --dp-radius: 16px;
    font-family: 'Inter', system-ui, sans-serif;
    color: var(--dp-text);
}
.dash-page h1, .dash-page h2, .dash-page h3 {
    font-family: 'Poppins', sans-serif;
}

/* ---------- HERO ---------- */
.dash-hero {
    position: relative;
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    align-items: center;
    gap: 2rem;
    background: linear-gradient(135deg, var(--dp-navy-deep) 0%, var(--dp-navy) 45%, var(--dp-cyan-dark) 100%);
    border-radius: var(--dp-radius);
    padding: 3rem 3rem;
    overflow: hidden;
    margin-bottom: 2.5rem;
    isolation: isolate;
}
.dash-hero::before {
    /* textura sutil de puntos, como panel de control */
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px);
    background-size: 22px 22px;
    opacity: 0.5;
    z-index: -1;
}
.dash-hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: rgba(95, 211, 224, 0.16);
    border: 1px solid rgba(95, 211, 224, 0.35);
    color: var(--dp-sky);
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    padding: 0.35rem 0.8rem;
    border-radius: 999px;
    margin-bottom: 1rem;
}
.dash-hero h1 {
    color: #fff;
    font-size: 2.3rem;
    font-weight: 800;
    line-height: 1.15;
    margin: 0 0 0.9rem;
    max-width: 14ch;
}
.dash-hero p {
    color: rgba(255,255,255,0.82);
    font-size: 1rem;
    line-height: 1.6;
    max-width: 42ch;
    margin: 0 0 1.6rem;
}
.dash-hero-cta {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--dp-white);
    color: var(--dp-navy-deep);
    font-weight: 700;
    font-size: 0.92rem;
    padding: 0.8rem 1.5rem;
    border-radius: 999px;
    text-decoration: none;
    transition: transform 0.15s, box-shadow 0.15s;
    box-shadow: 0 8px 20px -6px rgba(0,0,0,0.35);
    width: fit-content;
}
.dash-hero-cta:hover { transform: translateY(-2px); box-shadow: 0 12px 26px -6px rgba(0,0,0,0.4); }

.dash-hero-visual {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}
.dash-hero-visual img {
    width: 100%;
    max-width: 380px;
    border-radius: 12px;
    box-shadow: 0 30px 60px -20px rgba(0,0,0,0.5);
    border: 6px solid rgba(255,255,255,0.15);
}
@media (max-width: 760px) {
    .dash-hero { grid-template-columns: 1fr; padding: 2.2rem 1.6rem; text-align: center; }
    .dash-hero h1 { max-width: none; margin-inline: auto; }
    .dash-hero p { margin-inline: auto; }
    .dash-hero-cta { margin-inline: auto; }
}

/* ---------- KPI CARDS ---------- */
.dash-kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.1rem;
    margin-bottom: 2.5rem;
}
.dash-kpi-card {
    background: var(--dp-white);
    border-radius: var(--dp-radius);
    padding: 1.4rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 4px 18px -8px rgba(18, 60, 90, 0.18);
    border: 1px solid #E6EEF0;
    transition: transform 0.15s, box-shadow 0.15s;
}
.dash-kpi-card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px -10px rgba(18, 60, 90, 0.28); }
.dash-kpi-icon {
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    background: linear-gradient(135deg, var(--dp-cyan), var(--dp-cyan-dark));
    color: #fff;
}
.dash-kpi-card:nth-child(2) .dash-kpi-icon { background: linear-gradient(135deg, #2FBF8F, #1E9670); }
.dash-kpi-card:nth-child(3) .dash-kpi-icon { background: linear-gradient(135deg, #F5A623, #D98410); }
.dash-kpi-card:nth-child(4) .dash-kpi-icon { background: linear-gradient(135deg, #EF6461, #C94A47); }
.dash-kpi-text { display: flex; flex-direction: column; gap: 0.15rem; }
.dash-kpi-label { font-size: 0.78rem; color: var(--dp-text-dim); font-weight: 500; }
.dash-kpi-value { font-size: 1.3rem; font-weight: 700; color: var(--dp-navy-deep); font-family: 'Poppins', sans-serif; }

/* ---------- QUICK LINKS (estilo botones oscuros del panel de servicios) ---------- */
.dash-section-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--dp-navy-deep);
    margin: 0 0 1.1rem;
}
.dash-quick-links {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.1rem;
}
.dash-quick-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: linear-gradient(135deg, var(--dp-navy), var(--dp-navy-deep));
    color: #fff;
    text-decoration: none;
    border-radius: 14px;
    padding: 1.2rem 1.4rem;
    transition: transform 0.15s, box-shadow 0.15s, background 0.2s;
    box-shadow: 0 6px 20px -10px rgba(12, 42, 64, 0.5);
}
.dash-quick-card:hover {
    transform: translateY(-3px);
    background: linear-gradient(135deg, var(--dp-cyan-dark), var(--dp-navy));
}
.dash-quick-icon {
    flex-shrink: 0;
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: rgba(255,255,255,0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}
.dash-quick-card h3 {
    margin: 0 0 0.15rem;
    font-size: 0.98rem;
    font-weight: 700;
}
.dash-quick-card p {
    margin: 0;
    font-size: 0.82rem;
    color: rgba(255,255,255,0.75);
    line-height: 1.4;
}
</style>

<div class="dash-page">

    <section class="dash-hero">
        <div>
            <span class="dash-hero-eyebrow">● Portal de RR.HH.</span>
            <h1>Analítica de tu fuerza laboral</h1>
            <p>Contrataciones, salarios y movilidad interna de toda la organización, actualizados en tiempo real desde un solo panel.</p>
            <a href="index.php?page=reports" class="dash-hero-cta">Ver reportes →</a>
        </div>
        <div class="dash-hero-visual">
            <img src="public/img/dashboard-preview.png" alt="Vista previa del panel de reportes de empleados">
        </div>
    </section>

    <section class="dash-kpi-grid" id="kpiGrid">
        <div class="dash-kpi-card">
            <div class="dash-kpi-icon">🏢</div>
            <div class="dash-kpi-text">
                <span class="dash-kpi-label">Departamentos</span>
                <span class="dash-kpi-value" id="kpiDepts">—</span>
            </div>
        </div>
        <div class="dash-kpi-card">
            <div class="dash-kpi-icon">👥</div>
            <div class="dash-kpi-text">
                <span class="dash-kpi-label">Empleados activos</span>
                <span class="dash-kpi-value" id="kpiEmployees">—</span>
            </div>
        </div>
        <div class="dash-kpi-card">
            <div class="dash-kpi-icon">💰</div>
            <div class="dash-kpi-text">
                <span class="dash-kpi-label">Salario promedio más alto</span>
                <span class="dash-kpi-value" id="kpiTopSalaryDept">—</span>
            </div>
        </div>
        <div class="dash-kpi-card">
            <div class="dash-kpi-icon">🔄</div>
            <div class="dash-kpi-text">
                <span class="dash-kpi-label">Depto. con más rotación</span>
                <span class="dash-kpi-value" id="kpiRotation">—</span>
            </div>
        </div>
    </section>

    <h2 class="dash-section-title">Accesos rápidos</h2>
    <section class="dash-quick-links">
        <a class="dash-quick-card" href="index.php?page=reports">
            <div class="dash-quick-icon">📊</div>
            <div>
                <h3>Reportes</h3>
                <p>Tablas de contrataciones, salarios, plantilla, edades y rotación.</p>
            </div>
        </a>
        <a class="dash-quick-card" href="index.php?page=graphs">
            <div class="dash-quick-icon">📈</div>
            <div>
                <h3>Gráficos</h3>
                <p>Visualizaciones interactivas para cada reporte con Chart.js.</p>
            </div>
        </a>
        <a class="dash-quick-card" href="index.php?page=employees">
            <div class="dash-quick-icon">🔍</div>
            <div>
                <h3>Consulta de Empleados</h3>
                <p>Busca a un empleado y revisa su ficha completa.</p>
            </div>
        </a>
    </section>

</div>

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