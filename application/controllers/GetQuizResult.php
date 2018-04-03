<?php
class GetQuizResult extends MY_Controller {
	
	public function takeQuizResult(){
		$this->load->model('Result_model','student_quiz');
		$data=array(
/* 			'class_Id'=>'2418',
			'quiz_Id'=>'1', */
			'class_Id'=>$_SESSION['course']['class_Id'],
			'quiz_Id'=>$_SESSION['course']['quiz_Id'],
		);
		$res=$this->student_quiz->findQuizResult($data);
		$this->load->model('User_model','user');
		for($i=0;$i<count($res);$i++){
			$dataSearchName=array('user_Id'=>$res[$i]['user_Id']);
			$resOfName=$this->user->findHim($dataSearchName);
			$res[$i]['user_Id']=$resOfName[0]['user_Name'];
		}		
		echo json_encode($res);
		/* print_r($_SESSION); */
		}
	
	/* public function takeQuizResultForStu(){
		$this->load->model('Result_model','student_quiz');
		$data=array(
			'class_Id'=>$_SESSION['course']['class_Id'],
			'quiz_Id'=>$_SESSION['course']['quiz_Id'],
		);
		$res=$this->student_quiz->findQuizResult($data);
		$this->load->model('User_model','user');
		for($i=0;$i<3;$i++){
			$dataSearchName=array('user_Id'=>$res[$i]['user_Id']);
			$resOfName=$this->user->findHim($dataSearchName);
			$res[$i]['user_Id']=$resOfName[0]['user_Name'];
		}		
		echo json_encode($res);
		/* print_r($_SESSION); */
		/*} */
	public function takeCourseName(){		
		$data=array(
			'class_Id'=>$_SESSION['course']['class_Id'],
			'quiz_Id'=>$_SESSION['course']['quiz_Id'],
		);
		$this->load->model('Course_model','course');
		$resofcourse=$this->course->findCourse(array('course_num'=>$data['class_Id']));
		echo json_encode($resofcourse);
	}
	public function takeQuizName(){		
		$data=array(
			'class_Id'=>$_SESSION['course']['class_Id'],
			'quiz_Id'=>$_SESSION['course']['quiz_Id'],
		);
		$this->load->model('Quiz_model','quiz');
		$resofquiz=$this->quiz->findknowedquiz(array('quiz_Id'=>$data['quiz_Id']));
		echo json_encode($resofquiz);
	}
		
}
?>