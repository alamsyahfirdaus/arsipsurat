<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Auth extends Controller
{
	public function __construct()
	{
		// Inisialisasi model User dan validation dari Config\Services
		$this->model = new \App\Models\UserModel;
		$this->validation = \Config\Services::validation();
		// Set zona waktu ke Asia/Jakarta
		date_default_timezone_set('Asia/Jakarta');
	}

	public function index()
	{
		// Jika sudah login, redirect ke halaman sebelumnya, jika belum redirect ke halaman login
		if (session()->get('logged_in')) {
			return redirect()->back();
		} else {
			return redirect()->to('login');
		}
	}

	public function login()
	{
		// Jika sudah login, redirect ke halaman sebelumnya
		if (session()->get('logged_in')) {
			return redirect()->back();
		}

		// Jika metode request adalah POST, lakukan proses login, jika tidak, tampilkan halaman login
		if ($this->request->getServer('REQUEST_METHOD') == 'POST') {
			$this->_logged_in();
		} else {
			return view('section/index_login1.php');
		}
	}

	private function _logged_in()
	{
		// Ambil data pengguna berdasarkan email dari model User
		$query = $this->model->where('email', $this->request->getPost('email'))->first();

		// Set aturan validasi untuk email dan password
		$this->validation->setRules([
			'email' => [
				'label' => 'Email',
				'rules' => 'required',
				'errors' => [
					'required' => 'Email harus diisi.',
				],
			],
			'password' => [
				'label' => 'Password',
				'rules' => 'required',
				'errors' => [
					'required' => 'Password harus diisi.',
				],
			],
		]);

		// Jika validasi gagal, kirim pesan error
		if (!$this->validation->withRequest($this->request)->run()) {
			$output = array(
				'status'    => false,
				'errors'    => $this->validation->getErrors(),
			);
		} else {
			// Jika email terdaftar, cek password
			if (isset($query->id_pengguna)) {
				// Jika password sesuai, set session login
				if (sha1($this->request->getPost('password')) == $query->password) {
					session()->set([
						'logged_in'     => true,
						'id_pengguna'   => $query->id_pengguna,
						'language'      => 1,
					]);
					// Kirim pesan sukses login
					$output = array(
						'status'    => true,
						'message'   => 'Login Berhasil!',
					);
				} else {
					// Jika password salah, kirim pesan error
					$output = array(
						'status'    => false,
						'errors'    => array('password' => 'Password salah, coba lagi.'),
					);
				}
			} else {
				// Jika email belum terdaftar, kirim pesan error
				$output = array(
					'status'    => false,
					'errors'    => array('email' => 'Email belum terdaftar.'),
				);
			}
		}

		// Tambahkan token CSRF ke output dan kirim dalam format JSON
		$output[csrf_token()] = csrf_hash();
		echo json_encode($output);
	}

	public function logout()
	{
		// Hapus semua session dan redirect ke halaman login
		session()->destroy();
		return redirect()->to('login');
	}
}
