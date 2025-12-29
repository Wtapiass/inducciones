<?php 

require_once 'Config/config.php';
 
   
	$url = isset($_GET['url']) ? $_GET['url']:"Login/login";
	$url = explode("/", $url);
	$controller = "";
	$method = "";
	$params = "";

	

	if(isset($url[0])){
		$controller = $url[0];
	}
	if(isset($url[1])){
		if($url[1] != ''){
			$method = $url[1];
		}
	}

	if(isset($url[2])){
		if($url[1] != ''){
			$params = $url[2];
		}
	}

	spl_autoload_register(function($class){
		if(file_exists(LBS.$class.".php")){
			include_once(LBS.$class.".php");
		}
	});

	//new Controllers(); 
	$controllerPath = 'Controllers/'.$controller.'Controller.php';
	if(file_exists($controllerPath)){
		
		require_once $controllerPath;
		$controller = $controller.'Controller';
		$controller = new $controller();
		if(isset($method)){

			if(method_exists($controller, $method)){
				
				if (isset($params)) {
					$controller->{$method}($params);
				}else{
					$controller->{$method}();
				}
			}
		}
	}else{
		echo "Error en la direccion no existe el controlador";
	}

	
?>
