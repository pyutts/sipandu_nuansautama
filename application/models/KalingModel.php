<?php
defined('BASEPATH') or exit('No direct script access allowed');
class KalingModel extends CI_Model
{
    public function getKaling()
    {
        return $this->db->limit(1)->get('kaling')->row();
    }
    public function getByUserId($user_id)
    {
        return $this->db->get_where('kaling', ['user_id' => $user_id])->row();
    }
    public function getAll()
    {
        $this->db->select('kaling.*, wilayah.wilayah as wilayah, users.username as username');
        $this->db->from('kaling');
        $this->db->join('wilayah', 'wilayah.id = kaling.wilayah_id', 'left');
        $this->db->join('users', 'users.id = kaling.user_id', 'left');
        return $this->db->get()->result();
    }

    public function getById($id)
    {
        return $this->db->get_where('kaling', ['id' => $id])->row();
    }

    public function getByWilayahId($wilayah_id)
    {
        return $this->db->get_where('kaling', ['wilayah_id' => $wilayah_id])->row();
    }

    public function getUserId($id)
    {
        $this->db->select('kaling.*, users.username');
        $this->db->from('kaling');
        $this->db->join('users', 'users.id = kaling.user_id');
        $this->db->where('kaling.id', $id);
        return $this->db->get()->row();
    }

    public function getByUuid($uuid)
    {
        $this->db->select('kaling.*, wilayah.wilayah as wilayah, users.username as username');
        $this->db->from('kaling');
        $this->db->join('wilayah', 'wilayah.id = kaling.wilayah_id', 'left');
        $this->db->join('users', 'users.id = kaling.user_id', 'left');
        $this->db->where('kaling.uuid', $uuid);
        return $this->db->get()->row();
    }

    public function insert($data)
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

        $this->db->insert('kaling', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('kaling', $data);
    }

    public function updateByUuid($uuid, $data)
    {
        $this->db->where('uuid', $uuid);
        $this->db->update('kaling', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('kaling');
    }

    public function deleteByUuid($uuid)
    {
        $this->db->where('uuid', $uuid);
        $this->db->delete('kaling');
    }
}
