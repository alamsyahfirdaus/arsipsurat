<?php

namespace App\Controllers;

class Surat extends BaseController
{
	public function __construct()
	{
		// Konstruktor kelas Surat yang menginisialisasi koneksi database dan model SuratModel
		$this->db 	 = \Config\Database::connect();
		$this->model = new \App\Models\SuratModel;
	}

	public function index()
	{
		// Menyiapkan data untuk halaman index rekap surat
		$model   = new \App\Models\RekapSuratModel;
		$data = array(
			'title'		=> 'Rekap Surat',
			'heading' 	=> 'Daftar Surat',
			'list_date'	=> $model->findDate(), // Mendapatkan daftar tanggal surat
		);

		// Mengembalikan tampilan halaman index rekap surat dengan data yang disiapkan
		return view('section/index_rekap_surat.php', $data);
	}

	public function jenis_surat($id_jenis_surat)
	{
		// Mengambil data jenis surat berdasarkan id yang didekodekan
		$model = new \App\Models\JenisSuratModel;
		$query = $model->find(base64_decode($id_jenis_surat));

		// Menyiapkan judul dan judul halaman berdasarkan bahasa sesi
		$title 		= session()->get('language') == 2 ? 'Mail Data' : 'Data Surat';
		$heading 	= 'Daftar ' . $query->nama_jenis_surat;

		// Menyiapkan data untuk ditampilkan di halaman jenis surat
		$data = array(
			'title'				=> $title,
			'heading' 			=> $heading,
			'id_jenis_surat' 	=> $query->id_jenis_surat,
			'jenis_surat' 		=> $query->nama_jenis_surat
		);

		// Mengembalikan tampilan halaman jenis surat dengan data yang disiapkan
		return view('section/index_surat.php', $data);
	}

	public function list_surat()
	{
		// Mendapatkan data surat untuk ditampilkan dalam format DataTables
		$bulider 	= $this->model->getDataTable();
		$data  		= array();
		$start 		= $this->request->getPost('start');
		$no    		= $start > 0 ? $start + 1 : 1;
		foreach ($bulider['dataTable'] as $field) {
			$start++;
			$row    = array();

			// Menyiapkan baris data dengan nomor urut dan field yang sesuai
			$id = '<div style="text-align: center;">' . $no++ . '</div>';
			$id .= '<div style="display: none;">';
			$id .= '<input type="text" name="no_surat_' . $field->id_surat . '" value="' . $field->no_surat . '">';
			$id .= '<input type="text" name="nama_surat_' . $field->id_surat . '" value="' . $field->nama_surat . '">';
			$id .= '<input type="text" name="titimangsa_surat_' . $field->id_surat . '" value="' . date('d/m/Y', strtotime($field->titimangsa_surat)) . '">';
			$id .= '</div>';

			$row[]	= $id;
			$row[]	= '<div style="text-align: left;">' . $field->no_surat . '</div>';
			$row[]	= '<div style="text-align: left;">' . $field->nama_surat . '</div>';
			$row[]	= '<div style="text-align: left;">' . $this->_spit_date($field->titimangsa_surat) . '</div>';

			// Menyiapkan tautan untuk mengunduh file surat
			$file_link = '<div style="text-align: left;"><a href="' . base_url('public/uploads/' . $field->file_surat) . '" target="_blank">' . $field->file_surat . '</a></div>';
			$row[]  = $file_link;

			// Menyiapkan tombol edit dan hapus untuk setiap baris data
			$button = '<div class="btn-group btn-group-sm">';
			$button .= '<button type="button" class="btn btn-primary" onclick="edit_data(' . $field->id_surat . ')"><i class="fas fa-edit"></i></button>';
			$button .= '<button type="button" class="btn btn-danger" onclick="delete_data(' . $field->id_surat . ')"><i class="fas fa-trash"></i></button>';
			$button .= '</div>';

			$row[]	= '<div style="text-align: center;">' . $button . '</div>';

			$data[] = $row;
		}

		// Menyiapkan output dalam format JSON untuk DataTables
		$output = array(
			'draw'  			=> $this->request->getPost('draw'),
			'data'  			=> $data,
			'recordsTotal'  	=> $bulider['recordsTotal'],
			'recordsFiltered'  	=> $bulider['recordsFiltered'],
		);

		$output[csrf_token()] = csrf_hash();
		echo json_encode($output);
	}

