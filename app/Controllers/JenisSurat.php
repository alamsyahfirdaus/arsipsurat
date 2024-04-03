<?php

namespace App\Controllers;

class JenisSurat extends BaseController
{
	public function __construct()
	{
		// Menginisialisasi koneksi database dan model JenisSuratModel
		$this->db 	 = \Config\Database::connect();
		$this->model = new \App\Models\JenisSuratModel;
	}

	public function index()
	{
		// Menyiapkan data untuk halaman index jenis surat
		$data = array(
			'title'		=> 'Jenis Surat',
			'heading' 	=> 'Daftar Jenis Surat',
		);

		// Mengembalikan tampilan halaman index jenis surat dengan data yang disiapkan
		return view('section/index_jenis_surat.php', $data);
	}

	public function list_jenis_surat()
	{
		// Mengambil data jenis surat untuk ditampilkan dalam format DataTables
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
			$id .= '<input type="text" name="nama_jenis_surat_' . $field->id_jenis_surat . '" value="' . $field->nama_jenis_surat . '">';
			$id .= '</div>';

			$row[]	= $id;
			$row[]	= '<div style="text-align: left;">' . $field->nama_jenis_surat . '</div>';

			// Menyiapkan tombol edit dan hapus untuk setiap baris data
			$button = '<div class="btn-group btn-group-sm">';
			$button .= '<button type="button" class="btn btn-primary" onclick="edit_data(' . $field->id_jenis_surat . ')"><i class="fas fa-edit"></i></button>';
			$button .= '<button type="button" class="btn btn-danger" onclick="delete_data(' . $field->id_jenis_surat . ')"><i class="fas fa-trash"></i></button>';
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

	public function save_jenis_surat()
	{
		// Mengambil data jenis surat yang akan disimpan atau diubah
		$query = $this->model->find($this->request->getPost('id_jenis_surat'));

		// Menentukan label untuk field nama jenis surat
		$nama_jenis_surat	= 'Jenis Surat';

		// Menentukan apakah nama jenis surat harus unik
		if (isset($query->id_jenis_surat)) {
			$is_unique_js = $query->nama_jenis_surat != $this->request->getPost('nama_jenis_surat') ? '|is_unique[jenis_surat.nama_jenis_surat]' : '';
		} else {
			$is_unique_js = '|is_unique[jenis_surat.nama_jenis_surat]';
		}

		// Menyiapkan aturan validasi untuk field nama jenis surat
		$listFields = array(
			'nama_jenis_surat' 	=> [$nama_jenis_surat 	=> 'required' . $is_unique_js],
		);

		$setRules = array();
		foreach ($listFields as $key => $value) {
			foreach ($value as $label => $rule) {
				$setRule = array(
					'label' => $label,
					'rules' => $rule
				);
			}
			// Menyiapkan pesan kesalahan validasi jika menggunakan bahasa Indonesia
			if (session()->get('language') == 1) {
				$setRule['errors'] =  array(
					'required' 				=> '{field} harus diisi.',
					'numeric' 				=> '{field} hanya boleh berisi angka.',
					'is_unique' 			=> '{field} sudah terdaftar.',
					'alpha_numeric_space' 	=> '{field} hanya boleh berisi karakter alfanumerik dan spasi.',
					'is_natural_no_zero' 	=> '{field} hanya boleh berisi angka dan harus lebih besar dari nol.',
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
			// Jika validasi berhasil, siapkan data jenis surat untuk disimpan ke database
			$data = array();
			foreach ($listFields as $key => $value) {
				$data[$key] = htmlspecialchars($this->request->getPost($key));
			}

			// Jika data jenis surat sudah ada (untuk pengeditan), update data jenis surat
			if (isset($query->id_jenis_surat)) {
				$this->model->update($query->id_jenis_surat, $data);
				$message = 'Berhasil Mengubah Jenis Surat!';
			} else {
				// Jika ini adalah penambahan jenis surat baru, sisipkan data jenis surat ke dalam database
				$this->model->insert($data);
				$message = 'Berhasil Menambahkan Jenis Surat!';
			}

			// Menyiapkan output yang menandakan bahwa operasi berhasil
			$output = array(
				'status' 	=> TRUE,
				'message' 	=> $message,
			);
		}

		// Menambahkan token CSRF ke dalam output
		$output[csrf_token()] = csrf_hash();
		echo json_encode($output);
	}

	public function delete_jenis_surat()
	{
		// Mengambil data jenis surat yang akan dihapus
		$query = $this->model->find($this->request->getPost('id_jenis_surat'));

		// Jika data jenis surat tidak ditemukan, lempar pengecualian PageNotFoundException
		if (empty($query->id_jenis_surat)) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		// Menghapus data jenis surat dari database
		$this->model->delete($query->id_jenis_surat);

		// Menyiapkan output yang menandakan bahwa penghapusan berhasil dilakukan
		$output['status'] = true;
		$output['message'] = 'Berhasil Menghapus Jenis Surat!';

		// Menambahkan token CSRF ke dalam output
		$output[csrf_token()] = csrf_hash();

		// Mengembalikan output dalam format JSON
		echo json_encode($output);
	}
}
