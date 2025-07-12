<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');

        if (!$this->session->userdata('user_id') || !$this->session->userdata('role')) {
            $this->session->set_flashdata('error', 'Silakan login kembali!');
            redirect('auth');
        }
    }

    protected function check_role($allowed_roles = []) {
    $role = strtolower($this->session->userdata('role'));
    $allowed_roles = array_map('strtolower', $allowed_roles); 
    if (!in_array($role, $allowed_roles)) {
        redirect('dashboard/error');
        }
    }    
    
}

