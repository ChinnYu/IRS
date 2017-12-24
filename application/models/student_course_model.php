<?php
	class Student_course_model extends CI_Model{
		
		public function findStudent($data){
			$res=$this->db->where($data)->get('student_course');
			return $res->result_array();
			
		}
		public function findStudentCourse($data){
			$res=$this->db->where($data)->get('student_course');
			return $res->result_array();
			
		}
		
		public function createStudentcourse($data){
			$res=$this->db->where(array('user_Id'=>$data['user_Id'],'course_No'=>$data['course_No'],'course_Year'=>$data['course_Year'],'course_Term'=>$data['course_Term']))->get('student_course');
			if ($res->num_rows() > 0){
				return TRUE;
			}
			 else {
				$bool=$this->db->insert('student_course',$data);
				return $bool;
			}
		}
		public function updateStudentcourse($data){
			$bool=$this->db->update('student_course',$data,array('user_Id'=>$data['user_Id'],'course_No'=>$data['course_No'],'course_Year'=>$data['course_Year'],'course_Term'=>$data['course_Term']));
			return $bool;
		}
		public function deleteStudentcourse($data){
			$bool=$this->db->delete('student_course',array('user_Id'=>$data['user_Id'],'course_No'=>$data['course_No'],'course_Year'=>$data['course_Year'],'course_Term'=>$data['course_Term']));
			return $bool;
		}
	
	}