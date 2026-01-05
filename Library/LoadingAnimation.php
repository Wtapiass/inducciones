<?php
class LoadingAnimation {
    private $config;

    public function __construct($config = []) {
        $defaultConfig = [
            'text' => 'Cargando',
            'color' => '#007bff',
            'fontSize' => '30px',
            'id' => 'loadingMessage'
        ];

        $this->config = array_merge($defaultConfig, $config);
    }

    public function generateHTML() {
        $html = '
        <div id="' . $this->config['id'] . '" style="display: none; background-color: rgba(0, 0, 0, 0.8); position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
            <div class="spinner"></div>
            <div id="loading_text" class="loading-text">
                ' . htmlspecialchars($this->config['text']) . '<span>.</span><span>.</span><span>.</span>
            </div>
        </div>';
        return $html;
    }

    public function generateCSS() {
        $css = '
        <style>
        /* Barra cargando */
        #loadingMessage {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            /* Fondo más oscuro para mayor contraste */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            /* Asegura que el div esté por encima de todo */
            color: #007bff;
            /* Color del texto */
            font-size: 24px;
            /* Tamaño de fuente más grande */
            font-weight: bold;
            text-align: center;
        }

        .spinner {
            position: relative;
            border: 12px solid rgba(0, 123, 255, 0.2);
            /* Borde más grueso y suave */
            border-top: 12px solid #007bff;
            /* Azul intenso para el borde superior */
            border-radius: 50%;
            width: 180px;
            /* Tamaño más grande */
            height: 180px;
            /* Tamaño más grande */
            animation: spin 1.5s cubic-bezier(0.4, 0, 0.2, 1) infinite;
            /* Animación suave */
            box-shadow: 0px 8px 20px rgba(0, 123, 255, 0.4);
            /* Sombra más pronunciada */
        }

        /* Animación de rotación */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Animación de los puntos */
        .loading-text {
            position: absolute;
            /* Fijamos el texto en el centro */
            color: #007bff;
            /* Color del texto */
            font-size: 30px;
            /* Tamaño más grande para el texto */
            z-index: 2;
            /* Asegura que el texto esté por encima */
            pointer-events: none;
            /* Hace que el texto no se vea afectado por la animación */
            font-weight: bold;
            /* Hacer el texto más destacado */
            letter-spacing: 2px;
            /* Espaciado entre letras más amplio */
            top: 50%;
            /* Centrar verticalmente */
            left: 50%;
            /* Centrar horizontalmente */
            transform: translate(-50%, -50%);
            /* Ajuste fino para centrar */
        }

        /* Animación de puntos */
        .loading-text span {
            opacity: 0;
            animation: dotAnimation 1.5s infinite steps(1) forwards;
        }

        .loading-text span:nth-child(1) {
            animation-delay: 0.2s;
        }

        .loading-text span:nth-child(2) {
            animation-delay: 0.4s;
        }

        .loading-text span:nth-child(3) {
            animation-delay: 0.6s;
        }

        @keyframes dotAnimation {
            0% {
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }
        </style>';

        return $css;
    }

    public function generateJavaScript() {
        $js = '
        <script>
        const LoadingManager = {
            show: function() {
                $("#' . $this->config['id'] . '").show();
            },
            
            hide: function() {
                $("#' . $this->config['id'] . '").hide();
            },
            
            // Mostrar con texto personalizado
            showWithText: function(text) {
                const loadingElement = document.getElementById("' . $this->config['id'] . '");
                if (loadingElement) {
                    const textElement = loadingElement.querySelector(".loading-text");
                    if (textElement) {
                        // Conservar la estructura EXACTA con los spans de puntos
                        textElement.innerHTML = text + \'<span>.</span><span>.</span><span>.</span>\';
                    }
                    loadingElement.style.display = "block";
                }
            },
            
            // Versión vanilla JavaScript
            showVanilla: function() {
                const loading = document.getElementById("' . $this->config['id'] . '");
                if (loading) loading.style.display = "block";
            },
            
            hideVanilla: function() {
                const loading = document.getElementById("' . $this->config['id'] . '");
                if (loading) loading.style.display = "none";
            },
            
            // Ocultar después de un tiempo
            hideAfter: function(ms) {
                setTimeout(() => {
                    this.hide();
                }, ms);
            },
            
            // Mostrar con texto temporal
            showTemporary: function(text, ms) {
                this.showWithText(text);
                this.hideAfter(ms);
            }
        };
        </script>';

        return $js;
    }

    public function render() {
        return $this->generateCSS() . $this->generateHTML() . $this->generateJavaScript();
    }

    // Método para obtener solo el HTML (cuando ya tienes los estilos en CSS)
    public function renderHTMLOnly() {
        return $this->generateHTML();
    }

    // Método para obtener solo el CSS (para incluir en el head)
    public function renderCSSOnly() {
        return $this->generateCSS();
    }

    // Métodos estáticos para uso rápido
    public static function quickRender($text = "Cargando") {
        $loader = new self(['text' => $text]);
        return $loader->render();
    }

    public static function minimal($text = "Cargando") {
        $loader = new self(['text' => $text]);
        return $loader->generateHTML();
    }

    public static function custom($text = "Cargando", $color = "#007bff", $fontSize = "30px") {
        $loader = new self([
            'text' => $text,
            'color' => $color,
            'fontSize' => $fontSize
        ]);
        return $loader->render();
    }
}
