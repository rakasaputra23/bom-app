@extends('layouts.app')

@section('header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0">User Group Management</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">User Group</li>
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
    $canCreate = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.group.store');
    $canEdit = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.group.update');
    $canDelete = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.group.destroy');
    $canView = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.group.show');
    $canManagePermissions = Auth::user()->isSuperAdmin() || Auth::user()->hasPermission('user.group.permissions');
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-users-cog mr-1"></i>
                    Data User Group
                </h3>
                <div class="card-tools">
                    @if($canCreate)
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#userGroupModal">
                        <i class="fas fa-plus"></i> Tambah User Group
                    </button>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="userGroupTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Group</th>
                                <th>Jumlah User</th>
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

<!-- Modal User Group -->
<div class="modal fade" id="userGroupModal" tabindex="-1" aria-labelledby="userGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userGroupModalLabel">Tambah User Group</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="userGroupForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama">Nama Group <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Permissions</label>
                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            @if(count($permissions) > 0)
                                @foreach($permissions as $permission)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" 
                                               value="{{ $permission->id }}" id="permission_{{ $permission->id }}">
                                        <label class="form-check-label" for="permission_{{ $permission->id }}">
                                            {{ $permission->deskripsi }}
                                            @if($permission->route_name)
                                                <small class="text-muted d-block">({{ $permission->route_name }})</small>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted mb-0">Tidak ada permissions yang tersedia</p>
                            @endif
                        </div>
                        <div class="invalid-feedback"></div>
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

<!-- Modal Detail User Group -->
<div class="modal fade" id="detailUserGroupModal" tabindex="-1" aria-labelledby="detailUserGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailUserGroupModalLabel">Detail User Group</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailUserGroupContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Permissions -->
<div class="modal fade" id="permissionsModal" tabindex="-1" aria-labelledby="permissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="permissionsModalLabel">Manage Permissions - <span id="permissionGroupName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="permissionUserGroupId">
                <div id="permissionsContainer"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="updatePermissionsBtn">
                    <i class="fas fa-save"></i> Update Permissions
                </button>
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
        canView: {{ $canView ? 'true' : 'false' }},
        canManagePermissions: {{ $canManagePermissions ? 'true' : 'false' }}
    };

    // Pastikan DataTables sudah dimuat
    if (typeof $.fn.DataTable === 'undefined') {
        console.error('DataTables tidak dimuat dengan benar');
        return;
    }

    let table = $('#userGroupTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '{{ route("user.group.getData") }}',
            type: 'GET'
        },
        columns: [
            { data: 'nama' },
            { data: 'users_count' },
            { 
                data: 'created_at',
                render: function(data) {
                    if (!data) return '-';
                    return new Date(data).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: '2-digit', 
                        year: 'numeric'
                    });
                }
            },
            {
                data: 'id',
                render: function(data, type, row) {
                    let buttons = '<div class="btn-group" role="group">';
                    
                    // Tombol Detail - hanya muncul jika ada permission
                    if (permissions.canView) {
                        buttons += `<button type="button" class="btn btn-sm btn-info" onclick="showDetail(${data})" title="Detail">
                            <i class="fas fa-eye"></i>
                        </button>`;
                    }
                    
                    // Tombol Permissions - hanya muncul jika ada permission
                    if (permissions.canManagePermissions) {
                        buttons += `<button type="button" class="btn btn-sm btn-secondary" onclick="managePermissions(${data})" title="Permissions">
                            <i class="fas fa-key"></i>
                        </button>`;
                    }
                    
                    // Tombol Edit - hanya muncul jika ada permission
                    if (permissions.canEdit) {
                        buttons += `<button type="button" class="btn btn-sm btn-warning" onclick="editUserGroup(${data})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>`;
                    }
                    
                    // Tombol Hapus - hanya muncul jika ada permission
                    if (permissions.canDelete) {
                        buttons += `<button type="button" class="btn btn-sm btn-danger" onclick="deleteUserGroup(${data})" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>`;
                    }
                    
                    buttons += '</div>';
                    
                    // Jika tidak ada permission sama sekali, tampilkan dash
                    if (!permissions.canView && !permissions.canEdit && !permissions.canDelete && !permissions.canManagePermissions) {
                        return '-';
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
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
        order: [[0, 'asc']]
    });

    // Form submission
    $('#userGroupForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        let url = $('#userGroupForm').data('action') || '{{ route("user.group.store") }}';
        let method = $('#userGroupForm').data('method') || 'POST';
        
        if (method === 'PUT') {
            formData.append('_method', 'PUT');
        }

        // Show loading state
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
                if (response.success) {
                    $('#userGroupModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire('Berhasil!', response.message, 'success');
                    resetForm();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $('.form-control, .form-check-input, .border.rounded').removeClass('is-invalid');
                    $('.invalid-feedback').text('');
                    
                    $.each(errors, function(key, value) {
                        if (key === 'permissions') {
                            $('.border.rounded').addClass('is-invalid');
                            $('.border.rounded').siblings('.invalid-feedback').text(value[0]);
                        } else {
                            $(`[name="${key}"]`).addClass('is-invalid');
                            $(`[name="${key}"]`).siblings('.invalid-feedback').text(value[0]);
                        }
                    });
                } else {
                    Swal.fire('Error!', 'Terjadi kesalahan pada server', 'error');
                }
            },
            complete: function() {
                // Reset button state
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });

    // Update permissions button
    $('#updatePermissionsBtn').on('click', function() {
        updatePermissions();
    });

    // Reset modal when closed
    $('#userGroupModal').on('hidden.bs.modal', function() {
        resetForm();
    });

    // Clear validation on input change
    $('input, select').on('change', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });
});

