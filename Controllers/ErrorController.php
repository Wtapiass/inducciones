<?php 
    session_start();
    /**
    * 
    */
    class ErrorController extends Controllers {
        
        function __construct() {
            parent::__construct();
        }

        public function Error_404() {
            header("HTTP/1.0 404 Not Found");
            include 'Views/Default/404.php';
        }
    }

 ?>