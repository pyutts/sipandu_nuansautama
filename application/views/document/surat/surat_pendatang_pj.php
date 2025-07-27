<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title fw-semibold mb-0">Surat Pengantar Pendatang</h5>
                <button type="button" class="btn btn-danger" onclick="window.location.href='<?= base_url('dashboard/surat/view') ?>'">Kembali</button>
            </div>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="alert alert-warning border-0 shadow-sm rounded-3" role="alert">
                        <h5 class="mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Perhatian!</h5>
                        <ul class="mb-0 ps-3" style="list-style-type: disc;">
                            <li>Silahkan Memilih Data Pendatang yang sudah terverifikasi oleh <strong>Kepala Lingkungan</strong></li>
                            <li>Gunakan format kertas <strong>A4</strong> saat mencetak dokumen</li>
                            <li>Pastikan skala cetak dokumen disetel ke <strong>100%</strong> agar format tidak terpotong</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form id="suratForm">
                        <div class="form-group mb-4">
                            <label for="penghuni" class="form-label">Pilih Data Pendatang</label>
                            <select class="form-select" id="penghuni" name="penghuni">
                                <option value="">-- Pilih Data Pendatang --</option>
                                <?php if (!empty($penghuni)): ?>
                                    <?php foreach ($penghuni as $p): ?>
                                        <option value="<?= $p->uuid ?>" data-nama="<?= $p->nama_lengkap ?>">
                                            <?= $p->nama_lengkap ?> (NIK: <?= $p->nik ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">Tidak ada data pendatang</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="jenisSurat" class="form-label">Pilih Keperluan Surat Pengantar</label>
                            <select class="form-select" id="jenisSurat">
                                <option value="">-- Pilih Keperluan Surat --</option>
                                <option value="Mengurus KK">Mengurus KK</option>
                                <option value="Mengurus KTP">Mengurus KTP</option>
                                <option value="Mengurus Surat Domisili">Mengurus Surat Domisili</option>
                                <option value="Mengurus Surat Belum Menikah">Mengurus Surat Belum Menikah</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                            <input type="text" id="inputLainnya" class="form-control mt-2" placeholder="Tulis keperluan lainnya" style="display:none;">
                            <input type="hidden" name="keperluan" id="keperluanFinal">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary" id="btnAjukanSurat">
                                <i class="fa-solid fa-paper-plane me-2"></i>
                                Ajukan Surat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Surat Pengantar -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title fw-semibold">Surat Menunggu Verifikasi</h5>
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-nowrap align-middle" id="tableSuratMenunggu">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">No Surat</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Keperluan</th>
                            <th class="text-center">Tanggal Pengajuan</th>
                            <th class="text-center">Status Verifikasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($surat_menunggu) && count($surat_menunggu)): ?>
                            <?php foreach ($surat_menunggu as $i => $s): ?>
                                <tr>
                                    <td class="text-center"><?= $i + 1 ?></td>
                                    <td class="text-center"><?= $s->no_surat ?></td>
                                    <td class="text-center"><?= $s->nama_penghuni ?></td>
                                    <td class="text-center"><?= $s->keperluan ?></td>
                                    <td class="text-center"><?= date('d/m/Y', strtotime($s->tanggal_pengajuan)) ?></td>
                                    <td class="text-center">
                                        <?php if ($s->status_proses === 'Diproses'): ?>
                                            <span class="badge bg-warning">Diproses</span>
                                        <?php elseif ($s->status_proses === 'Diterima'): ?>
                                            <span class="badge bg-success">Diterima</span>
                                        <?php elseif ($s->status_proses === 'Ditolak'): ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <button
                                            type="button"
                                            class="btn btn-primary btn-sm<?php if ($s->status_proses !== 'Diterima') echo ' disabled'; ?>"
                                            title="Cetak Surat"
                                            onclick="window.open('<?= base_url('dashboard/surat/cetak_pengantar/' . $s->uuid) ?>', '_blank')"
                                            tabindex="<?= $s->status_proses === 'Diterima' ? '0' : '-1' ?>"
                                            aria-disabled="<?= $s->status_proses !== 'Diterima' ? 'true' : 'false' ?>"
                                            <?php if ($s->status_proses !== 'Diterima') echo 'style=\"pointer-events:none;opacity:0.5;\"'; ?>>
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm btn-hapus-surat" data-uuid="<?= $s->uuid ?>" title="Hapus">
                                             <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabel Surat Terverifikasi (Diterima/Ditolak) -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title fw-semibold">Surat Terverifikasi</h5>
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-nowrap align-middle" id="tableSuratTerverifikasi">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">No Surat</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Keperluan</th>
                            <th class="text-center">Tanggal Pengajuan</th>
                            <th class="text-center">Status Verifikasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($surat_terverifikasi) && count($surat_terverifikasi)): ?>
                            <?php foreach ($surat_terverifikasi as $i => $s): ?>
                                <tr>
                                    <td class="text-center"><?= $i + 1 ?></td>
                                    <td class="text-center"><?= $s->no_surat ?></td>
                                    <td class="text-center"><?= $s->nama_penghuni ?></td>
                                    <td class="text-center"><?= $s->keperluan ?></td>
                                    <td class="text-center"><?= date('d/m/Y', strtotime($s->tanggal_pengajuan)) ?></td>
                                    <td class="text-center">
                                        <?php if ($s->status_proses === 'Diterima'): ?>
                                            <span class="badge bg-success">Diterima</span>
                                        <?php elseif ($s->status_proses === 'Ditolak'): ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('dashboard/surat/print/pendatang/' . $s->uuid) ?>"
                                                class="btn btn-primary btn-sm" title="Cetak Surat" target="_blank">
                                                <i class="fas fa-print"></i>
                                        </a>
                                        <button class="btn btn-danger btn-sm btn-hapus-surat" data-uuid="<?= $s->uuid ?>" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('partials/watermark'); ?>

