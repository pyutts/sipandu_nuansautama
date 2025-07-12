<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ErrorController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('helperjs');
    }

    // Fungsi untuk menampilkan halaman error 404
    public function error_404()
    {
        $this->output->set_status_header(404);
        $data['title'] = "404 | Halaman Tidak Ditemukan";
        $this->load->view('errors/custom/error_404', $data);
    }

    // Fungsi untuk menampilkan halaman error 403
    public function error_403()
    {
        $this->output->set_status_header(403);
        $data['title'] = "403 | Akses Ditolak";
        $this->load->view('errors/custom/error_403', $data);
    }
}
