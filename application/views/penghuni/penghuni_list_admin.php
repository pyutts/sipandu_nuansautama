<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<style>
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1.2rem;
    }
    .table-responsive {
        padding-bottom: 1rem;
    }
    #myTable {
        margin-bottom: 0;
    }
    #myTable td, #myTable th {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    .table-responsive::-webkit-scrollbar {
        height: 10px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #dee2e6;
        border-radius: 8px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: transparent;
    }

    @media (max-width: 767.98px) {
        .d-flex.flex-column.flex-md-row.justify-content-between.align-items-md-center.mb-4.gap-2.gap-md-3 {
            flex-direction: column !important;
            align-items: stretch !important;
        }
        .d-flex.flex-wrap.align-items-center.gap-2.gap-md-3.mt-2.mt-md-0 {
            flex-direction: column !important;
            align-items: stretch !important;
        }
    }
    .d-flex.flex-wrap.align-items-center.gap-2.gap-md-3.mt-2.mt-md-0 > * {
        min-width: 140px;
    }
</style>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="alert alert-warning border-0 shadow-sm rounded-3" role="alert">
                <h5 class="mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>Perhatian! Panduan Penggunaan
                </h5>
                <ul class="mb-0 ps-3" style="list-style-type: disc;">
                    <li>Klik tombol <strong>Tambah Pendatang</strong> untuk menambahkan data pendatang baru.</li>
                    <li>Gunakan tombol <strong>Lihat Detail</strong> <i class="fas fa-eye text-info"></i> untuk melihat informasi lengkap pendatang.</li>
                    <li>Jika data sudah sesuai, klik <strong>Terima</strong> <i class="fas fa-check-circle text-success"></i> untuk memverifikasi pendatang.</li>
                    <li>Jika data tidak sesuai, klik <strong>Tolak</strong> <i class="fas fa-times-circle text-danger"></i> dan isikan alasan penolakan.</li>
                    <li>Gunakan tombol <strong>Hapus</strong> <i class="fas fa-trash-alt text-danger"></i> untuk menghapus data pendatang yang tidak diperlukan.</li>
                    <li>Pastikan hanya memilih data yang sudah <strong>terverifikasi Kepala Lingkungan</strong>.</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title fw-semibold mb-4">Menunggu Verifikasi</h5>
                <button type="button" class="btn btn-primary" onclick="window.location.href='<?= base_url('dashboard/penghuni/create/admin') ?>'">
                Tambah Pendatang
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-nowrap align-middle" id="myTable1">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">NIK</th>
                            <th class="text-center">Penanggung Jawab</th>
                            <th class="text-center">Status Verifikasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($penghuni_diproses as $i => $p): ?>
                            <tr>
                                <td class="text-center"><?= $i + 1 ?></td>
                                <td class="text-center"><?= $p->nama_lengkap ?></td>
                                <td class="text-center"><?= $p->nik ?></td>
                                <td class="text-center"><?= $p->pj_nama ?></td>
                                <td class="text-center"><span class="badge bg-warning"><?= $p->status_verifikasi ?></span></td>
                                <td class="text-center">
                                    <a href="<?= base_url('dashboard/penghuni/details/admin/' . $p->uuid) ?>"
                                        class="btn btn-info btn-sm"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button onclick="verifikasiAktif(<?= $p->id ?>)"
                                        class="btn btn-success btn-sm"
                                        title="Verifikasi & Aktifkan">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                    <button onclick="tolak(<?= $p->id ?>)"
                                        class="btn btn-danger btn-sm"
                                        title="Tolak">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                    <button onclick="confirmDelete(<?= $p->id ?>)"
                                        class="btn btn-danger btn-sm"
                                        title="Hapus">
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

    <div class="card">
        <div class="card-body">
            <div class="table-responsive mt-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2 gap-md-3">
                    <h5 class="card-title fw-semibold mb-0">Data Terverifikasi</h5>
                    <div class="d-flex flex-wrap align-items-center gap-2 gap-md-3 mt-2 mt-md-0">
                        <select class="form-select w-auto" name="penanggung_jawab_filter" id="penanggungJawabFilter">
                            <option value="">Pilih Penanggung Jawab</option>
                            <?php foreach ($penanggung_jawab as $pj): ?>
                                <option value="<?= $pj->id ?>"><?= $pj->nama_pj ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select class="form-select w-auto" name="status_filter" id="statusFilter">
                            <option value="">Pilih Status</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                        <button id="filterButton" class="btn btn-primary">Cari</button>
                    </div>
                </div>
                <table class="table table-striped table-bordered text-nowrap align-middle" id="tableTerverifikasi">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">NIK</th>
                            <th class="text-center">Penanggung Jawab</th>
                            <th class="text-center">Tanggal Masuk</th>
                            <th class="text-center">Tanggal Keluar</th>
                            <th class="text-center">Status Verifikasi</th>
                            <th class="text-center">Status Pendatang</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if (isset($penghuni_terverifikasi)) :
                            usort($penghuni_terverifikasi, function ($a, $b) {
                                if ($a->status_penghuni == $b->status_penghuni) return 0;
                                return ($a->status_penghuni == 'Aktif') ? -1 : 1;
                            });

                            foreach ($penghuni_terverifikasi as $p) :
                        ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="text-center"><?= $p->nama_lengkap ?></td>
                                    <td class="text-center"><?= $p->nik ?></td>
                                    <td class="text-center"><?= $p->nama_pj ?></td>
                                    <td class="text-center"><?= date('d/m/Y', strtotime($p->tanggal_masuk)) ?></td>
                                    <td class="text-center"><?= $p->tanggal_keluar ? date('d/m/Y', strtotime($p->tanggal_keluar)) : '-' ?></td>
                                    <td class="text-center">
                                        <?php if ($p->status_verifikasi === 'Diterima'): ?>
                                            <span class="badge bg-success">Diterima</span>
                                        <?php elseif ($p->status_verifikasi === 'Ditolak'): ?>
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
                                    <td class="text-center">
                                        <div class="d-flex gap-2">
                                            <a href="<?= base_url('dashboard/penghuni/details/admin/' . $p->uuid) ?>"
                                            class="btn btn-info btn-sm"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                            </a>

                                            <?php if ($p->status_verifikasi === 'Diterima' && $p->status_penghuni === 'Aktif'): ?>                                                
                                                <a href="javascript:void(0);"
                                                class="btn btn-danger btn-sm"
                                                title="Non-aktifkan"
                                                onclick="nonAktifkan('<?= $p->uuid ?>')">
                                                <i class="fas fa-user-times"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($p->status_penghuni === 'Tidak Aktif'): ?>
                                                <?php if ($p->status_verifikasi === 'Diterima'): ?>
                                                    <a href="javascript:void(0);"
                                                    class="btn btn-success btn-sm"
                                                    title="Aktifkan Kembali"
                                                    onclick="aktifkan('<?= $p->uuid ?>')">
                                                    <i class="fas fa-user-check"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <a href="<?= base_url('dashboard/penghuni/edit/admin/' . $p->uuid) ?>"
                                                class="btn btn-warning btn-sm"
                                                title="Edit Data">
                                                <i class="fas fa-edit"></i>
                                                </a>

                                                <button onclick="confirmDelete(<?= $p->id ?>)"
                                                        class="btn btn-danger btn-sm"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                </tr>
                        <?php
                            endforeach;
                            endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('partials/watermark'); ?>

