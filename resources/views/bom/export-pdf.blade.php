<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bill of Material - {{ $bom->nomor_bom }}</title>
    <style>
        .signature-nip {
            font-size: 7pt;
            text-align: center;
            margin-top: 2px;
        }
        
        .signature-nip.left {
            text-align: left;
        }
        
        .signature-date.right {
            text-align: left;
            margin-right: 0px;
            padding-left: 70px;
        }
        
        .signature-title.right {
            text-align: left;
            margin-right: 0px;
            padding-left: 70px;
        }@page {
            margin: 8mm;
            size: A4 landscape;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 8pt;
            line-height: 1.0;
            color: #000;
            margin: 0;
            padding: 0;
            position: relative;
        }
        
        .main-container {
            border: 3px solid #000;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px; /* Space for page number */
        }
        
        .header {
            display: table;
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        
        .header-left {
            display: table-cell;
            width: 20%;
            vertical-align: middle;
            padding: 8px;
            border-right: 3px solid #000;
            text-align: center;
        }
        
        .logo {
            width: 150px;
            height: auto;
            max-height: 90px;
        }
        
        .logo-text {
            font-size: 18pt;
            font-weight: bold;
            color: #333;
            border: 2px dashed #666;
            padding: 25px 8px;
            width: 150px;
            text-align: center;
        }
        
        .header-center {
            display: table-cell;
            width: 60%;
            text-align: center;
            vertical-align: middle;
            padding: 8px;
            border-right: 3px solid #000;
        }
        
        .header-center h1 {
            font-size: 28pt;
            font-weight: bold;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .header-center h2 {
            font-size: 24pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            line-height: 1.2;
        }
        
        .header-right {
            display: table-cell;
            width: 20%;
            vertical-align: top;
            padding: 8px;
        }
        
        .info-field {
            margin-bottom: 6px;
            font-size: 8pt;
        }
        
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 80px;
        }
        
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            table-layout: fixed;
        }
        
        .main-table th {
            background-color: #f5f5f5;
            border: 2px solid #000;
            padding: 4px 3px;
            text-align: center;
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
            vertical-align: middle;
            height: 28px;
        }
        
        .main-table td {
            border: 2px solid #000;
            padding: 3px;
            text-align: center;
            vertical-align: middle;
            font-size: 8pt;
            height: 16px;
        }
        
        .main-table td.text-left {
            text-align: left;
            padding-left: 4px;
        }
        
        .main-table td.text-right {
            text-align: right;
            padding-right: 4px;
        }
        
        .signature-section {
            width: 100%;
            border-collapse: collapse;
            display: table;
            table-layout: fixed;
            height: 120px;
            border-top: 3px solid #000;
        }
        
        .signature-row {
            display: table-row;
            height: 100%;
        }
        
        .signature-col {
            display: table-cell;
            width: 33.33%;
            padding: 8px 12px;
            vertical-align: top;
            position: relative;
        }
        
        .signature-date {
            font-size: 8pt;
            margin-bottom: 4px;
            text-align: left;
        }
        
        .signature-title {
            font-size: 8pt;
            margin-bottom: 6px;
            text-align: left;
            font-weight: bold;
        }
        
        .signature-space {
            height: 45px;
            margin: 4px 0;
        }
        
        .signature-name-line {
            font-size: 8pt;
            text-align: left;
            margin-bottom: 2px;
            padding-bottom: 1px;
            margin-left: 0px;
            margin-right: 10px;
        }
        
        .signature-name-line.center {
            text-align: center;
            margin-left: 10px;
        }
        
        .signature-name-line.right {
            text-align: center;
            margin-left: 10px;
        }
        
        .page-number {
            position: fixed;
            bottom: 5mm;
            right: 10mm;
            font-size: 8pt;
            color: #000;
            background: white;
            padding: 2px 4px;
            z-index: 1000;
        }
        
        /* Adjusted Column widths - memperbesar spesifikasi dan menyesuaikan kolom lain */
        .col-rev { width: 4%; }
        .col-no { width: 4%; }
        .col-kode { width: 12%; } /* Tetap 12% */
        .col-deskripsi { width: 30%; } /* Dikurangi dari 34% menjadi 30% */
        .col-qty { width: 6%; } /* Dikurangi dari 7% menjadi 6% */
        .col-satuan { width: 6%; } /* Dikurangi dari 7% menjadi 6% */
        .col-spesifikasi { width: 18%; } /* Diperbesar dari 12% menjadi 18% */
        .col-keterangan { width: 20%; } /* Kembalikan ke ukuran semula 20% */
        
        .empty-row {
            height: 16px;
        }
        
        .break-word {
            word-wrap: break-word;
            word-break: break-word;
        }

        /* Ensure no gaps between borders */
        .main-container * {
            box-sizing: border-box;
        }
        
        /* CSS untuk multi-halaman jika diperlukan */
        @media print {
            .page-break {
                page-break-before: always;
            }
            
            .page-number {
                position: absolute;
                bottom: 5mm;
                right: 10mm;
            }
        }
        
        .content-page {
            min-height: calc(100vh - 35mm); /* Adjusted to prevent overlap */
            margin-bottom: 20px;
        }
        
        /* Responsive adjustments */
        @media screen and (max-width: 1200px) {
            .header {
                font-size: 7pt;
            }
            
            .main-table th,
            .main-table td {
                font-size: 7pt;
                padding: 2px;
            }
            
            .header-center h1 {
                font-size: 24pt;
            }
            
            .header-center h2 {
                font-size: 20pt;
            }
        }
        
        @media screen and (max-width: 900px) {
            .main-table th,
            .main-table td {
                font-size: 6pt;
                padding: 1px;
            }
            
            .header-center h1 {
                font-size: 22pt;
            }
            
            .header-center h2 {
                font-size: 18pt;
            }
            
            .logo-text {
                font-size: 16pt;
                padding: 20px 5px;
            }
        }
    </style>
