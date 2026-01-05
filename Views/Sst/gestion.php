<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .form-check-input[type="radio"] {
        transform: scale(1.9);
        margin-right: 10px;
    }

    .logo-sahm {
        max-height: 60px;
        width: auto;
        object-fit: contain;
    }

    .sectionInfo {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.15);
        margin: 20px auto;
        width: 100%;
        box-sizing: border-box;
    }

    .btn-novedad {
        cursor: pointer;
    }

    .btn-novedad {
        transition: transform 0.3s;
    }

    .btn-novedad:hover {
        text-decoration: none;
        border: none;
        outline: none;
        transform: scale(1.02);
    }

    .hover-card {
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .status-pill {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 50rem;
        font-size: 0.875rem;
        font-weight: 500;
    }
    .status-success {
        background-color: #d1fae5;
        color: #065f46;
    }
    .status-danger {
        background-color: #fee2e2;
        color: #991b1b;
    }
    .form-section {
        display: none;
    }
    .back-btn {
        margin-bottom: 20px;
    }

    /* CORREGIDO: Eliminar max-height y scroll personalizado */
    .form-container {
        padding-right: 0; /* Eliminar padding derecho */
        overflow: visible; /* Cambiar de auto/scroll a visible */
    }

    .disabled-card {
        opacity: 0.6;
        pointer-events: none;
        cursor: not-allowed;
    }

    .status-secondary {
        background-color: #6c757d;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
    }

    .status-warning {
        background-color: #ffc107;
        color: black;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
    }

    /* Inline status + preview button row */
    .hover-card .status-row {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: flex-start;
        margin-top: 10px;
    }
    .hover-card .btn-previsualizar {
        padding: 4px 6px;
        font-size: 0.85rem;
        line-height: 1;
    }

    /* ============================================
       RESPONSIVE DESIGN - MOBILE, TABLET, DESKTOP
       ============================================ */
    
    /* Ajustes generales para todos los dispositivos */
    .sectionInfo {
        padding: 20px;
    }
    
    /* Botón de previsualización responsive */
    #btn-previsualizar-carta {
        width: 100%;
        white-space: normal;
        height: auto;
        min-height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    /* Firma y foto - Contenedores responsive */
    .signature-photo-row {
        gap: 1rem !important;
    }
    
    #signature-container-initial,
    [id^="signature-container-"] {
        max-width: 100% !important;
        width: 100% !important;
    }
    
    #signature-pad-initial,
    canvas[id^="signature-pad-"] {
        max-width: 100% !important;
        width: 100% !important;
        height: auto !important;
    }
    
    
    /* MOBILE - Menos de 576px */
    @media (max-width: 575.98px) {
        .sectionInfo,
        #forms-container {
            padding: 15px;
            margin: 10px auto;
            border-radius: 8px;
        }
        
        .sectionInfo h2 {
            font-size: 1.5rem;
            margin-bottom: 1rem !important;
        }
        
        /* Todos los campos ocupan 100% en móvil */
        .row > [class*="col-"] {
            flex: 0 0 100%;
            max-width: 100%;
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
            margin-bottom: 1rem;
        }
        
        /* Labels más pequeños */
        .form-label {
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }
        
        /* Inputs con mejor touch target */
        .form-control,
        select.form-control {
            font-size: 16px !important; /* Evita zoom en iOS */
            padding: 12px;
            min-height: 48px;
        }
        
        /* Botones más grandes para touch */
        .btn {
            min-height: 48px;
            padding: 12px 20px;
            font-size: 1rem;
        }
        
        .btn-lg {
            min-height: 52px;
            padding: 14px 24px;
            font-size: 1.1rem;
        }
        
        /* Firma adaptada a móvil */
        #signature-container-initial {
            max-width: 100% !important;
            width: 100% !important;
        }
        
        #signature-pad-initial {
            width: 100% !important;
            max-width: 100% !important;
            height: 120px !important;
        }
        
        /* Firma y foto en columna en móvil */
        .signature-photo-row {
            flex-direction: column !important;
            gap: 1.5rem !important;
        }
        
        .signature-box,
        .photo-box {
            flex: 1 1 100% !important;
            max-width: 100% !important;
            min-width: unset !important;
        }
        
        /* Video y foto uno debajo del otro en móvil */
        .d-flex.justify-content-between {
            flex-direction: column !important;
            gap: 1rem;
        }
        
        .d-flex.justify-content-between > div {
            width: 100% !important;
            max-width: 100% !important;
        }
        
        video {
            height: 200px !important;
        }
        
        .foto-placeholder {
            height: 200px !important;
        }
        
        /* Scroll buttons ajustados */
        .scroll-buttons {
            right: 10px;
        }
        
        .scroll-buttons .btn {
            width: 40px;
            height: 40px;
            padding: 0;
            font-size: 1.2rem;
        }
    }
    
    /* TABLET - 576px a 991px */
    @media (min-width: 576px) and (max-width: 991.98px) {
        .sectionInfo,
        #forms-container {
            padding: 25px;
            margin: 15px auto;
        }
        
        .sectionInfo h2 {
            font-size: 1.75rem;
        }
        
        /* 2 columnas en tablet para campos de 6 cols */
        .row > .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
        
        /* Inputs con buen tamaño */
        .form-control,
        select.form-control {
            font-size: 15px;
            padding: 10px 12px;
            min-height: 44px;
        }
        
        .btn {
            min-height: 44px;
            padding: 10px 18px;
        }
        
        .btn-lg {
            min-height: 48px;
            padding: 12px 22px;
        }
        
        /* Firma ajustada */
        #signature-pad-initial {
            width: 100% !important;
            max-width: 380px !important;
            height: 140px !important;
        }
        
        /* Firma y foto lado a lado en tablet */
        .signature-photo-row {
            flex-direction: row !important;
            gap: 2rem !important;
        }
        
        .signature-box,
        .photo-box {
            flex: 1 1 45% !important;
            max-width: 48% !important;
        }
        
        /* Video y foto lado a lado */
        .d-flex.justify-content-between {
            flex-direction: row !important;
            gap: 2%;
        }
        
        .d-flex.justify-content-between > div {
            width: 48% !important;
        }
        
        video,
        .foto-placeholder {
            height: 180px !important;
        }
    }
    
    /* Ajustes específicos para orientación horizontal en móvil/tablet */
    @media (max-width: 991.98px) and (orientation: landscape) {
        .signature-photo-row {
            flex-direction: row !important;
        }
        
        .signature-box,
        .photo-box {
            flex: 1 1 48% !important;
            max-width: 48% !important;
        }
        
        #signature-pad-initial {
            height: 120px !important;
        }
    }
    
    /* Mejoras de accesibilidad y touch */
    @media (hover: none) and (pointer: coarse) {
        /* Dispositivos táctiles */
        .btn,
        .form-control,
        select.form-control,
        input[type="text"],
        input[type="number"] {
            min-height: 48px;
        }
        
        .hover-card:active {
            transform: translateY(-3px);
        }
    }

    /* Colocar el footer en la parte baja */
    .min-vh-100 {
        min-height: 100vh;
    }

    #content-wrapper {
        flex: 1 0 auto;
    }

    #forms-container {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.15);
        margin: 20px auto;
        width: 100%;
        box-sizing: border-box;
    }

    /* Estilos para los botones de firma */
    .btn-group .btn {
        margin: 0 5px;
    }

    /* Estilo para el contenedor de firma cuando tiene firma maestra disponible */
    .firma-maestra-disponible {
        border-left: 4px solid #17a2b8 !important;
    }

    /* Mensaje de información sobre firma maestra */
    .firma-maestra-info {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 5px;
    }

    /* Asegurar que los contenedores principales ocupen todo el ancho */
    .container, .container-fluid {
        max-width: 100% !important;
        width: 100% !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
        margin: 0 !important;
    }

    /* Eliminar cualquier restricción de ancho en filas y columnas */
    .row {
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    /* Optimización específica para el formulario inicial */
    #initial-form-container .row,
    #initial-form-container .container-fluid,
    #initial-form-container .card,
    #initial-form-container .card-body,
    #initial-form-container form {
        max-width: 100% !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }

    /* Ajustar padding de columnas para mejor distribución */
    #initial-form-container [class*="col-"] {
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }

    /* Prevenir overflow en elementos internos */
    .form-control, .form-select, .form-check {
        max-width: 100%;
    }

    /* Asegurar que las tarjetas no creen overflow */
    .card {
        max-width: 100%;
        box-sizing: border-box;
    }

    /* ELIMINA todos los estilos anteriores del footer y reemplaza con: */

    .sticky-footer {
        width: 100%;
        background-color: #ffffff;
        border-top: 1px solid #e3e6f0;
        margin-top: auto;
        position: relative;
        bottom: 0;
        left: 0;
        right: 0;
    }

    .sticky-footer .container {
        max-width: 100%;
    }

    .sticky-footer .copyright {
        width: 100%;
        text-align: center;
    }

    /* Asegurar la estructura flex correcta */
    body {
        background-color: #f8f9fc;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    #content-wrapper {
        flex: 1 0 auto;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    #content {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
    }

    /* Contenedores principales */
    #main-content {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    /* Asegurar que los formularios no empujen el footer */
    #forms-container {
        flex: 1;
        margin-bottom: 0;
    }

    /* Animación de pulso para el botón cuando está listo */
    .pulse-animation {
        animation: pulse 2s infinite;
    }

    /* Estilos para el botón grande */
    #btn-guardar-todo {
        font-size: 1.2rem;
        padding: 15px 30px;
        border-radius: 50px;
        transition: all 0.3s ease;
    }

    #btn-guardar-todo:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    /* Estilos del scroll */
    .scroll-buttons {
        position: fixed;
        right: 20px;
        z-index: 1000;
    }

    .scroll-buttons .btn {
        margin-bottom: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    #scrollToTopBtn {
        bottom: 80px;
    }

    #scrollToBottomBtn {
        bottom: 20px;
    }

    /* Estilos específicos para el bloque Datos Personales Básicos */
    /* Mantener firma (izquierda) y foto (derecha) en la misma fila para ahorrar scroll */
    #initial-form-container .signature-photo-row { 
        display: flex; 
        gap: 2rem; 
        align-items: flex-start; 
        flex-wrap: wrap; 
        justify-content: space-evenly;
        width: 100% !important;
        max-width: 100% !important;
    }
    /* Dar espacio adaptable para aprovechar el ancho completo */
    #initial-form-container .signature-box, 
    #initial-form-container .photo-box { 
        flex: 1 1 400px; 
        max-width: 600px;
        min-width: 320px;
        width: 100%;
    }
    #initial-form-container .signature-box canvas { width:100%; height:auto; }
    #initial-form-container .photo-box .card {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: center;
        padding: 1rem;
        background: transparent;
        box-shadow: none;
        border: none;
        margin: 0 auto;
        text-align: center;
        width: 100%;
        max-width: 420px;
    }
    #initial-form-container .photo-box img { background: transparent; box-shadow: none; }
    #initial-form-container .photo-box .video-container { width:100%; display:flex; justify-content:center; }

    /* Centrado específico para el bloque dinámico de foto (firma+foto) */
    /* Aplica tanto en el formulario inicial como en formularios dinámicos generados */
    #initial-form-container .col-md-6.text-center > div,
    #dynamic_form .col-md-6.text-center > div {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: flex-start !important;
        text-align: center !important;
        width: 100% !important;
    }

    /* Asegurar que el encabezado 'Foto personal' quede centrado y encima de la caja */
    #initial-form-container .col-md-6.text-center > div h6.mb-0,
    #dynamic_form .col-md-6.text-center > div h6.mb-0 {
        width: 100% !important;
        text-align: center !important;
        margin-bottom: 0.5rem !important;
    }

    /* Forzar que el contenedor flex interior que antes justificaba el espacio ahora centre su contenido */
    #initial-form-container .col-md-6.text-center > div .d-flex.justify-content-between,
    #dynamic_form .col-md-6.text-center > div .d-flex.justify-content-between {
        justify-content: center !important;
    }

    /* Centrar la caja placeholder y limitar su ancho para quedar justo debajo del título */
    #initial-form-container .col-md-6.text-center > div .foto-placeholder,
    #dynamic_form .col-md-6.text-center > div .foto-placeholder {
        margin: 0.5rem auto !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    /* Estilo para caja de firma: fondo blanco, borde y sombra sutil para destacarla a la izquierda */
    #initial-form-container .signature-box #signature-container-initial,
    #initial-form-container .signature-box [id^="signature-container-"] {
        background: #ffffff !important;
        box-shadow: 0 6px 18px rgba(0,0,0,0.08) !important;
        border: 1px solid rgba(0,0,0,0.08) !important;
        border-radius: 8px;
        padding: 8px;
        /* Centrar y limitar ancho para que la firma coincida visualmente con la foto */
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        margin: 0 auto !important;
        width: 100% !important;
        max-width: 320px !important;
    }
    /* Forzar que los canvas tengan fondo transparente y no sean más anchos que el placeholder de foto */
    #initial-form-container canvas[id^="signature-pad-"],
    #forms-container canvas[id^="signature-pad-"] {
        background: transparent !important;
        border-radius: 6px;
        width:100%;
        height:auto;
        max-width: 300px !important;
        box-sizing: border-box !important;
    }
    /* Asegurar que los contenedores dinámicos de firma también muestren el estilo blanco y estén centrados */
    #forms-container [id^="signature-container-"],
    #dynamic_form [id^="signature-container-"] {
        background: #ffffff !important;
        box-shadow: 0 6px 18px rgba(0,0,0,0.08) !important;
        border: 1px solid rgba(0,0,0,0.08) !important;
        border-radius: 8px;
        padding: 6px;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        margin: 0 auto !important;
        width: 100% !important;
        max-width: 320px !important;
    }
    /* Hacer el área de datos básicos ocupe todo el ancho disponible */
    #initial-form-container { 
        max-width: 100% !important;
        margin: 0 !important;
        padding: 1rem 0.5rem !important;
        width: 100vw !important;
    }
    #initial-form-container .card { 
        max-width: none !important; 
        width: 100% !important;
        margin: 0 !important;
        box-sizing: border-box !important;
    }
    #initial-form-container .card-body { 
        padding: 2rem 1rem !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    /* Optimización de campos para pantallas grandes */
    @media (min-width: 1400px) {
        #initial-form-container .card-body { padding: 2rem 3rem !important; }
    }
    @media (min-width: 1600px) {
        #initial-form-container .card-body { padding: 2rem 4rem !important; }
    }
    @media (min-width: 1900px) {
        #initial-form-container .card-body { padding: 2rem 6rem !important; }
    }
    /* Más separación entre campos para que no queden pegados */
    #initial-form-container .row > [class*="col-"] { margin-bottom: 1rem; }
    
    /* Optimizaciones específicas para el contrato 15 */
    .contract-container {
        padding: 25px 40px !important;
        margin: 15px auto !important;
        width: 98% !important;
        max-width: 1600px !important;
    }
    
    .contract-container .form-section {
        margin-bottom: 25px;
        padding: 25px 30px;
        border-radius: 8px;
        background: #ffffff;
        border: 1px solid #e3e6f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        width: 100%;
    }
    
    .contract-container .row {
        margin-left: -10px;
        margin-right: -10px;
    }
    
    .contract-container .row > [class*="col-"] {
        padding-left: 10px;
        padding-right: 10px;
        margin-bottom: 15px;
    }
    
    .contract-container .form-control {
        padding: 12px 15px;
        font-size: 14px;
        border-radius: 4px;
        height: auto;
        min-height: 42px;
        line-height: 1.4;
        border: 1px solid #d1d3e2;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    
    .contract-container .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        outline: 0;
    }
    
    .contract-container select.form-control {
        padding: 12px 30px 12px 15px;
        height: 42px;
        line-height: 1.4;
        background-position: right 12px center;
        background-size: 12px;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        overflow: visible;
    }
    
    .contract-container select.form-control option {
        padding: 8px 15px;
        line-height: 1.4;
        font-size: 14px;
    }
    
    .contract-container .section-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #2c3e50;
        padding-bottom: 8px;
        border-bottom: 2px solid #e9ecef;
    }
    
    /* Responsividad mejorada para pantallas grandes */
    @media (min-width: 1200px) {
        .contract-container {
            max-width: 1800px !important;
            padding: 30px 50px !important;
        }
        
        .contract-container .row > [class*="col-"] {
            margin-bottom: 18px;
        }
    }
    
    /* Pantallas medianas optimizadas */
    @media (min-width: 768px) and (max-width: 1199px) {
        .contract-container {
            max-width: 95% !important;
            padding: 25px 35px !important;
        }
    }
    
    /* Pantallas pequeñas */
    @media (max-width: 767px) {
        .contract-container {
            width: 95% !important;
            padding: 20px 15px !important;
            margin: 10px auto !important;
        }
        
        .contract-container .form-section {
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .contract-container .row > [class*="col-"] {
            margin-bottom: 12px;
        }
    }
    
    /* Estilos específicos para las secciones de parientes */
    .contract-container .card {
        width: 100% !important;
        max-width: none !important;
        margin-bottom: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .contract-container .card .card-body {
        padding: 25px;
    }
    
    .contract-container .card .card-header {
        padding: 15px 25px;
        font-weight: 600;
        font-size: 15px;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-bottom: 1px solid #dee2e6;
    }
    /* Etiqueta de firma centrada sobre el canvas */
    #initial-form-container .signature-label { display:block; text-align:center; font-weight:600; margin-bottom:0.75rem; }
    /* Si la pantalla es muy estrecha, apilar verticalmente (menos scroll en móviles) */
    @media (max-width: 640px) { 
        #initial-form-container .signature-photo-row { 
            flex-direction: column; 
            gap: 1rem; 
        }
        #initial-form-container .signature-box, 
        #initial-form-container .photo-box { 
            flex: 1 1 100%; 
            min-width: unset; 
        }
    }
    
    /* Optimizaciones específicas para pantallas extra grandes */
    @media (min-width: 1200px) {
        #initial-form-container .signature-photo-row {
            gap: 4rem;
            justify-content: center;
        }
        #initial-form-container .signature-box, 
        #initial-form-container .photo-box {
            flex: 0 0 45%;
            max-width: 500px;
        }
    }

    /* Estilos específicos para el campo Observaciones y botón enviar */
    #observaciones {
        min-height: 160px;
        padding: 14px 16px;
        border-radius: 10px;
        border: 1px solid #e6eef6;
        box-shadow: 0 8px 24px rgba(16, 40, 80, 0.06);
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
        resize: vertical;
        width: 100%;
        font-size: 0.98rem;
        color: #213048;
        line-height: 1.45;
        transition: box-shadow 160ms ease, border-color 160ms ease;
    }

    /* Mantener aspecto legible aun cuando esté disabled */
    #observaciones[disabled] {
        opacity: 1;
        background: #fbfdff;
        color: #495057;
    }

    #observaciones:focus {
        outline: none;
        border-color: #9fc5ff;
        box-shadow: 0 8px 30px rgba(46, 125, 255, 0.08);
    }

    /* Pequeño ajuste visual del label */
    #observaciones-container label.form-label { font-weight: 700; color: #2c3e50; }

    /* Transición suave para el botón cuando su margin-top cambia */
    #btn-guardar-todo { transition: margin-top 240ms cubic-bezier(.2,.8,.2,1), transform 120ms ease; }

    /* Espaciado entre la lista de contratos y el bloque de observaciones */
    #contracts-list { margin-bottom: 20px; transition: margin-bottom 160ms ease; }
    #observaciones-container { margin-top: 20px !important; transition: margin-top 160ms ease; }
    
    @media (min-width: 1600px) {
        #initial-form-container .signature-photo-row {
            gap: 6rem;
        }
        #initial-form-container .signature-box, 
        #initial-form-container .photo-box {
            flex: 0 0 40%;
            max-width: 600px;
        }
    }
    
    @media (min-width: 2000px) {
        #initial-form-container .signature-photo-row {
            gap: 8rem;
        }
        #initial-form-container .signature-box, 
        #initial-form-container .photo-box {
            flex: 0 0 35%;
            max-width: 700px;
        }
    }

    /* FIX: Evitar el movimiento horizontal al abrir/cerrar modales */
    body {
        /* Forzar siempre la barra de scroll para evitar el shift */
        overflow-y: scroll !important;
    }

    /* Cuando un modal está abierto, mantener el scroll pero sin permitir scroll en el fondo */
    body.modal-open {
        overflow-y: scroll !important;
        padding-right: 0 !important; /* Eliminar el padding que Bootstrap agrega automáticamente */
    }

    /* Asegurar que los modales no tengan problemas de scroll */
    .modal {
        overflow-y: auto !important;
    }

    /* Centrar mejor los modales y evitar que se muevan */
    .modal-dialog {
        margin: 30px auto !important;
    }

    /* Asegurar que el contenido del contrato no se mueva */
    #forms-container, #dynamic_form, .contract-container {
        margin-left: 0 !important;
        margin-right: 0 !important;
        padding-left: 30px !important;
        padding-right: 30px !important;
        box-sizing: border-box !important;
    }

    .sectionInfo {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.15);
    }
