
<style>
/* Identidad visual compartida de LavaSoft */
:root {
    --lava-petroleo: #082b3b;
    --lava-azul: #0b3b50;
    --lava-celeste: #087e99;
    --lava-fondo: #f4f8fb;
    --lava-borde: #e3edf2;
    --lava-texto: #243746;
}

/* Contenedores */
.modulo-page {
    width: 100%;
    max-width: 1120px;
    margin: 0 auto;
}

.modulo-panel,
.modulo-card {
    background: #fff;
    border: 1px solid var(--lava-borde);
    border-radius: 18px;
    box-shadow: 0 5px 18px rgba(8, 43, 59, .05);
}

/* Encabezados */
.modulo-banner {
    position: relative;
    overflow: hidden;
    padding: 27px 30px;
    color: #fff;
    border-radius: 20px 20px 0 0;
    background: linear-gradient(115deg, #082b3b, #0b3b50, #087e99);
}

.lavasoft-module-header { border-radius: 20px; }

.modulo-banner::before,
.modulo-banner::after {
    content: "";
    position: absolute;
    z-index: 0;
    border: 2px solid rgb(34 211 238 / 18%);
    border-radius: 50%;
    pointer-events: none;
}

.modulo-banner::before {
    width: 230px;
    height: 230px;
    right: 35px;
    top: -125px;
}

.modulo-banner::after {
    width: 145px;
    height: 145px;
    right: 135px;
    bottom: -100px;
}

.modulo-banner h1,
.modulo-banner h2,
.modulo-banner p {
    color: #fff;
}

/* Formularios */
.modulo-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 22px 25px;
}

/* Acabado común para las páginas de mantenimiento de los módulos. */
main h1:not(.modulo-banner h1) {
    font-size: 1.5rem;
    line-height: 2rem;
    color: #17394d;
    font-weight: 800;
    letter-spacing: -.025em;
}

main h2:not(.modulo-banner h2) { color: #17394d; font-weight: 800; }

main :is(label, legend) { color: #334e5e; }

main :is(.modulo-panel, .modulo-card, .cliente-stat, .empleado-stat, .empleado-card, .cliente-card, .as-panel) {
    border-color: var(--lava-borde);
    border-radius: 17px;
    box-shadow: 0 5px 16px rgb(11 42 58 / 5%);
}

main form[method="POST"][class*="bg-white"][class*="border"] {
    border-color: var(--lava-borde);
    border-radius: 18px;
    box-shadow: 0 10px 28px rgb(11 42 58 / 6%);
}

main :is(.modulo-actions, .cliente-actions) {
    border-top: 1px solid #eaf0f4;
    background: #fbfdff;
}

main table:not(.lavasoft-table--invoice) {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    color: #334e5e;
    font-size: .875rem;
}

main table:not(.lavasoft-table--invoice) thead th {
    padding: .9rem 1rem;
    border-bottom: 1px solid var(--lava-borde);
    background-color: #eef6f9;
    color: #476171;
    font-size: .68rem;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
}

main table:not(.lavasoft-table--invoice) tbody td {
    padding: .9rem 1rem;
    border-bottom: 1px solid #edf3f6;
    color: #334e5e;
    vertical-align: middle;
}

main table:not(.lavasoft-table--invoice) tbody tr:nth-child(even) > td { background: #fbfdfe; }
main table:not(.lavasoft-table--invoice) tbody tr:last-child > td { border-bottom: 0; }
main table:not(.lavasoft-table--invoice) tbody tr { transition: background-color .14s ease; }

main table:not(.lavasoft-table--invoice) tbody tr:hover > td {
    background-color: #effbfc;
}

.modulo-field label {
    display: block;
    margin-bottom: 8px;
    color: var(--lava-petroleo);
    font-size: 13px;
    font-weight: 700;
}

.modulo-field input,
.modulo-field select,
.modulo-field textarea {
    width: 100%;
    min-height: 47px;
    padding: 11px 14px;
    color: var(--lava-texto);
    background: #f7fafc;
    border: 1px solid #dce8ee;
    border-radius: 11px;
    outline: none;
    transition: .2s ease;
}

.modulo-field input:focus,
.modulo-field select:focus,
.modulo-field textarea:focus {
    background: #fff;
    border-color: var(--lava-celeste);
    box-shadow: 0 0 0 3px rgba(8, 126, 153, .10);
}

/* Botones */
.modulo-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 42px;
    padding: 9px 17px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    transition: .2s ease;
}

.modulo-btn-primary {
    color: #fff;
    border: 0;
    background: linear-gradient(110deg, #0b3b50, #087e99);
}

.modulo-btn-primary:hover {
    color: #fff;
    transform: translateY(-1px);
}

.modulo-btn-secondary {
    color: var(--lava-petroleo);
    border: 1px solid var(--lava-borde);
    background: #fff;
}

/* Avisos */
.modulo-note {
    padding: 13px 16px;
    color: #315b70;
    background: #eff8fc;
    border: 1px solid #d8edf5;
    border-radius: 11px;
    font-size: 13px;
}

/* Responsive */
@media (max-width: 700px) {
    .modulo-form-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .modulo-banner {
        padding: 22px 18px;
    }

    .modulo-btn {
        max-width: 100%;
    }
}
</style>
