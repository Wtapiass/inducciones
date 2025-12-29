$(document).ready(function() {
    // ============================================
    // INICIALIZACIÓN DE FIRMA
    // ============================================
    function inicializarFirmaInicial() {
        const canvas = document.getElementById('signature-pad-initial');
        if (!canvas) return;

        // Evitar inicializar dos veces si ya existe
        if (canvas._signaturePadInstance) {
            return;
        }

        const signaturePad = new SignaturePad(canvas);
        // Guardar la instancia en el elemento para evitar dobles inicializaciones
        canvas._signaturePadInstance = signaturePad;

        // Ajustar el tamaño del canvas según su tamaño CSS y el devicePixelRatio
        function resizeCanvasPreserveData() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            // Guardar datos actuales (strokes)
            let data = null;
            try {
                if (!signaturePad.isEmpty()) {
                    data = signaturePad.toData();
                }
            } catch (e) {
                console.warn('No pude leer datos previos de signaturePad antes de redimensionar:', e);
            }

            // Obtener tamaño CSS del canvas
            const rect = canvas.getBoundingClientRect();
            // Establecer tamaño real en pixeles multiplicado por ratio
            canvas.width = Math.round(rect.width * ratio);
            canvas.height = Math.round(rect.height * ratio);

            // Escalar el contexto para que 1 unidad CSS = 1 unidad en canvas
            const ctx = canvas.getContext('2d');
            ctx.setTransform(ratio, 0, 0, ratio, 0, 0);

            // Restaurar datos si existían
            try {
                signaturePad.clear();
                if (data && data.length) {
                    signaturePad.fromData(data);
                }
            } catch (e) {
                console.warn('No pude restaurar los datos de signaturePad tras redimensionar:', e);
            }
        }

        // Ejecutar resize inicial
        resizeCanvasPreserveData();

        // Recalcular en resize de ventana (debounce mínimo)
        let resizeTimeout = null;
        window.addEventListener('resize', function() {
            if (resizeTimeout) clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                resizeCanvasPreserveData();
            }, 150);
        });

        // Configurar botón de limpiar
        $('#clear-button-initial').on('click', function(event) {
            event.preventDefault();
            signaturePad.clear();
            $('#alerta-firma-inicial').show();
            $('#signature-container-initial').css({'border-color':'#ccc'});
            $('#firma_personal').val('');
        });

        // Usar pointerup para manejar mouse y touch de forma consistente
        const terminarFirma = function() {
            try {
                if (!signaturePad.isEmpty()) {
                    $('#alerta-firma-inicial').hide();
                    $('#signature-container-initial').css({'border-color':'#28a745'});
                    const dataURL = signaturePad.toDataURL('image/png');
                    $('#firma_personal').val(dataURL);
                }
            } catch (e) {
                console.warn('Error al extraer dataURL de la firma:', e);
            }
        };

        canvas.addEventListener('pointerup', terminarFirma);
        canvas.addEventListener('mouseup', terminarFirma);
        canvas.addEventListener('touchend', terminarFirma);
    }

    // ============================================
    // INICIALIZACIÓN DE CÁMARA Y FOTO
    // ============================================
    function iniciarCamaraParaFotos() {
        const video = document.getElementById('videoUsuario');
        if (!video) {
            console.error('❌ No se encontró elemento de video');
            return;
        }
        
        // Detener cámara anterior si existe
        if (window.fotoStream) {
            window.fotoStream.getTracks().forEach(track => track.stop());
        }
        
        // Mostrar contenedor de video si existe
        const videoContainer = video.parentElement;
        if (videoContainer) {
            videoContainer.style.display = 'block';
        }
        
        // Solicitar acceso a la cámara
        navigator.mediaDevices.getUserMedia({
            video: { 
                width: { ideal: 1280 },
                height: { ideal: 720 },
                facingMode: "user"
            }
        })
        .then((stream) => {
            video.srcObject = stream;
            window.fotoStream = stream;
        })
        .catch((error) => {
            console.error("❌ Error accessing the camera: ", error);
            $('.capturar-foto-btn').prop('disabled', true).text('Cámara no disponible');
            alert('No se puede acceder a la cámara. Verifica los permisos.');
        });
    }

    function capturarFoto() {
        const video = document.getElementById('videoUsuario');
        const fotoPreview = document.getElementById('fotoPreview-foto_personal');
        const fotoPlaceholder = document.getElementById('fotoPlaceholder-foto_personal');
        const inputHidden = document.getElementById('foto_personal');

        try {
            // Capturar frame en canvas offscreen
            const vw = video.videoWidth || 640;
            const vh = video.videoHeight || 480;
            const maxWidth = 800;
            const scale = Math.min(1, maxWidth / vw);
            const w = Math.round(vw * scale);
            const h = Math.round(vh * scale);

            const off = document.createElement('canvas');
            off.width = w;
            off.height = h;
            const ctx = off.getContext('2d');
            ctx.drawImage(video, 0, 0, w, h);

            const dataURL = off.toDataURL('image/jpeg', 0.9);
            
            // Guardar en input hidden
            if (inputHidden) inputHidden.value = dataURL;

            // Mostrar preview
            if (fotoPreview) {
                fotoPreview.src = dataURL;
                fotoPreview.style.display = 'block';
                
                // Ocultar el texto placeholder
                const placeholderText = fotoPlaceholder.querySelector('.text-muted');
                if (placeholderText) placeholderText.style.display = 'none';
                
                // Ocultar el ícono si existe
                const placeholderIcon = fotoPlaceholder.querySelector('.bi-image');
                if (placeholderIcon) placeholderIcon.style.display = 'none';
            }
            
            if (fotoPlaceholder) fotoPlaceholder.style.display = 'flex';

        } catch(e) {
            console.error('Error capturando foto:', e);
            alert('Error al capturar la foto');
        }
    }

    function detenerCamaraFotos() {
        if (window.fotoStream) {
            window.fotoStream.getTracks().forEach(track => track.stop());
            window.fotoStream = null;
        }
    }

    // ============================================
    // EVENT LISTENERS
    // ============================================
    
    // Botón capturar foto
    $(document).on('click', '.capturar-foto-btn', function() {
        const video = document.getElementById('videoUsuario');
        
        // Si la cámara no está activa, activarla primero
        if (!video || !video.srcObject) {
            iniciarCamaraParaFotos();
            return;
        }
        
        // Si la cámara ya está activa, capturar foto
        capturarFoto();
    });

    // Inicializar firma cuando carga el documento
    inicializarFirmaInicial();

    // ============================================
    // VALIDACIÓN Y PREVISUALIZACIÓN (MEDIANTE TRIGGERS)
    // ============================================
    function validarCamposRequeridos() {
        const primer_nombre = $('#primer_nombre').val().trim();
        const primer_apellido = $('#primer_apellido').val().trim();
        const segundo_apellido = $('#segundo_apellido').val().trim();
        const tipo_documento = $('#id_tipo_documento').val();
        const documento = $('#numero_documento').val();
        const id_proceso_sst = $('#id_proceso_sst_ext').val();

        // Habilitar botón solo si todos los campos están llenos
        if (primer_nombre && primer_apellido && segundo_apellido && tipo_documento && documento && id_proceso_sst) {
            $('#btn-previsualizar-carta').prop('disabled', false).removeClass('btn-secondary').addClass('btn-info');
        } else {
            $('#btn-previsualizar-carta').prop('disabled', true).removeClass('btn-info').addClass('btn-secondary');
        }
    }

    // TRIGGER: Escuchar cuando el usuario escribe en los inputs de texto (evento 'input' en tiempo real)
    $('#primer_nombre, #primer_apellido, #segundo_apellido, #numero_documento').on('input', function() {
        validarCamposRequeridos();
    });

    // TRIGGER: Escuchar cuando el usuario selecciona una opción en los selects (evento 'change')
    $('#id_tipo_documento, #id_proceso_sst_ext').on('change', function() {
        validarCamposRequeridos();
    });

    // Manejar clic en botón de previsualización
    $('#btn-previsualizar-carta').on('click', function() {
        const id_proceso = $('#id_proceso_sst_ext').val();
        const segundo_nombre = $('#segundo_nombre').val().trim();
        const primer_nombre = $('#primer_nombre').val().trim();
        const segundo_apellido = $('#segundo_apellido').val().trim();
        const primer_apellido = $('#primer_apellido').val().trim();
        const documento = $('#numero_documento').val().trim();
        
        // Construir nombre completo
        const nombre_completo = [primer_nombre, segundo_nombre, primer_apellido, segundo_apellido]
            .filter(n => n !== '')
            .join(' ');

        // Determinar qué carta generar según el proceso
        let tipoCarta = '';
        if (id_proceso == '3' || id_proceso == '4') {
            tipoCarta = 'adminYCall';
        } else if (id_proceso == '2') {
            tipoCarta = 'comercial';
        } else {
            alert('El proceso seleccionado no tiene una carta asociada');
            return;
        }

        // Abrir en nueva ventana
        const url = BASE_URL + 'Sst/print_carta/' + tipoCarta + '?nombre=' + encodeURIComponent(nombre_completo) + '&documento=' + encodeURIComponent(documento);
        window.open(url, '_blank');
    });

    // ============================================
    // SUBMIT DEL FORMULARIO INICIAL
    // ============================================
    $('#initial-form').on('submit', function(event) {
        event.preventDefault();
        
        // Validar firma
        if (!$('#firma_personal').val()) {
            alert('Por favor, agregue su firma');
            return;
        }
        
        // Validar foto obligatoria
        if (!$('#foto_personal').val()) {
            alert('Por favor, capture su foto antes de continuar');
            return;
        }
        
        // Recopilar datos del formulario
        const formData = {
            primer_nombre: $('#primer_nombre').val(),
            segundo_nombre: $('#segundo_nombre').val(),
            primer_apellido: $('#primer_apellido').val(),
            segundo_apellido: $('#segundo_apellido').val(),
            id_tipo_documento: $('#id_tipo_documento').val(),
            numero_documento: $('#numero_documento').val(),
            id_proceso_sst_ext: $('#id_proceso_sst_ext').val(),
            firma: $('#firma_personal').val(),
            foto: $('#foto_personal').val()
        };
        
        // Detener cámara antes de continuar
        detenerCamaraFotos();
        
        $.ajax({
            type: 'POST',
            url: BASE_URL + 'Sst/gestion',
            data: formData,
            success: function(response) {
                if (response != 0) {
                    jQuery("#modalMessage").text('Datos guardados exitosamente!');
                    jQuery("#modalMessage").css({
                        'color': 'green'
                    });
                    jQuery("#showGeneralModal")[0].click();
                    jQuery('#btnAceptarModal').bind("click", function() {
                        window.location.href = BASE_URL;
                    });
                } else {
                    jQuery("#modalMessage").text('Error al guardar los datos.');
                    jQuery("#modalMessage").css({
                        'color': 'red'
                    });
                    jQuery("#showGeneralModal")[0].click();
                }
            },
            error: function() {
                alert('Error en la comunicación con el servidor');
            }
        });
    });
});
