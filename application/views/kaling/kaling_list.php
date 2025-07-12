<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title fw-semibold mb-4">Data Kepala Lingkungan</h5>
                    <button class="btn btn-primary" onclick="window.location.href='<?= base_url('dashboard/kaling/create') ?>'">Tambah Kaling</button>
                </div>

                <?php if ($this->session->flashdata('success')): ?>
                    <script>
                        Swal.fire("Sukses", "<?= $this->session->flashdata('success') ?>", "success");
                    </script>
                <?php endif; ?>

                <div class="table-responsive">
                    <table id="myTable" class="table table-striped table-bordered align-middle">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Username</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">No Hp</th>
                                <th class="text-center">Alamat</th>
                                <th class="text-center">Wilayah</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($kaling as $i => $row): ?>
                                <tr>
                                    <td class="text-center"><?= $i + 1 ?></td>
                                    <td class="text-center" ><?= $row->username ?></td>
                                    <td class="text-center"><?= $row->nama ?></td>
                                    <td class="text-center"><?= $row->no_hp ?></td>
                                    <td class="text-center"><?= $row->alamat_detail?>, No. <?= $row->alamat_no?></td>
                                    <td class="text-center"><?= $row->wilayah ?></td>
                                    <td class="text-center gap-3">
                                        <button class="btn btn-warning btn-sm me-1" title="Edit" onclick="window.location.href='<?= base_url('dashboard/kaling/edit/' . $row->uuid) ?>'">
                                            <i class="fas fa-edit"></i>

                                            <button title="Hapus" onclick="hapusKaling('<?= $row->uuid ?>')" class="btn btn-danger btn-sm">
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

    function hapusKaling(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            icon: 'warning',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('dashboard/kaling/delete/') ?>' + id;
            }
        });
    }
</script>
<?php HelperJS::end('scripts'); ?>

<?php $this->load->view('partials/footer'); ?>
