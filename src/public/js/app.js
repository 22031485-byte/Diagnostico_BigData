function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

const chartInstances = {};

async function apiGet(action, params = {}) {
    const qs = new URLSearchParams({ action, ...params }).toString();
    const res = await fetch(`api.php?${qs}`);
    const json = await res.json();
    if (json.error) throw new Error(json.error);
    return json.data;
}

function fillTable(tableId, rows, columns) {
    const tbody = document.querySelector(`#${tableId} tbody`);
    tbody.innerHTML = '';
    if (!rows || rows.length === 0) {
        tbody.innerHTML = `<tr><td colspan="${columns.length}" style="color:var(--text-dim)">Sin datos</td></tr>`;
        return;
    }
    for (const row of rows) {
        const tr = document.createElement('tr');
        tr.innerHTML = columns.map(col => `<td>${escapeHtml(row[col] ?? '')}</td>`).join('');
        tbody.appendChild(tr);
    }
}

function renderChart(canvasId, config) {
    const ctx = document.getElementById(canvasId);
    if (chartInstances[canvasId]) chartInstances[canvasId].destroy();
    chartInstances[canvasId] = new Chart(ctx, config);
}

const PALETTE = ['#4f7cff', '#2fd6a8', '#ff8a5c', '#ff5c7a', '#c084fc', '#f4c542'];


function initReportTabs() {
    document.querySelectorAll('#reportTabs .tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('#reportTabs .tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('.report-panel').forEach(p => p.classList.add('hidden'));
            const n = btn.dataset.report;
            document.getElementById(`panel-${n}`).classList.remove('hidden');
            loadReport(Number(n));
        });
    });

    document.getElementById('btnFiltrar4').addEventListener('click', () => loadReport(4));
    document.getElementById('btnFiltrar5').addEventListener('click', () => loadReport(5));
}

async function loadReport(n) {
    try {
        switch (n) {
            case 1: {
                const data = await apiGet('hiring_by_year_gender');
                fillTable('table-1', data, ['anio', 'gender', 'total_contrataciones']);
                break;
            }
            case 2: {
                const data = await apiGet('avg_salary_by_department');
                fillTable('table-2', data, ['departamento', 'salario_promedio']);
                break;
            }
            case 3: {
                const data = await apiGet('employee_count_by_department');
                fillTable('table-3', data, ['departamento', 'total_empleados']);
                break;
            }
            case 4: {
                const fecha = document.getElementById('fechaRef4').value;
                const data = await apiGet('employees_by_age_range', { fecha_ref: fecha });
                fillTable('table-4', data, ['rango_edad', 'gender', 'total_empleados']);
                break;
            }
            case 5: {
                const top = document.getElementById('topN5').value || 10;
                const data = await apiGet('top_salary_increase', { top });
                fillTable('table-5', data, ['empleado', 'salario_minimo', 'salario_maximo', 'pct_incremento', 'anios_carrera']);
                break;
            }
            case 6: {
                const data = await apiGet('department_rotation');
                fillTable('table-6', data, ['departamento', 'total_empleados', 'empleados_con_rotacion', 'pct_rotacion', 'antiguedad_promedio_anios']);
                break;
            }
        }
    } catch (err) {
        console.error(err);
        alert('Error cargando el reporte: ' + err.message);
    }
}

function initGraphTabs() {
    document.querySelectorAll('#graphTabs .tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('#graphTabs .tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('.graph-panel').forEach(p => p.classList.add('hidden'));
            const n = btn.dataset.graph;
            document.getElementById(`gpanel-${n}`).classList.remove('hidden');
            loadGraph(Number(n));
        });
    });

    document.getElementById('btnFiltrarG4').addEventListener('click', () => loadGraph(4));
    document.getElementById('btnFiltrarG5').addEventListener('click', () => loadGraph(5));
}

