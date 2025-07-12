<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions/
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method/ The segments in a
| URL normally follow this pattern:
|
|	example/com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL/
|
| Please see the user guide for complete details:
|
|	https://codeigniter/com/userguide3/general/routing/html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data/ In the above example, the "welcome" class
| would be loaded/
|
|	$route['404_override'] = '';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route/
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes/ '-' isn't a valid
| class or method name character, so it requires translation/
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments/
|
| Examples:	my-controller/index	-> my_controller/index
|		    my-controller/my-method	-> my_controller/my_method
*/

$route['404_override'] = 'ErrorController/error_404';
$route['dashboard/error'] = 'ErrorController/error_403';
$route['translate_uri_dashes'] = FALSE;

// Auth dan Loginnya
$route['default_controller'] = 'AuthController/login';
$route['auth'] = 'AuthController/login';
$route['auth/do_login'] = 'AuthController/do_login';
$route['auth/logout'] = 'AuthController/logout';

// Pendaftaran Penanggung Jawab + Token Logic
$route['daftar/submit_pj'] = 'DaftarPJController/submit_pj';
$route['shortlink'] = 'DaftarPJController/generate_link_view';
$route['daftar/link_pj'] = 'DaftarPJController/link_pj';
$route['daftar/pj/(:any)'] = 'DaftarPJController/pj/$1';

// Route Dashboard
$route['dashboard'] = 'HomeController/index';

// Routes untuk Admin
$route['dashboard/admin/view'] = 'AdminController/view';
$route['dashboard/admin/create'] = 'AdminController/create';
$route['dashboard/admin/store'] = 'AdminController/store';
$route['dashboard/admin/edit/(:any)'] = 'AdminController/edit/$1';
$route['dashboard/admin/update/(:any)'] = 'AdminController/update/$1';
$route['dashboard/admin/delete/(:any)'] = 'AdminController/delete/$1';

// Routes untuk Kaling
$route['dashboard/kaling/view'] = 'KalingController/view';
$route['dashboard/kaling/create'] = 'KalingController/create';
$route['dashboard/kaling/store'] = 'KalingController/store';
$route['dashboard/kaling/edit/(:any)'] = 'KalingController/edit/$1';
$route['dashboard/kaling/update/(:any)'] = 'KalingController/update/$1';
$route['dashboard/kaling/delete/(:any)'] = 'KalingController/delete/$1';

// Routes untuk Penanggung Jawab
$route['dashboard/pj/view'] = 'PJController/view';
$route['dashboard/pj/validation'] = 'PJController/verifikasi_pj';
$route['dashboard/pj/create'] = 'PJController/create';
$route['dashboard/pj/store'] = 'PJController/store';
$route['dashboard/pj/editdata'] = 'PJController/edit_pj';
$route['dashboard/pj/detail/(:any)'] = 'PJController/detail_admin/$1';
$route['dashboard/pj/edit/(:any)'] = 'PJController/edit/$1';
$route['dashboard/pj/update/(:any)'] = 'PJController/update/$1';
$route['dashboard/pj/delete/(:any)'] = 'PJController/delete/$1';
$route['dashboard/pj/update_pj/(:any)'] = 'PJController/update_pj/$1';

// Routes untuk Wilayah
$route['dashboard/wilayah/view'] = 'WilayahController/view';
$route['dashboard/wilayah/create'] = 'WilayahController/create';
$route['dashboard/wilayah/store'] = 'WilayahController/store';
$route['dashboard/wilayah/edit/(:any)'] = 'WilayahController/edit/$1';
$route['dashboard/wilayah/update/(:any)'] = 'WilayahController/update/$1';
$route['dashboard/wilayah/delete/(:any)'] = 'WilayahController/delete/$1';

