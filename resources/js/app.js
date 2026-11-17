import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import 'datatables.net-dt/css';
import 'datatables.net-responsive-dt/css';



window.Alpine = Alpine;
window.Chart = Chart;

Alpine.start();

document.addEventListener('submit', (event) => {
    if (!event.target.matches('form[data-preserve-scroll]')) return;
    sessionStorage.setItem('lavasoft-list-scroll-y', String(window.scrollY));
});

window.addEventListener('pageshow', () => {
    const posicion = sessionStorage.getItem('lavasoft-list-scroll-y');
    if (posicion === null) return;

    sessionStorage.removeItem('lavasoft-list-scroll-y');
    requestAnimationFrame(() => {
        const destino = window.location.hash
            ? document.getElementById(decodeURIComponent(window.location.hash.slice(1)))
            : null;

        if (destino) {
            destino.scrollIntoView({ block: 'center' });
            return;
        }

        window.scrollTo(0, Number(posicion));
    });
});

document.addEventListener('click', (event) => {
    const button = event.target.closest('[data-password-toggle]');
    if (!button) return;

    const input = document.getElementById(button.dataset.passwordToggle);
    if (!input) return;

    const mostrar = input.type === 'password';
    input.type = mostrar ? 'text' : 'password';
    button.setAttribute('aria-pressed', String(mostrar));
    button.setAttribute('aria-label', mostrar ? 'Ocultar contraseña' : 'Mostrar contraseña');
    button.setAttribute('title', mostrar ? 'Ocultar contraseña' : 'Mostrar contraseña');
    const ojoAbierto = button.querySelector('[data-password-eye]');
    const ojoCerrado = button.querySelector('[data-password-eye-off]');
    if (ojoAbierto) ojoAbierto.style.display = mostrar ? 'none' : 'block';
    if (ojoCerrado) ojoCerrado.style.display = mostrar ? 'block' : 'none';
});

document.addEventListener('click', (event) => {
    document.querySelectorAll('details[data-dismiss-on-outside][open]').forEach((panel) => {
        if (event.target instanceof Node && !panel.contains(event.target)) {
            panel.open = false;
        }
    });
});

document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') return;

    document.querySelectorAll('details[data-dismiss-on-outside][open]').forEach((panel) => {
        panel.open = false;
    });
});

// Initialize components on DOM ready
document.addEventListener('DOMContentLoaded', async () => {
    const tablas = document.querySelectorAll('table[data-datatable]');
    if (tablas.length > 0) {
        const [{ default: DataTable }] = await Promise.all([
            import('datatables.net-dt'),
            import('datatables.net-responsive-dt'),
        ]);

        tablas.forEach((table) => {
            if (DataTable.isDataTable(table)) return;

        // DataTables requiere que cada fila del tbody tenga la misma cantidad
        // de celdas que el encabezado. Omitir filas Blade vacías con colspan
        // evita el aviso "Requested unknown parameter".
        const columnas = table.tHead?.rows[0]?.cells.length ?? 0;
        const filasValidas = Array.from(table.tBodies).every((tbody) =>
            Array.from(tbody.rows).every((row) => {
                const celdas = Array.from(row.cells);
                const cantidad = celdas.reduce((total, cell) => total + cell.colSpan, 0);

                return celdas.every((cell) => cell.colSpan === 1 && cell.rowSpan === 1)
                    && (columnas === 0 || cantidad === columnas);
            }),
        );
        if (!filasValidas) return;

        const paginadaPorLaravel = table.dataset.serverPaginated === 'true';
            new DataTable(table, {
            responsive: true,
            autoWidth: false,
            ordering: !paginadaPorLaravel,
            searching: !paginadaPorLaravel,
            paging: !paginadaPorLaravel,
            pageLength: 10,
            info: !paginadaPorLaravel,
            language: {
                search: 'Buscar:',
                lengthMenu: 'Mostrar _MENU_',
                info: 'Mostrando _START_ a _END_ de _TOTAL_',
                infoEmpty: 'Sin registros',
                zeroRecords: 'No hay coincidencias',
                emptyTable: 'No hay registros disponibles',
                paginate: { previous: 'Anterior', next: 'Siguiente' },
            },
            });
        });
    }

    // Map imports
    if (document.querySelector('#mapOne')) {
        import('./components/map').then(module => module.initMap());
    }

    // Chart imports
    if (document.querySelector('#chartOne')) {
        import('./components/chart/chart-1').then(module => module.initChartOne());
    }
    if (document.querySelector('#chartTwo')) {
        import('./components/chart/chart-2').then(module => module.initChartTwo());
    }
    if (document.querySelector('#chartThree')) {
        import('./components/chart/chart-3').then(module => module.initChartThree());
    }
    if (document.querySelector('#chartSix')) {
        import('./components/chart/chart-6').then(module => module.initChartSix());
    }
    if (document.querySelector('#chartEight')) {
        import('./components/chart/chart-8').then(module => module.initChartEight());
    }
    if (document.querySelector('#chartThirteen')) {
        import('./components/chart/chart-13').then(module => module.initChartThirteen());
    }

    // Calendar init
    if (document.querySelector('#calendar')) {
        import('./components/calendar-init').then(module => module.calendarInit());
    }
});
