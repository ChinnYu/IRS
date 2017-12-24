<?php
	class Question_model extends CI_Model{
		
		public function findQuestion($data){
			$res=$this->db->where($data)->get('question');
			return $res->result_array();
		}
		
		public function findselectQuestion($data){
			$res=$this->db->where_in('question_Id',$data)->get('question');
			return $res->result_array();
		}
		
		public function createQuestion($data){
			$res=$this->db->where(array('question_Id'=>$data['question_Id']))->get('question');
			if ($res->num_rows() > 0){
				$bool=$this->db->update('question',$data,array('question_Content'=>$data['question_Content']));
				return $bool;
			}
			 else {
				$bool=$this->db->insert('question',$data);
				return $bool;
			}
		}
		public function updateQuestion($data){
			$bool=$this->db->update('question',$data,array('question_Id'=>$data['question_Id'],'user_Id'=>$data['user_Id']));
			return $bool;
		}
		public function deleteQuestion($data){
			$bool=$this->db->delete('question',array('question_Id'=>$data['question_Id']));
			return $bool;
		}
	
	}