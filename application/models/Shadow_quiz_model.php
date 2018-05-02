<?php
	class Shadow_quiz_model extends CI_Model{
		
		public function createShadow_quiz($data){
			$bool=$this->db->insert('shadow_quiz',$data);
			return $bool;
		}
		public function findShadow_quiz($data){
			$this->db->select('shadow_Quiz_Id');
			$res=$this->db->where($data)->get('shadow_quiz');
			if($res->num_rows() > 0){
				return $res->result_array();
			}
		}
		public function findUseTime($data){
			$this->db->select('usetime_List');
			$res=$this->db->where($data)->get('shadow_quiz');
			if($res->num_rows() > 0){
				return $res->result_array();
			}else{
				return null;
			}
		}
		public function updateShadow_quiz($data,$opinion){
			$bool=$this->db->update('shadow_quiz',$opinion,array('shadow_Quiz_Id'=>$data['shadow_Quiz_Id']));
			return $bool;
		}
		
		public function updateUseTime($data){
			$res=$this->db->where(array('shadow_Quiz_Id'=>$data['shadow_Quiz_Id']))->get('shadow_quiz');
			if ($res->num_rows() > 0){
				$res=$res->result_array();
				$usetime_List= $res[0]['usetime_List']."@".$data['usetime_List'];
				$data_update=array(
					'usetime_List'=>$usetime_List,
				);
				$bool=$this->db->update('shadow_quiz',$data_update,array('shadow_Quiz_Id'=>$data['shadow_Quiz_Id']));
				return $bool;
			}
		}
		
		public function updatescore($data){
			$bool=$this->db->update('shadow_quiz',array('quiz_Score_List' =>$data['quiz_Score_List']),array('shadow_Quiz_Id' =>$data['shadow_Quiz_Id']));
			return $bool;
		}
		
		public function clearPin($data){
			$bool=$this->db->update('shadow_quiz',array('current_Pin' =>$data['current_Pin']),array('shadow_Quiz_Id' =>$data['shadow_Quiz_Id']));
			return $bool;
		}
		public function deleteshadow($data){
			$bool=$this->db->delete('shadow_quiz',array('class_Id'=>$data['class_Id'],'current_Pin'=>$data['current_Pin']));
			return $bool;
		}
		
	}