<?php
	class Rollcall_model extends CI_Model{
		
		public function createRollcall($data){
			$bool=$this->db->insert('rollcall',$data);
			return $bool;	
		}

	}