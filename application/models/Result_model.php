<?php
class Result_model extends CI_Model{
		
		public function findQuizResult($data){
			$this->db->where(array('class_Id'=>$data['class_Id'], 'quiz_Id'=>$data['quiz_Id']));
			$this->db->select('user_Id, answer_List, student_Quiz_Total_Score');
			$this->db->order_by("student_Quiz_Total_Score","desc");
			$res=$this->db->get('student_quiz');
			if ($res->num_rows() > 0)
				return $res->result_array();
		}

		public function findThisPerson($data){
			$this->db->where('user_Id', $data['user_Id']);
			$this->db->select('user_Name');			
			$res=$this->db->get('user');
			if ($res->num_rows() > 0)
				return $res->result_array();
		}	
	}