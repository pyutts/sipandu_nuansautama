<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5.0.1/dist/tesseract.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="<?= base_url('/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?= base_url('/assets/js/sidebarmenu.js'); ?>"></script>
<script src="<?= base_url('/assets/js/app.min.js'); ?>"></script>

<?php HelperJS::render('scripts'); ?>

<script>
  $(document).ready(function () {
    $('#logoutButton').on('click', function () {
      Swal.fire({
        title: 'Apakah Anda akan logout?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, logout!',
        cancelButtonText: 'Batal',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          $('#logoutForm').submit();
        }
      });
    });

    <?php if ($this->session->flashdata('success_login')): ?>
      Swal.fire({
        icon: 'success',
        title: 'Berhasil Login',
        text: '<?= $this->session->flashdata('success_login'); ?>',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
      });
    <?php endif; ?>
  });

  const pusher = new Pusher('<?= config_item('pusher_key') ?>', {
    cluster: 'ap1'
  });

  const channel = pusher.subscribe('notifikasi-surat');

  const $notifDot = $('#notifDot');
  const $notifList = $('#notifList');

  let notifications = JSON.parse(localStorage.getItem('notifications')) || [];
  let deletedIds = JSON.parse(localStorage.getItem('deletedNotifIds')) || [];

  function getJenisSuratLabel(item) {
    if (item.anggota_keluarga_id) return 'Surat Pengantar Anggota';
    if (item.pj_id) return 'Surat Pengantar Penanggung Jawab';
    if (item.penghuni_id) return 'Surat Pengantar Pendatang';
    return 'Surat Pengantar';
  }

  function renderNotifications(notifs) {
    const filteredNotifs = notifs.filter(n => !deletedIds.includes(String(n.id)));

    if (filteredNotifs.length === 0) {
      $notifDot.addClass('d-none');
      $notifList.html(`
        <li class="px-3 pt-2 pb-0"><h5 class="text-center">Notifikasi</h5></li>
        <li><hr class="dropdown-divider mb-1 mt-1"></li>
        <li><a class="dropdown-item text-center text-muted small" href="#">Tidak ada notifikasi baru</a></li>
      `);
      return;
    }

    $notifDot.removeClass('d-none');

    let html = `
      <li class="px-3 pt-2 pb-0"><h5 class="text-center">Notifikasi</h5></li>
      <li><hr class="dropdown-divider mb-1 mt-1"></li>
    `;

    filteredNotifs.forEach((item) => {
      const jenisSuratLabel = getJenisSuratLabel(item);

      html += `
        <li class="px-3 pb-2">
          <div class="card shadow-sm border-0">
            <div class="card-body py-2 px-3 d-flex justify-content-between align-items-start">
              <div style="max-width: 250px;">
                <div class="fw-bold">${jenisSuratLabel}</div>
                <div class="small text-muted">No. ${item.no_surat}</div>
                <div class="small mt-1">Status: <strong>${item.status_proses}</strong></div>
              </div>
              <button class="btn btn-sm btn-danger btn-delete-notif ms-2" data-id="${item.id}" title="Hapus Notifikasi">
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
          </div>
        </li>
      `;
    });

    $notifList.html(html);

    $('.btn-delete-notif').off('click').on('click', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const id = String($(this).data('id'));

      $.ajax({
        url: '<?= base_url('notifikasi/delete') ?>',
        method: 'POST',
        data: { id: id },
        success: function (res) {
          if (res.status === 'success') {
            if (!deletedIds.includes(id)) {
              deletedIds.push(id);
              localStorage.setItem('deletedNotifIds', JSON.stringify(deletedIds));
            }
            renderNotifications(notifications);
          }
        }
      });
    });

  }

  renderNotifications(notifications);
  channel.bind('notif-deleted', function (data) {
    const id = String(data.notif_id);

    if (!deletedIds.includes(id)) {
      deletedIds.push(id);
      localStorage.setItem('deletedNotifIds', JSON.stringify(deletedIds));
      renderNotifications(notifications);
    }
  });

  channel.bind('status-update', function (response) {
    const items = response.data;

    items.forEach(newItem => {
      const idStr = String(newItem.id);
      if (deletedIds.includes(idStr)) return;

      const index = notifications.findIndex(n => String(n.id) === idStr);
      if (index !== -1) {
        notifications[index] = { ...notifications[index], ...newItem };
      } else {
        notifications.push(newItem);
      }
    });

    localStorage.setItem('notifications', JSON.stringify(notifications));
    renderNotifications(notifications);
  });
</script>

<script>
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('<?= base_url('service-worker.js') ?>')
      .then(function(registration) {
        console.log('Service Worker registered with scope:', registration.scope);
      })
      .catch(function(error) {
        console.error('Service Worker registration failed:', error);
      });
  }
</script>

</body>
</html>
