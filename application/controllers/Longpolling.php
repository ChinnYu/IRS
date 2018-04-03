<?php
	class longpolling extends MY_Controller{
		public function polling(){
			
			set_time_limit(0);
			$this->load->model('this_class_model','this_class');
			 $class_Id=array(
				'class_Id'=>$_SESSION['classandquiz']['class_Id']
			);
			$endClass=$this->this_class->findEndclass($class_Id); 
			$row = $endClass[0]['end_Class'];
			// main loop
			while (true) {
				$endClass=$this->this_class->findEndclass($class_Id); 
				$row = $endClass[0]['end_Class'];				
				if ($row == 1) {
					
					break;
				}else {
					sleep( 1 );
					continue;
				}
			}
			//header("Location: ../QuizPage");			
		}
		public function polling2(){
		$this->load->model('this_class_model','this_class');
		$class_Id=array(
				'class_Id'=>$_SESSION['classandquiz']['class_Id']
			);
			$endClass=$this->this_class->findEndclass($class_Id); 
			$row = $endClass[0]['end_Class'];
			echo $row;
		}
	}
	
	
?>