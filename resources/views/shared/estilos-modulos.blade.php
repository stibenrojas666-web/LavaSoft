
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
