<?php
defined('BASEPATH') OR exit('No direct script access allowed');


use Pusher\Pusher;

class Pusher_lib {
    protected $pusher;

   public function __construct() {
        $CI =& get_instance();
        $CI->load->config('pusher');

        $this->pusher = new Pusher(
            $CI->config->item('pusher_key'),
            $CI->config->item('pusher_secret'),
            $CI->config->item('pusher_app_id'),
            [
                'cluster' => $CI->config->item('pusher_cluster'),
                'useTLS' => true
            ]
        );
    }

    public function trigger($channel, $event, $data) {
        $this->pusher->trigger($channel, $event, $data);
    }
}


