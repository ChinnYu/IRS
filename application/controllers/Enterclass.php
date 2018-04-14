<?php

class Enterclass extends MY_Controller
{
	public function index()
	{
		$this->load->view('FOUNDCLASS/StudentEnterClass.html');
	}

	public function loginunusual()
	{
		//do nothing because lose this function ,url may broke
	}

	public function showwait()
	{
		$this->load->view('FOUNDCLASS/WaitingPage.html');
	}

	//使用Cache查詢
	public function enterqr()
	{
		if (isset($_SESSION['url'])) {//直接pin跳轉
			$this->load->library('MP_Cache');
			$pin_pass = array(
				'PIN' => $_SESSION['url']['pin']
			);
			$cdata = $this->mp_cache->get($pin_pass['PIN']);
			if ($cdata === false) {
				$this->load->view('FOUNDCLASS/StudentEnterClass_error.html');
			} else {
				$this->setseinfo(array('thingsname' => 'classandquiz', 'class_Id' => $cdata['class_Id'], 'quiz_Id' => $cdata['quiz_Id'], 'PIN' => $pin_pass['PIN']));
				unset($_SESSION['url']);
				header("Location: ./waitpage");
			}
		} else {
			$this->load->library('MP_Cache');
			$pin_pass = array(
				'PIN' => $this->input->post('pin')
			);
			$cdata = $this->mp_cache->get($pin_pass['PIN']);
			if ($cdata === false) {
				$this->load->view('FOUNDCLASS/StudentEnterClass_error.html');
			} else {
				$this->setseinfo(array('thingsname' => 'classandquiz', 'class_Id' => $cdata['class_Id'], 'quiz_Id' => $cdata['quiz_Id'], 'PIN' => $pin_pass['PIN']));
				header("Location: ./waitpage");
			}
		}
	}

	public function getCourseyt()
	{
		$times = date("Y/m/d");
		$time_array = explode("/", $times);
		$term;
		$cyear = $time_array[0] - 1911;
		if ($time_array[1] >= 2 && $time_array[1] <= 8) {
			$term = 2;
		} else {
			$term = 1;
		}
		$data = array(
			'course_Year' => $cyear,
			'course_Term' => $term
		);
		return $data;
	}
}