</style>

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <img draggable="false" src="<?php echo URL ?>/Views/Default/img/logo-sahm.png" class="logo-sahm img-fluid">
            <ul class="navbar-nav ml-auto">
                <div class="topbar-divider d-none d-sm-block"></div>
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-inline d-sm-block text-gray-600 small text-truncate"><?php echo $_SESSION['user_doc']; ?></span>
                        <img draggable="false" class="img-profile rounded-circle" src="<?php echo URL.VIEWS.DTF; ?>img/undraw_profile.svg">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Salir
                        </a>
                        <a class="dropdown-item" id="showGeneralModal" href="#" data-toggle="modal" data-target="#generalModal" style="display:none">
                        </a>
                    </div>
                </li>
            </ul>
        </nav>

        <?php
            $tipos_documentos = $array[0];
            $inducciones_ext_procesos = $array[1];
        ?>

        <div id="initial-form-container" class="container-fluid px-2 py-4">
            <div class="row mx-0">
                <div class="sectionInfo">
                    <h2 class="mb-0 text-center mb-4">Inducciones SST</h2>
                    <form id="initial-form">
                        <div class="row mx-0">
                            <!-- Campos de nombre divididos -->
                            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-12 mb-3 px-2">
                                <label for="primer_nombre" class="form-label">Primer Nombre *</label>
                                <input type="text" class="form-control text-center" id="primer_nombre" name="primer_nombre" required>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-12 mb-3 px-2">
                                <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                                <input type="text" class="form-control text-center" id="segundo_nombre" name="segundo_nombre">
                            </div>
                            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-12 mb-3 px-2">
                                <label for="primer_apellido" class="form-label">Primer Apellido *</label>
                                <input type="text" class="form-control text-center" id="primer_apellido" name="primer_apellido" required>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-12 mb-3 px-2">
                                <label for="segundo_apellido" class="form-label">Segundo Apellido *</label>
                                <input type="text" class="form-control text-center" id="segundo_apellido" name="segundo_apellido">
                            </div>

                            <!-- Documento -->
                            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-12 mb-3 px-2">
                                <label for="id_tipo_documento" class="form-label">Tipo Documento *</label>
                                <select name="id_tipo_documento" id="id_tipo_documento" class="form-control text-center" required>
                                    <option value="">Tipo de Documento</option>
                                    <?php foreach ($tipos_documentos as $tipo): ?>
                                        <?php if($tipo[0] == 8) {
                                            continue;
                                        }?>
                                        <option value="<?php echo $tipo[0]; ?>"><?php echo $tipo[1]; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-12 mb-3 px-2">
                                <label for="numero_documento" class="form-label">Documento de Identidad *</label>
                                <input type="text" class="form-control text-center" id="numero_documento" name="numero_documento" required>
                            </div>

                            <!-- Procesos -->
                            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-12 mb-3 px-2">
                                <label for="id_proceso_sst_ext" class="form-label">Procesos *</label>
                                <select name="id_proceso_sst_ext" id="id_proceso_sst_ext" class="form-control text-center" required>
                                    <option value="">Tipo de Proceso</option>
                                    <?php foreach ($inducciones_ext_procesos as $tipo): ?>
                                        <option value="<?php echo $tipo[0]; ?>"><?php echo $tipo[2]; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Botón de Previsualización -->
                            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-12 mb-3 px-2 mt-4">
                                <button type="button" id="btn-previsualizar-carta" class="btn btn-secondary btn-lg" disabled>
                                    <i class="fas fa-file-pdf"></i> Previsualizar
                                </button>
                            </div>
                            
                            <!-- Firma inicial + Foto personal -->
                            <div class="col-12 mt-4">
                                <div class="row mx-0 signature-photo-row">
                                    <!-- Firma Personal -->
                                    <div class="col-lg-6 col-md-12 text-center signature-box mb-4">
                                        <label class="signature-label">Firma Personal *</label>
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <!-- Canvas de firma centrado -->
                                            <div id="signature-container-initial" style="border: 1px dashed #cfd8e3; border-radius: 8px; background: #ffffff; display: flex; justify-content: center; align-items: center; margin: 0 auto;">
                                                <canvas id="signature-pad-initial" style="width: 400px; height: 150px;"></canvas>
                                            </div>
                                            <p id="alerta-firma-inicial" style="color:red; display:block" class="mt-2">La firma es obligatoria</p>
                                            <input type="hidden" id="firma_personal" name="firma_personal" value="">
                                            <!-- Botones debajo del canvas -->
                                            <div class="btn-group mt-3" role="group">
                                                <button type="button" id="clear-button-initial" class="btn btn-outline-primary btn-lg" title="Limpiar Firma">
                                                    <i class="fas fa-eraser"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Foto Personal -->
                                    <div class="col-lg-6 col-md-12 text-center photo-box mb-4">
                                        <div class="rounded-3">
                                            <h6 class="mb-3"><b>Foto personal *</b></h6>
                                            <div class="d-flex flex-wrap justify-content-between align-items-start">
                                                <!-- Video a la izquierda -->
                                                <div class="video-wrapper" style="width: 48%;">
                                                    <div id="capturarVideoUsuario-foto_personal" class="video-container mb-2">
                                                        <video id="videoUsuario" autoplay class="w-100 rounded border" style="height: 180px; object-fit: cover;"></video>
                                                    </div>
                                                </div>
                                                <!-- Placeholder a la derecha -->
                                                <div id="fotoPlaceholder-foto_personal" class="foto-placeholder d-flex flex-column align-items-center justify-content-center" style="width: 48%; height: 180px; border: 2px dashed #cfd8e3; border-radius: 8px; background: #f8f9fa; overflow: hidden; position: relative;">
                                                    <i class="bi bi-image text-muted" style="font-size: 2rem; margin-bottom: 10px;"></i>
                                                    <img id="fotoPreview-foto_personal" src="" alt="Foto" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border-radius: 6px; object-fit: cover; display: none;" draggable="false">
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <button type="button" class="btn btn-primary capturar-foto-btn px-4 py-2" data-field-name="foto_personal">
                                                    <i class="fas fa-camera fa-lg text-white-50"></i>
                                                </button>
                                                <input type="hidden" id="foto_personal" name="foto_personal" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save"></i> Enviar Datos
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; Hogar y Moda 2023</span>
            </div>
        </div>
    </footer>
</div>

<?php 
    $loader = new LoadingAnimation();
    echo $loader->render();
?>

<script>
    // Constante con la URL base para usar en JavaScript
    const BASE_URL = '<?php echo URL; ?>';
</script>
<script src="<?php echo assetVersion('Views/Sst/Scripts/gestion.js'); ?>"></script>