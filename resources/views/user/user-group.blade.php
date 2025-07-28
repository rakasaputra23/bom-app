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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="permissionsModalLabel">Permissions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="permissionsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
@endpush

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
            { data: 'created_at' },
            {
                data: 'id',
                render: function(data, type, row) {
                    return `
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-info" onclick="showDetail(${data})" title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="showPermissions(${data})" title="Permissions">
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
                }
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
        }
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
                    Swal.fire('Error!', 'Terjadi kesalahan pada server', 'error');
                }
            }
        });
    });

    // Reset modal when closed
    $('#userGroupModal').on('hidden.bs.modal', function() {
        resetForm();
    });
});

function resetForm() {
    $('#userGroupForm')[0].reset();
    $('#userGroupModalLabel').text('Tambah User Group');
    $('#userGroupForm').removeData('action').removeData('method');
    $('.form-control, .form-check-input, .border.rounded').removeClass('is-invalid');
    $('.invalid-feedback').text('');
    $('input[name="permissions[]"]').prop('checked', false);
}

function editUserGroup(id) {
    $.get(`{{ url('user-group') }}/${id}`, function(data) {
        $('#userGroupModalLabel').text('Edit User Group');
        $('#userGroupForm').data('action', `{{ url('user-group') }}/${id}`).data('method', 'PUT');
        
        $('#nama').val(data.nama);
        
        // Reset all checkboxes first
        $('input[name="permissions[]"]').prop('checked', false);
        
        // Check permissions that belong to this group
        if (data.permissions && data.permissions.length > 0) {
            data.permissions.forEach(function(permission) {
                $(`#permission_${permission.id}`).prop('checked', true);
            });
        }
        
        $('#userGroupModal').modal('show');
    });
}

function showDetail(id) {
    $.get(`{{ url('user-group') }}/${id}`, function(data) {
        let permissionsList = '';
        if (data.permissions && data.permissions.length > 0) {
                                    permissionsList = data.permissions.map(p => `<span class="badge bg-secondary me-1 mb-1">${p.deskripsi}</span>`).join('');
        } else {
            permissionsList = '<span class="text-muted">Tidak ada permissions</span>';
        }

        let usersList = '';
        if (data.users && data.users.length > 0) {
            usersList = data.users.map(u => `<span class="badge bg-info me-1 mb-1">${u.nama} (${u.nip})</span>`).join('');
        } else {
            usersList = '<span class="text-muted">Tidak ada user</span>';
        }

        let content = `
            <div class="row">
                <div class="col-12">
                    <table class="table table-borderless">
                        <tr>
                            <td width="150"><strong>Nama Group:</strong></td>
                            <td>${data.nama}</td>
                        </tr>
                        <tr>
                            <td><strong>Jumlah User:</strong></td>
                            <td>${data.users ? data.users.length : 0} user</td>
                        </tr>
                        <tr>
                            <td><strong>Dibuat:</strong></td>
                            <td>${new Date(data.created_at).toLocaleDateString('id-ID')}</td>
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
    });
}

function showPermissions(id) {
    $.get(`{{ url('user-group') }}/${id}/permissions`, function(data) {
        let content = '';
        if (data && data.length > 0) {
            content = '<div class="list-group">';
            data.forEach(function(permission) {
                content += `
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">${permission.deskripsi}</h6>
                        </div>
                        ${permission.route_name ? `<p class="mb-1 text-muted small">(${permission.route_name})</p>` : ''}
                    </div>
                `;
            });
            content += '</div>';
        } else {
            content = '<p class="text-muted text-center">Tidak ada permissions untuk group ini</p>';
        }
        
        $('#permissionsContent').html(content);
        $('#permissionsModal').modal('show');
    });
}

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
                url: `{{ url('user-group') }}/${id}`,
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
                    let message = xhr.responseJSON?.message || 'Terjadi kesalahan pada server';
                    Swal.fire('Error!', message, 'error');
                }
            });
        }
    });
}
</script>
@endpush