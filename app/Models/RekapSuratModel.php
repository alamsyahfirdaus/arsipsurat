<?php

namespace App\Models;

use CodeIgniter\Model;

class RekapSuratModel extends Model
{
    // Menetapkan nama tabel database
    protected $table         = 'surat';

    // Menetapkan primary key dari tabel
    protected $primaryKey   = 'id_surat';

    // Menetapkan tipe data kembalian dari query
    protected $returnType   = 'object';

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
        $listFields = array(
            's.id_surat',
            's.no_surat',
            's.nama_surat',
            's.titimangsa_surat',
            'js.id_jenis_surat',
            'js.nama_jenis_surat'
        );

        // Menetapkan urutan kolom
        $columnOrder    = $listFields;

        // Menetapkan kolom yang bisa dicari
        $columnSearch   = $listFields;

        // Menetapkan urutan default
        $orderBy        = ['s.id_surat' => 'desc'];

        // Memilih kolom yang diperlukan dari tabel
        foreach ($listFields as $field) {
            $builder->select($field);
        }

        // Melakukan join dengan tabel jenis_surat
        $builder->join('jenis_surat js', 's.jenis_surat_id = js.id_jenis_surat', 'left');

        // Menerapkan filter berdasarkan tanggal
        if ($this->request->getPost('dari_tanggal') && $this->request->getPost('sampai_tanggal')) {
            $builder->where('DATE(s.titimangsa_surat) BETWEEN "' . $this->request->getPost('dari_tanggal') . '" AND "' . $this->request->getPost('sampai_tanggal') . '"');
        } elseif ($this->request->getPost('dari_tanggal')) {
            $builder->where('DATE(s.titimangsa_surat)', $this->request->getPost('dari_tanggal'));
        }

        // Mengatur pencarian pada kolom yang diperbolehkan
        $i = 0;
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
        // Mendefinisikan nama tabel
        $table   = 'surat s';

        // Memulai pembangunan query
        $builder = $this->db->table($table);

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

    // Fungsi untuk mencari tanggal surat
    public function findDate($tanggal = null)
    {
        // Memulai pembangunan query
        $builder = $this->db->table($this->table);

        // Memilih kolom tanggal surat
        $builder->select('DATE(titimangsa_surat) as tanggal');

        // Menambahkan kondisi tanggal jika diberikan
        if ($tanggal) {
            $builder->where('DATE(titimangsa_surat) >', $tanggal);
        } else {
            $builder->orderBy('DATE(titimangsa_surat)', 'desc');
        }

        // Melakukan pengelompokan tanggal
        $builder->groupBy('DATE(titimangsa_surat)');

        // Menjalankan query dan mendapatkan hasil
        $query = $builder->get()->getResult();

        // Mengonversi hasil query menjadi format yang sesuai dengan kebutuhan aplikasi
        $data = array();
        foreach ($query as $row) {
            $data[$row->tanggal] = session()->get('language') == 2 ? date('d F Y', strtotime($row->tanggal)) : $this->splitDate($row->tanggal);
        }

        // Mengembalikan data tanggal
        return $data;
    }

    // Fungsi untuk memisahkan tanggal menjadi format yang sesuai
    public function splitDate($date)
    {
        // Mendefinisikan nama bulan
        $month = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );

        // Memisahkan tanggal menjadi komponen tahun, bulan, dan hari
        $split = explode('-', $date);

        // Mengembalikan tanggal dalam format yang sesuai
        return $split[2] . ' ' . $month[(int)$split[1]] . ' ' . $split[0];
    }

    // Fungsi untuk mencari nama bulan
    public function findMonth($month)
    {
        // Mengecek bahasa yang digunakan untuk menampilkan nama bulan
        if (session()->get('language') == 2) {
            return date('F', strtotime(date('Y-' . $month)));
        } else {
            // Mendefinisikan daftar nama bulan dalam bahasa Indonesia
            $data = array(
                1 => 'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            );

            // Mengembalikan nama bulan sesuai dengan indeks bulan yang diberikan
            return $data[$month];
        }
    }
}
