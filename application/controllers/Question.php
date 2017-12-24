<?php
class Question extends MY_Controller{
	//新增問題
	public function newquestion(){
		$this->load->model('question_model','question');
		$questiondefault=array(
			'question_Id'=>'',
			'user_Id'=>'9',
			'question_Chapter'=>$_POST['question_Chapter'],
			'question_Lession'=>$_POST['question_Lession'],
			'question_Content'=>$_POST['question_Content'],
			'question_Optiona'=>$_POST['question_Optiona'],
			'question_Optionb'=>$_POST['question_Optionb'],
			'question_Optionc'=>$_POST['question_Optionc'],
			'question_Optiond'=>$_POST['question_Optiond'],
			'question_Answer'=>$_POST['question_Answer'],
			'question_Types'=>$_POST['question_Types'],
			'question_Tag'=>'bad',
			'question_Time'=>$_POST['question_Time'],
			'used_Times'=>'0'
		);
		
		$bool=$this->question->createQuestion($questiondefault);//新增題庫題目
		//var_dump($bool);
		//echo $_POST['question_Content'];
		

	}
	//編輯問題頁面 修改問題
	public function editquestion(){
		$this->load->model('question_model','question');
		$questiondefault=array(
			'question_Id'=>$_POST['question_Id'],
			'user_Id'=>'9',
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
			$data=array(
				'user_Id'=>'9',
				'quiz_Name'=>$_POST['quiz_Name'],
				'irs_Quiz_QuestionI_Id'=>$_POST['irs_Quiz_QuestionI_Id'],	
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
			$data=array(
				'user_Id'=>$res['user_Id'],
				'course_Year'=>106,
				'course_Term'=>1
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
			$data=array(
				'user_Id'=>$res['user_Id'],
				'course_Name'=>$_POST['course_Name'],
				'course_Year'=>106,
				'course_Term'=>1
			);
			$query=$this->course->findCourse($data);
			$temp='<select class="select" id="chapter">';
			foreach ($query as $row)
			{
					 $coursetagresult =explode('_',$row['course_Tag']);
					 for( $i=0;$i<count($coursetagresult);$i++)
					 $temp =$temp .'<option >'. $coursetagresult[$i]. '</option>';
			}
			$temp =$temp .'</select>';
			echo $temp;
	}
}
?>