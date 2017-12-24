<?php
	class Test extends MY_Controller{
		public function index(){
			echo '來這裡囉';
			$res=$this->userinfo;
			echo $res['user_Id'];
			$userinfo=array(
				'thingsname'=>'jj',
				'user_Email'=>'LCkkkk@CCU',
				'user_Identity'=>88888
			);
			$this->setseinfo($userinfo);
			var_dump($_SESSION['jj']);
			//$this->logout();
			
		}
	
	}