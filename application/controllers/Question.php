<?php
class Question extends MY_Controller{
	//新增問題
	public function newquestion(){
		$this->load->model('question_model','question');
		$this->load->model('course_model','course');
		$res=$this->userinfo;
		$courseyt=$this->getCourseyt();
		//處理<p>標籤問題 開始
		$question_Content =str_replace("<p>","",$_POST['question_Content']);
		$question_Content =str_replace("</p>","",$question_Content);
		$question_Optiona =str_replace("<p>","",$_POST['question_Optiona']);
		$question_Optiona =str_replace("</p>","",$question_Optiona);
		$question_Optionb =str_replace("<p>","",$_POST['question_Optionb']);
		$question_Optionb =str_replace("</p>","",$question_Optionb);
		$question_Optionc =str_replace("<p>","",$_POST['question_Optionc']);
		$question_Optionc =str_replace("</p>","",$question_Optionc);
		$question_Optiond =str_replace("<p>","",$_POST['question_Optiond']);
		$question_Optiond =str_replace("</p>","",$question_Optiond);
		//處理<p>標籤問題 結束
		$questiondefault=array(
			'question_Id'=>'',
			'user_Id'=>$res['user_Id'],
			'course_Tag'=>$_POST['question_Chapter'],
			'course_Name'=>$_POST['question_Lession'],
			'question_Content'=>$question_Content,
			'question_Optiona'=>$question_Optiona,
			'question_Optionb'=>$question_Optionb,
			'question_Optionc'=>$question_Optionc,
			'question_Optiond'=>$question_Optiond,
			'question_Answer'=>$_POST['question_Answer'],
			'question_Types'=>$_POST['question_Types'],
			'question_Time'=>$_POST['question_Time'],
			'used_Times'=>'0'
		);
		$alreadyhasthistag = 0;
			$data=array(
					'user_Id'=>$res['user_Id'],
					'course_Name'=>$_POST['question_Lession'],
					'course_Year'=>$courseyt['course_Year'],
					'course_Term'=>$courseyt['course_Term']
				);
			$query=$this->course->findCourse($data);
			foreach ($query as $row)
			{
			$coursetagresult = explode('_',$row['course_Tag']);
			for( $i=0;$i<count($coursetagresult);$i++){
				if($_POST['question_Chapter']== $coursetagresult[$i])
					$alreadyhasthistag = 1;
			}
			}
			if($alreadyhasthistag == 0){
				$coursetag = $query[0]['course_Tag']. '_'. $_POST['question_Chapter'];
				$updateCourse=array(
						'user_Id'=>$res['user_Id'],
						'course_No'=>$query[0]['course_No'],
						'course_Tag'=>$coursetag,
						'course_Year'=>$courseyt['course_Year'],
						'course_Term'=>$courseyt['course_Term']
					);
				$bool2=$this->course->updateCourse($updateCourse);//確認course_Tag有沒有存在資料庫，沒有的話便新增進去
			}
		$bool=$this->question->createQuestion($questiondefault);//新增題庫題目
		echo $alreadyhasthistag;
	}
	//編輯問題頁面 修改問題
	public function editquestion(){
		$this->load->model('question_model','question');
		$res=$this->userinfo;
		$questiondefault=array(
			'question_Id'=>$_POST['question_Id'],
			'user_Id'=>$res['user_Id'],
			'question_Content'=>$_POST['question_Content'],
			'question_Optiona'=>$_POST['question_Optiona'],
			'question_Optionb'=>$_POST['question_Optionb'],
			'question_Optionc'=>$_POST['question_Optionc'],
			'question_Optiond'=>$_POST['question_Optiond'],
			'question_Answer'=>$_POST['question_Answer'],
			'question_Time'=>$_POST['question_Time']
		);
		print_r($questiondefault);
		$bool=$this->question->updateQuestion($questiondefault);
	}
	
	public function editfindquestion(){
		$this->load->model('question_model','question');
		$res=$this->userinfo;
			$chapterdefault=array(
				'user_Id'=>$res['user_Id'],
				'course_Tag'=>$_POST['question_Chapter'],
				'course_Name'=>$_POST['question_Lession'],
				'question_Types'=>$_POST['question_Types']
			);
			$res=$this->question->findQuestion($chapterdefault);
			$out = array_values($res);
			echo json_encode($out);
	}
	
