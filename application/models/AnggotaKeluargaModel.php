<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AnggotaKeluargaModel extends CI_Model
{
    public function getByPenanggungJawab($pj_id)
    {
        return $this->db->get_where('anggota_keluarga', ['penanggung_jawab_id' => $pj_id])->result();
    }

    public function getAllWithPJ()
    {
        $this->db->select('anggota_keluarga.*, penanggung_jawab.nama_pj');
        $this->db->from('anggota_keluarga');
        $this->db->join('penanggung_jawab', 'penanggung_jawab.id = anggota_keluarga.penanggung_jawab_id');
        return $this->db->get()->result();
    }

    public function getById($id)
    {
        return $this->db->get_where('anggota_keluarga', ['id' => $id])->row();
    }

    public function getByUuid($uuid)
    {
        return $this->db->get_where('anggota_keluarga', ['uuid' => $uuid])->row();
    }
}
