<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LaporanController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $this->load->model('PenghuniModel');
        $this->load->model('PenanggungJawabModel');
        $this->load->model('WilayahModel');
        $this->load->model('KalingModel');
        $this->load->library('session');
        $this->load->library('helperjs');
        $this->load->library('Dompdf_lib');
    }

    // Fungsi untuk menampilkan halaman laporan
    public function index()
    {
        $data['title'] = 'Laporan | SIPANDU NUANSA UTAMA';
        $data['wilayah'] = $this->WilayahModel->get_all();
        $this->load->view('document/laporan/laporan_views', $data);
    }

    // Fungsi untuk cetak laporan pendatang berdasarkan data penanggung jawab
    public function report_pj()
    {
        $data['penghuni_data'] = $this->PenghuniModel->getPendatangByPJAndKaling();

        if (empty($data['penghuni_data'])) {
            $response = [
                'status' => 'error',
                'message' => 'Lengkapi Data Pendatang dan Kaling terlebih dahulu'
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
            return;
        }

        $data['tanggal'] = date('d F Y');

        $html = $this->load->view('document/laporan/cetak_laporan_detail_pj', $data, true);
        $this->dompdf_lib->loadHtml($html);
        $this->dompdf_lib->setPaper('legal', 'landscape');
        $this->dompdf_lib->render();
        $this->dompdf_lib->stream('LAP_PENDATANG_PERPJ.pdf', 0);
    }

    // Fungsi untuk cetak laporan semua pendatang
    public function report_all()
    {
        $data['all_pendatang'] = $this->PenghuniModel->getAllPendatang();

        if (empty($data['all_pendatang'])) {
            $response = [
                'status' => 'error',
                'message' => 'Data pendatang tidak tersedia'
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
            return;
        }

        $data['tanggal'] = date('d F Y');

        $wilayah = $this->WilayahModel->get_all();
        if (!empty($wilayah)) {
            $data['kaling'] = $this->KalingModel->getByWilayahId($wilayah[0]->id);
        }

        $html = $this->load->view('document/laporan/cetak_laporan_all', $data, true);
        $this->dompdf_lib->loadHtml($html);
        $this->dompdf_lib->setPaper('legal', 'landscape');
        $this->dompdf_lib->render();
        $this->dompdf_lib->stream('LAP_ALL.pdf', 0);
    }

    // fungsi halaman laporan
    public function laporan()
    {
        $data['wilayah'] = $this->db->get('wilayah')->result();
        if (empty($data['wilayah'])) {
            $response = [
                'status' => 'error',
                'message' => 'Data wilayah tidak tersedia'
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
            return;
        }
        $this->load->view('document/laporan/laporan_views', $data);
    }
}
