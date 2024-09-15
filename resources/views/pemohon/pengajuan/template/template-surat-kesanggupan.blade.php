<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Kesanggupan</title>

    <style>
        @page {
            size: A4;
            margin: 0;
        }

        @media print {

            html,
            body {
                width: 210mm;
                height: 297mm;
            }

        }

        body {
            margin: 1.5cm 1.5cm 1.5cm 1.5cm;
            font-family: "Times New Roman" !important;
            /* line-height: 17px !important; */
        }

        .logo {
            width: 3cm;
        }

        .aksara {
            width: 25%;
            margin-top: -20px;
        }

        .text-center {
            text-align: center;
        }

        .garis {
            border-top: 1px solid black;
            border-bottom: 5px solid black;
            padding: 1px 0;
            width: 100%;
        }
    </style>
</head>

<body>
    {{-- KOP --}}
    <div>
        <table style="width: 100%">
            <tr>
                <td style="width: 15%">
                    <img src="data:image/jpeg;base64,{{ $logo }}" class="logo">
                </td>
                <td class="text-center">
                    <p style="font-size: 16pt !important; margin-top: 0px;">
                        PEMERINTAH KABUPATEN BANTUL
                    </p>
                    <p style="font-size: 16pt !important; margin-top: -20px;">
                        <b>
                            DINAS PERHUBUNGAN
                        </b>
                    </p>
                    <p>
                        <img src="data:image/jpeg;base64,{{ $aksara }}" class="aksara">
                    </p>
                    <p style="font-size: 12pt; margin-top: -20px;">
                        Jalan Lingkar Timur, Manding, Trirenggo, Bantul
                    </p>
                    <p style="font-size: 12pt; margin-top: -10px;">Telp. (0274)-367321</p>
                    <p style="font-size: 12pt; margin-top: -10px;">Email: dishub@bantulkab.go.id</p>
                    <p style="font-size: 12pt; margin-top: -10px;">Website: http://dishub.bantulkab.go.id</p>
                </td>
            </tr>
        </table>
    </div>

    {{-- garis tebal --}}
    <div class="garis">
    </div>

    <section class="section-1 text-center">
        <h3>
            <b>SURAT PERNYATAAN KESANGGUPAN KERJASAMA PENGELOLAAN PARKIR DINAS PERHUBUNGAN</b>
        </h3>
    </section>

    <section class="section-2">
        <p>Yang bertanda tangan di bawah ini:</p>
        <table style="width: 100%">
            <tr>
                <td style="width: 30%">Nama</td>
                <td>: {{ $nama }}</td>
            </tr>
            <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>: {{ $tanggalLahir }}</td>
            </tr>
            <tr>
                <td>Agama</td>
                <td>: {{ $agama }}</td>
            </tr>
            <tr>
                <td>Pendidikan Terakhir</td>
                <td>: {{ $pendidikanTerakhir }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>: {{ $alamat }}</td>
            </tr>
            <tr>
                <td>No. Telp/HP (WA)</td>
                <td>: {{ $noTelepon }}</td>
            </tr>
            <tr>
                <td>Lokasi Parkir</td>
                <td>: {{ $lokasiParkir }}</td>
            </tr>
            @if ($luas)
                <tr>
                    <td>Luas Lokasi Parkir</td>
                    <td>: {{ $luas }} m<sup>2</sup></td>
                </tr>
            @elseif($panjang)
                <tr>
                    <td>Panjang Lokasi Parkir</td>
                    <td>: {{ $panjang }} m<sup>2</sup></td>
                </tr>
            @endif
        </table>
    </section>

    <section class="section-3">
        <p class="text-center">
            <b>MENYATAKAN : </b>
        </p>
        <ol start="1">
            <li>Sanggup mentaati segala ketentuan yang berlaku dalam menyelenggarakan parkir di lokasi tersebut diatas.
            </li>
            <li>Bertanggungjawab secara penuh atas ketertiban dan keamanan dalam menyelenggarakan parkir di lokasi
                tersebut diatas.</li>
            <li>Bersedia membayar retribusi parkir</li>
        </ol>
        <p>Demikian surat ini dibuat untuk dapat digunakan sebgaimana mestinya.</p>
        <p style="text-align:right">
            {{ tanggalTtd() }}
        </p>
        <table style="width: 100%;">
            <tr>
                <td style="text-align:center">
                    An Kepala Dinas <br>
                    Petugas Penetapan
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    {{-- {{ $kadis->hasOneProfile?->nama ?? '' }} --}}
                </td>
                <td style="text-align:center">
                    <br>
                    Pemohon
                    <br>
                    <br>
                    <br>
                    <br>
                    Ttd dan Materai
                    <br>
                    <br>
                    <br>
                    <br>
                    {{ $nama }}
                </td>
            </tr>
        </table>
    </section>
</body>

</html>
