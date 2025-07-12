<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Surat Pengantar</title>
    <style>
        @page {
            size: A4;
            margin: 2.5cm;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 11pt;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .kop-table {
            width: 100%;
            border-bottom: 2px solid black;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-spacing: 0;
            table-layout: fixed;
            text-align: center;
        }

        .kop-table {
            width: 100%;
            border-bottom: 2px solid black;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-spacing: 0;
            table-layout: fixed;
            text-align: center;
        }

        .kop-table td {
            vertical-align: middle;
            padding: 0;
        }

        .header-text {
            width: 50%;
            text-align: center;
            padding: 0;
            line-height: 1;
        }

        .logo-cell {
            width: 20%;
            text-align: right;
            padding: 0;
        }

        .logo-cell img {
            width: 110px;
            height: auto;
            display: inline-block;
            vertical-align: middle;
            margin-right: 30px;

        }

        .header-text h3 {
            margin: 1px 0;
            text-transform: uppercase;
            font-weight: bold;
            margin-right: 30px;
        }

        .header-text h4 {
            font-size: 12pt;
            margin: 1px 0;
            text-transform: uppercase;
            font-weight: bold;
            margin-right: 30px;
        }

        .header-text p {
            margin: 1px 0;
            font-size: 10pt;
            margin-right: 30px;

        }

        .title {
            text-align: center;
            margin: 20px 0 10px;
        }

        .title h2 {
            text-decoration: underline;
            font-size: 14pt;
            margin-bottom: 5px;
        }

        .title p {
            margin: 0;
            font-size: 12pt;
        }

        .content {
            text-align: justify;
            margin-top: 10px;
        }

        table.biodata {
            margin: 15px 0;
            width: 100%;
        }

        table.biodata td {
            padding: 4px 20px 4px 0;
            vertical-align: top;
        }

        .durasi {
            margin: 15px 0;
            font-weight: bold;
        }

        ol {
            margin: 15px 0;
            padding-left: 20px;
        }

        ol li {
            margin-bottom: 10px;
            text-align: justify;
        }

        .signature {
            margin-top: 50px;
            width: 100%;
            display: flex;
            justify-content: space-between;
        }

        .signature-left {
            text-align: center;
            width: 45%;
        }

        .signature-right {
            text-align: center;
            width: 45%;
        }
    </style>
    <?php
    function tanggal_indonesia($tanggal = null)
    {
        $bulan = array(
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        );
        $tanggal = $tanggal ? strtotime($tanggal) : time();
        $tgl = date('d', $tanggal);
        $bln = $bulan[(int)date('m', $tanggal)];
        $thn = date('Y', $tanggal);
        return "$tgl $bln $thn";
    }
    ?>
</head>

<body>
    <table class="kop-table">
        <tr>
            <td class="logo-cell">
                <img src="<?= base_url('assets/images/logos/icon.png') ?>" alt="Logo">
            </td>
            <td class="header-text">
                <h3>PEMERINTAH KECAMATAN KUTA SELATAN</h3>
                <h4>KELURAHAN JIMBARAN</h4>
                <p>LINGKUNGAN / BR. TAMAN GRIYA</p>
                <p>PERUMAHAN NUANSA UTAMA</p>
                <p>Jalan Nuansa Utama, Perumahan No. 14, Jimbaran, Kuta Selatan, Bali</p>
            </td>
        </tr>
    </table>

    <div class="title">
        <h2>SURAT PENGANTAR</h2>
        <p>Nomor: <?php echo $nomor_surat; ?></p>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini kami Ketua Perumahan Nuansa Utama, Lingkungan / Banjar Taman Griya, Kelurahan Jimbaran, Kecamatan Kuta Selatan, Kabupaten Badung dengan ini menerangkan bahwa:</p>
        <table class="biodata">
            <tr>
                <td width="180px">Nama</td>
                <td width="10">:</td>
                <td><?php echo $penghuni->nama_lengkap; ?></td>
            </tr>
            <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>:</td>
                <td><?php echo $penghuni->tempat_lahir . ', ' . date('d/m/Y', strtotime($penghuni->tanggal_lahir)); ?></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td><?php echo $penghuni->jenis_kelamin; ?></td>
            </tr>
            <tr>
                <td>Alamat Asal</td>
                <td>:</td>
                <td>
                    <?php echo $penghuni->alamat_asal; ?>
                    <?php echo $penghuni->kecamatan_asal; ?>
                    <?php echo $penghuni->kabupaten_asal; ?>
                    <?php echo $penghuni->provinsi_asal; ?>
                </td>
            </tr>
            <tr>
                <td>Alamat Sekarang</td>
                <td>:</td>
                <td><?php echo $penghuni->alamat_sekarang;?></td>
            </tr>
            <tr>
                <td>Wilayah</td>
                <td>:</td>
                <td><?php echo $wilayah->wilayah; ?></td>
            </tr>

        </table>
        <p>
            Memang benar bahwa orang tersebut di atas tinggal di wilayah perumahan kami. Adapun Surat Pengantar ini kami berikan untuk keperluan : <b><?php echo $keperluan; ?></b>
        </p>
        <p>
            Demikian Surat ini kami berikan agar dapat digunakan dimana perlu, atas perhatian dan kerjasamanya kami ucapkan terima kasih.
        </p>
        
        <table style="width: 100%; margin-top: 70px; text-align: center;">
            <tr>
                <td style="width: 50%;">

                </td>
                <td style="width: 50%; padding-left: 100px;">
                    Taman Griya, <?= tanggal_indonesia() ?><br>
                    Hormat Kami,<br>
                    Ketua Perumahan Nuansa Utama <br><br><br><br>
                    <u><?php echo $kaling->nama; ?></u>
                </td>
            </tr>
        </table>

</body>

</html>