<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HomeController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $this->load->model('AdminModel');
        $this->load->model('PenghuniModel');
        $this->load->model('PenanggungJawabModel');
        $this->load->library('helperjs');
    }
    
    // Fungsi untuk menampilkan halaman dashboard admin
    public function index()
    {
        $data['title'] = "Dashboard | SIPANDU Nuansa Utama";
        $data['total_admin'] = $this->AdminModel->countAdmins();
        $data['total_kaling'] = $this->AdminModel->countKaling();
        $data['total_warga'] = $this->AdminModel->countWarga();
        $data['total_users'] = $this->AdminModel->countUsers();
        $data['penghuni_baru'] = $this->PenghuniModel->getPenghuniBaruCoordinates();
        $data['data_kaling'] = $this->AdminModel->getAllKaling();
        $data['data_pj'] = $this->AdminModel->getAllPenanggungJawab();
        $data['data_warga'] = $this->AdminModel->getAllWarga();

        $kepala_by_alamat = [];
        foreach ($data['data_warga'] as $warga) {
            if (strtolower($warga->status) === 'kepala keluarga') {
                $kepala_by_alamat[$warga->alamat] = $warga;
            }
        }

        $keluarga_terstruktur = [];
        foreach ($data['data_warga'] as $warga) {
            $kepala = $kepala_by_alamat[$warga->alamat] ?? null;

            if ($kepala) {
                $nama_kk = $kepala->nama_lengkap;
                $keluarga_terstruktur[$nama_kk][] = $warga;
            } else {
                $keluarga_terstruktur['Tanpa Kepala Keluarga'][] = $warga;
            }
        }
        
        ksort($keluarga_terstruktur);
        $data['data_keluarga_terkelompok'] = $keluarga_terstruktur;

        $data['data_pendatang'] = $this->AdminModel->getAllPendatang();
        $data['total_pj'] = $this->PenanggungJawabModel->countAll();
        $data['penghuni_verifikasi_bulanini'] = $this->PenghuniModel->get_verifikasi_bulanini();
        
        $this->load->view('dashboard/index_admin_views', $data);
    }
}
