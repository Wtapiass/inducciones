<?php 
    session_start();
    /**
    * 
    */
    class AccesoRestringidoController extends Controllers {
        
        function __construct() {
            parent::__construct();
        }

        public function Error_403() {
            // header("HTTP/1.0 404 Not Found");
            include 'Views/Default/acceso_restringido.php';
        }
    }

 ?>