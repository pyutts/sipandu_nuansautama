<?php
defined('BASEPATH') or exit('No direct script access allowed');
class DaftarPJController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('helperjs');
        $this->load->model('TokenModel');
    }

    // Function untuk menampilkan halaman generate link
    public function generate_link_view()
    {
        $data['title'] = "Short Link Daftar Penanggung Jawab | SIPANDU NUANSA UTAMA";
        $this->load->view('partials/header', $data);
        $this->load->view('partials/footer');
        $this->load->view('auth/generate_link');
    }

    // Function untuk generate token dan link
    public function link_pj()
    {
        $token = $this->TokenModel->generate_token();
        $link = base_url("daftar/pj/$token");
        echo json_encode(['link' => $link]);
    }

    // Function untuk menampilkan halaman pendaftaran Penanggung Jawab
    public function pj($token, $data_errors = [])
    {
        $valid_token = $this->TokenModel->validate_token($token);

        if (!$valid_token) {
            $data['title'] = "Token Kadaluwarsa | SIPANDU NUANSA UTAMA";
            $this->load->view('auth/token_views', $data);
            return;
        }

        $data['token'] = $token;
        $data = array_merge($data, $data_errors);
        $data['title'] = "Daftar Penanggung Jawab | SIPANDU NUANSA UTAMA";

        $this->load->view('auth/register_views_pj', $data);
    }

    // Function untuk memproses pendaftaran Penanggung Jawab
    public function submit_pj()
    {
        $this->load->library('form_validation');
        $token = $this->input->post('token');
        
        $this->form_validation->set_rules(
            'username', 
            'Username', 
            'required|is_unique[users.username]',
            [
                'required'  => 'Username wajib diisi.',
                'is_unique' => 'Username ini sudah digunakan. Mohon gunakan username lain.'
            ]
        );

        $this->form_validation->set_rules(
            'email', 
            'Email', 
            'required|valid_email|is_unique[penanggung_jawab.email]', 
            [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email sudah digunakan, silakan gunakan email yang lain.'
            ]
        );

        $this->form_validation->set_rules(
            'password', 
            'Password', 
            'required|min_length[6]',
            [
                'required'        => 'Password wajib diisi.',
                'min_length[6]'   => 'Password anda melebihi 6 karakter'
            ]
        );

        if ($this->form_validation->run() === FALSE) {
            $data_errors = [
                'error_username' => form_error('username'),
                'error_nama'     => form_error('nama'),
                'error_email'    => form_error('email'),
                'error_password' => form_error('password'),
            ];
            $this->pj($token, $data_errors);
            return;
        }

        $token = $this->input->post('token');
        $valid_token = $this->TokenModel->validate_token($token);
        if (!$valid_token) {
            $data_errors = ['error_token' => 'Token tidak valid atau sudah kadaluarsa.'];
            $this->pj($token, $data_errors);
            return;
        }

        $hashed_password = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
        $user_data = [
            'username' => $this->input->post('username'),
            'password' => $hashed_password,
            'role' => 'Penanggung Jawab',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('users', $user_data);
        $user_id = $this->db->insert_id();
        
        $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

        $pj_data = [
            'user_id' => $user_id,
            'uuid' => $uuid,
            'email' => $this->input->post('email'),
        ];
        
        $this->db->insert('penanggung_jawab', $pj_data);
        $pj_id = $this->db->insert_id();

        $this->TokenModel->mark_token_as_used($token, $pj_id);

        $this->session->set_flashdata('success', 'Pendaftaran berhasil! Silakan login.');
        redirect('auth');

    }
}
