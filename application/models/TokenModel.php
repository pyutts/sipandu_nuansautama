<?php
defined('BASEPATH') or exit('No direct script access allowed');
class TokenModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function generate_token()
    {
        $token = bin2hex(random_bytes(32));
        $data = [
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s'),
            'is_used' => 0
        ];
        $this->db->insert('registration_tokens', $data);
        return $token;
    }

    public function validate_token($token)
    {
        $this->db->where('token', $token);
        $this->db->where('is_used', 0);
        $this->db->where('created_at >=', date('Y-m-d H:i:s', strtotime('-3 days')));
        return $this->db->get('registration_tokens')->row();
    }

    public function mark_token_as_used($token, $pj_id)
    {
        $this->db->where('token', $token);
        $this->db->update('registration_tokens', [
            'is_used' => 1,
            'pj_id' => $pj_id
        ]);
    }

    public function clean_expired_tokens()
    {
        $this->db->where('is_used', 0);
        $this->db->where('created_at <', date('Y-m-d H:i:s', strtotime('-3 days')));
        $this->db->delete('registration_tokens');
    }
}
