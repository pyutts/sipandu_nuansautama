<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>
<div class="container-fluid">
    <div class="datatables">
        <div class="card">
            <div class="card-body">
               <h5 class="card-title fw-semibold mb-4">Tambah Data Admin</h5>
                <form action="<?= base_url('dashboard/admin/store') ?>" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control <?= form_error('username') ? 'is-invalid' : '' ?>" id="username" value="<?= set_value('username'); ?>" required>
                        <?= form_error('username', '<div class="invalid-feedback">', '</div>'); ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control <?= form_error('nama') ? 'is-invalid' : '' ?>" id="nama" value="<?= set_value('nama'); ?>" required>
                        <?= form_error('nama', '<div class="invalid-feedback">', '</div>'); ?>
                    </div>


                    <div class="mb-3 position-relative">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control <?= form_error('password') ? 'is-invalid' : '' ?>" id="inputPassword" required>
                            <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                <i class="bi bi-eye-slash" id="iconPassword"></i>
                            </span>
                        </div>
                        <?= form_error('password', '<div class="invalid-feedback d-block">', '</div>'); ?>
                    </div>
                    <button type="submit" class="btn btn-secondary">Simpan</button>
                    <a href="<?= base_url('dashboard/admin/view') ?>" class="btn btn-danger">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('partials/watermark'); ?>

<?php HelperJS::start('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#togglePassword').on('click', function() {
            const $passwordInput = $('#inputPassword');
            const $icon = $('#iconPassword');
            const type = $passwordInput.attr('type') === 'password' ? 'text' : 'password';
            $passwordInput.attr('type', type);
            $icon.toggleClass('bi-eye bi-eye-slash');
        });

        <?php if ($this->session->flashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: '<?= $this->session->flashdata('success'); ?>',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    });
</script>
<?php HelperJS::end('scripts'); ?>

<?php $this->load->view('partials/footer'); ?>