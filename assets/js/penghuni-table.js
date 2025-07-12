$(document).ready(function() {
    let tableTerverifikasi = $('#tableTerverifikasi').DataTable({
        "order": [[0, "asc"]],
        "pageLength": 10,
        "responsive": true,
        "language": {
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "search": "Cari:",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });

    const tableMenunggu = $('#myTable1').DataTable({
        "pageLength": 10,
        "responsive": true,
        "language": {
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "search": "Cari:",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });

    $('#filterButton').on('click', function() {
        const pj_id = $('#penanggungJawabFilter').val();
        const status = $('#statusFilter').val();
        Swal.fire({
            title: 'Memuat Data...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        $.ajax({
            url: baseUrl + 'dashboard/penghuni/filterTerverifikasiByPJ',
            type: 'GET',
            data: {
                pj_id: pj_id,
                status: status
            },
            success: function(response) {
                Swal.close();                
                if (response && Array.isArray(response)) {
                    if ($.fn.DataTable.isDataTable('#tableTerverifikasi')) {
                        tableTerverifikasi.destroy();
                        $('#tableTerverifikasi tbody').empty();
                    }
                    let html = '';
                    response.data.forEach((p, index) => {
                        html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${p.nama_lengkap}</td>
                                <td>${p.nik}</td>
                                <td>${p.nama_pj}</td>
                                <td>${p.tanggal_masuk_formatted}</td>
                                <td>${p.tanggal_keluar_formatted}</td>
                                <td>
                                    <span class="badge ${p.status_verifikasi === 'Diterima' ? 'bg-success' : 'bg-danger'}">
                                        ${p.status_verifikasi}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge ${p.status_penghuni === 'Aktif' ? 'bg-success' : 'bg-danger'}">
                                        ${p.status_penghuni}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="${baseUrl}dashboard/penghuni/detail_admin/${p.uuid}" 
                                           class="btn btn-info btn-sm" 
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="${baseUrl}dashboard/penghuni/edit_admin/${p.uuid}" 
                                           class="btn btn-warning btn-sm"
                                           title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        ${p.status_verifikasi === 'Diterima' && p.status_penghuni === 'Aktif' ? 
                                            `<a href="javascript:void(0);"
                                                class="btn btn-danger btn-sm"
                                                title="Non-aktifkan"
                                                onclick="nonAktifkan('${p.uuid}')">
                                                <i class="fas fa-user-times"></i>
                                            </a>` : ''
                                        }
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    
                    $('#tableTerverifikasi tbody').html(html);
                    tableTerverifikasi = $('#tableTerverifikasi').DataTable({
                        "order": [[0, "asc"]],
                        "pageLength": 10,
                        "responsive": true,
                        "language": {
                            "lengthMenu": "Tampilkan _MENU_ data per halaman",
                            "zeroRecords": "Data tidak ditemukan",
                            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                            "infoEmpty": "Tidak ada data tersedia",
                            "infoFiltered": "(difilter dari _MAX_ total data)",
                            "search": "Cari:",
                            "paginate": {
                                "first": "Pertama",
                                "last": "Terakhir",
                                "next": "Selanjutnya",
                                "previous": "Sebelumnya"
                            }
                        }
                    });

                    if (response.data.length === 0) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Tidak Ada Data',
                            text: 'Tidak ada data yang sesuai dengan filter yang dipilih',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat data',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat memuat data',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });
});
