<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PenghuniModel extends CI_Model
{

    private $table = 'penghuni';

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('string');
        $this->load->database();
    }

    private function generate_uuid()
    {
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

    public function getAll()
    {
        return $this->db->get('penghuni')->result();
    }

    public function get_verifikasi_bulanini()
    {
        $this_month = date('Y-m'); 

        return $this->db
            ->select('penghuni.*, penanggung_jawab.nama_pj') 
            ->from('penghuni')
            ->join('penanggung_jawab', 'penanggung_jawab.id = penghuni.penanggung_jawab_id', 'left')
            ->where('penghuni.status_verifikasi', 'Diterima')
            ->where('penghuni.status_penghuni', 'Aktif')
            ->like('penghuni.tanggal_masuk', $this_month, 'after')
            ->order_by('penghuni.tanggal_masuk', 'DESC')
            ->get()
            ->result_array();
    }

    public function getByStatus($status)
    {
        $this->db->select('penghuni.*, penanggung_jawab.nama_pj as pj_nama')
            ->from($this->table)
            ->join('penanggung_jawab', 'penanggung_jawab.id = penghuni.penanggung_jawab_id');

        if (is_array($status)) {
            $this->db->where_in('status_verifikasi', $status);
        } else {
            $this->db->where('status_verifikasi', $status);
        }

        return $this->db->get()->result();
    }

    public function getByPJ($pj_id)
    {
        return $this->db->get_where($this->table, ['penanggung_jawab_id' => $pj_id])->result();
    }

    public function getById($id)
    {
        return $this->db->select('penghuni.*, kaling.nama as kaling_nama, penanggung_jawab.nama_pj as pj_nama, wilayah.wilayah as wilayah')
            ->from($this->table)
            ->join('kaling', 'kaling.id = penghuni.kaling_id')
            ->join('penanggung_jawab', 'penanggung_jawab.id = penghuni.penanggung_jawab_id')
            ->join('wilayah', 'wilayah.id = penghuni.wilayah_id')
            ->where('penghuni.id', $id)
            ->get()
            ->row();
    }

    public function getByUuid($uuid)
    {
        $this->db->select('penghuni.*, 
            kaling.nama as kaling_nama,
            pj.nama_pj as pj_nama,
            wilayah.wilayah as wilayah,
            wilayah.id as wilayah_id');
        $this->db->from($this->table);
        $this->db->join('kaling', 'kaling.id = penghuni.kaling_id', 'left');
        $this->db->join('penanggung_jawab pj', 'pj.id = penghuni.penanggung_jawab_id', 'left');
        $this->db->join('wilayah', 'wilayah.id = penghuni.wilayah_id', 'left');
        $this->db->where('penghuni.uuid', $uuid);
        return $this->db->get()->row();
    }

    public function insert($data)
    {
        $data['uuid'] = $this->generate_uuid();
        if (!isset($data['status_penghuni'])) {
            $data['status_penghuni'] = 'Tidak Aktif';
        }
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        if (isset($data['status_verifikasi'])) {
            if ($data['status_verifikasi'] === 'Ditolak') {
                $data['status_penghuni'] = 'Tidak Aktif';
                if (!isset($data['tanggal_keluar'])) {
                    $data['tanggal_keluar'] = date('Y-m-d');
                }
            } elseif ($data['status_verifikasi'] === 'Diterima') {
                $data['status_penghuni'] = 'Aktif';
                $data['tanggal_keluar'] = null;
            }
        }

        $this->db->where('id', $id);
        return $this->db->update('penghuni', $data);
    }

    public function updateByUuid($uuid, $data)
    {
        if (isset($data['status_verifikasi'])) {
            if ($data['status_verifikasi'] === 'Ditolak') {
                $data['status_penghuni'] = 'Tidak Aktif';
                if (!isset($data['tanggal_keluar'])) {
                    $data['tanggal_keluar'] = date('Y-m-d');
                }
            } elseif ($data['status_verifikasi'] === 'Diterima') {
                $data['status_penghuni'] = 'Aktif';
                $data['tanggal_keluar'] = null;
            }
        }

        if (isset($data['status_penghuni']) && $data['status_penghuni'] === 'Tidak Aktif' && !isset($data['tanggal_keluar'])) {
            $data['tanggal_keluar'] = date('Y-m-d');
        }

        $this->db->where('uuid', $uuid);
        return $this->db->update('penghuni', $data);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    public function getAllWithRelations()
    {
        return $this->db->select('p.*, pj.nama_pj as nama_pj, k.nama as nama_kaling, w.wilayah as nama_wilayah')
            ->from('penghuni p')
            ->join('penanggung_jawab pj', 'pj.id = p.penanggung_jawab_id')
            ->join('kaling k', 'k.id = p.kaling_id')
            ->join('wilayah w', 'w.id = p.wilayah_id')
            ->get()
            ->result();
    }

    public function getJumlahPendatangPerPJ()
    {
        $result = $this->db->select('pj.id, pj.nama_pj as nama_pj, COUNT(p.id) as jumlah_pendatang, GROUP_CONCAT(p.nama_lengkap SEPARATOR ", ") as nama_pendatang')
            ->from('penghuni p')
            ->join('penanggung_jawab pj', 'pj.id = p.penanggung_jawab_id')
            ->where('p.status_verifikasi', 'Diterima')
            ->group_by('pj.id, pj.nama_pj')
            ->get()
            ->result();
        return $result;
    }

    public function getJumlahPendatangPerTujuan()
    {
        return $this->db->select('tujuan, COUNT(id) as jumlah')
            ->from($this->table)
            ->where('status_verifikasi', 'Diterima')
            ->group_by('tujuan')
            ->get()
            ->result();
    }

    public function getJumlahPendatangPerTujuanByWilayah($wilayah_uuid)
    {
        $wilayah = $this->db->get_where('wilayah', ['uuid' => $wilayah_uuid])->row();
        if (!$wilayah) {
            return [];
        }

        return $this->db
            ->select('tujuan, COUNT(*) as jumlah')
            ->from($this->table)
            ->where([
                'wilayah_id' => $wilayah->id,
                'status_verifikasi' => 'Diterima'
            ])
            ->group_by('tujuan')
            ->get()
            ->result();
    }

    public function getPenghuniBaruCoordinates()
    {
        return $this->db->select('nama_lengkap as nama, latitude as lat, longitude as lng')
            ->from($this->table)
            ->where('status_verifikasi', 'Diterima')
            ->where('latitude IS NOT NULL')
            ->where('longitude IS NOT NULL')
            ->get()
            ->result();
    }

    public function getPendatangByPJAndKaling()
    {
        return $this->db->select('
            p.*,
            pj.nama_pj as nama_pj,
            pj.id as pj_id,
            pj.no_hp as pj_telepon,
            pj.alamat_detail as pj_alamat,
            pj.alamat_no as pj_alamat_no,
            k.nama as nama_kaling,
            k.id as kaling_id')
            ->from('penghuni p')
            ->join('penanggung_jawab pj', 'pj.id = p.penanggung_jawab_id')
            ->join('kaling k', 'k.id = p.kaling_id')
            ->where('p.status_verifikasi', 'Diterima')
            ->order_by('pj.nama_pj', 'ASC')
            ->order_by('k.nama', 'ASC')
            ->order_by('p.nama_lengkap', 'ASC')
            ->get()
            ->result();
    }

    public function getTerverifikasiByPJ($pj_id)
    {
        $this->db->select('penghuni.*, penanggung_jawab.nama_pj as pj_nama');
        $this->db->from('penghuni');
        $this->db->join('penanggung_jawab', 'penanggung_jawab.id = penghuni.penanggung_jawab_id');
        $this->db->where('penghuni.penanggung_jawab_id', $pj_id);
        $this->db->where_in('penghuni.status_verifikasi', ['Diterima', 'Ditolak']);
        return $this->db->get()->result();
    }

    public function getAllTerverifikasi()
    {
        $this->db->select('penghuni.*, penanggung_jawab.nama_pj as pj_nama');
        $this->db->from('penghuni');
        $this->db->join('penanggung_jawab', 'penanggung_jawab.id = penghuni.penanggung_jawab_id');
        $this->db->where_in('penghuni.status_verifikasi', ['Diterima', 'Ditolak']);
        return $this->db->get()->result();
    }

    public function getAllPendatang()
    {
        $this->db->select('penghuni.*, pj.nama_pj as pj_nama, pj.no_hp as pj_no_hp');
        $this->db->from('penghuni');
        $this->db->join('penanggung_jawab pj', 'pj.id = penghuni.penanggung_jawab_id');
        $this->db->where('penghuni.status_verifikasi', 'Diterima');
        $this->db->order_by('pj.nama_pj', 'ASC');
        $this->db->order_by('penghuni.nama_lengkap', 'ASC');
        return $this->db->get()->result();
    }

    public function getByPenanggungJawab($pj_id)
    {
        $this->db->select('penghuni.*, wilayah.wilayah');
        $this->db->from('penghuni');
        $this->db->join('wilayah', 'wilayah.id = penghuni.wilayah_id');
        $this->db->where('penghuni.penanggung_jawab_id', $pj_id);
        $this->db->where('penghuni.status_verifikasi', 'Diterima');
        return $this->db->get()->result();
    }

    public function getByPenanggungJawabAndStatus($pj_id, $status)
    {
        return $this->db
            ->select('penghuni.*, wilayah.wilayah as wilayah_nama')
            ->from('penghuni')
            ->join('wilayah', 'wilayah.id = penghuni.wilayah_id')
            ->where('penghuni.penanggung_jawab_id', $pj_id)
            ->where('penghuni.status_verifikasi', $status)
            ->get()
            ->result();
    }

    public function getByStatusPJ($pj_id, $status)
    {
        return $this->db
            ->select('penghuni.*, wilayah.wilayah as wilayah_nama')
            ->from('penghuni')
            ->join('wilayah', 'wilayah.id = penghuni.wilayah_id')
            ->where('penghuni.penanggung_jawab_id', $pj_id)
            ->where('penghuni.status_verifikasi', $status)  
            ->where('penghuni.status_penghuni', 'Aktif')     
            ->get()
            ->result();
    }

    public function getByStatusVerifikasiSorted($statuses)
    {
        $this->db->select('penghuni.*, wilayah.wilayah as wilayah_nama, pj.nama_pj as nama_pj');
        $this->db->from('penghuni');
        $this->db->join('wilayah', 'wilayah.id = penghuni.wilayah_id');
        $this->db->join('penanggung_jawab pj', 'pj.id = penghuni.penanggung_jawab_id');
        $this->db->where_in('penghuni.status_verifikasi', $statuses);
        $this->db->order_by('FIELD(penghuni.status_penghuni, "Aktif", "Tidak Aktif")', '', FALSE);
        $this->db->order_by('penghuni.created_at', 'DESC');
        return $this->db->get()->result();
    }
}
