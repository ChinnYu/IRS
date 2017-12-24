<?php
	class Teacherhome extends MY_Controller{
		public function index(){
			$this->load->view('FOUNDCLASS/TeacherHome.html');
		}
		public function infotohtml(){
			$res=$this->userinfo;
			echo json_encode($res['user_Name']);
		}
	
	}