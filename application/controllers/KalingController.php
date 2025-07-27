<?php
defined('BASEPATH') or exit('No direct script access allowed');
class KalingController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_role(['Admin']);
        $this->load->model('KalingModel');
        $this->load->model('WilayahModel');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('helperjs');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->database();
    }

    // Fungsi untuk generate UUID
    private function generate_uuid()
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    // Fungsi untuk menampilkan halaman daftar Kepala Lingkungan
    public function view()
    {
        $data['title'] = "Data Kepala Lingkungan | SIPANDU Nuansa Utama";
        $data['kaling'] = $this->KalingModel->getAll();
        $this->load->view('kaling/kaling_list', $data);
    }

    // Fungsi untuk mendaftarkan Kepala Lingkungan
    public function create()
    {
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['title'] = "Buat Akun Kepala Lingkungan | SIPANDU Nuansa Utama";
        $data['default_wilayah_id'] = $this->session->userdata('wilayah_id'); 
        $this->load->view('kaling/kaling_create', $data);
    }

    public function store()
    {
        $this->form_validation->set_rules(
            'nama',
            'Nama',
            'required|is_unique[kaling.nama]',
            [
                'required' => 'Kolom Nama wajib diisi.',
                'is_unique' => 'Nama ini sudah digunakan. Mohon gunakan nama lain.'
            ]
        );

        $this->form_validation->set_rules(
            'no_hp',
            'No Handphone',
            'required|is_unique[kaling.no_hp]|min_length[11]',
            [
                'required' => 'Kolom No Handphone wajib diisi.',
                'is_unique' => 'No Handphone ini sudah digunakan. Mohon gunakan No Handphone lain.',
                'min_length'=> 'No Handphone yang ada masukkan minimal 11 Karakter.'
            ]
        );
        
        $this->form_validation->set_rules(
            'username',
            'Username',
            'required|is_unique[users.username]',
            [
                'required'  => 'Kolom Username wajib diisi.',
                'is_unique' => 'Username ini sudah digunakan. Mohon gunakan username lain.'
            ]
        );

        $this->form_validation->set_rules(
            'password',
            'Password',
            'required|min_length[6]',
            [
                'required'      => 'Kolom Password wajib diisi.',
                'min_length'    => 'Password harus memiliki minimal 6 karakter.'
            ]
        );

        $this->form_validation->set_rules('wilayah_id', 'Wilayah', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('alamat_detail', 'Alamat Detail', 'required');
        $this->form_validation->set_rules('alamat_no', 'Nomor Rumah', 'required');
        $this->form_validation->set_rules('latitude', 'Latitude', 'required|decimal');
        $this->form_validation->set_rules('longitude', 'Longitude', 'required|decimal');

        if ($this->form_validation->run() === FALSE) {
             $this->create();
             return;
        }

        $userData = [
            'username' => $this->input->post('username'),
            'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
            'role' => 'Kepala Lingkungan',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->db->insert('users', $userData);
        $user_id = $this->db->insert_id();

        $kalingData = [
            'uuid' => $this->generate_uuid(),
            'user_id' => $user_id,
            'nama' => $this->input->post('nama'),
            'no_hp' => $this->input->post('no_hp'),
            'alamat' => $this->input->post('alamat'),
            'alamat_detail' => $this->input->post('alamat_detail'),
            'alamat_no' => $this->input->post('alamat_no'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'wilayah_id' => $this->input->post('wilayah_id')
        ];

        $this->KalingModel->insert($kalingData);
        $this->session->set_flashdata('success', 'Data Kaling berhasil ditambahkan!');
        redirect('dashboard/kaling/view');
    }

    // Fungsi untuk menampilkan halaman edit Kepala Lingkungan
    public function edit($uuid)
    {
        $data['kaling'] = $this->KalingModel->getByUuid($uuid);
        if (!$data['kaling']) {
            $this->session->set_flashdata('error', 'Data Kaling tidak ditemukan');
            redirect('dashboard/kaling/view');
            return;
        }
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['default_wilayah_id'] = $this->session->userdata('wilayah_id'); 
        $data['title'] = "Edit Akun Kepala Lingkungan | SIPANDU Nuansa Utama";
        $this->load->view('kaling/kaling_edit', $data);
    }

    // Fungsi untuk memperbarui data Kepala Lingkungan
    public function update($uuid)
    {
        $this->form_validation->set_rules(
            'nama',
            'Nama',
            'required',
            [
                'required' => 'Kolom Nama wajib diisi.'
            ]
        );

        $this->form_validation->set_rules(
            'no_hp',
            'No Handphone',
            'required|min_length[11]',
            [
                'required' => 'Kolom No Handphone wajib diisi.',
                'min_length'=> 'No Handphone yang ada masukkan minimal 11 Karakter.'
            ]
        );
        
        $this->form_validation->set_rules(
            'username',
            'Username',
            'required',
            [
                'required'  => 'Kolom Username wajib diisi.'
            ]
        );

        $this->form_validation->set_rules(
            'password',
            'Password',
            'min_length[6]',
            [
                'min_length'    => 'Password harus memiliki minimal 6 karakter.'
            ]
        );

        $this->form_validation->set_rules('wilayah_id', 'Wilayah', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('alamat_detail', 'Alamat Detail', 'required');
        $this->form_validation->set_rules('alamat_no', 'Nomor Rumah', 'required');
        $this->form_validation->set_rules('latitude', 'Latitude', 'required|decimal');
        $this->form_validation->set_rules('longitude', 'Longitude', 'required|decimal');

        if ($this->form_validation->run() === FALSE) {
            $this->edit($uuid);
            return;
        }
    
        $kaling = $this->KalingModel->getByUuid($uuid);
        if (!$kaling) {
            $this->session->set_flashdata('error', 'Data Kaling tidak ditemukan');
            redirect('dashboard/kaling/view');
            return;
        }
    
        $userData = [
            'username' => $this->input->post('username')
        ];
        
        $this->db->where('id', $kaling->user_id)
                 ->update('users', $userData);
    
        $password = $this->input->post('password');
        if (!empty($password)) {
            $this->db->where('id', $kaling->user_id)
                     ->update('users', ['password' => password_hash($password, PASSWORD_BCRYPT)]);
        }
    
        $data = [
            'nama' => $this->input->post('nama'),
            'wilayah_id' => $this->input->post('wilayah_id'),
            'no_hp' => $this->input->post('no_hp'),
            'alamat' => $this->input->post('alamat'),
            'alamat_detail' => $this->input->post('alamat_detail'),
            'alamat_no' => $this->input->post('alamat_no'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude')
        ];
    
        $this->KalingModel->updateByUuid($uuid, $data);
        $this->session->set_flashdata('success', 'Data Kaling berhasil diupdate!');
        redirect('dashboard/kaling/view');
    }
    
    // Fungsi untuk menghapus Kepala Lingkungan
    public function delete($uuid)
    {
        $kaling = $this->KalingModel->getByUuid($uuid);
        if ($kaling) {
            $this->KalingModel->deleteByUuid($uuid);
            $this->db->where('id', $kaling->user_id);
            $this->db->delete('users');

            $this->session->set_flashdata('success', 'Data Kaling berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Data Kaling tidak ditemukan');
        }
        redirect('dashboard/kaling/view');
    }

}
