<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#0d6efd">
  <meta name="author" content="Jurusan Teknologi Informasi Politeknik Negeri Bali">
  <title><?= isset($title) ? $title : ""; ?></title>
  <link rel="manifest" href="<?= base_url('manifest.json') ?>">
  <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/images/logos/icon.png'); ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/css/styles.min.css'); ?>" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
  <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<style>
  @media (max-width: 576px) {
    #notifList {
      width: 300px !important;
      right: 0 !important;
      left: auto !important;
      border-radius: 12px !important;
      top: 60px !important;
    }

    #notifList .card-body {
      padding: 0.75rem !important;
      flex-direction: column;
      align-items: flex-start;
    }

    #notifList .btn-delete-notif {
      margin-top: 0.5rem;
      align-self: flex-end;
    }

    #notifList .fw-bold {
      font-size: 0.9rem;
    }

    #notifList .small {
      font-size: 0.75rem;
    }

    #notifList li {
      padding: 0.5rem 1rem !important;
    }

    #notifList .card-body > div {
      max-width: 240px;
      word-wrap: break-word;
    }
  }
</style>

<body>
  