	public function newquiz(){//新增測驗
			$this->load->model('Quiz_model','quiz');
			$res=$this->userinfo;
			$data=array(
				'user_Id'=>$res['user_Id'],
				'course_Name'=>$_POST['course_Name'],
				'quiz_Name'=>$_POST['quiz_Name'],
				'irs_Quiz_Question_Id'=>$_POST['irs_Quiz_QuestionI_Id'],	
				'quiz_Time'=>$_POST['quiz_Time']
			);
			$res=$this->quiz->createQuiz($data);
			var_dump($res);
		}
	
	public function deletequestion(){//刪除題庫題目
		$this->load->model('question_model','question');
		$questiondefault=array(
			'question_Id'=>$_POST['question_Id']
		);
		$bool=$this->question->deleteQuestion($questiondefault);
		var_dump($bool);
		}
	
	public function loadlessonandchapter(){
		$this->load->model('Course_model','course');
		$res=$this->userinfo;
		$courseyt=$this->getCourseyt();
			$data=array(
				'user_Id'=>$res['user_Id'],
				'course_Year'=>$courseyt['course_Year'],
				'course_Term'=>$courseyt['course_Term']
			);
			$query=$this->course->findCourse($data);
			$temp='<select class="select" id="lesson" onchange="loadTag(this.options[this.selectedIndex].text)">';
			foreach ($query as $row)
			{
					 $temp =$temp .'<option >'. $row['course_Name']. '</option>';
			}
			$temp =$temp .'</select>';
			echo $temp;
	}
	
	public function loadquestiontag(){
		$this->load->model('Course_model','course');
		$res=$this->userinfo;
		$courseyt=$this->getCourseyt();
			$data=array(
				'user_Id'=>$res['user_Id'],
				'course_Name'=>$_POST['course_Name'],
				'course_Year'=>$courseyt['course_Year'],
				'course_Term'=>$courseyt['course_Term']
			);
			$query=$this->course->findCourse($data);
			$temp='<select class="radio1" id="chapter" name="for_radio1[]"  disabled="true">';
			foreach ($query as $row)
			{
					 $coursetagresult =explode('_',$row['course_Tag']);
					 for( $i=0;$i<count($coursetagresult);$i++)
					 $temp =$temp .'<option >'. $coursetagresult[$i]. '</option>';
			}
			$temp =$temp .'</select>';
			echo $temp;
	}
	
	public function loadquestiontag2(){
		$this->load->model('Course_model','course');
		$res=$this->userinfo;
		$courseyt=$this->getCourseyt();
			$data=array(
				'user_Id'=>$res['user_Id'],
				'course_Name'=>$_POST['course_Name'],
				'course_Year'=>$courseyt['course_Year'],
				'course_Term'=>$courseyt['course_Term']
			);
			$query=$this->course->findCourse($data);
			$temp='<label style="margin-top:15px;">選擇Tag :</label> <select class="select" id="chapter" onchange="showTag(this.options[this.selectedIndex].text)"><option>新增Tag</option>';
			foreach ($query as $row)
			{
					 $coursetagresult =explode('_',$row['course_Tag']);
					 for( $i=0;$i<count($coursetagresult);$i++)
					 $temp =$temp .'<option >'. $coursetagresult[$i]. '</option>';
			}
			$temp =$temp .'</select> 
											  <div style="margin-top:15px;" id="addtopic">
												<label>輸入新Tag :</label>
												<textarea id="tag_new" rows="1" cols="10" style="margin-top:15px;"></textarea>
											</div>';
			echo $temp;
	}
	
	public function loadquestiontagforeditquestion(){
		$this->load->model('Course_model','course');
		$res=$this->userinfo;
		$courseyt=$this->getCourseyt();
			$data=array(
				'user_Id'=>$res['user_Id'],
				'course_Name'=>$_POST['course_Name'],
				'course_Year'=>$courseyt['course_Year'],
				'course_Term'=>$courseyt['course_Term']
			);
			$query=$this->course->findCourse($data);
			$temp='<select class="select" id="chapter" >';
			foreach ($query as $row)
			{
					 $coursetagresult =explode('_',$row['course_Tag']);
					 for( $i=0;$i<count($coursetagresult);$i++)
					 $temp =$temp .'<option >'. $coursetagresult[$i]. '</option>';
			}
			$temp =$temp .'</select>';
			echo $temp;
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
	
}
?>