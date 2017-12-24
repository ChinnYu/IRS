<?php
	class Waitpage extends MY_Controller{
		
		public function index(){
			//$this->checkMethod_teacher();
			$this->load->view('FOUNDCLASS/ShowPinAndQR.html');
		}
	}