// Routes untuk Penghuni
$route['dashboard/penghuni/view'] = 'PenghuniController/index';
$route['dashboard/penghuni/viewpj'] = 'PenghuniController/index_pj';
$route['dashboard/penghuni/create/pj'] = 'PenghuniController/create_pj';
$route['dashboard/penghuni/store/pj'] = 'PenghuniController/store_pj';
$route['dashboard/penghuni/edit/pj/(:any)'] = 'PenghuniController/edit_pj/$1';
$route['dashboard/penghuni/update/pj/(:any)'] = 'PenghuniController/update_pj/$1';
$route['dashboard/penghuni/details/admin/(:any)'] = 'PenghuniController/detail_admin/$1';
$route['dashboard/penghuni/details/pj/(:any)'] = 'PenghuniController/detail_pj/$1';
$route['dashboard/penghuni/create/admin'] = 'PenghuniController/create_admin';
$route['dashboard/penghuni/store/admin'] = 'PenghuniController/store_admin';
$route['dashboard/penghuni/edit/admin/(:any)'] = 'PenghuniController/edit_admin/$1';
$route['dashboard/penghuni/update/admin/(:any)'] = 'PenghuniController/update_admin/$1';
$route['dashboard/penghuni/verifikasi/(:any)/(:any)'] = 'PenghuniController/verifikasi/$1/$2';
$route['dashboard/penghuni/delete/(:any)'] = 'PenghuniController/delete/$1';
$route['dashboard/pj/getPJLocation'] = 'PJController/getPJLocation';
$route['dashboard/penghuni/filterTerverifikasiByPJ'] = 'PenghuniController/filterTerverifikasiByPJ';
$route['dashboard/penghuni/nonaktifkan_status/(:any)'] = 'PenghuniController/nonaktifkan_status/$1';
$route['dashboard/penghuni/aktifkan_status/(:any)'] = 'PenghuniController/aktifkan_status/$1';

// Routes untuk Surat
$route['dashboard/surat/view'] = 'SuratController/index';
$route['dashboard/surat/view/pendatang'] = 'SuratController/surat_pendatang';
$route['dashboard/surat/view/anggota'] = 'SuratController/surat_anggota_keluarga';
$route['dashboard/surat/get_penghuni_by_pj/(:num)'] = 'SuratController/get_penghuni_by_pj/$1';
$route['dashboard/surat/get_anggota_by_pj/(:any)'] = 'SuratController/get_anggota_by_pj/$1';
$route['dashboard/surat/print/anggota/(:any)'] = 'SuratController/cetak_pengantar_anggota/$1';
$route['dashboard/surat/cetak_langsung_anggota'] = 'SuratController/cetak_langsung_anggota';
$route['dashboard/surat/cetak_langsung_pj'] = 'SuratController/cetak_langsung_pj';
$route['dashboard/surat/cetak_langsung_pendatang'] = 'SuratController/cetak_langsung_pendatang';
$route['dashboard/surat/print/pj/(:any)'] = 'SuratController/cetak_pengantar_pj/$1';
$route['dashboard/surat/print/pendatang/(:any)'] = 'SuratController/cetak_pengantar_pendatang/$1';
$route['dashboard/surat/verifikasi'] = 'SuratController/verifikasi';
$route['dashboard/surat/ajukan_pendatang'] = 'SuratController/ajukan_surat_pendatang';
$route['dashboard/surat/ajukan_anggota'] = 'SuratController/ajukan_surat_anggota';
$route['dashboard/surat/ajukan_surat_anggota'] = 'SuratController/ajukan_surat_anggota';
$route['dashboard/surat/ajukan_pj_sendiri'] = 'SuratController/ajukan_surat_pj_sendiri';
$route['dashboard/surat/delete'] = 'SuratController/delete_surat';

// Routes untuk Laporan
$route['dashboard/report/view'] = 'LaporanController/index';
$route['dashboard/reportdetailpj'] = 'LaporanController/report_pj';
$route['dashboard/reportpendatang/(:any)'] = 'LaporanController/report_pendatang/$1';
$route['dashboard/reportall'] = 'LaporanController/report_all';

// Routes untuk Keperluan Surat
$route['keperluan'] = 'KeperluanController/index';
$route['keperluan/store'] = 'KeperluanController/store';
$route['dashboard/surat/ajukan'] = 'SuratController/ajukan_surat';
$route['dashboard/surat/ajukan_surat_pendatang_pj'] = 'suratcontroller/ajukan_surat_pendatang_pj';