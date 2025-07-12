<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fa-solid fa-user-lock fa-5x text-danger mb-3"></i>
                </div>
                <h2 class="mb-2">Menu Terkunci, Lengkapi Data Anda!</h2>
                <p class="mb-4">Maaf, Anda harus melengkapi data diri Anda terlebih dahulu sebelum dapat mengakses fitur lainnya.</p>
                
                <div class="mb-3">
                    <button class="btn btn-danger" onclick="window.location.href='<?= base_url('dashboard/pj/editdata') ?>'">
                         <i class="fa-solid fa-arrow-left"></i> Lengkapi Data Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('partials/footer'); ?>