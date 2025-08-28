@extends('layouts.app')

@section('header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0">User Management</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">User Management</li>
      </ol>
    </div>
  </div>
@endsection

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
@php
    // Check permissions
    Auth::user()->refreshRelations();
    $canCreate = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.store');
    $canEdit = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.update');
    $canDelete = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.destroy');
    $canView = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.show');
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-users mr-1"></i>
                    Data User
                </h3>
                <div class="card-tools">
                    @if($canCreate)
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#userModal">
                        <i class="fas fa-plus"></i> Tambah User
                    </button>
                    @endif
                    <button type="button" class="btn btn-secondary btn-sm ml-1" onclick="refreshTable()" title="Refresh Data">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="userTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Posisi</th>
                                <th>Email</th>
                                <th>User Group</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal User -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Tambah User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="userForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nip">NIP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nip" name="nip" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama">Nama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="posisi">Posisi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="posisi" name="posisi" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_group_id">User Group <span class="text-danger">*</span></label>
                                <select class="form-control select2" id="user_group_id" name="user_group_id" required style="width: 100%;">
                                    <option value="">Pilih User Group</option>
                                    @foreach($userGroups as $group)
                                        <option value="{{ $group->id }}">{{ $group->nama }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmation">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail User -->
<div class="modal fade" id="detailUserModal" tabindex="-1" aria-labelledby="detailUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailUserModalLabel">Detail User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailUserContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Permission flags for JavaScript
    const permissions = {
        canCreate: {{ $canCreate ? 'true' : 'false' }},
        canEdit: {{ $canEdit ? 'true' : 'false' }},
        canDelete: {{ $canDelete ? 'true' : 'false' }},
        canView: {{ $canView ? 'true' : 'false' }}
    };

    // Initialize Select2 for User Group
    $('#user_group_id').select2({
        theme: 'bootstrap4',
        placeholder: 'Pilih User Group',
        allowClear: true,
        dropdownParent: $('#userModal')
    });

    // Password toggle functionality
    $('#togglePassword').on('click', function() {
        const passwordField = $('#password');
        const icon = $(this).find('i');
        
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    $('#togglePasswordConfirmation').on('click', function() {
        const passwordField = $('#password_confirmation');
        const icon = $(this).find('i');
        
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // IMPROVED: DataTables initialization with better configuration
    let table = $('#userTable').DataTable({
        processing: true,
        serverSide: false, // Client-side processing
        destroy: true, // Allow re-initialization
        ajax: {
            url: '{{ route("user.getData") }}',
            type: 'GET',
            cache: false, // Disable cache
            error: function(xhr, error, thrown) {
                console.error('DataTable Ajax Error:', xhr, error, thrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal memuat data. Silakan refresh halaman.'
                });
            }
        },
        columns: [
            { 
                data: 'nip',
                name: 'nip'
            },
            { 
                data: 'nama',
                name: 'nama'
            },
            { 
                data: 'posisi',
                name: 'posisi'
            },
            { 
                data: 'email',
                name: 'email'
            },
            { 
                data: 'group_nama',
                name: 'group_nama',
                orderable: false
            },
            { 
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'id',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    let buttons = '<div class="btn-group" role="group">';
                    
                    if (permissions.canView) {
                        buttons += `<button type="button" class="btn btn-sm btn-info" onclick="showDetail(${data})" title="Detail">
                            <i class="fas fa-eye"></i>
                        </button>`;
                    }
                    
                    if (permissions.canEdit) {
                        buttons += `<button type="button" class="btn btn-sm btn-warning" onclick="editUser(${data})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>`;
                    }
                    
                    if (permissions.canDelete) {
                        buttons += `<button type="button" class="btn btn-sm btn-danger" onclick="deleteUser(${data})" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>`;
                    }
                    
                    buttons += '</div>';
                    
                    if (!permissions.canView && !permissions.canEdit && !permissions.canDelete) {
                        return '<span class="text-muted">-</span>';
                    }
                    
                    return buttons;
                }
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
        },
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        order: [[1, 'asc']],
        // IMPROVED: Better draw callback for debugging
        drawCallback: function(settings) {
            console.log('DataTable redrawn with', settings.json ? settings.json.data.length : 'unknown', 'rows');
        }
    });

    // IMPROVED: Make table variable global for debugging
    window.userTable = table;

    // Form submission with improved error handling
    $('#userForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        let url = $('#userForm').data('action') || '{{ route("user.store") }}';
        let method = $('#userForm').data('method') || 'POST';
        
        if (method === 'PUT') {
            formData.append('_method', 'PUT');
        }

        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Form submit success:', response);
                if (response.success) {
                    $('#userModal').modal('hide');
                    // IMPROVED: Force reload with callback
                    table.ajax.reload(function() {
                        console.log('Table reloaded after form submit');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }, false);
                    resetForm();
                }
            },
            error: function(xhr) {
                console.error('Form submit error:', xhr);
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $('.form-control').removeClass('is-invalid');
                    $('.invalid-feedback').text('');
                    
                    $.each(errors, function(key, value) {
                        $(`[name="${key}"]`).addClass('is-invalid');
                        $(`[name="${key}"]`).siblings('.invalid-feedback').text(value[0]);
                    });
                } else {
                    Swal.fire('Error!', 'Terjadi kesalahan pada server', 'error');
                }
            },
            complete: function() {
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });

    // Reset modal when closed
    $('#userModal').on('hidden.bs.modal', function() {
        resetForm();
    });

    // Clear validation on input change
    $('input, select').on('change', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });
});

// IMPROVED: Global refresh function
function refreshTable() {
    if (window.userTable) {
        console.log('Manual table refresh triggered');
        window.userTable.ajax.reload(function() {
            console.log('Manual table refresh completed');
        }, false);
        
        // Show brief loading indicator
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1000
        });
        Toast.fire({
            icon: 'info',
            title: 'Memperbarui data...'
        });
    }
}

