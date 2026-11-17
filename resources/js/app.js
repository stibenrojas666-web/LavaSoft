import './bootstrap';
import Alpine from 'alpinejs';
import ApexCharts from 'apexcharts';
import Chart from 'chart.js/auto';

// flatpickr
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
// FullCalendar
import { Calendar } from '@fullcalendar/core';



window.Alpine = Alpine;
window.ApexCharts = ApexCharts;
window.Chart = Chart;
window.flatpickr = flatpickr;
window.FullCalendar = Calendar;

Alpine.start();

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

// Initialize components on DOM ready
document.addEventListener('DOMContentLoaded', () => {
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
