<?php
	class Teacherhome extends MY_Controller{
		public function index(){
			$this->load->view('FOUNDCLASS/TeacherHome.html');
			//$this->load->view('user/test.html');
		}
		public function infotohtml(){
			$res=$this->userinfo;
			echo json_encode($res['user_Name']);
		}
		
		public function set_cookie(){
			if(isset($_SESSION['remember'])){
				if($_SESSION['remember']==1){
					$times=date("Y-m-d");
					$coo_temp=$_SESSION['user']['user_Id']."-".$times;
					$data=$this->encrypt_cookies($coo_temp);
					unset($_SESSION['remember']);
					echo json_encode($data);
				}else{
					unset($_SESSION['remember']);
					echo json_encode(FALSE);
				}
			}else{
				echo json_encode(FALSE);
			}
		}
		
		public function encrypt_cookies($data){
			$this->load->library('encryption');
			$this->encryption->initialize(
				array(
					'cipher' => 'aes-128',
					'mode' => 'cbc',
					'key' => '8888',
				)
			);
			$ciphertext = $this->encryption->encrypt($data);
			$data=base64_encode($ciphertext);
			return $data;
		}
		
		public function check_cookies(){
			if(isset($_SESSION['update_coo'])){
				if($_SESSION['update_coo']==1){
					$times=date("Y-m-d");
					$coo_temp=$_SESSION['user']['user_Id']."-".$times;
					$data=$this->encrypt_cookies($coo_temp);
					unset($_SESSION['update_coo']);
					echo json_encode($data);
				}else{
					unset($_SESSION['update_coo']);
					echo json_encode(FALSE);
				}
			}else{
				echo json_encode(FALSE);
			}
			
		}
		
		
	}