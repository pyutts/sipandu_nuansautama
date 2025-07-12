<?php
defined('BASEPATH') or exit('No direct script access allowed');
class AdminModel extends CI_Model
{
    public function getByUserId($user_id)
    {
        return $this->db->get_where('admin', ['user_id' => $user_id])->row();
    }
    
    public function getAll()
    {
        $this->db->select('admin.id, admin.uuid, admin.nama, admin.user_id, users.username');
        $this->db->from('admin');
        $this->db->join('users', 'users.id = admin.user_id');
        return $this->db->get()->result();
    }

    public function getById($id)
    {
        $this->db->select('admin.id AS id, admin.nama, users.username, users.password, users.id AS user_id');
        $this->db->from('admin');
        $this->db->join('users', 'users.id = admin.user_id');
        $this->db->where('admin.id', $id);
        return $this->db->get()->row();
    }

    public function getByUuid($uuid)
    {
        $this->db->select('admin.id AS id, admin.uuid, admin.nama, users.username, users.password, users.id AS user_id');
        $this->db->from('admin');
        $this->db->join('users', 'users.id = admin.user_id');
        $this->db->where('admin.uuid', $uuid);
        return $this->db->get()->row();
    }

    // Fungsi untuk mendapatkan admin berdasarkan tabel admin
    public function countAdmins()
    {
        return $this->db->count_all('admin');
    }

    // Fungsi untuk mendapatkan kaling berdasarkan tabel kaling
    public function countKaling()
    {
        return $this->db->count_all('kaling');
    }

    // Fungsi untuk mendapatkan penanggung jawab berdasarkan tabel penanggung_jawab dan anggota_keluarga
    public function countWarga()
    {
        $total_pj = $this->db->count_all('penanggung_jawab');
        $this->db->select('COUNT(*) as total_anggota');
        $query = $this->db->get('anggota_keluarga')->row();
        $total_anggota = $query ? $query->total_anggota : 0;
        return $total_pj + $total_anggota;
    }

    // Fungsi untuk mendapatkan penghuni berdasarkan tabel penghuni
    public function countUsers()
    {
        $this->db->where('status_penghuni', 'Aktif');
        return $this->db->count_all_results('penghuni');
    }

   // Ambil data Kaling => lihat detail dashboard
   public function getAllKaling()
    {
       return $this->db->get('kaling')->result();
    }


    // Ambil data Penanggung Jawab => lihat detail dashboard
    public function getAllPenanggungJawab()
    {
        return $this->db->get('penanggung_jawab')->result();
    }

    // Ambil data Warga dari penanggung jawab + anggota keluarga => lihat detail dashboard
    public function getAllWarga()
    {
        $this->db->select('penanggung_jawab.nama_pj AS nama_lengkap, CONCAT(penanggung_jawab.alamat_detail, " No. ", penanggung_jawab.alamat_no) AS alamat, penanggung_jawab.no_hp, "Kepala Keluarga" AS status');
        $this->db->from('penanggung_jawab');
        $pj = $this->db->get()->result();
        $this->db->select('anggota_keluarga.nama AS nama_lengkap, CONCAT(penanggung_jawab.alamat_detail, " No. ", penanggung_jawab.alamat_no) AS alamat, penanggung_jawab.no_hp, anggota_keluarga.hubungan AS status');
        $this->db->from('anggota_keluarga');
        $this->db->join('penanggung_jawab', 'anggota_keluarga.penanggung_jawab_id = penanggung_jawab.id');
        $anggota = $this->db->get()->result();
        return array_merge($pj, $anggota);
    }

    // Ambil data pendatang dan penanggung jawab => lihat detail dashboard
    public function getAllPendatang()
    {
        $this->db->select('penghuni.nama_lengkap, penghuni.alamat_detail, penghuni.alamat_no, penghuni.no_hp, penanggung_jawab.nama_pj AS nama_pj');
        $this->db->from('penghuni');
        $this->db->join('penanggung_jawab', 'penghuni.penanggung_jawab_id = penanggung_jawab.id', 'left');
        $this->db->where('penghuni.status_penghuni', 'Aktif');
        $this->db->where('penghuni.status_verifikasi', 'Diterima');
        return $this->db->get()->result();
    }

    public function insertUser($data)
    {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    public function insertAdmin($data)
    {
        $data['uuid'] = sprintf(
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
        return $this->db->insert('admin', $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('admin', $data);
    }

    public function delete($id)
    {
        $admin = $this->getById($id);
        $this->db->delete('admin', ['id' => $id]);
        $this->db->delete('users', ['id' => $admin->user_id]);
    }
}
