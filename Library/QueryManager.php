<?php 
	class QueryManager {
		private $link;
		function __construct($HOST,$USER,$PASS,$DB) {	
			$connectionInfo = array( "Database"=>$DB, "UID"=>$USER, "PWD"=>$PASS, "CharacterSet" => "UTF-8");
			$this->link = sqlsrv_connect( $HOST, $connectionInfo);
			
			if($this->link === false){
				if( sqlsrv_errors() ) {
					print_r(sqlsrv_errors()[0]);
					echo "Conexión no se pudo establecer.<br />";
					die( print_r( sqlsrv_errors(), true));
				}
			}
		}

		function excecute_sp($sp,$attr,$params){
			$sql = "EXEC ".$sp." ".$attr."";
			$stmt = sqlsrv_prepare($this->link, $sql, $params);
			$mod = 0;
			if(sqlsrv_execute($stmt)){
				while($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC)){
					$mod = 1;
					$response[] = $row;
				}

				if($mod == 0){
					$response[] = 0;
				}
				sqlsrv_free_stmt( $stmt);  
				return $response;
				
			}else{
				die( print_r( sqlsrv_errors(), true));
			}
		}


		function select($attr,$table,$where){
			$query = "SELECT ".$attr." FROM ".$table." WHERE ".$where.";";
			$result = $this->link->query($query);
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					$response[] = $row;
				}
				return $response;
			}
		}

		function selectLastId($table){
			$query = "SELECT TOP 1 id FROM ".$table." ORDER BY id DESC;";
			$result = sqlsrv_query($this->link, $query);
		
			while ($row = sqlsrv_fetch_array($result , SQLSRV_FETCH_ASSOC)) {
				$response[] = $row;
			}
				return $response;
			
		}

		function insert($table,$columns){
			$columnas = null;
			$data = null;
			foreach ($columns as $key => $value) {
				$columnas .= $key.',';
				$data .='"'.$value.'",'; 
			}
			$columnas = substr($columnas, 0, -1);
			$data = substr($data, 0, -1);
			$stmt = "INSERT INTO ".$table." (".$columnas.") VALUES (".$data.")";
			$result = $this->link->query($stmt) or die($this->link->error);
		}

		function update($table,$columns,$where){
			$values = "";
			foreach ($columns as $key => $value) {
				$values .= $key.'="'.$value.'",';
			}
			$values = substr($values, 0, strlen($values)-1);
			$query = "UPDATE $table SET $values WHERE $where";
			$result = $this->link->query($query) or die ($this->link->error.__LINE__);
			return true;
		}
	}
 ?>