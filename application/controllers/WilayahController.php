<?php
defined('BASEPATH') or exit('No direct script access allowed');

class WilayahController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $this->load->model('WilayahModel');
        $this->load->library('form_validation');
        $this->load->library('helperjs');
    }

    // Fungsi untuk menampilkan halaman daftar wilayah
    public function view()
    {
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['title'] = "Data Wilayah | SIPANDU Nuansa Utama";
        $this->load->view('wilayah/wilayah_list', $data);
    }

    // Fungsi untuk mendaftarkan wilayah
    public function create()
    {
        $data['title'] = "Tambah Wilayah | SIPANDU Nuansa Utama";
        $this->load->view('wilayah/wilayah_create', $data);
    }

    public function store()
    {
        $this->form_validation->set_rules(
            
            'wilayah', 
            'Nama Wilayah', 
            'required|is_unique[wilayah.wilayah]',

            [
                'required'  => 'Kolom Wilayah wajib diisi.',
                'is_unique' => 'Wilayah ini sudah digunakan. Mohon gunakan wilayah lain.'
            ]
        
        );
   
        if ($this->form_validation->run() === FALSE) {
             $this->create();
             return;
        }

        $this->WilayahModel->insert([
            'wilayah' => $this->input->post('wilayah'),
           
        ]);

        $this->session->set_flashdata('success', 'Wilayah berhasil ditambahkan!');
        redirect('dashboard/wilayah/view');
    }

    // Fungsi untuk menampilkan edit wilayah
    public function edit($uuid)
    {
        $data['wilayah'] = $this->WilayahModel->get_by_id($uuid);
        if (!$data['wilayah']) {
            $this->session->set_flashdata('error', 'Wilayah tidak ditemukan');
            redirect('dashboard/wilayah/view');
        }
        $data['title'] = "Edit Wilayah | SIPANDU Nuansa Utama";
        $this->load->view('wilayah/wilayah_edit', $data);
    }

    public function update($uuid)
    {
       $this->form_validation->set_rules(
            
            'wilayah', 
            'Nama Wilayah', 
            'required',

            [
                'required'  => 'Kolom Wilayah wajib diisi.',
            ]
        
        );
   
        if ($this->form_validation->run() === FALSE) {
            $this->edit($uuid);
            return;
        }

        if (!$this->WilayahModel->update($uuid, [
            'wilayah' => $this->input->post('wilayah'),
           
        ])) {
            $this->session->set_flashdata('error', 'Gagal memperbarui wilayah');
            redirect('dashboard/wilayah/edit/' . $uuid);
        }

        $this->session->set_flashdata('success', 'Wilayah berhasil diperbarui!');
        redirect('dashboard/wilayah/view');
    }
    
    // Fungsi untuk menghapus wilayah
    public function delete($uuid)
    {
        if (!$this->WilayahModel->delete($uuid)) {
            $this->session->set_flashdata('error', 'Wilayah tidak dapat dihapus karena masih memiliki data terkait (Kepala Lingkungan, Penanggung Jawab, atau Penghuni)');
        } else {
            $this->session->set_flashdata('success', 'Wilayah berhasil dihapus!');
        }
        redirect('dashboard/wilayah/view');
    }
}