<?php
	class Student_quiz_model extends CI_Model{
		
		public function findGradehistory($data){
			$res=$this->db->where($data)->get('Student_quiz');
			if ($res->num_rows() > 0)
				return $res->result_array();
		}
		
		public function createStudent_quiz($data){
			$bool=$this->db->insert('Student_quiz',$data);
		}
		
		
	}