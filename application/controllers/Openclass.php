<?php
	class Openclass extends MY_Controller{
		public function index(){
			$this->checkMethod_teacher();
			$this->checkshadowstatue();
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
			if(!isset($_SESSION['PIN'])){
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
			}else{
				$this->load->view('FOUNDCLASS/ShowPinAndQR.html');
			}
			
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
			if($time_array[1]<7){
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
		
		public function checkshadowstatue(){//未正常完成考試流程
			$this->load->model('This_class_model', 'this_class');
			$data=array(
				'user_Id'=>$_SESSION['user']['user_Id'],
			);
			$res=$this->this_class->findclass($data);
			if(isset($_SESSION['quizstatue'])){
				unset($_SESSION['quizstatue']);
			}
			if(isset($_SESSION['course'])){
				unset($_SESSION['course']);
			}
			if(isset($_SESSION['PIN'])){
				unset($_SESSION['PIN']);
			}
			if($res!=NULL){
				$this->load->model('Grade_record_model','grade_record');
				$this->load->model('Shadow_quiz_model','shadow_quiz');
				foreach($res as $key=>$b){
					if($b['PIN']!=null){
						$data=array(
							'class_Id'=>$b['class_Id'],
							'current_Pin'=>$b['PIN'],
						);
						$this->grade_record->deleteRecord($data);
						$data_reset=array(
							'class_Id'=>$b['class_Id'],
							//'current_Pin'=>$b['PIN'],
						);
						$this->shadow_quiz->deleteshadow($data_reset);
						
						$data_reset1=array(
							'user_Id'=>$_SESSION['user']['user_Id'],
							'class_Id'=>$b['class_Id'],
							'PIN'=>null,
							'quiz_Id'=>null,
						);
						$this->this_class->clearPin($data_reset1);
						$this->deleteabnormal($b['class_Id'],$b['quiz_Id'],$b['PIN']);
					}
				}
			}
			
		}
		public function deleteabnormal($class_Id,$quiz_Id,$PIN){
			$cdata_name=$class_Id.'_'.$quiz_Id;
			$this->load->library('MP_Cache');
			$cdata_quiz = $this->mp_cache->set_name($cdata_name)->get();
			if ($cdata_quiz!== false){
				$this->mp_cache->delete($cdata_name);
			}
			$cdata=$PIN;
			$cdata_pin= $this->mp_cache->set_name($cdata)->get();
			if ($cdata_pin!== false){
				$this->mp_cache->delete($cdata);
			}
			$cdata_name=$class_Id.'_'.$quiz_Id.'_'.'ans';
			$this->load->library('MP_Cache');
			$cdata_quiz_ans = $this->mp_cache->set_name($cdata_name)->get();
			if ($cdata_quiz_ans!== false){
				$this->mp_cache->delete($cdata_name);
			}
			$this->deletequestiontime($class_Id);
		}
		public function deletequestiontime($class_Id){//刪除題目開始時間_教師用
			$this->load->library('MP_Cache');
			$cdata_name=$class_Id.'_time';
			$cdata_time = $this->mp_cache->set_name($cdata_name)->get();
			if ($cdata_time!== false){
				$this->mp_cache->delete($cdata_name);
			}
		}
		
		
	}