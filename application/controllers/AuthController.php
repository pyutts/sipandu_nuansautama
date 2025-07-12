<?php
defined('BASEPATH') or exit('No direct script access allowed');
class AuthController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('helperjs');
        $this->load->model('UserModel');
        $this->load->model('PenanggungJawabModel');
        $this->load->model('KalingModel');
        $this->load->model('AdminModel');
        $this->load->model('WilayahModel');
    }

    // Function untuk menampilkan halaman login
    public function login()
    {
        $data['title'] = "Login | SIPANDU NUANSA UTAMA";
        $this->load->view('auth/login_views', $data);
    }

    // Function untuk memproses login
    public function do_login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $user = $this->UserModel->get_by_username($username);
       
        if (!$user) {
            $this->session->set_flashdata('error_username', 'Username tidak ditemukan.');
            redirect('auth');
            return; 
        }

        if (!$this->_is_hashed($user->password)) {
            $hashed_password = password_hash($user->password, PASSWORD_BCRYPT);
            $this->UserModel->update_password($user->id, $hashed_password);
            $user->password = $hashed_password;
        }

        if (password_verify($password, $user->password)) {
            $this->session->set_userdata('user_id', $user->id);
            $this->session->set_userdata('username', $user->username);
            $this->session->set_userdata('role', $user->role);

            switch ($user->role) {
                case 'Admin':
                    $admin = $this->AdminModel->getByUserId($user->id);
                    if ($admin) {
                        $this->session->set_userdata('nama', $admin->nama);
                        $default_kaling = $this->KalingModel->getKaling();
                        $default_wilayah = $this->WilayahModel->getWilayah();

                            if ($default_kaling) {
                                $this->session->set_userdata('kaling_id', $default_kaling->id);
                            }

                            if ($default_wilayah) {
                                $this->session->set_userdata('wilayah_id', $default_wilayah->id);
                            }
                    }
                    break;

                case 'Kepala Lingkungan':
                    $kaling = $this->KalingModel->getByUserId($user->id);
                    if ($kaling) {
                        $this->session->set_userdata('nama', $kaling->nama);
                        $default_kaling = $this->KalingModel->getKaling();
                        $default_wilayah = $this->WilayahModel->getWilayah();

                            if ($default_kaling) {
                                $this->session->set_userdata('kaling_id', $default_kaling->id);
                            }

                            if ($default_wilayah) {
                                $this->session->set_userdata('wilayah_id', $default_wilayah->id);
                            }
                    }
                    break;
                    
                case 'Penanggung Jawab':
                    $pj = $this->PenanggungJawabModel->getByUserId($user->id);
                    if ($pj) {
                        $this->session->set_userdata('user_id', $user->id);
                        $this->session->set_userdata('pj_id', $pj->id); 
                        $this->session->set_userdata('nama', $pj->nama_pj);
                        $default_kaling = $this->KalingModel->getKaling();
                        $default_wilayah = $this->WilayahModel->getWilayah();

                            if ($default_kaling) {
                                $this->session->set_userdata('kaling_id', $default_kaling->id);
                            }

                            if ($default_wilayah) {
                                $this->session->set_userdata('wilayah_id', $default_wilayah->id);
                            }
                    }
                    break;
            }

            $this->session->set_flashdata('success_login', 'Selamat datang di Dashboard SIPANDU, ' . $this->session->userdata('nama') . '!');
            if ($user->role === 'Admin' || $user->role === 'Kepala Lingkungan') {
                redirect('dashboard');
            } elseif ($user->role === 'Penanggung Jawab') {
                redirect('dashboard/penghuni/viewpj');
            } else {
                $this->session->set_flashdata('error', 'Akses Ditolak');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('error_password', 'Password salah.');
            redirect('auth');
        }
    }

    // Function untuk mengecek apakah password sudah di-hash
    private function _is_hashed($password)
    {
        return strlen($password) == 60 && preg_match('/^\$2[ayb]\$.{56}$/', $password);
    }

    // Function untuk logout
    public function logout()
    {
        $role = $this->session->userdata('role');

        switch ($role) {
            case 'Admin':
                $this->session->unset_userdata(['admin_logged_in', 'admin_id']);
                break;

            case 'Kepala Lingkungan':
                $this->session->unset_userdata(['kaling_logged_in', 'kaling_id']);
                break;

            case 'Penanggung Jawab':
                $this->session->unset_userdata(['pj_logged_in', 'pj_id']);
                break;
        }

        $this->session->unset_userdata('role');
        $this->session->set_flashdata('success', 'Anda berhasil logout.');
        redirect('auth');
    }
}
