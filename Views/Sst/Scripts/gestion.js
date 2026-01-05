$(document).ready(function() {
    // Nueva función para generar y compartir PDF desde WhatsApp en móvil/tablet
    async function generarYCompartirPDFWhatsApp() {
        const id_proceso = $('#id_proceso_sst_ext').val();
        const segundo_nombre = $('#segundo_nombre').val().trim();
        const primer_nombre = $('#primer_nombre').val().trim();
        const segundo_apellido = $('#segundo_apellido').val().trim();
        const primer_apellido = $('#primer_apellido').val().trim();
        const documento = $('#numero_documento').val().trim();
        const firma = $('#firma_personal').val();
        const foto = $('#foto_personal').val();

        // Validar que haya firma y foto
        if (!firma) {
            jQuery("#modalMessage").text('Por favor, agregue su firma antes de compartir.');
            jQuery("#modalMessage").css({ 'color': 'red' });
            jQuery("#showGeneralModal")[0].click();
            return;
        }
        if (!foto) {
            jQuery("#modalMessage").text('Por favor, capture su foto antes de compartir.');
            jQuery("#modalMessage").css({ 'color': 'red' });
            jQuery("#showGeneralModal")[0].click();
            return;
        }

        // Construir nombre completo
        const nombre_completo = [primer_nombre, segundo_nombre, primer_apellido, segundo_apellido]
            .filter(n => n !== '')
            .join(' ');

        const { anio, mes } = getFechaRuta();
        let nombreArchivo = '';
        if (id_proceso == '2') {
            nombreArchivo = 'Certificacion_Induccion_SG-SST_COMERCIALES_' + nombre_completo + '_' + new Date().toISOString().slice(0,10) + '.pdf';
        } else if (id_proceso == '3' || id_proceso == '4') {
            nombreArchivo = 'Certificacion_Induccion_SG-SST_ADMON_CALL_CENTER_' + nombre_completo + '_' + new Date().toISOString().slice(0,10) + '.pdf';
        } else if (id_proceso == '1') {
            nombreArchivo = 'Certificacion_Induccion_SG-SST_LOGISTICA_' + nombre_completo + '_' + new Date().toISOString().slice(0,10) + '.pdf';
        } else {
            jQuery("#modalMessage").text('El proceso seleccionado no tiene una carta asociada');
            jQuery("#modalMessage").css({ 'color': 'red' });
            jQuery("#showGeneralModal")[0].click();
            return;
        }

        const formData = {
            primer_nombre: primer_nombre,
            segundo_nombre: segundo_nombre,
            primer_apellido: primer_apellido,
            segundo_apellido: segundo_apellido,
            id_tipo_documento: $('#id_tipo_documento').val(),
            numero_documento: documento,
            id_proceso_sst_ext: id_proceso,
            firma: firma,
            foto: foto
        };

        // Construir la ruta del PDF guardado (igual que en PC)
        const rutaPDF = BASE_URL + 'files/Inducciones/documento_persona/' + anio + '/' + mes + '/' + documento + '/' + nombreArchivo;

        LoadingManager.show();
        try {
            // Primero llamar al endpoint para generar y guardar el PDF completo
            const response = await $.ajax({
                type: 'POST',
                url: BASE_URL + 'Sst/previsualizar',
                data: formData
            });
            if (response != 0) {
                // Descargar el PDF completo desde la ruta del archivo guardado (no desde el endpoint)
                const pdfResponse = await fetch(rutaPDF);
                if (!pdfResponse.ok) {
                    throw new Error('No se pudo obtener el PDF desde el servidor');
                }
                const pdfBlob = await pdfResponse.blob();
                
                const pdfFile = new File([pdfBlob], nombreArchivo, { type: 'application/pdf' });
                window._pdfFileWhatsApp = pdfFile;
                window._pdfNombreArchivoWhatsApp = nombreArchivo;
                // Descargar automáticamente el PDF
                const downloadUrl = URL.createObjectURL(pdfBlob);
                const link = document.createElement('a');
                link.href = downloadUrl;
                link.download = nombreArchivo;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                setTimeout(() => URL.revokeObjectURL(downloadUrl), 100);
                // Compartir por WhatsApp
                await shareViaWhatsApp(pdfFile, nombreArchivo);
                pdfCompartidoWhatsApp = true;
            } else {
                throw new Error('Error en la generación del PDF');
            }
        } catch (error) {
            console.error('Error:', error);
            jQuery("#modalMessage").text('Error al generar el PDF para compartir.');
            jQuery("#modalMessage").css({ 'color': 'red' });
            jQuery("#showGeneralModal")[0].click();
        } finally {
            LoadingManager.hide();
        }
    }
    
    // Bandera para saber si el PDF fue compartido por WhatsApp
    let pdfCompartidoWhatsApp = false;
    // En móvil/tablet, mostrar el botón WhatsApp siempre, pero deshabilitado hasta que todo esté completo
    function renderWhatsAppButton() {
        if (isMobileOrTablet()) {
            // Si ya existe, no lo agregues de nuevo
            if ($('#whatsapp-share-btn').length === 0) {
                const btn = $('<button>')
                    .attr('id', 'whatsapp-share-btn')
                    .addClass('btn btn-success mt-2')
                    .css({
                        'width': '100%',
                        'padding': '10px',
                        'font-size': '16px',
                        'border-radius': '8px'
                    })
                    .prop('disabled', true)
                    .html('<i class="fab fa-whatsapp"></i> Compartir por WhatsApp')
                    .on('click', async function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        // Solo permitir si está habilitado
                        if (!$(this).prop('disabled')) {
                            await generarYCompartirPDFWhatsApp();
                        }
                    });
                $('#btn-previsualizar-carta').after(btn);
            }
        }
    }

    // ============================================
    // INICIALIZACIÓN DE FIRMA MEJORADA
    // ============================================
    function mostrarFirmaPreview(dataURL) {
        const firmaPreview = document.getElementById('firmaPreview-firma_personal');
        const firmaPlaceholder = document.getElementById('firmaPlaceholder-firma_personal');
        if (!firmaPreview || !firmaPlaceholder) return;
        
        if (dataURL) {
            firmaPreview.src = dataURL;
            firmaPreview.style.display = 'block';
            // Ocultar el texto placeholder y el ícono
            const placeholderText = firmaPlaceholder.querySelector('.text-muted');
            if (placeholderText) placeholderText.style.display = 'none';
            const placeholderIcon = firmaPlaceholder.querySelector('.bi-pencil');
            if (placeholderIcon) placeholderIcon.style.display = 'none';
        } else {
            firmaPreview.src = '';
            firmaPreview.style.display = 'none';
            const placeholderText = firmaPlaceholder.querySelector('.text-muted');
            if (placeholderText) placeholderText.style.display = '';
            const placeholderIcon = firmaPlaceholder.querySelector('.bi-pencil');
            if (placeholderIcon) placeholderIcon.style.display = '';
        }
    }

    function inicializarFirmaInicial() {
        const canvas = document.getElementById('signature-pad-initial');
        if (!canvas) return;

        // Si ya existe una instancia previa, no reinicializar
        if (canvas._signaturePadInstance) return;

        // Configuración mejorada para móviles
        const signaturePad = new SignaturePad(canvas, {
            // Configuración para mejorar la experiencia táctil
            minWidth: 0.5,
            maxWidth: 2.5,
            throttle: 16, // Reduce eventos para mejor rendimiento
            backgroundColor: 'rgb(255, 255, 255)'
        });
        
        canvas._signaturePadInstance = signaturePad;

        // Restaurar firma previa si existe en el input oculto
        const firmaGuardada = $('#firma_personal').val();
        if (firmaGuardada) {
            const img = new Image();
            img.onload = function() {
                signaturePad.clear();
                canvas.getContext('2d').drawImage(img, 0, 0, canvas.width, canvas.height);
                mostrarFirmaPreview(firmaGuardada);
            };
            img.src = firmaGuardada;
        } else {
            mostrarFirmaPreview('');
        }

        // Función optimizada para guardar firma
        function guardarFirma() {
            if (!signaturePad.isEmpty()) {
                const dataURL = signaturePad.toDataURL('image/png');
                $('#firma_personal').val(dataURL);
                mostrarFirmaPreview(dataURL);
                $('#alerta-firma-inicial').hide();
                $('#signature-container-initial').css({'border-color':'#28a745'});
            } else {
                $('#firma_personal').val('');
                mostrarFirmaPreview('');
            }
        }

        // Manejo mejorado de eventos táctiles para móviles
        let isDrawing = false;
        
        function startDrawing(event) {
            // Prevenir scroll mientras se dibuja en móviles
            if (event.type.includes('touch')) {
                event.preventDefault();
            }
            isDrawing = true;
            signaturePad._isEmpty = false;
        }
        
        function endDrawing() {
            if (isDrawing) {
                isDrawing = false;
                guardarFirma();
            }
        }

        // Añadir eventos con mejor manejo para móviles
        canvas.addEventListener('pointerdown', startDrawing);
        canvas.addEventListener('mousedown', startDrawing);
        
        // Para eventos táctiles, usar passive: false para permitir preventDefault
        canvas.addEventListener('touchstart', startDrawing, { passive: false });
        
        canvas.addEventListener('pointerup', endDrawing);
        canvas.addEventListener('mouseup', endDrawing);
        canvas.addEventListener('touchend', endDrawing);
        canvas.addEventListener('touchcancel', endDrawing);

        // Eliminar resize en scroll/orientation/resize para evitar zoom tras dibujar
        function eliminarContenidoFirma() {
            signaturePad.clear();
            $('#alerta-firma-inicial').show();
            $('#signature-container-initial').css({'border-color':'#ccc'});
            $('#firma_personal').val('');
            mostrarFirmaPreview('');
            isDrawing = false;

            // Restaurar la vista mostrando el canvas nuevamente
            $('.contenedor_firma').show();
            $('#signature-row').css({
                'justify-content': 'space-between',
                'align-items': 'start'
            });
            $('#firmaPlaceholder-firma_personal').css({
                'width': '48%',
                'max-width': 'none',
                'margin': '0'
            });
            $('#save-signature-btn').show();

            // Sincronizar tamaño del canvas SOLO al limpiar
            setTimeout(function() {
                inicializarFirmaInicial();
            }, 100);
        }

        // Solo permitir resize cuando se limpia el canvas:
        $('#clear-button-initial').on('click', function(event) {
            event.preventDefault();
            eliminarContenidoFirma();
        });

        // Mejorar scroll en dispositivos móviles
        let initialTouchY = 0;
        
        canvas.addEventListener('touchstart', function(event) {
            if (event.touches.length === 1) {
                initialTouchY = event.touches[0].clientY;
            }
        }, { passive: true });
        
        canvas.addEventListener('touchmove', function(event) {
            if (event.touches.length === 1 && isDrawing) {
                // Si está dibujando, prevenir scroll
                event.preventDefault();
            } else if (event.touches.length === 1) {
                // Permitir scroll solo si no está dibujando
                const currentTouchY = event.touches[0].clientY;
                const diff = Math.abs(currentTouchY - initialTouchY);
                
                // Si el movimiento es principalmente vertical, permitir scroll
                if (diff > 10) {
                    // Permitir que el scroll natural ocurra
                    return;
                }
            }
        }, { passive: false });

        // Configurar botón de limpiar
        $('#clear-button-initial').on('click', function(event) {
            event.preventDefault();
            signaturePad.clear();
            $('#alerta-firma-inicial').show();
            $('#signature-container-initial').css({'border-color':'#ccc'});
            $('#firma_personal').val('');
            mostrarFirmaPreview('');
            isDrawing = false;

            // Restaurar la vista mostrando el canvas nuevamente
            $('.contenedor_firma').show();
            $('#signature-row').css({
                'justify-content': 'space-between',
                'align-items': 'start'
            });
            $('#firmaPlaceholder-firma_personal').css({
                'width': '48%',
                'max-width': 'none',
                'margin': '0'
            });
            $('#save-signature-btn').show();

            // Sincronizar tamaño del canvas con el tamaño visual para evitar zoom
            setTimeout(function() {
                const rect = canvas.getBoundingClientRect();
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = Math.round(rect.width * ratio);
                canvas.height = Math.round(rect.height * ratio);
                canvas.getContext('2d').setTransform(ratio, 0, 0, ratio, 0, 0);
            }, 100);
        });

        // Mejorar la zona táctil del canvas para móviles
        function mejorarZonaTactil() {
            const container = document.getElementById('signature-container-initial');
            if (container) {
                // Añadir padding para mejor zona táctil en móviles
                container.style.padding = '10px';
                canvas.style.touchAction = 'none'; // Deshabilitar gestos del navegador
            }
        }
        
        mejorarZonaTactil();
    }

    // Asegurar que se inicialice cuando el DOM esté listo
    $(document).ready(function() {
        inicializarFirmaInicial();
        
        // También reinicializar si hay cambios en la visibilidad (útil para móviles)
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                setTimeout(inicializarFirmaInicial, 100);
            }
        });
    });

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
            jQuery("#modalMessage").text('No se puede acceder a la cámara. Verifica los permisos.');
            jQuery("#modalMessage").css({
                'color': 'red'
            });
            jQuery("#showGeneralModal")[0].click();
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
            jQuery("#modalMessage").text('Error al capturar la foto');
            jQuery("#modalMessage").css({
                'color': 'red'
            });
            jQuery("#showGeneralModal")[0].click();
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
        const firma = $('#firma_personal').val();
        const foto = $('#foto_personal').val();

        // Habilitar botón solo si todos los campos están llenos
        const completo = (
            primer_nombre &&
            primer_apellido &&
            segundo_apellido &&
            tipo_documento &&
            documento &&
            id_proceso_sst &&
            firma &&
            firma.length > 100 && // Firma debe tener un tamaño mínimo (base64)
            foto &&
            foto.length > 100 // Foto debe tener un tamaño mínimo (base64)
        );
        // Siempre deshabilitado por defecto, solo habilitar si está completo
        const $btnPrev = $('#btn-previsualizar-carta');
        $btnPrev.prop('disabled', !completo);
        if (completo) {
            $btnPrev.removeClass('btn-secondary btn-info').addClass('btn-primary');
        } else {
            $btnPrev.removeClass('btn-primary btn-info').addClass('btn-secondary');
        }
        // WhatsApp en móvil/tablet
        if (isMobileOrTablet()) {
            renderWhatsAppButton();
            $('#whatsapp-share-btn').prop('disabled', !completo);
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

    // TRIGGER: Validar cuando cambia la firma o la foto
    $('#firma_personal, #foto_personal').on('change input', function() {
        validarCamposRequeridos();
    });

    // Validar también después de capturar foto o guardar firma
    $(document).on('click', '.capturar-foto-btn, #clear-button-initial', function() {
        setTimeout(validarCamposRequeridos, 200);
    });

    // Bandera para saber si el usuario abrió la previsualización
    let previsualizado = false;
    // Detectar si es móvil o tablet
    function isMobileOrTablet() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|Windows Phone/i.test(navigator.userAgent) || window.innerWidth < 991;
    }
    // Ocultar el botón de previsualizar y mostrar solo WhatsApp en móvil/tablet al cargar
    if (isMobileOrTablet()) {
        $('#btn-previsualizar-carta').hide();
        // El botón WhatsApp se mostrará tras generar el PDF
    }

    // Función para obtener año y mes actuales en formato string
    function getFechaRuta() {
        const hoy = new Date();
        const anio = hoy.getFullYear();
        // Mes en formato 2 dígitos
        const mes = (hoy.getMonth() + 1).toString().padStart(2, '0');
        return { anio, mes };
    }

    // Manejar clic en botón de previsualización
    $('#btn-previsualizar-carta').on('click', async function() {
        // Guardar el PDF generado para compartir por WhatsApp
        if (isMobileOrTablet()) {
            window._pdfFileWhatsApp = null;
            window._pdfNombreArchivoWhatsApp = null;
        }

        const id_proceso = $('#id_proceso_sst_ext').val();
        const segundo_nombre = $('#segundo_nombre').val().trim();
        const primer_nombre = $('#primer_nombre').val().trim();
        const segundo_apellido = $('#segundo_apellido').val().trim();
        const primer_apellido = $('#primer_apellido').val().trim();
        const documento = $('#numero_documento').val().trim();
        const firma = $('#firma_personal').val();
        const foto = $('#foto_personal').val();

        // Construir nombre completo
        const nombre_completo = [primer_nombre, segundo_nombre, primer_apellido, segundo_apellido]
            .filter(n => n !== '')
            .join(' ');

        const { anio, mes } = getFechaRuta();
        let nombreArchivo = '';
        if (id_proceso == '2') {
            nombreArchivo = 'Certificacion_Induccion_SG-SST_COMERCIALES_' + nombre_completo + '_' + new Date().toISOString().slice(0,10) + '.pdf';
        } else if (id_proceso == '3' || id_proceso == '4') {
            nombreArchivo = 'Certificacion_Induccion_SG-SST_ADMON_CALL_CENTER_' + nombre_completo + '_' + new Date().toISOString().slice(0,10) + '.pdf';
        } else if (id_proceso == '1') {
            nombreArchivo = 'Certificacion_Induccion_SG-SST_LOGISTICA_' + nombre_completo + '_' + new Date().toISOString().slice(0,10) + '.pdf';
        } else {
            jQuery("#modalMessage").text('El proceso seleccionado no tiene una carta asociada');
            jQuery("#modalMessage").css({
                'color': 'red'
            });
            jQuery("#showGeneralModal")[0].click();
            return;
        }

        // Línea solicitada para abrir el PDF generado
        const rutaPDF = BASE_URL + 'files/Inducciones/documento_persona/' + anio + '/' + mes + '/' + documento + '/' + nombreArchivo;

        const formData = {
            primer_nombre: primer_nombre,
            segundo_nombre: segundo_nombre,
            primer_apellido: primer_apellido,
            segundo_apellido: segundo_apellido,
            id_tipo_documento: $('#id_tipo_documento').val(),
            numero_documento: documento,
            id_proceso_sst_ext: id_proceso,
            firma: firma,
            foto: foto
        };

        if (isMobileOrTablet()) {
            $('#btn-previsualizar-carta').hide();
            previsualizado = true;
            LoadingManager.show();
            try {
                const response = await $.ajax({
                    type: 'POST',
                    url: BASE_URL + 'Sst/previsualizar',
                    data: formData
                });
                if (response != 0) {
                    // Abrir el PDF generado en una nueva pestaña para previsualizar o descargar
                    window.open(rutaPDF, '_blank');
                    // Guardar para WhatsApp
                    window._pdfFileWhatsApp = rutaPDF;
                    window._pdfNombreArchivoWhatsApp = nombreArchivo;

                    // Mostrar botón
                    showWhatsAppButton(rutaPDF, nombreArchivo);
                    $('#whatsapp-share-btn').prop('disabled', false);
                } else {
                    throw new Error('Error en la generación del PDF');
                }
            } catch (error) {
                console.error('Error:', error);
                jQuery("#modalMessage").text('Error al generar la previsualización del PDF.');
                jQuery("#modalMessage").css({
                    'color': 'red'
                });
                jQuery("#showGeneralModal")[0].click();
            } finally {
                LoadingManager.hide();
            }
        } else {
            LoadingManager.show();
            try {
                // Solicitar el PDF como blob directamente
                const pdfBlob = await $.ajax({
                    url: BASE_URL + 'Sst/previsualizar',
                    method: 'POST',
                    data: formData,
                    xhrFields: { responseType: 'blob' },
                    processData: true,
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                });
                if (pdfBlob && pdfBlob.size > 1000) {
                    // Descargar automáticamente el PDF
                    const downloadUrl = rutaPDF;
                    const link = document.createElement('a');
                    link.href = downloadUrl;
                    link.download = nombreArchivo;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    // Abrir el PDF generado en una nueva pestaña para previsualizar o descargar
                    window.open(rutaPDF, '_blank');
                    setTimeout(() => {
                        URL.revokeObjectURL(downloadUrl);
                    }, 10000);
                    previsualizado = true;
                } else {
                    throw new Error('Error en la generación del PDF');
                }
            } catch (error) {
                console.error('Error:', error);
                jQuery("#modalMessage").text('Error al generar la previsualización del PDF.');
                jQuery("#modalMessage").css({
                    'color': 'red'
                });
                jQuery("#showGeneralModal")[0].click();
            } finally {
                LoadingManager.hide();
            }
        }
    });

    // Función para mostrar botón de WhatsApp
    function showWhatsAppButton(pdfFile, nombreArchivo) {
        if (window.innerWidth < 800) { // Solo en móvil
            // Limpiar botón anterior si existe
            $('#whatsapp-share-btn').remove();
            
            // Crear nuevo botón
            const btn = $('<button>')
                .attr('id', 'whatsapp-share-btn')
                .addClass('btn btn-success mt-2')
                .css({
                    'width': '100%',
                    'padding': '10px',
                    'font-size': '16px',
                    'border-radius': '8px'
                })
                .html('<i class="fab fa-whatsapp"></i> Compartir por WhatsApp')
                .on('click', async function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    await shareViaWhatsApp(pdfFile, nombreArchivo);
                });
            
            // Insertar después del botón de previsualizar
            $('#btn-previsualizar-carta').after(btn);
        }
    }

    // Función para compartir vía WhatsApp
    async function shareViaWhatsApp(pdfFile, nombreArchivo) {
        try {
            // Intentar usar Web Share API si está disponible
            if (navigator.share) {
                try {
                    await navigator.share({
                        title: 'Certificación SG-SST',
                        text: 'Aquí tienes tu certificado de inducción SG-SST',
                        files: [pdfFile]
                    });
                    return; // Éxito, salir de la función
                } catch (shareError) {
                    console.log('Web Share API falló, intentando alternativa:', shareError);
                    // Continuar con método alternativo
                }
            }
            
            // Método alternativo: crear blob URL y compartir
            const blobUrl = URL.createObjectURL(pdfFile);
            
            // Para móviles: intentar abrir WhatsApp con el archivo
            if (/Android|iPhone|iPad|iPod/i.test(navigator.userAgent)) {
                // En Android/iOS, podemos intentar usar un enfoque diferente
                // Primero guardamos el archivo temporalmente
                const a = document.createElement('a');
                a.href = blobUrl;
                a.download = nombreArchivo;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                
                // Mostrar mensaje instructivo
                setTimeout(() => {
                    alert('El PDF se ha descargado. Ahora puede compartirlo desde WhatsApp:\n\n1. Abra WhatsApp\n2. Seleccione un chat\n3. Toque el clip de adjuntar\n4. Seleccione "Documento"\n5. Busque y seleccione el archivo descargado');
                }, 500);
            } else {
                // En escritorio o navegadores sin soporte
                alert('Para compartir por WhatsApp:\n\n1. El PDF ya se descargó automáticamente\n2. Abra WhatsApp Web/Desktop\n3. Adjunte el archivo descargado\n\nUbicación: Descargas/' + nombreArchivo);
            }
            
            // Liberar el blob URL después de un tiempo
            setTimeout(() => URL.revokeObjectURL(blobUrl), 10000);
            
        } catch (error) {
            console.error('Error al compartir:', error);
            // Mensaje de error simple
            alert('Para compartir el PDF, busque el archivo descargado en su dispositivo y compártalo manualmente desde WhatsApp.\n\nArchivo: ' + nombreArchivo);
        }
    }

    // También puedes agregar esta función para limpiar el botón cuando se deshabilite el de previsualizar
    function clearWhatsAppButton() {
        $('#whatsapp-share-btn').remove();
    }

    // Opcional: Si quieres que el botón desaparezca al recargar o cambiar datos
    $(document).on('input change', '#firma_personal, #foto_personal', function() {
        // Si los campos de firma o foto se modifican, quitar el botón de WhatsApp
        clearWhatsAppButton();
    });
    
    // SUBMIT DEL FORMULARIO INICIAL
    $('#initial-form').on('submit', function(event) {
        // En móvil/tablet, solo permitir guardar si se compartió el PDF por WhatsApp
        if (isMobileOrTablet()) {
            if (!pdfCompartidoWhatsApp) {
                jQuery("#modalMessage").text('Debe compartir el PDF por WhatsApp antes de guardar.');
                jQuery("#modalMessage").css({
                    'color': 'red'
                });
                jQuery("#showGeneralModal")[0].click();
                event.preventDefault();
                return false;
            }
            previsualizado = true;
        } else {
            if (!previsualizado) {
                jQuery("#modalMessage").text('Debe previsualizar el documento antes de poder enviarlo.');
                jQuery("#modalMessage").css({
                    'color': 'red'
                });
                jQuery("#showGeneralModal")[0].click();
                event.preventDefault();
                return false;
            }
        }
        event.preventDefault();

        // Validar firma
        if (!$('#firma_personal').val()) {
            jQuery("#modalMessage").text('Por favor, agregue su firma');
            jQuery("#modalMessage").css({
                'color': 'red'
            });
            jQuery("#showGeneralModal")[0].click();
            return;
        }

        // Validar foto obligatoria
        if (!$('#foto_personal').val()) {
            jQuery("#modalMessage").text('Por favor, capture su foto antes de continuar');
            jQuery("#modalMessage").css({
                'color': 'red'
            });
            jQuery("#showGeneralModal")[0].click();
            return;
        }

        // Recopilar datos del formulario
        const formData = {
            primer_nombre: $.trim($('#primer_nombre').val()),
            segundo_nombre: $.trim($('#segundo_nombre').val()),
            primer_apellido: $.trim($('#primer_apellido').val()),
            segundo_apellido: $.trim($('#segundo_apellido').val()),
            id_tipo_documento: $('#id_tipo_documento').val(),
            numero_documento: $.trim($('#numero_documento').val()),
            id_proceso_sst_ext: $('#id_proceso_sst_ext').val(),
            firma: $('#firma_personal').val(),
            foto: $('#foto_personal').val()
        };

        // Detener cámara antes de continuar
        detenerCamaraFotos();

        // Mostrar la firma en el recuadro de previsualización solo al guardar
        mostrarFirmaPreview($('#firma_personal').val());

        LoadingManager.show();
        $.ajax({
            type: 'POST',
            url: BASE_URL + 'Sst/gestion', // endpoint para guardar definitivo
            data: formData,
            success: function(response) {
                if (response != 0) {
                    jQuery("#modalMessage").text('Registro guardado exitosamente.');
                    jQuery("#modalMessage").css({
                        'color': 'green'
                    });
                    jQuery("#showGeneralModal")[0].click();
                    jQuery('#btnAceptarModal').bind("click", function () {
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
            complete: function() {
                LoadingManager.hide();
            },
            error: function() {
                jQuery("#modalMessage").text('Error en la comunicación con el servidor');
                jQuery("#modalMessage").css({
                    'color': 'red'
                });
                jQuery("#showGeneralModal")[0].click();
            }
        });
    });
});
