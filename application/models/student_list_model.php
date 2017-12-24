<?php
	class Student_list_model extends CI_Model{
		
		public function findClass($data){
			$res=$this->db->where($data)->get('student_list');
			if ($res->num_rows() > 0)
				return $res->result_array();
		}
		public function checkinClass($data){
			
			$res=$this->db->where(array('class_Id'=>$data['class_Id'],'user_Id'=>$data['user_Id'],))->get('student_list');
			if ($res->num_rows() > 0){
				$bool=True;
				return $bool;
			}
			 else {
				$bool=$this->db->insert('student_list',$data);
				return $bool;
			}
		}
		public function getParticipants($data){
			
			$res=$this->db->where($data)->get('student_list');
			if ($res->num_rows() > 0)
				return $res->result_array();
			else{
				return null;
			}
		}
		public function getParticipantsnumber(){
			$participants=array(
				'in_Class_Or_Not'=>1
			);
			$res=$this->db->where($participants)->get('student_list');
			$num=$res->num_rows();
				return $num;
		}
		public function cleantable(){
			$bool=$this->db->truncate('student_list'); 
			return $bool;
		}
	}