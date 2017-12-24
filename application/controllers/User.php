<?php
	class User extends CI_Controller{
		public function checkMethod_student(){//用來url 限制
			$this->load->library('session');
			if($_SESSION['user']['user_Identity']!='0'){
				die('非法連結,無權限觀看');
			};
		}
		
		public function checkMethod_teacher(){//用來url 限制
			$this->load->library('session');
			if($_SESSION['user']['user_Identity']!='1'){
				die('非法連結,無權限觀看');
			};
		}
		
		
		/* public function testdb(){//刪除學生休的課
			$this->load->model('Student_course_model','student_course');
			$data=array(
				'user_Id'=>9,
				'course_No'=>'4105210_01',
				'course_Year'=>'106',
				'course_Term'=>'1',
			);
			$res=$this->student_course->deleteStudentcourse($data);
			var_dump($res);
		} */
		
		/* public function testdb(){//修改學生休的課
			$this->load->model('Student_course_model','student_course');
			$data=array(
				'user_Id'=>9,
				'user_Name'=>'LBJ',
				'course_No'=>'4105210_01',
				'course_Name'=>'llll',
				'course_Year'=>'106',
				'course_Term'=>'1',
			);
			$res=$this->student_course->updateStudentcourse($data);
			var_dump($res);
		} */
		
		/* public function testdb(){//創學生休的課
			$this->load->model('Student_course_model','student_course');
			$data=array(
				'user_Id'=>9,
				'user_Name'=>'LBJ',
				'course_No'=>'4105210_01',
				'course_Name'=>'dsds',
				'course_Year'=>'106',
				'course_Term'=>'1',
			);
			$res=$this->student_course->createStudentcourse($data);
			var_dump($res);
		} */
		
		/* public function testdb(){//找學生休的課
			$this->load->model('Student_course_model','student_course');
			$data=array(
				'user_Id'=>9,
			);
			$res=$this->student_course->findStudentCourse($data);
			var_dump($res);
		} */
		
		/* public function testdb(){//找這堂課的學生名單
			$this->load->model('Student_course_model','student_course');
			$data=array(
				'course_No'=>'4105210_01',
				'course_Year'=>'106',
				'course_Term'=>'1',
			);
			$res=$this->student_course->findStudent($data);
			var_dump($res);
		} */
		/* public function testdb(){//刪除開課
			$this->load->model('Course_model','course');
			$data=array(
				'user_Id'=>27,
				'course_Year'=>'106',
				'course_Term'=>'1',
				'course_No'=>'88888',
			);
			$res=$this->course->deleteCourse($data);
			var_dump($res);
		} */
		
		/* public function testdb(){//修改開課
			$this->load->model('Course_model','course');
			$data=array(
				'user_Id'=>27,
				'course_Year'=>'106',
				'course_Term'=>'1',
				'course_Name'=>'kkkk ',
				'course_No'=>'88888',
			);
			$res=$this->course->updateCourse($data);
			var_dump($res);
		} */
		
		/* public function testdb(){//開課
			$this->load->model('Course_model','course');
			$data=array(
				'user_Id'=>27,
				'course_Year'=>'106',
				'course_Term'=>'1',
				'course_Name'=>'d d d ',
				'course_No'=>'88888',
			);
			$res=$this->course->createCourse($data);
			var_dump($res);
		} */
		
	
		
		/* public function testdb(){//找此人的開課
			$this->load->model('Course_model','course');
			$data=array(
				'user_Id'=>21,
				'course_Year'=>'106',
				'course_Term'=>'1',
			);
			$res=$this->course->findCourse($data);
			var_dump($res);
		} */
		
		/* public function testdb(){//刪除測驗
			$this->load->model('Quiz_model','quiz');
			$data=array(
				'user_Id'=>2,
				'quiz_Name'=>'2017/10/26',	
			);
			$res=$this->quiz->deleteQuiz($data);
		} */
		
		/* public function testdb(){//修改測驗
			$this->load->model('Quiz_model','quiz');
			$data=array(
				'user_Id'=>2,
				'quiz_Name'=>'2017/10/26',	
			);
			$res=$this->quiz->updateQuiz($data);
			//var_dump($res);
		} */
		
		/* public function testdb(){//刪除題庫題目
		$this->load->model('question_model','question');//findpin
		$questiondefault=array(
			'question_Id'=>24
		);
		$bool=$this->question->deleteQuestion($questiondefault);
		var_dump($bool);
		} */
		
		/* public function testdb(){//建學生考卷答案與紀錄答案
			$this->load->model('Student_quiz_model','student_quiz');
			$data=array(
				'class_Id'=>'10',
				'user_Id'=>'4',
				'answer_List'=>'BBDD',
				'random_List'=>'DDBB',
				'student_Opinion'=>3,
				'score_List'=>'10@10@5@6@',
				'student_Quiz_Total_Score'=>53
			);
			$res=$this->student_quiz->createStudent_quiz($data);
			var_dump($res);
		} */
		
		/* public function testdb(){//查詢shadow測驗(個人化)//需處理重複案問題
			$this->load->model('Shadow_quiz_model','shadow_quiz');
			$data=array(
				'quiz_Id'=>'6'
			);
			$res=$this->shadow_quiz->findShadow_quiz($data);
			var_dump($res);
		} */
		
		/* public function testdb(){//新增shadow測驗(個人化)
			$this->load->model('Shadow_quiz_model','shadow_quiz');
			$data=array(
				'quiz_Id'=>6,
				'quiz_Score_List'=>'LCY_50@LBJ_49@',
				'class_Id'=>10,
				'quiz_Time'=>6,
				'shadow_Quiz_opinion'=>3
			);
			$res=$this->shadow_quiz->createShadow_quiz($data);
			var_dump($res);
		} */
		
		
		/* public function testdb(){//抓此人所創測驗
			$this->load->model('Quiz_model','quiz');
			$data=array(
				'user_Id'=>2
			);
			$res=$this->quiz->findQuiz($data);
			var_dump($res);
		} */
		
		/* public function testdb(){//新增測驗
			$this->load->model('Quiz_model','quiz');
			$data=array(
				'user_Id'=>2,
				'quiz_Name'=>'2017/10/26',
				'irs_Quiz_QuestionI_Id'=>'23_'	
			);
			$res=$this->quiz->createQuiz($data);
			var_dump($res);
		} */
		
		/* public function testdb(){//新增至老師名單,但先需判斷此人是否已在USER中，應為連貫動作
			$this->load->model('Teacher_model','teacher');
			$data=array(
				'user_Id'=>5,
			);
			$res=$this->teacher->addTea_user($data);
			var_dump($res);
		} */ 
		
		/* public function testdb(){//修改學生名單學號等等
			$this->load->model('Student_model','student');
			$data=array(
				'user_Id'=>4,
				'student_Id'=>'6888888'
			);
			$res=$this->student->updateStu_user($data);
			var_dump($res);
		} */ 
		
		/* public function testdb(){//新增至學生名單,但先需判斷此人是否已在USER中，應為連貫動作
			$this->load->model('Student_model','student');
			$data=array(
				'user_Id'=>4,
				'user_Name'=>'LOO',
				'student_Id'=>'655555554'
			);
			$res=$this->student->addStu_user($data);
			var_dump($res);
		} */
		
		/* public function testdb(){//新增人員
			$this->load->model('User_model','user');
			$data=array(
				'user_Identity'=>1,
				'user_Name'=>'光晉',
				'user_Email'=>'光晉@CCU'
			);
			$res=$this->user->addUser($data);
			var_dump($res);
		} */
		
		/* public function testdb(){//修改題庫題目
		$this->load->model('question_model','question');//findpin
		$questiondefault=array(
			'question_Chapter'=>'1-3',
			'question_Lession'=>'線性代數',
			'question_Content'=>'請問5+1=?',
			'question_Options'=>'A_2@B_3@C_4@D_6',
			'question_Types'=>'2',
			'question_Tag'=>'bad',
			'question_Time'=>'3',
		);
		$bool=$this->question->updateQuestion($questiondefault);
		//var_dump($bool);
		} */

		
		/* public function testdb(){//修改題庫題目
		$this->load->model('question_model','question');//findpin
		$questiondefault=array(
			'question_Id'=>'2',
			'user_Id'=>'2',
			'question_Chapter'=>'1-888',
			'question_Lession'=>'線性代數',
			'question_Content'=>'請問5+1=?',
			'question_Options'=>'A_2@B_3@C_4@D_6',
			'question_Types'=>'2',
			'question_Tag'=>'bad',
			'question_Time'=>'3',
		);
		$bool=$this->question->updateQuestion($questiondefault);
		var_dump($bool);
		} */
			
			
		/* public function testdb(){//新增題庫題目
			$this->load->model('question_model','question');
			$this->load->model('answer_model','answer');
			$questiondefault=array(
				'question_Id'=>'2',
				'user_Id'=>'2',
				'question_Chapter'=>'1-2',
				'question_Lession'=>'線性代數',
				'question_Content'=>'請問5+1=?',
				'question_Options'=>'A_2@B_3@C_4@D_6',
				'question_Types'=>'2',
				'question_Tag'=>'bad',
				'question_Time'=>'3',
				'used_Times'=>'6'
			);
			$ans=array(
				'question_Id'=>'2',
				'answer'=>'6'
			);
			$bool=$this->question->createQuestion($questiondefault);//新增題庫題目
			$bool=$this->answer->setAns($ans);//設置答案
			var_dump($bool);
		}  */
		
		/* public function testdb(){//抓題庫題目
			$this->load->model('question_model','question');
			$chapterdefault=array(
				'user_Id'=>'2',
				//'question_Chapter'=>'1-1',
			);
			$res=$this->question->findQuestion($chapterdefault);
			var_dump($res);
		} */
		
		/* public function testdb(){//抓題庫題目_chapter分
			$this->load->model('question_model','question');
			$chapterdefault=array(
				'user_Id'=>'2',
				'question_Chapter'=>'1-1',
			);
			$res=$this->question->findQuestion($chapterdefault);
			var_dump($res);
		} */
		
		
		
		/* public function testdb(){//建教室
			$this->load->model('this_class_model','this_class');
			$userprofile=array(
				'user_Id'=>'T0002',
				'class_Name'=>'om5g',
				'PIN'=>8888559, //這要random
				'class_Id'=>'10'
			);
			$bool=$this->this_class->createClass($userprofile);
			var_dump($bool);
		} */
		
		/* public function testdb(){//pincheck&點名等等
			
			$this->load->model('this_class_model','this_class');//findpin
			$PIN=array(
				'PIN'=>'8888559'
			);
			$classId=$this->this_class->findPin($PIN); 
			
			$this->load->model('student_list_model','student_list');
			$data=array(
				'class_Id'=>$classId[0]['class_Id'],
				'user_Id'=>'4',
				'in_Class_Or_Not'=>1,
				'user_Name'=>'LOO'
			);
			$bool=$this->student_list->checkinClass($data);//紀錄有進入教室
			$res=$this->student_list->getParticipants();//點名有到的人
			$res=$this->student_list->getParticipantsnumber();//點名有到的人數
			echo $res;
			 
		} */	
		
		/* public function testdb(){//找歷史成績
			$this->load->model('student_quiz_model','student_quiz');
			$data=array(
				'user_Id'=>'T0003',
			);
			$res=$this->student_quiz->findGradehistory($data);
			var_dump($res);
			echo '<br>';
			echo $res[0]['student_Quiz_Total_Score'];
		} */
		/* public function testdb(){//User_model
			$this->load->model('User_model','user');
			$data=array(
				'user_Name'=>'LBJ',
				'user_Identity'=>1
			);
			$res=$this->user->findHim($data);
			var_dump($res);
		} */
		
		
		
		
		public function test2(){
			$this->_test1();
		}
	}