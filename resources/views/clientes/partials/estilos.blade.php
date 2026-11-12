
<style>
    .cliente-page {
        width: 100%;
        max-width: 1150px;
        margin: 0 auto;
    }

    /* ENCABEZADO */
    .cliente-banner {
        position: relative;
        overflow: hidden;
        isolation: isolate;
        color: white;
        background: linear-gradient(115deg, #082b3b, #0b3b50 65%, #087e99);
        border-radius: 20px 20px 0 0;
    }

    .cliente-banner::before,
    .cliente-banner::after {
        content: "";
        position: absolute;
        z-index: -1;
        border: 2px solid rgb(34 211 238 / 18%);
        border-radius: 50%;
    }

    .cliente-banner::before {
        width: 230px;
        height: 230px;
        right: 35px;
        top: -125px;
    }

    .cliente-banner::after {
        width: 145px;
        height: 145px;
        right: 135px;
        bottom: -100px;
    }

    .cliente-icon {
        display: grid;
        width: 58px;
        height: 58px;
        flex-shrink: 0;
        place-items: center;
        border-radius: 17px;
        background: #cffafe;
        color: #07536c;
    }

    /* PANEL */
    .cliente-panel {
        overflow: hidden;
        border: 1px solid #dce8f0;
        border-radius: 0 0 20px 20px;
        background: white;
        box-shadow: 0 14px 38px rgb(11 42 58 / 8%);
    }

    /* FORMULARIO */
    .cliente-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 23px 26px;
    }

    .cliente-field label {
        display: block;
        margin-bottom: 8px;
        color: #17394d;
        font-size: 13px;
        font-weight: 700;
    }

    .cliente-field .campo-contenedor {
        position: relative;
    }

    .cliente-field .campo-icono {
        position: absolute;
        top: 50%;
        left: 12px;
        display: grid;
        width: 34px;
        height: 34px;
        place-items: center;
        transform: translateY(-50%);
        border-radius: 10px;
        background: #e4f9fc;
        color: #087e99;
        pointer-events: none;
    }

    .cliente-field input {
        width: 100%;
        min-height: 49px;
        padding: 11px 14px 11px 57px;
        border: 1px solid #d5e3ed;
        border-radius: 12px;
        background: #fbfdff;
        color: #17394d;
        font-size: 13px;
        transition: .2s ease;
    }

    .cliente-field input:hover {
        border-color: #a5dbe5;
    }

    .cliente-field input:focus {
        border-color: #08a6c1;
        background: white;
        box-shadow: 0 0 0 4px rgb(8 166 193 / 11%);
        outline: none;
    }

    .cliente-field input::placeholder {
        color: #9aabb9;
    }

    /* MENSAJE INFORMATIVO */
    .cliente-note {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 13px 16px;
        border: 1px solid #d8edf8;
        border-radius: 12px;
        background: #eff9ff;
        color: #315d78;
        font-size: 12px;
    }

    .cliente-note svg {
    width: 20px !important;
    height: 20px !important;
    min-width: 20px;
    max-width: 20px;
    flex: 0 0 20px;
}

    /* BOTONES DEL FORMULARIO */
    .cliente-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        padding: 16px 28px;
        border-top: 1px solid #eaf0f4;
        background: #fbfdff;
    }

    .cliente-actions .cliente-btn {
        display: inline-flex;
        width: auto;
        min-width: 110px;
        height: 36px;
        min-height: 36px;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 0 14px;
        border-radius: 8px;
        font-size: 12px;
        line-height: 1;
        font-weight: 600;
        box-shadow: none;
        cursor: pointer;
        transition: .2s ease;
    }

    .cliente-actions .cliente-btn-cancelar {
        border: 1px solid #d7e4ed;
        background: white;
        color: #526b7d;
    }

    .cliente-actions .cliente-btn-cancelar:hover {
        background: #f0f6f9;
    }

    .cliente-actions .cliente-btn-guardar {
        border: none;
        background: linear-gradient(110deg, #087b98, #06364c);
        color: white;
    }

    .cliente-actions .cliente-btn-guardar:hover {
        filter: brightness(1.1);
    }

    /* BOTÓN NUEVO CLIENTE */
    .cliente-btn-nuevo {
        display: inline-flex;
        height: 36px;
        min-height: 36px;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 0 14px;
        border-radius: 8px;
        background: linear-gradient(110deg, #087b98, #06364c);
        color: white;
        font-size: 12px;
        font-weight: 600;
    }

    /* TARJETAS DE ESTADÍSTICAS */
    .cliente-stat {
        border: 1px solid #dce8f0;
        border-radius: 15px;
        background: white;
        padding: 17px;
        box-shadow: 0 5px 16px rgb(11 42 58 / 4%);
    }

    /* TARJETAS DE CLIENTES */
    .cliente-card {
        display: flex;
        height: 100%;
        flex-direction: column;
        border: 1px solid #dce8f0;
        border-radius: 17px;
        background: white;
        padding: 20px;
        box-shadow: 0 5px 16px rgb(11 42 58 / 4%);
        transition: .2s ease;
    }

    .cliente-card:hover {
        transform: translateY(-3px);
        border-color: #a5dbe5;
        box-shadow: 0 12px 25px rgb(11 42 58 / 9%);
    }

    .cliente-info-label {
        color: #8193a2;
        font-size: 12px;
    }

    .cliente-info-value {
        color: #17394d;
        font-size: 13px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    /* ESTADO VACÍO */
    .cliente-empty {
        border: 1px dashed #cbdce6;
        border-radius: 15px;
        background: #f8fbfd;
        padding: 40px 20px;
        text-align: center;
    }

    /* HISTORIAL */
    .cliente-history-card {
        overflow: hidden;
        border: 1px solid #dce8f0;
        border-radius: 17px;
        background: white;
        box-shadow: 0 5px 16px rgb(11 42 58 / 4%);
        transition: .2s ease;
    }

    .cliente-history-card:hover {
        border-color: #a5dbe5;
        box-shadow: 0 10px 22px rgb(11 42 58 / 8%);
    }

    .cliente-history-card summary {
        cursor: pointer;
        list-style: none;
    }

    .cliente-history-card summary::-webkit-details-marker {
        display: none;
    }

    .cliente-history-card[open] .cliente-chevron {
        transform: rotate(180deg);
    }

    /* RESPONSIVE */
    @media (max-width: 640px) {
        .cliente-form-grid {
            grid-template-columns: 1fr;
            gap: 18px;
        }

        .cliente-actions {
            flex-wrap: wrap;
            justify-content: flex-end;
            padding: 14px 17px;
        }
    }
</style>
