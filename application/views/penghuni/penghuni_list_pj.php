<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="mb-4">
        <h2>Selamat Datang di Dashboard, <?= $this->session->userdata('nama'); ?></h2>
    </div>
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="alert alert-warning border-0 shadow-sm rounded-3" role="alert">
                <h5 class="mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>Perhatian! Panduan Penggunaan
                </h5>
                <ul class="mb-0 ps-3" style="list-style-type: disc;">
                    <li>Klik tombol <strong>Tambah Pendatang</strong> <i class="fas fa-plus text-primary"></i> untuk menambahkan data pendatang baru.</li>
                    <li>Gunakan tombol <strong>Lihat Detail</strong> <i class="fas fa-eye text-info"></i> untuk melihat informasi lengkap pendatang.</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center mb-4">
                <h5 class="card-title fw-semibold mb-4">Data Pendatang</h5>
                <div class="ms-auto">
                    <a href="<?= base_url('dashboard/penghuni/create/pj') ?>" class="btn btn-primary">
                    Tambah Pendatang
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-nowrap align-middle" id="myTable">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">NIK</th>
                            <th class="text-center">Tanggal Masuk</th>
                            <th class="text-center">Tanggal Keluar</th>
                            <th class="text-center">Status Verifikasi</th>
                            <th class="text-center">Status Penghuni</th>
                            <th class="text-center">Alasan Penolakan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($penghuni as $i => $p): ?>
                            <tr>
                                <td class="text-center"><?= $i + 1 ?></td>
                                <td class="text-center"><?= $p->nama_lengkap ?></td>
                                <td class="text-center"><?= $p->nik ?></td>
                                <td class="text-center"><?= date('d/m/Y', strtotime($p->tanggal_masuk)) ?></td>
                                <td class="text-center"><?= $p->tanggal_keluar ? date('d/m/Y', strtotime($p->tanggal_keluar)) : '-' ?></td>
                                <td class="text-center">
                                    <?php if ($p->status_verifikasi == 'Diproses'): ?>
                                        <span class="badge bg-warning">Diproses</span>
                                    <?php elseif ($p->status_verifikasi == 'Diterima'): ?>
                                        <span class="badge bg-success">Diterima</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Ditolak</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($p->status_penghuni == 'Aktif'): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Tidak Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= $p->alasan ?? '-' ?></td>
                                <td class="text-center">
                                    <div class="d-flex gap-2">
                                        <a href="<?= base_url('dashboard/penghuni/details/pj/' . $p->uuid) ?>"
                                            class="btn btn-info btn-sm"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($p->status_verifikasi === 'Ditolak'): ?>
                                            <a href="<?= base_url('dashboard/penghuni/edit/pj/' . $p->uuid) ?>"
                                                class="btn btn-warning btn-sm"
                                                title="Edit Data">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($p->status_verifikasi === 'Diterima' && $p->status_penghuni === 'Aktif'): ?>
                                                <a href="javascript:void(0);"
                                                    class="btn btn-danger btn-sm"
                                                    title="Non-aktifkan"
                                                    onclick="nonAktifkan('<?= $p->uuid ?>')">
                                                    <i class="fas fa-user-times"></i>
                                                </a>
                                        <?php elseif ($p->status_verifikasi === 'Diterima' && $p->status_penghuni === 'Tidak Aktif'): ?>
                                                <a href="javascript:void(0);"
                                                    class="btn btn-success btn-sm"
                                                    title="Aktifkan Kembali"
                                                    onclick="aktifkan('<?= $p->uuid ?>')">
                                                    <i class="fas fa-user-check"></i>
                                                </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('partials/watermark'); ?>
<?php HelperJS::start('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            language: {
                "decimal": "",
                "emptyTable": "Tidak ada data yang tersedia pada tabel",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(disaring dari total _MAX_ data)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Tampilkan _MENU_ data",
                "loadingRecords": "Memuat...",
                "processing": "Memproses...",
                "search": "Cari:",
                "zeroRecords": "Tidak ditemukan data yang cocok",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                },
                "aria": {
                    "sortAscending": ": aktifkan untuk mengurutkan kolom secara menaik",
                    "sortDescending": ": aktifkan untuk mengurutkan kolom secara menurun"
                }
            }
        });
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

    function nonAktifkan(uuid) {
        Swal.fire({
            title: 'Non-aktifkan Penghuni?',
            text: "Anda yakin ingin menonaktifkan penghuni ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Non-aktifkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('dashboard/penghuni/nonaktifkan_status/') ?>' + uuid,
                    type: 'POST',
                    success: function(response) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Penghuni berhasil dinonaktifkan.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function() {
                        Swal.fire('Error!', 'Terjadi kesalahan saat menonaktifkan penghuni.', 'error');
                    }
                });
            }
        });
    }

    function aktifkan(uuid) {
        Swal.fire({
            title: 'Aktifkan data Pendatang?',
            text: "Anda yakin ingin menonaktifkan data pendatang ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Aktifkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('dashboard/penghuni/aktifkan_status/') ?>' + uuid,
                    type: 'POST',
                    success: function(response) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Data berhasil diaktifkan.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menonaktifkan penghuni.',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    }
</script>
<?php HelperJS::end('scripts'); ?>
<?php $this->load->view('partials/footer'); ?>
