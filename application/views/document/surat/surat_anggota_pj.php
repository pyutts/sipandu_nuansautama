<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
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
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="alert alert-warning border-0 shadow-sm rounded-3" role="alert">
                        <h5 class="mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Perhatian!</h5>
                        <ul class="mb-0 ps-3" style="list-style-type: disc;">
                            <li>Silahkan Memilih Data Anggota yang sesuai dengan<strong> Penanggung Jawab</strong></li>
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
                            <label for="anggota" class="form-label">Pilih Data Anggota Keluarga</label>
                            <select class="form-select" id="anggota" name="anggota_id">
                                <option value="">-- Pilih Data Anggota Keluarga --</option>
                                <?php foreach ($anggota as $a): ?>
                                    <option value="<?= $a->uuid ? $a->uuid : $a->id ?>" data-nama="<?= $a->nama ?>">
                                        <?= $a->nama ?> (NIK: <?= $a->nik_anggota ?>)
                                    </option>
                                <?php endforeach; ?>
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
                            <button type="button" class="btn btn-primary" id="btnAjukanSurat" disabled>
                                <i class="fa-solid fa-paper-plane me-2"></i>
                                Ajukan Surat
                            </button>
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#cetakLangsungModal">
                                <i class="fas fa-print me-2"></i>
                                Cetak Langsung (Untuk Penanggung Jawab)
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

        <!-- Tabel Surat Menunggu Verifikasi -->
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
                                <th class="text-center">Nama Anggota</th>
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
                                            
                                            <td class="text-center">
                                                <?= htmlspecialchars($s->nama_pemohon) ?> </br>
                                                <?php if (is_null($s->anggota_keluarga_id)): ?>
                                                    <span class="badge bg-info">Diajukan Pribadi</span>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-center"><?= htmlspecialchars($s->keperluan) ?></td>
                                            <td class="text-center"><?= date('d/m/Y', strtotime($s->tanggal_pengajuan)) ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-warning">Diproses</span>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-secondary btn-sm" title="Belum bisa cetak" disabled>
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
                                        
                                        <td class="text-center">
                                            <?= htmlspecialchars($s->nama_pemohon) ?>
                                            <?php if (is_null($s->anggota_keluarga_id)): ?>
                                                <span class="badge bg-info">Pribadi</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center"><?= htmlspecialchars($s->keperluan) ?></td>
                                        <td class="text-center"><?= date('d/m/Y', strtotime($s->tanggal_pengajuan)) ?></td>
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
                                            <?php else: ?>
                                                <button class="btn btn-secondary btn-sm" title="Tidak dapat mencetak surat" disabled>
                                                    <i class="fas fa-print"></i>
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

            <div class="modal fade" id="cetakLangsungModal" tabindex="-1" aria-labelledby="cetakLangsungModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cetakLangsungModalLabel">Cetak Surat Pengantar Langsung</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info border-0 shadow-sm rounded-3" role="alert">
                                <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Perhatian! (Fitur Cetak Langsung)</h5>
                                <ul class="mb-0 ps-3" style="list-style-type: disc;">
                                    <li>Fitur ini digunakan untuk mencetak surat secara langsung Khusus untuk <strong>Penanggung Jawab</strong>, dan Diverifikasi kembali oleh <strong>Admin & Kaling</strong></li>
                                </ul>
                            </div>

                            <form id="formCetakLangsung">
                                <div class="form-group mb-4">
                                    <label for="keperluanCetakLangsung" class="form-label">Pilih Keperluan Surat</label>
                                    <select class="form-select" id="keperluanCetakLangsung">
                                        <option value="">-- Pilih Keperluan Surat --</option>
                                        <option value="Mengurus KK">Mengurus KK</option>
                                        <option value="Mengurus KTP">Mengurus KTP</option>
                                        <option value="Mengurus Surat Domisili">Mengurus Surat Domisili</option>
                                        <option value="Mengurus Surat Belum Menikah">Mengurus Surat Belum Menikah</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                    <input type="text" id="inputLainnyaCetak" class="form-control mt-2" placeholder="Tulis keperluan lainnya" style="display:none;">
                                    <input type="hidden" name="keperluan" id="keperluanFinalCetak">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-primary" id="btnCetakLangsung" disabled>
                                <i class="fas fa-print me-2"></i>Cetak Surat
                            </button>
                        </div>
                    </div>
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
        const anggotaSelect = document.getElementById('anggota');
        const jenisSuratSelect = document.getElementById('jenisSurat');
        const btnAjukanSurat = document.getElementById('btnAjukanSurat');

        function checkForm() {
            if (!anggotaSelect || !jenisSuratSelect || !btnAjukanSurat) return;
            const keperluanValue = document.getElementById('keperluanFinal').value;
            btnAjukanSurat.disabled = !anggotaSelect.value || !keperluanValue;
        }

        const keperluanDropdown = document.getElementById('jenisSurat');
        const keperluanInput = document.getElementById('inputLainnya');

        if (anggotaSelect) anggotaSelect.addEventListener('change', checkForm);
        if (keperluanDropdown) keperluanDropdown.addEventListener('change', checkForm);
        if (keperluanInput) keperluanInput.addEventListener('input', checkForm);

        checkForm(); 

        if (btnAjukanSurat) {
            btnAjukanSurat.addEventListener('click', function() {
                const anggotaValue = document.getElementById('anggota').value;
                const keperluanValue = document.getElementById('keperluanFinal').value;

                if (!anggotaValue || !keperluanValue) {
                    Swal.fire('Peringatan', 'Pilih data anggota dan isi keperluannya!', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Ajukan Surat?',
                    text: 'Yakin ingin mengajukan surat ini ke admin?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Ajukan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        btnAjukanSurat.disabled = true;
                        fetch('<?= base_url('dashboard/surat/ajukan_surat_anggota') ?>', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: new URLSearchParams({
                                'anggota_id': anggotaValue,
                                'keperluan': keperluanValue 
                            })
                        })
                        .then(async res => { 
                            if (!res.ok) {
                                const errorData = await res.json().catch(() => ({ error: 'Terjadi kesalahan tidak dikenal.' }));
                                const errorMessage = errorData.error || 'Terjadi kesalahan pada server.';
                                Swal.fire('Gagal', errorMessage, 'error');
                                throw new Error(errorMessage);
                            }
                            return res.json();
                        })
                        .then(res => {
                            if (res && res.success) {
                                Swal.fire('Berhasil', 'Pengajuan surat berhasil dikirim ke admin', 'success').then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Gagal', (res && res.error) || 'Gagal mengajukan surat', 'error');
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

    $(document).ready(function() {
        const keperluanSelect = $('#keperluanCetakLangsung');
        const keperluanInput = $('#inputLainnyaCetak');
        const keperluanFinal = $('#keperluanFinalCetak'); 
        const btnCetak = $('#btnCetakLangsung');

        keperluanSelect.on('change', function() {
            if ($(this).val() === 'lainnya') {
                keperluanInput.show();
            } else {
                keperluanInput.hide();
            }
            validasiForm();
        });

        keperluanInput.on('keyup', function() {
            validasiForm();
        });

        function validasiForm() {
            let isValid = false;
            const selectedValue = keperluanSelect.val();

            if (selectedValue && selectedValue !== 'lainnya') {
                isValid = true;
            } else if (selectedValue === 'lainnya' && keperluanInput.val().trim() !== '') {
                isValid = true;
            }
            btnCetak.prop('disabled', !isValid);
        }

        btnCetak.on('click', function(e) {
            e.preventDefault();
            let finalKeperluanValue = '';
            if (keperluanSelect.val() === 'lainnya') {
                finalKeperluanValue = keperluanInput.val();
            } else {
                finalKeperluanValue = keperluanSelect.val();
            }

            $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengajukan...');

            $.ajax({
                url: "<?= site_url('dashboard/surat/ajukan_pj_sendiri') ?>",
                method: 'POST',
                data: {
                    keperluan: finalKeperluanValue
                },
                dataType: 'json',
                success: function(response) {
                    $('#cetakLangsungModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload(); 
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    let errorMessage = 'Terjadi kesalahan sistem. Silakan coba lagi.';
                    if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                        errorMessage = jqXHR.responseJSON.error;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMessage
                    });
                },
                complete: function() {
                    btnCetak.prop('disabled', false).html('<i class="fas fa-print me-2"></i>Cetak Surat');
                }
            });
        });

        $('#cetakLangsungModal').on('hidden.bs.modal', function () {
            $('#formCetakLangsung')[0].reset();
            keperluanInput.hide();
            btnCetak.prop('disabled', true);
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
</script>
<?php HelperJS::end('scripts'); ?>

<?php $this->load->view('partials/footer'); ?>
