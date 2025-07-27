<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>
<?php $error = $this->session->flashdata('error'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Laporan</h5>

            <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-4" role="alert">
                <h5 class="mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Perhatian!</h5>
                <ul class="mb-0 ps-3" style="list-style-type: disc;">
                    <li>Silahkan Memilih Data Pendatang yang sudah terverifikasi oleh <strong>Kepala Lingkungan</strong></li>
                    <li>Cetak Dalam Format Kertas <strong>A4</strong></li>
                    <li>Scale Format Dokumen di <strong>100%</strong></li>
                </ul>
            </div>

            <form id="laporanForm">
                <div class="form-group mb-4">
                    <label for="jenisLaporan" class="form-label">Pilih Jenis Laporan</label>
                    <select class="form-select" id="jenisLaporan" name="jenisLaporan">
                        <option value="">-- Pilih Jenis Laporan --</option>
                        <option value="report_pj">Laporan Pendatang Berdasarkan Data Penanggung Jawab</option>
                        <option value="report_all">Laporan Semua Pendatang Pada Perumahan Nuansa Utama</option>
                    </select>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary" id="btnCetakLaporan">
                        <i class="ti ti-printer me-1"></i>
                        Cetak Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php $this->load->view('partials/watermark'); ?>

    <?php HelperJS::start('scripts'); ?>
    <script>
        $(document).ready(function() {
            const jenisLaporanSelect = $('#jenisLaporan');
            const btnCetakLaporan = $('#btnCetakLaporan');

            function checkForm() {
                btnCetakLaporan.prop('disabled', !jenisLaporanSelect.val());
            }

            jenisLaporanSelect.on('change', checkForm);
            checkForm();

            btnCetakLaporan.on('click', function() {
                const jenisLaporan = jenisLaporanSelect.val();

                if (!jenisLaporan) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Silakan pilih jenis laporan terlebih dahulu!'
                    });
                    return;
                }

                let title, url;

                switch (jenisLaporan) {
                    case 'report_pj':
                        title = 'Laporan Pendatang Berdasarkan Data Penanggung Jawab';
                        url = '<?= base_url('dashboard/reportdetailpj') ?>';
                        processReport(title, url);
                        break;
                    case 'report_all':
                        title = 'Laporan Semua Pendatang Pada Perumahan Nuansa Utama';
                        url = '<?= base_url('dashboard/reportall') ?>';
                        processReport(title, url);
                        break;
                    default:
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Jenis laporan tidak valid!'
                        });
                        return;
                }
            });

            function processReport(title, url) {
                Swal.fire({
                    title: 'Cetak ' + title,
                    text: 'Apakah anda yakin Mencetak ' + title + '?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Cetak!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            method: 'GET',
                            success: function(data, status, xhr) {
                                const contentType = xhr.getResponseHeader('Content-Type');
                                if (contentType && contentType.indexOf('application/json') !== -1) {
                                    let json;
                                    try {
                                        json = typeof data === 'string' ? JSON.parse(data) : data;
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal Cetak',
                                            text: json.message || 'Terjadi kesalahan.'
                                        });
                                        
                                    } catch (e) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal Cetak',
                                            text: 'Terjadi kesalahan.'
                                        });
                                    }
                                } else if (contentType && contentType.indexOf('application/pdf') !== -1) {
                                    window.open(url, '_blank');
                                } else {
                                    window.open(url, '_blank');
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Cetak',
                                    text: 'Terjadi kesalahan saat menghubungi server.'
                                });
                            }
                        });
                    }
                });
            }
        });

    </script>
    <?php HelperJS::end('scripts'); ?>
    
    <?php $this->load->view('partials/footer'); ?>