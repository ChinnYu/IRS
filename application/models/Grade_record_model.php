<?php
	class Grade_record_model extends CI_Model{
		
		public function findRecord($data){
			$this->db->select('user_Id,ans_List,ans_Time_List,user_Info,user_Name');
			$res=$this->db->where($data)->get('grade_record');
			if ($res->num_rows() > 0){
				return $res->result_array();
			}
			else{
				return null;
			}
		}
		
		public function createRecord($data){
			$res=$this->db->where(array('user_Id'=>$data['user_Id'],'class_Id'=>$data['class_Id']))->get('grade_record');
			if ($res->num_rows() > 0){
				$res=$res->result_array();
				$ans_List=$res[0]['ans_List']."@".$data['ans_List'];
				$ans_Time_List= $res[0]['ans_Time_List']."@".$data['ans_Time_List'];
				$data_update=array(
					'ans_List'=>$ans_List,
					'ans_Time_List'=>$ans_Time_List,
				);
				$bool=$this->db->update('grade_record',$data_update,array('user_Id'=>$data['user_Id'],'class_Id'=>$data['class_Id']));
				return $bool;
			}
			else {
				$bool=$this->db->insert('grade_record',$data);
				return $bool;
			}
		}
		
		public function deleteRecord($data){
			$bool=$this->db->delete('grade_record',array('class_Id'=>$data['class_Id'],'current_Pin'=>$data['current_Pin']));
			return $bool;
		}
	
	}