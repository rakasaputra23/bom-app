@extends('layouts.app')

@section('title', 'Bill of Materials')

@section('header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0">Bill of Materials</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item active">BOM</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<!-- Filter Pencarian -->
<div class="row">
  <div class="col-md-4">
    <div class="form-group">
      <label>Cari Nomor BOM</label>
      <input type="text" class="form-control" id="search_nomor" placeholder="Cari berdasarkan nomor BOM...">
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Cari Proyek</label>
      <input type="text" class="form-control" id="search_proyek" placeholder="Cari berdasarkan proyek...">
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Cari Revisi</label>
      <input type="text" class="form-control" id="search_revisi" placeholder="Cari berdasarkan revisi...">
    </div>
  </div>
</div>

<!-- DataTable -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar BOM</h3>
    <div class="card-tools">
      <a href="{{ route('bom.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Buat BOM Baru
      </a>
      <button type="button" class="btn btn-success" id="exportExcelBtn">
        <i class="fas fa-file-excel"></i> Excel
      </button>
      <button type="button" class="btn btn-danger" id="exportPdfBtn">
        <i class="fas fa-file-pdf"></i> PDF
      </button>
      <button type="button" class="btn btn-info" id="printBtn">
        <i class="fas fa-print"></i> Print
      </button>
      <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="bomTable" class="table table-bordered table-striped table-hover">
        <thead>
          <tr>
            <th style="width: 5%;">No</th>
            <th style="width: 15%;">Nomor</th>
            <th style="width: 20%;">Proyek</th>
            <th style="width: 15%;">Tgl. Terbit</th>
            <th style="width: 15%;">Revisi List</th>
            <th style="width: 20%;">Kategori</th>
            <th style="width: 10%;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr data-bom-id="1">
            <td>1</td>
            <td>IV-096-001</td>
            <td>Proyek Pembangunan Hotel</td>
            <td>15/06/2023</td>
            <td>Rev. 1.0</td>
            <td>JIG, TOOL DAN MAL</td>
            <td>
              <button class="btn btn-sm btn-info view-btn" title="Lihat">
                <i class="fas fa-eye"></i>
              </button>
              <button class="btn btn-sm btn-warning" title="Edit">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-sm btn-danger delete-btn" title="Hapus">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          <tr data-bom-id="2">
            <td>2</td>
            <td>IV-096-002</td>
            <td>Proyek Apartemen Taman</td>
            <td>22/06/2023</td>
            <td>Rev. 2.1</td>
            <td>TOOLS</td>
            <td>
              <button class="btn btn-sm btn-info view-btn" title="Lihat">
                <i class="fas fa-eye"></i>
              </button>
              <button class="btn btn-sm btn-warning" title="Edit">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-sm btn-danger delete-btn" title="Hapus">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewModalLabel">Detail BOM</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="bom-template">
          <div class="row mb-4">
            <div class="col-md-12 text-center">
              <h4>BILL OF MATERIAL</h4>
            </div>
          </div>
          
          <div class="row mb-4">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-4 col-form-label">Nomor</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="view_nomor" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-4 col-form-label">Proyek</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="view_proyek" readonly>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-sm-4 col-form-label">Tgl. Terbit</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="view_tanggal" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-4 col-form-label">Revisi List</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="view_revisi" readonly>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row mb-4">
            <div class="col-md-12 text-center">
              <h5 id="view_kategori">JIG, TOOL DAN MAL / TOOLS / CONSUMABLE TOOLS / SPECIAL PROCESS</h5>
            </div>
          </div>
          
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 5%;">REV</th>
                  <th style="width: 5%;">NO.</th>
                  <th style="width: 15%;">KODE MATERIAL</th>
                  <th style="width: 30%;">DESKRIPSI MATERIAL</th>
                  <th style="width: 10%;">QTY</th>
                  <th style="width: 10%;">SATUAN</th>
                  <th style="width: 15%;">SPESIFIKASI</th>
                  <th style="width: 10%;">KETERANGAN</th>
                </tr>
              </thead>
              <tbody id="view_items">
                <!-- Items will be populated here -->
              </tbody>
            </table>
          </div>
          
          <div class="row mt-4">
            <div class="col-md-4">
              <div class="form-group">
                <label>Tanggal</label>
                <input type="text" class="form-control" id="view_tanggal_dibuat" readonly>
              </div>
              <div class="form-group">
                <label>Disiapkan oleh</label>
                <input type="text" class="form-control" id="view_dibuat_oleh" readonly>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Tanggal</label>
                <input type="text" class="form-control" id="view_tanggal_periksa" readonly>
              </div>
              <div class="form-group">
                <label>Diperiksa oleh</label>
                <input type="text" class="form-control" id="view_diperiksa_oleh" readonly>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Tanggal</label>
                <input type="text" class="form-control" id="view_tanggal_sahkan" readonly>
              </div>
              <div class="form-group">
                <label>Disahkan oleh</label>
                <input type="text" class="form-control" id="view_disahkan_oleh" readonly>
              </div>
            </div>
          </div>
          
          <div class="row mt-2">
            <div class="col-md-12 text-right">
              <p>Form No.: IV-096 Rev.0</p>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus BOM <strong id="delete_item_name"></strong>?</p>
        <div class="alert alert-warning">
          <i class="fas fa-exclamation-triangle"></i>
          Data yang telah dihapus tidak dapat dikembalikan!
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<style>
  .bom-template {
    font-family: Arial, sans-serif;
    padding: 20px;
  }
  .bom-template h4, .bom-template h5 {
    font-weight: bold;
  }
  .bom-template table {
    width: 100%;
    border-collapse: collapse;
  }
  .bom-template table th, 
  .bom-template table td {
    border: 1px solid #000;
    padding: 8px;
    text-align: left;
  }
  .bom-template table th {
    background-color: #f2f2f2;
  }
  @media print {
    body * {
      visibility: hidden;
    }
    .bom-template, .bom-template * {
      visibility: visible;
    }
    .bom-template {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
    }
    .no-print {
      display: none !important;
    }
  }
</style>
@endpush

@push('scripts')
<!-- DataTables & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
<!-- Library XLSX untuk export Excel -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script>
// Sample data for BOM items
const bomItems = {
  1: [
    { rev: '', no: '1', kode: 'MAT-001', deskripsi: 'Besi Beton 10mm', qty: '150', satuan: 'Batang', spesifikasi: 'SNI', keterangan: 'Untuk kolom utama' },
    { rev: '', no: '2', kode: 'MAT-002', deskripsi: 'Semen Portland', qty: '50', satuan: 'Sak', spesifikasi: 'Grade 40', keterangan: '' },
    { rev: '', no: '3', kode: 'MAT-003', deskripsi: 'Pasir Beton', qty: '5', satuan: 'm³', spesifikasi: 'Kualitas baik', keterangan: '' }
  ],
  2: [
    { rev: '', no: '1', kode: 'TOOL-001', deskripsi: 'Gerinda Tangan', qty: '2', satuan: 'Unit', spesifikasi: 'Maktec MT90', keterangan: 'Untuk potong besi' },
    { rev: '', no: '2', kode: 'TOOL-002', deskripsi: 'Bor Listrik', qty: '3', satuan: 'Unit', spesifikasi: 'Makita HP1630', keterangan: '' },
    { rev: '', no: '3', kode: 'TOOL-003', deskripsi: 'Mesin Las', qty: '1', satuan: 'Unit', spesifikasi: 'Las Listrik 900W', keterangan: 'Untuk penyambungan besi' }
  ]
};

$(document).ready(function() {
  // Initialize DataTable
  var table = $('#bomTable').DataTable({
    responsive: true,
    autoWidth: false,
    processing: true,
    language: {
      search: "Cari:",
      lengthMenu: "Tampilkan _MENU_ data per halaman",
      zeroRecords: "Data tidak ditemukan",
      info: "Menampilkan halaman _PAGE_ dari _PAGES_",
      infoEmpty: "Data tidak tersedia",
      infoFiltered: "(difilter dari _MAX_ total data)",
      paginate: {
        first: "Pertama",
        last: "Terakhir",
        next: "Selanjutnya",
        previous: "Sebelumnya"
      },
      processing: "Sedang memproses..."
    },
    pageLength: 10,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
    order: [[1, 'asc']],
    columnDefs: [
      { 
        targets: [0, 6], 
        orderable: false 
      }
    ]
  });

  // Export to Excel with company template
  $('#exportExcelBtn').on('click', function() {
    console.log('Tombol Excel diklik'); // Debugging
    
    try {
      // Get selected BOM data
      var row = $('#bomTable tbody tr:first');
      var bomId = row.data('bom-id');
      var nomor = row.find('td:eq(1)').text();
      var proyek = row.find('td:eq(2)').text();
      var tanggal = row.find('td:eq(3)').text();
      var revisi = row.find('td:eq(4)').text();
      var kategori = row.find('td:eq(5)').text();
      
      // Create workbook
      var workbook = XLSX.utils.book_new();
      
      // Create worksheet data that exactly matches the template
      var wsData = [
          ['', '', '', '', '', '', '', '', ''], // Row 1 (empty)
          ['', '', '', '', '', '', '', '', ''], // Row 2 (empty)
          ['', '', '', '', 'BILL OF MATERIAL', '', '', '', 'Nomor              : ' + nomor],
          ['', '', '', '', '', '', '', '', 'Proyek              : ' + proyek],
          ['', '', '', '', kategori, '', '', '', 'Tgl. Terbit        : ' + tanggal],
          ['', '', '', '', '', '', '', '', 'Revisi List        : ' + revisi],
          ['', '', '', '', '', '', '', '', ''],
          ['', 'REV', 'NO.', 'KODE MATERIAL', 'DESKRIPSI MATERIAL', 'QTY', 'SATUAN', 'SPESIFIKASI', 'KETERANGAN'],
      ];
      
      // Add items
      bomItems[bomId].forEach(function(item) {
          wsData.push([
              '',
              item.rev,
              item.no,
              item.kode,
              item.deskripsi,
              item.qty,
              item.satuan,
              item.spesifikasi,
              item.keterangan
          ]);
      });
      
      // Add empty rows to match template structure
      for (var i = 0; i < 15 - bomItems[bomId].length; i++) {
          wsData.push(['', '', '', '', '', '', '', '', '']);
      }
      
      // Add signature section
      wsData.push(
          ['Tanggal             :', '', '', '', '', 'Tanggal             :', '', '', 'Tanggal            :'],
          ['Disiapkan oleh :', '', '', '', '', 'Diperiksa oleh  :', '', '', 'Disahkan oleh :'],
          ['', '', '', '', '', '', '', '', ''],
          ['', '', '( .............................................................)', '', '', '', '( .............................................................)', '', '( .............................................................)'],
          ['', '', '', '', '', '', '', '', ''],
          ['Form No.: IV-096 Rev.0', '', '', '', '', '', '', '', '']
      );
      
      // Create worksheet
      var ws = XLSX.utils.aoa_to_sheet(wsData);
      
      // Set column widths to match template
      ws['!cols'] = [
          { width: 10 }, // A
          { width: 5 },  // B (REV)
          { width: 5 },  // C (NO.)
          { width: 15 }, // D (KODE MATERIAL)
          { width: 30 }, // E (DESKRIPSI MATERIAL)
          { width: 10 }, // F (QTY)
          { width: 10 }, // G (SATUAN)
          { width: 15 }, // H (SPESIFIKASI)
          { width: 20 }  // I (KETERANGAN)
      ];
      
      // Merge cells for headers
      ws['!merges'] = [
          // BILL OF MATERIAL title (D3:G3)
          { s: { r: 2, c: 4 }, e: { r: 2, c: 7 } },
          // Kategori title (D5:G5)
          { s: { r: 4, c: 4 }, e: { r: 4, c: 7 } }
      ];
      
      // Add worksheet to workbook
      XLSX.utils.book_append_sheet(workbook, ws, "BOM");
      
      // Export Excel file
      XLSX.writeFile(workbook, `BOM_${nomor}.xlsx`);
      
    } catch (error) {
      console.error('Error exporting to Excel:', error);
      alert('Terjadi kesalahan saat mengekspor ke Excel: ' + error.message);
    }
  });

  // Export to PDF with company template
  $('#exportPdfBtn').on('click', function() {
    console.log('Tombol PDF diklik'); // Debugging
    
    try {
      // Get selected BOM data
      var row = $('#bomTable tbody tr:first');
      var bomId = row.data('bom-id');
      var nomor = row.find('td:eq(1)').text();
      var proyek = row.find('td:eq(2)').text();
      var tanggal = row.find('td:eq(3)').text();
      var revisi = row.find('td:eq(4)').text();
      var kategori = row.find('td:eq(5)').text();
      
      // Check if pdfMake is available
      if (typeof pdfMake === 'undefined') {
        throw new Error('PDF library not loaded');
      }
      
      // Prepare PDF content
      var docDefinition = {
          pageSize: 'A4',
          pageOrientation: 'portrait',
          pageMargins: [40, 60, 40, 60],
          content: [
              // Header with company info
              { text: 'INKA MULTI SOLUSI', style: 'companyHeader' },
              { canvas: [{ type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 1 }] },
              { text: '\n' },
              
              // BOM Title
              { text: 'BILL OF MATERIAL', style: 'title' },
              { text: '\n' },
              
              // Information columns
              {
                  columns: [
                      {
                          width: '*',
                          text: ''
                      },
                      {
                          width: 'auto',
                          text: [
                              { text: 'Nomor              : ', bold: true }, nomor + '\n',
                              { text: 'Proyek              : ', bold: true }, proyek + '\n',
                              { text: 'Tgl. Terbit        : ', bold: true }, tanggal + '\n',
                              { text: 'Revisi List        : ', bold: true }, revisi
                          ],
                          alignment: 'left'
                      }
                  ]
              },
              { text: '\n' },
              
              // Category
              { text: kategori, style: 'category', alignment: 'center' },
              { text: '\n' },
              
              // Items table
              {
                  table: {
                      headerRows: 1,
                      widths: ['5%', '5%', '15%', '30%', '10%', '10%', '15%', '10%'],
                      body: [
                          [
                              { text: 'REV', style: 'tableHeader' },
                              { text: 'NO.', style: 'tableHeader' },
                              { text: 'KODE MATERIAL', style: 'tableHeader' },
                              { text: 'DESKRIPSI MATERIAL', style: 'tableHeader' },
                              { text: 'QTY', style: 'tableHeader' },
                              { text: 'SATUAN', style: 'tableHeader' },
                              { text: 'SPESIFIKASI', style: 'tableHeader' },
                              { text: 'KETERANGAN', style: 'tableHeader' }
                          ],
                          ...bomItems[bomId].map(item => [
                              { text: item.rev || '', alignment: 'center' },
                              { text: item.no, alignment: 'center' },
                              item.kode,
                              item.deskripsi,
                              { text: item.qty, alignment: 'center' },
                              { text: item.satuan, alignment: 'center' },
                              item.spesifikasi,
                              item.keterangan
                          ])
                      ]
                  }
              },
              
              // Empty space for additional items
              { text: '\n'.repeat(10) },
              
              // Signature section
              {
                  columns: [
                      {
                          width: '33%',
                          stack: [
                              { text: 'Tanggal             :' },
                              { text: 'Disiapkan oleh :' },
                              { text: '\n' },
                              { canvas: [{ type: 'line', x1: 0, y1: 0, x2: 150, y2: 0, lineWidth: 1 }] }
                          ]
                      },
                      {
                          width: '33%',
                          stack: [
                              { text: 'Tanggal             :' },
                              { text: 'Diperiksa oleh  :' },
                              { text: '\n' },
                              { canvas: [{ type: 'line', x1: 0, y1: 0, x2: 150, y2: 0, lineWidth: 1 }] },
                              { text: '( .............................................................)', alignment: 'center', margin: [0, 5, 0, 0] }
                          ]
                      },
                      {
                          width: '33%',
                          stack: [
                              { text: 'Tanggal            :' },
                              { text: 'Disahkan oleh :' },
                              { text: '\n' },
                              { canvas: [{ type: 'line', x1: 0, y1: 0, x2: 150, y2: 0, lineWidth: 1 }] },
                              { text: '( .............................................................)', alignment: 'center', margin: [0, 5, 0, 0] }
                          ]
                      }
                  ]
              },
              
              // Form number
              { text: '\n' },
              { text: 'Form No.: IV-096 Rev.0', alignment: 'left' }
          ],
          styles: {
              companyHeader: {
                  fontSize: 12,
                  bold: true,
                  alignment: 'center',
                  margin: [0, 0, 0, 5]
              },
              title: {
                  fontSize: 16,
                  bold: true,
                  alignment: 'center'
              },
              category: {
                  fontSize: 14,
                  bold: true
              },
              tableHeader: {
                  bold: true,
                  fontSize: 10,
                  fillColor: '#f2f2f2'
              }
          },
          defaultStyle: {
              fontSize: 10,
              font: 'Helvetica'
          }
      };
      
      // Generate and download PDF
      pdfMake.createPdf(docDefinition).download(`BOM_${nomor}.pdf`);
      
    } catch (error) {
      console.error('Error exporting to PDF:', error);
      alert('Terjadi kesalahan saat mengekspor ke PDF: ' + error.message);
    }
  });

  // Print function with company template
  $('#printBtn').on('click', function() {
    console.log('Tombol Print diklik'); // Debugging
    
    try {
      // Get selected BOM data
      var row = $('#bomTable tbody tr:first');
      var bomId = row.data('bom-id');
      var nomor = row.find('td:eq(1)').text();
      var proyek = row.find('td:eq(2)').text();
      var tanggal = row.find('td:eq(3)').text();
      var revisi = row.find('td:eq(4)').text();
      var kategori = row.find('td:eq(5)').text();
      
      // Create print window
      var printWindow = window.open('', '_blank');
      
      // HTML content that matches the template
      var printContent = `
          <!DOCTYPE html>
          <html>
          <head>
              <title>BOM ${nomor}</title>
              <style>
                  body {
                      font-family: Arial, sans-serif;
                      font-size: 10pt;
                      margin: 0;
                      padding: 20px;
                  }
                  .header {
                      text-align: center;
                      margin-bottom: 20px;
                  }
                  .company-name {
                      font-weight: bold;
                      font-size: 12pt;
                  }
                  .title {
                      font-weight: bold;
                      font-size: 14pt;
                      text-align: center;
                      margin: 10px 0;
                  }
                  .info-container {
                      display: flex;
                      margin-bottom: 15px;
                  }
                  .info-left {
                      width: 60%;
                  }
                  .info-right {
                      width: 40%;
                  }
                  .category {
                      font-weight: bold;
                      font-size: 12pt;
                      text-align: center;
                      margin: 10px 0;
                  }
                  table {
                      width: 100%;
                      border-collapse: collapse;
                      margin-top: 10px;
                  }
                  th, td {
                      border: 1px solid #000;
                      padding: 5px;
                  }
                  th {
                      background-color: #f2f2f2;
                      text-align: center;
                  }
                  .signature-section {
                      margin-top: 50px;
                  }
                  .signature-row {
                      display: flex;
                      justify-content: space-between;
                  }
                  .signature-col {
                      width: 30%;
                  }
                  .signature-line {
                      border-top: 1px solid #000;
                      margin-top: 60px;
                  }
                  .form-number {
                      margin-top: 20px;
                      font-size: 9pt;
                  }
                  @media print {
                      body {
                          padding: 0;
                      }
                  }
              </style>
          </head>
          <body>
              <div class="header">
                  <div class="company-name">INKA MULTI SOLUSI</div>
                  <hr>
              </div>
              
              <div class="title">BILL OF MATERIAL</div>
              
              <div class="info-container">
                  <div class="info-left"></div>
                  <div class="info-right">
                      <div><strong>Nomor              :</strong> ${nomor}</div>
                      <div><strong>Proyek              :</strong> ${proyek}</div>
                      <div><strong>Tgl. Terbit        :</strong> ${tanggal}</div>
                      <div><strong>Revisi List        :</strong> ${revisi}</div>
                  </div>
              </div>
              
              <div class="category">${kategori}</div>
              
              <table>
                  <thead>
                      <tr>
                          <th style="width: 5%;">REV</th>
                          <th style="width: 5%;">NO.</th>
                          <th style="width: 15%;">KODE MATERIAL</th>
                          <th style="width: 30%;">DESKRIPSI MATERIAL</th>
                          <th style="width: 10%;">QTY</th>
                          <th style="width: 10%;">SATUAN</th>
                          <th style="width: 15%;">SPESIFIKASI</th>
                          <th style="width: 10%;">KETERANGAN</th>
                      </tr>
                  </thead>
                  <tbody>
                      ${bomItems[bomId].map(item => `
                          <tr>
                              <td style="text-align: center;">${item.rev || ''}</td>
                              <td style="text-align: center;">${item.no}</td>
                              <td>${item.kode}</td>
                              <td>${item.deskripsi}</td>
                              <td style="text-align: center;">${item.qty}</td>
                              <td style="text-align: center;">${item.satuan}</td>
                              <td>${item.spesifikasi}</td>
                              <td>${item.keterangan}</td>
                          </tr>
                      `).join('')}
                  </tbody>
              </table>
              
              <div style="height: 300px;"></div>
              
              <div class="signature-section">
                  <div class="signature-row">
                      <div class="signature-col">
                          <div>Tanggal             :</div>
                          <div>Disiapkan oleh :</div>
                          <div class="signature-line"></div>
                      </div>
                      <div class="signature-col">
                          <div>Tanggal             :</div>
                          <div>Diperiksa oleh  :</div>
                          <div class="signature-line"></div>
                          <div style="text-align: center;">( .............................................................)</div>
                      </div>
                      <div class="signature-col">
                          <div>Tanggal            :</div>
                          <div>Disahkan oleh :</div>
                          <div class="signature-line"></div>
                          <div style="text-align: center;">( .............................................................)</div>
                      </div>
                  </div>
              </div>
              
              <div class="form-number">Form No.: IV-096 Rev.0</div>
              
              <script>
                  window.onload = function() {
                      window.print();
                      setTimeout(function() {
                          window.close();
                      }, 1000);
                  };
              </script>
          </body>
          </html>
      `;
      
      printWindow.document.open();
      printWindow.document.write(printContent);
      printWindow.document.close();
      
    } catch (error) {
      console.error('Error printing:', error);
      alert('Terjadi kesalahan saat mencetak: ' + error.message);
    }
  });

  // Tambahkan event handler untuk tombol view
  $('.view-btn').on('click', function() {
    var row = $(this).closest('tr');
    var bomId = row.data('bom-id');
    
    // Isi data ke modal view
    $('#view_nomor').text(row.find('td:eq(1)').text());
    $('#view_proyek').text(row.find('td:eq(2)').text());
    $('#view_tanggal').text(row.find('td:eq(3)').text());
    $('#view_revisi').text(row.find('td:eq(4)').text());
    $('#view_kategori').text(row.find('td:eq(5)').text());
    
    // Isi items
    var itemsHtml = '';
    bomItems[bomId].forEach(function(item) {
      itemsHtml += `
        <tr>
          <td>${item.rev}</td>
          <td>${item.no}</td>
          <td>${item.kode}</td>
          <td>${item.deskripsi}</td>
          <td>${item.qty}</td>
          <td>${item.satuan}</td>
          <td>${item.spesifikasi}</td>
          <td>${item.keterangan}</td>
        </tr>
      `;
    });
    $('#view_items').html(itemsHtml);
    
    // Tampilkan modal
    $('#viewModal').modal('show');
  });
});
</script>
@endpush