async function loadGraph(n) {
    try {
        switch (n) {
            case 1: {
                const data = await apiGet('hiring_by_year_gender');
                const years = [...new Set(data.map(d => d.anio))].sort();
                const genders = [...new Set(data.map(d => d.gender))];
                const datasets = genders.map((g, i) => ({
                    label: g === 'M' ? 'Masculino' : 'Femenino',
                    data: years.map(y => {
                        const row = data.find(d => d.anio == y && d.gender === g);
                        return row ? Number(row.total_contrataciones) : 0;
                    }),
                    backgroundColor: PALETTE[i],
                }));
                renderChart('chart-1', {
                    type: 'bar',
                    data: { labels: years, datasets },
                    options: { responsive: true, scales: { x: { stacked: true }, y: { stacked: true } } }
                });
                break;
            }
            case 2: {
                const data = await apiGet('avg_salary_by_department');
                renderChart('chart-2', {
                    type: 'bar',
                    data: {
                        labels: data.map(d => d.departamento),
                        datasets: [{ label: 'Salario promedio', data: data.map(d => d.salario_promedio), backgroundColor: PALETTE[0] }]
                    },
                    options: { indexAxis: 'y', responsive: true }
                });
                break;
            }
            case 3: {
                const data = await apiGet('employee_count_by_department');
                renderChart('chart-3', {
                    type: 'bar',
                    data: {
                        labels: data.map(d => d.departamento),
                        datasets: [{ label: 'Empleados', data: data.map(d => d.total_empleados), backgroundColor: PALETTE[1] }]
                    },
                    options: { responsive: true }
                });
                break;
            }
            case 4: {
                const fecha = document.getElementById('fechaRefG4').value;
                const data = await apiGet('employees_by_age_range', { fecha_ref: fecha });
                const ranges = ['<30', '30-39', '40-49', '50-59', '>=60'];
                const male = ranges.map(r => {
                    const row = data.find(d => d.rango_edad === r && d.gender === 'M');
                    return row ? -Number(row.total_empleados) : 0; 
                });
                const female = ranges.map(r => {
                    const row = data.find(d => d.rango_edad === r && d.gender === 'F');
                    return row ? Number(row.total_empleados) : 0;
                });
                renderChart('chart-4', {
                    type: 'bar',
                    data: {
                        labels: ranges,
                        datasets: [
                            { label: 'Masculino', data: male, backgroundColor: PALETTE[0] },
                            { label: 'Femenino', data: female, backgroundColor: PALETTE[3] },
                        ]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        scales: { x: { ticks: { callback: v => Math.abs(v) } } },
                        plugins: { tooltip: { callbacks: { label: ctx => `${ctx.dataset.label}: ${Math.abs(ctx.raw)}` } } }
                    }
                });
                break;
            }
            case 5: {
                const top = document.getElementById('topNG5').value || 10;
                const data = await apiGet('top_salary_increase', { top });
                renderChart('chart-5', {
                    type: 'scatter',
                    data: {
                        datasets: [{
                            label: '% Incremento salarial',
                            data: data.map(d => ({ x: Number(d.anios_carrera), y: Number(d.pct_incremento), label: d.empleado })),
                            backgroundColor: PALETTE[2],
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: { title: { display: true, text: 'Años de carrera' } },
                            y: { title: { display: true, text: '% Incremento salarial' } }
                        },
                        plugins: {
                            tooltip: { callbacks: { label: ctx => `${ctx.raw.label}: ${ctx.raw.y}%` } }
                        }
                    }
                });
                break;
            }
            case 6: {
                const data = await apiGet('department_rotation');
                renderChart('chart-6', {
                    type: 'bar',
                    data: {
                        labels: data.map(d => d.departamento),
                        datasets: [{ label: '% Rotación interna', data: data.map(d => d.pct_rotacion), backgroundColor: PALETTE[4] }]
                    },
                    options: { responsive: true }
                });
                break;
            }
        }
    } catch (err) {
        console.error(err);
        alert('Error cargando el gráfico: ' + err.message);
    }
}


function initEmployeeSearch() {
    const input = document.getElementById('employeeSearchInput');
    const btn = document.getElementById('btnSearchEmployee');

    const doSearch = async () => {
        const q = input.value.trim();
        if (!q) return;
        try {
            const result = await apiGet('employee_search', { q });
            const rows = result.results;

            const tbody = document.querySelector('#table-search-results tbody');
            tbody.innerHTML = '';

            if (!rows.length) {
                tbody.innerHTML = `<tr><td colspan="5" style="color:var(--text-dim)">Sin resultados</td></tr>`;
            } else {
                for (const emp of rows) {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${emp.emp_no}</td>
                        <td>${escapeHtml(emp.first_name)} ${escapeHtml(emp.last_name)}</td>
                        <td>${emp.gender === 'M' ? 'Masculino' : 'Femenino'}</td>
                        <td>${emp.hire_date}</td>
                        <td><button class="btn-link" data-emp="${emp.emp_no}">Ver ficha</button></td>`;
                    tbody.appendChild(tr);
                }
                tbody.querySelectorAll('.btn-link').forEach(b => {
                    b.addEventListener('click', () => loadEmployeeDetail(b.dataset.emp));
                });
            }

            let noteEl = document.getElementById('searchMoreNote');
            if (!noteEl) {
                noteEl = document.createElement('p');
                noteEl.id = 'searchMoreNote';
                document.querySelector('.table-wrap').after(noteEl);
            }
            noteEl.textContent = result.has_more
                ? 'Hay más de 50 coincidencias. Escribe un término más específico para refinar la búsqueda.'
                : '';

        } catch (err) {
            console.error(err);
            alert('Error en la búsqueda: ' + err.message);
        }
    };

    btn.addEventListener('click', doSearch);
    input.addEventListener('keydown', e => { if (e.key === 'Enter') doSearch(); });
}

async function loadEmployeeDetail(empNo) {
    try {
        const data = await apiGet('employee_detail', { emp_no: empNo });
        const g = data.general;
        document.getElementById('edNombre').textContent = `${g.first_name} ${g.last_name} (#${g.emp_no})`;
        document.getElementById('edGeneral').innerHTML = `
            Género: ${g.gender === 'M' ? 'Masculino' : 'Femenino'} &nbsp;·&nbsp;
            Nacimiento: ${g.birth_date} &nbsp;·&nbsp;
            Contratación: ${g.hire_date}`;

        const avatarEl = document.getElementById('edAvatarFallback');
        if (avatarEl) {
            const iniciales = `${g.first_name?.[0] ?? ''}${g.last_name?.[0] ?? ''}`.toUpperCase();
            avatarEl.textContent = iniciales || '--';
        }

        fillTable('table-titles', data.titles, ['title', 'from_date', 'to_date']);
        fillTable('table-depts', data.departments, ['dept_name', 'from_date', 'to_date']);
        fillTable('table-salaries', data.salaries, ['salary', 'from_date', 'to_date']);

        document.getElementById('employeeDetail').classList.remove('hidden');
        document.getElementById('employeeDetail').scrollIntoView({ behavior: 'smooth' });
    } catch (err) {
        alert('Error cargando la ficha del empleado: ' + err.message);
    }
}