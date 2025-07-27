<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title mb-0">Tambah Data Pendatang</h4>  
            </div>
            <form action="<?= base_url('dashboard/penghuni/store/admin') ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <!-- ALERT PERHATIAN -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-warning border-0 shadow-sm rounded-3" role="alert">
                                <h5 class="mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Perhatian!</h5>
                                <ul class="mb-0 ps-3" style="list-style-type: disc;">
                                    <li>Pastikan Anda mengunggah <strong>Foto</strong> dan <strong>Scan KTP</strong> dengan jelas.</li>
                                    <li>Data seperti <em>NIK</em>, <em>Nama</em>, <em>Tempat/Tanggal Lahir</em>, dan lainnya harus diisi manual sesuai KTP.</li>
                                    <li>Jangan lupa untuk <strong>klik lokasi</strong> Anda pada peta untuk mengisi koordinat.</li>
                                    <li><strong>Alamat Sekarang</strong> akan terisi otomatis berdasarkan GPS browser Anda.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- FOTO & SCAN KTP -->
                    <div class="row mb-4">
                        <div class="col-md-6 text-center">
                            <label class="form-label">Foto</label><br>
                            <img src="<?= base_url('assets/images/profile/foto.png') ?>" alt="Foto" class="img-fluid" style="max-height: 200px; filter: grayscale(100%);">
                            <div class="mt-4">
                                <input type="file" name="foto" id="foto"  class="form-control <?= form_error('foto') ? 'is-invalid' : '' ?> accept="image/png, image/jpeg" onchange="validateImage(this)" required>
                                <?= form_error('foto', '<div class="invalid-feedback d-block">', '</div>'); ?>
                                <button type="button" class="btn btn-primary mt-4 text-center" onclick="showPreviewImage('foto', 'previewFotoImg', 'modalPreviewFoto')">Preview Foto</button>
                            </div>
                        </div>
                        <div class="col-md-6  text-center">
                            <label class="form-label">KTP (Kartu Tanda Penduduk)</label><br>
                            <img src="<?= base_url('assets/images/profile/scan_ktp.png') ?>" alt="Scan KTP" class="img-fluid" style="max-height: 200px; filter: grayscale(100%);">
                            <div class="mt-4">
                                <input type="file" name="ktp" id="ktp" class="form-control <?= form_error('ktp') ? 'is-invalid' : '' ?>" accept="image/png, image/jpeg" onchange="validateImage(this)" required>
                                 <?= form_error('ktp', '<div class="invalid-feedback d-block">', '</div>'); ?>
                            </div>
                            <button type="button" class="btn btn-primary mt-4 text-center" onclick="showPreviewImage('ktp', 'previewKtpImg', 'modalPreviewKTP')">Preview KTP</button>
                            <button type="button" class="btn btn-success mt-4 text-center" onclick="scanKTP()">Scan & Isi Otomatis</button>
                            <div id="loading-ocr" class="text-muted mt-2" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i> Memproses gambar...
                            </div>              
                        </div>
                    </div>

                    <!-- Modal Preview Foto -->
                    <div class="modal fade" id="modalPreviewFoto" tabindex="-1" aria-labelledby="modalPreviewFotoLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalPreviewFotoLabel">Preview Foto</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img id="previewFotoImg" src="#" alt="Preview Foto" class="img-fluid rounded shadow">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Preview KTP -->
                    <div class="modal fade" id="modalPreviewKTP" tabindex="-1" aria-labelledby="modalPreviewKTPLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalPreviewKTPLabel">Preview KTP</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img id="previewKtpImg" src="#" alt="Preview KTP" class="img-fluid rounded shadow">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM KIRI -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">NIK (Isi Sesuai KTP)</label>
                            <input type="text" name="nik" class="form-control <?= form_error('nik') ? 'is-invalid' : '' ?>" value="<?= set_value('nik'); ?>" required placeholder="Masukkan NIK Sesuai dengan KTP">
                            <?= form_error('nik', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap (Isi Sesuai KTP)</label>
                            <input type="text" name="nama" class="form-control <?= form_error('nama') ? 'is-invalid' : '' ?>" value="<?= set_value('nama'); ?>" required placeholder="Masukkan Nama Lengkap Sesuai dengan KTP">
                            <?= form_error('nama', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No Handphone</label>
                            <input type="number" name="no_hp" class="form-control <?= form_error('no_hp') ? 'is-invalid' : '' ?>" value="<?= set_value('no_hp'); ?>" placeholder="Masukkan No Handphone yang sesuai">
                            <?= form_error('no_hp', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tempat Lahir (Isi Sesuai KTP)</label>
                            <input type="text" name="tempat_lahir" class="form-control <?= form_error('tempat_lahir') ? 'is-invalid' : '' ?>" value="<?= set_value('tempat_lahir'); ?>" placeholder="Masukkan Tempat Lahir Sesuai dengan KTP">
                            <?= form_error('tempat_lahir', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Lahir (Isi Sesuai KTP)</label>
                            <input type="date" name="tanggal_lahir" class="form-control <?= form_error('tanggal_lahir') ? 'is-invalid' : '' ?>" value="<?= set_value('tanggal_lahir'); ?>">
                            <?= form_error('tanggal_lahir', '<div class="invalid-feedback">', '</div>'); ?>
                        </div> 

                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin (Isi Sesuai KTP)</label>
                            <select name="jenis_kelamin" class="form-select <?= form_error('jenis_kelamin') ? 'is-invalid' : '' ?>">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="LAKI - LAKI" <?= set_select('jenis_kelamin', 'LAKI - LAKI'); ?>>LAKI-LAKI</option>
                                <option value="PEREMPUAN" <?= set_select('jenis_kelamin', 'PEREMPUAN'); ?>>PEREMPUAN</option>
                            </select>
                            <?= form_error('jenis_kelamin', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Golongan Darah</label>
                            <select name="golongan_darah" class="form-select <?= form_error('golongan_darah') ? 'is-invalid' : '' ?>">
                                <option value="">-- Pilih Golongan Darah --</option>
                                <option value="A" <?= set_select('golongan_darah', 'A'); ?>>A</option>
                                <option value="B" <?= set_select('golongan_darah', 'B'); ?>>B</option>
                                <option value="AB" <?= set_select('golongan_darah', 'AB'); ?>>AB</option>
                                <option value="O" <?= set_select('golongan_darah', 'O'); ?>>O</option>
                            </select>
                            <?= form_error('golongan_darah', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Agama</label>
                           <select name="agama" class="form-select <?= form_error('agama') ? 'is-invalid' : '' ?>">
                                <option value="">-- Pilih Agama --</option>
                                <option value="ISLAM" <?= set_select('agama', 'ISLAM'); ?>>ISLAM</option>
                                <option value="KRISTEN" <?= set_select('agama', 'KRISTEN'); ?>>KRISTEN</option>
                                <option value="KATOLIK" <?= set_select('agama', 'KATOLIK'); ?>>KATOLIK</option>
                                <option value="HINDU" <?= set_select('agama', 'HINDU'); ?>>HINDU</option>
                                <option value="BUDDHA" <?= set_select('agama', 'BUDDHA'); ?>>BUDDHA</option>
                                <option value="KONGHUCU" <?= set_select('agama', 'KONGHUCU'); ?>>KONGHUCU</option>
                            </select>
                            <?= form_error('agama', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <!-- Alamat asal -->
                        <div class="mb-3">
                            <label class="form-label">Provinsi Asal (Isi Sesuai KTP)</label>
                            <select id="provinsi" name="provinsi_asal" class="form-select" required></select>
                            <input type="hidden" name="provinsi_nama" id="provinsi_nama">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kabupaten/Kota Asal (Isi Sesuai KTP)</label>
                            <select id="kabupaten" name="kabupaten_asal" class="form-select" required></select>
                            <input type="hidden" name="kabupaten_nama" id="kabupaten_nama">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kecamatan Asal (Isi Sesuai KTP)</label>
                            <select id="kecamatan" name="kecamatan_asal" class="form-select" required></select>
                            <input type="hidden" name="kecamatan_nama" id="kecamatan_nama">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kelurahan Asal (Isi Sesuai KTP)</label>
                            <select id="kelurahan" name="kelurahan_asal" class="form-select" required></select>
                            <input type="hidden" name="kelurahan_nama" id="kelurahan_nama">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">RT</label>
                                <input type="text" name="rt" class="form-control" placeholder="Contoh: 001" value="000">
                            </div>
    
                            <div class="col-md-6 mb-3">
                                <label class="form-label">RW</label>
                                <input type="text" name="rw" class="form-control" placeholder="Contoh: 002" value="000">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Asal (Isi Sesuai KTP)</label>
                            <textarea name="alamat_asal"  rows="5" class="form-control <?= form_error('alamat_asal') ? 'is-invalid' : '' ?>" value="<?= set_value('tempat_lahir'); ?>"  placeholder="Contoh : Jalan Malioboro No.6, Denpasar" required></textarea>
                              <?= form_error('alamat_asal', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                    </div>

                    <!-- KOLOM KANAN -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="usePJLocation">
                                <label class="form-check-label" for="usePJLocation">Gunakan alamat dan lokasi yang sama dengan Penanggung Jawab</label>
                            </div>
                        </div>

                        <div class="location-inputs">
                            <div class="mb-3">
                                <label class="form-label">Lokasi (Klik pada peta)</label>
                                <div id="map" style="height: 300px; z-index: 1; position: relative;"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" class="form-control" readonly>
                                </div>
    
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Alamat Sekarang</label>
                                <textarea name="alamat_sekarang" id="alamat_sekarang" class="form-control" required></textarea>
                                <button type="button" id="search-location" class="btn btn-warning mt-2 w-100"> <i class="fa-solid fa-magnifying-glass"></i> Cari Lokasi</button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Detail</label>
                            <select class="form-select" name="alamat_detail" id="alamat_detail" required>
                                <option value="">-- Pilih Alamat Detail --</option>
                                <?php 
                                    $jalan_options = [
                                        "JL. NUANSA UTAMA II",
                                        "JL. NUANSA UTAMA IV",
                                        "JL. NUANSA UTAMA BARAT",
                                        "JL. NUANSA UTAMA V",
                                        "JL. NUANSA UTAMA VI",
                                        "JL. NUANSA UTAMA VII",
                                        "JL. NUANSA UTAMA VIII",
                                        "JL. NUANSA UTAMA IX",
                                        "JL. NUANSA UTAMA X",
                                        "JL. NUANSA UTAMA XI",
                                        "JL. NUANSA UTAMA XIA",
                                        "JL. NUANSA UTAMA XII",
                                        "JL. NUANSA UTAMA XIII",
                                        "JL. NUANSA UTAMA XIV",
                                        "JL. NUANSA UTAMA XV",
                                        "JL. NUANSA UTAMA XVI",
                                        "JL. NUANSA UTAMA XVII",
                                        "JL. NUANSA UTAMA XVIII",
                                        "JL. NUANSA UTAMA TENGAH",
                                        "JL. NUANSA UTAMA XIX",
                                        "JL. NUANSA UTAMA XXI",
                                        "JL. NUANSA UTAMA XXIII",
                                        "JL. NUANSA UTAMA XXV",
                                        "JL. NUANSA UTAMA XXVII",
                                        "JL. NUANSA UTAMA RAYA"
                                    ];
                                    foreach ($jalan_options as $jalan) {
                                        echo '<option value="'.$jalan.'">'.$jalan.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No Rumah</label>
                            <select class="form-select" name="alamat_no" id="alamat_no" required>
                                <option value="">-- Pilih No Rumah --</option>
                                <?php for ($i = 1; $i <= 40; $i++) {
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                } ?>
                            </select>
                        </div>
                        
                            <div class="mb-3">
                                <label class="form-label">Tujuan</label>
                                <select id="tujuan-select" name="tujuan_pilihan" class="form-select" required>
                                    <option value="">-- Pilih Tujuan --</option>
                                    <option value="Bekerja">Bekerja</option>
                                    <option value="Kuliah">Kuliah</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <input type="hidden" id="tujuan-hidden" name="tujuan">

                            <div class="mb-3" id="tujuan-lainnya" style="display: none;">
                                <label class="form-label">Tuliskan Tujuan Lainnya</label>
                                <textarea id="tujuan-lainnya-text" class="form-control" placeholder="Tulis tujuan lainnya..."></textarea>
                            </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" class="form-control" required value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="penanggung_jawab_id" class="form-label">Penanggung Jawab</label>
                            <select name="penanggung_jawab_id" id="penanggung_jawab_id" class="form-select" required>
                                <option value="">Pilih Penanggung Jawab</option>
                                <?php foreach ($pj as $p): ?>
                                    <option value="<?= $p->id ?>"><?= $p->nama_pj ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="kaling_id" class="form-label">Kepala Lingkungan</label>
                            <select name="kaling_id" id="kaling_id" class="form-select" disabled>
                                <?php foreach ($kaling as $k): ?>
                                    <option value="<?= $k->id ?>" <?= ($k->id == $default_kaling_id) ? 'selected' : '' ?>>
                                        <?= $k->nama ?> - <?= $k->wilayah ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="wilayah_id" class="form-label">Wilayah</label>
                            <select name="wilayah_id" id="wilayah_id" class="form-select" disabled>
                                <?php foreach ($wilayah as $w): ?>
                                    <option value="<?= $w->id ?>" <?= ($w->id == $default_wilayah_id) ? 'selected' : '' ?>>
                                        <?= $w->wilayah ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <input type="hidden" name="kaling_id" value="<?= $default_kaling_id ?>">
                        <input type="hidden" name="wilayah_id" value="<?= $default_wilayah_id ?>">

                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-danger" onclick="window.location.href='<?= base_url('dashboard/penghuni/view') ?>'">Kembali</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('partials/watermark'); ?>

<?php HelperJS::start('scripts'); ?>

        <?php if ($this->session->flashdata('error')): ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?= $this->session->flashdata('error') ?>',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')): ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '<?= $this->session->flashdata('success') ?>',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>
        <?php endif; ?>

        <script>
            let map, marker;
            let mapInitialized = false;

            const select = document.getElementById('tujuan-select');
            const lainnyaDiv = document.getElementById('tujuan-lainnya');
            const lainnyaText = document.getElementById('tujuan-lainnya-text');
            const tujuanHidden = document.getElementById('tujuan-hidden');

            if (select) {
                select.addEventListener('change', function () {
                    if (this.value === 'Lainnya') {
                        lainnyaDiv.style.display = 'block';
                        tujuanHidden.value = lainnyaText.value;
                    } else {
                        lainnyaDiv.style.display = 'none';
                        tujuanHidden.value = this.value;
                    }
                });
            }

            if (lainnyaText) {
                lainnyaText.addEventListener('input', function () {
                    if (select.value === 'Lainnya') {
                        tujuanHidden.value = this.value;
                    }
                });
            }

            function validateImage(input) {
                const file = input.files[0];
                if (!file) return;

                const allowedTypes = ['image/jpeg', 'image/png'];
                const maxSize = 2 * 1024 * 1024; 

                let errorMessage = '';

                if (!allowedTypes.includes(file.type)) {
                    errorMessage = 'Hanya file PNG atau JPG yang diperbolehkan!';
                } else if (file.size > maxSize) {
                    errorMessage = 'Ukuran file tidak boleh lebih dari 2 MB!';
                }

                if (errorMessage) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Gagal',
                        text: errorMessage,
                    }).then(() => {
                        input.value = ''; 
                    });
                }
            }
            
            function showPreviewImage(inputId, imgPreviewId, modalId) {
                const input = document.getElementById(inputId);
                if (!input || !input.files || !input.files[0]) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'File tidak ditemukan',
                        text: 'Pilih gambar terlebih dahulu.'
                    });
                    return;
                }

                const file = input.files[0];

                if (!file.type.startsWith('image/')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File bukan gambar',
                        text: 'Hanya file gambar yang bisa dipreview.'
                    });
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewImg = document.getElementById(imgPreviewId);
                    previewImg.src = e.target.result;

                    const modalEl = document.getElementById(modalId);
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                };
                reader.readAsDataURL(file);
            }



            function initMap(lat = -6.2088, lng = 106.8456) {
                if (mapInitialized) {
                    return;
                }

                const mapContainer = document.getElementById('map');
                if (!mapContainer) {
                    return;
                }

                map = L.map('map').setView([lat, lng], 12);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap'
                }).addTo(map);

                marker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(map);

                map.on('click', async function(e) {
                    const { lat, lng } = e.latlng;
                    marker.setLatLng([lat, lng]);
                    $('#latitude').val(lat);
                    $('#longitude').val(lng);

                    const address = await getAddressFromCoordinates(lat, lng);
                    $('#alamat_sekarang').val(address);
                });

                marker.on('dragend', async function() {
                    const { lat, lng } = marker.getLatLng();
                    $('#latitude').val(lat);
                    $('#longitude').val(lng);
                    const address = await getAddressFromCoordinates(lat, lng);
                    $('#alamat_sekarang').val(address);
                });

                mapInitialized = true;
            }

            async function getAddressFromCoordinates(lat, lng) {
                try {
                    const res = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
                    const data = await res.json();
                    return data.display_name || '';
                } catch (error) {
                    console.error('Error fetching address:', error);
                    return '';
                }
            }

            function getCurrentLocationAndAddress() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(async function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        $('#latitude').val(lat);
                        $('#longitude').val(lng);
                        
                        if (marker) {
                            marker.setLatLng([lat, lng]);
                            map.setView([lat, lng], 15);
                        }

                        const address = await getAddressFromCoordinates(lat, lng);
                        $('#alamat_sekarang').val(address);
                    }, function(error) {
                        console.error('Geolocation error:', error);
                    });
                }
            }

            async function searchLocation() {
                const address = $('#alamat_sekarang').val();
                if (!address) {
                    alert('Masukkan alamat terlebih dahulu');
                    return;
                }

                try {
                    const res = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&limit=1`);
                    const data = await res.json();

                    if (data && data.length > 0) {
                        const { lat, lon } = data[0];
                        marker.setLatLng([lat, lon]);
                        map.setView([lat, lon], 15);
                        $('#latitude').val(parseFloat(lat).toFixed(8));
                        $('#longitude').val(parseFloat(lon).toFixed(8));
                        $('#alamat_sekarang').val(data[0].display_name);
                    } else {
                        alert('Alamat tidak ditemukan');
                    }
                } catch (err) {
                    console.error("Error searching location:", err);
                    alert('Gagal mencari lokasi');
                }
            }

            async function scanKTP() {
                const input = document.getElementById('ktp');
                if (!input.files.length) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Gambar KTP belum dipilih',
                        text: 'Pilih gambar KTP dulu.'
                    });
                    return;
                }

                const file = input.files[0];
                const reader = new FileReader();

                $('#loading-ocr').show();

                reader.onload = async function() {
                    try {
                        const ocrResult = await Tesseract.recognize(
                            reader.result,
                            'ind', {
                                logger: () => {}
                            }
                        );

                        const text = ocrResult.data.text;

                        const nik = text.match(/NIK\s*:?\s*(\d{16})/i);
                        const nama = text.match(/Nama\s*:?\s*(.+)/i);
                        const jk = text.match(/Jenis Kelamin\s*:?\s*(\w+)/i);
                        const ttlParsed = parseTempatTanggalLahir(text);

                        if (nik) $('input[name="nik"]').val(nik[1]);
                        if (nama) $('input[name="nama"]').val(nama[1].trim());
                        if (ttlParsed.tempat) $('input[name="tempat_lahir"]').val(ttlParsed.tempat);
                        if (ttlParsed.tanggal) $('input[name="tanggal_lahir"]').val(ttlParsed.tanggal);
                        if (jk) $('select[name="jk"]').val(jk[1].toUpperCase());
                    } catch (error) {
                        console.error('OCR Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memproses gambar KTP'
                        });
                    } finally {
                        $('#loading-ocr').hide();
                    }
                };

                reader.readAsDataURL(file);
            }

            function parseTempatTanggalLahir(text) {
                const ttlRegex = /(Tempat\s*\/?\s*Tgl\s*Lahir|TTL)\s*[:\-]?\s*(.+)/i;
                const match = text.match(ttlRegex);
                if (!match) return { tempat: '', tanggal: '' };

                let ttlLine = match[2].trim();
                let tempat = '', tanggal = '';

                if (ttlLine.includes(',')) {
                    const parts = ttlLine.split(',');
                    tempat = parts[0].trim();
                    tanggal = formatTanggal(parts[1]);
                } else {
                    const dateRegex = /(\d{1,2})[^\d]?(\d{1,2})[^\d]?(\d{4})/;
                    const dateMatch = ttlLine.match(dateRegex);
                    if (dateMatch) {
                        tempat = ttlLine.replace(dateRegex, '').trim();
                        tanggal = `${dateMatch[3]}-${dateMatch[2].padStart(2, '0')}-${dateMatch[1].padStart(2, '0')}`;
                    }
                }

                return { tempat, tanggal };
            }

            function formatTanggal(input) {
                const parts = input.replace(/[^\d]/g, ' ').trim().split(/\s+/);
                if (parts.length >= 3) {
                    let [day, month, year] = parts;
                    return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
                }
                return '';
            }

            function loadWilayah() {
                $.getJSON("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json", function(data) {
                    $('#provinsi').html('<option value="">Pilih Provinsi</option>');
                    $.each(data, function(i, p) {
                        $('#provinsi').append(`<option value="${p.name}">${p.name}</option>`);
                    });
                });

                $('#provinsi').change(function() {
                    const selectedProvName = $(this).val();
                    $('#provinsi_nama').val(selectedProvName);
                    
                    if (!selectedProvName) return;
                    
                    $.getJSON("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json", function(provinces) {
                        const province = provinces.find(p => p.name === selectedProvName);
                        if (province) {
                            $.getJSON(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${province.id}.json`, function(data) {
                                $('#kabupaten').html('<option value="">Pilih Kabupaten</option>');
                                $('#kecamatan').html('<option value="">Pilih Kecamatan</option>');
                                $('#kelurahan').html('<option value="">Pilih Kelurahan</option>');
                                $.each(data, function(i, k) {
                                    $('#kabupaten').append(`<option value="${k.name}" data-id="${k.id}">${k.name}</option>`);
                                });
                            });
                        }
                    });
                });

                $('#kabupaten').change(function() {
                    const selectedKabId = $(this).find(':selected').data('id');
                    const selectedKabName = $(this).val();
                    $('#kabupaten_nama').val(selectedKabName);
                    
                    if (selectedKabId) {
                        $.getJSON(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${selectedKabId}.json`, function(data) {
                            $('#kecamatan').html('<option value="">Pilih Kecamatan</option>');
                            $('#kelurahan').html('<option value="">Pilih Kelurahan</option>');
                            $.each(data, function(i, kc) {
                                $('#kecamatan').append(`<option value="${kc.name}" data-id="${kc.id}">${kc.name}</option>`);
                            });
                        });
                    }
                });

                $('#kecamatan').change(function() {
                    const selectedKecId = $(this).find(':selected').data('id');
                    const selectedKecName = $(this).val();
                    $('#kecamatan_nama').val(selectedKecName);
                    
                    if (selectedKecId) {
                        $.getJSON(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${selectedKecId}.json`, function(data) {
                            $('#kelurahan').html('<option value="">Pilih Kelurahan</option>');
                            $.each(data, function(i, kl) {
                                $('#kelurahan').append(`<option value="${kl.name}">${kl.name}</option>`);
                            });
                        });
                    }
                });

                $('#kelurahan').change(function() {
                    $('#kelurahan_nama').val($(this).val());
                });
            }

            $(document).ready(function() {
                initMap();
                loadWilayah();
                getCurrentLocationAndAddress();

                $('#usePJLocation').change(function() {
                    if ($(this).is(':checked')) {
                    const pj_id = $('#penanggung_jawab_id').val();
                    if (!pj_id) {
                        Swal.fire('Perhatian!', 'Pilih Penanggung Jawab terlebih dahulu!', 'warning');
                        $(this).prop('checked', false);
                        return;
                    }

                   $.ajax({
                        url: '<?= base_url('dashboard/pj/getPJLocation') ?>',
                        method: 'GET',
                        data: { pj_id: pj_id },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                const { latitude, longitude, alamat_detail, alamat_no } = response.data;

                                if (marker && map) {
                                    marker.setLatLng([latitude, longitude]);
                                    map.setView([latitude, longitude], 15);
                                }

                                $('#latitude').val(latitude);
                                $('#longitude').val(longitude);

                                let alamatLengkapPJ = alamat_detail || '';
                                if (alamat_no) {
                                    alamatLengkapPJ += ', No. ' + alamat_no;
                                }
                                $('#alamat_sekarang').val(alamatLengkapPJ);

                                $('.location-inputs input, .location-inputs textarea, #alamat_detail, #alamat_no').prop('readonly', true);
                                $('#search-location').prop('disabled', true);

                                if (map) map.dragging.disable();
                                if (marker) marker.dragging.disable();

                            } else {
                                Swal.fire('Gagal', response.message || 'Gagal mengambil lokasi Penanggung Jawab.', 'error');
                                $('#usePJLocation').prop('checked', false);
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Terjadi kesalahan saat menghubungi server.', 'error');
                            $('#usePJLocation').prop('checked', false);
                        }
                    });

                    
                    } else {
                        $('.location-inputs input, .location-inputs textarea').prop('readonly', false);
                        if (map) {
                            map.dragging.enable();
                            map.touchZoom.enable();
                            map.doubleClickZoom.enable();
                            map.scrollWheelZoom.enable();
                            map.boxZoom.enable();
                            map.keyboard.enable();
                        }
                        if (marker) {
                            marker.dragging.enable();
                        }
                        getCurrentLocationAndAddress();
                    }
                });

                $('#penanggung_jawab_id').change(function() {
                    $('#usePJLocation').prop('checked', false).trigger('change');
                });

                $('#search-location').on('click', searchLocation);
            });
        </script>

<?php HelperJS::end('scripts'); ?>

<?php $this->load->view('partials/footer'); ?>

