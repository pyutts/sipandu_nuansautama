<?php $this->load->view('partials/header'); ?>

<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
  data-sidebar-position="fixed" data-header-position="fixed">
  <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
    <div class="d-flex align-items-center justify-content-center w-100">
      <div class="row justify-content-center w-100">
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <div class="text-nowrap logo-img text-center d-block py-3 w-100">
                <img src="<?= base_url('assets/images/logos/icon.png'); ?>" width="180" alt="">
              </div>
              <p class="text-center">Daftar Akun Khusus Penanggung Jawab di Wilayah Nuansa Utama</p>
              <form method="post" action="<?= base_url('daftar/submit_pj') ?>">
                <input type="hidden" name="token" value="<?= $token ?>">

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control <?= !empty($error_username) ? 'is-invalid' : '' ?>" 
                    name="username" id="username" value="<?= set_value('username') ?>" required>
                      <div class="invalid-feedback">
                          <?= $error_username ?? 'Wajib diisi!' ?>
                      </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control <?= !empty($error_email) ? 'is-invalid' : '' ?>" 
                    name="email" id="email" value="<?= set_value('email') ?>" required>
                      <div class="invalid-feedback">
                          <?= $error_email ?? 'Wajib diisi!' ?>
                      </div>
                </div>

                <div class="mb-3 position-relative">
                  <label class="form-label">Password</label>
                    <div class="input-group">
                      <input type="password" 
                            name="password" 
                            class="form-control <?= !empty($error_password) ? 'is-invalid' : '' ?>"  
                            id="inputPassword" required>
                      <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                        <i class="bi bi-eye-slash" id="iconPassword"></i>
                      </span>
                    </div>
                  <div class="invalid-feedback">
                      <?= $error_password ?? 'Wajib diisi!' ?>
                  </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Daftar</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('partials/watermark'); ?>

<?php HelperJS::start('scripts'); ?>
  <script>
  document.addEventListener("DOMContentLoaded", function() {
      $('#togglePassword').on('click', function () {
          const $passwordInput = $('#inputPassword');
          const $icon = $('#iconPassword');
          const type = $passwordInput.attr('type') === 'password' ? 'text' : 'password';
          $passwordInput.attr('type', type);
          $icon.toggleClass('bi-eye bi-eye-slash');
      });

      <?php
      $success_message = $this->session->flashdata('success');
      if ($success_message):
      ?>
          Swal.fire({
              icon: 'success',
              title: 'Sukses!',
              text: '<?= addslashes($success_message); ?>',
              showConfirmButton: true,
              confirmButtonText: 'OK'
          });
      <?php endif; ?>

      <?php
      $eror_message = $this->session->flashdata('eror');
      if ($eror_message):
      ?>
          Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: '<?= addslashes($eror_message); ?>',
              showConfirmButton: false
          });
      <?php endif; ?>

  });
  </script>
<?php HelperJS::end('scripts'); ?>

<?php $this->load->view('partials/footer'); ?>

