<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PJController extends MY_Controller
{
  
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PenanggungJawabModel');
        $this->load->model('WilayahModel');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('upload');
        $this->load->library('image_lib'); 
        $this->load->library('helperjs');
        $this->load->helper(['url', 'form']);
        $this->load->database(); 
    }

    // Fungsi untuk generate UUID
    private function generate_uuid() {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    // Fungsi untuk mengupload file dan kompress file
    private function _upload_file($field)
    {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === 4) {
            return null; 
        }

        $config['upload_path']   = FCPATH . 'uploads/pj/';
        $config['allowed_types'] = 'jpg|jpeg|png|webp|pdf';
        $config['max_size'] = '8192';  

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $ext = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
        $new_name = 'foto_kartukeluarga_' . md5(uniqid(rand(), true)) . '.' . strtolower($ext);
        $config['file_name'] = $new_name;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload($field)) {
            $error_msg = $this->upload->display_errors();
            $this->session->set_flashdata('error_upload', $error_msg);
            return null; 
        }

        $upload_data = $this->upload->data();

        $image_types = ['jpg', 'jpeg', 'png','webp'];
        $file_ext = strtolower($upload_data['file_ext']);
        
        if (in_array(ltrim($file_ext, '.'), $image_types) && $upload_data['file_size'] > 1024) {
            
            $config_compress['image_library']    = 'gd2';
            $config_compress['source_image']     = $upload_data['full_path'];
            $config_compress['maintain_ratio']   = TRUE;
            $config_compress['width']            = 1920; 
            $config_compress['height']           = 1920; 
            $config_compress['quality']          = '50%';
            
            $this->load->library('image_lib', $config_compress);

            if (!$this->image_lib->resize()) {
                $this->session->set_flashdata('error_compress', $this->image_lib->display_errors());
            }
            
            $this->image_lib->clear();
        }
        return $upload_data['file_name'];
    }

    // Fungsi untuk memeriksa kelekapan data di Penanggung Jawab
    public function verifikasi_pj()
    {
        $data['title'] = "Lengkapi Data Penanggung Jawab | SIPANDU Nuansa Utama";
        $this->load->view('dashboard/index_pj_views', $data);
    }

    public function index()
    {
        $pj_id = $this->session->userdata('pj_id');
        $role = $this->session->userdata('role');
        if (!$pj_id || $role !== 'Penanggung Jawab') {
            redirect('dashboard/pj/validation');
            return;
        }

        $is_complete = $this->PenanggungJawabModel->verifikasi_data($pj_id);
        if (!$is_complete) {
            redirect('dashboard/penghuni/viewpj');
            return;
        }

        $pj = $this->PenanggungJawabModel->getById($pj_id);
        if ($pj) {
            redirect('dashboard/pj/edit/' . $pj->uuid);
            return;
        } else {
            $this->session->set_flashdata('error', 'Data tidak ditemukan');
            redirect('dashboard/pj/validation');
            return;
        }
    }

    // Fungsi untuk tampilan data Penanggung Jawab di Admin dan Kaling
    public function view()
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $data['title'] = "Data Penanggung Jawab | SIPANDU Nuansa Utama";
        $data['pj'] = $this->PenanggungJawabModel->getAllPJ();
        $this->load->view('pj/pj_list_admin', $data);
    }

    // Fungsi untuk tambah data Penanggung Jawab di Admin dan Kaling
    public function create()
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $data['title'] = "Buat Akun Penanggung Jawab | SIPANDU Nuansa Utama";
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['default_wilayah_id'] = $this->session->userdata('wilayah_id'); 
        $this->load->view('pj/pj_create_admin', $data);
    }

    public function store()
    {
        $this->form_validation->set_rules(
            'username',
            'Username',
            'required|is_unique[users.username]',
            [
                'required'  => 'Kolom Username wajib diisi.',
                'is_unique' => 'Username ini sudah terdaftar, silakan gunakan yang lain.'
            ]
        );

        $this->form_validation->set_rules(
            'password',
            'Password',
            'required|min_length[6]',
            [
                'required' => 'Kolom Password wajib diisi.',
                'min_length'    => 'Password harus memiliki minimal 6 karakter.'
            ]
        );
        
        $this->form_validation->set_rules(
            'no_kk',
            'Nomor KK',
            'required|is_unique[penanggung_jawab.no_kk]',
            [
                'required'  => 'Kolom Nomor KK wajib diisi.',
                'is_unique' => 'Nomor KK ini sudah terdaftar.'
            ]
        );

        $this->form_validation->set_rules(
            'nik',
            'NIK',
            'required|is_unique[penanggung_jawab.nik]',
            [
                'required'  => 'Kolom NIK wajib diisi.',
                'is_unique' => 'NIK ini sudah terdaftar.'
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
            'jenis_kelamin',
            'Jenis Kelamin',
            'required|in_list[LAKI - LAKI,PEREMPUAN]',
            [
                'required' => 'Kolom Jenis Kelamin wajib dipilih.',
                'in_list'  => 'Pilihan Jenis Kelamin tidak valid.'
            ]
        );

        $this->form_validation->set_rules(
            'nama_pj',
            'Nama Penanggung Jawab',
            'required',
            [
                'required' => 'Kolom Nama Penanggung Jawab wajib diisi.'
            ]
        );

        $this->form_validation->set_rules(
            'email',
            'Email',
            'required|valid_email|is_unique[penanggung_jawab.email]',
            [
                'required'    => 'Kolom Email wajib diisi.',
                'valid_email' => 'Format Email tidak valid.',
                'is_unique'   => 'Email ini sudah terdaftar, Silahkan gunakan email yang lain.'
            ]
        );

        $this->form_validation->set_rules(
            'no_hp',
            'Nomor HP',
            'required',
            [
                'required' => 'Kolom Nomor HP wajib diisi.'
            ]
        );

        $this->form_validation->set_rules('alamat_maps', 'Alamat Maps', 'required');
        $this->form_validation->set_rules('alamat_detail', 'Alamat Detail', 'required');
        $this->form_validation->set_rules('alamat_no', 'Nomor Rumah', 'required');
        $this->form_validation->set_rules('latitude', 'Latitude', 'required');
        $this->form_validation->set_rules('longitude', 'Longitude', 'required');
        $this->form_validation->set_rules('status_rumah', 'Status Rumah', 'required');
        $this->form_validation->set_rules('wilayah_id', 'Wilayah', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->create(); 
            return;
        }

        $anggota_keluarga_json = $this->input->post('anggota_keluarga');
        $anggota_keluarga_arr = json_decode($anggota_keluarga_json, true);
        $anggota_errors = []; 

        if (!empty($anggota_keluarga_arr) && is_array($anggota_keluarga_arr)) {
            $submitted_niks = [];
            foreach ($anggota_keluarga_arr as $index => $anggota) {
                $row_number = $index + 1;
                if (empty($anggota['nik'])) {
                    $anggota_errors[] = "Baris {$row_number}: NIK anggota wajib diisi.";
                } else {
                    if (!is_numeric($anggota['nik']) || strlen($anggota['nik']) != 16) {
                        $anggota_errors[] = "Baris {$row_number}: NIK anggota harus 16 digit angka.";
                    }
                    if (in_array($anggota['nik'], $submitted_niks)) {
                        $anggota_errors[] = "Baris {$row_number}: Terdapat duplikat NIK ({$anggota['nik']}) di dalam form.";
                    }
                    $submitted_niks[] = $anggota['nik'];
                }

                if (empty($anggota['nama'])) $anggota_errors[] = "Baris {$row_number}: Nama anggota wajib diisi.";
                if (empty($anggota['tempat_lahir'])) $anggota_errors[] = "Baris {$row_number}: Tempat Lahir anggota wajib diisi.";
                if (empty($anggota['tanggal_lahir'])) $anggota_errors[] = "Baris {$row_number}: Tanggal Lahir anggota wajib diisi.";
                if (empty($anggota['jenis_kelamin'])) $anggota_errors[] = "Baris {$row_number}: Jenis Kelamin anggota wajib dipilih.";
                if (empty($anggota['hubungan'])) $anggota_errors[] = "Baris {$row_number}: Hubungan keluarga wajib diisi.";
            }
            
            if (!empty($submitted_niks)) {
                $existing_niks = $this->db->select('nik_anggota')->where_in('nik_anggota', $submitted_niks)->get('anggota_keluarga')->result();
                if (!empty($existing_niks)) {
                    foreach ($existing_niks as $row) {
                        $anggota_errors[] = "NIK Anggota {$row->nik_anggota} sudah terdaftar di sistem.";
                    }
                }
            }
        }

        if (!empty($anggota_errors)) {
            $this->session->set_flashdata('anggota_errors', $anggota_errors);
            redirect('dashboard/pj/create');
            return;
        }

        $this->db->trans_begin();
        
        $foto_kk = $this->_upload_file('foto_kk'); 
        if (!$foto_kk) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error_upload', $this->upload->display_errors());
            $this->create();
            return;
        }

        $user_data = [
            'username' => $this->input->post('username'),
            'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
            'role' => 'Penanggung Jawab',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->insert('users', $user_data);
        $user_id = $this->db->insert_id();

        $pj_data = [
            'uuid' => $this->generate_uuid(),
            'user_id' => $user_id,
            'no_kk' => $this->input->post('no_kk'),
            'nik' => $this->input->post('nik'),
            'nama_pj' => $this->input->post('nama_pj'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'email' => $this->input->post('email'),
            'no_hp' => $this->input->post('no_hp'),
            'foto_kk' => $foto_kk,
            'alamat_maps' => $this->input->post('alamat_maps'),
            'alamat_detail' => $this->input->post('alamat_detail'),
            'alamat_no' => $this->input->post('alamat_no'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'status_rumah' => $this->input->post('status_rumah'),
            'wilayah_id' => $this->input->post('wilayah_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->insert('penanggung_jawab', $pj_data);
        $pj_id = $this->db->insert_id();
        
          if (!empty($anggota_keluarga_arr)) {
            foreach ($anggota_keluarga_arr as $anggota) {
                $this->db->insert('anggota_keluarga', [
                    'uuid' => $this->generate_uuid(),
                    'penanggung_jawab_id' => $pj_id,
                    'nik_anggota' => $anggota['nik'],
                    'nama' => $anggota['nama'],
                    'tempat_lahir' => $anggota['tempat_lahir'],
                    'tanggal_lahir' => $anggota['tanggal_lahir'],
                    'jenis_kelamin' => $anggota['jenis_kelamin'],
                    'hubungan' => $anggota['hubungan'],
                    'pekerjaan' => $anggota['pekerjaan'] ?? null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        $this->session->set_flashdata('error', 'Gagal menyimpan data, terjadi kesalahan pada database.');
        $this->create();

        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Data Penanggung Jawab berhasil ditambahkan.');
            redirect('dashboard/pj/view');
        }
    }

    // Fungsi untuk mengedit data Penanggung Jawab di Admin dan Kaling
    public function edit($uuid)
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $pj = $this->PenanggungJawabModel->getByUUID($uuid);
        if (!$pj) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('dashboard/pj/view');
            return;
        }
        
        $data['pj'] = $pj;
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['anggota_keluarga'] = $this->PenanggungJawabModel->getAnggotaKeluarga($pj->id);
        $data['default_wilayah_id'] = $this->session->userdata('wilayah_id'); 
        $raw_anggota_data = $this->PenanggungJawabModel->getAnggotaKeluarga($pj->id);
        $formatted_anggota = [];

        if (!empty($raw_anggota_data)) {
            foreach ($raw_anggota_data as $anggota) {
                $formatted_anggota[] = [
                    'nik'           => $anggota->nik_anggota, 
                    'nama'          => $anggota->nama,
                    'tempat_lahir'  => $anggota->tempat_lahir,
                    'tanggal_lahir' => $anggota->tanggal_lahir,
                    'jenis_kelamin' => $anggota->jenis_kelamin,
                    'hubungan'      => $anggota->hubungan,
                    'pekerjaan'     => $anggota->pekerjaan,
                ];
            }
        }

        $data['anggota_keluarga'] = $formatted_anggota;
        $data['title'] = "Edit Akun Penanggung Jawab | SIPANDU Nuansa Utama";
        $this->load->view('pj/pj_edit_admin', $data);
    }

    public function update($uuid)
    {
        $old_data = $this->PenanggungJawabModel->getByUUID($uuid);
        if (!$old_data) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('dashboard/pj/view');
            return;
        }

        $this->form_validation->set_message('required', 'Kolom {field} wajib diisi.');
        if($this->input->post('username') != $old_data->username) {
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]', ['is_unique' => 'Username ini sudah terdaftar.']);
        } else {
        $this->form_validation->set_rules('username', 'Username', 'required');
        }

        if($this->input->post('no_kk') != $old_data->no_kk) {
        $this->form_validation->set_rules('no_kk', 'Nomor KK', 'required|is_unique[penanggung_jawab.no_kk]', ['is_unique' => 'Nomor KK ini sudah terdaftar.']);
        } else {
        $this->form_validation->set_rules('no_kk', 'Nomor KK', 'required');
        }

        if($this->input->post('nik') != $old_data->nik) {
        $this->form_validation->set_rules('nik', 'NIK', 'required|is_unique[penanggung_jawab.nik]', ['is_unique' => 'NIK ini sudah terdaftar.']);
        } else {
        $this->form_validation->set_rules('nik', 'NIK', 'required');
        }

        if($this->input->post('email') != $old_data->email) {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[penanggung_jawab.email]', ['is_unique' => 'Email ini sudah terdaftar.']);
        } else {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        }

        if (!empty($this->input->post('password'))) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[6]', ['min_length' => 'Password harus minimal 6 karakter.']);
        }

        $this->form_validation->set_rules('nama_pj', 'Nama Penanggung Jawab', 'required');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
        $this->form_validation->set_rules('no_hp', 'Nomor HP', 'required');
        $this->form_validation->set_rules('wilayah_id', 'Wilayah', 'required');


        if ($this->form_validation->run() === FALSE) {
            $this->edit($uuid); 
            return;
        }

        $anggota_keluarga_json = $this->input->post('anggota_keluarga');
        $anggota_keluarga_arr = json_decode($anggota_keluarga_json, true);
        $anggota_errors = [];

        if (!empty($anggota_keluarga_arr) && is_array($anggota_keluarga_arr)) {
            $submitted_niks = [];
            foreach ($anggota_keluarga_arr as $index => $anggota) {
                $row_number = $index + 1;
                if (empty($anggota['nik'])) {
                    $anggota_errors[] = "Baris {$row_number}: NIK anggota wajib diisi.";
                } else {
                    if (!is_numeric($anggota['nik']) || strlen($anggota['nik']) != 16) {
                        $anggota_errors[] = "Baris {$row_number}: NIK anggota harus 16 digit angka.";
                    }
                    if (in_array($anggota['nik'], $submitted_niks)) {
                        $anggota_errors[] = "Baris {$row_number}: Terdapat duplikat NIK ({$anggota['nik']}) di dalam form.";
                    }
                    $submitted_niks[] = $anggota['nik'];
                }

                if (empty($anggota['nama'])) $anggota_errors[] = "Baris {$row_number}: Nama anggota wajib diisi.";
                if (empty($anggota['tempat_lahir'])) $anggota_errors[] = "Baris {$row_number}: Tempat Lahir anggota wajib diisi.";
                if (empty($anggota['tanggal_lahir'])) $anggota_errors[] = "Baris {$row_number}: Tanggal Lahir anggota wajib diisi.";
                if (empty($anggota['jenis_kelamin'])) $anggota_errors[] = "Baris {$row_number}: Jenis Kelamin anggota wajib dipilih.";
                if (empty($anggota['hubungan'])) $anggota_errors[] = "Baris {$row_number}: Hubungan keluarga wajib diisi.";
            }
            
            if (!empty($submitted_niks)) {
                $this->db->select('nik_anggota');
                $this->db->where_in('nik_anggota', $submitted_niks);
                $this->db->where('penanggung_jawab_id !=', $old_data->id); 
                $existing_niks = $this->db->get('anggota_keluarga')->result();
                
                if (!empty($existing_niks)) {
                    foreach ($existing_niks as $row) {
                        $anggota_errors[] = "NIK Anggota {$row->nik_anggota} sudah terdaftar pada warga lain.";
                    }
                }
            }
        }

        if (!empty($anggota_errors)) {
            $this->session->set_flashdata('anggota_errors', $anggota_errors);
            redirect('dashboard/pj/edit/' . $uuid);
            return;
        }

        $this->db->trans_begin();


        $user_update_data = ['username' => $this->input->post('username')];
        if (!empty($this->input->post('password'))) {
            $user_update_data['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
        }
        $this->db->where('id', $old_data->user_id)->update('users', $user_update_data);


        $foto_kk = $old_data->foto_kk;
        if (isset($_FILES['foto_kk']) && $_FILES['foto_kk']['error'] == 0) {
            $uploaded_file = $this->_upload_file('foto_kk');
            if ($uploaded_file) {
                if ($foto_kk && file_exists('./uploads/pj/' . $foto_kk)) {
                    unlink('./uploads/pj/' . $foto_kk);
                }
                $foto_kk = $uploaded_file;
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error_upload', $this->upload->display_errors());
                $this->edit($uuid);
                return;
            }
        }

        $pj_update_data = [
            'no_kk' => $this->input->post('no_kk'),
            'nik' => $this->input->post('nik'),
            'nama_pj' => $this->input->post('nama_pj'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'email' => $this->input->post('email'),
            'no_hp' => $this->input->post('no_hp'),
            'foto_kk' => $foto_kk,
            'alamat_maps' => $this->input->post('alamat_maps'),
            'alamat_detail' => $this->input->post('alamat_detail'),
            'alamat_no' => $this->input->post('alamat_no'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'status_rumah' => $this->input->post('status_rumah'),
            'wilayah_id' => $this->input->post('wilayah_id'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->PenanggungJawabModel->update($old_data->id, $pj_update_data);

        if ($anggota_keluarga_json !== null) {
            $this->db->where('penanggung_jawab_id', $old_data->id)->delete('anggota_keluarga');
            if (!empty($anggota_keluarga_arr)) {
                foreach ($anggota_keluarga_arr as $anggota) {
                    $this->db->insert('anggota_keluarga', [
                        'uuid' => $this->generate_uuid(),
                        'penanggung_jawab_id' => $old_data->id,
                        'nik_anggota' => $anggota['nik'],
                        'nama' => $anggota['nama'],
                        'tempat_lahir' => $anggota['tempat_lahir'],
                        'tanggal_lahir' => $anggota['tanggal_lahir'],
                        'jenis_kelamin' => $anggota['jenis_kelamin'],
                        'hubungan' => $anggota['hubungan'],
                        'pekerjaan' => $anggota['pekerjaan'] ?? null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal mengupdate data.');
            $this->edit($uuid);
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Data Penanggung Jawab berhasil diupdate.');
            redirect('dashboard/pj/view');
        }
    }

    // Fungsi untuk mengedit data Penanggung Jawab di session Penanggung Jawab
    public function edit_pj()
    {
        $pj_id = $this->session->userdata('pj_id');
        $role = $this->session->userdata('role');
        if (!$pj_id || $role !== 'Penanggung Jawab') {
            return;
        }

        $pj = $this->PenanggungJawabModel->getById($pj_id);
        if (!$pj) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan');
            redirect('dashboard/pj/editdata');
            return;
        }
        $data['pj'] = $pj;
        $data['wilayah'] = $this->WilayahModel->get_all();
        $data['anggota_keluarga'] = $this->PenanggungJawabModel->getAnggotaKeluarga($pj->id);
        $data['default_wilayah'] = $this->session->userdata('wilayah_id'); 
        $raw_anggota_data = $this->PenanggungJawabModel->getAnggotaKeluarga($pj->id);
        $formatted_anggota = [];

        if (!empty($raw_anggota_data)) {
            foreach ($raw_anggota_data as $anggota) {
                $formatted_anggota[] = [
                    'nik'           => $anggota->nik_anggota, 
                    'nama'          => $anggota->nama,
                    'tempat_lahir'  => $anggota->tempat_lahir,
                    'tanggal_lahir' => $anggota->tanggal_lahir,
                    'jenis_kelamin' => $anggota->jenis_kelamin,
                    'hubungan'      => $anggota->hubungan,
                    'pekerjaan'     => $anggota->pekerjaan,
                ];
            }
        }

        $data['anggota_keluarga'] = $formatted_anggota;
        $data['title'] = "Profil Data | SIPANDU Nuansa Utama";
        $this->load->view('pj/pj_profil_data', $data);
        return;
    }

    public function update_pj($uuid)
    {
        $pj_id = $this->session->userdata('pj_id');
        $role = $this->session->userdata('role');
        if (!$pj_id || $role !== 'Penanggung Jawab') {
            return;
        }

        $old_data = $this->PenanggungJawabModel->getByUUID($uuid);
        if (!$old_data) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('dashboard/pj/view');
            return;
        }

         $this->form_validation->set_message('required', 'Kolom {field} wajib diisi.');

        $this->form_validation->set_rules('username', 'Username', 'required|callback_username_check');
        $this->form_validation->set_rules('no_kk', 'Nomor KK', 'required|callback_no_kk_check');
        $this->form_validation->set_rules('nik', 'NIK', 'required|callback_nik_check');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_check');

        if (!empty($this->input->post('password'))) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[6]', ['min_length' => 'Password harus minimal 6 karakter.']);
        }

        $this->form_validation->set_rules('nama_pj', 'Nama Penanggung Jawab', 'required');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
        $this->form_validation->set_rules('no_hp', 'Nomor HP', 'required');
        $this->form_validation->set_rules('wilayah_id', 'Wilayah', 'required');
        $this->form_validation->set_rules('status_rumah', 'Status Rumah', 'required');
        $this->form_validation->set_rules('alamat_detail', 'Alamat Detail', 'required');
        $this->form_validation->set_rules('alamat_no', 'No Rumah', 'required');
        $this->form_validation->set_rules('latitude', 'Latitude', 'required');
        $this->form_validation->set_rules('longitude', 'Longitude', 'required');
        $this->form_validation->set_rules('alamat_maps', 'Alamat Maps', 'required');

       
    if ($this->form_validation->run() === FALSE) {
        $this->session->set_flashdata('error', 'Terdapat kesalahan pada input Anda, mohon periksa kembali.'); 
        $this->edit_pj(); 
        return;
    }

        $anggota_keluarga_json = $this->input->post('anggota_keluarga');
        $anggota_keluarga_arr = json_decode($anggota_keluarga_json, true);
        $anggota_errors = [];

        if (!empty($anggota_keluarga_arr) && is_array($anggota_keluarga_arr)) {
            $submitted_niks = [];
            foreach ($anggota_keluarga_arr as $index => $anggota) {
                $row_number = $index + 1;
                if (empty($anggota['nik'])) {
                    $anggota_errors[] = "Baris {$row_number}: NIK anggota wajib diisi.";
                } else {
                    if (!is_numeric($anggota['nik']) || strlen($anggota['nik']) != 16) {
                        $anggota_errors[] = "Baris {$row_number}: NIK anggota harus 16 digit angka.";
                    }
                    if (in_array($anggota['nik'], $submitted_niks)) {
                        $anggota_errors[] = "Baris {$row_number}: Terdapat duplikat NIK ({$anggota['nik']}) di dalam form.";
                    }
                    $submitted_niks[] = $anggota['nik'];
                }

                if (empty($anggota['nama'])) $anggota_errors[] = "Baris {$row_number}: Nama anggota wajib diisi.";
                if (empty($anggota['tempat_lahir'])) $anggota_errors[] = "Baris {$row_number}: Tempat Lahir anggota wajib diisi.";
                if (empty($anggota['tanggal_lahir'])) $anggota_errors[] = "Baris {$row_number}: Tanggal Lahir anggota wajib diisi.";
                if (empty($anggota['jenis_kelamin'])) $anggota_errors[] = "Baris {$row_number}: Jenis Kelamin anggota wajib dipilih.";
                if (empty($anggota['hubungan'])) $anggota_errors[] = "Baris {$row_number}: Hubungan keluarga wajib diisi.";
            }
            
            if (!empty($submitted_niks)) {
                $this->db->select('nik_anggota');
                $this->db->where_in('nik_anggota', $submitted_niks);
                $this->db->where('penanggung_jawab_id !=', $old_data->id); 
                $existing_niks = $this->db->get('anggota_keluarga')->result();
                
                if (!empty($existing_niks)) {
                    foreach ($existing_niks as $row) {
                        $anggota_errors[] = "NIK Anggota {$row->nik_anggota} sudah terdaftar pada warga lain.";
                    }
                }
            }
        }

        if (!empty($anggota_errors)) {
            $this->session->set_flashdata('anggota_errors', $anggota_errors);
            redirect('dashboard/pj/editdata/' . $uuid);
            return;
        }

        $this->db->trans_begin();


        $user_update_data = ['username' => $this->input->post('username')];
        if (!empty($this->input->post('password'))) {
            $user_update_data['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
        }
        $this->db->where('id', $old_data->user_id)->update('users', $user_update_data);


        $foto_kk = $old_data->foto_kk;
        if (isset($_FILES['foto_kk']) && $_FILES['foto_kk']['error'] == 0) {
            $uploaded_file = $this->_upload_file('foto_kk');
            if ($uploaded_file) {
                if ($foto_kk && file_exists('./uploads/pj/' . $foto_kk)) {
                    unlink('./uploads/pj/' . $foto_kk);
                }
                $foto_kk = $uploaded_file;
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error_upload', $this->upload->display_errors());
                $this->edit($uuid);
                return;
            }
        }

        $pj_update_data = [
            'no_kk' => $this->input->post('no_kk'),
            'nik' => $this->input->post('nik'),
            'nama_pj' => $this->input->post('nama_pj'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'email' => $this->input->post('email'),
            'no_hp' => $this->input->post('no_hp'),
            'foto_kk' => $foto_kk,
            'alamat_maps' => $this->input->post('alamat_maps'),
            'alamat_detail' => $this->input->post('alamat_detail'),
            'alamat_no' => $this->input->post('alamat_no'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'status_rumah' => $this->input->post('status_rumah'),
            'wilayah_id' => $this->input->post('wilayah_id'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->PenanggungJawabModel->update($old_data->id, $pj_update_data);

        if ($anggota_keluarga_json !== null) {
            $this->db->where('penanggung_jawab_id', $old_data->id)->delete('anggota_keluarga');
            if (!empty($anggota_keluarga_arr)) {
                foreach ($anggota_keluarga_arr as $anggota) {
                    $this->db->insert('anggota_keluarga', [
                        'uuid' => $this->generate_uuid(),
                        'penanggung_jawab_id' => $old_data->id,
                        'nik_anggota' => $anggota['nik'],
                        'nama' => $anggota['nama'],
                        'tempat_lahir' => $anggota['tempat_lahir'],
                        'tanggal_lahir' => $anggota['tanggal_lahir'],
                        'jenis_kelamin' => $anggota['jenis_kelamin'],
                        'hubungan' => $anggota['hubungan'],
                        'pekerjaan' => $anggota['pekerjaan'] ?? null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal mengupdate data.');
            $this->edit_pj(); 

        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Data Penanggung Jawab berhasil diupdate.');
            redirect('dashboard/penghuni/viewpj');
        }
    }

    // Fungsi Validasi Check dengan Callback untuk Profile data dan Update di view penanggung jawabnya 
    public function username_check($username)
    {
        $uuid = $this->uri->segment(4);
        $old_data = $this->PenanggungJawabModel->getByUUID($uuid);

        if ($username == $old_data->username) {
            return TRUE;
        }

        $this->db->where('username', $username);
        $query = $this->db->get('users');

        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('username_check', 'Username ini sudah terdaftar.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function no_kk_check($no_kk)
    {
        $uuid = $this->uri->segment(4);
        $old_data = $this->PenanggungJawabModel->getByUUID($uuid);

        if ($no_kk == $old_data->no_kk) {
            return TRUE;
        }

        $this->db->where('no_kk', $no_kk);
        $query = $this->db->get('penanggung_jawab');

        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('no_kk_check', 'Nomor KK ini sudah terdaftar.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function nik_check($nik)
    {
        $uuid = $this->uri->segment(4);
        $old_data = $this->PenanggungJawabModel->getByUUID($uuid);

        if ($nik == $old_data->nik) {
            return TRUE;
        }

        $this->db->where('nik', $nik);
        $query = $this->db->get('penanggung_jawab');

        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('nik_check', 'NIK ini sudah terdaftar.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function email_check($email)
    {
        $uuid = $this->uri->segment(4);
        $old_data = $this->PenanggungJawabModel->getByUUID($uuid);

        if ($email == $old_data->email) {
            return TRUE;
        }
        
        $this->db->where('email', $email);
        $query = $this->db->get('penanggung_jawab');

        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('email_check', 'Email ini sudah terdaftar.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    // Fungsi untuk menghapus data Penanggung Jawab di Admin dan Kaling
    public function delete($uuid)
    {
        $pj = $this->PenanggungJawabModel->getByUUID($uuid);
        if ($pj) {
            $this->PenanggungJawabModel->delete($pj->id);
            $this->session->set_flashdata('success', 'Data Penanggung Jawab berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
        }
        redirect('dashboard/pj/view');
    }

   // Fungsi untuk ambil lokasi yang sesuai dengan penanggung jawab
    public function getPJLocation()
    {
        header('Content-Type: application/json');

        if (!$this->input->is_ajax_request()) {
            echo json_encode(['success' => false, 'message' => 'Metode request tidak valid']);
            return;
        }

        $role = $this->session->userdata('role');
        $pj_model_data = null; 
        if ($role === 'Admin' || $role === 'Kepala Lingkungan') {
            $pj_id_from_form = $this->input->get('pj_id');

            if (!$pj_id_from_form) {
                echo json_encode(['success' => false, 'message' => 'Silakan pilih Penanggung Jawab terlebih dahulu.']);
                return;
            }
            $pj_model_data = $this->PenanggungJawabModel->getById($pj_id_from_form);

        } elseif ($role === 'Penanggung Jawab') {
            $user_id = $this->session->userdata('user_id');
            if ($user_id) {
                $pj_model_data = $this->PenanggungJawabModel->getByUserId($user_id);
            }

        } else {
              echo json_encode(['success' => false, 'message' => 'Akses tidak diizinkan.']);
            return;
        }

        if (!$pj_model_data) {
            echo json_encode(['success' => false, 'message' => 'Data Penanggung Jawab tidak ditemukan.']);
            return;
        }

        if (empty($pj_model_data->latitude) || empty($pj_model_data->longitude)) {
            echo json_encode(['success' => false, 'message' => 'Data lokasi untuk Penanggung Jawab ini belum lengkap.']);
            return;
        }
        
        echo json_encode([
            'success' => true,
            'data'    => [
                'latitude'      => $pj_model_data->latitude,
                'longitude'     => $pj_model_data->longitude,
                'alamat_detail' => $pj_model_data->alamat_detail,
                'alamat_no'     => $pj_model_data->alamat_no,
                'alamat_maps'   => $pj_model_data->alamat_maps
            ]
        ]);
    }

    public function detail_admin($uuid)
    {
        $this->check_role(['Admin', 'Kepala Lingkungan']);
        $pj = $this->PenanggungJawabModel->getByUUID($uuid);
        if (!$pj) {
            show_404();
        }
        $anggota_keluarga = $this->PenanggungJawabModel->getAnggotaKeluarga($pj->id);
        $data['pj'] = $pj;
        $data['anggota_keluarga'] = $anggota_keluarga;
        $data['title'] = "Detail Penanggung Jawab | SIPANDU Nuansa Utama";
        $this->load->view('pj/pj_details_admin', $data);
    }

}
