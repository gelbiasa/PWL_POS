<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            padding: 4px 3px;
        }
        th {
            text-align: left;
        }
        .d-block {
            display: block;
        }
        img.logo-image {
            width: 70px;
            height: auto;
            max-height: 80px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .p-1 {
            padding: 5px 1px;
        }
        .font-10 {
            font-size: 10pt;
        }
        .font-11 {
            font-size: 11pt;
        }
        .font-12 {
            font-size: 12pt;
        }
        .font-13 {
            font-size: 13pt;
        }
        .border-bottom-header {
            border-bottom: 1px solid;
        }
        .border-all, .border-all th, .border-all td {
            border: 1px solid;
        }
    </style>
</head>
<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center">
                <img src="{{ asset('polinema.png') }}" alt="Logo Polinema" class="logo-image">
            </td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">
                    KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI
                </span>
                <span class="text-center d-block font-13 font-bold mb-1">
                    POLITEKNIK NEGERI MALANG
                </span>
                <span class="text-center d-block font-10">
                    Jl. Soekarno-Hatta No. 9 Malang 65141
                </span>
                <span class="text-center d-block font-10">
                    Telepon (0341) 404424 Pes. 101105, 0341-404420, Fax. (0341) 404420
                </span>
                <span class="text-center d-block font-10">
                    Laman: www.polinema.ac.id
                </span>
            </td>
        </tr>
    </table>

    <h3 class="text-center">LAPORAN DATA PENJUALAN</h3>

    <table class="border-all">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Kode Penjualan</th>
                <th>Tanggal Penjualan</th>
                <th>Nama User</th>
                <th>Pembeli</th>
                <th>Nama Barang</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan as $index => $p)
                @php
                    $totalHargaPenjualan = 0;
                @endphp
                @foreach($p->penjualanDetail as $detailIndex => $detail)
                <tr>
                    @if ($detailIndex === 0)
                        @php
                            foreach ($p->penjualanDetail as $d) {
                                $totalHargaPenjualan += $d->harga;
                            }
                        @endphp
                        <td class="text-center font-10" rowspan="{{ count($p->penjualanDetail) }}">{{ $index + 1 }}</td>
                        <td class="font-10" rowspan="{{ count($p->penjualanDetail) }}">{{ $p->penjualan_kode }}</td>
                        <td class="font-10" rowspan="{{ count($p->penjualanDetail) }}">{{ \Carbon\Carbon::parse($p->penjualan_tanggal)->format('Y-m-d H:i:s') }}</td>
                        <td class="font-10" rowspan="{{ count($p->penjualanDetail) }}">{{ $p->user->nama }}</td>
                        <td class="font-10" rowspan="{{ count($p->penjualanDetail) }}">{{ $p->pembeli }}</td>
                    @endif
                    <td class="font-10">{{ $detail->barang->barang_nama }}</td>
                    <td class="font-10">Rp {{ number_format($detail->barang->harga_jual, 0, ',', '.') }}</td>
                    <td class="font-10">{{ $detail->jumlah }}</td>
                    <td class="font-10">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>

                    @if ($detailIndex === 0)
                        <td class="font-10" rowspan="{{ count($p->penjualanDetail) }}">Rp {{ number_format($totalHargaPenjualan, 0, ',', '.') }}</td>
                    @endif
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
