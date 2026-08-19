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

<script>
document.addEventListener('DOMContentLoaded', initEmployeeSearch);
</script>