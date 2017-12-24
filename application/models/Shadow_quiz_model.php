<?php
	class Shadow_quiz_model extends CI_Model{
		
		public function createShadow_quiz($data){
			$bool=$this->db->insert('shadow_quiz',$data);
			return $bool;
		}
		
		
		public function updateShadow_quiz($data,$opinion){
			$bool=$this->db->update('shadow_quiz',$opinion,array('quiz_Id'=>$data['quiz_Id'],'class_Id' =>$data['class_Id']));
			return $bool;
		}
		
	}