    <?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class NotifikasiController extends MY_Controller 
    {
        public function delete()
        {
            $notifId = $this->input->post('id');

            if (!$this->session->userdata('user_id')) {
                show_error('Unauthorized', 401);
                return;
            }

            $this->load->library('Pusher_lib');
            $this->pusher_lib->trigger('notifikasi-surat', 'notif-deleted', [
                'notif_id' => $notifId
            ]);

            echo json_encode(['status' => 'success']);
        }

    }