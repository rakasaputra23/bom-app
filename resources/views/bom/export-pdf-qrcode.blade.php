<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bill of Material - {{ $bom->nomor_bom }}</title>
    <style>
        @page {
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
            margin-bottom: 25px;
            position: relative;
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
            width: 54%;
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
            width: 25%;
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
        
        /* FIXED TABLE LAYOUT */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            table-layout: fixed;
            position: relative;
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
            overflow: hidden;
            word-wrap: break-word;
            position: relative;
        }
        
        .main-table td {
            border: 2px solid #000;
            padding: 3px 2px;
            text-align: center;
            vertical-align: top;
            font-size: 7pt;
            height: auto;
            min-height: 20px;
            overflow: hidden;
            word-wrap: break-word;
            word-break: break-word;
            line-height: 1.1;
            position: relative;
        }
        
        .main-table td.text-left {
            text-align: left;
            padding-left: 4px;
        }
        
        .main-table td.text-right {
            text-align: right;
            padding-right: 4px;
        }
        
        /* FIXED COLUMN WIDTHS */
        .col-rev { 
            width: 4% !important; 
            min-width: 4% !important; 
            max-width: 4% !important;
        }
        .col-no { 
            width: 4% !important; 
            min-width: 4% !important; 
            max-width: 4% !important;
        }
        .col-kode { 
            width: 12% !important;
            min-width: 12% !important; 
            max-width: 12% !important;
        }
        .col-deskripsi { 
            width: 18% !important;
            min-width: 18% !important; 
            max-width: 18% !important;
        }
        .col-qty { 
            width: 5% !important; 
            min-width: 5% !important; 
            max-width: 5% !important;
        }
        .col-satuan { 
            width: 5% !important; 
            min-width: 5% !important; 
            max-width: 5% !important;
        }
        .col-spesifikasi { 
            width: 26% !important; 
            min-width: 26% !important; 
            max-width: 26% !important;
        }
        .col-keterangan { 
            width: 25% !important; 
            min-width: 25% !important; 
            max-width: 25% !important;
        }
        
        /* TEXT HANDLING */
        .break-word {
            word-wrap: break-word;
            word-break: break-all;
            hyphens: auto;
            overflow-wrap: break-word;
            white-space: normal;
            font-size: 6pt;
            line-height: 1.0;
        }
        
        /* Multi-row cell styling */
        .multi-row-cell {
            vertical-align: top !important;
            padding-top: 4px !important;
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
        
        /* QR Code Styles - keeping for potential future use */
        .qr-code {
            width: 35px;
            height: 35px;
            margin: 0 auto;
            display: block;
        }
        
        .qr-code img {
            width: 100%;
            height: 100%;
        }
        
        .signature-space-with-qr {
            height: 45px;
            margin: 4px 0;
            display: flex;
            align-items: center;
            justify-content: center;
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
            text-align: right; /* Changed from center to right */
            margin-right: 15px; /* Add some space from right edge */
            margin-left: 0px;
        }
        
        /* QR Code positioning for each column */
        .signature-col:first-child .qr-code {
            margin: 0; /* Left column - align left */
        }
        
        .signature-col:nth-child(2) .qr-code {
            margin: 0 auto; /* Middle column - center */
        }
        
        .signature-col:last-child .qr-code {
            margin: 0 70px 0 auto; /* Right column - align right with 70px margin */
        }

        
        .signature-nip {
            font-size: 7pt;
            text-align: center;
            margin-top: 2px;
        }
        
        .signature-nip.left {
            text-align: left;
        }
        
        .signature-nip.right {
            text-align: right; /* This was already correct */
            margin-right: 70px; /* Add some space from right edge to match name */
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
        }
        
        /* New style for right column QR code positioning - removed since not using QR codes */
        
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
        
        .empty-row {
            height: 20px;
        }
        
        .empty-row td {
            height: 20px;
            min-height: 20px;
            max-height: 20px;
            padding: 2px;
        }
        
        /* Force table constraints */
        .main-table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
        }
        
        .main-table th, .main-table td {
            border-collapse: collapse !important;
            box-sizing: border-box !important;
        }
        
        /* CSS untuk multi-halaman */
        @media print {
            .page-break {
                page-break-before: always;
            }
            
            .page-number {
                position: absolute;
                bottom: 5mm;
                right: 10mm;
            }
            
            .main-table tr {
                page-break-inside: avoid;
            }
        }
        
        .content-page {
            min-height: calc(100vh - 35mm);
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
        // KONFIGURASI UTAMA - PERBAIKAN
        $maxRowsPerPage = 16; // Maksimal 16 row per halaman (sesuai kebutuhan Anda)
        
        // Ambil semua item BOM
        $allItems = $bom->itemBom ? $bom->itemBom->values()->all() : [];
        
        // ALGORITMA PERBAIKAN - Pembagian halaman yang sederhana dan konsisten
        $pages = [];
        $itemsPerPage = $maxRowsPerPage; // Setiap item = 1 row
        $totalItems = count($allItems);
        
        if ($totalItems == 0) {
            // Jika tidak ada data, buat 1 halaman kosong
            $pages[] = [
                'items' => [],
                'startIndex' => 0,
                'itemCount' => 0,
                'emptyRows' => $maxRowsPerPage
            ];
        } else {
            // Bagi items ke halaman-halaman
            $totalPages = ceil($totalItems / $itemsPerPage);
            
            for ($pageNum = 0; $pageNum < $totalPages; $pageNum++) {
                $startIndex = $pageNum * $itemsPerPage;
                $endIndex = min($startIndex + $itemsPerPage - 1, $totalItems - 1);
                
                $pageItems = [];
                for ($i = $startIndex; $i <= $endIndex; $i++) {
                    $pageItems[] = $allItems[$i];
                }
                
                $itemCount = count($pageItems);
                $emptyRows = $maxRowsPerPage - $itemCount;
                
                $pages[] = [
                    'items' => $pageItems,
                    'startIndex' => $startIndex,
                    'itemCount' => $itemCount,
                    'emptyRows' => $emptyRows
                ];
            }
        }
        
        $totalPages = count($pages);
        
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
    
    @for($pageNum = 1; $pageNum <= $totalPages; $pageNum++)
        @if($pageNum > 1)
            <div class="page-break"></div>
        @endif
        
        @php
            $currentPageData = $pages[$pageNum - 1];
            $pageItems = $currentPageData['items'];
            $startIndex = $currentPageData['startIndex'];
            $itemCount = $currentPageData['itemCount'];
            $emptyRows = $currentPageData['emptyRows'];
        @endphp
        
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
                    <h2>{{ strtoupper($bom->kategori ?? 'TOOL') }}</h2>
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
                <colgroup>
                    <col class="col-rev">
                    <col class="col-no">
                    <col class="col-kode">
                    <col class="col-deskripsi">
                    <col class="col-qty">
                    <col class="col-satuan">
                    <col class="col-spesifikasi">
                    <col class="col-keterangan">
                </colgroup>
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
                    {{-- Tampilkan data items untuk halaman ini --}}
                    @foreach($pageItems as $index => $item)
                        @php
                            $globalIndex = $startIndex + $index; // Index global dari item
                        @endphp
                        <tr class="empty-row">
                            <td class="col-rev"></td>
                            <td class="col-no">{{ $globalIndex + 1 }}</td>
                            <td class="col-kode text-left">{{ ($item->kodeMaterial && isset($item->kodeMaterial->kode_material)) ? $item->kodeMaterial->kode_material : '' }}</td>
                            <td class="col-deskripsi text-left">{{ ($item->kodeMaterial && isset($item->kodeMaterial->nama_material)) ? $item->kodeMaterial->nama_material : '' }}</td>
                            <td class="col-qty text-right">
                                @php
                                    $qty = 0;
                                    if ($item->qty !== null && $item->qty !== 0) {
                                        $qty = $item->qty;
                                    } elseif ($item->kodeMaterial && isset($item->kodeMaterial->uom) && $item->kodeMaterial->uom && isset($item->kodeMaterial->uom->qty)) {
                                        $qty = $item->kodeMaterial->uom->qty;
                                    } else {
                                        $qty = 1;
                                    }
                                    echo number_format($qty, 0, ',', '.');
                                @endphp
                            </td>
                            <td class="col-satuan">
                                @php
                                    $satuan = '';
                                    if ($item->satuan && trim($item->satuan) !== '') {
                                        $satuan = $item->satuan;
                                    } elseif ($item->kodeMaterial && isset($item->kodeMaterial->uom) && $item->kodeMaterial->uom && isset($item->kodeMaterial->uom->satuan)) {
                                        $satuan = $item->kodeMaterial->uom->satuan;
                                    }
                                    echo $satuan;
                                @endphp
                            </td>
                            <td class="col-spesifikasi text-left">{{ ($item->kodeMaterial && isset($item->kodeMaterial->spesifikasi)) ? $item->kodeMaterial->spesifikasi : '' }}</td>
                            <td class="col-keterangan text-left">{{ isset($item->keterangan) ? $item->keterangan : '' }}</td>
                        </tr>
                    @endforeach
                    
                    {{-- Fill sisa halaman dengan row kosong --}}
                    @for($i = 0; $i < $emptyRows; $i++)
                        <tr class="empty-row">
                            <td class="col-rev">&nbsp;</td>
                            <td class="col-no">&nbsp;</td>
                            <td class="col-kode">&nbsp;</td>
                            <td class="col-deskripsi">&nbsp;</td>
                            <td class="col-qty">&nbsp;</td>
                            <td class="col-satuan">&nbsp;</td>
                            <td class="col-spesifikasi">&nbsp;</td>
                            <td class="col-keterangan">&nbsp;</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
            
            <!-- Signature Section dengan QR Code - MUNCUL DI SETIAP HALAMAN -->
            <div class="signature-section">
                <div class="signature-row">
                    <div class="signature-col">
                        <div class="signature-date">
                            Tanggal: {{ $bom->created_at ? date('d/m/Y', strtotime($bom->created_at)) : '' }}
                        </div>
                        <div class="signature-title">Disiapkan oleh:</div>
                        @if(isset($createdByQrCode) && $createdByQrCode)
                            <div class="signature-space-with-qr">
                                <div class="qr-code">
                                    <img src="data:image/svg+xml;base64,{{ $createdByQrCode }}" alt="QR Code Created By">
                                </div>
                            </div>
                        @else
                            <div class="signature-space"></div>
                        @endif
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
                        <div class="signature-date">
                            Tanggal: {{ $bom->approved_by_1_at ? date('d/m/Y', strtotime($bom->approved_by_1_at)) : '' }}
                        </div>
                        <div class="signature-title">Diperiksa oleh:</div>
                        @if(isset($approvedBy1QrCode) && $approvedBy1QrCode)
                            <div class="signature-space-with-qr">
                                <div class="qr-code">
                                    <img src="data:image/svg+xml;base64,{{ $approvedBy1QrCode }}" alt="QR Code Approved By 1">
                                </div>
                            </div>
                        @else
                            <div class="signature-space"></div>
                        @endif
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
                        <div class="signature-date right">
                            Tanggal: {{ $bom->approved_by_2_at ? date('d/m/Y', strtotime($bom->approved_by_2_at)) : '' }}
                        </div>
                        <div class="signature-title right">Disahkan oleh:</div>
                        @if(isset($approvedBy2QrCode) && $approvedBy2QrCode)
                            <div class="signature-space-with-qr">
                                <div class="qr-code">
                                    <img src="data:image/svg+xml;base64,{{ $approvedBy2QrCode }}" alt="QR Code Approved By 2">
                                </div>
                            </div>
                        @else
                            <div class="signature-space"></div>
                        @endif
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
        </div>
        
        <!-- Page Number -->
        <div class="page-number">
            Halaman {{ $pageNum }} of {{ $totalPages }}
        </div>
    @endfor
</body>
</html>