<?php $this->load->view('partials/header'); ?>

<div class="container-fluid">
    <div class="text-center mt-5">
        <i class="fa fa-exclamation-triangle fa-5x text-warning mb-3"></i>
        <h1 class="display-4">403 - Akses Ditolak</h1>
        <p class="lead">Link pendaftaran ini sudah kadaluarsa atau tidak valid lagi.</p>

        <button onclick="window.location.href='<?= base_url('shortlink') ?>'" class="btn btn-danger mt-3">
            <i class="fa fa-arrow-left"></i> Kembali ke Halaman Shortlink
        </button>

    </div>
</div>