<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title fw-semibold mb-4">Data Wilayah</h5>
                <button class="btn btn-primary" onclick="window.location.href='<?= base_url('dashboard/wilayah/create') ?>'">Tambah Wilayah</button>
            </div>

            <?php if ($this->session->flashdata('success')): ?>
                <script>
                    Swal.fire("Sukses", "<?= $this->session->flashdata('success') ?>", "success");
                </script>
            <?php endif; ?>

            <table id="myTable" class="table table-striped table-bordered text-nowrap align-middle">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Wilayah</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($wilayah as $i => $w): ?>
                        <tr>
                            <td class="text-center"><?= $i + 1 ?></td>
                            <td class="text-center"><?= $w->wilayah ?></td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm" title="Edit" onclick="window.location.href='<?= base_url('dashboard/wilayah/edit/' . $w->uuid) ?>'">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button title="Hapus" onclick="hapusWilayah('<?= $w->uuid ?>')" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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

    function hapusWilayah(id) {
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
                window.location.href = '<?= base_url('dashboard/wilayah/delete/') ?>' + id;
            }
        });
    }
</script>

<?php HelperJS::end('scripts'); ?>

<?php $this->load->view('partials/footer'); ?>
