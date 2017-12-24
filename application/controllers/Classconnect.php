<?php
class Classconnect extends MY_Controller{
	public function loadlesson(){
			$this->load->model('Course_model','course');
			$res=$this->userinfo;
				$data=array(
					'user_Id'=>$res['user_Id'],
					'course_Year'=>106,
					'course_Term'=>1
				);
				$query=$this->course->findCourse($data);
				$temp='<select class="form-control nav" name="YourCourse" id="Coursebox" onchange="loadquiz(this.options[this.selectedIndex].text)"> <option selected disabled>選擇一個課程</option>';
				foreach ($query as $row)
				{
						 $temp =$temp .'<option >'. $row['course_Name']. '</option>';
				}
				$temp =$temp .'</select>';
				echo $temp;
		}
		
	public function loadquiz(){
			$this->load->model('Quiz_model','quiz');
			$res=$this->userinfo;
			$data=array(
				'user_Id'=>$res['user_Id'],
				'course_Name'=>$_POST['course_Name']
			);
			$query=$this->quiz->findQuiz($data);
			if($query!= null){
			$temp='<select class="form-control nav" name="YourClass" id="Classbox">';
			foreach ($query as $row)
				{
						 $temp =$temp .'<option >'. $row['quiz_Name']. '</option>';
				}
			$temp =$temp .'</select>';
			echo $temp;
			}
	}
	
	public function loadname(){
			//$this->load->model('this_class_model', 'this_class');
			//$classId = $this->this_class->findPin();
			$res=$this->userinfo;
			$pin=$this->session->userdata('PIN');
			$data=array(
				'user_Name'=>$res['user_Name'],
				'pin'=>$pin['PIN']
			);
			//$temp = $res['user_Name'];
				echo json_encode($data);
		}
	
	public function loadnamestudent(){
			//$this->load->model('this_class_model', 'this_class');
			//$classId = $this->this_class->findPin();
			$res=$this->userinfo;
			$pin=$this->session->userdata('PinCode');
			$data=array(
				'user_Name'=>$res['user_Name'],
				'pin'=>$pin['Pin']
			);
			//$temp = $res['user_Name'];
				echo json_encode($data);
		}
}
?>