<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('dashboard/pj/store') ?>" method="POST" enctype="multipart/form-data" id="pjForm">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Buat Akun Penanggung Jawab</h4>
                </div>

                <!-- Alert Section -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="alert alert-warning border-0 shadow-sm rounded-3" role="alert">
                            <h5 class="mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Perhatian!</h5>
                            <ul class="mb-0 ps-3" style="list-style-type: disc;">
                                <li>Pastikan Anda mengunggah <strong>Scan Kartu Keluarga</strong> dengan jelas.</li>
                                <li>Data seperti <em>NIK</em>, <em>Nama</em>, <em>Tempat/Tanggal Lahir</em>, dan lainnya harus diisi manual dan bisa otomatis sesuai KK.</li>
                                <li>Jangan lupa untuk <strong>klik lokasi</strong> Anda pada peta untuk mengisi koordinat.</li>
                                <li><strong>Alamat Sekarang</strong> akan terisi otomatis berdasarkan GPS browser Anda.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- FOTO & SCAN KK -->
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <label class="form-label">Scan Kartu Keluarga (KK)</label><br>
                        <img id="previewKKImg" src="<?= base_url('assets/images/profile/scan_kk.png') ?>" alt="Scan KK" class="img-fluid rounded" style="max-height: 400px;">
                        <div class="mt-4">
                            <input type="file" name="foto_kk" id="foto_kk" class="form-control <?= form_error('foto_kk') ? 'is-invalid' : '' ?>" accept="image/*,.pdf">
                             <?= form_error('foto_kk', '<div class="invalid-feedback d-block">', '</div>'); ?>
                        </div>
                    </div>

                    
                </div>
                <div class="mb-3 text-center">
                    <button type="button" class="btn btn-primary" onclick="showPreviewImage('modalPreviewKK', 'modalPreviewKK')">
                        <i class="fas fa-eye me-1"></i> Preview KK
                    </button>
                </div>
                

                <div class="row">
                    <!-- KOLOM KIRI -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">No KK (Sesuai Kartu Keluarga)</label>
                            <input type="number" name="no_kk" class="form-control <?= form_error('no_kk') ? 'is-invalid' : '' ?>" value="<?= set_value('no_kk'); ?>" placeholder="Masukkan 16 digit Nomor KK">
                            <?= form_error('no_kk', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIK Kepala Keluarga (Sesuai Kartu Keluarga)</label>
                            <input type="number" name="nik" class="form-control <?= form_error('nik') ? 'is-invalid' : '' ?>" value="<?= set_value('nik'); ?>" placeholder="Masukkan 16 digit NIK Kepala Keluarga">
                            <?= form_error('nik', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control <?= form_error('username') ? 'is-invalid' : '' ?>" value="<?= set_value('username'); ?>" placeholder="Masukkan Username untuk login">
                            <?= form_error('username', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group has-validation">
                                <input type="password" name="password" id="inputPassword" class="form-control <?= form_error('password') ? 'is-invalid' : '' ?>" placeholder="Masukkan Password">
                                <span class="input-group-text cursor-pointer" id="togglePassword">
                                    <i class="bi bi-eye-slash" id="iconPassword"></i>
                                </span>
                            </div>
                            <?= form_error('password', '<div class="invalid-feedback d-block">', '</div>'); ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control <?= form_error('email') ? 'is-invalid' : '' ?>" value="<?= set_value('email'); ?>" placeholder="Masukkan Email aktif">
                            <?= form_error('email', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Kepala Keluarga (Sesuai Kartu Keluarga)</label>
                            <input type="text" name="nama_pj" class="form-control <?= form_error('nama_pj') ? 'is-invalid' : '' ?>" value="<?= set_value('nama_pj'); ?>" placeholder="Masukkan Nama Kepala Keluarga">
                            <?= form_error('nama_pj', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No Handphone</label>
                            <input type="number" name="no_hp" class="form-control <?= form_error('no_hp') ? 'is-invalid' : '' ?>" value="<?= set_value('no_hp'); ?>" placeholder="Contoh: 081234567890">
                            <?= form_error('no_hp', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tempat Lahir (Sesuai Kartu Keluarga)</label>
                            <input type="text" name="tempat_lahir" class="form-control <?= form_error('tempat_lahir') ? 'is-invalid' : '' ?>" value="<?= set_value('tempat_lahir'); ?>" placeholder="Masukkan Tempat Lahir">
                            <?= form_error('tempat_lahir', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Lahir (Sesuai Kartu Keluarga)</label>
                            <input type="date" name="tanggal_lahir" class="form-control <?= form_error('tanggal_lahir') ? 'is-invalid' : '' ?>" value="<?= set_value('tanggal_lahir'); ?>">
                            <?= form_error('tanggal_lahir', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>
                         <div class="mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select <?= form_error('jenis_kelamin') ? 'is-invalid' : '' ?>">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="LAKI - LAKI" <?= set_select('jenis_kelamin', 'LAKI - LAKI'); ?>>LAKI-LAKI</option>
                                <option value="PEREMPUAN" <?= set_select('jenis_kelamin', 'PEREMPUAN'); ?>>PEREMPUAN</option>
                            </select>
                            <?= form_error('jenis_kelamin', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Wilayah</label>
                            <select class="form-select <?= form_error('wilayah_id') ? 'is-invalid' : '' ?>" name="wilayah_id" id="wilayah_id" disabled>
                                 <?php foreach ($wilayah as $w): ?>
                                    <option value="<?= $w->id ?>" <?= ($w->id == $default_wilayah_id) ? 'selected' : '' ?>>
                                        <?= $w->wilayah ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                             <?= form_error('wilayah_id', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>
                        
                        <input type="hidden" name="wilayah_id" value="<?= $default_wilayah_id ?>">


                        <div class="mb-3">
                            <label class="form-label">Alamat Detail</label>
                             <select class="form-select <?= form_error('alamat_detail') ? 'is-invalid' : '' ?>" name="alamat_detail" id="alamat_detail">
                                <option value="">-- Pilih Alamat Detail --</option>
                                <?php 
                                    $jalan_options = ["JL. NUANSA UTAMA II", "JL. NUANSA UTAMA IV", "JL. NUANSA UTAMA BARAT", "JL. NUANSA UTAMA V", "JL. NUANSA UTAMA VI", "JL. NUANSA UTAMA VII", "JL. NUANSA UTAMA VIII", "JL. NUANSA UTAMA IX", "JL. NUANSA UTAMA X", "JL. NUANSA UTAMA XI", "JL. NUANSA UTAMA XIA", "JL. NUANSA UTAMA XII", "JL. NUANSA UTAMA XIII", "JL. NUANSA UTAMA XIV", "JL. NUANSA UTAMA XV", "JL. NUANSA UTAMA XVI", "JL. NUANSA UTAMA XVII", "JL. NUANSA UTAMA XVIII", "JL. NUANSA UTAMA TENGAH", "JL. NUANSA UTAMA XIX", "JL. NUANSA UTAMA XXI", "JL. NUANSA UTAMA XXIII", "JL. NUANSA UTAMA XXV", "JL. NUANSA UTAMA XXVII", "JL. NUANSA UTAMA RAYA"];
                                    foreach ($jalan_options as $jalan) {
                                        echo '<option value="'.$jalan.'" '.set_select('alamat_detail', $jalan).'>'.$jalan.'</option>';
                                    }
                                ?>
                            </select>
                            <?= form_error('alamat_detail', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No Rumah</label>
                            <select class="form-select <?= form_error('alamat_no') ? 'is-invalid' : '' ?>" name="alamat_no" id="alamat_no">
                                <option value="">-- Pilih No Rumah --</option>
                                <?php for ($i = 1; $i <= 40; $i++) {
                                    echo '<option value="'.$i.'" '.set_select('alamat_no', $i).'>'.$i.'</option>';
                                } ?>
                            </select>
                            <?= form_error('alamat_no', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status Rumah</label>
                            <?php
                                $status_options = ['' => '-- Pilih Status Rumah --', 'Permanen' => 'Permanen', 'Kontrak' => 'Kontrak'];
                                $extra_attributes = 'class="form-select '.(form_error('status_rumah') ? 'is-invalid' : '').'"';
                                echo form_dropdown('status_rumah', $status_options, set_value('status_rumah'), $extra_attributes);
                            ?>
                            <?= form_error('status_rumah', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="location-inputs">
                            <div class="mb-3">
                                <label class="form-label">Lokasi (Klik pada peta)</label>
                                <div id="map" style="height: 300px; z-index: 1; position: relative;"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" class="form-control" readonly required>
                                    <span class="invalid-feedback"></span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" class="form-control" readonly required>
                                    <span class="invalid-feedback"></span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Alamat Maps</label>
                                <textarea name="alamat_maps" id="alamat_maps" class="form-control" required></textarea>
                                <span class="invalid-feedback"></span>
                                <button type="button" id="search-location" class="btn btn-warning mt-2 w-100"> <i class="fa-solid fa-magnifying-glass"></i> Cari Lokasi</button>
                            </div>

                        </div>
                        <div class="mb-3">
                             <label class="form-label">Tambah Anggota Keluarga</label>
                             <?php
                                $anggota_errors = $this->session->flashdata('anggota_errors');
                                if (!empty($anggota_errors)):
                                ?>
                                    <div class="alert alert-danger" role="alert">
                                        <h4 class="alert-heading">Terdapat Kesalahan pada Data Anggota Keluarga!</h4>
                                        <p>Mohon periksa kembali data yang Anda masukkan:</p>
                                        <hr>
                                        <ul>
                                            <?php foreach ($anggota_errors as $error): ?>
                                                <li><?= html_escape($error); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                            <?php endif; ?>
                             <div class="mb-3">
                                 <button type="button" class="btn btn-secondary mt-2" data-bs-toggle="modal" data-bs-target="#modalTambahAnggota">
                                     <i class="ti ti-plus"></i> Tambah Data
                                 </button>
                             </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="anggotaTable">
                                    <thead>
                                        <tr>
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Tempat Lahir</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Hubungan</th>
                                            <th>Pekerjaan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="anggotaBody">
                                        <tr><td colspan="8" class="text-center">Belum ada data anggota keluarga.</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <input type="hidden" name="anggota_keluarga" id="anggota_keluarga_input">
                        </div>
                    </div>
                </div>
                <div class="mt-3 mb-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-danger" onclick="window.location.href='<?= base_url('dashboard/pj/view') ?>'">Kembali</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Anggota -->
<div class="modal fade" id="modalTambahAnggota" tabindex="-1" aria-labelledby="modalTambahAnggotaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahAnggotaLabel">Tambah Anggota Keluarga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">NIK Anggota</label>
                    <input type="number" id="nikAnggota" class="form-control" maxlength="17" required placeholder="Masukkan NIK Anggota">
                    <span class="invalid-feedback"></span>
                    <small class="text-muted">Masukkan 16 digit NIK sesuai KTP/KK</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" id="namaAnggota" class="form-control" maxlength="100" required placeholder="Masukkan Nama Lengkap">
                    <span class="invalid-feedback"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" id="tempatLahirAnggota" class="form-control" maxlength="20" required placeholder="Masukkan Tempat Lahir">
                    <span class="invalid-feedback"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" id="tanggalLahirAnggota" class="form-control" required placeholder="Pilih Tanggal Lahir">
                    <span class="invalid-feedback"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" id="jeniskelaminAnggota" class="form-select <?= form_error('jenis_kelamin') ? 'is-invalid' : '' ?>" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="LAKI - LAKI" <?= set_value('jenis_kelamin') == 'LAKI - LAKI' ? 'selected' : '' ?>>LAKI-LAKI</option>
                        <option value="PEREMPUAN" <?= set_value('jenis_kelamin') == 'PEREMPUAN' ? 'selected' : '' ?>>PEREMPUAN</option>
                    </select>
                    <span class="invalid-feedback error-message"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Hubungan dengan Kepala Keluarga</label>
                        <?php
                                $options = [
                                    '' => '-- Pilih Hubungan --',
                                    'Anak'=> 'Anak',
                                    'Istri'=> 'Istri',
                                    'Suami' => 'Suami',
                                    'Orang Tua' => 'Orang Tua',
                                    'Saudara'=> 'Saudara',
                                    'Lainnya'=> 'Lainnya',
                                ];
                                echo form_dropdown('hubunganAnggota', $options, set_value('hubunganAnggota'), 'class="form-select" id="hubunganAnggota" required');
                            ?>   
                </div>
                <div class="mb-3">
                    <label class="form-label">Pekerjaan Anggota</label>
                        <?php
                                $options = [
                                    '' => '-- Pilih Pekerjaan Anggota --',
                                    'Pegawai Swasta'=> 'Pegawai Swasta',
                                    'Wiraswasta'=> 'Wiraswasta',
                                    'Pelajar/Mahasiswa' => 'Pelajar/Mahasiswa',
                                    'Mengurus Rumah Tangga' => 'Mengurus Rumah Tangga',
                                    'PNS'=> 'PNS',
                                    'TNI/Polri' => 'TNI/Polri',
                                    'Pensiunan' => 'Pensiunan',
                                    'Belum/Tidak Bekerja'=> 'Belum/Tidak Bekerja',
                                ];
                                echo form_dropdown('pekerjaanAnggota', $options, set_value('pekerjaanAnggota'), 'class="form-select" id="pekerjaanAnggota" required');
                            ?>                    
                </div>
                <button type="button" class="btn btn-primary" onclick="addAnggota()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview KK -->
<div class="modal fade" id="modalPreviewKK" tabindex="-1" aria-labelledby="modalPreviewKKLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPreviewKKLabel">Preview KK</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center image-container">
                    <img id="modalPreviewKKImg" src="#" alt="Preview KK" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>
</div>


<?php $this->load->view('partials/watermark'); ?>
<?php HelperJS::start('scripts'); ?>
<script>
    let map, marker;
    window.anggotaKeluarga = [];

    async function getAddressFromCoordinates(lat, lng) {
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
            const data = await response.json();
            return data.display_name || 'Alamat tidak ditemukan';
        } catch (error) {
            console.error("Gagal mendapatkan alamat:", error);
            return "Gagal memuat alamat";
        }
    }

    function initMap(lat = -8.7903, lng = 115.1726) { 
        map = L.map('map').setView([lat, lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        map.on('click', async function(e) {
            const { lat, lng } = e.latlng;
            marker.setLatLng([lat, lng]);
            $('#latitude').val(lat.toFixed(8));
            $('#longitude').val(lng.toFixed(8));
            const address = await getAddressFromCoordinates(lat, lng);
            $('#alamat_maps').val(address);
        });

        marker.on('dragend', async function() {
            const { lat, lng } = marker.getLatLng();
            $('#latitude').val(lat.toFixed(8));
            $('#longitude').val(lng.toFixed(8));
            const address = await getAddressFromCoordinates(lat, lng);
            $('#alamat_maps').val(address);
        });
    }
    
    function showError(input, message) {
        $(input).addClass('is-invalid');
        $(input).next('.invalid-feedback').text(message).show();
    }
    function clearError(input) {
        $(input).removeClass('is-invalid');
        $(input).next('.invalid-feedback').text('').hide();
    }

    $(document).ready(function() {
        try {
            const oldData = JSON.parse('<?= html_entity_decode(set_value('anggota_keluarga', '[]')) ?>');
            if (Array.isArray(oldData)) {
                window.anggotaKeluarga = oldData;
            }
        } catch(e) {
            console.error("Gagal parsing data anggota lama:", e);
        }
        updateTable();

        initMap();
        
        if (!$('#latitude').val() || !$('#longitude').val()) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(async function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    $('#latitude').val(lat.toFixed(8));
                    $('#longitude').val(lng.toFixed(8));
                    
                    const address = await getAddressFromCoordinates(lat, lng);
                    $('#alamat_maps').val(address);

                    if (marker) {
                        marker.setLatLng([lat, lng]);
                        map.setView([lat, lng], 15);
                    }
                }, function(error) {
                    console.warn('Gagal mendapatkan lokasi otomatis:', error.message);
                });
            }
        }

        $('#togglePassword').on('click', function() {
            const input = $('#inputPassword');
            const icon = $('#iconPassword');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
            } else {
                input.attr('type', 'password');
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
            }
        });

        $('#pjForm').on('submit', function(e) {
            $('#anggota_keluarga_input').val(JSON.stringify(window.anggotaKeluarga));
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: 'Simpan Data?',
                text: 'Pastikan semua data sudah benar sebelum disimpan!',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        $('#search-location').on('click', function() {
            const address = $('#alamat_maps').val();
            if (!address) {
                Swal.fire('Perhatian', 'Masukkan alamat terlebih dahulu untuk dicari.', 'warning');
                return;
            }

            fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&limit=1`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Jaringan bermasalah');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.length > 0) {
                        const { lat, lon } = data[0];
                        marker.setLatLng([lat, lon]);
                        map.setView([lat, lon], 15);
                        $('#latitude').val(parseFloat(lat).toFixed(8));
                        $('#longitude').val(parseFloat(lon).toFixed(8));
                    } else {
                        Swal.fire('Error', 'Alamat tidak ditemukan.', 'error');
                    }
                })
                .catch(err => {
                    console.error("Error mencari lokasi:", err);
                    Swal.fire('Error', 'Gagal mencari lokasi.', 'error');
                });
        });
    });

    function addAnggota() {
        let valid = true;
        $('#nikAnggota, #namaAnggota, #tempatLahirAnggota, #tanggalLahirAnggota, #jeniskelaminAnggota, #hubunganAnggota').each(function() {
            if (!$(this).val()) {
                showError(this, 'Wajib diisi!');
                valid = false;
            } else {
                clearError(this);
            }
        });
        if ($('#nikAnggota').val().length !== 16) {
            showError('#nikAnggota', 'NIK harus 16 digit!');
            valid = false;
        } else {
             clearError('#nikAnggota');
        }
        if (!valid) return;

        const nik = $('#nikAnggota').val();
        const nama = $('#namaAnggota').val();
        const tempatLahir = $('#tempatLahirAnggota').val();
        const tanggalLahir = $('#tanggalLahirAnggota').val();
        const jenis_kelamin = $('#jeniskelaminAnggota').val();
        const hubungan = $('#hubunganAnggota').val();
        const pekerjaan = $('#pekerjaanAnggota').val();
        const editNIK = $('#modalTambahAnggota').data('edit-nik');

        if (editNIK) { 
            const anggota = window.anggotaKeluarga.find(a => a.nik === editNIK);
            if (anggota) {
                anggota.nik = nik;
                anggota.nama = nama;
                anggota.tempat_lahir = tempatLahir;
                anggota.tanggal_lahir = tanggalLahir;
                anggota.jenis_kelamin = jenis_kelamin;
                anggota.hubungan = hubungan;
                anggota.pekerjaan = pekerjaan || null;
            }
        } else { 
            const exists = window.anggotaKeluarga.some(anggota => anggota.nik === nik);
            if (exists) {
                Swal.fire('Error', 'NIK sudah ada di dalam daftar anggota.', 'error');
                return;
            }
            window.anggotaKeluarga.push({ nik, nama, tempat_lahir: tempatLahir, tanggal_lahir: tanggalLahir, jenis_kelamin, hubungan, pekerjaan: pekerjaan || null });
        }
        
        updateTable();
        $('#modalTambahAnggota').modal('hide');
    }

    function updateTable() {
        const tbody = $('#anggotaBody');
        tbody.empty();

        if (window.anggotaKeluarga.length === 0) {
            tbody.append('<tr><td colspan="8" class="text-center">Belum ada data anggota keluarga.</td></tr>');
        } else {
            window.anggotaKeluarga.forEach((anggota, index) => {
                const row = `
                    <tr>
                        <td>${anggota.nik}</td>
                        <td>${anggota.nama}</td>
                        <td>${anggota.tempat_lahir}</td>
                        <td>${anggota.tanggal_lahir}</td>
                        <td>${anggota.jenis_kelamin || '-'}</td>
                        <td>${anggota.hubungan}</td>
                        <td>${anggota.pekerjaan || '-'}</td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" onclick="editAnggotaByNIK('${anggota.nik}')"><i class="ti ti-edit"></i></button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteAnggota(${index})"><i class="ti ti-trash"></i></button>
                        </td>
                    </tr>`;
                tbody.append(row);
            });
        }
    }

    function deleteAnggota(index) {
        Swal.fire({
            title: 'Hapus Anggota?',
            text: "Data anggota ini akan dihapus dari daftar.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.anggotaKeluarga.splice(index, 1);
                updateTable();
            }
        })
    }
    
    function editAnggotaByNIK(nik) {
        const anggota = window.anggotaKeluarga.find(a => a.nik === nik);
        if (!anggota) return;

        $('#nikAnggota').val(anggota.nik);
        $('#namaAnggota').val(anggota.nama);
        $('#tempatLahirAnggota').val(anggota.tempat_lahir);
        $('#tanggalLahirAnggota').val(anggota.tanggal_lahir);
        $('#jeniskelaminAnggota').val(anggota.jenis_kelamin);
        $('#hubunganAnggota').val(anggota.hubungan);
        $('#pekerjaanAnggota').val(anggota.pekerjaan);

        $('#modalTambahAnggotaLabel').text('Edit Anggota Keluarga');
        $('#modalTambahAnggota').data('edit-nik', nik);
        $('#modalTambahAnggota').modal('show');
    }
    
    $('#modalTambahAnggota').on('hidden.bs.modal', function() {
        $(this).find('input, select').val('');
        clearError($(this).find('input, select'));
        $('#modalTambahAnggotaLabel').text('Tambah Anggota Keluarga');
        $(this).removeData('edit-nik');
    });

   function showPreviewImage(previewId, modalId) {
        const fileInput = document.getElementById('foto_kk');
        const file = fileInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(previewId + 'Img'); 
                img.src = e.target.result;
                const modal = new bootstrap.Modal(document.getElementById(modalId));
                modal.show();
            };
            reader.readAsDataURL(file);
        } else {
            Swal.fire('Error', 'Anda belum memilih gambar!', 'error');
        }
    }

</script>
<?php HelperJS::end('scripts'); ?>
<?php $this->load->view('partials/footer'); ?>