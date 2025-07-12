<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
   <h3 class="mb-4 fw-bold text-center">Pilih Jenis Surat Pengantar</h3>
    <div class="row justify-content-center g-4">
        <div class="col-md-5">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-id-card fa-3x text-primary"></i>
                    <h5 class="card-title mt-3">Surat Pengantar Pendatang</h5>
                    <p class="card-text text-muted">
                        Surat pengantar untuk pendatang yang ingin mengurus administrasi di lingkungan Nuansa Utama.
                    </p>
                    <button onclick="window.location.href='<?= base_url('dashboard/surat/view/pendatang') ?>'" class="btn btn-primary mt-2">Pilih Surat</button>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-people-group fa-3x text-success"></i>
                    <h5 class="card-title mt-3">Surat Pengantar Anggota Keluarga</h5>
                    <p class="card-text text-muted">
                        Surat pengantar untuk anggota keluarga yang membutuhkan dokumen resmi dari lingkungan Nuansa Utama.
                    </p>
                    <button onclick="window.location.href='<?= base_url('dashboard/surat/view/anggota') ?>'" class="btn btn-success mt-2">Pilih Surat</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5 justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-info text-center" role="alert">
                <i class="fa-solid fa-circle-info me-2"></i>
                Silakan pilih jenis surat sesuai kebutuhan Anda. Pastikan data yang Anda pilih sudah benar dan sudah terverifikasi sesuai dengan persyaratan yang berlaku.
            </div>
        </div>
    </div>
</div>
        
<?php $this->load->view('partials/watermark'); ?>
<?php $this->load->view('partials/footer'); ?>
