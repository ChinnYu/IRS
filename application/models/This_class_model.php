<?php
	class This_class_model extends CI_Model{
		public function findclass($PIN){
			$res=$this->db->where($PIN)->get('this_class');
			if ($res->num_rows() > 0){
				return $res->result_array();
			}
			else{
				$bool= NULL;
				return $bool;
			}
		}
		public function findPin($data){
			$res=$this->db->where($data)->get('this_class');
			return $res->result_array();
		}
		public function getAll(){
			$res=$this->db->get('this_class');
			return $res->result_array();
		}
		public function updateClass($data,$pin_pass){
			$res=$this->db->where(array('PIN'=>$data['PIN']))->get('this_class');
			if ($res->num_rows() > 0){
				$bool= false;
				return $bool;
			}
			else {
				$bool=$this->db->update('this_class',$pin_pass,array('user_Id'=>$data['user_Id'],'course_No' =>$data['course_No'],'course_Year'=>$data['course_Year'],'course_Term'=>$data['course_Term']));
				return $bool;
			}
		}
		public function clearPin($data){
			$bool=$this->db->update('this_class',array('PIN' =>$data['PIN'],'quiz_Id' =>$data['quiz_Id']),array('user_Id' =>$data['user_Id'],'class_Id' =>$data['class_Id']));
			return $bool;
		}
		
	}