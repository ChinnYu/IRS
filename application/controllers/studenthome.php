<?php
	class Studenthome extends MY_Controller{
		public function index(){
			$this->load->view('FOUNDCLASS/StudentHomePage.html');
		}
		public function infotohtml(){
			$res=$this->userinfo;
			echo json_encode($res['user_Name']);
		}
	
	}