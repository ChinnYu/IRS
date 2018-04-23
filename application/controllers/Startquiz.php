<?php
	class Startquiz extends MY_Controller{
		public function index(){
			//$this->checkMethod_teacher();
			if(!isset($_SESSION['quizstatue'])){
				$this->createshadowquiz();
			}
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
				
				
			}else if($_SESSION['user']['user_Identity']==1){
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
			$question_array=explode('_',$res[0]['irs_Quiz_Question_Id']);
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

		public function endquiz(){
			$this->load->library('MP_Cache');

			$cdata = array(
				'endclass'=> 1
			);
			$cdata_name=$cdata_name=$_SESSION['course']['class_Id'].'_endclass';
			$this->mp_cache->write($cdata, $cdata_name,7200);
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
			$cdata_name=$_SESSION['course']['class_Id'].'_'.$_SESSION['course']['quiz_Id'].'_'.'ans';
			$this->load->library('MP_Cache');
			$cdata_quiz_ans = $this->mp_cache->set_name($cdata_name)->get();
			if ($cdata_quiz_ans!== false){
				$this->mp_cache->delete($cdata_name);
			}
			$cdata=$_SESSION['course']['class_Id'].'_endclass';//刪除endclass cache
			$cdata_endclss= $this->mp_cache->set_name($cdata)->get();
			if ($cdata_endclss!== false){
				$this->mp_cache->delete($cdata);
			}
			$this->deletequestiontime();
		}
		
		public function setopinion(){//設置shadow_quiz評價和刪除session
			$this->load->model('Shadow_quiz_model','shadow_quiz');
			$data=array(
				'shadow_Quiz_Id' =>$_SESSION['quizstatue']['shadow_Quiz_Id'],
			);
			$opinion=array(
				'current_Pin'=>null,
				'shadow_Quiz_opinion'=>5,//需從前端皆值
			);
			$this->shadow_quiz->updateShadow_quiz($data,$opinion);
			$res=$this->clearforfinish();
			if($res==true){
				$this->deletesesfortea();
			}
			echo json_encode('OK');
		}
		
		public function checkans(){//用來儲存學生作答
			if(isset($_SESSION['classandquiz'])){
				$this->load->model('Grade_record_model','grade_record');
				$this->load->library('MP_Cache');
				$cdata_name=$_SESSION['classandquiz']['class_Id'].'_time';
				$cdata_time = $this->mp_cache->set_name($cdata_name)->get();
				//var_dump($cdata_name);
				$date=date_create();
				$checktime=date_timestamp_get($date);
				$anstime=$checktime-$cdata_time['time'];
				$ans=$_POST['ans'];//從前端接值
				$data=array(
					'user_Id'=>$_SESSION['user']['user_Id'],
					'user_Info'=>$_SESSION['user']['user_Info'],
					'user_Name'=>$_SESSION['user']['user_Name'],
					'class_Id'=>$_SESSION['classandquiz']['class_Id'],
					'current_Pin'=>$_SESSION['classandquiz']['PIN'],
					'ans_List'=>$ans,
					'ans_Time_List'=>$anstime,
				);
				$this->grade_record->createRecord($data);
				echo json_encode('ok');
			}
			else{
				echo json_encode('bad');
			}
		}
		
		public function createshadowquiz(){//創建shadowquiz
			$this->load->model('Shadow_quiz_model','shadow_quiz');
			$data=array(
				'quiz_Id'=>$_SESSION['course']['quiz_Id'],
				'class_Id'=>$_SESSION['course']['class_Id'],
				'user_Id'=>$_SESSION['user']['user_Id'],
				'current_Pin'=>$_SESSION['course']['pin'],
			);
			$this->shadow_quiz->createShadow_quiz($data);
			$res=$this->shadow_quiz->findShadow_quiz($data);
			$this->setseinfo(array('thingsname' => 'quizstatue', 'shadow_Quiz_Id' =>$res[0]['shadow_Quiz_Id']));
		}
		
		public function recordusetime(){//紀錄題目各題時間,各題皆要呼叫
			if(isset($_SESSION['quizstatue'])){
				$this->load->library('MP_Cache');
				$cdata_name=$_SESSION['classandquiz']['class_Id'].'_time';
				$cdata_time = $this->mp_cache->set_name($cdata_name)->get();
				$date=date_create();
				$checktime=date_timestamp_get($date);
				$usetime=$checktime-$cdata_time['time'];
				$this->load->model('Shadow_quiz_model','shadow_quiz');
				$data=array(
					'shadow_Quiz_Id' =>$_SESSION['quizstatue']['shadow_Quiz_Id'],
					'usetime_List'=> $usetime,
				);
				$this->shadow_quiz->updateUseTime($data);
				echo json_encode('ok');
			}else{
				echo json_encode('流程錯誤');
			}
		}
		
		public function correctans(){//計算分數
			$this->load->model('Grade_record_model','grade_record');
			$this->load->model('Shadow_quiz_model','shadow_quiz');
			$this->load->library('MP_Cache');
			$cdata_name=$_SESSION['course']['class_Id']."_".$_SESSION['course']['quiz_Id'].'_ans';
			$cdata_ans = $this->mp_cache->set_name($cdata_name)->get();
			$data=array(
				'shadow_Quiz_Id' =>$_SESSION['quizstatue']['shadow_Quiz_Id'],
			);
			$res=$this->shadow_quiz->findUseTime($data);
			if($res!=null){
				$usetime_array=explode('@',$res[0]['usetime_List']);
				$data_usetime=array();
				foreach($usetime_array as $a){
					if($a!='')
						array_push($data_usetime,$a);
				}
				$data_stuans=array(
					'class_Id' =>$_SESSION['course']['class_Id'],
					'current_Pin' =>$_SESSION['course']['pin'],
				);
				$res_stuans=$this->grade_record->findRecord($data_stuans);
				$ans_split=array();
				$temp_ans=array();
				$time_split=array();
				$temp_time=array();
				$empty_array=array();
				$ans_output_orign=array(
					'user_Id'=>'',
					'class_Id'=>$_SESSION['course']['class_Id'],
					'shadow_Quiz_Id'=>$_SESSION['quizstatue']['shadow_Quiz_Id'],
					'answer_List'=>'',
					'score_List'=>'',
					'student_Quiz_Total_Score'=>''
				);
				$ans_output_temp=array();
				$ans_output=array();
				$score_List_output = null;
				$anspoint= null;
				$anspoint_total= null;
				$shadow_grade_list="";
				if($res_stuans!=null){
					foreach($res_stuans as $value){
						$ans_split=$empty_array;
						$temp_ans=$empty_array;
						$time_split=$empty_array;
						$temp_time=$empty_array;
						$ans_split=explode('@',$value['ans_List']);
						foreach($ans_split as $c){
							if($c!='')
								array_push($temp_ans,$c);
						}
						$time_split=explode('@',$value['ans_Time_List']);
						foreach($time_split as $b){
							if($b!='')
								array_push($temp_time,$b);
						}
						$ans_output_temp=$ans_output_orign;
						$ans_output_temp['user_Id']=$value['user_Id'];
						$score_List_output="";
						$anspoint_total=0;
						foreach($temp_ans as $key=>$d){
							if($d!=16){
								if($d==$cdata_ans[$key]){
									$anspoint=0;
									$anspoint=(1000/$data_usetime[$key])*($temp_time[$key]);
									$anspoint=1000-(int)$anspoint;
									$score_List_output=$score_List_output."@".$anspoint;
									$anspoint_total=$anspoint_total+$anspoint;
								}
								else{
									$anspoint=0;
									$score_List_output=$score_List_output."@".$anspoint;
								}
							}else{
								$anspoint=0;
								$score_List_output=$score_List_output."@".$anspoint;
							}
						}
						$ans_output_temp['student_Quiz_Total_Score']=$anspoint_total;
						$shadow_grade_list=$shadow_grade_list.','.$value['user_Info'].','.$value['user_Name'].','.$anspoint_total;
						$ans_output_temp['answer_List']=$value['ans_List'];
						$ans_output_temp['score_List']=$score_List_output;
						array_push($ans_output,$ans_output_temp);
					}
					$this->load->model('Student_quiz_model','student_quiz');
					foreach($ans_output as $key=>$f){
						$this->student_quiz->createStudent_quiz($f);
					}
					$data_score_list=array(
						'shadow_Quiz_Id'=>$_SESSION['quizstatue']['shadow_Quiz_Id'],
						'quiz_Score_List'=>$shadow_grade_list,
					);
					
					$this->shadow_quiz->updatescore($data_score_list);
				}
			}
		}
		
		public function setquestiontime(){//設置題目開始時間
			$this->load->library('MP_Cache');
			$date=date_create();
			$date=date_timestamp_get($date);
			$cdata_name=$_SESSION['course']['class_Id'].'_time';
			$cdata = $this->mp_cache->set_name($cdata_name)->get();
			if ($cdata === false){
				$cdata = array(
					'time'=>$date,
				);
				$this->mp_cache->write($cdata,$cdata_name,7200);
			}	
		}
		
		public function deletequestiontime(){//刪除題目開始時間_教師用
			$this->load->library('MP_Cache');
			$cdata_name=$_SESSION['course']['class_Id'].'_time';
			$cdata_time = $this->mp_cache->set_name($cdata_name)->get();
			if ($cdata_time!== false){
				$this->mp_cache->delete($cdata_name);
			}
		}
		
		public function deletesesforstu(){//刪除session for student
			unset($_SESSION['classandquiz']);
		}
		
		public function deletesesfortea(){//刪除session for teacher
			unset($_SESSION['course']);
			unset($_SESSION['quizstatue']);
		}
		
		public function clearforfinish(){//刪除資料庫所記錄的pin(shadow,this_class)與清空grade_record
			$this->load->model('Grade_record_model','grade_record');
			$this->load->model('Shadow_quiz_model','shadow_quiz');
			$this->load->model('This_class_model', 'this_class');
			$data=array(
				'class_Id'=>$_SESSION['course']['class_Id'],
				'current_Pin'=>$_SESSION['course']['pin'],
			);
			$this->grade_record->deleteRecord($data);
			$data_reset=array(
				'shadow_Quiz_Id'=>$_SESSION['quizstatue']['shadow_Quiz_Id'],
				'current_Pin'=>null,
			);
			$this->shadow_quiz->clearPin($data_reset);
			$data_reset1=array(
				'user_Id'=>$_SESSION['user']['user_Id'],
				'class_Id'=>$_SESSION['course']['class_Id'],
				'PIN'=>null,
				'quiz_Id'=>null,
			);
			$this->this_class->clearPin($data_reset1);
			return true;
		}
		
		public function checkshadowstatue(){//未正常完成考試流程
			$this->load->model('This_class_model', 'this_class');
			$data=array(
				'user_Id'=>$_SESSION['user']['user_Id'],
			);
			$res=$this->this_class->findclass($data);
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
							'current_Pin'=>$b['PIN'],
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
		public function deleteabnormal($class_Id,$quiz_Id,$PIN){//異常情況下刪快取
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
		
		public function handletime(){
			$this->deletequestiontime($_SESSION['course']['class_Id']);
			$this->setquestiontime();
		}
		
	}
