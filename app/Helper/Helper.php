<?php

if (!function_exists('contohHelper')) {
    function tanggalTtd($kota = 'Bantul')
    {
        $tanggal = date('Y-m-d');

        // Ubah format tanggal ke timestamp
        $time = strtotime($tanggal);

        // Array nama bulan dalam bahasa Indonesia
        $bulanIndonesia = [
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
        ];

        // Format tanggal sesuai dengan format yang diinginkan
        $tanggalFormatted = date('j', $time); // Tanggal (tanpa leading zero)
        $bulanFormatted = $bulanIndonesia[date('n', $time)]; // Nama bulan dalam bahasa Indonesia
        $tahunFormatted = date('Y', $time); // Tahun

        // Hasil akhir
        return "$kota, $tanggalFormatted $bulanFormatted $tahunFormatted";
    }
}
