<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Auth extends AUTH_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('login');

		$this->load->library(array(
			'form_validation'
		));
	}

	public function index()
	{
		redirect('login');
	}

	public function login()
	{
		if ($this->input->post()) {
			$this->load->library('form_validation');

			$valid = $this->form_validation;

			$username = $this->input->post('login');
			$password = $this->input->post('password');
			$shift_id = !empty($this->input->post('shift_id'))
				? $this->input->post('shift_id')
				: FALSE;

			$valid->set_rules('login', 'Login', 'required');
			$valid->set_rules('password', 'Password', '');
			$valid->set_rules('shift_id', 'Shift', '');

			if ($valid->run()) {

				if ($this->simple_login->login($username, $password, $shift_id)) {
					redirect();
				} else {
					redirect('login');
				}
			} else {
				$this->session->set_flashdata(array(
					'response_status' => 'error',
					'message' => 'Maaf Username atau Password Anda salah!'
				));

				redirect('login');
			}
		}

		$data = array(
			'login_by_username' => (config_item('login_by_username') and config_item('use_username')),
			'login_by_email' => config_item('login_by_email'),
			'option_shift' => config_item('shift') !== FALSE ? $this->db->order_by("IDShift")->get("SIMmShift")->result() : FALSE,
		);

		$this->template
			->build("auth/login_with_bg", (isset($data) ? $data : NULL));
	}

	public function medics($type = 'outpatient')
	{
		switch ($type) {
			case 'inpatient':
				$type_service = 'RI';
				$redirect = "poly/inpatient";
				break;
			case 'outpatient':
				$type_service = 'RJ';
				$redirect = "poly/outpatient";
				break;
			case 'laboratory':
				$type_service = 'PENUNJANG';
				$redirect = "laboratory";
				break;
		}

		if ($this->input->post()) {
			$this->session->set_userdata([$type => [
				'doctor_id' => $this->input->post('doctor_id'),
				'nurse_id' => $this->input->post('nurse_id'),
				'section_id' => $this->input->post('section_id')
			]]);
			redirect($redirect);
		}

		$data = [
			'type' => $type,
			'item' => $this->session->userdata($type),
			'option_section' => option_section(['StatusAktif' => 1, 'TipePelayanan' => $type_service]),
			'option_doctor' => option_doctor(),
			// 'option_nurse' => $type_service == 'PENUNJANG' ? option_analys() : option_nurse() ,
			'option_nurse' =>  option_nurse(),
			'option_analys' => option_analys(),
		];

		if ($this->input->is_ajax_request()) {
			$this->load->view("auth/medics", $data);
		} else {
			$this->template->build("auth/medics", $data);
		}
	}

	public function pharmacy()
	{
		$checkSaldo = $this->db->where('Tanggal', date('Y-m-01'))->get('GD_trPostedBulanan')->row();

		if (empty($checkSaldo)) {
			$this->db->query("EXEC CreateTutupBukuStokLewat");
		}

		if ($this->input->post()) {
			$this->session->set_userdata(['pharmacy' => [
				'section_id' => $this->input->post('section_id')
			]]);
			redirect("pharmacy");
		}

		$data = [
			'item' => $this->session->userdata('pharmacy'),
			'option_section' => option_section(['StatusAktif' => 1, 'TipePelayanan' => 'FARMASI']),
		];

		if ($this->input->is_ajax_request()) {
			$this->load->view("auth/pharmacy", $data);
		} else {
			$this->template->build("auth/pharmacy", $data);
		}
	}

	public function logout()
	{
		if ($this->input->is_ajax_request() || $this->input->post()) {
			if ($this->input->post("confirm")) {
				$this->simple_login->logout();
				redirect($this->input->post("r_url"));
			}
			$this->load->view("modal/logout", array());
		} else {

			$this->simple_login->logout();
			redirect("login");
		}
	}

	public function forget_password()
	{
		if ($this->input->post()) {
			$data_post = $this->input->post('f');

			$find_user = $this->db->where($data_post)->get("user");

			if ($find_user->num_rows() > 0) {
				$penduduk = $this->db->where("nik", $find_user->row()->nik)->get("penduduk")->row();

				$penduduk = (object) array_merge((array) $penduduk, (array) $find_user->row());

				$cek_reset_password = $this->db->where(array("id_user" => $penduduk->id_user, "status" => "Belum Terpakai"))->count_all_results("reset_password");

				if ($cek_reset_password) {
					$this->session->set_flashdata(array(
						"response_status" => "danger",
						"message" => "Maaf link permintaan reset kata sandi Anda telah dikirim! Silahkan cek Email Anda."
					));

					redirect('login');
				}

				$this->send_reset_password($penduduk);
			} else {

				$this->session->set_flashdata(array(
					"response_status" => "danger",
					"message" => "Email dan Nomor Induk Kependudukan Tidak ditemukan!"
				));
			}
		}

		$this->template
			->build("forgot_password_form", (isset($data) ? $data : NULL));
	}

	private function send_reset_password($penduduk = NULL)
	{
		if (empty($penduduk)) {
			return FALSE;
		}

		$this->load->library('email');

		$this->email->set_newline("\r\n");
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'smtp.gmail.com';
		$config['smtp_port'] = 465;
		$config['smtp_crypto'] = 'ssl';
		$config['smtp_user'] = 'info.desaabiansemal@gmail.com';
		$config['smtp_from_name'] = 'Admin Desa Abiansemal';
		$config['smtp_pass'] = "abiansemal123";
		$config['wordwrap'] = TRUE;
		$config['newline'] = "\r\n";
		$config['mailtype'] = 'html';
		$this->email->initialize($config);

		$this->email->from('info.desaabiansemal@gmail.com');
		$this->email->to($penduduk->email);
		$this->email->subject(" Reset Password - $penduduk->nama ");

		$link_reset = sprintf("%sreset_password/%s", base_url(), md5(time()));

		$message = "Hai $penduduk->username,
        	<br/>
			Anda meminta reset kata sandi. <br/>
			Untuk mengubah kata sandi Anda, klik link berikut di browser Anda :<br/>
			<a href=\"$link_reset\">$link_reset</a>
			<br/> 

			Terima Kasih <br/>
			Desa Abiansemal &copy; Copyright " . date("Y") . " www.desaabiansemal.com ";

		$this->email->message($message);
		if ($this->email->send()) {
			$this->session->set_flashdata(array(
				"response_status" => "success",
				"message" => "Email Reset Password sudah dikirim. Silahkan periksa Email Anda!"
			));

			$data_reset = array(
				"id_user" => $penduduk->id_user,
				"link" => $link_reset,
				"status" => "Belum Terpakai"
			);

			$this->db->insert("reset_password", $data_reset);

			redirect('login');
		} else {
			echo $this->email->print_debugger();
			exit;
			$this->session->set_flashdata(array(
				"response_status" => "danger",
				"message" => "<span class=\"text-center\">Terjadi Kesalahan, Email Reset Password tidak terkirim. Silahkan Hubungi Admin!</span>"
			));

			redirect('auth/forget_password');
		}
	}

	public function reset_password($link)
	{
		$find_reset = $this->db->where("link", current_url())->get("reset_password");

		if ($find_reset->num_rows() == 0) {
			$this->session->set_flashdata(array(
				"response_status" => "danger",
				"message" => "Terjadi Kesalahan! link permintaan reset kata sandi Anda salah!"
			));

			redirect('login');
		}

		$reset = $find_reset->row();

		if ($reset->status == "Terpakai") {
			$this->session->set_flashdata(array(
				"response_status" => "danger",
				"message" => "Maaf ! Link reset telak terpakai."
			));

			redirect('login');
		}

		if ($this->input->post()) {

			$password = $this->input->post('password');
			$password_ulang = $this->input->post('password_ulang');

			if ($password != $password_ulang) {
				$this->session->set_flashdata(array(
					"response_status" => "danger",
					"message" => "Terjadi kesalahan! Ulang password tidak sama!"
				));
			} elseif ($this->db->update("user", array("password" => $password), array('id_user' => $reset->id_user)) and $this->db->update("reset_password", array("status" => "Terpakai"), array(' link' => current_url()))) {
				$this->session->set_flashdata(array(
					"response_status" => "success",
					"message" => "Reset kata sandi berhasil! Silahkan Login."
				));

				redirect('login');
			} else {

				$this->session->set_flashdata(array(
					"response_status" => "danger",
					"message" => "Reset kata sandi gagal! Silahkan ulangi."
				));
			}
		}

		$this->template
			->build("reset_password_form", (isset($data) ? $data : NULL));
	}
}
