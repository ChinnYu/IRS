<?php
	class Student_model extends CI_Model{
		public function addStu_user($data){
			$res=$this->db->where(array('user_Id'=>$data['user_Id']))->get('student');
			if ($res->num_rows() > 0){
				$bool='OK';
				return $bool;
			}
			 else {
				$bool=$this->db->insert('student',$data);
				return $bool;
			}
		}
		public function updateStu_user($data){
			$res=$this->db->where(array('user_Id'=>$data['user_Id']))->get('student');
				if ($res->num_rows() > 0){
					$bool=$this->db->update('student',$data,array('user_Id'=>$data['user_Id']));
					$bool='OK';
					return $bool;
				}
		}
	}