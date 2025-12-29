<?php 
	session_start();
	class LoginController extends Controllers {
		function __construct() {
			parent::__construct();
		}
		
		public function login() {
			$this->view->render($this,'login',"");
		}
	}
 ?>