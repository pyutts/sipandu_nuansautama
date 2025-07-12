<body class="container mt-5">
    <div class="d-flex justify-content-center align-items-center flex-column text-center">
        <a href="<?= base_url('shortlink') ?>" class="text-nowrap logo-img d-block py-3">
            <img src="<?= base_url('assets/images/logos/icon_full.png'); ?>" width="300" alt="">
        </a>
        <h2>Short Link Pendaftaran Penanggung Jawab</h2>
        <p>Silahkan untuk menekan tombol <b>dibawah ini</b> untuk menuju ke link penanggung jawab</p>
        <div class="mt-3">
            <button id="tombolLink" class="btn btn-primary">Klik Disini</button>
        </div>
        <div class="mt-3">
            <p id="result" class="fw-bold"></p>
            <p id="countdown"></p>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#tombolLink").click(function() {
                $.getJSON("<?= base_url('daftar/link_pj') ?>", function(response) {
                    const link = response.link;
                    let count = 5;

                    function countdown() {
                        if (count > 0) {
                            $("#countdown").text(`Mengalihkan dalam ${count} detik...`);
                            count--;
                            setTimeout(countdown, 1000);
                        } else {
                            window.location.href = link;
                        }
                    }
                    countdown();
                });
            });
        });
    </script>
</body>