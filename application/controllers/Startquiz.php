<?php
	class Startquiz extends MY_Controller{
		public function index(){
			//$this->checkMethod_teacher();
			$this->load->view('FOUNDCLASS/QuizPage.html');
		}
		public function quizpageforstu(){
			//$this->checkMethod_teacher();
			$this->load->view('FOUNDCLASS/Popquiz.html');
		}
		
		public function getQuizinfo(){
			if($_SESSION['user']['user_Identity']==0){
				$this->load->library('MP_Cache');
				$data=array(
					'quiz_Id'=>(int)$_SESSION['course']['quiz_Id'],
				);
				$data_return=$this->getquestion($data);
				$cdata_set=array();
				$cdata_ans_set=array();
				for($i=0;$i<count($data_return);$i++){
					if($i%7!=5){
						$cdata_set[]=$data_return[$i];
					}
					else {
						$cdata_ans_set[]=$data_return[$i];
					}
				}
				$cdata_name=$_SESSION['course']['class_Id'].'_'.$data['quiz_Id'];
				$cdata = $this->mp_cache->set_name($cdata_name)->get();
				if ($cdata === false){
					$cdata = $cdata_set;
					$this->mp_cache->write($cdata,$cdata_name,7200);
				}
				
				$cdata_name=$_SESSION['course']['class_Id'].'_'.$data['quiz_Id'].'_'.'ans';
				$cdata = $this->mp_cache->set_name($cdata_name)->get();
				if ($cdata === false){
					$cdata = $cdata_ans_set;
					$this->mp_cache->write($cdata,$cdata_name,7200);
				}
				echo json_encode($data_return);

			}
			else if($_SESSION['user']['user_Identity']==1){
				$this->load->library('MP_Cache');
				$cdata_name=$_SESSION['classandquiz']['class_Id'].'_'.$_SESSION['classandquiz']['quiz_Id'];
				$cdata = $this->mp_cache->get($cdata_name);
				echo json_encode($cdata);
			}
			
		}
		public function getquestion($data){
			$this->load->model('Quiz_model','quiz');
			$this->load->model('Question_model','question');
			$res=$this->quiz->findknowedquiz($data);
			$question_array=explode('/',$res[0]['irs_Quiz_Question_Id']);
			$data_question=array();
			foreach($question_array as $a){
				if($a!='')
					array_push($data_question,$a);
			}
			$result=$this->question->findselectQuestion($data_question);
			$data_return=array();
			foreach($result as $b){
				array_push($data_return,$b["question_Content"],$b["question_Optiona"],$b["question_Optionb"],$b["question_Optionc"],$b["question_Optiond"],$b["question_Answer"],$b["question_Time"]);
			}
			return $data_return;
		}
		public function finishaction(){
			
			$cdata_name=$_SESSION['course']['class_Id'].'_'.$_SESSION['course']['quiz_Id'];
			$this->load->library('MP_Cache');
			$cdata_quiz = $this->mp_cache->set_name($cdata_name)->get();
			if ($cdata_quiz!== false){
				$this->mp_cache->delete($cdata_name);
			}
			$cdata=$_SESSION['course']['pin'];
			$cdata_pin= $this->mp_cache->set_name($cdata)->get();
			if ($cdata_pin!== false){
				$this->mp_cache->delete($cdata);
			}
			$this->load->model('Shadow_quiz_model','shadow_quiz');
			$data=array(
				'quiz_Id'=>$_SESSION['course']['quiz_Id'],
				'class_Id'=>$_SESSION['course']['class_Id'],
				'quiz_Score_List'=>'jason@100@',//需從前端皆值
			);
			$this->shadow_quiz->createShadow_quiz($data);
			$this->load->model('Rollcall_model','rollcall');
			$roll_list=array(
				'class_Id'=>$_SESSION['course']['class_Id'],
				'rollcall_Name'=>$_SESSION['course']['course_Name'],
				'rollcall_Allmember'=>'jason'//需從前端皆值
			);
			$this->rollcall->createRollcall($roll_list);
			echo json_encode('OK');
		}
		
		public function setopinion(){//設置評價和刪除答案快取
			$cdata_name=$_SESSION['course']['class_Id'].'_'.$_SESSION['course']['quiz_Id'].'_'.'ans';
			$this->load->library('MP_Cache');
			$cdata_quiz_ans = $this->mp_cache->set_name($cdata_name)->get();
			if ($cdata_quiz_ans!== false){
				$this->mp_cache->delete($cdata_name);
			}
			$this->load->model('Shadow_quiz_model','shadow_quiz');
			$data=array(
				'quiz_Id'=>$_SESSION['course']['quiz_Id'],
				'class_Id'=>$_SESSION['course']['class_Id'],
			);
			$opinion=array(
				'shadow_Quiz_opinion'=>'',//需從前端皆值
			);
			$this->shadow_quiz->updateShadow_quiz($data,$opinion);
			
			//echo json_encode('OK');
		}
		
		public function checkans(){//學生交卷並閱卷//單題適用
			$cdata_name=$_SESSION['classandquiz']['class_Id'].'_'.$_SESSION['classandquiz']['quiz_Id'].'_'.'ans';
			$this->load->library('MP_Cache');
			$cdata_quiz_ans = $this->mp_cache->set_name($cdata_name)->get();
			$anspoint;
			if ($cdata_quiz_ans!== false){
				$anstime=$_POST['anstime'];//學生答題時間
				$ansalltime=$_POST['ansalltime'];//題目總花時間
				$ans=$_POST['ans'];
				$question_num=$_POST['question_num'];
				if($ans==$cdata_quiz_ans[$question_num]){
					$anspoint=(1000/$ansalltime)*($ansalltime-$anstime);
					$anspoint=(int)$anspoint;
					//echo json_encode($anspoint);
				}
				else{
					$anspoint=0;
				}
				$this->load->model('Student_quiz_model','student_quiz');			
				$data=array(
					'user_Id'=>$_SESSION['user']['user_Id'],
					'quiz_Id'=>$_SESSION['classandquiz']['quiz_Id'],
					'class_Id'=>$_SESSION['classandquiz']['class_Id'],
					'answer_List'=>$ans,
					'score_List'=>$anspoint,
					'student_Quiz_Total_Score'=>$anspoint,
				);
				$this->student_quiz->createStudent_quiz($data);
				//echo json_encode($ans);
			}
			
			
			echo json_encode($anspoint);
		}
		
		
		
		
	}