function resetForm() {
    $('#userForm')[0].reset();
    $('#userModalLabel').text('Tambah User');
    $('#userForm').removeData('action').removeData('method');
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').text('');
    $('#password').prop('required', true);
    $('#password_confirmation').prop('required', true);
    
    // Reset Select2
    $('#user_group_id').val(null).trigger('change');
    
    // Reset password visibility
    $('#password, #password_confirmation').attr('type', 'password');
    $('#togglePassword i, #togglePasswordConfirmation i').removeClass('fa-eye-slash').addClass('fa-eye');
}

function editUser(id) {
    if (!{{ $canEdit ? 'true' : 'false' }}) {
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak!',
            text: 'Anda tidak memiliki izin untuk mengedit data.'
        });
        return;
    }

    $.get(`{{ url('user') }}/${id}`, function(data) {
        $('#userModalLabel').text('Edit User');
        $('#userForm').data('action', `{{ url('user') }}/${id}`).data('method', 'PUT');
        
        $('#nip').val(data.nip);
        $('#nama').val(data.nama);
        $('#posisi').val(data.posisi);
        $('#user_group_id').val(data.user_group_id).trigger('change');
        $('#email').val(data.email);
        
        // Password tidak required saat edit
        $('#password').prop('required', false);
        $('#password_confirmation').prop('required', false);
        
        $('#userModal').modal('show');
    }).fail(function(xhr) {
        console.error('Edit user error:', xhr);
        Swal.fire('Error!', 'Gagal memuat data user', 'error');
    });
}

function showDetail(id) {
    if (!{{ $canView ? 'true' : 'false' }}) {
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak!',
            text: 'Anda tidak memiliki izin untuk melihat detail data.'
        });
        return;
    }

    $.get(`{{ url('user') }}/${id}`, function(data) {
        let content = `
            <div class="row">
                <div class="col-12">
                    <table class="table table-borderless">
                        <tr>
                            <td style="width: 30%;"><strong>NIP:</strong></td>
                            <td>${data.nip}</td>
                        </tr>
                        <tr>
                            <td><strong>Nama:</strong></td>
                            <td>${data.nama}</td>
                        </tr>
                        <tr>
                            <td><strong>Posisi:</strong></td>
                            <td>${data.posisi}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>${data.email}</td>
                        </tr>
                        <tr>
                            <td><strong>User Group:</strong></td>
                            <td>
                                <span class="badge badge-primary">${data.group ? data.group.nama : '-'}</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Dibuat:</strong></td>
                            <td>${new Date(data.created_at).toLocaleDateString('id-ID', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            })}</td>
                        </tr>
                        <tr>
                            <td><strong>Terakhir Diupdate:</strong></td>
                            <td>${new Date(data.updated_at).toLocaleDateString('id-ID', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            })}</td>
                        </tr>
                    </table>
                </div>
            </div>
        `;
        $('#detailUserContent').html(content);
        $('#detailUserModal').modal('show');
    }).fail(function(xhr) {
        console.error('Show detail error:', xhr);
        Swal.fire('Error!', 'Gagal memuat detail user', 'error');
    });
}

// COMPLETELY IMPROVED: Delete function with better error handling and debugging
function deleteUser(id) {
    if (!{{ $canDelete ? 'true' : 'false' }}) {
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak!',
            text: 'Anda tidak memiliki izin untuk menghapus data.'
        });
        return;
    }

    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data user akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading immediately
            Swal.fire({
                title: 'Menghapus...',
                text: 'Sedang memproses permintaan Anda',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: `{{ url('user') }}/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Delete response:', response);
                    
                    if (response && response.success) {
                        // IMPROVED: Multiple strategies to ensure table refresh
                        console.log('Delete successful, refreshing table...');
                        
                        // Strategy 1: Force reload with callback
                        if (window.userTable) {
                            window.userTable.ajax.reload(function(json) {
                                console.log('Table reloaded after delete, new data:', json);
                                
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message || 'User berhasil dihapus',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                
                            }, false); // false = don't reset paging
                        } else {
                            console.error('Table object not found, forcing page reload');
                            location.reload();
                        }
                        
                    } else {
                        console.error('Delete failed, response:', response);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response?.message || 'Terjadi kesalahan saat menghapus data'
                        });
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.error('Delete ajax error:', {
                        xhr: xhr,
                        textStatus: textStatus,
                        errorThrown: errorThrown,
                        status: xhr.status,
                        responseText: xhr.responseText
                    });
                    
                    let message = 'Terjadi kesalahan pada server';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    } else if (xhr.status === 404) {
                        message = 'Data tidak ditemukan';
                    } else if (xhr.status === 403) {
                        message = 'Akses ditolak';
                    } else if (xhr.status === 500) {
                        message = 'Kesalahan server internal';
                    } else if (xhr.status === 0) {
                        message = 'Koneksi terputus, periksa jaringan internet Anda';
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: message
                    });
                },
                timeout: 30000 // 30 seconds timeout
            });
        }
    });
}

// IMPROVED: Add keyboard shortcuts for debugging
$(document).keydown(function(e) {
    // Ctrl+Shift+R = Force refresh table (for debugging)
    if (e.ctrlKey && e.shiftKey && e.which === 82) {
        e.preventDefault();
        console.log('Force refresh triggered by keyboard shortcut');
        refreshTable();
    }
});

// IMPROVED: Add visibility change handler to refresh when tab becomes active
document.addEventListener('visibilitychange', function() {
    if (!document.hidden && window.userTable) {
        console.log('Tab became visible, refreshing table');
        window.userTable.ajax.reload(null, false);
    }
});
</script>
@endpush