<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title fw-semibold mb-4">Data Penanggung Jawab</h5>
                <button type="button" class="btn btn-primary" onclick="window.location.href='<?= base_url('dashboard/pj/create') ?>'">
                    Tambah Penanggung Jawab
                </button>
            </div>

            <?php if ($this->session->flashdata('success')): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: '<?= $this->session->flashdata('success') ?>',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    });
                </script>
            <?php endif; ?>

            <div class="table-responsive">
                <table id="pjTable" class="table table-striped table-bordered text-nowrap align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%;">No</th>
                            <th class="text-center" style="width: 15%;">Username</th>
                            <th class="text-center" style="width: 15%;">Nama</th>
                            <th class="text-center" style="width: 15%;">No Hp</th>
                            <th class="text-center" style="width: 20%;">Alamat</th>
                            <th class="text-center" style="width: 15%;">Wilayah</th>
                            <th class="text-center" style="width: 20%;">Status</th>
                            <th class="text-center" style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pj as $i => $p):?>
                            <tr>
                                <td class="text-center"><?= $i + 1 ?></td>
                                <td class="text-center"><?= $p->username ?></td>
                                <td class="text-center"><?= $p->nama_pj ?></td>
                                <td class="text-center"><?= $p->no_hp ?></td>
                                <td class="text-center"><?= $p->alamat_detail ?>, No.<?= $p->alamat_no ?></td>
                                <td class="text-center"><?= $p->wilayah_nama ?></td>
                                <td class="text-center"><?= $p->status_rumah ?></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-start gap-1">
                                        <button type="button" class="btn btn-info btn-sm" title="Lihat Detail" onclick="window.location.href='<?= base_url('dashboard/pj/detail/' . $p->uuid) ?>'">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm" title="Edit" onclick="window.location.href='<?= base_url('dashboard/pj/edit/' . $p->uuid) ?>'">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button title="Hapus" onclick="hapusPJ('<?= $p->uuid ?>')" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
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
    $('#pjTable').DataTable({
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

    function hapusPJ(uuid) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('dashboard/pj/delete/') ?>' + uuid;
            }
        });
    }
</script>

<?php HelperJS::end('scripts'); ?>
<?php $this->load->view('partials/footer'); ?>
