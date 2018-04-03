<?php
	class User_model extends CI_Model{
		public function getselectHim($data){
			$res=$this->db->where_in('user_Id',$data)->get('user');
			return $res->result_array();
		}
		public function getAll(){
			$res=$this->db->where(array('user_Identity'=>1))->get('user');
			return $res->result_array();
		}
		
		public function checkHim($data){
			$res=$this->db->where(array('user_Info'=>$data['user_Info']))->get('user');
			if ($res->num_rows() > 0){
				return $res->result_array();
			}
			else{
				return NULL;
			}
		}
		public function getFromId($data){
			$res=$this->db->where(array('user_Id'=>$data['user_Id']))->get('user');
			if ($res->num_rows() > 0){
				return $res->result_array();
			}
			else{
				return NULL;
			}
		}
		public function findHim($data){
			$res=$this->db->where($data)->get('user');
			return $res->result_array();
		}
		
		public function addUser($data){
			$res=$this->db->where(array('user_Info'=>$data['user_Info']))->get('user');
			if ($res->num_rows() > 0){
				$bool='OK';
				return $bool;
			}
			 else {
				$bool=$this->db->insert('user',$data);
				return $bool;
			}
		}
		
	}
	