	private function _spit_date($tanggal = null)
	{
		// Memecah format tanggal berdasarkan bahasa sesi pengguna
		if ($tanggal) {
			$model   = new \App\Models\RekapSuratModel;
			return session()->get('language') == 2 ? date('d F Y', strtotime($tanggal)) : $model->splitDate(date('Y-m-d', strtotime($tanggal)));
		} else {
			return '-';
		}
	}

	// Fungsi untuk menyimpan data surat
	public function save_surat()
	{
		// Mengambil data surat berdasarkan id_surat yang diterima dari permintaan
		$query = $this->model->find($this->request->getPost('id_surat'));

		// Menyiapkan variabel untuk menyimpan status unggahan file surat
		$uploaded_file_surat = empty($query->id_surat) ? '|uploaded[file_surat]' : '';

		if (isset($query)) {
			$no_surat = $query->no_surat != $this->request->getPost('no_surat') ? '|is_unique[surat.no_surat]' : '';
		} else {
			$no_surat = '|is_unique[surat.no_surat]';
		}
		
		// Daftar field beserta aturan validasi yang diperlukan
		$listFields = array(
			'no_surat'        	=> ['No Surat'        => 'required' . $no_surat],
			'nama_surat'        => ['Nama Surat'      => 'required|alpha_numeric_space'],
			'titimangsa_surat'  => ['Tanggal Surat'   => 'required|valid_date[d/m/Y]'],
			'file_surat'        => ['File Surat'      => 'max_size[file_surat,1024]|ext_in[file_surat,pdf,doc,docx]' . $uploaded_file_surat],
			'jenis_surat_id'    => ['Jenis Surat'     => 'required|is_natural_no_zero'],
		);

		// Menyiapkan aturan validasi dan pesan kesalahan sesuai dengan field dan rule
		$setRules = array();
		foreach ($listFields as $key => $value) {
			foreach ($value as $label => $rule) {
				$setRule = array(
					'label' => $label,
					'rules' => $rule
				);
			}
			// Menambahkan pesan kesalahan khusus untuk setiap aturan validasi
			$setRule['errors'] =  array(
				'required'                  => '{field} harus diisi.',
				'numeric'                   => '{field} hanya boleh berisi angka.',
				'is_unique'                 => '{field} sudah terdaftar.',
				'alpha_numeric_space'       => '{field} hanya boleh berisi karakter alfanumerik dan spasi.',
				'is_natural_no_zero'        => '{field} hanya boleh berisi angka dan harus lebih besar dari nol.',
				'uploaded'                  => 'Gagal mengunggah {field}.',
				'max_size'                  => 'Ukuran {field} terlalu besar. Maksimum 1MB.',
				'ext_in'                    => 'Jenis file {field} tidak valid. Hanya PDF, DOC, atau DOCX yang diperbolehkan.',
			);
			$setRules[$key] = $setRule;
		}

		// Memuat objek validasi dari konfigurasi framework
		$validation = \Config\Services::validation();
		// Menetapkan aturan validasi yang telah disiapkan sebelumnya
		$validation->setRules($setRules);

		// Menjalankan validasi terhadap permintaan saat ini
		if (!$validation->withRequest($this->request)->run()) {
			// Jika validasi gagal, menyiapkan pesan kesalahan
			$output = array(
				'status'    => false,
				'errors'    => $validation->getErrors(),
			);
		} else {
			// Jika validasi berhasil, menyiapkan data surat untuk disimpan
			$data = array();
			foreach ($listFields as $key => $value) {
				$data[$key] = htmlspecialchars($this->request->getPost($key));
			}

			// Proses unggah file hanya jika ada file yang baru diunggah
			if ($this->request->getFile('file_surat')->isValid()) {
				$file_surat = $this->request->getFile('file_surat');
				$newFileName = $file_surat->getRandomName(); // Membangkitkan nama unik untuk file

				// Memindahkan file ke direktori yang diinginkan
				$file_surat->move(ROOTPATH . 'public/uploads', $newFileName);
				$data['file_surat'] = $newFileName; // Menyimpan nama file yang diunggah ke dalam database
			} elseif (empty($query->id_surat)) {
				// Jika tidak ada file yang diunggah dan ini adalah operasi penambahan data, set nilai file_surat menjadi null
				$data['file_surat'] = null;
			} else {
				// Jika tidak ada file yang diunggah, tetapi ini adalah operasi perubahan data, gunakan nilai file_surat yang ada
				$data['file_surat'] = $query->file_surat;
			}

			// Mengubah format tanggal ke format yang sesuai untuk penyimpanan dalam database
			$data['titimangsa_surat'] = date('Y-m-d', strtotime($this->request->getPost('titimangsa_surat')));

			// Memeriksa apakah ini operasi perubahan data atau penambahan data
			if (isset($query->id_surat)) {
				// Jika ini operasi perubahan data, lakukan pembaruan data surat
				$this->model->update($query->id_surat, $data);
				$message = 'Berhasil Mengubah Data Surat!';
			} else {
				// Jika ini operasi penambahan data, tambahkan data surat baru ke dalam database
				$data['id_pengguna_pengunggah'] = session()->get('id_pengguna');
				$data['tanggal_unggah'] = date('Y-m-d H:i:s');
				$this->model->insert($data);
				$message = 'Berhasil Menambahkan Data Surat!';
			}

			// Menyiapkan output dengan status berhasil dan pesan yang sesuai
			$output = array(
				'status'    => TRUE,
				'message'   => $message,
			);
		}

		// Menambahkan token CSRF ke output sebelum mengirimkan output sebagai JSON
		$output[csrf_token()] = csrf_hash();
		// Mengonversi output menjadi format JSON dan mencetaknya
		echo json_encode($output);
	}

