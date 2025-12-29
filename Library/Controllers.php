<?php 
	class Controllers {
		function __construct() {
			$this->view = new Views();
			$this->loadClassmodels();
		}

		function loadClassmodels(){
			//$model = get_class($this);
			$model = (preg_replace('/Controller/m',"", get_class($this)));
			$path = 'Models/'.$model.'.php';
			if(file_exists($path)){
				require_once $path;
				require_once "Connection.php";
				$this->model = new $model(); 
			}
		}
	}
 ?>