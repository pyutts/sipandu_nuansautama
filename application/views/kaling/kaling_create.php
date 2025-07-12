<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/navbar'); ?>

<div class="container-fluid">
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Tambah Data Kepala Lingkungan</h5>
                <form action="<?= base_url('dashboard/kaling/store') ?>" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control <?= form_error('username') ? 'is-invalid' : '' ?>" id="username" value="<?= set_value('username'); ?>" required>
                        <?= form_error('username', '<div class="invalid-feedback">', '</div>'); ?>
                    </div>

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control <?= form_error('nama') ? 'is-invalid' : '' ?>" value="<?= set_value('nama'); ?>" required>
                        <?= form_error('nama', '<div class="invalid-feedback">', '</div>'); ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control <?= form_error('password') ? 'is-invalid' : '' ?>" id="inputPassword" required>
                            <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                <i class="bi bi-eye-slash" id="iconPassword"></i>
                            </span>
                        </div>
                        <?= form_error('password', '<div class="invalid-feedback d-block">', '</div>'); ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No Handphone</label>
                        <input type="number" name="no_hp" 
                            class="form-control <?= form_error('no_hp') ? 'is-invalid' : '' ?>" 
                            value="<?= set_value('no_hp'); ?>" required>

                        <?= form_error('no_hp', '<div class="invalid-feedback d-block">', '</div>'); ?>
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

                       
                        <input type="hidden" name="wilayah_id" value="<?= $default_wilayah_id ?>">


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
                            <span class="invalid-feedback"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No Rumah</label>
                            <select class="form-select" name="alamat_no" id="alamat_no" required>
                                <option value="">-- Pilih No Rumah --</option>
                                <?php for ($i = 1; $i <= 40; $i++) {
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                } ?>
                            </select>
                            <span class="invalid-feedback"></span>
                        </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Maps</label>
                        <textarea name="alamat" id="alamat" class="form-control" required></textarea>
                        <button type="button" id="search-location" class="btn btn-warning mt-2 w-100"> <i class="fa-solid fa-magnifying-glass"></i> Cari Lokasi</button>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Lokasi (Klik pada peta)</label>
                        <div id="map" style="height: 300px; z-index: 1; position: relative;"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Latitude</label>
                        <input type="text" name="latitude" id="latitude" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Longitude</label>
                        <input type="text" name="longitude" id="longitude" class="form-control" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button class="btn btn-danger" onclick="window.location.href='<?= base_url('dashboard/kaling/view') ?>'">Kembali</button>

                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('partials/watermark'); ?>

<?php HelperJS::start('scripts'); ?>
<script>
    $(document).ready(function() {
        initMap();
        getCurrentLocationAndAddress();
    });

    let map, marker;

    async function getAddressFromCoordinates(lat, lng) {
        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
            const data = await res.json();
            return data.display_name || '';
        } catch (err) {
            console.error("Error getting address:", err);
            return '';
        }
    }

    async function searchLocation() {
        const address = $('#alamat').val();
        if (!address) {
            alert('Masukkan alamat terlebih dahulu');
            return;
        }

        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&limit=1`);
            const data = await res.json();

            if (data && data.length > 0) {
                const {
                    lat,
                    lon
                } = data[0];
                marker.setLatLng([lat, lon]);
                map.setView([lat, lon], 15);
                $('#latitude').val(parseFloat(lat).toFixed(8));
                $('#longitude').val(parseFloat(lon).toFixed(8));
                $('#alamat').val(data[0].display_name);
            } else {
                alert('Alamat tidak ditemukan');
            }
        } catch (err) {
            console.error("Error searching location:", err);
            alert('Gagal mencari lokasi');
        }
    }

    function initMap(lat = -8.6726, lng = 115.2088) {
        try {
            map = L.map('map').setView([lat, lng], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            marker = L.marker([lat, lng], {
                draggable: true
            }).addTo(map);

            map.on('click', async function(e) {
                const {
                    lat,
                    lng
                } = e.latlng;
                marker.setLatLng([lat, lng]);
                $('#latitude').val(parseFloat(lat).toFixed(8));
                $('#longitude').val(parseFloat(lng).toFixed(8));

                const address = await getAddressFromCoordinates(lat, lng);
                $('#alamat').val(address);
            });

            marker.on('dragend', async function() {
                const {
                    lat,
                    lng
                } = marker.getLatLng();
                $('#latitude').val(parseFloat(lat).toFixed(8));
                $('#longitude').val(parseFloat(lng).toFixed(8));
                const address = await getAddressFromCoordinates(lat, lng);
                $('#alamat').val(address);
            });
        } catch (err) {
            console.error("Error initializing map:", err);
            alert('Gagal menginisialisasi peta');
        }
    }

    function getCurrentLocationAndAddress() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                async function(position) {
                        try {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            $('#latitude').val(parseFloat(lat).toFixed(8));
                            $('#longitude').val(parseFloat(lng).toFixed(8));

                            if (marker && map) {
                                marker.setLatLng([lat, lng]);
                                map.setView([lat, lng], 15);
                            }

                            const address = await getAddressFromCoordinates(lat, lng);
                            $('#alamat').val(address);
                        } catch (err) {
                            console.error("Error setting current location:", err);
                        }
                    },
                    function(error) {
                        console.error("Geolocation error:", error);
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                alert("Izin akses lokasi ditolak");
                                break;
                            case error.POSITION_UNAVAILABLE:
                                alert("Informasi lokasi tidak tersedia");
                                break;
                            case error.TIMEOUT:
                                alert("Waktu permintaan lokasi habis");
                                break;
                            default:
                                alert("Terjadi kesalahan saat mengambil lokasi");
                        }
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
            );
        } else {
            alert("Browser Anda tidak mendukung geolokasi");
        }

        $('#search-location').on('click', async function() {
            const address = $('#alamat').val();
            if (!address) {
                alert('Masukkan alamat terlebih dahulu');
                return;
            }

            try {
                const res = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&limit=1`);
                const data = await res.json();

                if (data && data.length > 0) {
                    const {
                        lat,
                        lon
                    } = data[0];
                    marker.setLatLng([lat, lon]);
                    map.setView([lat, lon], 15);
                    $('#latitude').val(parseFloat(lat).toFixed(8));
                    $('#longitude').val(parseFloat(lon).toFixed(8));
                } else {
                    alert('Alamat tidak ditemukan');
                }
            } catch (err) {
                console.error("Error searching location:", err);
                alert('Gagal mencari lokasi');
            }
        });

    $('#togglePassword').on('click', function() {
        const passwordField = $('#inputPassword');
        const icon = $('#iconPassword');
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        }
    });
    <?php if ($this->session->flashdata('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: '<?= $this->session->flashdata('success'); ?>',
            showConfirmButton: true,
            confirmButtonText: 'OK'
        });
    <?php endif; ?>
    }
</script>
<?php HelperJS::end('scripts'); ?>

<?php $this->load->view('partials/footer'); ?>