function resetForm() {
    $('#userGroupForm')[0].reset();
    $('#userGroupModalLabel').text('Tambah User Group');
    $('#userGroupForm').removeData('action').removeData('method');
    $('.form-control, .form-check-input, .border.rounded').removeClass('is-invalid');
    $('.invalid-feedback').text('');
}

function editUserGroup(id) {
    // Check permission di JavaScript juga untuk keamanan tambahan
    const permissions = {
        canEdit: {{ $canEdit ? 'true' : 'false' }}
    };
    
    if (!permissions.canEdit) {
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak!',
            text: 'Anda tidak memiliki izin untuk mengedit data.'
        });
        return;
    }

    $.get(`{{ url('user-group') }}/${id}`, function(data) {
        $('#userGroupModalLabel').text('Edit User Group');
        $('#userGroupForm').data('action', `{{ url('user-group') }}/${id}`).data('method', 'PUT');
        
        $('#nama').val(data.userGroup.nama);
        
        // Reset all checkboxes
        $('input[name="permissions[]"]').prop('checked', false);
        
        // Check assigned permissions
        if (data.permissions) {
            data.permissions.forEach(permission => {
                $(`#permission_${permission.id}`).prop('checked', true);
            });
        }
        
        $('#userGroupModal').modal('show');
    }).fail(function() {
        Swal.fire('Error!', 'Gagal memuat data user group', 'error');
    });
}

function showDetail(id) {
    // Check permission di JavaScript juga untuk keamanan tambahan
    const permissions = {
        canView: {{ $canView ? 'true' : 'false' }}
    };
    
    if (!permissions.canView) {
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak!',
            text: 'Anda tidak memiliki izin untuk melihat detail data.'
        });
        return;
    }

    $.get(`{{ url('user-group') }}/${id}`, function(data) {
        // Format tanggal created_at
        let createdAt = '-';
        if (data.userGroup.created_at) {
            try {
                let date = new Date(data.userGroup.created_at);
                createdAt = date.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            } catch (e) {
                console.error('Error parsing date:', e);
            }
        }

        // Format permissions list
        let permissionsList = '<ul class="list-unstyled">';
        if (data.permissions && data.permissions.length > 0) {
            data.permissions.forEach(permission => {
                permissionsList += `<li><i class="fas fa-check-circle text-success mr-2"></i> ${permission.deskripsi}`;
                if (permission.route_name) {
                    permissionsList += ` <small class="text-muted">(${permission.route_name})</small>`;
                }
                permissionsList += '</li>';
            });
        } else {
            permissionsList += '<li class="text-muted">Tidak ada permissions</li>';
        }
        permissionsList += '</ul>';

        // Format users list
        let usersList = '<ul class="list-unstyled">';
        if (data.users && data.users.length > 0) {
            data.users.forEach(user => {
                usersList += `<li><i class="fas fa-user mr-2"></i> ${user.nama}`;
                if (user.nip) {
                    usersList += ` <small class="text-muted">(${user.nip})</small>`;
                }
                usersList += '</li>';
            });
        } else {
            usersList += '<li class="text-muted">Tidak ada user dalam group ini</li>';
        }
        usersList += '</ul>';

        let content = `
            <div class="row">
                <div class="col-12">
                    <table class="table table-borderless">
                        <tr>
                            <td style="width: 30%;"><strong>Nama Group:</strong></td>
                            <td>${data.userGroup.nama}</td>
                        </tr>
                        <tr>
                            <td><strong>Jumlah User:</strong></td>
                            <td>${data.users ? data.users.length : 0} user</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Dibuat:</strong></td>
                            <td>${createdAt}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <h6><strong>Permissions:</strong></h6>
                    <div class="mb-3">
                        ${permissionsList}
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <h6><strong>Users dalam Group:</strong></h6>
                    <div>
                        ${usersList}
                    </div>
                </div>
            </div>
        `;
        
        $('#detailUserGroupContent').html(content);
        $('#detailUserGroupModal').modal('show');
    }).fail(function() {
        Swal.fire('Error!', 'Gagal memuat detail user group', 'error');
    });
}