<?php HelperJS::start('scripts'); ?>
<script>
    function nonAktifkan(uuid) {
        Swal.fire({
            title: 'Non-aktifkan data Pendatang?',
            text: "Anda yakin ingin menonaktifkan data pendatang ini?",
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
                            text: 'Data berhasil dinonaktifkan.',
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

    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('dashboard/penghuni/delete/') ?>' + id,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Data penghuni berhasil dihapus',
                            icon: 'success'
                        }).then(() => {
                            window.location.href = '<?= base_url('dashboard/penghuni/view') ?>';
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        window.location.href = '<?= base_url('dashboard/penghuni/view') ?>';
                    }
                });
            }
        });
    }

    function tolak(id) {
        Swal.fire({
            title: 'Tolak Penghuni',
            input: 'textarea',
            inputLabel: 'Alasan Penolakan',
            inputPlaceholder: 'Tulis alasan penolakan...',
            showCancelButton: true,
            confirmButtonText: 'Tolak',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            inputValidator: (value) => {
                if (!value) {
                    return 'Alasan penolakan harus diisi!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('dashboard/penghuni/verifikasi/') ?>' + id + '/Ditolak',
                    type: 'POST',
                    data: {
                        alasan: result.value,
                        status_verifikasi: 'Ditolak',
                        status_penghuni: 'Tidak Aktif'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data penghuni berhasil ditolak',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error!', response.message || 'Gagal menolak data', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Terjadi kesalahan sistem', 'error');
                    }
                });
            }
        });
    }

    function verifikasiAktif(id) {
        Swal.fire({
            title: 'Verifikasi & Aktifkan',
            text: "Anda akan memverifikasi dan mengaktifkan penghuni ini. Lanjutkan?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Verifikasi!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('dashboard/penghuni/verifikasi/') ?>' + id + '/Diterima',
                    type: 'POST',
                    data: {
                        status_verifikasi: 'Diterima',
                        status_penghuni: 'Aktif'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data penghuni berhasil diverifikasi dan diaktifkan',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error!', response.message || 'Gagal memverifikasi data', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Terjadi kesalahan sistem', 'error');
                    }
                });
            }
        });
    }
    $(document).ready(function() {
        let table = $('#tableTerverifikasi').DataTable({
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
        $('#myTable1').DataTable({
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

        function formatDateToDDMMYYYY(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); 
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }
        

        $('#filterButton').on('click', function() {
            const pj_id = $('#penanggungJawabFilter').val();
            const status = $('#statusFilter').val();
            if ($.fn.DataTable.isDataTable('#tableTerverifikasi')) {
                $('#tableTerverifikasi').DataTable().destroy();
            }

            $.ajax({
                url: '<?= base_url('dashboard/penghuni/filterTerverifikasiByPJ') ?>',
                type: 'GET',
                data: {
                    pj_id: pj_id,
                    status: status
                },
                success: function(response) {
                    if (response && Array.isArray(response)) {
                        let html = '';
                        response.forEach((p, index) => {
                            const formattedTanggalMasuk = formatDateToDDMMYYYY(p.tanggal_masuk);
                            const formattedTanggalKeluar = formatDateToDDMMYYYY(p.tanggal_keluar);

                            html += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${p.nama_lengkap}</td>
                                    <td>${p.nik}</td>
                                    <td>${p.nama_pj}</td>
                                    <td>${formattedTanggalMasuk}</td>
                                    <td>${formattedTanggalKeluar}</td>
                                    <td>
                                        <span class="badge ${p.status_verifikasi === 'Diterima' ? 'bg-success' : 'bg-danger'}">
                                            ${p.status_verifikasi}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge ${p.status_penghuni === 'Aktif' ? 'bg-success' : 'bg-danger'}">
                                            ${p.status_penghuni}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="<?= base_url('dashboard/penghuni/details/admin/') ?>${p.uuid}" 
                                               class="btn btn-info btn-sm" 
                                               title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= base_url('dashboard/penghuni/edit/admin/') ?>${p.uuid}" 
                                               class="btn btn-warning btn-sm" 
                                               title="Edit Data">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            ${p.status_verifikasi === 'Diterima' && p.status_penghuni === 'Aktif' ? `
                                                <a href="javascript:void(0);" 
                                                   class="btn btn-danger btn-sm" 
                                                   title="Non-aktifkan"
                                                   onclick="nonAktifkan('${p.uuid}')">
                                                    <i class="fas fa-user-times"></i>
                                                </a>
                                            ` : ''}
                                        </div>
                                    </td>
                                </tr>
                            `;
                        });
                        $('#tableTerverifikasi tbody').html(html);

                        $('#tableTerverifikasi').DataTable({
                            "order": [
                                [0, "asc"]
                            ],
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

                        if (response.length === 0) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Tidak Ada Data',
                                text: 'Tidak ada data yang sesuai dengan filter yang dipilih',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Gagal memuat data', 'error');
                }
            });
        });

        <?php if ($this->session->flashdata('success')): ?>
            Swal.fire({
                title: 'Berhasil!',
                text: '<?= $this->session->flashdata('success') ?>',
                icon: 'success',
                position: 'center',
                showConfirmButton: false,
                timer: 3000
            });
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            Swal.fire({
                title: 'Error!',
                text: '<?= $this->session->flashdata('error') ?>',
                icon: 'error',
                position: 'center',
                showConfirmButton: false,
                timer: 3000
            });
        <?php endif; ?>
    });
</script>

<?php HelperJS::end('scripts'); ?>
<?php $this->load->view('partials/footer'); ?>
