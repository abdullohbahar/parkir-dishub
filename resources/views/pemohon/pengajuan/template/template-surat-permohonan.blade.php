<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Permohonan</title>

    <style>
        table {
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <div class="section1">
        <table style="width: 100%">
            <tr style="">
                <td
                    style="width: 50%; font-size: 14pt; font-weight:bolder; text-transform:uppercase; vertical-align:top;">
                    <span style="margin-left: 5px">
                        {{ $pengajuan->hasOneJenisPengajuan->jenis }}
                    </span>
                </td>
                <td style="width: 50%; line-height: 0.7cm; vertical-align:top;">
                    <div style="margin-left: 5px">
                        Kepada: <br>
                        Yth. Bupati Bantul <br>
                        C/q Kepala Dinas perhubungan Kab. Bantul
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="">
        <table>
            <tr>
                <td style="vertical-align: top">Hal</td>
                <td style="vertical-align: top;">
                    <span style="margin-left: 5px; margin-right:5px;">
                        :
                    </span>
                </td>
                <td>
                    Permohonan Ijin Penyelenggaraan <br>
                    Parkir Tahun {{ date('Y') }}
                </td>
            </tr>
        </table>
    </div>
    <div style="margin-top: 25px">
        Yang bertanda tangan di bawah ini:
        <table style="padding-top: 15px; width: 100%">
            <tr>
                <td style="width: 30%">Nama</td>
                <td style="width: 2%">:</td>
                <td>{{ $pengajuan->hasOnePemohon?->hasOneProfile?->nama }}</td>
            </tr>
            <tr style="line-height: 25px">
                <td style="width: 30%">Tempat, Tanggal Lahir</td>
                <td style="width: 2%">:</td>
                <td>
                    {{ $pengajuan->hasOnePemohon?->hasOneProfile?->ttl }}
                </td>
            </tr>
            <tr style="line-height: 25px">
                <td style="width: 30%">Agama</td>
                <td style="width: 2%">:</td>
                <td>
                    {{ $pengajuan->hasOnePemohon?->hasOneProfile?->agama }}
                </td>
            </tr>
            <tr style="line-height: 25px">
                <td style="width: 30%">Alamat</td>
                <td style="width: 2%">:</td>
                <td>
                    {{ $pengajuan->hasOnePemohon?->hasOneProfile?->alamat }}
                </td>
            </tr>
            <tr style="line-height: 25px">
                <td style="width: 30%">No. Telp/HP (WA)</td>
                <td style="width: 2%">:</td>
                <td>
                    {{ $pengajuan->hasOnePemohon?->hasOneProfile?->no_telepon }}
                </td>
            </tr>
            <tr style="line-height: 25px">
                <td style="width: 30%">
                    @if ($pengajuan->hasOneJenisPengajuan->jenis == 'Tepi Jalan')
                        Panjang
                    @else
                        Luas
                    @endif
                </td>
                <td style="width: 2%">:</td>
                <td>
                    @if ($pengajuan->hasOneJenisPengajuan->jenis == 'Tepi Jalan')
                        {{ $pengajuan->panjang }}
                    @else
                        {{ $pengajuan->luas }}
                    @endif
                </td>
            </tr>
        </table>
    </div>
    <div>
        <p style="text-align:justify; line-height: 25px;">
            Dengan ini mengajukan permohonan kepada Bupati Bantul C/q Kepala Dinas Perhubungan Kabupaten Bantul untuk
            menyelenggarakan dan mengolah parkir serta sanggup mematuhi peraturan yang berlaku, yang berlokasi di :
        </p>
        <b>
            "{{ $pengajuan->alamat_lokasi_parkir }}"
        </b>
    </div>
    <div style="margin-top:30px">
        <p>
            Sebagai bahan pertimbangan telah diupload:
        <ol>
            <li>Foto atau Scan KTP</li>
            <li>Pas Foto atau Scan Foto 3x4</li>
            <li>Foto atau Scan Denah Lokasi Parkir</li>
        </ol>
        </p>
    </div>
    <div style="margin-top: 30px">
        <p>
            Demikian permohonan ini, atas perhatian dan terkabulnya permohonan diucapkan terimakasih
        </p>
    </div>
    <div style="margin-top: 25px">
        <table style="width: 100%">
            <tr>
                <td style="width: 50%; text-align:center; vertical-align:top">
                    Mengetahui, <br>
                    Pemilik Toko/Lembaga/Kantor
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    {{ $pengajuan?->nama_pemilik }}
                </td>
                <td style="width: 50%; text-align:center; vertical-align:top">
                    Mengetahui, <br>
                    Pemohon
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    {{ $pengajuan->hasOnePemohon?->hasOneProfile?->nama }}
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
