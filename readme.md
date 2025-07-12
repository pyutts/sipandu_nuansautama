# SIPANDU Nuansa Utama

Sistem Informasi Pendataan Penduduk (**SIPANDU**) berbasis web untuk lingkungan **Nuansa Utama**, dikembangkan sebagai bagian dari kegiatan **Project Based Learning (PBL)** di **Politeknik Negeri Bali**.

Proyek ini bertujuan untuk membantu kepala lingkungan dan warga dalam melakukan pendataan penduduk secara digital, efisien, dan real-time.

---

## 📌 Fitur Utama

- ✅ Autentikasi Pengguna (Admin & Kepala Lingkungan)
- ✅ Manajemen Data Penduduk (Pendatang, Penanggung Jawab, Warga)
- ✅ Validasi Data oleh Kepala Lingkungan
- ✅ Peta Lokasi Rumah menggunakan Leaflet.js
- ✅ Filter dan Pencarian Data (Select2 & DataTables)
- ✅ Export Laporan ke PDF (Dompdf)
- ✅ Notifikasi Real-time menggunakan Pusher.js
- ✅ Upload Dokumen (KK, KTP, Surat Domisili)

---

## 🧑‍💻 Teknologi yang Digunakan

| Teknologi     | Keterangan                            |
| ------------- | ------------------------------------- |
| PHP           | Bahasa pemrograman backend            |
| CodeIgniter 3 | Framework utama aplikasi              |
| Bootstrap 5   | Tampilan UI responsif                 |
| Leaflet.js    | Menampilkan peta lokasi rumah warga   |
| Select2       | Dropdown dengan pencarian             |
| DataTables    | Tabel interaktif dan sortable         |
| Dompdf        | Export data ke file PDF               |
| Pusher.js     | Notifikasi real-time                  |
| PWA           | Konfigurasi Web Apps untuk di browser |

---

## ⚙️ Instalasi & Setup

### Clone Repository

```bash
git clone https://github.com/username/sipandu_pbl.git
cd sipandu_pbl
```

## 🛠️ Penggunaan Fitur

### Pendataan

- Admin menambahkan penanggung jawab (KK)
- Penanggung jawab menambahkan anggota keluarga (penghuni)
- Kaling memverifikasi data penghuni yang aktif

### Peta Rumah

- Input lokasi rumah via lat/lng saat menambah data
- Dapat dilihat secara interaktif dengan Leaflet.js

### PDF Export

- Tersedia tombol `Cetak PDF` pada halaman data penghuni

---

## 🧪 Testing

Untuk menjalankan aplikasi secara lokal:

```bash
php -S localhost://
```

Pastikan semua ekstensi PHP seperti `mbstring`, `dom`, dan `fileinfo` aktif di `php.ini`.

---

## 👥 Tim Pengembang

- **I Putu Agus Wiadnyana**
- **I Komang Syama Sundara**
- 💼 _Kolaborasi bersama dosen pembimbing Jurusan Teknologi Informasi (JTI), Politeknik Negeri Bali_

---

## 📸 Screenshots (opsional)

Tambahkan beberapa tangkapan layar folder `/screenshots`:

- Halaman login
- Dashboard Admin / Kaling
- Form input penghuni
- Peta lokasi
- Export PDF

---

## 💬 Kontribusi

Anda tidak dapat berkontribusi dikarenakan ini bersifat privat dan anda hanya dapat melihat repository ini saja tanpa **Fork / Kontribusi** langsung.

---

## 🔗 Referensi

- [CodeIgniter 3 Docs](https://codeigniter.com/userguide3/)
- [Bootstrap 5](https://getbootstrap.com/)
- [Leaflet.js](https://leafletjs.com/)
- [Pusher.js](https://pusher.com/docs/)
- [Dompdf](https://github.com/dompdf/dompdf)
