<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AdminController extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->check_role(['Admin']);
        $this->load->model('AdminModel');
        $this->load->model('UserModel');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->library('helperjs');
    }

    // Fungsi untuk menampilkan halaman daftar admin
    public function view()
    {
        $data['title'] = "Data Admin | SIPANDU Nuansa Utama";
        $data['admins'] = $this->AdminModel->getAll();
        $data['logged_in_user_id'] = $this->session->userdata('user_id');
        $this->load->view('admin/admin_list', $data);
    }

    // Fungsi untuk tambah admin
    public function create()
    {
        $data['title'] = "Buat Akun Admin | SIPANDU Nuansa Utama";
        $this->load->view('admin/admin_create', $data);
    }

    public function store()
    {
       $this->form_validation->set_rules(
            'nama',
            'Nama',
            'required|is_unique[admin.nama]',
            [
                'required' => 'Kolom Nama wajib diisi.',
                'is_unique' => 'Nama ini sudah digunakan. Mohon gunakan nama lain.'
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

        if ($this->form_validation->run() === FALSE) {
             $this->create();
             return;
        }

        $user = [
            'username' => $this->input->post('username'),
            'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
            'role' => 'Admin',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $user_id = $this->AdminModel->insertUser($user);
        $this->AdminModel->insertAdmin([
            'user_id' => $user_id,
            'nama' => $this->input->post('nama')
        ]);

        $this->session->set_flashdata('success', 'Admin berhasil ditambahkan!');
        redirect('dashboard/admin/view');
    }

    // Fungsi untuk edit admin
    public function edit($uuid)
    {
        $admin = $this->AdminModel->getByUuid($uuid);
        if (!$admin) {
            redirect('dashboard/error');
        }
        $data['admin'] = $admin;
        $data['logged_in_user_id'] = $this->session->userdata('user_id');
        $this->load->view('admin/admin_edit', $data);
    }

    public function update($uuid)
    {
        $admin = $this->AdminModel->getByUuid($uuid);
        if (!$admin) {
            redirect('dashboard/error');
        }

        $this->form_validation->set_rules(
            'nama',
            'Nama',
            'required',
            [
                'required' => 'Kolom Nama wajib diisi.'
            ]
        );

        $this->form_validation->set_rules(
            'username',
            'Username',
            'required',
            [
                'required'  => 'Kolom Username wajib diisi.',
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


        if ($this->form_validation->run() === FALSE) {
            $this->edit($uuid);
            return;
        }
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('dashboard/admin/edit/' . $uuid);
        }

        $data_admin = array(
            'nama' => $this->input->post('nama')
        );

        $data_user = array(
            'username' => $this->input->post('username')
        );

        if (!empty($this->input->post('password'))) {
            $data_user['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        }

        $this->db->trans_begin();
        
        $this->AdminModel->update($admin->id, $data_admin);
        
        $this->db->where('id', $admin->user_id);
        $this->db->update('users', $data_user);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal mengupdate data admin');
            redirect('dashboard/admin/edit/' . $uuid);
        }

        $this->db->trans_commit();
        $this->session->set_flashdata('success', 'Berhasil mengupdate data admin');
        redirect('dashboard/admin/view');
    }

    // Fungsi untuk menghapus admin
    public function delete($uuid)
    {
        $admin = $this->AdminModel->getByUuid($uuid);
        if (!$admin) {
            redirect('dashboard/error');
        }

        if ($admin->user_id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Tidak dapat menghapus akun sendiri');
            redirect('dashboard/admin/view');
        }

        $this->AdminModel->delete($admin->id);
        $this->session->set_flashdata('success', 'Berhasil menghapus data admin');
        redirect('dashboard/admin/view');
    }
}
