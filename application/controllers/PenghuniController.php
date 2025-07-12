<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PenghuniController extends MY_Controller 
{    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PenghuniModel');
        $this->load->model('PenanggungJawabModel'); 
        $this->load->model('KalingModel');
        $this->load->model('WilayahModel');
        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->load->library('session');
        $this->load->library('helperjs');
        $this->load->helper(['url', 'form']);
    }

    // Fungsi untuk mengupload file
    private function _upload_file($field, $prefix = 'file_')
    {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === 4) {
            return ['status' => 'no_file', 'data' => null];
        }

        $config['upload_path']   = FCPATH . 'uploads/penghuni/';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size']      = '8192'; 

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }
        
        $ext = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
        $new_name = $prefix . md5(uniqid(rand(), true)) . '.' . strtolower($ext);
        $config['file_name'] = $new_name;

        $this->load->library('upload'); 
        $this->upload->initialize($config, TRUE);

        if (!$this->upload->do_upload($field)) {
            $this->session->set_flashdata('error_upload', $this->upload->display_errors());
            return ['status' => 'error', 'data' => null];
        }

        $upload_data = $this->upload->data();
        $image_types = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array(ltrim(strtolower($upload_data['file_ext']), '.'), $image_types) && $upload_data['file_size'] > 1024) {
            $this->load->library('image_lib');
            $config_compress['image_library']  = 'gd2';
            $config_compress['source_image']   = $upload_data['full_path'];
            $config_compress['maintain_ratio'] = TRUE;
            $config_compress['width']          = 1920;
            $config_compress['height']         = 1920;
            $config_compress['quality']        = '60%';
            
            $this->image_lib->initialize($config_compress);
            if (!$this->image_lib->resize()) {
                $this->session->set_flashdata('error_compress', $this->image_lib->display_errors());
            }
            $this->image_lib->clear();
        }

        return ['status' => 'success', 'data' => $upload_data['file_name']];
    }

    // Fungsi untuk tampilan halaman di session Admin dan Kepala Lingkungan
    public function index()
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $data['penghuni_menunggu'] = $this->PenghuniModel->getByStatus('Menunggu');
        $data['penghuni_diproses'] = $this->PenghuniModel->getByStatus('Diproses');
        $data['penghuni_terverifikasi'] = $this->PenghuniModel->getByStatusVerifikasiSorted(['Diterima', 'Ditolak']);
        $data['penanggung_jawab'] = $this->PenanggungJawabModel->getAll();
        $data['title'] = "Data Pendatang | SIPANDU Nuansa Utama";
        $this->load->view('penghuni/penghuni_list_admin', $data);
    }

    // Fungsi untuk tampilan halaman detail di session Admin dan Kepala Lingkungan
    public function detail_admin($uuid)
    {
        $this->load->model('PenghuniModel');
        $data['penghuni'] = $this->PenghuniModel->getByUuid($uuid);
        $data['title'] = "Detail Data Pendatang | SIPANDU Nuansa Utama";

        if (!$data['penghuni']) {
            show_404();
        }

        $this->load->view('penghuni/penghuni_details_admin', $data);
    }

    // Fungsi untuk tampilan halaman detail di session Penanggung Jawab
    public function detail_pj($uuid)
    {
        $this->load->model('PenghuniModel');
        $data['penghuni'] = $this->PenghuniModel->getByUuid($uuid);
        $data['title'] = "Detail Data Pendatang | SIPANDU Nuansa Utama";

        if (!$data['penghuni']) {
            show_404();
        }

        $this->load->view('penghuni/penghuni_details_pj', $data);
    }

    // Fungsi untuk verifikasi data pendatang
    public function verifikasi($id, $status)
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);

        if (!$this->input->is_ajax_request()) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Invalid request method'
                ]));
            return;
        }

        $penghuni = $this->PenghuniModel->getById($id);
        if (!$penghuni) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ]));
            return;
        }

        $data = [
            'status_verifikasi' => $status
        ];

        if ($status === 'Ditolak') {
            $alasan = $this->input->post('alasan');
            if (empty($alasan)) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Alasan penolakan harus diisi'
                    ]));
                return;
            }
            $data['alasan'] = $alasan;
            $data['status_penghuni'] = 'Tidak Aktif';
            $data['tanggal_keluar'] = date('Y-m-d');
        } elseif ($status === 'Diterima') {
            $data['status_penghuni'] = 'Aktif';
            $data['alasan'] = null;
            $data['tanggal_keluar'] = null;
        }

        if ($this->PenghuniModel->update($id, $data)) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'success',
                    'message' => 'Status verifikasi berhasil diubah',
                    'data' => [
                        'status_verifikasi' => $status,
                        'status_penghuni' => $data['status_penghuni']
                    ]
                ]));
        } else {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'error',
                    'message' => 'Gagal mengubah status verifikasi'
                ]));
        }
    }

    // Fungsi untuk mengubah status pendatang menjadi nonaktif
    public function nonaktifkan_status($uuid)
    {
        $this->check_role(['Admin', 'Kepala Lingkungan', 'Penanggung Jawab']);
        
        $penghuni = $this->PenghuniModel->getByUuid($uuid);
        if (!$penghuni) {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Data tidak ditemukan'
                    ]));
                return;
            }
            $this->session->set_flashdata('error', 'Data tidak ditemukan');
            redirect('dashboard/penghuni/view');
            return;
        }

        if ($penghuni->status_verifikasi !== 'Diterima') {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Hanya data terverifikasi yang dapat diubah statusnya'
                    ]));
                return;
            }
            $this->session->set_flashdata('error', 'Hanya data terverifikasi yang dapat diubah statusnya');
            redirect('dashboard/penghuni/view');
            return;
        }

        $data = [
            'status_penghuni' => 'Tidak Aktif',
            'tanggal_keluar' => date('Y-m-d') 
        ];

        if ($this->PenghuniModel->updateByUuid($uuid, $data)) {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'success',
                        'message' => 'Status penghuni berhasil dinonaktifkan'
                    ]));
                return;
            }
            $this->session->set_flashdata('success', 'Status penghuni berhasil dinonaktifkan');
        } else {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Gagal mengubah status penghuni'
                    ]));
                return;
            }
            $this->session->set_flashdata('error', 'Gagal mengubah status penghuni');
        }
        redirect('dashboard/penghuni/view');
    }

    // Fungsi untuk mengubah status penghuni menjadi aktif kembali
    public function aktifkan_status($uuid)
    {
        $this->check_role(['Admin', 'Kepala Lingkungan', 'Penanggung Jawab']);

        $penghuni = $this->PenghuniModel->getByUuid($uuid);
        if (!$penghuni) {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Data tidak ditemukan'
                    ]));
                return;
            }
            $this->session->set_flashdata('error', 'Data tidak ditemukan');
            redirect('dashboard/penghuni/view');
            return;
        }

        if ($penghuni->status_verifikasi !== 'Diterima') {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Hanya data terverifikasi yang dapat diubah statusnya'
                    ]));
                return;
            }
            $this->session->set_flashdata('error', 'Hanya data terverifikasi yang dapat diubah statusnya');
            redirect('dashboard/penghuni/view');
            return;
        }

        $data = [
            'status_penghuni' => 'Aktif',
            'tanggal_keluar' => null
        ];

        if ($this->PenghuniModel->updateByUuid($uuid, $data)) {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'success',
                        'message' => 'Status penghuni berhasil diaktifkan kembali'
                    ]));
                return;
            }
            $this->session->set_flashdata('success', 'Status penghuni berhasil diaktifkan kembali');
        } else {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Gagal mengaktifkan kembali status penghuni'
                    ]));
                return;
            }
            $this->session->set_flashdata('error', 'Gagal mengaktifkan kembali status penghuni');
        }

        redirect('dashboard/penghuni/view');
    }

    // Fungsi untuk tampilan halaman penghuni di session Penanggung Jawab
    public function index_pj()
    {
        $this->check_role(['Penanggung Jawab']);
        $pj_id = $this->session->userdata('pj_id');
        $this->load->model('PenanggungJawabModel');
        if (!$this->PenanggungJawabModel->verifikasi_data($pj_id)) {
            redirect('dashboard/pj/validation');
            return;
        }
        $data['penghuni'] = $this->PenghuniModel->getByPJ($pj_id);
        $data['title'] = "Data Pendatang | SIPANDU Nuansa Utama";
        $this->load->view('penghuni/penghuni_list_pj', $data);
    }

    // Fungsi untuk tambah data pendatang di session Admin dan Kepala Lingkungan
    public function create_admin()
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $data['kaling'] = $this->KalingModel->getAll();
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['pj'] = $this->PenanggungJawabModel->getAll();
        $data['default_kaling_id'] = $this->session->userdata('kaling_id'); 
        $data['default_wilayah_id'] = $this->session->userdata('wilayah_id'); 
        $data['title'] = "Tambah Data Pendatang | SIPANDU Nuansa Utama";
        $this->load->view('penghuni/penghuni_create_admin', $data);
    }

    public function store_admin()
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $this->form_validation->set_rules(
            'nik',
            'NIK',
            'required|exact_length[16]|is_unique[penghuni.nik]',
            [
                'required'     => 'Kolom NIK wajib diisi.',
                'exact_length' => 'Kolom NIK harus berisi tepat 16 digit angka.',
                'is_unique'    => 'NIK ini sudah terdaftar.'
            ]
        );

        $this->form_validation->set_rules(
            'nama',
            'Nama Lengkap',
            'required',
            [
                'required' => 'Kolom Nama Lengkap wajib diisi.'
            ]
        );

        $this->form_validation->set_rules(
            'tempat_lahir',
            'Tempat Lahir',
            'required',
            [
                'required' => 'Kolom Tempat Lahir wajib diisi.'
            ]
        );

        $this->form_validation->set_rules(
            'tanggal_lahir',
            'Tanggal Lahir',
            'required',
            [
                'required' => 'Kolom Tanggal Lahir wajib diisi.'
            ]
        );

        $this->form_validation->set_rules(
            'no_hp',
            'No Handphone',
            'numeric|min_length[12]', 
            [
                'numeric'    => 'Kolom No Handphone hanya boleh berisi angka.',
                'min_length' => 'Kolom No Handphone minimal 12 digit.'
            ]
        );

        $this->form_validation->set_rules(
            'golongan_darah',
            'Golongan Darah',
            'in_list[A,B,AB,O]',
            [
                'in_list' => 'Pilihan Golongan Darah tidak valid.'
            ]
        );

        $this->form_validation->set_rules('agama', 'Agama', 'trim');
        $this->form_validation->set_rules('provinsi_asal', 'Provinsi Asal', 'required');
        $this->form_validation->set_rules('kabupaten_asal', 'Kabupaten Asal', 'required');
        $this->form_validation->set_rules('kecamatan_asal', 'Kecamatan Asal', 'required');
        $this->form_validation->set_rules('kelurahan_asal', 'Kelurahan Asal', 'required');
        $this->form_validation->set_rules('rt', 'RT', 'trim|numeric');
        $this->form_validation->set_rules('rw', 'RW', 'trim|numeric');
        $this->form_validation->set_rules('alamat_asal', 'Alamat Asal', 'required');
        $this->form_validation->set_rules('alamat_sekarang', 'Alamat Sekarang', 'required');
        $this->form_validation->set_rules('alamat_detail', 'Alamat Detail', 'required');
        $this->form_validation->set_rules('alamat_no', 'Nomor Rumah', 'required');
        $this->form_validation->set_rules('tujuan', 'Tujuan', 'required');
        $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required');
        $this->form_validation->set_rules('penanggung_jawab_id', 'Penanggung Jawab', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $this->create_admin();
            return;
        }

        
        $uploaded_files = []; 
        $upload_profil = $this->_upload_file('foto', 'pendatang_');
        if ($upload_profil['status'] === 'success') {
            $uploaded_files[] = $upload_profil['data'];
            $foto_profil = $upload_profil['data'];

        } elseif ($upload_profil['status'] === 'error') {
            $this->db->trans_rollback();
            $this->create_admin();
            return;

        } else {
            $foto_profil = null;
        }

        $upload_ktp = $this->_upload_file('ktp', 'ktp_');
        if ($upload_ktp['status'] === 'success') {
            $uploaded_files[] = $upload_ktp['data'];
            $foto_ktp = $upload_ktp['data'];
        } else {
            foreach ($uploaded_files as $file) {
                $this->hapus_file($file);
            }

            $this->db->trans_rollback();
            $this->create_admin();
            return;
        }

        $data = [
            'nik' => $this->input->post('nik'),
            'nama_lengkap' => $this->input->post('nama'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'no_hp' => $this->input->post('no_hp'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'golongan_darah' => $this->input->post('golongan_darah'),
            'agama' => $this->input->post('agama'),
            'provinsi_asal' => $this->input->post('provinsi_nama'),
            'kabupaten_asal' => $this->input->post('kabupaten_nama'),
            'kecamatan_asal' => $this->input->post('kecamatan_nama'),
            'kelurahan_asal' => $this->input->post('kelurahan_nama'),
            'rt' => $this->input->post('rt'),
            'rw' => $this->input->post('rw'),
            'alamat_asal' => $this->input->post('alamat_asal'),
            'alamat_sekarang' => $this->input->post('alamat_sekarang'),
            'alamat_detail' => $this->input->post('alamat_detail'),
            'alamat_no' => $this->input->post('alamat_no'),
            'foto_profil' => $foto_profil,
            'foto_ktp' => $foto_ktp,
            'penanggung_jawab_id' => $this->input->post('penanggung_jawab_id'),
            'kaling_id' => $this->input->post('kaling_id'),
            'wilayah_id' => $this->input->post('wilayah_id'),
            'tujuan' => $this->input->post('tujuan'),
            'tanggal_masuk' => $this->input->post('tanggal_masuk'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'status_verifikasi' => 'Diproses',
            'status_penghuni' => 'Tidak Aktif'
        ];

           $this->PenghuniModel->insert($data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            foreach ($uploaded_files as $file) {
                $this->hapus_file($file);
            }
            $this->session->set_flashdata('error', 'Gagal menambahkan data penghuni karena kesalahan database.');
            $this->create_admin();
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Data penghuni berhasil ditambahkan.');
            redirect('dashboard/penghuni/view');
        }
    }

    // Fungsi untuk tambah data pendatang di session Penanggung Jawab
    public function create_pj()
    {
        $this->check_role(['Penanggung Jawab']);

        $pj_id = $this->session->userdata('pj_id');
        if (!$pj_id) {
            $this->session->set_flashdata('error', 'Sesi tidak valid. Silakan login kembali.');
            redirect('auth');
            return;
        }

        $data['kaling'] = $this->KalingModel->getAll();
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['pj_login'] = $this->PenanggungJawabModel->getById($pj_id);
        $data['kaling'] = $this->KalingModel->getAll(); 
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['default_pj_id'] = $pj_id;
        $data['default_kaling_id'] = $data['pj_login']->kaling_id ?? $this->session->userdata('kaling_id');
        $data['default_wilayah_id'] = $data['pj_login']->wilayah_id ?? $this->session->userdata('wilayah_id');
        $data['title'] = "Tambah Data Pendatang | SIPANDU Nuansa Utama";
        $this->load->view('penghuni/penghuni_create_pj', $data);
    }

    public function store_pj()
    {
        $this->check_role(['Penanggung Jawab']);
        $this->form_validation->set_rules(
            'nik',
            'NIK',
            'required|exact_length[16]|is_unique[penghuni.nik]',
            [
                'required'     => 'Kolom NIK wajib diisi.',
                'exact_length' => 'Kolom NIK harus berisi tepat 16 digit angka.',
                'is_unique'    => 'NIK ini sudah terdaftar.'
            ]
        );

        $this->form_validation->set_rules(
            'nama',
            'Nama Lengkap',
            'required',
            [
                'required' => 'Kolom Nama Lengkap wajib diisi.'
            ]
        );

        $this->form_validation->set_rules(
            'tempat_lahir',
            'Tempat Lahir',
            'required',
            [
                'required' => 'Kolom Tempat Lahir wajib diisi.'
            ]
        );

        $this->form_validation->set_rules(
            'tanggal_lahir',
            'Tanggal Lahir',
            'required',
            [
                'required' => 'Kolom Tanggal Lahir wajib diisi.'
            ]
        );

        $this->form_validation->set_rules(
            'no_hp',
            'No Handphone',
            'numeric|min_length[12]', 
            [
                'numeric'    => 'Kolom No Handphone hanya boleh berisi angka.',
                'min_length' => 'Kolom No Handphone minimal 12 digit.'
            ]
        );

        $this->form_validation->set_rules(
            'golongan_darah',
            'Golongan Darah',
            'in_list[A,B,AB,O]',
            [
                'in_list' => 'Pilihan Golongan Darah tidak valid.'
            ]
        );

        $this->form_validation->set_rules('agama', 'Agama', 'trim');
        $this->form_validation->set_rules('provinsi_asal', 'Provinsi Asal', 'required');
        $this->form_validation->set_rules('kabupaten_asal', 'Kabupaten Asal', 'required');
        $this->form_validation->set_rules('kecamatan_asal', 'Kecamatan Asal', 'required');
        $this->form_validation->set_rules('kelurahan_asal', 'Kelurahan Asal', 'required');
        $this->form_validation->set_rules('rt', 'RT', 'trim|numeric');
        $this->form_validation->set_rules('rw', 'RW', 'trim|numeric');
        $this->form_validation->set_rules('alamat_asal', 'Alamat Asal', 'required');
        $this->form_validation->set_rules('alamat_sekarang', 'Alamat Sekarang', 'required');
        $this->form_validation->set_rules('alamat_detail', 'Alamat Detail', 'required');
        $this->form_validation->set_rules('alamat_no', 'Nomor Rumah', 'required');
        $this->form_validation->set_rules('tujuan', 'Tujuan', 'required');
        $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required');
        $this->form_validation->set_rules('penanggung_jawab_id', 'Penanggung Jawab', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $this->create_pj();
            return;
        }

        $pj_id = $this->session->userdata('pj_id');
        if (!$pj_id) {
            $this->session->set_flashdata('error', 'Penanggung Jawab tidak ditemukan. Silakan login ulang.');
            redirect('dashboard/penghuni/viewpj');
            return;
        }

        
        $uploaded_files = []; 
        $upload_profil = $this->_upload_file('foto', 'pendatang_');
        if ($upload_profil['status'] === 'success') {
            $uploaded_files[] = $upload_profil['data'];
            $foto_profil = $upload_profil['data'];
        } elseif ($upload_profil['status'] === 'error') {
            $this->db->trans_rollback();
            $this->create_pj();
            return;
        } else {
            $foto_profil = null;
        }

        $upload_ktp = $this->_upload_file('ktp', 'ktp_');
        if ($upload_ktp['status'] === 'success') {
            $uploaded_files[] = $upload_ktp['data'];
            $foto_ktp = $upload_ktp['data'];
        } else {
            foreach ($uploaded_files as $file) {
                $this->hapus_file($file);
            }
            $this->db->trans_rollback();
            $this->create_pj();
            return;
        }

        $data = [
            'nik' => $this->input->post('nik'),
            'nama_lengkap' => $this->input->post('nama'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'no_hp' => $this->input->post('no_hp'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'golongan_darah' => $this->input->post('golongan_darah'),
            'agama' => $this->input->post('agama'),
            'provinsi_asal' => $this->input->post('provinsi_nama'),
            'kabupaten_asal' => $this->input->post('kabupaten_nama'),
            'kecamatan_asal' => $this->input->post('kecamatan_nama'),
            'kelurahan_asal' => $this->input->post('kelurahan_nama'),
            'rt' => $this->input->post('rt'),
            'rw' => $this->input->post('rw'),
            'alamat_asal' => $this->input->post('alamat_asal'),
            'alamat_sekarang' => $this->input->post('alamat_sekarang'),
            'alamat_detail' => $this->input->post('alamat_detail'),
            'alamat_no' => $this->input->post('alamat_no'),
            'foto_profil' => $foto_profil,
            'foto_ktp' => $foto_ktp,
            'penanggung_jawab_id' => $this->input->post('penanggung_jawab_id'),
            'kaling_id' => $this->input->post('kaling_id'),
            'wilayah_id' => $this->input->post('wilayah_id'),
            'tujuan' => $this->input->post('tujuan'),
            'tanggal_masuk' => $this->input->post('tanggal_masuk'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'status_verifikasi' => 'Diproses',
            'status_penghuni' => 'Tidak Aktif'
        ];

        $this->PenghuniModel->insert($data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            foreach ($uploaded_files as $file) {
                $this->hapus_file($file);
            }
            $this->session->set_flashdata('error', 'Gagal menambahkan data pendatang karena kesalahan database.');
            $this->create_pj();
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Data pendatang berhasil ditambahkan dan sedang menunggu verifikasi oleh Kepala Lingkungan.');
            redirect('dashboard/penghuni/viewpj');
        }
    }

    // Fungsi untuk mengedit data pendatang di session Admin dan Kepala Lingkungan
    public function edit_admin($uuid)
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $data['kaling'] = $this->KalingModel->getAll();
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['pj'] = $this->PenanggungJawabModel->getAll();
        $data['penghuni'] = $this->PenghuniModel->getByUuid($uuid);
        $data['default_kaling_id'] = $this->session->userdata('kaling_id'); 
        $data['default_wilayah_id'] = $this->session->userdata('wilayah_id'); 
        $data['title'] = "Edit Data Pendatang | SIPANDU Nuansa Utama";

        if (!$data['penghuni']) {
            $this->session->set_flashdata('error', 'Data penghuni tidak ditemukan.');
            redirect('dashboard/penghuni/view');
            return;
        }

        $this->load->view('penghuni/penghuni_edit_admin', $data);
    }

    public function update_admin($uuid)
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $penghuni = $this->PenghuniModel->getByUuid($uuid);

        if (!$penghuni) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('dashboard/penghuni/view');
            return;
        }
            $this->form_validation->set_rules(
                'nik',
                'NIK',
                'required|exact_length[16]',
                [
                    'required'     => 'Kolom NIK wajib diisi.',
                    'exact_length' => 'Kolom NIK harus berisi tepat 16 digit angka.',
                ]
            );

            $this->form_validation->set_rules(
                'nama',
                'Nama Lengkap',
                'required',
                [
                    'required' => 'Kolom Nama Lengkap wajib diisi.'
                ]
            );

            $this->form_validation->set_rules(
                'tempat_lahir',
                'Tempat Lahir',
                'required',
                [
                    'required' => 'Kolom Tempat Lahir wajib diisi.'
                ]
            );

            $this->form_validation->set_rules(
                'tanggal_lahir',
                'Tanggal Lahir',
                'required',
                [
                    'required' => 'Kolom Tanggal Lahir wajib diisi.'
                ]
            );

            $this->form_validation->set_rules(
                'no_hp',
                'No Handphone',
                'numeric|min_length[12]', 
                [
                    'numeric'    => 'Kolom No Handphone hanya boleh berisi angka.',
                    'min_length' => 'Kolom No Handphone minimal 12 digit.'
                ]
            );

            $this->form_validation->set_rules(
                'golongan_darah',
                'Golongan Darah',
                'in_list[A,B,AB,O]',
                [
                    'in_list' => 'Pilihan Golongan Darah tidak valid.'
                ]
            );

            $this->form_validation->set_rules('agama', 'Agama', 'trim');
            $this->form_validation->set_rules('provinsi_asal', 'Provinsi Asal', 'required');
            $this->form_validation->set_rules('kabupaten_asal', 'Kabupaten Asal', 'required');
            $this->form_validation->set_rules('kecamatan_asal', 'Kecamatan Asal', 'required');
            $this->form_validation->set_rules('kelurahan_asal', 'Kelurahan Asal', 'required');
            $this->form_validation->set_rules('rt', 'RT', 'trim|numeric');
            $this->form_validation->set_rules('rw', 'RW', 'trim|numeric');
            $this->form_validation->set_rules('alamat_asal', 'Alamat Asal', 'required');
            $this->form_validation->set_rules('alamat_sekarang', 'Alamat Sekarang', 'required');
            $this->form_validation->set_rules('alamat_detail', 'Alamat Detail', 'required');
            $this->form_validation->set_rules('alamat_no', 'Nomor Rumah', 'required');
            $this->form_validation->set_rules('tujuan', 'Tujuan', 'required');
            $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required');
            $this->form_validation->set_rules('penanggung_jawab_id', 'Penanggung Jawab', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $this->edit_admin($uuid);
            return;
        }

        $this->db->trans_begin();

        $data = [
            'nik' => $this->input->post('nik'),
            'nama_lengkap' => $this->input->post('nama'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'no_hp' => $this->input->post('no_hp'),
            'golongan_darah' => $this->input->post('golongan_darah'),
            'agama' => $this->input->post('agama'),
            'provinsi_asal' => $this->input->post('provinsi_nama'),
            'kabupaten_asal' => $this->input->post('kabupaten_nama'),
            'kecamatan_asal' => $this->input->post('kecamatan_nama'),
            'kelurahan_asal' => $this->input->post('kelurahan_nama'),
            'rt' => $this->input->post('rt'),
            'rw' => $this->input->post('rw'),
            'alamat_asal' => $this->input->post('alamat_asal'),
            'alamat_detail' => $this->input->post('alamat_detail'),
            'alamat_no' => $this->input->post('alamat_no'),
            'penanggung_jawab_id' => $this->input->post('penanggung_jawab_id'),
            'kaling_id' => $this->input->post('kaling_id'),
            'wilayah_id' => $this->input->post('wilayah_id'),
            'alamat_sekarang' => $this->input->post('alamat_sekarang'),
            'tujuan' => $this->input->post('tujuan'),
            'tanggal_masuk' => $this->input->post('tanggal_masuk'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'status_verifikasi' => 'Diproses',
            'status_penghuni' => 'Tidak Aktif',
            'alasan' => null
        ];

        $old_files_to_delete = [];

        $upload_profil = $this->_upload_file('foto', 'pendatang_'); 
        if ($upload_profil['status'] === 'success') {
            $data['foto_profil'] = $upload_profil['data'];
            if ($penghuni->foto_profil) {
                $old_files_to_delete[] = $penghuni->foto_profil;
            }
        } elseif ($upload_profil['status'] === 'error') {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal mengupload foto profil.');
            $this->edit_admin($uuid);
            return;
        }

        $upload_ktp = $this->_upload_file('ktp', 'ktp_');
        if ($upload_ktp['status'] === 'success') {
            $data['foto_ktp'] = $upload_ktp['data'];
            if ($penghuni->foto_ktp) {
                $old_files_to_delete[] = $penghuni->foto_ktp;
            }
        } elseif ($upload_ktp['status'] === 'error') {
            $this->db->trans_rollback();
            if (isset($data['foto_profil'])) { $this->hapus_file($data['foto_profil']); }
            $this->session->set_flashdata('error', 'Gagal mengupload foto KTP.');
            $this->edit_admin($uuid);
            return;
        }

        $this->PenghuniModel->updateByUuid($uuid, $data);
        
       if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            if (isset($data['foto_profil'])) { $this->hapus_file($data['foto_profil']); }
            if (isset($data['foto_ktp'])) { $this->hapus_file($data['foto_ktp']); }
            $this->session->set_flashdata('error', 'Gagal memperbarui data karena kesalahan database.');
            $this->edit_admin($uuid);
        } else {
            $this->db->trans_commit();
            foreach($old_files_to_delete as $file) {
                $this->hapus_file($file);
            }
            $this->session->set_flashdata('success', 'Data pendatang berhasil diperbarui dan dikirim ulang untuk verifikasi.');
            redirect('dashboard/penghuni/view');
        }
    }

    // Fungsi untuk mengedit data pendatang di session Penanggung Jawab
    public function edit_pj($uuid)
    {
        $this->check_role(['Penanggung Jawab']);

        $pj_id = $this->session->userdata('pj_id');
        if (!$pj_id) {
            $this->session->set_flashdata('error', 'Sesi tidak valid. Silakan login kembali.');
            redirect('auth');
            return;
        }

        $data['kaling'] = $this->KalingModel->getAll();
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['penghuni'] = $this->PenghuniModel->getByUuid($uuid);
        $data['pj_login'] = $this->PenanggungJawabModel->getById($pj_id);
        $data['default_pj_id'] = $pj_id;
        $data['default_kaling_id'] = $data['pj_login']->kaling_id ?? $this->session->userdata('kaling_id');
        $data['default_wilayah_id'] = $data['pj_login']->wilayah_id ?? $this->session->userdata('wilayah_id');
        $data['title'] = "Edit Data Pendatang | SIPANDU Nuansa Utama";
        $this->load->view('penghuni/penghuni_edit_pj', $data);
    }

    public function update_pj($uuid)
    {
        $this->check_role(['Penanggung Jawab']);
        $penghuni = $this->PenghuniModel->getByUuid($uuid);
        $pj_id = $this->session->userdata('pj_id');

        if (!$penghuni || $penghuni->status_verifikasi !== 'Ditolak' || $penghuni->penanggung_jawab_id != $pj_id) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan atau tidak dapat diedit.');
            redirect('dashboard/penghuni/viewpj');
            return;
        }

        $this->form_validation->set_rules(
                'nik',
                'NIK',
                'required|exact_length[16]',
                [
                    'required'     => 'Kolom NIK wajib diisi.',
                    'exact_length' => 'Kolom NIK harus berisi tepat 16 digit angka.',
                ]
            );

            $this->form_validation->set_rules(
                'nama',
                'Nama Lengkap',
                'required',
                [
                    'required' => 'Kolom Nama Lengkap wajib diisi.'
                ]
            );

            $this->form_validation->set_rules(
                'tempat_lahir',
                'Tempat Lahir',
                'required',
                [
                    'required' => 'Kolom Tempat Lahir wajib diisi.'
                ]
            );

            $this->form_validation->set_rules(
                'tanggal_lahir',
                'Tanggal Lahir',
                'required',
                [
                    'required' => 'Kolom Tanggal Lahir wajib diisi.'
                ]
            );

            $this->form_validation->set_rules(
                'no_hp',
                'No Handphone',
                'numeric|min_length[12]', 
                [
                    'numeric'    => 'Kolom No Handphone hanya boleh berisi angka.',
                    'min_length' => 'Kolom No Handphone minimal 12 digit.'
                ]
            );

            $this->form_validation->set_rules(
                'golongan_darah',
                'Golongan Darah',
                'in_list[A,B,AB,O]',
                [
                    'in_list' => 'Pilihan Golongan Darah tidak valid.'
                ]
            );

            $this->form_validation->set_rules('agama', 'Agama', 'trim');
            $this->form_validation->set_rules('provinsi_asal', 'Provinsi Asal', 'required');
            $this->form_validation->set_rules('kabupaten_asal', 'Kabupaten Asal', 'required');
            $this->form_validation->set_rules('kecamatan_asal', 'Kecamatan Asal', 'required');
            $this->form_validation->set_rules('kelurahan_asal', 'Kelurahan Asal', 'required');
            $this->form_validation->set_rules('rt', 'RT', 'trim|numeric');
            $this->form_validation->set_rules('rw', 'RW', 'trim|numeric');
            $this->form_validation->set_rules('alamat_asal', 'Alamat Asal', 'required');
            $this->form_validation->set_rules('alamat_sekarang', 'Alamat Sekarang', 'required');
            $this->form_validation->set_rules('alamat_detail', 'Alamat Detail', 'required');
            $this->form_validation->set_rules('alamat_no', 'Nomor Rumah', 'required');
            $this->form_validation->set_rules('tujuan', 'Tujuan', 'required');
            $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required');
            $this->form_validation->set_rules('penanggung_jawab_id', 'Penanggung Jawab', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $this->edit_pj($uuid);
            return;
        }

        $data = [
            'nik' => $this->input->post('nik'),
            'nama_lengkap' => $this->input->post('nama'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'no_hp' => $this->input->post('no_hp'),
            'golongan_darah' => $this->input->post('golongan_darah'),
            'agama' => $this->input->post('agama'),
            'provinsi_asal' => $this->input->post('provinsi_nama'),
            'kabupaten_asal' => $this->input->post('kabupaten_nama'),
            'kecamatan_asal' => $this->input->post('kecamatan_nama'),
            'kelurahan_asal' => $this->input->post('kelurahan_nama'),
            'rt' => $this->input->post('rt'),
            'rw' => $this->input->post('rw'),
            'alamat_asal' => $this->input->post('alamat_asal'),
            'alamat_detail' => $this->input->post('alamat_detail'),
            'alamat_no' => $this->input->post('alamat_no'),
            'penanggung_jawab_id' => $this->input->post('penanggung_jawab_id'),
            'kaling_id' => $this->input->post('kaling_id'),
            'wilayah_id' => $this->input->post('wilayah_id'),
            'alamat_sekarang' => $this->input->post('alamat_sekarang'),
            'tujuan' => $this->input->post('tujuan'),
            'tanggal_masuk' => $this->input->post('tanggal_masuk'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'status_verifikasi' => 'Diproses',
            'status_penghuni' => 'Tidak Aktif',
            'alasan' => null
        ];

        $this->db->trans_begin();
    
        $old_files_to_delete = [];

        $upload_profil = $this->_upload_file('foto', 'pendatang_'); 
        if ($upload_profil['status'] === 'success') {
            $data['foto_profil'] = $upload_profil['data'];
            if ($penghuni->foto_profil) {
                $old_files_to_delete[] = $penghuni->foto_profil;
            }
        } elseif ($upload_profil['status'] === 'error') {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal mengupload foto profil.');
            $this->edit_pj($uuid);
            return;
        }

        $upload_ktp = $this->_upload_file('ktp', 'ktp_');
        if ($upload_ktp['status'] === 'success') {
            $data['foto_ktp'] = $upload_ktp['data'];
            if ($penghuni->foto_ktp) {
                $old_files_to_delete[] = $penghuni->foto_ktp;
            }
        } elseif ($upload_ktp['status'] === 'error') {
            $this->db->trans_rollback();
            if (isset($data['foto_profil'])) { $this->hapus_file($data['foto_profil']); }
            $this->session->set_flashdata('error', 'Gagal mengupload foto KTP.');
            $this->edit_pj($uuid);
            return;
        }

        $this->PenghuniModel->updateByUuid($uuid, $data);
        
       if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            if (isset($data['foto_profil'])) { $this->hapus_file($data['foto_profil']); }
            if (isset($data['foto_ktp'])) { $this->hapus_file($data['foto_ktp']); }
            $this->session->set_flashdata('error', 'Gagal memperbarui data karena kesalahan database.');
            $this->edit_pj($uuid);
        } else {
            $this->db->trans_commit();
            foreach($old_files_to_delete as $file) {
                $this->hapus_file($file);
            }
            $this->session->set_flashdata('success', 'Data pendatang berhasil diperbarui dan dikirim ulang untuk verifikasi.');
            redirect('dashboard/penghuni/viewpj');
        }
    }

    // Fungsi untuk menghapus data pendatang di session Admin dan Kepala Lingkungan
    private function hapus_file($filename)
    {
        if ($filename) {
            $file_path = './uploads/penghuni/' . $filename; 
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }

    public function delete($id)
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);

        $penghuni = $this->PenghuniModel->getById($id);
        if (!$penghuni) {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Data penghuni tidak ditemukan'
                    ]));
                return;
            }
            $this->session->set_flashdata('error', 'Data penghuni tidak ditemukan.');
            redirect('dashboard/penghuni/view');
            return;
        }

        $this->hapus_file($penghuni->foto_profil);
        $this->hapus_file($penghuni->foto_ktp);

        $deleted = $this->PenghuniModel->delete($id);

        if ($this->input->is_ajax_request()) {
            if ($deleted) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'success',
                        'message' => 'Data penghuni berhasil dihapus'
                    ]));
            } else {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => 'error',
                        'message' => 'Gagal menghapus data penghuni'
                    ]));
            }
            return;
        }

        if ($deleted) {
            $this->session->set_flashdata('success', 'Data penghuni berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data penghuni.');
        }

        redirect('dashboard/penghuni/view');
    }
   
    // Fungsi untuk filter data terverifikasi berdasarkan Penanggung Jawab
    // Fungsi ini akan mengembalikan data dalam format JSON
    public function filterTerverifikasiByPJ()
    {
        $this->output->set_content_type('application/json');
        
        if (!$this->input->is_ajax_request()) {
            $this->output->set_output(json_encode([
                'status' => 'error',
                'message' => 'Invalid request method'
            ]));
            return;
        }

        $pj_id = $this->input->get('pj_id');
        $status = $this->input->get('status');
        
        log_message('debug', 'Filter Terverifikasi By PJ ID: ' . $pj_id); 
        log_message('debug', 'Filter Status: ' . $status);

        $this->db->select('p.*, pj.nama_pj, p.uuid');
        $this->db->from('penghuni p');
        $this->db->join('penanggung_jawab pj', 'p.penanggung_jawab_id = pj.id');
        $this->db->where('p.status_verifikasi', 'Diterima');
        
        if ($pj_id) {
            $this->db->where('p.penanggung_jawab_id', $pj_id);
        }
        
        if ($status) {
            $this->db->where('p.status_penghuni', $status);
        }
        
        $this->db->order_by('FIELD(p.status_penghuni, "Aktif", "Tidak Aktif")', '');
        
        $data = $this->db->get()->result();

        log_message('debug', 'Data returned: ' . json_encode($data));
        $this->output->set_output(json_encode($data));
    }
}
