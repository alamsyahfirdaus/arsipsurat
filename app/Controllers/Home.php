<?php

namespace App\Controllers;

class Home extends BaseController
{
	// Konstruktor kelas Home
	public function __construct()
	{
		// Menginisialisasi koneksi ke database
		$this->db    = \Config\Database::connect();
		// Membuat instance dari model UserModel
		$this->model = new \App\Models\UserModel;
	}

	// Method untuk menampilkan halaman utama
	public function index()
	{

		// Menyiapkan data untuk ditampilkan di halaman
		$data = array(
			// Menentukan judul berdasarkan bahasa yang dipilih (diasumsikan bahasa 2 adalah Bahasa Inggris)
			'title' 		=> session()->get('language') == 2 ? 'Home' : 'Beranda',
			'jenis_surat'  	=> $this->db->table('jenis_surat')->get()->getResult(),
		);

		// Mengembalikan view halaman utama dengan data yang sudah disiapkan
		return view('section/index_home1.php', $data);
	}

	public function user()
	{
		// Memuat helper form untuk digunakan dalam halaman ini
		helper('form');

		// Mengambil data pengguna berdasarkan id_pengguna yang tersimpan dalam sesi
		$query = $this->model->find(session()->get('id_pengguna'));

		// Jika data pengguna tidak ditemukan, lempar pengecualian PageNotFoundException
		if (empty($query->id_pengguna)) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		// Menyiapkan data untuk ditampilkan di halaman
		$data = array(
			// Judul halaman
			'title'			=> 'Pengguna',
			// Judul di heading halaman
			'heading' 		=> 'Daftar Pengguna',
			// ID pengguna
			'id_pengguna'   => $query->id_pengguna,
		);

		// Mengembalikan view halaman pengguna dengan data yang sudah disiapkan
		return view('section/index_user.php', $data);
	}


	public function list_pengguna()
	{
		// Mendapatkan data pengguna dari model menggunakan metode getDataTable()
		$bulider 	= $this->model->getDataTable();
		$data  		= array();
		$start 		= $this->request->getPost('start');
		$no    		= $start > 0 ? $start + 1 : 1;
		// Melakukan iterasi untuk setiap baris data pengguna
		foreach ($bulider['dataTable'] as $field) {
			$start++;
			$row    = array();

			// Membuat kolom ID pengguna dengan input tersembunyi untuk setiap baris
			$id = '<div style="text-align: center;">' . $no++ . '</div>';
			$id .= '<div style="display: none;">';
			$id .= '<input type="text" name="nama_pengguna_' . $field->id_pengguna . '" value="' . $field->nama_pengguna . '">';
			$id .= '<input type="text" name="email_' . $field->id_pengguna . '" value="' . $field->email . '">';
			$id .= '</div>';

			// Menyimpan status 'disabled' untuk tombol hapus jika pengguna yang sedang diiterasi adalah pengguna yang saat ini sedang masuk
			$disabled = $field->id_pengguna == session()->get('id_pengguna') ? 'disabled' : '';

			// Menyusun baris data pengguna
			$row[]	= $id;
			$row[]	= '<div style="text-align: left;">' . $field->nama_pengguna . '</div>';
			$row[]	= '<div style="text-align: left;">' . $field->email . '</div>';

			// Membuat tombol edit dan hapus untuk setiap baris data pengguna
			$button = '<div class="btn-group btn-group-sm">';
			$button .= '<button type="button" class="btn btn-primary" onclick="edit_data(' . $field->id_pengguna . ')"><i class="fas fa-edit"></i></button>';
			$button .= '<button type="button" class="btn btn-danger" onclick="delete_data(' . $field->id_pengguna . ')" ' . $disabled . '><i class="fas fa-trash"></i></button>';
			$button .= '</div>';

			$row[]	= '<div style="text-align: center;">' . $button . '</div>';

			// Menambahkan baris data pengguna ke dalam array data
			$data[] = $row;
		}

		// Membuat output JSON yang berisi data tabel pengguna
		$output = array(
			'draw'  			=> $this->request->getPost('draw'),
			'data'  			=> $data,
			'recordsTotal'  	=> $bulider['recordsTotal'],
			'recordsFiltered'  	=> $bulider['recordsFiltered'],
		);

		// Menambahkan token CSRF ke dalam output
		$output[csrf_token()] = csrf_hash();
		// Mencetak output sebagai JSON
		echo json_encode($output);
	}

