<?php 
	session_start();

	class PreviewsController extends Controllers {
		public $model;
		public $view;

        function __construct() {
			parent::__construct();
		}

        //Previsualización de Contrato
        public function contract_1() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_1', $response);
        }

        public function contract_2() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_2', $response);
        }

        public function contract_3() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_3', $response);
        }

        public function contract_4() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_4', $response);
        }

        public function contract_5() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_5', $response);
        }

        public function contract_6() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_6', $response);
        }

        public function contract_7() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_7', $response);
        }

        public function contract_8() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_8', $response);
        }

        public function contract_9() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_9', $response);
        }

        public function contract_10() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_10', $response);
        }

        public function contract_11() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_11', $response);
        }

        public function contract_12() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_12', $response);
        }

        public function contract_13() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_13', $response);
        }

        public function contract_14() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_14', $response);
        }

        public function contract_15() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contrato_ficha_ingreso_datos_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_15', $response);
        }

        public function contract_16() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_16', $response);
        }

        public function contract_17() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_17', $response);
        }

        public function contract_18() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_18', $response);
        }

        public function contract_19() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_19', $response);
        }

        public function contract_20() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_20', $response);
        }

        public function contract_21() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_21', $response);
        }

        public function contract_22() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_22', $response);
        }

        public function contract_23() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_23', $response);
        }

        public function contract_24() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_24', $response);
        }

        public function contract_25() {
            //Contratos Digitales
            $attrCD = "@field=?, @value=?";
            $myparamsCD['@field'] = 'id_contrato_digital'; 
            $myparamsCD['@value'] = $_SESSION['id_contrato_digital'];

            $paramsCD = array(
                array(&$myparamsCD["@field"]),
                array(&$myparamsCD["@value"])
            );

            $contratos_digitales = $this->model->execute_sp('dbo.sp_contratos_digitales_select_one', $attrCD, $paramsCD);

            $response = array();
            array_push($response, $contratos_digitales);
            $this->view->render($this, 'contract_25', $response);
        }
    }