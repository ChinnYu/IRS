<?php
	class Course_model extends CI_Model{
		
		public function getAll(){
			$res=$this->db->get('course');
			return $res->result_array();
		}
		
		public function findCourse($data){
			$res=$this->db->where($data)->get('course');
			return $res->result_array();
			
		}
		public function updateCourse($data){
			$bool=$this->db->update('course',$data,array('course_No'=>$data['course_No'],'course_Year'=>$data['course_Year'],'course_Term'=>$data['course_Term']));
			return $bool;
		}
		public function deleteCourse($data){
			$bool=$this->db->delete('course',array('course_No'=>$data['course_No'],'course_Year'=>$data['course_Year'],'course_Term'=>$data['course_Term']));
			return $bool;
		}
	
	}