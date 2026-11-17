<style>
    .cliente-page{width:100%;max-width:1150px;margin:0 auto}
    .cliente-banner{position:relative;overflow:hidden;isolation:isolate;color:#fff;background:linear-gradient(115deg,#082b3b,#0b3b50 65%,#087e99);border-radius:20px}
    .cliente-banner::before,.cliente-banner::after{content:"";position:absolute;z-index:-1;border:2px solid rgb(34 211 238 / 18%);border-radius:50%}
    .cliente-banner::before{width:230px;height:230px;right:35px;top:-125px}
    .cliente-banner::after{width:145px;height:145px;right:135px;bottom:-100px}
    .cliente-icon{display:grid;width:58px;height:58px;flex-shrink:0;place-items:center;border-radius:17px;background:#cffafe;color:#07536c}
    .cliente-panel{overflow:hidden;border:1px solid #dce8f0;border-radius:20px;background:#fff;box-shadow:0 14px 38px rgb(11 42 58 / 8%)}
    .cliente-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:23px 26px}
    .cliente-field label{display:block;margin-bottom:8px;color:#17394d;font-size:13px;font-weight:700}
    .cliente-field .campo-contenedor{position:relative}
    .cliente-field .campo-icono{position:absolute;top:50%;left:12px;display:grid;width:34px;height:34px;place-items:center;transform:translateY(-50%);border-radius:10px;background:#e4f9fc;color:#087e99;pointer-events:none}
    .cliente-field input{width:100%;min-height:49px;padding:11px 14px 11px 57px;border:1px solid #d5e3ed;border-radius:12px;background:#fbfdff;color:#17394d;font-size:13px;transition:.2s ease}
    .cliente-field input:hover{border-color:#a5dbe5}
    .cliente-field input:focus{border-color:#08a6c1;background:#fff;box-shadow:0 0 0 4px rgb(8 166 193 / 11%);outline:none}
    .cliente-field input::placeholder{color:#9aabb9}
    .cliente-field input[aria-invalid="true"]{border-color:#fca5a5;background:#fffafa}
    .cliente-note{display:flex;align-items:center;gap:10px;padding:13px 16px;border:1px solid #d8edf8;border-radius:12px;background:#eff9ff;color:#315d78;font-size:12px}
    .cliente-note svg{width:20px!important;height:20px!important;min-width:20px;flex:0 0 20px}
    .cliente-actions{display:flex;align-items:center;justify-content:flex-end;gap:10px;padding:16px 28px;border-top:1px solid #eaf0f4;background:#fbfdff}
    .cliente-actions .cliente-btn{display:inline-flex;min-width:110px;min-height:40px;align-items:center;justify-content:center;gap:7px;padding:0 15px;border-radius:9px;font-size:12px;font-weight:700;cursor:pointer;transition:.2s ease}
    .cliente-btn-cancelar{border:1px solid #d7e4ed;background:#fff;color:#526b7d}
    .cliente-btn-cancelar:hover{background:#f0f6f9}
    .cliente-btn-guardar,.cliente-btn-nuevo{background:linear-gradient(110deg,#087b98,#06364c);color:#fff}
    .cliente-btn-guardar:hover,.cliente-btn-nuevo:hover{filter:brightness(1.1);transform:translateY(-1px)}
    .cliente-btn-nuevo{display:inline-flex;min-height:38px;align-items:center;justify-content:center;gap:7px;padding:0 15px;border-radius:9px;font-size:12px;font-weight:700;transition:.2s ease}
    .cliente-stat{padding:17px;border:1px solid #dce8f0;border-radius:15px;background:#fff;box-shadow:0 5px 16px rgb(11 42 58 / 4%)}
    .cliente-card{display:flex;height:100%;flex-direction:column;padding:20px;border:1px solid #dce8f0;border-radius:17px;background:#fff;box-shadow:0 5px 16px rgb(11 42 58 / 4%);transition:.2s ease}
    .cliente-card:hover{transform:translateY(-3px);border-color:#a5dbe5;box-shadow:0 12px 25px rgb(11 42 58 / 9%)}
    .cliente-info-label{color:#8193a2;font-size:12px}
    .cliente-info-value{overflow-wrap:anywhere;color:#17394d;font-size:13px;font-weight:600}
    .cliente-empty{padding:40px 20px;border:1px dashed #cbdce6;border-radius:15px;background:#f8fbfd;text-align:center}
    .cliente-history-card{overflow:hidden;border:1px solid #dce8f0;border-radius:17px;background:#fff;box-shadow:0 5px 16px rgb(11 42 58 / 4%);transition:.2s ease}
    .cliente-history-card:hover{border-color:#a5dbe5;box-shadow:0 10px 22px rgb(11 42 58 / 8%)}
    .cliente-history-card summary{cursor:pointer;list-style:none}
    .cliente-history-card summary::-webkit-details-marker{display:none}
    .cliente-history-card[open] .cliente-chevron{transform:rotate(180deg)}
    @media(max-width:640px){
        .cliente-form-grid{grid-template-columns:1fr;gap:18px}
        .cliente-actions{flex-wrap:wrap;justify-content:flex-end;padding:14px 17px}
        .cliente-actions .cliente-btn{flex:1}
    }
</style>