<?php HelperJS::start('scripts'); ?>
<script>
    const select = document.getElementById('jenisSurat');
    const inputLainnya = document.getElementById('inputLainnya');
    const inputHidden = document.getElementById('keperluanFinal');

    select.addEventListener('change', function () {
        if (this.value === 'lainnya') {
            inputLainnya.style.display = 'block';
            inputHidden.value = inputLainnya.value;
        } else {
            inputLainnya.style.display = 'none';
            inputHidden.value = this.value;
        }
    });

    inputLainnya.addEventListener('input', function () {
        inputHidden.value = this.value;
    });

      $(document).ready(function () {
        const table = $('#tableSuratMenunggu').DataTable({
            responsive: true,
            paging: true,
            ordering: true,
            info: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Tidak ada data surat yang tersedia",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                }
            }
        });

        $('#tableSuratTerverifikasi').DataTable({
            responsive: true,
            paging: true,
            ordering: true,
            info: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Tidak ada data surat yang tersedia",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('jenisSurat');
        const inputLainnya = document.getElementById('inputLainnya');
        const inputHidden = document.getElementById('keperluanFinal');

        if(select) { 
            select.addEventListener('change', function() {
                if (this.value === 'lainnya') {
                    inputLainnya.style.display = 'block';
                    inputLainnya.value = ''; 
                    inputHidden.value = ''; 
                } else {
                    inputLainnya.style.display = 'none';
                    inputHidden.value = this.value;
                }
            });
        }

        if(inputLainnya) {
            inputLainnya.addEventListener('input', function() {
                if (select.value === 'lainnya') {
                    inputHidden.value = this.value;
                }
            });
        }

        document.querySelectorAll('.btn-hapus-surat').forEach(btn => {
            btn.addEventListener('click', function() {
                const uuid = this.getAttribute('data-uuid'); 
                
                Swal.fire({
                    title: 'Anda Yakin?',
                    text: "Data surat yang dihapus tidak akan dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus Surat',
                    cancelButtonText: 'Batal'

                }).then((result) => {
                    if (result.isConfirmed) {
                        const csrfName = document.querySelector('meta[name="csrf-token-name"]')?.content;
                        const csrfHash = document.querySelector('meta[name="csrf-token-hash"]')?.content;

                        const bodyParams = new URLSearchParams();
                        bodyParams.append('uuid', uuid); 
                        if(csrfName && csrfHash) {
                            bodyParams.append(csrfName, csrfHash);
                        }

                        fetch('<?= base_url('dashboard/surat/delete') ?>', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: bodyParams
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                Swal.fire('Dihapus!', 'Surat telah berhasil dihapus.', 'success')
                                .then(() => location.reload());
                            } else {
                                Swal.fire('Gagal', res.error || 'Gagal menghapus surat.', 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error', 'Terjadi kesalahan jaringan.', 'error');
                        });
                    }
                });
            });
        });

        const btnAjukanSurat = document.getElementById('btnAjukanSurat');
        if (btnAjukanSurat) {
            btnAjukanSurat.addEventListener('click', function() {
                const penghuniValue = document.getElementById('penghuni').value;
                const keperluanValue = document.getElementById('keperluanFinal').value; 

                if (!penghuniValue || !keperluanValue) {
                    Swal.fire('Peringatan', 'Pilih data pendatang dan isi keperluannya!', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Ajukan Surat?',
                    text: 'Yakin ingin mengajukan surat ini ke admin?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Ajukan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        btnAjukanSurat.disabled = true;
                        fetch('<?= base_url('dashboard/surat/ajukan_surat_pendatang_pj') ?>', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: new URLSearchParams({
                                'penghuni': penghuniValue,
                                'keperluan': keperluanValue 
                            })
                        })
                        .then(async res => {
                            if (!res.ok) {
                                const errorData = await res.json().catch(() => null);
                                const errorMessage = errorData ? errorData.error : 'Terjadi kesalahan pada server.';
                                Swal.fire('Gagal', errorMessage, 'error');
                                throw new Error(errorMessage);
                            }
                            return res.json();
                        })
                        .then(res => {
                            if (res && res.success) {
                                Swal.fire('Berhasil', 'Pengajuan surat berhasil dikirim ke admin.', 'success').then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Gagal', (res && res.error) || 'Gagal mengajukan surat.', 'error');
                            }
                        })
                        .catch((err) => {
                            console.error('AJAX error:', err);
                        })
                        .finally(() => {
                            btnAjukanSurat.disabled = false;
                        });
                    }
                });
            });
        }
    });
</script>
<?php HelperJS::end('scripts'); ?>

<?php $this->load->view('partials/footer'); ?>
