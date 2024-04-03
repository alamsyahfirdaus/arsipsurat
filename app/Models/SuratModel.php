<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratModel extends Model
{
    // Menetapkan nama tabel database
    protected $table         = 'surat';

    // Menetapkan primary key dari tabel
    protected $primaryKey    = 'id_surat';

    // Menetapkan tipe data kembalian dari query
    protected $returnType    = 'object';

    // Menetapkan field yang diperbolehkan untuk diisi
    protected $allowedFields = ['no_surat', 'nama_surat', 'titimangsa_surat', 'file_surat', 'jenis_surat_id', 'id_pengguna_pengunggah', 'tanggal_unggah'];

    // Konstruktor kelas
    public function __construct()
    {
        parent::__construct();
        // Mendapatkan instance dari service request
        $this->request = \Config\Services::request();
    }

    // Fungsi internal untuk membangun query DataTable
    private function _queryDataTable($builder)
    {
        // Mendapatkan daftar field dari tabel
        $listFields     = $this->db->getFieldNames($this->table);
        $columnOrder    = $listFields;
        $columnSearch   = $listFields;
        $orderBy        = [$this->primaryKey => 'desc'];

        // Menambahkan kondisi WHERE untuk jenis surat
        $builder->where('jenis_surat_id', $this->request->getPost('jenis_surat_id'));

        $i = 0;
        // Membangun bagian pencarian
        foreach ($columnSearch as $item) {
            if (@$this->request->getPost('search')['value']) {
                if ($i === 0) {
                    $builder->groupStart();
                    $builder->like($item, $this->request->getPost('search')['value']);
                } else {
                    $builder->orLike($item, $this->request->getPost('search')['value']);
                }
                if (count($columnSearch) - 1 == $i)
                    $builder->groupEnd();
            }
            $i++;
        }

        // Menetapkan urutan hasil jika disediakan
        if ($this->request->getPost('order')) {
            $builder->orderBy($columnOrder[$this->request->getPost('order')['0']['column']], $this->request->getPost('order')['0']['dir']);
        } elseif (isset($orderBy)) {
            $builder->orderBy(key($orderBy), $orderBy[key($orderBy)]);
        }
    }

    // Fungsi internal untuk menghitung jumlah hasil terfilter
    private function _countFiltered($builder)
    {
        $this->_queryDataTable($builder);
        return $builder->countAllResults();
    }

    // Fungsi untuk mendapatkan data dalam format DataTable
    public function getDataTable()
    {
        // Memulai pembangunan query
        $builder = $this->db->table($this->table);

        // Mengonfigurasi query DataTable
        $this->_queryDataTable($builder);

        // Mengatur batasan hasil yang akan ditampilkan
        if ($this->request->getPost('length') != -1)
            $builder->limit($this->request->getPost('length'), $this->request->getPost('start'));

        // Menjalankan query dan mendapatkan hasil
        $query = $builder->get()->getResult();

        // Mengembalikan hasil query dalam format yang sesuai dengan DataTable
        return array(
            'dataTable'         => $query,
            'recordsTotal'      => $builder->countAllResults(),
            'recordsFiltered'   => $this->_countFiltered($builder),
        );
    }
}
