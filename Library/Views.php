<?php 
	class Views {
		function __construct() {
		}
		
		function render($controller,$view,$array){
			
			$controllers = (preg_replace('/Controller/m',"", get_class($controller)));

			require_once VIEWS.DTF.'head.php';
			if($view != "login"){
				require_once ROOT.'authentication/seguridad.php';
			}

			require_once VIEWS.$controllers.'/'.$view.'.php';

			require_once VIEWS.DTF.'footer.php';
		}
	}
 ?>