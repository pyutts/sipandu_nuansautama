<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title mb-0">Detail Penanggung Jawab</h4>
                <button type="button" class="btn btn-danger" onclick="window.location.href='<?= base_url('dashboard/pj/view') ?>'">Kembali</button>
            </div>
            <div class="row">
                <!-- Foto KK -->
                <div class="col-md-12 mb-4">
                    <div class="text-center mb-4">
                        <h5>Foto KK</h5>
                        <?php if ($pj->foto_kk): ?>
                            <img src="<?= base_url('uploads/pj/' . $pj->foto_kk) ?>" alt="Foto KK" class="img-fluid rounded" style="max-height: 200px;">
                        <?php else: ?>
                            <p class="text-muted">Foto tidak tersedia</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Informasi Detail -->
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th>Status Rumah</th>
                                <td>
                                    <?php
                                    $badge_class = [
                                        'Permanen' => 'bg-info',
                                        'Kontrak' => 'bg-success',
                                    ];

                                    $status = $pj->status_rumah ?? null;
                                    if ($status && isset($badge_class[$status])): ?>
                                        <span class="badge <?= $badge_class[$status] ?>">
                                            <?= $status ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Username</th>
                                <td><?= $pj->username?></td>
                            </tr>
                            <tr>
                                <th>Nama Penanggung Jawab</th>
                                <td><?= $pj->nama_pj ?></td>
                            </tr>
                            <tr>
                                <th>NIK Penanggung Jawab</th>
                                <td><?= $pj->nik ?></td>
                            </tr>
                            <tr>
                                <th>Nomor KK</th>
                                <td><?= $pj->no_kk ?></td>
                            </tr>
                            <tr>
                                <th>Tempat Lahir</th>
                                <td><?= $pj->tempat_lahir ?></td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td><?= $pj->jenis_kelamin ?></td>
                            </tr>
                            <tr>
                                <th>Tanggal Lahir</th>
                                <td><?= $pj->tanggal_lahir ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?= $pj->email ?></td>
                            </tr>
                            <tr>
                                <th>No HP</th>
                                <td><?= $pj->no_hp ?></td>
                            </tr>
                            <tr>
                                <th>Alamat Maps</th>
                                <td><?= $pj->alamat_maps ?></td>
                            </tr>
                            <tr>
                                <th>Alamat Detail</th>
                                <td><?= $pj->alamat_detail ?></td>
                            </tr>
                            <tr>
                                <th>Nomor Rumah</th>
                                <td><?= $pj->alamat_no ?></td>
                            </tr>
                            <tr>
                                <th>Koordinat Lokasi</th>
                                <td>
                                    <?= $pj->latitude ?>, <?= $pj->longitude ?>
                                    <div id="map" style="height: 200px;" z-index: 0; class="mt-2"></div>
                                </td>
                            </tr>
                            <tr>
                                <th>Wilayah</th>
                                <td><?= isset($pj->wilayah_nama) ? $pj->wilayah_nama : '-' ?></td>
                            </tr>
                        </table>
                      
                    </div>
                </div>

                <div class="col-md-12">
                        <br/><br/>
                        <h5 class="text-center mb-3">Data Anggota Keluarga</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Tempat Lahir</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Hubungan</th>
                                            <th>Pekerjaan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($anggota_keluarga as $anggota): ?>
                                            <tr>
                                                <td><?= $anggota->nik_anggota ?></td>
                                                <td><?= $anggota->nama ?></td>
                                                <td><?= $anggota->tempat_lahir ?></td>
                                                <td><?= $anggota->tanggal_lahir ?></td>
                                                <td><?= $anggota->jenis_kelamin ?></td>
                                                <td><?= $anggota->hubungan ?></td>
                                                <td><?= $anggota->pekerjaan ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                      </div>  
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('partials/watermark'); ?>
<?php HelperJS::start('scripts'); ?>

<script>
    $(document).ready(function() {
        $('#map').css('z-index', 0);
        var map = L.map('map').setView([<?= $pj->latitude ?>, <?= $pj->longitude ?>], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        L.marker([<?= $pj->latitude ?>, <?= $pj->longitude ?>])
            .addTo(map)
            .bindPopup('<?= $pj->nama_pj ?>');
    });
</script>

<?php HelperJS::end('scripts'); ?>
<?php $this->load->view('partials/footer'); ?>
