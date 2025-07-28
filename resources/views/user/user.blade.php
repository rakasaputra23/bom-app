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
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-users mr-1"></i>
                    Data User
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#userModal">
                        <i class="fas fa-plus"></i> Tambah User
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
                                <select class="form-control" id="user_group_id" name="user_group_id" required>
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
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
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
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Pastikan DataTables sudah dimuat
    if (typeof $.fn.DataTable === 'undefined') {
        console.error('DataTables tidak dimuat dengan benar');
        return;
    }

    let table = $('#userTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '{{ route("user.getData") }}',
            type: 'GET'
        },
        columns: [
            { data: 'nip' },
            { data: 'nama' },
            { data: 'posisi' },
            { data: 'email' },
            { data: 'group_nama' },
            { data: 'created_at' },
            {
                data: 'id',
                render: function(data, type, row) {
                    return `
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-info" onclick="showDetail(${data})">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-warning" onclick="editUser(${data})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteUser(${data})">
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
    $('#userForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        let url = $('#userForm').data('action') || '{{ route("user.store") }}';
        let method = $('#userForm').data('method') || 'POST';
        
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
                    $('#userModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire('Berhasil!', response.message, 'success');
                    resetForm();
                }
            },
            error: function(xhr) {
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
            }
        });
    });

    // Reset modal when closed
    $('#userModal').on('hidden.bs.modal', function() {
        resetForm();
    });
});

function resetForm() {
    $('#userForm')[0].reset();
    $('#userModalLabel').text('Tambah User');
    $('#userForm').removeData('action').removeData('method');
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').text('');
    $('#password').prop('required', true);
    $('#password_confirmation').prop('required', true);
}

function editUser(id) {
    $.get(`{{ url('user') }}/${id}`, function(data) {
        $('#userModalLabel').text('Edit User');
        $('#userForm').data('action', `{{ url('user') }}/${id}`).data('method', 'PUT');
        
        $('#nip').val(data.nip);
        $('#nama').val(data.nama);
        $('#posisi').val(data.posisi);
        $('#user_group_id').val(data.user_group_id);
        $('#email').val(data.email);
        
        // Password tidak required saat edit
        $('#password').prop('required', false);
        $('#password_confirmation').prop('required', false);
        
        $('#userModal').modal('show');
    });
}

function showDetail(id) {
    $.get(`{{ url('user') }}/${id}`, function(data) {
        let content = `
            <table class="table table-borderless">
                <tr>
                    <td><strong>NIP:</strong></td>
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
                    <td>${data.group ? data.group.nama : '-'}</td>
                </tr>
                <tr>
                    <td><strong>Dibuat:</strong></td>
                    <td>${new Date(data.created_at).toLocaleDateString('id-ID')}</td>
                </tr>
            </table>
        `;
        $('#detailUserContent').html(content);
        $('#detailUserModal').modal('show');
    });
}

function deleteUser(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data user akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `{{ url('user') }}/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#userTable').DataTable().ajax.reload();
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