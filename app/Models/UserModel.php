<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    // Menentukan nama tabel yang akan digunakan
    protected $table         = 'pengguna';
    // Menentukan primary key dari tabel
    protected $primaryKey    = 'id_pengguna';
    // Menentukan jenis data yang akan dikembalikan oleh model
    protected $returnType    = 'object';
    // Menentukan field yang diizinkan untuk diisi secara massal
    protected $allowedFields = ['nama_pengguna', 'email', 'password'];

    // Konstruktor kelas UserModel
    public function __construct()
    {
        parent::__construct();
        // Menginisialisasi instance dari class Request pada objek $this->request
        $this->request = \Config\Services::request();
    }

    // Method private untuk membangun query berdasarkan data yang dikirimkan oleh DataTable
    private function _queryDataTable($builder)
    {
        // Mendapatkan daftar nama field dari tabel
        $listFields     = $this->db->getFieldNames($this->table);
        // Inisialisasi variabel untuk urutan kolom
        $columnOrder    = $listFields;
        // Inisialisasi variabel untuk kolom pencarian
        $columnSearch   = $listFields;
        // Inisialisasi urutan berdasarkan primary key secara descending
        $orderBy        = ['' . $this->table . '.' . $this->primaryKey . '' => 'desc'];

        $i = 0;
        // Melakukan iterasi untuk setiap kolom pencarian
        foreach ($columnSearch as $item) {
            // Memeriksa apakah ada kata kunci pencarian yang dikirimkan
            if (@$this->request->getPost('search')['value']) {
                if ($i === 0) {
                    $builder->groupStart();
                    $builder->like($item, $this->request->getPost('search')['value']);
                } else {
                    $builder->orLike($item, $this->request->getPost('search')['value']);
                }
                // Menutup grup pencarian jika sudah mencapai kolom terakhir
                if (count($columnSearch) - 1 == $i)
                    $builder->groupEnd();
            }
            $i++;
        }

        // Menambahkan pengurutan jika ada
        if ($this->request->getPost('order')) {
            $builder->orderBy($columnOrder[$this->request->getPost('order')['0']['column']], $this->request->getPost('order')['0']['dir']);
        } else if (isset($orderBy)) {
            $builder->orderBy(key($orderBy), $orderBy[key($orderBy)]);
        }
    }

    // Method private untuk menghitung jumlah data yang sudah difilter
    private function _countFiltered($builder)
    {
        $this->_queryDataTable($builder);
        // Menghitung jumlah data yang difilter
        return $builder->countAllResults();
    }

    // Method public untuk mendapatkan data yang akan ditampilkan oleh DataTable
    public function getDataTable()
    {
        $builder = $this->db->table($this->table);
        $this->_queryDataTable($builder);
        // Menambahkan batasan jumlah data yang akan ditampilkan
        if ($this->request->getPost('length') != -1)
            $builder->limit($this->request->getPost('length'), $this->request->getPost('start'));
        // Eksekusi query dan mengambil hasilnya
        $query = $builder->get()->getResult();

        // Mengembalikan array berisi data DataTable beserta informasi jumlah data secara keseluruhan dan yang sudah difilter
        return array(
            'dataTable'         => $query,
            'recordsTotal'      => $builder->countAllResults(),
            'recordsFiltered'   => $this->_countFiltered($builder),
        );
    }
}
