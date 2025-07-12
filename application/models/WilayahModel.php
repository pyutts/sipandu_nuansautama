<?php
defined('BASEPATH') or exit('No direct script access allowed');

class WilayahModel extends CI_Model
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

    public function getWilayah()
    {
        return $this->db->limit(1)->get('wilayah')->row();
    }

    public function get_all()
    {
        return $this->db->get('wilayah')->result();
    }

    public function get_by_id($id)
    {
        if (!$id) {
            return null;
        }

        return $this->db->where('id', $id)
            ->or_where('uuid', $id)
            ->get('wilayah')
            ->row();
    }

    public function getById($id)
    {
        return $this->db->get_where('wilayah', ['id' => $id])->row();
    }

    public function insert($data)
    {
        $insert_data = [
            'uuid' => $this->generate_uuid(),
            'wilayah' => $data['wilayah'],
        ];
        
        return $this->db->insert('wilayah', $insert_data);
    }

    public function update($uuid, $data)
    {
        if (!$uuid) {
            return false;
        }

        $update_data = [
            'wilayah' => $data['wilayah'],
        ];

        $this->db->where('uuid', $uuid);
        return $this->db->update('wilayah', $update_data);
    }

    public function delete($uuid)
    {
        if (!$uuid) {
            return false;
        }

        $wilayah = $this->db->get_where('wilayah', ['uuid' => $uuid])->row();
        if (!$wilayah) {
            return false;
        }

        $this->db->where('wilayah_id', $wilayah->id);
        $kaling_count = $this->db->count_all_results('kaling');

        $this->db->where('wilayah_id', $wilayah->id);
        $pj_count = $this->db->count_all_results('penanggung_jawab');

        $this->db->where('wilayah_id', $wilayah->id);
        $penghuni_count = $this->db->count_all_results('penghuni');

        if ($kaling_count > 0 || $pj_count > 0 || $penghuni_count > 0) {
            return false;
        }

        $this->db->where('uuid', $uuid);
        return $this->db->delete('wilayah');
    }
}