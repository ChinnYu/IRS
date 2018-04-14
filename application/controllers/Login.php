<?php

class Login extends CI_Controller
{

	public function index()
	{
		$this->load->library('session');
		if (!isset($_SESSION['user'])) {
			if (empty($_COOKIE['info'])) {
				$this->load->view('FOUNDCLASS/login.html');
			} else {
				date_default_timezone_set("Asia/Taipei");
				$data = base64_decode($_COOKIE['info']);
				$data = $this->decrypt_cookies($data);
				$cookie_array = explode("-", $data);
				$userinfo = array(
					'user_Id' => $cookie_array[0]
				);
				$this->load->model('User_model', 'user');
				$res = $this->user->getFromId($userinfo);
				$date = mktime(0, 0, 0, $cookie_array[2], $cookie_array[3], $cookie_array[1]);
				$date_check = strtotime("now");
				$date_expire = $date_check - $date;
				if ($date_expire > 0) {
					$date_expire = $date_expire / 3600 / 24;
					$para_coo = 1;
					if ($date_expire < 7 && $date_expire > 6) {
						$this->session->set_userdata('update_coo', $para_coo);
					}
				}
				if (!isset($_SESSION['user'])) {
					$user = array('user_Id' => $res[0]['user_Id'], 'user_Name' => $res[0]['user_Name'], 'user_Identity' => $res[0]['user_Identity']);
					$this->session->set_userdata('user', $user);
				}
				if ($_SESSION['user']['user_Identity'] == 1) {
					header("Location: ./studenthome");
				} else if ($_SESSION['user']['user_Identity'] == 0) {
					header("Location: ./teacherhome");
				}
			}
		} else {
			if ($_SESSION['user']['user_Identity'] == 1) {
				header("Location: ./studenthome");
			} else if ($_SESSION['user']['user_Identity'] == 0) {
				header("Location: ./teacherhome");
			}
		}
	}

	public function setinfo()
	{
		$remember = $this->input->post('remember');
		$user_pass = array(
			'user_Info' => $this->input->post('account'),
			'user_pwd' => $this->input->post('pwd')
		);
		$para_coo = 1;
		$id_pwd = $user_pass['user_Info'] . ':' . $user_pass['user_pwd'];
		$enc_arg = $this->encrypt_user($id_pwd);
		$enc_arg = urlencode($enc_arg);
		$temp = $this->curl($enc_arg);
		if ($temp != "fault") {
			$userinfo = array(
				'user_Info' => $temp
			);
			$this->load->model('User_model', 'user');
			$res = $this->user->checkHim($userinfo);
			if ($res != NULL) {
				$this->load->library('session');
				$this->load->helper('url');
				if (!isset($_SESSION['user'])) {
					$user = array('user_Id' => $res[0]['user_Id'], 'user_Name' => $res[0]['user_Name'], 'user_Identity' => $res[0]['user_Identity']);
					$this->session->set_userdata('user', $user);
				}
				if ($remember == 1) {
					$this->session->set_userdata('remember', $para_coo);
				}
				if ($res[0]['user_Identity'] == 1) {
					if (isset($_SESSION['url'])) {
						header("Location: ./checkqr");
					} else {
						header("Location: ./studenthome");
					}
				} else if ($res[0]['user_Identity'] == 0) {
					header("Location: ./teacherhome");
				}
			} else {
				echo '有登入了,但無權限,請洽管理員';
			}
		} else {
			header("Location: ./Login");
		}
	}

	public function loginerror()
	{
		$this->load->view('FOUNDCLASS/login_error.html');
	}

	public function logout()
	{
		$this->load->library('session');
		session_destroy();
		header("Location: ./");
	}

	public function encrypt_user($data)
	{
		$key = 'ecourse@irs#!';
		$encryption_key = base64_decode($key);
		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
		$encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
		return base64_encode($encrypted . '::' . $iv);
	}

	public function curl($enc_arg)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://ecourse.ccu.edu.tw/php/api/irs.php?action=verifyUser&arg=" . $enc_arg);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($curl);
		curl_close($curl);
		$temp = trim($response, "\xEF\xBB\xBF");
		return $temp;
	}
}
