<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Semua Pendatang Pada Perumahan Nuansa Utama</title>
    <style>
        @page {
            size: legal landscape;
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
            width: 30%;
            text-align: right;
            padding: 0;
        }

        .logo-cell img {
            width: 110px;
            height: auto;
            display: inline-block;
            vertical-align: middle;
        }

        .header-text h3 {
            margin: 1px 0;
            text-transform: uppercase;
            font-weight: bold;
            margin-right: 300px;
        }

        .header-text h4 {
            font-size: 12pt;
            margin: 1px 0;
            text-transform: uppercase;
            font-weight: bold;
            margin-right: 300px;
        }

        .header-text p {
            margin: 1px 0;
            font-size: 10pt;
            margin-right: 300px;
        }

        .content {
            margin-top: 20px;
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12pt;
            text-transform: uppercase;
            font-weight: bold;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data th,
        .data td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 10pt;
        }

        .data th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .data td.center {
            text-align: center;
        }

        .pj-info {
            background-color: #f9f9f9;
            font-weight: bold;
        }

        .data td.photo-cell {
            text-align: center;
            width: 90px;
            height: 90px;
            padding: 8px;
            position: relative;
            overflow: hidden;
        }

        .foto-profile {
            width: auto;
            height: 60px;
            max-width: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            margin: 0 auto;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            margin-left: 950px;
        }

        .footer .tanggal {
            margin-bottom: 60px;
            text-align: center;
        }

        .footer .ttd-nama {
            text-align: center;
            margin-top: 50px;
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

    <div class="content">
        <div class="title">
            LAPORAN SEMUA PENDATANG PADA PERUMAHAN NUANSA UTAMA<br>
            Per tanggal: <?= date('d-m-Y', strtotime($tanggal)) ?>
        </div>

        <table class="data">
            <thead>
                <tr>
                    <th width="4%">No</th>
                    <th width="8%">Foto</th>
                    <th width="11%">NIK</th>
                    <th width="13%">Nama</th>
                    <th width="11%">Tempat & Tanggal Lahir</th>
                    <th width="6%">Agama</th>
                    <th width="8%">No Hp</th>
                    <th width="12%">Alamat Asal</th>
                    <th width="12%">Alamat Sekarang</th>
                    <th width="10%">Tujuan</th>
                    <th width="10%">Status Pendatang</th>
                    <th width="13%">Penanggung Jawab / No Wa</th>
                    <th width="8%">Tanggal Masuk/Keluar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($all_pendatang as $row):
                ?>
                    <tr>
                        <td class="center"><?= $no++ ?></td>
                        <td class="photo-cell">
                            <?php if ($row->foto_profil): ?>
                                <img src="<?= base_url('uploads/penghuni/' . $row->foto_profil) ?>" alt="Foto Profil" class="foto-profile">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="center"><?= $row->nik ?></td>
                        <td class="center"><?= $row->nama_lengkap ?></td>
                        <td class="center"><?= $row->tempat_lahir . ', ' . date('d-m-Y', strtotime($row->tanggal_lahir)) ?></td>
                        <td class="center"><?= $row->agama ?? '-' ?></td>
                        <td class="center"><?= $row->no_hp ?? '-' ?></td>
                        <td class="center"><?= $row->alamat_asal ?></td>

                        <td class="center"><?= $row->alamat_sekarang ?></td>
                        <td class="center"><?= $row->tujuan ?></td>
                        <td class="center"><?= $row->status_penghuni ?></td>
                        <td><?= $row->pj_nama ?? '-' ?> /<br>No. WA: <?= $row->pj_no_hp ?? '-' ?></td>
                        <td class="center"><?= date('d-m-Y', strtotime($row->tanggal_masuk)) ?> / <br>
                            <?= $row->tanggal_keluar ? date('d-m-Y', strtotime($row->tanggal_keluar)) : '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="footer">
            <div class="tanggal">
                Taman Griya, <?= tanggal_indonesia() ?>
                <p>Ketua Perumahan Nuansa Utama</p>
            </div>
            <div class="ttd-nama">
                <u>
                    <?= $kaling->nama ?>
                </u>
            </div>
        </div>
    </div>
</body>

</html>