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
                    echo json_encode(["success" => true]);
                }else {
                    echo json_encode(["success" => false]);
                }
			}
		}

        //Diligencia de Contratos
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

        //PDF
        public function print_carta($tipoCarta = '') {
            ob_clean();  // Limpia el buffer de salida

            // Obtener datos desde GET
            $nombre_completo = isset($_GET['nombre']) ? $_GET['nombre'] : '';
            $documento = isset($_GET['documento']) ? $_GET['documento'] : '';

            // Validar que se reciban los datos necesarios
            if (empty($nombre_completo) || empty($documento)) {
                http_response_code(400);
                echo "Error: Faltan datos requeridos (nombre y documento)";
                return;
            }

            // Crear array de datos para el PDF
            $datos = array(
                0 => '', // ID (no necesario para previsualización)
                1 => $nombre_completo,
                2 => $documento
            );

            $footerHeight = 120; // px - altura usada en el footer HTML
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'Letter',
                'default_font_size' => 12,
                'default_font' => 'times',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 40,
                // Reservar espacio inferior igual a la altura del footer para que el contenido llegue hasta arriba del footer
                'margin_bottom' => $footerHeight,
                'margin_header' => 10,
                // Usamos margin_footer 0 porque el footer se pintará en el área reservada por margin_bottom
                'margin_footer' => 0
            ]);
            
            // Determinar qué carta imprimir según el tipo
            switch($tipoCarta) {
                case 'comercial':
                    $this->generate_comercial_pdf($mpdf, $datos);
                    break;
                    
                case 'adminYCall':
                    $this->generate_adminYCall_pdf($mpdf, $datos);
                    break;
                    
                default:
                    http_response_code(400);
                    echo "Error: Tipo de carta no reconocido: " . $tipoCarta;
                    break;
            }
        }

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
                    <table style="width: 100%; border-collapse: collapse; margin: 10px 0; border-bottom: 1px solid #e9ecef;">
                        <tr>
                            <td style="width: 33.33%; text-align: center; padding: 10px;">
                                <img src="https://i.ibb.co/1tzHfN61/Hogarjenkins.png" style="height: 60px; max-width: 150px; object-fit: contain;" alt="Logo HYM" />
                            </td>
                            <td style="width: 33.33%; text-align: center; padding: 10px;">
                                <img src="https://i.ibb.co/hJ9TczCb/fami.png" style="height: 60px; max-width: 150px; object-fit: contain;" alt="Logo FAMI" />
                            </td>
                            <td style="width: 33.33%; text-align: center; padding: 10px;">
                                <img src="https://i.ibb.co/7JjbzHD0/alianzas.png" style="height: 60px; max-width: 150px; object-fit: contain;" alt="Logo ALIANZAS" />
                            </td>
                        </tr>
                    </table>
                    
                    <div class="content">
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

            // Construir footer HTML
            $footerHtml = '
                <div style="width:100%; text-align:center;">
                    <img src="' . __DIR__ . '/../Views/Default/img/pie_de_pagina_promotoria.jpg' . '" style="width:100%; height:170px; object-fit:cover;" />
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
                    <table style="width: 100%; border-collapse: collapse; margin: 10px 0; border-bottom: 1px solid #e9ecef;">
                        <tr>
                            <td style="width: 33.33%; text-align: center; padding: 10px;">
                                <img src="https://i.ibb.co/1tzHfN61/Hogarjenkins.png" style="height: 70px; max-width: 180px; object-fit: contain;" alt="Logo HYM" />
                            </td>
                            <td style="width: 33.33%; text-align: center; padding: 10px;">
                                <img src="https://i.ibb.co/DDKX7ZBC/logo-famicredito.jpg" style="height: 70px; max-width: 180px; object-fit: contain;" alt="Logo FAMI" />
                            </td>
                            <td style="width: 33.33%; text-align: center; padding: 10px;">
                                <img src="https://i.ibb.co/7JjbzHD0/alianzas.png" style="height: 70px; max-width: 180px; object-fit: contain;" alt="Logo ALIANZAS" />
                            </td>
                        </tr>
                    </table>
                    
                    <div class="content">
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
                    <img src="' . __DIR__ . '/../Views/Default/img/pie_de_pagina_promotoria.jpg' . '" style="width:100%; height:170px; object-fit:cover;" />
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
	}
 ?>