function deleteUserGroup(id) {
    // Check permission di JavaScript juga untuk keamanan tambahan
    const permissions = {
        canDelete: {{ $canDelete ? 'true' : 'false' }}
    };
    
    if (!permissions.canDelete) {
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak!',
            text: 'Anda tidak memiliki izin untuk menghapus data.'
        });
        return;
    }

    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data user group akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Menghapus...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: `{{ url('user-group') }}/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        table.ajax.reload();
                        Swal.fire('Berhasil!', response.message, 'success');
                    }
                },
                error: function(xhr) {
                    let message = xhr.responseJSON?.message || 'Terjadi kesalahan pada server';
                    Swal.fire('Error!', message, 'error');
                }
            });
        }
    });
}

function managePermissions(id) {
    // Check permission di JavaScript juga untuk keamanan tambahan
    const permissions = {
        canManagePermissions: {{ $canManagePermissions ? 'true' : 'false' }}
    };
    
    if (!permissions.canManagePermissions) {
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak!',
            text: 'Anda tidak memiliki izin untuk mengelola permissions.'
        });
        return;
    }

    $.ajax({
        url: `{{ url('user-group') }}/${id}/permissions`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                // Populate permissions modal
                $('#permissionUserGroupId').val(id);
                $('#permissionGroupName').text(response.userGroup.nama);
                
                // Clear existing checkboxes
                $('#permissionsContainer').empty();
                
                // Group permissions by category or show all
                if (response.groupedPermissions) {
                    Object.keys(response.groupedPermissions).forEach(category => {
                        let categoryHtml = `
                            <div class="permission-category mb-3">
                                <h6 class="text-primary">${category}</h6>
                                <div class="row">
                        `;
                        
                        response.groupedPermissions[category].forEach(permission => {
                            let isChecked = response.assignedPermissions.includes(permission.id) ? 'checked' : '';
                            categoryHtml += `
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="permissions[]" value="${permission.id}" 
                                               id="perm_${permission.id}" ${isChecked}>
                                        <label class="form-check-label" for="perm_${permission.id}">
                                            ${permission.deskripsi}
                                            ${permission.route_name ? `<small class="text-muted d-block">(${permission.route_name})</small>` : ''}
                                        </label>
                                    </div>
                                </div>
                            `;
                        });
                        
                        categoryHtml += '</div></div>';
                        $('#permissionsContainer').append(categoryHtml);
                    });
                } else {
                    // Fallback if no grouping available
                    let permissionsHtml = '<div class="row">';
                    response.allPermissions.forEach(permission => {
                        let isChecked = response.assignedPermissions.includes(permission.id) ? 'checked' : '';
                        permissionsHtml += `
                            <div class="col-md-6 col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="permissions[]" value="${permission.id}" 
                                           id="perm_${permission.id}" ${isChecked}>
                                    <label class="form-check-label" for="perm_${permission.id}">
                                        ${permission.deskripsi}
                                        ${permission.route_name ? `<small class="text-muted d-block">(${permission.route_name})</small>` : ''}
                                    </label>
                                </div>
                            </div>
                        `;
                    });
                    permissionsHtml += '</div>';
                    $('#permissionsContainer').html(permissionsHtml);
                }
                
                $('#permissionsModal').modal('show');
            }
        },
        error: function(xhr) {
            Swal.fire('Error!', 'Gagal memuat data permissions', 'error');
        }
    });
}

function updatePermissions() {
    const permissions = {
        canManagePermissions: {{ $canManagePermissions ? 'true' : 'false' }}
    };
    
    if (!permissions.canManagePermissions) {
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak!',
            text: 'Anda tidak memiliki izin untuk mengelola permissions.'
        });
        return;
    }

    let id = $('#permissionUserGroupId').val();
    let selectedPermissions = [];
    
    $('input[name="permissions[]"]:checked').each(function() {
        selectedPermissions.push($(this).val());
    });
    
    // Show loading state
    const updateBtn = $('#updatePermissionsBtn');
    const originalText = updateBtn.html();
    updateBtn.html('<i class="fas fa-spinner fa-spin"></i> Updating...').prop('disabled', true);

    $.ajax({
        url: `{{ url('user-group') }}/${id}`,
        method: 'POST',
        data: {
            nama: $('#permissionGroupName').text(),
            permissions: selectedPermissions,
            _method: 'PUT'
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#permissionsModal').modal('hide');
                Swal.fire('Berhasil!', 'Permissions berhasil diupdate', 'success');
            }
        },
        error: function(xhr) {
            Swal.fire('Error!', xhr.responseJSON?.message || 'Gagal mengupdate permissions', 'error');
        },
        complete: function() {
            // Reset button state
            updateBtn.html(originalText).prop('disabled', false);
        }
    });
}
</script>
@endpush