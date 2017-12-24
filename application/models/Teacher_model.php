<?php
	class Teacher_model extends CI_Model{
		
		public function addTea_user($data){
			$res=$this->db->where(array('user_Id'=>$data['user_Id']))->get('teacher');
			if ($res->num_rows() > 0){
				$bool='有囉';
				return $bool;
			}
			 else {
				$bool=$this->db->insert('teacher',$data);
				return $bool;
			}
		}
		
	
	}