	public function delete_surat()
	{
		// Mengambil data surat yang akan dihapus
		$query = $this->model->find($this->request->getPost('id_surat'));

		if (empty($query->id_surat)) {
			// Jika data surat tidak ditemukan, lempar pengecualian PageNotFoundException
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		// Hapus file fisik dari direktori jika ada
		$file_surat = $query->file_surat;
		if ($file_surat) {
			$file_path = ROOTPATH . 'public/uploads' . $file_surat;
			if (file_exists($file_path)) {
				unlink($file_path); // Hapus file jika ada
			}
		}

		// Hapus data surat dari database
		$this->model->delete($query->id_surat);

		// Menyiapkan output yang menandakan bahwa penghapusan berhasil dilakukan
		$output['status'] = true;
		$output['message'] = 'Berhasil Menghapus Data Surat!';

		$output[csrf_token()] = csrf_hash();

		// Mengonversi output menjadi format JSON dan mencetaknya
		echo json_encode($output);
	}

	public function list_rekap_surat()
	{
		// Mendapatkan data rekap surat untuk ditampilkan dalam format DataTables
		$model   	= new \App\Models\RekapSuratModel;
		$bulider 	= $model->getDataTable();
		$data  		= array();
		$start 		= $this->request->getPost('start');
		$no    		= $start > 0 ? $start + 1 : 1;
		foreach ($bulider['dataTable'] as $field) {
			$start++;
			$row    = array();

			// Menyiapkan baris data untuk ditampilkan
			$row[]	= '<div style="text-align: center;">' . $no++ . '</div>';
			$row[]	= '<div style="text-align: left;">' . $field->no_surat . '</div>';
			$row[]	= '<div style="text-align: left;">' . $field->nama_surat . '</div>';
			$row[]	= '<div style="text-align: left;">' . $this->_spit_date($field->titimangsa_surat) . '</div>';
			$row[]	= '<div style="text-align: left;">' . $field->nama_jenis_surat . '</div>';
			$data[] = $row;
		}

		// Menyiapkan output dalam format JSON untuk DataTables
		$output = array(
			'draw'  			=> $this->request->getPost('draw'),
			'data'  			=> $data,
			'recordsTotal'  	=> $bulider['recordsTotal'],
			'recordsFiltered'  	=> $bulider['recordsFiltered'],
			'tanggal'			=> $this->request->getPost('dari_tanggal') ? $model->findDate($this->request->getPost('dari_tanggal')) : [],
		);

		$output[csrf_token()] = csrf_hash();
		// Mengonversi output menjadi format JSON dan mencetaknya
		echo json_encode($output);
	}
}
