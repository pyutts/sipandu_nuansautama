<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Tambah Data Wilayah</h5>
            <form action="<?= base_url('dashboard/wilayah/store') ?>" method="POST">
                <div class="mb-3">
                    <label for="wilayah" class="form-label">Nama Wilayah</label>
                    <input type="text" name="wilayah" class="form-control <?= form_error('wilayah') ? 'is-invalid' : '' ?>" id="wilayah" value="<?= set_value('wilayah'); ?>" required>
                    <?= form_error('wilayah', '<div class="invalid-feedback">', '</div>'); ?>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" onclick="window.location.href='<?= base_url('dashboard/wilayah/view') ?>'">Kembali</button>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('partials/watermark'); ?>
<?php $this->load->view('partials/footer'); ?>