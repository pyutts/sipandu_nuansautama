<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SuratModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function simpan_surat_izin_tinggal($data)
    {
        return $this->db->insert('surat_izin_tinggal', $data);
    }

    public function simpan_surat_pernyataan($data)
    {
        return $this->db->insert('surat_pernyataan', $data);
    }

    public function get_surat_izin_tinggal($id = null)
    {
        if ($id === null) {
            return $this->db->get('surat_izin_tinggal')->result();
        }
        return $this->db->get_where('surat_izin_tinggal', ['id' => $id])->row();
    }

    public function get_surat_pernyataan($id = null)
    {
        if ($id === null) {
            return $this->db->get('surat_pernyataan')->result();
        }
        return $this->db->get_where('surat_pernyataan', ['id' => $id])->row();
    }

    public function insert($data)
    {
        if (empty($data['uuid'])) {
            $data['uuid'] = $this->generate_uuid();
        }
        $this->db->insert('surat', $data);
        return $this->db->insert_id();
    }

    public function getKeperluan(){
        $this->db->select('surat.keperluan'); 
        $this->db->from('surat');
        return $this->db->get()->result();
    }

    public function generate_uuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function getSuratPengantar($id){
        $this->db->select('surat.*, penghuni.nama_lengkap, penghuni.nik, penghuni.tempat_lahir, penghuni.tanggal_lahir, penghuni.jenis_kelamin, penghuni.agama, penghuni.alamat_asal, penghuni.kecamatan_asal, penghuni.kabupaten_asal, penghuni.provinsi_asal, penghuni.alamat_sekarang, penghuni.tujuan, penghuni.tanggal_masuk, penghuni.tanggal_keluar, wilayah.wilayah, kaling.nama as nama_kaling');
        $this->db->from('surat');
        $this->db->join('penghuni', 'penghuni.id = surat.penghuni_id');
        $this->db->join('wilayah', 'wilayah.id = penghuni.wilayah_id');
        $this->db->join('kaling', 'kaling.id = penghuni.kaling_id'); 
        $this->db->where('surat.id', $id);
        return $this->db->get()->row();
    }

    public function getSuratPengantarByUuid($uuid)
    {
        $this->db->select('surat.*, penghuni.nama_lengkap, penghuni.nik, penghuni.tempat_lahir, penghuni.tanggal_lahir, penghuni.jenis_kelamin, penghuni.alamat_asal, penghuni.kecamatan_asal, penghuni.kabupaten_asal, penghuni.provinsi_asal, penghuni.alamat_sekarang, wilayah.wilayah, kaling.nama as nama_kaling');
        $this->db->from('surat');
        $this->db->join('penghuni', 'penghuni.id = surat.penghuni_id');
        $this->db->join('wilayah', 'wilayah.id = penghuni.wilayah_id', 'left');
        $this->db->join('kaling', 'kaling.id = penghuni.kaling_id', 'left'); 
        $this->db->where('surat.uuid', $uuid);
        return $this->db->get()->row();
    }

    public function getSuratMenungguVerifikasi()
    {
        $this->db->select('surat.*, penghuni.nama_lengkap as nama_penghuni, penanggung_jawab.nama_pj as nama_pj');
        $this->db->from('surat');
        $this->db->join('penghuni', 'penghuni.id = surat.penghuni_id');
        $this->db->join('penanggung_jawab', 'penanggung_jawab.id = surat.pj_id');
        $this->db->where('surat.status_proses', 'Diproses');
        $this->db->order_by('surat.tanggal_pengajuan', 'DESC');
        return $this->db->get()->result();
    }

    public function getSuratTerverifikasi()
    {
        $this->db->select('surat.*, penghuni.nama_lengkap as nama_penghuni, penghuni.uuid as penghuni_uuid, penanggung_jawab.nama_pj as nama_pj');
        $this->db->from('surat');
        $this->db->join('penghuni', 'penghuni.id = surat.penghuni_id');
        $this->db->join('penanggung_jawab', 'penanggung_jawab.id = surat.pj_id');
        $this->db->where_in('surat.status_proses', ['Diterima', 'Ditolak']);
        $this->db->order_by('surat.tanggal_pengajuan', 'DESC');
        return $this->db->get()->result();
    }

    public function getSuratTerverifikasiByPJ($pj_id)
    {
        $this->db->select('surat.*, penghuni.nama_lengkap as nama_penghuni, penghuni.uuid as penghuni_uuid, penanggung_jawab.nama_pj as nama_pj');
        $this->db->from('surat');
        $this->db->join('penghuni', 'penghuni.id = surat.penghuni_id');
        $this->db->join('penanggung_jawab', 'penanggung_jawab.id = surat.pj_id');
        $this->db->where_in('surat.status_proses', ['Diterima', 'Ditolak']);
        $this->db->where('surat.pj_id', $pj_id);
        $this->db->order_by('surat.tanggal_pengajuan', 'DESC');
        return $this->db->get()->result();
    }

    public function getSuratMenungguVerifikasiByPJ($pj_id)
    {
        $this->db->select('surat.*, penghuni.nama_lengkap as nama_penghuni, penanggung_jawab.nama_pj as nama_pj');
        $this->db->from('surat');
        $this->db->join('penghuni', 'penghuni.id = surat.penghuni_id');
        $this->db->join('penanggung_jawab', 'penanggung_jawab.id = surat.pj_id');
        $this->db->where('surat.status_proses', 'Diproses');
        $this->db->where('surat.pj_id', $pj_id);
        $this->db->order_by('surat.tanggal_pengajuan', 'DESC');
        return $this->db->get()->result();
    }

    public function updateStatusProses($id, $status)
    {
        $this->db->where('id', $id);
        return $this->db->update('surat', ['status_proses' => $status]);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('surat', $data);
    }

    public function insertSuratAnggota($data)
    {
        $this->db->insert('surat', $data);
        return $this->db->insert_id();
    }

    public function getSuratMenungguVerifikasiAnggota()
    {
        $this->db->select('surat.*, anggota_keluarga.nama as nama_anggota, penanggung_jawab.nama_pj');
        $this->db->from('surat');
        $this->db->join('anggota_keluarga', 'anggota_keluarga.id = surat.anggota_keluarga_id');
        $this->db->join('penanggung_jawab', 'penanggung_jawab.id = surat.pj_id');
        $this->db->where('surat.status_proses', 'Diproses');
        return $this->db->get()->result();
    }

    public function getSuratMenungguVerifikasiAnggotaByPJ($pj_id)
    {
        $this->db->select('surat.*, anggota_keluarga.nama as nama_anggota');
        $this->db->from('surat');
        $this->db->join('anggota_keluarga', 'anggota_keluarga.id = surat.anggota_keluarga_id');
        $this->db->where('surat.pj_id', $pj_id);
        $this->db->where('surat.status_proses', 'Diproses');
        return $this->db->get()->result();
    }

    public function getSuratTerverifikasiAnggota()
    {
        $this->db->select('surat.*, anggota_keluarga.nama as nama_anggota, anggota_keluarga.uuid as anggota_keluarga_uuid, penanggung_jawab.nama_pj');
        $this->db->from('surat');
        $this->db->join('anggota_keluarga', 'anggota_keluarga.id = surat.anggota_keluarga_id');
        $this->db->join('penanggung_jawab', 'penanggung_jawab.id = surat.pj_id');
        $this->db->where_in('surat.status_proses', ['Diterima', 'Ditolak']);
        return $this->db->get()->result();
    }

    public function getSuratTerverifikasiAnggotaByPJ($pj_id)
    {
        $this->db->select('surat.*, anggota_keluarga.nama as nama_anggota, anggota_keluarga.uuid as anggota_keluarga_uuid');
        $this->db->from('surat');
        $this->db->join('anggota_keluarga', 'anggota_keluarga.id = surat.anggota_keluarga_id');
        $this->db->where('surat.pj_id', $pj_id);
        $this->db->where_in('surat.status_proses', ['Diterima', 'Ditolak']);
        return $this->db->get()->result();
    }

    public function getSuratAnggotaKeluarga()
    {
        $this->db->select('surat.*, anggota_keluarga.nama as nama_anggota, penanggung_jawab.nama_pj');
        $this->db->from('surat');
        $this->db->join('anggota_keluarga', 'anggota_keluarga.id = surat.anggota_keluarga_id');
        $this->db->join('penanggung_jawab', 'penanggung_jawab.id = surat.pj_id');
        return $this->db->get()->result();
    }

    public function getByUuid($uuid)
    {
        return $this->db->get_where('surat', ['uuid' => $uuid])->row();
    }

    public function getDetailSuratByUuid($uuid)
    {
        $this->db->select('surat.*,penghuni.nama_lengkap as nama_penghuni,penghuni.nik as nik_penghuni,anggota_keluarga.nama as nama_anggota,anggota_keluarga.nik_anggota as nik_anggotapenanggung_jawab.nama_pj');
        $this->db->from('surat');
        $this->db->join('penghuni', 'penghuni.id = surat.penghuni_id', 'left');
        $this->db->join('anggota_keluarga', 'anggota_keluarga.id = surat.anggota_keluarga_id', 'left');
        $this->db->join('penanggung_jawab', 'penanggung_jawab.id = surat.pj_id', 'left');
        $this->db->where('surat.uuid', $uuid);
        return $this->db->get()->row();
    }

    public function getById($id)
    {
        return $this->db->get_where('surat', ['id' => $id])->row();
    }

    public function hapus_surat($uuid)
    {
        $this->db->where('uuid', $uuid);
        return $this->db->delete('surat');
    }

}
