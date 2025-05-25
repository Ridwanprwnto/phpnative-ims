<?php
    // Action untuk perubahan status pembelian ada pada tabel status pembelian
    $arrsp = [
        "S01", // Menunggu Approve Atasan
        "S02", // Ditolak Atasan
        "S03", // Disetujui Atasan
        "S04", // Ditolak Department Tujuan
        "S05", // Disetujui Sebagian
        "S06", // Disetujui Semua
        "S07", // Proses Pembelian
        "S08", // Sudah Terima Belum Input Barang
        "S09", // Sudah Input Barang Sebagian
        "S10", // Sudah Diterima Sebagian
        "S11"  // Selesai
    ];
    // Action untuk perubahan status pembelian ada pada tabel status pembelian

    $arrcond = [
        "01", // Baik
        "02", // Cadangan
        "03", // Rusak
        "04", // Perbaikan
        "05", // P3AT
        "06", // Musnah
        "07", // Hilang
        "08" // Mutasi
    ];

    $arrtransaksi = [
        "K", // Khusus
        "P", // Pembelian
        "M", // Mutasi
        "S", // Service
        "I", // Penerimaan
        "O"  // Pengeluaran
    ];

    $arrdept = [
        "DT01", // DC
        "DT02", // GA
        "DT03", // EDP
        "DT04", // IC
        "DT05", // ACL
    ];
    
    $arrgroup = [
        "GP01", // ADMINISTRATOR
        "GP02", // SUPPORT
        "GP03", // APPROVAL
        "GP04", // SPV
        "GP05", // WH
        "GP06", // DELIVERY
        "GP08", // RCV RTR
        "GP09", // CCTV
        "GP10", // PERISH
        "GP11", // REPORTING
    ];

    $arrlvl = [
        "LV02", // MANAGER
        "LV03", // SUPERVISOR
        "LV04", // SENIOR
        "LV05", // JUNIOR
        "LV01", // SUPERADMIN
    ];

    $arrdiv = [
        "DV01", // ADMIN
        "DV02", // WH
        "DV03", // RCV
        "DV04", // RTR
        "DV05", // ISSUING
        "DV06", // DRIVER
        "DV07", // PERISH
        "DV08", // DELIVERY
        "DV09", // BAKERY
        "DV10", // SEC
        "DV11", // LAIN
        "DV12" // REC / RTR
    ];

    $arrsp3at = [
        "T01", // Proses P3AT
        "T02", // P3AT Approved
        "T03", // Proses PP
        "T04"  // Selesai
    ];

    $arrmodifref = [
        "PMB", // Perubahan Master Barang
        "PAP", // Penerimaan Atas Pembelian
        "IBK", // Input Barang Khusus
        "BKB", // Bukti Keluar Barang
        "BAK", // Berita Acara Kerusakan
        "PBI", // Perbaikan Barang Inventaris
        "TBI", // Terima Barang Inventaris
        "PAT", // Proses Pemusnahan Aktiva Tetap
        "MBI", // Mutasi Barang Inventaris
        "ASO", // Adjust Stock Opname
        "AKH", // Absensi Kehadiran Harian
        "MSH"  // Pemusnahan Aktiva Barang Inventaris
    ];

    $arrextmenu = [
        "E001", // Ext Menu Rekam Draft SO
        "E002", // Ext Rekam Draft SO Non Aktiva
        "E003", // Ext Menu Pengajuan Pembelian Atas Pemusnahan
        "E004", // Ext Menu Edit Pengajuan Pembelian
        "E005", // Ext Menu Revisi Pengajuan Pembelian
        "E006", // Ext Menu Proses Penerimaan Pembelian
        "E007", // Ext Menu Proses Update Penerimaan Pembelian
        "E008", // Ext Menu Proses SO Apar
        "E009", // Ext Menu Update Project
        "E010", // Ext Menu Proses Pemusnahan DAT
    ];
?>