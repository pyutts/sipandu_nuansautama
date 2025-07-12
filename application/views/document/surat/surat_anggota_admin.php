<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">

    <!-- Tabel Perhatian dan Tambah Keperluan -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title fw-semibold mb-0">Surat Pengantar Anggota Keluarga</h5>
                <button type="button" class="btn btn-danger" onclick="window.location.href='<?= base_url('dashboard/surat/view') ?>'">Kembali</button>
            </div>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>
            <div class="col-md-12">
                <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-0" role="alert">
                    <h5 class="mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Perhatian!</h5>
                    <ul class="mb-0 ps-3" style="list-style-type: disc;">
                        <li>Anda bisa menambahkan jenis keperluan dari surat pengantar</li>
                        <li>Anda bisa mencetak langsung Surat Pengantar di form Cetak Surat Pengantar Pendatang</li>
                        <li>Pastikan Anda memverifikasi Surat Pengantar yang di kirimkan oleh Penanggung Jawab</li>
                        <li>Gunakan Format kertas <strong>A4</strong> saat mencetak dokumen</li>
                        <li>Pastikan skala cetak dokumen disetel ke <strong>100%</strong> agar format tidak terpotong</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Cetak Langsung oleh Admin -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Cetak Surat Pengantar Anggota Keluarga</h5>
            <form id="formCetakLangsung">
                <div>
                    <div class="mb-3">
                        <label for="pj" class="form-label">Pilih Penanggung Jawab</label>
                        <select class="form-select" id="pjCetak" name="pjCetak">
                            <option value="">-- Pilih Penanggung Jawab --</option>
                            <?php if (!empty($pj)): ?>
                                <?php foreach ($pj as $p): ?>
                                    <option value="<?= $p->uuid ?>"><?= $p->nama_pj ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="anggota" class="form-label">Pilih Anggota Keluarga</label>
                        <select class="form-select" id="anggotaCetak" name="anggotaCetak" disabled>
                            <option value="">-- Pilih Anggota Keluarga --</option>
                            <?php foreach ($anggota as $a): ?>
                                <option value="<?= $a->uuid ?>"><?= $a->nama ?> (NIK: <?= $a->nik_anggota ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
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
                        <button type="button" class="btn btn-primary" id="btnCetakLangsung">
                            <i class="fas fa-print me-2"></i>Cetak Surat Langsung
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Surat Menunggu Verifikasi -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold">Surat Menunggu Verifikasi</h5>
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-nowrap align-middle" id="tableSuratMenunggu">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">No Surat</th>
                            <th class="text-center">Nama Anggota</th>
                            <th class="text-center">Nama Kepala Keluarga</th>
                            <th class="text-center">Keperluan</th>
                            <th class="text-center">Tanggal Pengajuan</th>
                            <th class="text-center">Status Verifikasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        <?php if (isset($surat_menunggu) && count($surat_menunggu)): ?>
                            <?php foreach ($surat_menunggu as $i => $s): ?>
                                <tr>
                                    <td class="text-center"><?= $i + 1 ?></td>
                                    <td class="text-center"><?= $s->no_surat ?></td>
                                    
                                    <td class="text-center">
                                        <?php 
                                            echo htmlspecialchars($s->nama_anggota ?? $s->nama_kepala_keluarga); 
                                        ?>
                                        <?php if (is_null($s->anggota_keluarga_id)): ?>
                                            <br>
                                            <span class="badge bg-info">Diajukan Pribadi</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <?php
                                            echo is_null($s->anggota_keluarga_id) ? '-' : htmlspecialchars($s->nama_kepala_keluarga);
                                        ?>
                                    </td>

                                    <td class="text-center"><?= htmlspecialchars($s->keperluan) ?></td>
                                    <td class="text-center"><?= date('d/m/Y', strtotime($s->tanggal_pengajuan)) ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-warning">Diproses</span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-success btn-sm btn-terima-surat" data-id="<?= $s->id ?>" title="Terima"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-danger btn-sm btn-tolak-surat" data-id="<?= $s->id ?>" title="Tolak"><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabel Surat Terverifikasi -->
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
                            <th class="text-center">Nama Anggota</th>
                            <th class="text-center">Nama Kepala Keluarga</th>
                            <th class="text-center">Keperluan</th>
                            <th class="text-center">Tanggal Verifikasi</th>
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
                                    
                                    <td class="text-center">
                                        <?php 
                                            echo htmlspecialchars($s->nama_anggota ?? $s->nama_kepala_keluarga); 
                                        ?>
                                        <?php if (is_null($s->anggota_keluarga_id)): ?>
                                            <br>
                                            <span class="badge bg-info">Diajukan Pribadi</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <?php
                                            echo is_null($s->anggota_keluarga_id) ? '-' : htmlspecialchars($s->nama_kepala_keluarga);
                                        ?>
                                    </td>

                                    <td class="text-center"><?= htmlspecialchars($s->keperluan) ?></td>
                                    <td class="text-center"><?= isset($s->tanggal_verifikasi) ? date('d/m/Y', strtotime($s->tanggal_verifikasi)) : '-' ?></td>
                                    <td class="text-center">
                                        <?php if ($s->status_proses === 'Diterima'): ?>
                                            <span class="badge bg-success">Diterima</span>
                                        <?php elseif ($s->status_proses === 'Ditolak'): ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($s->status_proses === 'Diterima'): ?>
                                            
                                            <?php 
                                                $print_url = is_null($s->anggota_keluarga_id)
                                                    ? base_url('dashboard/surat/print/pj/' . $s->uuid)
                                                    : base_url('dashboard/surat/print/anggota/' . $s->uuid);
                                            ?>
                                            <a href="<?= $print_url ?>" class="btn btn-primary btn-sm" title="Cetak Surat" target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <button class="btn btn-danger btn-sm btn-hapus-surat" data-uuid="<?= $s->uuid ?>" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                        <?php else: ?>
                                            <button class="btn btn-secondary btn-sm" title="Tidak dapat mencetak surat" disabled>
                                                <i class="fas fa-print"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm btn-hapus-surat" data-uuid="<?= $s->uuid ?>" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
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
    
    document.addEventListener('DOMContentLoaded', function() {
            const pjCetak = document.getElementById('pjCetak');
            const anggotaCetak = document.getElementById('anggotaCetak');
            pjCetak.addEventListener('change', function() {
                anggotaCetak.innerHTML = '<option value="">-- Cetak untuk Kepala Keluarga --</option>'; 
                anggotaCetak.disabled = true;
                const pjId = this.value;
                if (!pjId) {
                    anggotaCetak.innerHTML = '<option value="">-- Pilih Anggota Keluarga --</option>';
                    return;
                }
                
                fetch('<?= base_url('dashboard/surat/get_anggota_by_pj/') ?>' + pjId)
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            data.forEach(a => {
                                const opt = document.createElement('option');
                                opt.value = a.uuid ? a.uuid : a.id;
                                opt.textContent = `${a.nama} (NIK: ${a.nik_anggota})`;
                                anggotaCetak.appendChild(opt);
                            });
                        }
                        anggotaCetak.disabled = false; 
                    });
            });

        document.getElementById('btnCetakLangsung').addEventListener('click', function () {
            const pjUuid = document.getElementById('pjCetak').value;
            const anggotaUuid = document.getElementById('anggotaCetak').value;
            const keperluanValue = document.getElementById('keperluanFinal').value;

            if (!pjUuid || !keperluanValue) {
                Swal.fire('Peringatan', 'Pilih Penanggung Jawab dan Keperluan surat!', 'warning');
                return;
            }

            let displayName, url, formAction, isUntukPJ;
            if (anggotaUuid) {
                displayName = document.getElementById('anggotaCetak').selectedOptions[0].textContent;
                formAction = '<?= base_url('dashboard/surat/cetak_langsung_anggota') ?>';
                isUntukPJ = false;
            } else {
                displayName = document.getElementById('pjCetak').selectedOptions[0].textContent;
                formAction = '<?= base_url('dashboard/surat/cetak_langsung_pj') ?>';
                isUntukPJ = true;
            }
            
            Swal.fire({
                title: 'Cetak Surat?',
                text: 'Anda akan mencetak surat pengantar untuk ' + displayName + '.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cetak',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = formAction; 
                    form.target = '_blank';

                    if (isUntukPJ) {
                        const hiddenInputPj = document.createElement('input');
                        hiddenInputPj.type = 'hidden';
                        hiddenInputPj.name = 'pj_uuid';
                        hiddenInputPj.value = pjUuid;
                        form.appendChild(hiddenInputPj);
                    } else {
                        const hiddenInputAnggota = document.createElement('input');
                        hiddenInputAnggota.type = 'hidden';
                        hiddenInputAnggota.name = 'anggota_uuid';
                        hiddenInputAnggota.value = anggotaUuid;
                        form.appendChild(hiddenInputAnggota);
                    }

                    const hiddenInputKeperluan = document.createElement('input');
                    hiddenInputKeperluan.type = 'hidden';
                    hiddenInputKeperluan.name = 'keperluan';
                    hiddenInputKeperluan.value = keperluanValue;
                    form.appendChild(hiddenInputKeperluan);
                    
                    const csrfName = document.querySelector('meta[name="csrf-token-name"]')?.content;
                    const csrfHash = document.querySelector('meta[name="csrf-token-hash"]')?.content;
                    if (csrfName && csrfHash) {
                        const hiddenInputCsrf = document.createElement('input');
                        hiddenInputCsrf.type = 'hidden';
                        hiddenInputCsrf.name = csrfName;
                        hiddenInputCsrf.value = csrfHash;
                        form.appendChild(hiddenInputCsrf);
                    }

                    document.body.appendChild(form);
                    form.submit();
                    document.body.removeChild(form);
                }
            });
        });


        document.querySelectorAll('.btn-terima-surat').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Verifikasi Surat',
                    text: 'Yakin ingin menerima surat ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Terima',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('<?= base_url('dashboard/surat/verifikasi') ?>', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: 'id=' + encodeURIComponent(id) + '&status=Diterima'
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                Swal.fire('Berhasil', 'Surat berhasil diverifikasi', 'success').then(() => location.reload());
                            } else {
                                Swal.fire('Gagal', res.error || 'Gagal memverifikasi surat', 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Gagal', 'Terjadi kesalahan jaringan', 'error');
                        });
                    }
                });
            });
        });

        document.querySelectorAll('.btn-tolak-surat').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Tolak Surat',
                    text: 'Yakin ingin menolak surat ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Tolak',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('<?= base_url('dashboard/surat/verifikasi') ?>', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: 'id=' + encodeURIComponent(id) + '&status=Ditolak'
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                Swal.fire('Ditolak', 'Surat berhasil ditolak', 'success').then(() => location.reload());
                            } else {
                                Swal.fire('Gagal', res.error || 'Gagal menolak surat', 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Gagal', 'Terjadi kesalahan jaringan', 'error');
                        });
                    }
                });
            });
        });
    });

     document.querySelectorAll('.btn-hapus-surat').forEach(btn => {
        btn.addEventListener('click', function() {
            const uuid = this.getAttribute('data-uuid'); 
            
            Swal.fire({
                title: 'Anda Yakin?',
                text: "Data surat yang dihapus tidak akan dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
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
</script>
<?php HelperJS::end('scripts'); ?>

<?php $this->load->view('partials/footer'); ?>
