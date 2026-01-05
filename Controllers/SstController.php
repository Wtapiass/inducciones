<?php 
	session_start();

    require 'vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use PhpOffice\PhpSpreadsheet\IOFactory;
	use PhpOffice\PhpSpreadsheet\Coordinate;
	use Mpdf\Mpdf;

	class SstController extends Controllers {
		public $model;
		public $view;
		
		function __construct() {
			parent::__construct();
		}

        //Validación de acceso.
		public function validateUser() {
			if(isset($_POST['documento'])) {
                $attrAcceso = "@documento=?";
                $myparamsAcceso['@documento'] = $_POST['documento'];
                $paramsAcceso = array(
                    array(&$myparamsAcceso['@documento'])
                );
                $conteno_inducciones = $this->model->execute_sp('dbo.sp_inducciones_sst_ext_select_count', $attrAcceso, $paramsAcceso);
                //Validación de número de inducciones.
                if($conteno_inducciones[0][0] == 3) {
                    echo json_encode(["success" => false]);
                    return;
                }

                $attr = "@documento=?, @token=?";
				$myparams['@documento'] = $_POST['documento'];
				$myparams['@token'] = $_POST['token'];
                $params = array(
                    array(&$myparams['@documento']),
                    array(&$myparams['@token'])
                );
                $inducciones_sst_ext_valide = $this->model->execute_sp('dbo.sp_inducciones_sst_acceso_select_validate', '', array());
                $estado_permiso = $inducciones_sst_ext_valide[0][4];
                $token_acceso = $inducciones_sst_ext_valide[0][3];

                $response = $this->model->execute_sp('dbo.sp_inducciones_sst_ext_log',$attr,$params);

                if($estado_permiso == 1 && $_POST['token'] === $token_acceso) {
                    $_SESSION['user_doc'] = $_POST['documento'];
                    $_SESSION["autentica"] = 'SIP';
                    echo json_encode(["success" => true]);
                }else {
                    echo json_encode(["success" => false]);
                }
			}
		}

		public function gestion() {
            if(isset($_POST['primer_nombre'])) {
                $attr = "@primer_nombre=?, @segundo_nombre=?, @primer_apellido=?, @segundo_apellido=?, @id_tipo_documento=?, @numero_documento=?, @id_sst_ext_proc=?, @firma=?, @foto=?";

                $myparams['@primer_nombre'] = $_POST['primer_nombre'];
                $myparams['@segundo_nombre'] = $_POST['segundo_nombre'];
                $myparams['@primer_apellido'] = $_POST['primer_apellido'];
                $myparams['@segundo_apellido'] = $_POST['segundo_apellido'];
                $myparams['@id_tipo_documento'] = $_POST['id_tipo_documento'];
                $myparams['@numero_documento'] = $_POST['numero_documento'];
                $myparams['@id_sst_ext_proc'] = $_POST['id_proceso_sst_ext'];
                $myparams['@firma'] = $_POST['firma'];
                $myparams['@foto'] = $_POST['foto'];

                $params = array(
                    array(&$myparams['@primer_nombre']),
                    array(&$myparams['@segundo_nombre']),
                    array(&$myparams['@primer_apellido']),
                    array(&$myparams['@segundo_apellido']),
                    array(&$myparams['@id_tipo_documento']),
                    array(&$myparams['@numero_documento']),
                    array(&$myparams['@id_sst_ext_proc']),
                    array(&$myparams['@firma']),
                    array(&$myparams['@foto'])
                );
                $response = $this->model->execute_sp('dbo.sp_inducciones_sst_ext_save',$attr,$params);

                echo 1;
            }else {
                $response = array();
                $tipos_documentos = $this->model->execute_sp('dbo.sp_tipo_documentos_select_all', '', array());
                $inducciones_ext_procesos = $this->model->execute_sp('dbo.sp_inducciones_sst_ext_procesos_select_all', '', array());

                array_push($response, $tipos_documentos, $inducciones_ext_procesos);
                $this->view->render($this, 'gestion', $response);
            }
		}

        public function previsualizar() {
            // === Lógica para guardar el PDF personalizado ===
            $parts = array_map('trim', array(
                isset($_POST['primer_nombre']) ? $_POST['primer_nombre'] : '',
                isset($_POST['segundo_nombre']) ? $_POST['segundo_nombre'] : '',
                isset($_POST['primer_apellido']) ? $_POST['primer_apellido'] : '',
                isset($_POST['segundo_apellido']) ? $_POST['segundo_apellido'] : ''
            ));
            $parts = array_filter($parts, function($v){ return $v !== null && $v !== ''; });
            $nombre_completo = trim(implode(' ', $parts));
            
            $documento = $_POST['numero_documento'];
            $tipoCarta = '';
            if ($_POST['id_proceso_sst_ext'] == '3' || $_POST['id_proceso_sst_ext'] == '4') {
                $tipoCarta = 'adminYCall';
            } else if ($_POST['id_proceso_sst_ext'] == '2') {
                $tipoCarta = 'comercial';
            } else if ($_POST['id_proceso_sst_ext'] == '1') {
                $tipoCarta = 'logistica';
            }

            // Obtener año y mes actuales
            $fechaHoy = new DateTime('now', new DateTimeZone('America/Bogota'));
            $anio = $fechaHoy->format('Y');
            $mes = $fechaHoy->format('m');

            // Generar el PDF en memoria
            $footerHeight = 50; // Altura del área de footer en mm (aproximadamente 170px)
            $baseDir = __DIR__ . '/..';
            $rutaBase = $baseDir . '/files/Inducciones/documento_base/';
            $rutaPersona = $baseDir . "/files/Inducciones/documento_persona/$anio/$mes/$documento/";

            // Crear carpeta si no existe
            if (!is_dir($rutaPersona)) {
                mkdir($rutaPersona, 0777, true);
            }

            // Seleccionar documento base según tipoCarta
            $archivoBase = '';
            if ($tipoCarta === 'comercial') {
                $archivoBase = $rutaBase . 'CERTIFICACION_INDUCCION_REINDUCCION_SG-SST_2025_COMERCIALES.pdf';
            } else if ($tipoCarta === 'adminYCall') {
                $archivoBase = $rutaBase . 'CERTIFICACION_INDUCCION_REINDUCCION_SG-SST_2025_ADMON_CALL_CENTER.pdf';
            } else if ($tipoCarta === 'logistica') {
                $archivoBase = $rutaBase . 'CERTIFICACION_INDUCCION_REINDUCCION_SG-SST_2025_LOGISTICA.pdf';
            }

            // --- NUEVA LÓGICA CON FPDF/FPDI ---
            require_once($baseDir . '/vendor/setasign/fpdf/fpdf.php');
            require_once($baseDir . '/vendor/setasign/fpdi/src/autoload.php');

            $pdf = new \setasign\Fpdi\Fpdi();
            $pdf->SetAutoPageBreak(true, 20);

            // 1. Agregar la carta personalizada como primera hoja
            $mpdfCarta = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'Letter',
                'default_font_size' => 12,
                'default_font' => 'times',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 10,
                'margin_bottom' => $footerHeight,
                'margin_header' => 5,
                'margin_footer' => 5
            ]);
            $datos = array('', $nombre_completo, $documento);
            if ($tipoCarta === 'comercial') {
                $this->generate_comercial_pdf($mpdfCarta, $datos);
            } else if ($tipoCarta === 'adminYCall') {
                $this->generate_adminYCall_pdf($mpdfCarta, $datos);
            } else if ($tipoCarta === 'logistica') {
                $this->generate_logistica_pdf($mpdfCarta, $datos);
            }
            $tmpCarta = tempnam(sys_get_temp_dir(), 'carta_') . '.pdf';
            $mpdfCarta->Output($tmpCarta, \Mpdf\Output\Destination::FILE);
            $pageCountCarta = $pdf->setSourceFile($tmpCarta);
            for ($i = 1; $i <= $pageCountCarta; $i++) {
                $tplIdx = $pdf->importPage($i);
                $pdf->AddPage();
                $pdf->useTemplate($tplIdx);
            }

            // 2. Agregar todas las páginas del documento base
            if (file_exists($archivoBase) && filesize($archivoBase) > 1000) {
                $pageCountBase = $pdf->setSourceFile($archivoBase);
                for ($i = 1; $i <= $pageCountBase; $i++) {
                    $tplIdx = $pdf->importPage($i);
                    $pdf->AddPage();
                    $pdf->useTemplate($tplIdx);
                }
            }

            // 3. Agregar hoja de firma y foto al final
            $pdf->AddPage();
            $pdf->SetFont('Arial', '', 12);
            $pdf->Ln(10);

            // Preparar imágenes y títulos
            $firmaOk = false;
            $fotoOk = false;
            $tmpFirma = '';
            $tmpFoto = '';
            if (!empty($_POST['firma'])) {
                $firmaData = $_POST['firma'];
                if (strpos($firmaData, 'base64,') !== false) {
                    $firmaData = explode('base64,', $firmaData)[1];
                }
                $firmaImg = base64_decode($firmaData);
                $tmpFirma = tempnam(sys_get_temp_dir(), 'firma_') . '.png';
                file_put_contents($tmpFirma, $firmaImg);
                $firmaOk = true;
            }
            if (!empty($_POST['foto'])) {
                $fotoData = $_POST['foto'];
                if (strpos($fotoData, 'base64,') !== false) {
                    $fotoData = explode('base64,', $fotoData)[1];
                }
                $fotoImg = base64_decode($fotoData);
                $tmpFoto = tempnam(sys_get_temp_dir(), 'foto_') . '.jpg';
                file_put_contents($tmpFoto, $fotoImg);
                $fotoOk = true;
            }

            // Tabla de dos columnas: Firma | Foto (sin bordes visibles)
            $tableY = $pdf->GetY();
            $tableWidth = 160;
            $colWidth = $tableWidth / 2;
            $imgHeight = 50;
            $imgFirmaWidth = 50;
            $imgFotoWidth = 60;
            $startX = ($pdf->GetPageWidth() - $tableWidth) / 2;

            // Títulos
            $pdf->SetXY($startX, $tableY);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell($colWidth, 8, 'Firma', 0, 0, 'C');
            $pdf->Cell($colWidth, 8, 'Foto', 0, 1, 'C');

            // Imágenes
            $imgY = $pdf->GetY();
            $pdf->SetFont('Arial', '', 12);
            if ($firmaOk) {
                $pdf->SetXY($startX + ($colWidth - $imgFirmaWidth) / 2, $imgY);
                $pdf->Image($tmpFirma, $pdf->GetX(), $pdf->GetY(), $imgFirmaWidth, $imgHeight);
            }
            if ($fotoOk) {
                $pdf->SetXY($startX + $colWidth + ($colWidth - $imgFotoWidth) / 2, $imgY);
                $pdf->Image($tmpFoto, $pdf->GetX(), $pdf->GetY(), $imgFotoWidth, $imgHeight);
            }

            // Limpiar temporales
            if ($firmaOk && file_exists($tmpFirma)) @unlink($tmpFirma);
            if ($fotoOk && file_exists($tmpFoto)) @unlink($tmpFoto);

            // Espacio debajo de la tabla
            $pdf->SetY($imgY + $imgHeight + 4);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 8, $nombre_completo, 0, 1, 'C');
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 8, 'Documento: ' . $documento, 0, 1, 'C');

            // Fecha y hora local Colombia
            $pdf->SetFont('Arial', '', 11);
            try {
                $dt = new DateTime('now', new DateTimeZone('America/Bogota'));
                $fechaHora = $dt->format('d/m/Y H:i');
            } catch (Exception $e) {
                $fechaHora = '';
            }
            $fecha_realizacion_text = 'Fecha y hora: ' . $fechaHora;
            $pdf->Cell(0, 8, $fecha_realizacion_text, 0, 1, 'C');

            // Eliminar temporal de carta
            if (file_exists($tmpCarta)) @unlink($tmpCarta);

            // Guardar PDF final en la carpeta de la persona
            if($tipoCarta === 'comercial') {
                $nombreArchivo = 'Certificacion_Induccion_SG-SST_COMERCIALES_' . $nombre_completo . '_' . date('Y-m-d') . '.pdf';
            }else if($tipoCarta === 'adminYCall') {
                $nombreArchivo = 'Certificacion_Induccion_SG-SST_ADMON_CALL_CENTER_' . $nombre_completo . '_' . date('Y-m-d') . '.pdf';
            }else if($tipoCarta === 'logistica') {
                $nombreArchivo = 'Certificacion_Induccion_SG-SST_LOGISTICA_' . $nombre_completo . '_' . date('Y-m-d') . '.pdf';
            }

            $rutaPDF = $rutaPersona . $nombreArchivo;
            $pdf->Output($rutaPDF, 'F');
        }

        //PDF
        private function generate_comercial_pdf($mpdf, $datos) {
            setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'es');

            $html = '
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <style>
                    @page {
                        margin: 0;
                        padding: 0;
                    }
                    
                    body { 
                        font-family: "Arial", serif; 
                        line-height: 1.5; 
                        color: #333;
                        font-size: 10pt;
                        margin: 0;
                        padding: 0;
                        width: 100%;
                        min-height: 100vh;
                        position: relative;
                    }

                    .header-image {
                        width: 100%;
                        height: 230px;
                        object-fit: cover;
                        display: block;
                    }

                    .signature { 
                        object-fit: contain;
                        text-align: center;
                        margin: 5px 0;
                    }
                    
                    .signature-image {
                        width: 300px;
                        height: auto;
                        max-width: 100%;
                    }
                    
                    .content {
                        padding: 10px 30px 8px 30px;
                        margin-top: -100px;
                        position: relative;
                        z-index: 1;
                        min-height: 0;
                    }

                    /* Compactar todo */
                    p {
                        margin: 5px 0;
                        line-height: 1.2;
                    }

                    ul {
                        margin: 5px 0;
                        padding-left: 15px;
                    }

                    li {
                        margin: 2px 0;
                    }

                    .compact-section {
                        margin: 8px 0;
                    }

                    strong {
                        font-weight: bold;
                    }

                    .page-container {
                        display: flex;
                        flex-direction: column;
                        min-height: 100vh;
                    }
                    
                    .logos-container {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        gap: 15px;
                        padding: 10px 20px;
                        background: #ffffff;
                        border-bottom: 1px solid #e9ecef;
                    }
                    
                    .logo-item {
                        text-align: center;
                    }
                    
                    .logo-item img {
                        width: auto;
                        max-width: 120px;
                        height: 45px;
                        object-fit: contain;
                    }

                    @media print {
                        .logos-container {
                            display: flex !important;
                            justify-content: center !important;
                            align-items: center !important;
                            gap: 15px !important;
                            padding: 10px 20px !important;
                            background: #ffffff !important;
                            border-bottom: 1px solid #e9ecef !important;
                        }
                        
                        .logo-item {
                            text-align: center !important;
                        }
                        
                        .logo-item img {
                            width: auto !important;
                            max-width: 120px !important;
                            height: 45px !important;
                            object-fit: contain !important;
                        }
                    }

                    /* Ajustes para contenido extenso */
                    .auto-adjust {
                        page-break-inside: avoid;
                        break-inside: avoid;
                    }
                    
                    .title {
                        text-align: center;
                        font-size: 14pt;
                        font-weight: bold;
                        margin: 15px 0 10px 0;
                    }
                    
                    .subtitle {
                        font-weight: bold;
                        margin: 8px 0 5px 0;
                    }
                    
                    .document-info {
                        margin: 10px 0;
                        padding: 10px;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                        background-color: #f9f9f9;
                    }
                    
                    .standards-list {
                        margin-left: 20px;
                    }
                </style>
            </head>
            <body>
                <div class="page-container">
                    <table style="width: 100%; border-collapse: collapse; border-bottom: 1px solid #e9ecef; background: white;">
                        <tr>
                            <td style="width: 33.33%; text-align: center; padding: 10px 5px;">
                                <img src="' . __DIR__ . '/../Views/Default/img/logo-hym.jpg" style="height: 70px; max-width: 180px; object-fit: contain;" alt="Logo HYM" />
                            </td>
                            <td style="width: 33.33%; text-align: center; padding: 10px 5px;">
                                <img src="' . __DIR__ . '/../Views/Default/img/fami.jpg" style="height: 70px; max-width: 180px; object-fit: contain;" alt="Logo FAMI" />
                            </td>
                            <td style="width: 33.33%; text-align: center; padding: 10px 5px;">
                                <img src="' . __DIR__ . '/../Views/Default/img/alianzas.jpg" style="height: 70px; max-width: 180px; object-fit: contain;" alt="Logo Alianzas" />
                            </td>
                        </tr>
                    </table>
                    
                    <div class="content" style="margin-top: 3px;">
                        <div class="title">TEMAS ABORDADOS EN LA CAPACITACIÓN SG-SST</div>
                        
                        <div class="compact-section auto-adjust">
                            <p>Se certifica que el colaborador recibió la formación obligatoria sobre los siguientes módulos fundamentales del Sistema de Gestión de la Seguridad y Salud en el Trabajo:</p>
                        </div>
                        
                        <div class="compact-section auto-adjust">
                            <ol style="margin: 5px 0; padding-left: 20px;">
                                <li><strong>Generalidades del SG-SST:</strong> Objetivo, alcance y definición del Sistema.</li>
                                <li><strong>Plan de Emergencia:</strong> Concepto, cadena de llamada, equipos y grupos de emergencia en PDV y Sede Principal.</li>
                                <li><strong>Políticas de SST:</strong> Presentación y comprensión de las políticas generales de la empresa.</li>
                                <li><strong>Política de Prevención de Sustancias Psicoactivas.</strong></li>
                                <li><strong>Lineamientos Generales para el Personal Interno.</strong></li>
                                <li><strong>Responsabilidades del colaborador en el SG-SST.</strong></li>
                                <li><strong>Concepto de Peligro, Riesgo y Medidas de Control.</strong></li>
                            </ol>
                        </div>
                        
                        <div class="compact-section auto-adjust">
                            <p class="subtitle">Estándares de seguridad que apliquen de acuerdo con el cargo:</p>
                            <p class="subtitle">Comercial</p>
                            <ul class="standards-list">
                                <li>Estándar de desplazamiento seguro.</li>
                                <li>Estándar de Uso, manejo y almacenamiento de escalera tipo tijera.</li>
                                <li>Estándar para cargos en PDV (administradores, cajeros y asesores).</li>
                                <li>Estándar de manipulación de puerta reja (apertura y cierre de punto de venta).</li>
                            </ul>
                        </div>
                        
                        <div class="compact-section auto-adjust">
                            <ol start="8" style="margin: 5px 0; padding-left: 20px;">
                                <li><strong>Equipos del SG-SST:</strong> Funcionamiento y rol del COPASST y COCOLAB.</li>
                                <li><strong>Concepto de Accidente de Trabajo (AT)</strong> y sus multicausalidades.</li>
                                <li><strong>Concepto de Incidente de Trabajo.</strong></li>
                                <li><strong>Concepto de Enfermedad Laboral (EL).</strong></li>
                                <li><strong>Procedimiento para Reportar un Accidente de Trabajo.</strong></li>
                                <li><strong>Salas Amigas para la Lactancia:</strong> Conocimiento de su existencia y uso.</li>
                                <li><strong>Manejo de Residuos Sólidos</strong> en las instalaciones de la Compañía.</li>
                            </ol>
                        </div>
                        
                        <div class="title" style="margin-top: 20px;">CONSTANCIA DE RECIBIDO Y COMPROMISO</div>
                        
                        <div class="compact-section auto-adjust">
                            <p>El (La) colaborador(a) <strong>' . $datos[1] . '</strong> identificado (a) con número de documento <strong>' . $datos[2] . '</strong> declara que ha recibido y entendido los contenidos sobre Seguridad y Salud en el Trabajo presentados en la inducción/reinducción, y se compromete a cumplir con los procedimientos, normas y responsabilidades establecidos por la empresa para la prevención de accidentes, incidentes y enfermedades laborales.</p>
                        </div>
                        
                        <div class="compact-section auto-adjust">
                            <p style="margin-top: 15px;"><strong>Fecha de capacitación:</strong> ' . strftime('%d de %B de %Y') . '</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>';

            $mpdf->WriteHTML($html);

            // Construir footer HTML - posicionado en la parte inferior
            $footerHtml = '
                <div style="width:100%; text-align:center; position:absolute; bottom:0; left:0; right:0;">
                    <img src="' . __DIR__ . '/../Views/Default/img/pie_de_pagina_promotoria.jpg' . '" style="width:100%; height:auto; display:block;" />
                </div>';

            // Asignar footer
            $mpdf->SetHTMLFooter($footerHtml);

            $nombreArchivo = 'Certificacion_Induccion_SG-SST_' . $datos[1] . '_' . date('Y-m-d') . '.pdf';

            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $nombreArchivo . '"');
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');

            $mpdf->Output($nombreArchivo, 'I');
        }

        private function generate_adminYCall_pdf($mpdf, $datos) {
            setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'es');

            $html = '
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <style>
                    @page {
                        margin: 0;
                        padding: 0;
                    }
                    
                    body { 
                        font-family: "Arial", serif; 
                        line-height: 1.5; 
                        color: #333;
                        font-size: 10pt;
                        margin: 0;
                        padding: 0;
                        width: 100%;
                        min-height: 100vh;
                        position: relative;
                    }

                    .header-image {
                        width: 100%;
                        height: 230px;
                        object-fit: cover;
                        display: block;
                    }

                    .signature { 
                        object-fit: contain;
                        text-align: center;
                        margin: 5px 0;
                    }
                    
                    .signature-image {
                        width: 300px;
                        height: auto;
                        max-width: 100%;
                    }
                    
                    .content {
                        padding: 10px 30px 8px 30px;
                        margin-top: -100px;
                        position: relative;
                        z-index: 1;
                        min-height: 0;
                    }

                    /* Compactar todo */
                    p {
                        margin: 5px 0;
                        line-height: 1.2;
                    }

                    ul {
                        margin: 5px 0;
                        padding-left: 15px;
                    }

                    li {
                        margin: 2px 0;
                    }

                    .compact-section {
                        margin: 8px 0;
                    }

                    strong {
                        font-weight: bold;
                    }

                    .page-container {
                        display: flex;
                        flex-direction: column;
                        min-height: 100vh;
                    }
                    
                    .logos-container {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        gap: 15px;
                        padding: 10px 20px;
                        background: #ffffff;
                        border-bottom: 1px solid #e9ecef;
                    }
                    
                    .logo-item {
                        text-align: center;
                    }
                    
                    .logo-item img {
                        width: auto;
                        max-width: 120px;
                        height: 45px;
                        object-fit: contain;
                    }

                    @media print {
                        .logos-container {
                            display: flex !important;
                            justify-content: center !important;
                            align-items: center !important;
                            gap: 15px !important;
                            padding: 10px 20px !important;
                            background: #ffffff !important;
                            border-bottom: 1px solid #e9ecef !important;
                        }
                        
                        .logo-item {
                            text-align: center !important;
                        }
                        
                        .logo-item img {
                            width: auto !important;
                            max-width: 120px !important;
                            height: 45px !important;
                            object-fit: contain !important;
                        }
                    }

                    /* Ajustes para contenido extenso */
                    .auto-adjust {
                        page-break-inside: avoid;
                        break-inside: avoid;
                    }
                    
                    .title {
                        text-align: center;
                        font-size: 14pt;
                        font-weight: bold;
                        margin: 15px 0 10px 0;
                    }
                    
                    .subtitle {
                        font-weight: bold;
                        margin: 8px 0 5px 0;
                    }
                    
                    .document-info {
                        margin: 10px 0;
                        padding: 10px;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                        background-color: #f9f9f9;
                    }
                    
                    .standards-list {
                        margin-left: 20px;
                    }
                </style>
            </head>
            <body>
                <div class="page-container">
                    <table style="width: 100%; border-collapse: collapse; border-bottom: 1px solid #e9ecef; background: white;">
                        <tr>
                            <td style="width: 33.33%; text-align: center; padding: 10px 5px;">
                                <img src="' . __DIR__ . '/../Views/Default/img/logo-hym.jpg" style="height: 70px; max-width: 180px; object-fit: contain;" alt="Logo HYM" />
                            </td>
                            <td style="width: 33.33%; text-align: center; padding: 10px 5px;">
                                <img src="' . __DIR__ . '/../Views/Default/img/fami.jpg" style="height: 70px; max-width: 180px; object-fit: contain;" alt="Logo FAMI" />
                            </td>
                            <td style="width: 33.33%; text-align: center; padding: 10px 5px;">
                                <img src="' . __DIR__ . '/../Views/Default/img/alianzas.jpg" style="height: 70px; max-width: 180px; object-fit: contain;" alt="Logo Alianzas" />
                            </td>
                        </tr>
                    </table>

                    <div class="content" style="margin-top: 3px;">
                        <div class="title">TEMAS ABORDADOS EN LA CAPACITACIÓN SG-SST</div>
                        
                        <div class="compact-section auto-adjust">
                            <p>Se certifica que el colaborador recibió la formación obligatoria sobre los siguientes módulos fundamentales del Sistema de Gestión de la Seguridad y Salud en el Trabajo:</p>
                        </div>
                        
                        <div class="compact-section auto-adjust">
                            <ol style="margin: 5px 0; padding-left: 20px;">
                                <li><strong>Generalidades del SG-SST:</strong> Objetivo, alcance y definición del Sistema.</li>
                                <li><strong>Plan de Emergencia:</strong> Concepto, cadena de llamada, equipos y grupos de emergencia en PDV y Sede Principal.</li>
                                <li><strong>Políticas de SST:</strong> Presentación y comprensión de las políticas generales de la empresa.</li>
                                <li><strong>Política de Prevención de Sustancias Psicoactivas.</strong></li>
                                <li><strong>Lineamientos Generales para el Personal Interno.</strong></li>
                                <li><strong>Responsabilidades del colaborador en el SG-SST.</strong></li>
                                <li><strong>Concepto de Peligro, Riesgo y Medidas de Control.</strong></li>
                            </ol>
                        </div>
                        
                        <div class="compact-section auto-adjust">
                            <p class="subtitle">Estándares de seguridad que apliquen de acuerdo con el cargo:</p>
                            <p class="subtitle">Administrativos y Call Center</p>
                            <ul class="standards-list">
                                <li>Estándar para cargos administrativos.</li>
                                <li>Estándar de desplazamiento seguro.</li>
                                <li>Estándar de Uso, manejo y almacenamiento de escalera tipo tijera.</li>
                            </ul>
                        </div>
                        
                        <div class="compact-section auto-adjust">
                            <ol start="8" style="margin: 5px 0; padding-left: 20px;">
                                <li><strong>Equipos del SG-SST:</strong> Funcionamiento y rol del COPASST y COCOLAB.</li>
                                <li><strong>Concepto de Accidente de Trabajo (AT)</strong> y sus multicausalidades.</li>
                                <li><strong>Concepto de Incidente de Trabajo.</strong></li>
                                <li><strong>Concepto de Enfermedad Laboral (EL).</strong></li>
                                <li><strong>Procedimiento para Reportar un Accidente de Trabajo.</strong></li>
                                <li><strong>Salas Amigas para la Lactancia:</strong> Conocimiento de su existencia y uso.</li>
                                <li><strong>Manejo de Residuos Sólidos</strong> en las instalaciones de la Compañía.</li>
                            </ol>
                        </div>
                        
                        <div class="title" style="margin-top: 20px;">CONSTANCIA DE RECIBIDO Y COMPROMISO</div>
                        
                        <div class="compact-section auto-adjust">
                            <p>El (La) colaborador(a) <strong>' . $datos[1] . '</strong> identificado (a) con número de documento <strong>' . $datos[2] . '</strong> declara que ha recibido y entendido los contenidos sobre Seguridad y Salud en el Trabajo presentados en la inducción/reinducción, y se compromete a cumplir con los procedimientos, normas y responsabilidades establecidos por la empresa para la prevención de accidentes, incidentes y enfermedades laborales.</p>
                        </div>
                        
                        <div class="compact-section auto-adjust">
                            <p style="margin-top: 15px;"><strong>Fecha de capacitación:</strong> ' . strftime('%d de %B de %Y') . '</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>';

            $mpdf->WriteHTML($html);

            // Construir footer HTML
            $footerHtml = '
                <div style="width:100%; text-align:center;">
                    <img src="' . __DIR__ . '/../Views/Default/img/pie_de_pagina_promotoria.jpg" style="width:100%; height:170px; object-fit:cover;" />
                </div>';

            // Asignar footer
            $mpdf->SetHTMLFooter($footerHtml);


            $nombreArchivo = 'Certificacion_Induccion_SG-SST_Admon_CallCenter_' . $datos[1] . '_' . date('Y-m-d') . '.pdf';

            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $nombreArchivo . '"');
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');

            $mpdf->Output($nombreArchivo, 'I');
        }

        private function generate_logistica_pdf($mpdf, $datos) {
            setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'es');

            $html = '
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <style>
                    @page {
                        margin: 0;
                        padding: 0;
                    }
                    
                    body { 
                        font-family: "Arial", serif; 
                        line-height: 1.5; 
                        color: #333;
                        font-size: 10pt;
                        margin: 0;
                        padding: 0;
                        width: 100%;
                        min-height: 100vh;
                        position: relative;
                    }

                    .header-image {
                        width: 100%;
                        height: 230px;
                        object-fit: cover;
                        display: block;
                    }

                    .signature { 
                        object-fit: contain;
                        text-align: center;
                        margin: 5px 0;
                    }
                    
                    .signature-image {
                        width: 300px;
                        height: auto;
                        max-width: 100%;
                    }
                    
                    .content {
                        padding: 10px 30px 8px 30px;
                        margin-top: -100px;
                        position: relative;
                        z-index: 1;
                        min-height: 0;
                    }

                    /* Compactar todo */
                    p {
                        margin: 5px 0;
                        line-height: 1.2;
                    }

                    ul {
                        margin: 5px 0;
                        padding-left: 15px;
                    }

                    li {
                        margin: 2px 0;
                    }

                    .compact-section {
                        margin: 8px 0;
                    }

                    strong {
                        font-weight: bold;
                    }

                    .page-container {
                        display: flex;
                        flex-direction: column;
                        min-height: 100vh;
                    }
                    
                    .logos-container {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        gap: 15px;
                        padding: 10px 20px;
                        background: #ffffff;
                        border-bottom: 1px solid #e9ecef;
                    }
                    
                    .logo-item {
                        text-align: center;
                    }
                    
                    .logo-item img {
                        width: auto;
                        max-width: 120px;
                        height: 45px;
                        object-fit: contain;
                    }

                    @media print {
                        .logos-container {
                            display: flex !important;
                            justify-content: center !important;
                            align-items: center !important;
                            gap: 15px !important;
                            padding: 10px 20px !important;
                            background: #ffffff !important;
                            border-bottom: 1px solid #e9ecef !important;
                        }
                        
                        .logo-item {
                            text-align: center !important;
                        }
                        
                        .logo-item img {
                            width: auto !important;
                            max-width: 120px !important;
                            height: 45px !important;
                            object-fit: contain !important;
                        }
                    }

                    /* Ajustes para contenido extenso */
                    .auto-adjust {
                        page-break-inside: avoid;
                        break-inside: avoid;
                    }
                    
                    .title {
                        text-align: center;
                        font-size: 14pt;
                        font-weight: bold;
                        margin: 15px 0 10px 0;
                    }
                    
                    .subtitle {
                        font-weight: bold;
                        margin: 8px 0 5px 0;
                    }
                    
                    .document-info {
                        margin: 10px 0;
                        padding: 10px;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                        background-color: #f9f9f9;
                    }
                    
                    .standards-list {
                        margin-left: 20px;
                    }
                </style>
            </head>
            <body>
                <div class="page-container">
                    <table style="width: 100%; border-collapse: collapse; border-bottom: 1px solid #e9ecef; background: white;">
                        <tr>
                            <td style="width: 33.33%; text-align: center; padding: 10px 5px;">
                                <img src="' . __DIR__ . '/../Views/Default/img/logo-hym.jpg" style="height: 70px; max-width: 180px; object-fit: contain;" alt="Logo HYM" />
                            </td>
                            <td style="width: 33.33%; text-align: center; padding: 10px 5px;">
                                <img src="' . __DIR__ . '/../Views/Default/img/fami.jpg" style="height: 70px; max-width: 180px; object-fit: contain;" alt="Logo FAMI" />
                            </td>
                            <td style="width: 33.33%; text-align: center; padding: 10px 5px;">
                                <img src="' . __DIR__ . '/../Views/Default/img/alianzas.jpg" style="height: 70px; max-width: 180px; object-fit: contain;" alt="Logo Alianzas" />
                            </td>
                        </tr>
                    </table>

                    <div class="content" style="margin-top: 3px;">
                        <div class="title">TEMAS ABORDADOS EN LA CAPACITACIÓN SG-SST</div>
                        
                        <div class="compact-section auto-adjust">
                            <p>Se certifica que el colaborador recibió la formación obligatoria sobre los siguientes módulos fundamentales del Sistema de Gestión de la Seguridad y Salud en el Trabajo:</p>
                        </div>
                        
                        <div class="compact-section auto-adjust">
                            <ol style="margin: 5px 0; padding-left: 20px;">
                                <li><strong>Generalidades del SG-SST:</strong> Objetivo, alcance y definición del Sistema.</li>
                                <li><strong>Plan de Emergencia:</strong> Concepto, cadena de llamada, equipos y grupos de emergencia en PDV y Sede Principal.</li>
                                <li><strong>Políticas de SST:</strong> Presentación y comprensión de las políticas generales de la empresa.</li>
                                <li><strong>Política de Prevención de Sustancias Psicoactivas.</strong></li>
                                <li><strong>Lineamientos Generales para el Personal Interno.</strong></li>
                                <li><strong>Responsabilidades del colaborador en el SG-SST.</strong></li>
                                <li><strong>Concepto de Peligro, Riesgo y Medidas de Control.</strong></li>
                            </ol>
                        </div>
                        
                        <div class="compact-section auto-adjust">
                            <p class="subtitle">Estándares de seguridad que apliquen de acuerdo con el cargo:</p>
                            <p class="subtitle">Logística</p>
                            <ul class="standards-list">
                                <li>Estándar de desplazamiento seguro.</li>
                                <li>Estándar de Uso, manejo y almacenamiento de escalera tipo tijera.</li>
                            </ul>
                        </div>
                        
                        <div class="compact-section auto-adjust">
                            <ol start="8" style="margin: 5px 0; padding-left: 20px;">
                                <li><strong>Equipos del SG-SST:</strong> Funcionamiento y rol del COPASST y COCOLAB.</li>
                                <li><strong>Concepto de Accidente de Trabajo (AT)</strong> y sus multicausalidades.</li>
                                <li><strong>Concepto de Incidente de Trabajo.</strong></li>
                                <li><strong>Concepto de Enfermedad Laboral (EL).</strong></li>
                                <li><strong>Procedimiento para Reportar un Accidente de Trabajo.</strong></li>
                                <li><strong>Salas Amigas para la Lactancia:</strong> Conocimiento de su existencia y uso.</li>
                                <li><strong>Manejo de Residuos Sólidos</strong> en las instalaciones de la Compañía.</li>
                            </ol>
                        </div>
                        
                        <div class="title" style="margin-top: 20px;">CONSTANCIA DE RECIBIDO Y COMPROMISO</div>
                        
                        <div class="compact-section auto-adjust">
                            <p>El (La) colaborador(a) <strong>' . $datos[1] . '</strong> identificado (a) con número de documento <strong>' . $datos[2] . '</strong> declara que ha recibido y entendido los contenidos sobre Seguridad y Salud en el Trabajo presentados en la inducción/reinducción, y se compromete a cumplir con los procedimientos, normas y responsabilidades establecidos por la empresa para la prevención de accidentes, incidentes y enfermedades laborales.</p>
                        </div>
                        
                        <div class="compact-section auto-adjust">
                            <p style="margin-top: 15px;"><strong>Fecha de capacitación:</strong> ' . strftime('%d de %B de %Y') . '</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>';

            $mpdf->WriteHTML($html);

            // Construir footer HTML
            $footerHtml = '
                <div style="width:100%; text-align:center;">
                    <img src="' . __DIR__ . '/../Views/Default/img/pie_de_pagina_promotoria.jpg" style="width:100%; height:170px; object-fit:cover;" />
                </div>';

            // Asignar footer
            $mpdf->SetHTMLFooter($footerHtml);


            $nombreArchivo = 'Certificacion_Induccion_SG-SST_Logistica_' . $datos[1] . '_' . date('Y-m-d') . '.pdf';

            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $nombreArchivo . '"');
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');

            $mpdf->Output($nombreArchivo, 'I');
        }

        private function anexarFirmaFotoPDF($mpdf, $firmaBase64, $fotoBase64, $nombreColaborador = 'Colaborador', $documentoColaborador = '') {
            try {
                // Decodificar imágenes base64
                $firmaImg = '';
                $fotoImg = '';
                if ($firmaBase64) {
                    $firmaImg = '<img src="data:image/png;base64,' . $firmaBase64 . '" style="width:220px; height:auto; display:block; margin:0 auto;" />';
                }
                if ($fotoBase64) {
                    $fotoImg = '<img src="data:image/png;base64,' . $fotoBase64 . '" style="width:120px; height:120px; border-radius:8px; object-fit:cover; display:block; margin:0 auto;" />';
                }

                // Contenido de la página de firma y foto
                $html = '
                <div style="width:100%; min-height:100vh; display:flex; flex-direction:column; justify-content:center; align-items:center;">
                    <h2 style="margin-bottom:18px; font-size:18pt;">Firma y Foto de la Inducción</h2>
                    <div style="margin-bottom:10px; font-size:13pt; font-weight:600;">' . htmlspecialchars($nombreColaborador) . '</div>
                    <div style="margin-bottom:18px; font-size:11pt;">Documento: ' . htmlspecialchars($documentoColaborador) . '</div>
                    <div style="display:flex; flex-direction:row; gap:60px; justify-content:center; align-items:center; margin-bottom:30px;">
                        <div style="text-align:center;">
                            <div style="font-weight:600; margin-bottom:8px;">Firma</div>
                            ' . $firmaImg . '
                        </div>
                        <div style="text-align:center;">
                            <div style="font-weight:600; margin-bottom:8px;">Foto</div>
                            ' . $fotoImg . '
                        </div>
                    </div>
                    <div style="margin-top:30px; font-size:10pt; color:#555;">Fecha de generación: ' . date('Y-m-d H:i') . '</div>
                </div>';

                // Agregar salto de página si no hay espacio suficiente en la última hoja
                $mpdf->AddPage();
                $mpdf->WriteHTML($html);
            } catch (\Exception $e) {
                error_log('Error anexando firma/foto al PDF: ' . $e->getMessage());
            }
        }
	}
 ?>