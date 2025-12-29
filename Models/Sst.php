<?php 
	class Sst extends Connection {
		function __construct() {
			parent::__construct();
		}

		function execute_sp($sp,$attr,$params){
			return $this->db->excecute_sp($sp,$attr,$params);
		}
		
		function getDataModel($columns,$table){
			return $this->db->select($columns,$table,'1');
		}
	}
 ?>