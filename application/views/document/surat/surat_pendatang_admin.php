<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">

    <div class="card mb-4">
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

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Cetak Surat Pengantar Pendatang</h5>
            <form id="formCetakLangsungPendatang">
                <div class="mb-3">
                    <label for="pj" class="form-label">Pilih Penanggung Jawab</label>
                    <select class="form-select" id="pjCetakPendatang" name="pjCetakPendatang">
                        <option value="">-- Pilih Penanggung Jawab --</option>
                        <?php if (!empty($pj)): ?>
                            <?php foreach ($pj as $p): ?>
                                <option value="<?= $p->id ?>"><?= $p->nama_pj ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="pendatang" class="form-label">Pilih Data Pendatang</label>
                    <select class="form-select" id="penghuniCetak" name="penghuniCetak" disabled>
                        <option value="">-- Pilih Data Pendatang --</option>
                        <?php foreach ($penghuni as $p): ?>
                            <?php if ($p->status_penghuni === 'Aktif'): ?>
                                <option value="<?= $p->uuid ?>"><?= $p->nama_lengkap ?> (NIK: <?= $p->nik ?>)</option>
                            <?php endif; ?>
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
            </form>
        </div>
    </div>


    <div class="card" id="list-surat-menunggu">
        <div class="card-body">
            <h5 class="card-title fw-semibold">Surat Menunggu Verifikasi</h5>
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-nowrap align-middle" id="tableSuratMenunggu">
                    <thead>
                        <tr>
                            <th class="text-center sort" data-sort="no">No</th>
                            <th class="text-center sort" data-sort="no_surat">No Surat</th>
                            <th class="text-center sort" data-sort="nama_pendatang">Nama Pendatang</th>
                            <th class="text-center sort" data-sort="nama_pj">Penanggung Jawab</th>
                            <th class="text-center sort" data-sort="keperluan">Keperluan</th>
                            <th class="text-center">Tanggal Pengajuan</th>
                            <th class="text-center">Status Verifikasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody class="list">
                        <?php if (isset($surat_menunggu) && count($surat_menunggu)): ?>
                            <?php foreach ($surat_menunggu as $i => $s): ?>
                                <tr>
                                    <td class="text-center no"><?= $i + 1 ?></td>
                                    <td class="text-center no_surat"><?= $s->no_surat ?? '-' ?></td>
                                    <td class="text-center nama_pendatang"><?= $s->nama_penghuni ?? '-' ?></td>
                                    <td class="text-center nama_pj"><?= $s->nama_pj ?? '-' ?></td>
                                    <td class="text-center keperluan"><?= $s->keperluan ?? '-' ?></td>
                                    <td class="text-center"><?= isset($s->tanggal_pengajuan) ? date('d/m/Y', strtotime($s->tanggal_pengajuan)) : '-' ?></td>
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
                                        <button class="btn btn-success btn-sm btn-terima-surat" data-id="<?= $s->id ?>" title="Terima"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-danger btn-sm btn-tolak-surat" data-id="<?= $s->id ?>" title="Tolak"><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <ul class="pagination justify-content-center"></ul>
        </div>
    </div>

    <div class="card mt-4" id="list-surat-terverifikasi">
        <div class="card-body">
            <h5 class="card-title fw-semibold">Surat Terverifikasi</h5>
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-nowrap align-middle" id="tableSuratTerverifikasi">
                    <thead>
                        <tr>
                            <th class="text-center sort" data-sort="no">No</th>
                            <th class="text-center sort" data-sort="no_surat">No Surat</th>
                            <th class="text-center sort" data-sort="nama">Nama</th>
                            <th class="text-center sort" data-sort="pj">Penanggung Jawab</th>
                            <th class="text-center sort" data-sort="keperluan">Keperluan</th>
                            <th class="text-center">Tanggal Verifikasi</th>
                            <th class="text-center">Status Verifikasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        <?php if (isset($surat_terverifikasi) && count($surat_terverifikasi)): ?>
                            <?php foreach ($surat_terverifikasi as $i => $s): ?>
                                <tr>
                                    <td class="text-center no"><?= $i + 1 ?></td>
                                    <td class="text-center no_surat"><?= $s->no_surat ?? '-' ?></td>
                                    <td class="text-center nama"><?= $s->nama_penghuni ?? '-' ?></td>
                                    <td class="text-center pj"><?= $s->nama_pj ?? '-' ?></td>
                                    <td class="text-center keperluan"><?= $s->keperluan ?? '-' ?></td>
                                    <td class="text-center"><?= isset($s->tanggal_verifikasi) ? ($s->tanggal_verifikasi ? date('d/m/Y', strtotime($s->tanggal_verifikasi)) : '-') : '-' ?></td>
                                    <td class="text-center">
                                        <?php if (isset($s->status_proses) && $s->status_proses === 'Diterima'): ?>
                                            <span class="badge bg-success">Diterima</span>
                                        <?php elseif (isset($s->status_proses) && $s->status_proses === 'Ditolak'): ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if (isset($s->status_proses) && $s->status_proses === 'Diterima'): ?>
                                            <a href="<?= base_url('dashboard/surat/print/pendatang/' .$s->uuid) ?>"
                                                class="btn btn-primary btn-sm" title="Cetak Surat" target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <button class="btn btn-danger btn-sm btn-hapus-surat" data-uuid="<?= $s->uuid ?>" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php elseif (isset($s->status_proses) && $s->status_proses === 'Ditolak'): ?>
                                            <button class="btn btn-secondary btn-sm" title="Tidak dapat mencetak surat" disabled style="pointer-events: none; opacity: 0.6;">
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
            <ul class="pagination justify-content-center"></ul>
        </div>
    </div>
