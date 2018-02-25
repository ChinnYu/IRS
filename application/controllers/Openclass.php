<?php
	class Openclass extends MY_Controller{
		public function index(){
			$this->checkMethod_teacher();
			$this->load->view('FOUNDCLASS/CreateClass.html');
		}
		public function getClassinfo(){
			$this->checkMethod_teacher();
			$this->load->model('Course_model','course');
			$user_detail=$this->userinfo;
			$courseyt=$this->getCourseyt();
			$data=array(
				'user_Id'=>$user_detail['user_Id'],
				'course_Year'=>$courseyt['course_Year'],
				'course_Term'=>$courseyt['course_Term']
			);
			$res=$this->course->findCourse($data);
			echo json_encode($res);
		}
		
		public function createQR(){
			$this->checkMethod_teacher();
			$this->load->library('MP_Cache');
			$this->load->model('This_class_model', 'this_class');
			$courseyt=$this->getCourseyt();
			$res = $this->userinfo;
			$data = array(
				'user_Id' => $res['user_Id'],
				'course_Name' =>$this->input->post('Course_text'),
				'course_No' =>$this->input->post('course_No'),
				'course_Year'=>$courseyt['course_Year'],
				'course_Term'=>$courseyt['course_Term'],
				'quiz_Id'=>$this->input->post('quiz_Id'),
				'PIN' =>''
			);
			$pin_pass=array(
				'PIN' =>'',
				'quiz_Id'=>$this->input->post('quiz_Id'),
			);
			$Pin = null;
			$if_success=null;
			while ($if_success == false) {
				$Pin = str_pad($this->randPin(), 4, "0", STR_PAD_LEFT);
				$data['PIN'] = $Pin;
				$pin_pass ['PIN'] = $Pin;
				$if_success= $this->this_class->updateClass($data,$pin_pass);
			}
			$this->setseinfo(array('thingsname' => 'PIN', 'PIN_code' => $Pin));
			$result=$this->this_class->findclass($pin_pass);
			$cdata = $this->mp_cache->set_name($Pin)->get();
			if ($cdata === false){
				$cdata = array(
					'class_Id'=>$result[0]['class_Id'],
					'quiz_Id'=>$result[0]['quiz_Id'],
				);
				$this->mp_cache->write($cdata, $Pin,7200);
			}
			$class_pass=array(
				'thingsname' => 'course',
				'class_Id'=>$result[0]['class_Id'],
				'course_Name'=>$data['course_Name'],
				'course_No'=>$data['course_No'],
				'pin'=>$Pin,
				'quiz_Id'=>$this->input->post('quiz_Id')
			);
			$this->setseinfo($class_pass);
			
			$this->load->view('FOUNDCLASS/ShowPinAndQR.html');
		}
		public function randPin(){
			return rand(0, 9999);
		}
		public function getPin(){
			$this->checkMethod_teacher();
			echo $_SESSION['PIN']['PIN_code'];
		}
		public function startQuiz(){
			$this->checkMethod_teacher();
			$this->load->view('FOUNDCLASS/StartQuiz.html');
		}
		public function getQuizinfo(){
			$this->checkMethod_teacher();
			$this->load->model('Quiz_model', 'quiz');
			$data=array(
				'user_Id'=>$_SESSION['user']['user_Id'],
				'course_Name'=>$_POST['course_Name']
			);
			$res=$this->quiz->findQuiz($data);
			$data_return=array();
			foreach($res as $i){
				array_push($data_return,$i['quiz_Id'],$i['quiz_Name'],$i['quiz_Time']);
			}
			$result= json_encode($data_return);
			echo $result;
		}
		public function getCourseyt(){
			$this->checkMethod_teacher();
			$times=date("Y/m/d");
			$time_array=explode("/",$times);
			$term;
			if($time_array[1]<2){
				$cyear=$time_array[0]-1911-1;
			}
			else{
				$cyear=$time_array[0]-1911;
			}
			if($time_array[1]>=2&&$time_array[1]<=8){
				$term=2;
			}
			else{
				$term=1;
			}
			$data=array(
				'course_Year'=>$cyear,
				'course_Term'=>$term
			);
			return $data;
		}
		public function checkMethod_teacher(){
			if($_SESSION['user']['user_Identity']!='0'){
				die('非法連結,無權限觀看');
			};
		}
		public function getstuname(){
			$this->load->model('Student_list_model','student_list');
			$data=array(
				'class_Id'=>$_SESSION['course']['class_Id'],
			);
			$res=$this->student_list->getParticipants($data);
			$data_return=array();
			foreach($res as $i){
				array_push($data_return,$i['user_Name']);
			}
			echo json_encode($data_return);
		}
		
		
		
	}