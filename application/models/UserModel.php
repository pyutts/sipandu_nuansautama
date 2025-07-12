<?php
defined('BASEPATH') or exit('No direct script access allowed');
class UserModel extends CI_Model
{
    public function get_by_username($username)
    {
        return $this->db->get_where('users', ['username' => $username])->row();
    }

    public function insert($data)
    {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    public function delete($id)
    {
        return $this->db->delete('users', ['id' => $id]);
    }

    public function update_password($user_id, $new_password)
    {
        $this->db->where('id', $user_id);
        return $this->db->update('users', ['password' => $new_password]);
    }
}
