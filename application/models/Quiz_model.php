<?php
	class Quiz_model extends CI_Model{
		
		public function createQuiz($data){
			$res=$this->db->where(array('quiz_Name'=>$data['quiz_Name'],'user_Id'=>$data['user_Id']))->get('quiz');
			if ($res->num_rows() > 0){
				$bool='名子有重複啦';
				return $bool;
			}
			 else {
				$bool=$this->db->insert('quiz',$data);
			return $bool;
			}
		}
		public function findknowedquiz($data){
			$res=$this->db->where(array('quiz_Id'=>$data['quiz_Id']))->get('quiz');
			return $res->result_array();
		}
		public function findQuiz($data){
			$res=$this->db->where(array('user_Id'=>$data['user_Id'],'course_Name'=>$data['course_Name']))->get('quiz');
			return $res->result_array();
		}
		
		public function updateQuiz($data){
			$bool=$this->db->update('quiz',$data,array('quiz_Name'=>$data['quiz_Name'],'user_Id'=>$data['user_Id']));
			return $bool;
		}
		public function deleteQuiz($data){
			$bool=$this->db->delete('quiz',array('quiz_Name'=>$data['quiz_Name'],'user_Id'=>$data['user_Id']));
			return $bool;
		}
		
		
		
	}