	public function save_pengguna()
	{
		// Mengambil data pengguna berdasarkan id_pengguna yang diterima dari permintaan POST
		$query = $this->model->find($this->request->getPost('id_pengguna'));

		// Menentukan label untuk nama pengguna dan konfirmasi password berdasarkan bahasa yang dipilih
		$nama_pengguna  = session()->get('language') == 2 ? 'Full Name' : 'Nama Lengkap';
		$passconf 	    = session()->get('language') == 2 ? 'Confirm Password' : 'Konfirmasi Password';

		// Mendefinisikan aturan validasi untuk setiap field
		$listFields['nama_pengguna'] = [$nama_pengguna 	=> 'required|alpha_space'];

		// Jika data pengguna sudah ada (untuk pengeditan), tentukan aturan tambahan untuk email dan password
		if (isset($query->id_pengguna)) {
			// Tentukan aturan validasi untuk email, termasuk pengecekan apakah email sudah unik
			$iu_email = $query->email != $this->request->getPost('email') ? '|is_unique[pengguna.email]' : '';
			// Jika password diisi, tambahkan aturan validasi untuk password dan konfirmasi password
			if ($this->request->getPost('password1')) {
				$listFields['password1'] = ['Password' => 'required|min_length[6]'];
				$listFields['password2'] = [$passconf => 'required|matches[password1]'];
			}
		} else {
			// Jika ini adalah penambahan pengguna baru, tentukan aturan validasi untuk email, password, dan konfirmasi password
			$iu_email = '|is_unique[pengguna.email]';
			$listFields['password1'] = ['Password' => 'required|min_length[6]'];
			$listFields['password2'] = [$passconf => 'required|matches[password1]'];
		}

		// Tentukan aturan validasi untuk email
		$listFields['email'] = ['Email' => 'required|valid_email' . $iu_email];

		// Siapkan aturan validasi dengan label dan rules untuk setiap field
		$setRules = array();
		foreach ($listFields as $key => $value) {
			foreach ($value as $label => $rule) {
				$setRule = array(
					'label' => $label,
					'rules' => $rule
				);
			}
			// Jika bahasa yang dipilih adalah Bahasa Indonesia, tambahkan pesan kesalahan validasi dalam bahasa Indonesia
			if (session()->get('language') == 1) {
				$setRule['errors'] =  array(
					'required' 				=> '{field} harus diisi.',
					'alpha_space' 			=> '{field} hanya boleh berisi karakter alfabet dan spasi.',
					'valid_email' 			=> '{field} tidak valid.',
					'is_unique' 			=> '{field} sudah terdaftar.',
					'min_length' 			=> '{field} harus memiliki panjang minimal {param} karakter.',
					'matches' 			    => '{field} tidak sama dengan {param}.',
				);
			}
			$setRules[$key] = $setRule;
		}

		// Membuat objek validasi dan menetapkan aturan validasi
		$validation = \Config\Services::validation();
		$validation->setRules($setRules);

		// Jika validasi tidak berhasil, kembalikan pesan kesalahan validasi
		if (!$validation->withRequest($this->request)->run()) {
			$output = array(
				'status'	=> false,
				'errors'	=> $validation->getErrors(),
			);
		} else {
			// Jika validasi berhasil, siapkan data pengguna untuk disimpan ke database
			$data = array(
				'nama_pengguna' => htmlspecialchars($this->request->getPost('nama_pengguna')),
				'email'         => htmlspecialchars($this->request->getPost('email')),
			);

			// Jika password diisi, tambahkan password ke data yang akan disimpan
			if ($this->request->getPost('password1')) {
				$data['password'] = sha1($this->request->getPost('password1'));
			}

			// Jika data pengguna sudah ada (untuk pengeditan), update data pengguna
			if (isset($query->id_pengguna)) {
				$data['updated_at']	= date('Y-m-d H:i:s');
				$this->model->update($query->id_pengguna, $data);
				// Jika bahasa yang dipilih adalah Bahasa Inggris, kirimkan pesan berhasil untuk pengeditan pengguna
				if (session()->get('language') == 2) {
					$message = 'Successfully Changed User!';
				} else {
					$message = 'Berhasil Mengubah Pengguna!';
				}
				// Jika pengguna yang diedit adalah pengguna yang saat ini masuk, tampilkan pesan sukses
				if ($query->id_pengguna == session()->get('id_pengguna')) {
					session()->setFlashdata('success', $message);
					$output['id_pengguna'] = $query->id_pengguna;
				}
			} else {
				// Jika ini adalah penambahan pengguna baru, sisipkan data pengguna ke dalam database
				$data['is_active']		= '1';
				$data['created_at']		= date('Y-m-d H:i:s');
				$this->model->insert($data);
				// Jika bahasa yang dipilih adalah Bahasa Inggris, kirimkan pesan berhasil untuk penambahan pengguna
				if (session()->get('language') == 2) {
					$message = 'Successfully Added User!';
				} else {
					$message = 'Berhasil Menambahkan Pengguna!';
				}
			}

			// Menyusun output yang akan dikirimkan sebagai respons, menandakan bahwa operasi berhasil
			$output['status']  = TRUE;
			$output['message'] = $message;
		}

		// Menambahkan token CSRF ke dalam output
		$output[csrf_token()] = csrf_hash();
		// Mengembalikan output dalam format JSON
		echo json_encode($output);
	}

	public function delete_pengguna()
	{
		// Mengambil data pengguna berdasarkan id_pengguna yang diterima dari permintaan POST
		$query = $this->model->find($this->request->getPost('id_pengguna'));

		// Jika data pengguna tidak ditemukan, lempar pengecualian PageNotFoundException
		if (empty($query->id_pengguna)) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		// Menghapus data pengguna dari database
		$this->model->delete($query->id_pengguna);

		// Menyiapkan output yang menandakan bahwa penghapusan berhasil dilakukan
		$output['status'] = true;
		// Menentukan pesan berhasil penghapusan berdasarkan bahasa yang dipilih
		if (session()->get('language') == 2) {
			$output['message'] = 'Successfully Delete User!';
		} else {
			$output['message'] = 'Berhasil Menghapus Pengguna!';
		}

		// Menambahkan token CSRF ke dalam output
		$output[csrf_token()] = csrf_hash();

		// Mengembalikan output dalam format JSON
		echo json_encode($output);
	}
}