</div>

<?php $this->load->view('partials/watermark'); ?>

<?php HelperJS::start('scripts'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pjCetakSelect = document.getElementById('pjCetakPendatang');
        const penghuniCetakSelect = document.getElementById('penghuniCetak');
        const jenisSuratSelect = document.getElementById('jenisSurat');
        const inputLainnya = document.getElementById('inputLainnya');
        const keperluanFinalInput = document.getElementById('keperluanFinal');
        const btnCetakLangsung = document.getElementById('btnCetakLangsung');

        jenisSuratSelect.addEventListener('change', function () {
            if (this.value === 'lainnya') {
                inputLainnya.style.display = 'block';
                keperluanFinalInput.value = inputLainnya.value;
            } else {
                inputLainnya.style.display = 'none';
                keperluanFinalInput.value = this.value;
            }
        });
        inputLainnya.addEventListener('input', function () {
            keperluanFinalInput.value = this.value;
        });

        pjCetakSelect.addEventListener('change', function () {
            const pjId = this.value;
            penghuniCetakSelect.innerHTML = '<option value="">-- Pilih Data Pendatang --</option>';
            penghuniCetakSelect.disabled = true;

            if (!pjId) return;
            fetch('<?= base_url('dashboard/surat/get_penghuni_by_pj/') ?>' + pjId)
                .then(res => res.json())
                .then(data => {
                    if (data && data.length > 0) {
                        data.forEach(p => {
                            const opt = document.createElement('option');
                            opt.value = p.uuid; 
                            opt.textContent = `${p.nama_lengkap} (NIK: ${p.nik})`;
                            penghuniCetakSelect.appendChild(opt);
                        });
                        penghuniCetakSelect.disabled = false;
                    }
                })
                .catch(err => Swal.fire('Error', 'Gagal mengambil data pendatang.', 'error'));
        });

        btnCetakLangsung.addEventListener('click', function () {
            const penghuniUuid = penghuniCetakSelect.value;
            const keperluanValue = keperluanFinalInput.value;
            const displayName = penghuniCetakSelect.options[penghuniCetakSelect.selectedIndex]?.textContent;
            
            if (!penghuniUuid || !keperluanValue) {
                Swal.fire('Peringatan', 'Pilih Penanggung Jawab, Data Pendatang, dan Keperluan surat!', 'warning');
                return;
            }

            Swal.fire({
                title: 'Cetak Surat?',
                text: 'Anda akan membuat dan mencetak surat pengantar untuk ' + displayName + '.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Cetak',
                cancelButtonText: 'Batal'

            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?= base_url('dashboard/surat/cetak_langsung_pendatang') ?>'; 
                    form.target = '_blank';

                    const hiddenInputPenghuni = document.createElement('input');
                    hiddenInputPenghuni.type = 'hidden';
                    hiddenInputPenghuni.name = 'penghuni_uuid';
                    hiddenInputPenghuni.value = penghuniUuid;
                    form.appendChild(hiddenInputPenghuni);

                    const hiddenInputKeperluan = document.createElement('input');
                    hiddenInputKeperluan.type = 'hidden';
                    hiddenInputKeperluan.name = 'keperluan';
                    hiddenInputKeperluan.value = keperluanValue;
                    form.appendChild(hiddenInputKeperluan);
                    
                    document.body.appendChild(form);
                    form.submit();
                    document.body.removeChild(form);
                }
            });
        });

        
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-terima-surat, .btn-tolak-surat');
            if (!btn) return;
            const id = btn.dataset.id;
            const isTerima = btn.classList.contains('btn-terima-surat');
            const status = isTerima ? 'Diterima' : 'Ditolak';

            Swal.fire({
                title: 'Konfirmasi',
                text: `Apakah Anda yakin ingin ${isTerima ? 'menerima' : 'menolak'} surat ini?`,
                icon: isTerima ? 'question' : 'warning',
                showCancelButton: true,
                confirmButtonText: `Ya, ${isTerima ? 'Terima' : 'Tolak'}`,
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch('<?= base_url('dashboard/surat/verifikasi') ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `id=${encodeURIComponent(id)}&status=${status}`
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            Swal.fire('Berhasil', `Surat berhasil ${status.toLowerCase()}!`, 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Gagal', res.error || 'Gagal memproses surat', 'error');
                        }
                    })
                    .catch(() => Swal.fire('Gagal', 'Terjadi kesalahan jaringan', 'error'));
                }
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
