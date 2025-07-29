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
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-users-cog mr-1"></i>
                    Data User Group
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#userGroupModal">
                        <i class="fas fa-plus"></i> Tambah User Group
                    </button>
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
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Group <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3">
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
                    <button type="submit" class="btn btn-primary">Simpan</button>
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
                <button type="button" class="btn btn-primary" id="updatePermissionsBtn">Update Permissions</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit User Group -->
<div class="modal fade" id="editUserGroupModal" tabindex="-1" aria-labelledby="editUserGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserGroupModalLabel">Edit User Group</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editUserGroupForm">
                <input type="hidden" id="editUserGroupId">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_nama">Nama Group</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama">
                        <div class="invalid-feedback" id="edit_nama_error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
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
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Pastikan DataTables sudah dimuat
    if (typeof $.fn.DataTable === 'undefined') {
        console.error('DataTables tidak dimuat dengan benar');
        return;
    }

    // Initialize DataTable - SAMA SEPERTI USER MANAGEMENT
    let table = $('#userGroupTable').DataTable({
        processing: true,
        serverSide: false,  // UBAH DARI true KE false SAMA SEPERTI USER
        ajax: {
            url: '{{ route("user.group.getData") }}',
            type: 'GET'
        },
        columns: [
    { data: 'nama', name: 'nama' },
    { data: 'users_count', name: 'users_count' },
    { 
        data: 'created_at', 
        name: 'created_at',
        render: function(data) {
            if (data === '-' || !data) return '-';
            try {
                // Parse ISO string dan format ke locale Indonesia
                let date = new Date(data);
                return date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } catch (e) {
                console.error('Error parsing date:', e);
                return '-';
            }
        }
    },
            {
                data: 'id',
                render: function(data, type, row) {
                    return `
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-info" onclick="showDetail(${data})" title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="managePermissions(${data})" title="Permissions">
                                <i class="fas fa-key"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-warning" onclick="editUserGroup(${data})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteUserGroup(${data})" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                },
                orderable: false,
                searchable: false
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
        }
    });

    // Form submission for adding user group
    $('#userGroupForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        storeUserGroup(formData);
    });

    // Form submission for editing user group
    $('#editUserGroupForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#editUserGroupId').val();
        let formData = $(this).serialize();
        updateUserGroup(id, formData);
    });

    // Button for update permissions
    $('#updatePermissionsBtn').on('click', function() {
        updatePermissions();
    });

    // Reset modal when closed
    $('#userGroupModal').on('hidden.bs.modal', function() {
        $('#userGroupForm')[0].reset();
        $('.form-control, .form-check-input, .border.rounded').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    });

    $('#editUserGroupModal').on('hidden.bs.modal', function() {
        $('#editUserGroupForm')[0].reset();
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    });
});

// 1. STORE USER GROUP
function storeUserGroup(formData) {
    $.ajax({
        url: '{{ route("user.group.store") }}',
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
                $('#userGroupTable').DataTable().ajax.reload();
                Swal.fire('Berhasil!', response.message, 'success');
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                $('.form-control, .form-check-input').removeClass('is-invalid');
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
                Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan pada server', 'error');
            }
        }
    });
}

// 2. EDIT USER GROUP
function editUserGroup(id) {
    $.ajax({
        url: `/user-group/${id}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                $('#edit_nama').val(response.userGroup.nama);
                $('#editUserGroupId').val(id);
                $('#editUserGroupModal').modal('show');
            }
        },
        error: function(xhr) {
            Swal.fire('Error!', 'Gagal memuat data user group', 'error');
        }
    });
}

// 3. UPDATE USER GROUP
function updateUserGroup(id, formData) {
    $.ajax({
        url: `/user-group/${id}`,
        method: 'POST',
        data: formData + '&_method=PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#editUserGroupModal').modal('hide');
                $('#userGroupTable').DataTable().ajax.reload();
                Swal.fire('Berhasil!', response.message, 'success');
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                
                $.each(errors, function(key, value) {
                    $(`#edit_${key}`).addClass('is-invalid');
                    $(`#edit_${key}_error`).text(value[0]);
                });
            } else {
                Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan pada server', 'error');
            }
        }
    });
}

// 4. DELETE USER GROUP
function deleteUserGroup(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data user group akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/user-group/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#userGroupTable').DataTable().ajax.reload();
                        Swal.fire('Berhasil!', response.message, 'success');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan pada server', 'error');
                }
            });
        }
    });
}

// 5. MANAGE PERMISSIONS
function managePermissions(id) {
    $.ajax({
        url: `/user-group/${id}/permissions`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                // Populate permissions modal
                $('#permissionUserGroupId').val(id);
                $('#permissionGroupName').text(response.userGroup.nama);
                
                // Clear existing checkboxes
                $('#permissionsContainer').empty();
                
                // Group permissions by category
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
                
                $('#permissionsModal').modal('show');
            }
        },
        error: function(xhr) {
            Swal.fire('Error!', 'Gagal memuat data permissions', 'error');
        }
    });
}

// 6. UPDATE PERMISSIONS
function updatePermissions() {
    let id = $('#permissionUserGroupId').val();
    let selectedPermissions = [];
    
    $('input[name="permissions[]"]:checked').each(function() {
        selectedPermissions.push($(this).val());
    });
    
    $.ajax({
        url: `/user-group/${id}`,
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
        }
    });
}

// 7. SHOW DETAIL - VERSI DIPERBAIKI
function showDetail(id) {
    $.ajax({
        url: `/user-group/${id}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                // Format tanggal created_at
                let createdAt = '-';
                if (response.userGroup.created_at) {
                    try {
                        let date = new Date(response.userGroup.created_at);
                        createdAt = date.toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    } catch (e) {
                        console.error('Error parsing date:', e);
                    }
                }

                // Format permissions list
                let permissionsList = '<ul class="list-unstyled">';
                if (response.permissions && response.permissions.length > 0) {
                    response.permissions.forEach(permission => {
                        permissionsList += `<li><i class="fas fa-check-circle text-success mr-2"></i> ${permission.deskripsi} (${permission.route_name})</li>`;
                    });
                } else {
                    permissionsList += '<li class="text-muted">Tidak ada permissions</li>';
                }
                permissionsList += '</ul>';

                // Format users list
                let usersList = '<ul class="list-unstyled">';
                if (response.users && response.users.length > 0) {
                    response.users.forEach(user => {
                        usersList += `<li><i class="fas fa-user mr-2"></i> ${user.nama} (${user.nip || 'N/A'})</li>`;
                    });
                } else {
                    usersList += '<li class="text-muted">Tidak ada user dalam group ini</li>';
                }
                usersList += '</ul>';

                // Build HTML content
                let content = `
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="150"><strong>Nama Group:</strong></td>
                                    <td>${response.userGroup.nama || '-'}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah User:</strong></td>
                                    <td>${response.users ? response.users.length : 0} user</td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat:</strong></td>
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
            } else {
                Swal.fire('Error!', response.message || 'Gagal memuat detail user group', 'error');
            }
        },
        error: function(xhr) {
            Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan saat memuat detail', 'error');
        }
    });
}
</script>
@endpush