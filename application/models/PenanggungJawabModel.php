<?php
defined('BASEPATH') or exit('No direct script access allowed');
class PenanggungJawabModel extends CI_Model
{
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
        return $this->db->get('penanggung_jawab')->result();
    }

    public function getAllPJ()
    {
        $this->db->select("pj.*, u.username, w.wilayah as wilayah_nama, CONCAT(pj.alamat_detail, IF(pj.alamat_no IS NOT NULL AND pj.alamat_no != '', CONCAT(' No ', pj.alamat_no), '')) as alamat_lengkap");
        $this->db->from('penanggung_jawab pj');
        $this->db->join('users u', 'pj.user_id = u.id');
        $this->db->join('wilayah w', 'pj.wilayah_id = w.id', 'left');
        return $this->db->get()->result();
    }

     public function getByUUID($uuid)
    {
        $this->db->select('pj.*, u.username, w.wilayah as wilayah_nama');
        $this->db->from('penanggung_jawab pj');
        $this->db->join('users u', 'pj.user_id = u.id');
        $this->db->join('wilayah w', 'pj.wilayah_id = w.id', 'left');
        $this->db->where('pj.uuid', $uuid);
        return $this->db->get()->row();
    }

    public function getById($id)
    {
        $this->db->select('pj.*, u.username, w.wilayah as wilayah_nama, p.alamat_sekarang');
        $this->db->from('penanggung_jawab pj');
        $this->db->join('users u', 'pj.user_id = u.id');
        $this->db->join('wilayah w', 'pj.wilayah_id = w.id', 'left');
        $this->db->join('penghuni p', 'p.penanggung_jawab_id = pj.id', 'left');
        $this->db->where('pj.id', $id);
        return $this->db->get()->row();
    }

    public function checkId($id)
    {
        return $this->db->get_where('penanggung_jawab', ['id' => $id])->row();
    }

    public function getByUserId($user_id)
    {
        return $this->db->get_where('penanggung_jawab', ['user_id' => $user_id])->row();
    }

    public function getPJById($user_id)
    {
        return $this->db
            ->select('penanggung_jawab.*')
            ->from('penanggung_jawab')
            ->join('users', 'users.id = penanggung_jawab.user_id')
            ->where('users.id', $user_id)
            ->get()
            ->row();
    }

    public function update($id, $data)
    {
        unset($data['uuid']); 
        $this->db->where('id', $id);
        return $this->db->update('penanggung_jawab', $data);
    }

    public function delete($id)
    {
        $pj = $this->getById($id);
        if ($pj) {
            $this->db->delete('users', ['id' => $pj->user_id]);
            $this->db->delete('penanggung_jawab', ['id' => $id]);
        }
    }

    public function create($data)
    {
        $data['uuid'] = $this->generate_uuid();
        return $this->db->insert('penanggung_jawab', $data);
    }

    public function save_anggota_keluarga($pj_id, $anggota_data) 
    {
        $anggota_data['penanggung_jawab_id'] = $pj_id;
        return $this->db->insert('anggota_keluarga', $anggota_data);
    }

    public function getAnggotaKeluarga($pj_id) 
    {
        return $this->db->get_where('anggota_keluarga', ['penanggung_jawab_id' => $pj_id])->result();
    }

    public function getAllWithWilayah()
    {
        $this->db->select('penanggung_jawab.*, wilayah.wilayah');
        $this->db->from('penanggung_jawab');
        $this->db->join('wilayah', 'wilayah.id = penanggung_jawab.wilayah_id');
        return $this->db->get()->result();
    }
    public function verifikasi_data($id)
    {
        $pj = $this->getById($id);
        if (!$pj) return false;
        
        $validasi_pj = [
            'no_kk',
            'nik',
            'nama_pj',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'email',
            'no_hp',
            'foto_kk',
            'alamat_maps',
            'alamat_detail',
            'alamat_no',
            'latitude',
            'longitude',
            'status_rumah',
            'wilayah_id'
        ];
        
        foreach ($validasi_pj as $field) {
            if (empty($pj->$field)) {
                return false;
            }
        }

        $anggota_list = $this->getAnggotaKeluarga($id);
        if (!$anggota_list || empty($anggota_list)) return false;

        $validasi_anggota = [
            'penanggung_jawab_id',
            'nik_anggota',
            'nama',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'hubungan',
            'pekerjaan'
        ];

        foreach ($anggota_list as $anggota) {
            foreach ($validasi_anggota as $field) {
                if (empty($anggota->$field)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function countAll()
    {
        return $this->db->count_all('penanggung_jawab');
    }
}