</head>
<body>
    @php
        $itemsPerPage = 20;
        $totalItems = $bom->itemBom ? $bom->itemBom->count() : 0;
        $totalPages = $totalItems > 0 ? ceil($totalItems / $itemsPerPage) : 1;
        
        // Logo path logic
        $logoPath = '';
        $logoPaths = [
            public_path('img/logo-qinka.png'),
            public_path('assets/img/logo-qinka.png'),
            public_path('images/logo-qinka.png'),
            storage_path('app/public/logo-qinka.png'),
            base_path('public/img/logo-qinka.png'),
            base_path('resources/img/logo-qinka.png')
        ];
        
        foreach($logoPaths as $path) {
            if(file_exists($path)) {
                $logoPath = $path;
                break;
            }
        }
    @endphp
    
    <!-- Halaman Pertama -->
    <div class="main-container content-page">
        <!-- Header Section -->
        <div class="header">
            <div class="header-left">
                @if($logoPath && file_exists($logoPath))
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents($logoPath)) }}" class="logo" alt="Logo">
                @else
                    <div class="logo-text">QINKA<br>Multi Solusi</div>
                @endif
            </div>
            <div class="header-center">
                <h1>BILL OF MATERIAL</h1>
                <h2>{{ strtoupper($bom->kategori ?? 'JIG, TOOL DAN MAL / TOOLS / CONSUMABLE TOOLS / SPECIAL PROCESS') }}</h2>
            </div>
            <div class="header-right">
                <div class="info-field">
                    <span class="info-label">Nomor</span>: {{ $bom->nomor_bom ?? '' }}
                </div>
                <div class="info-field">
                    <span class="info-label">Proyek</span>: {{ $bom->proyek ? $bom->proyek->nama_proyek : '' }}
                </div>
                <div class="info-field">
                    <span class="info-label">Tgl. Terbit</span>: {{ $bom->tanggal ? date('d/m/Y', strtotime($bom->tanggal)) : '' }}
                </div>
                <div class="info-field">
                    <span class="info-label">Revisi</span>: {{ $bom->revisi ? $bom->revisi->jenis_revisi . ' - ' . $bom->revisi->keterangan : 'Tidak ada revisi' }}
                </div>
            </div>
        </div>
        
        <!-- Main Table -->
        <table class="main-table">
            <thead>
                <tr>
                    <th class="col-rev">REV</th>
                    <th class="col-no">NO.</th>
                    <th class="col-kode">KODE MATERIAL</th>
                    <th class="col-deskripsi">DESKRIPSI MATERIAL</th>
                    <th class="col-qty">QTY</th>
                    <th class="col-satuan">SATUAN</th>
                    <th class="col-spesifikasi">SPESIFIKASI</th>
                    <th class="col-keterangan">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $currentPageItems = $bom->itemBom ? $bom->itemBom->take($itemsPerPage) : collect([]);
                @endphp
                
                @if($currentPageItems->count() > 0)
                    @foreach($currentPageItems as $index => $item)
                        <tr>
                            <td></td>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-left">{{ $item->kodeMaterial ? $item->kodeMaterial->kode_material : '' }}</td>
                            <td class="text-left break-word">{{ $item->kodeMaterial ? $item->kodeMaterial->nama_material : '' }}</td>
                            <td class="text-right">
                                @php
                                    $qty = 0;
                                    if ($item->qty !== null && $item->qty !== 0) {
                                        $qty = $item->qty;
                                    } elseif ($item->kodeMaterial && $item->kodeMaterial->uom && $item->kodeMaterial->uom->qty) {
                                        $qty = $item->kodeMaterial->uom->qty;
                                    } else {
                                        $qty = 1;
                                    }
                                    echo number_format($qty, 0, ',', '.');
                                @endphp
                            </td>
                            <td>
                                @php
                                    $satuan = '';
                                    if ($item->satuan && trim($item->satuan) !== '') {
                                        $satuan = $item->satuan;
                                    } elseif ($item->kodeMaterial && $item->kodeMaterial->uom && $item->kodeMaterial->uom->satuan) {
                                        $satuan = $item->kodeMaterial->uom->satuan;
                                    }
                                    echo $satuan;
                                @endphp
                            </td>
                            <td class="text-left break-word">{{ $item->kodeMaterial && $item->kodeMaterial->spesifikasi ? $item->kodeMaterial->spesifikasi : '' }}</td>
                            <td class="text-left break-word">{{ $item->keterangan ?: '' }}</td>
                        </tr>
                    @endforeach
                @endif
                
                <!-- Fill remaining rows to maintain consistent layout -->
                @for($i = $currentPageItems->count(); $i < 20; $i++)
                    <tr class="empty-row">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endfor
            </tbody>
        </table>
        
        <!-- Signature Section untuk halaman pertama atau satu-satunya halaman -->
        @if($totalPages == 1)
            <div class="signature-section">
                <div class="signature-row">
                    <div class="signature-col">
                        <div class="signature-date">Tanggal: {{ $bom->created_at ? date('d/m/Y', strtotime($bom->created_at)) : '' }}</div>
                        <div class="signature-title">Disiapkan oleh:</div>
                        <div class="signature-space"></div>
                        <div class="signature-name-line">
                            @if($bom->createdBy)
                                ( {{ $bom->createdBy->nama }} )
                            @else
                                ( _________________________ )
                            @endif
                        </div>
                        @if($bom->createdBy && $bom->createdBy->nip)
                            <div class="signature-nip left">{{ $bom->createdBy->nip }}</div>
                        @endif
                    </div>
                    
                    <div class="signature-col">
                        <div class="signature-date">Tanggal: {{ $bom->approved_by_1_at ? date('d/m/Y', strtotime($bom->approved_by_1_at)) : '' }}</div>
                        <div class="signature-title">Diperiksa oleh:</div>
                        <div class="signature-space"></div>
                        <div class="signature-name-line center">
                            @if($bom->approvedBy1)
                                ( {{ $bom->approvedBy1->nama }} )
                            @else
                                ( _________________________ )
                            @endif
                        </div>
                        @if($bom->approvedBy1 && $bom->approvedBy1->nip)
                            <div class="signature-nip">{{ $bom->approvedBy1->nip }}</div>
                        @endif
                    </div>
                    
                    <div class="signature-col">
                        <div class="signature-date right">Tanggal: {{ $bom->approved_by_2_at ? date('d/m/Y', strtotime($bom->approved_by_2_at)) : '' }}</div>
                        <div class="signature-title right">Disahkan oleh:</div>
                        <div class="signature-space"></div>
                        <div class="signature-name-line right">
                            @if($bom->approvedBy2)
                                ( {{ $bom->approvedBy2->nama }} )
                            @else
                                ( _________________________ )
                            @endif
                        </div>
                        @if($bom->approvedBy2 && $bom->approvedBy2->nip)
                            <div class="signature-nip right">{{ $bom->approvedBy2->nip }}</div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Halaman-halaman berikutnya jika data lebih dari 20 item -->
    @if($totalItems > 20)
        @for($page = 2; $page <= $totalPages; $page++)
            <div class="page-break"></div>
            <div class="main-container content-page">
                <!-- Header untuk halaman selanjutnya -->
                <div class="header">
                    <div class="header-left">
                        @if($logoPath && file_exists($logoPath))
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents($logoPath)) }}" class="logo" alt="Logo">
                        @else
                            <div class="logo-text">QINKA<br>Multi Solusi</div>
                        @endif
                    </div>
                    <div class="header-center">
                        <h1>BILL OF MATERIAL</h1>
                        <h2>{{ strtoupper($bom->kategori ?? 'JIG, TOOL DAN MAL / TOOLS / CONSUMABLE TOOLS / SPECIAL PROCESS') }}</h2>
                    </div>
                    <div class="header-right">
                        <div class="info-field">
                            <span class="info-label">Nomor</span>: {{ $bom->nomor_bom ?? '' }}
                        </div>
                        <div class="info-field">
                            <span class="info-label">Proyek</span>: {{ $bom->proyek ? $bom->proyek->kode_proyek : '' }}
                        </div>
                        <div class="info-field">
                            <span class="info-label">Tgl. Terbit</span>: {{ $bom->tanggal ? date('d/m/Y', strtotime($bom->tanggal)) : '' }}
                        </div>
                        <div class="info-field">
                            <span class="info-label">Revisi List</span>: {{ $bom->revisi ? $bom->revisi->jenis_revisi : '' }}
                        </div>
                    </div>
                </div>
                
                <table class="main-table">
                    <thead>
                        <tr>
                            <th class="col-rev">REV</th>
                            <th class="col-no">NO.</th>
                            <th class="col-kode">KODE MATERIAL</th>
                            <th class="col-deskripsi">DESKRIPSI MATERIAL</th>
                            <th class="col-qty">QTY</th>
                            <th class="col-satuan">SATUAN</th>
                            <th class="col-spesifikasi">SPESIFIKASI</th>
                            <th class="col-keterangan">KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $startIndex = ($page - 1) * $itemsPerPage;
                            $pageItems = $bom->itemBom->slice($startIndex, $itemsPerPage);
                        @endphp
                        
                        @foreach($pageItems as $index => $item)
                            <tr>
                                <td></td>
                                <td>{{ $startIndex + $index + 1 }}</td>
                                <td class="text-left">{{ $item->kodeMaterial ? $item->kodeMaterial->kode_material : '' }}</td>
                                <td class="text-left break-word">{{ $item->kodeMaterial ? $item->kodeMaterial->nama_material : '' }}</td>
                                <td class="text-right">
                                    @php
                                        $qty = 0;
                                        if ($item->qty !== null && $item->qty !== 0) {
                                            $qty = $item->qty;
                                        } elseif ($item->kodeMaterial && $item->kodeMaterial->uom && $item->kodeMaterial->uom->qty) {
                                            $qty = $item->kodeMaterial->uom->qty;
                                        } else {
                                            $qty = 1;
                                        }
                                        echo number_format($qty, 0, ',', '.');
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        $satuan = '';
                                        if ($item->satuan && trim($item->satuan) !== '') {
                                            $satuan = $item->satuan;
                                        } elseif ($item->kodeMaterial && $item->kodeMaterial->uom && $item->kodeMaterial->uom->satuan) {
                                            $satuan = $item->kodeMaterial->uom->satuan;
                                        }
                                        echo $satuan;
                                    @endphp
                                </td>
                                <td class="text-left break-word">{{ $item->kodeMaterial && $item->kodeMaterial->spesifikasi ? $item->kodeMaterial->spesifikasi : '' }}</td>
                                <td class="text-left break-word">{{ $item->keterangan ?: '' }}</td>
                            </tr>
                        @endforeach
                        
                        @for($i = $pageItems->count(); $i < 20; $i++)
                            <tr class="empty-row">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
                
                <!-- Signature section hanya di halaman terakhir -->
                @if($page == $totalPages)
                    <div class="signature-section">
                        <div class="signature-row">
                            <div class="signature-col">
                                <div class="signature-date">Tanggal: {{ $bom->created_at ? date('d/m/Y', strtotime($bom->created_at)) : '' }}</div>
                                <div class="signature-title">Disiapkan oleh:</div>
                                <div class="signature-space"></div>
                                <div class="signature-name-line">
                                    @if($bom->createdBy)
                                        ( {{ $bom->createdBy->nama }} )
                                    @else
                                        ( _________________________ )
                                    @endif
                                </div>
                                @if($bom->createdBy && $bom->createdBy->nip)
                                    <div class="signature-nip left">{{ $bom->createdBy->nip }}</div>
                                @endif
                            </div>
                            
                            <div class="signature-col">
                                <div class="signature-date">Tanggal: {{ $bom->approved_by_1_at ? date('d/m/Y', strtotime($bom->approved_by_1_at)) : '' }}</div>
                                <div class="signature-title">Diperiksa oleh:</div>
                                <div class="signature-space"></div>
                                <div class="signature-name-line center">
                                    @if($bom->approvedBy1)
                                        ( {{ $bom->approvedBy1->nama }} )
                                    @else
                                        ( _________________________ )
                                    @endif
                                </div>
                                @if($bom->approvedBy1 && $bom->approvedBy1->nip)
                                    <div class="signature-nip">{{ $bom->approvedBy1->nip }}</div>
                                @endif
                            </div>
                            
                            <div class="signature-col">
                                <div class="signature-date right">Tanggal: {{ $bom->approved_by_2_at ? date('d/m/Y', strtotime($bom->approved_by_2_at)) : '' }}</div>
                                <div class="signature-title right">Disahkan oleh:</div>
                                <div class="signature-space"></div>
                                <div class="signature-name-line right">
                                    @if($bom->approvedBy2)
                                        ( {{ $bom->approvedBy2->nama }} )
                                    @else
                                        ( _________________________ )
                                    @endif
                                </div>
                                @if($bom->approvedBy2 && $bom->approvedBy2->nip)
                                    <div class="signature-nip right">{{ $bom->approvedBy2->nip }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endfor
    @endif
    
    <!-- Page Number - Fixed position di pojok kanan bawah -->
    <div class="page-number">
        Halaman 1 of {{ $totalPages }}
    </div>
</body>
</html>