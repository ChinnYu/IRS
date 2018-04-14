<?php
	class MY_controller extends CI_Controller{
		
		public function __construct(){
			parent::__construct();
			
			//權限驗證
			$this->checkurl();
			$this->checklogin();
			$user=$this->getseinfo();
			//$user=$this->checkidentity();
			$this->userinfo=$user;
		}
		public function checklogin(){
			$temp=$this->uri->segment(2);
			$this->load->library('session');
			if($temp!='pin'){
				if(isset($_SESSION['user'])){
					
				}
				else{	
					header("Location:./login");
					die;
				}
			}
			else{
				if(isset($_SESSION['user'])){
					header("Location:../../checkqr");
					die;
				}
				else{
					header("Location:../../login");
					die;
				}
				
			}
		}
		public function getseinfo(){
			$this->load->library('session');
			//取CI session中的數據
			$user=$this->session->userdata('user');
			return $user;
		}
		public function setseinfo($data){
			$this->load->library('session');
			$this->session->set_userdata($data['thingsname'],$data);
		}
		
		public function checkidentity(){
			//$this->load->library('session');
			if($_SESSION['user']['user_Identity']!=0){
				//echo '無權限觀看';
				die('無權限觀看');
			}
			
		}
		
		public function checkurl(){
			$name=$this->uri->segment(2);
			if($name=="pin"){
				$temp=$this->uri->segment(3);
				if($temp!=null){
					$this->load->library('session');
					$url_pin=array('pin'=>$temp);
					$this->session->set_userdata('url',$url_pin);
				}
			}
		}
		
	}