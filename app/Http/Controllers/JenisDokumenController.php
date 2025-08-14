<?php

namespace App\Http\Controllers;

use App\Models\JenisDokumen;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Illuminate\Validation\ValidationException;

class JenisDokumenController extends Controller
{
    public function index()
    {
        return view('master.jenis-dokumen');
    }

    public function getData(Request $request)
    {
        $query = JenisDokumen::query();

        return DataTables::of($query)
            ->filter(function ($query) use ($request) {
                if ($request->has('search_kode') && $request->search_kode != '') {
                    $query->where('kode_dokumen', 'like', '%' . $request->search_kode . '%');
                }
                if ($request->has('search_nama') && $request->search_nama != '') {
                    $query->where('nama_dokumen', 'like', '%' . $request->search_nama . '%');
                }
                if ($request->has('search_status') && $request->search_status != '') {
                    $query->where('is_active', $request->search_status);
                }
            })
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                if ($row->is_active) {
                    return '<span class="badge badge-success">Aktif</span>';
                } else {
                    return '<span class="badge badge-secondary">Non-Aktif</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $actions = '<div class="btn-group" role="group">';
                
                // Check edit permission
                if (auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('jenis-dokumen.update')) {
                    $actions .= '<button type="button" class="btn btn-sm btn-warning mr-1" onclick="editJenisDokumen('.$row->id.')" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>';
                }
                
                // Check delete permission
                if (auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('jenis-dokumen.destroy')) {
                    $actions .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteJenisDokumen('.$row->id.', \''.addslashes($row->kode_dokumen).'\', \''.addslashes($row->nama_dokumen).'\')" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>';
                }
                
                $actions .= '</div>';
                
                return $actions;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'kode_dokumen' => 'required|string|max:10|unique:jenis_dokumen,kode_dokumen',
                'nama_dokumen' => 'required|string|max:100',
                'deskripsi' => 'nullable|string|max:255',
                'is_active' => 'required|boolean',
            ], [
                'kode_dokumen.required' => 'Kode dokumen harus diisi',
                'kode_dokumen.unique' => 'Kode dokumen sudah digunakan',
                'kode_dokumen.max' => 'Kode dokumen maksimal 10 karakter',
                'nama_dokumen.required' => 'Nama dokumen harus diisi',
                'nama_dokumen.max' => 'Nama dokumen maksimal 100 karakter',
                'deskripsi.max' => 'Deskripsi maksimal 255 karakter',
                'is_active.required' => 'Status harus dipilih',
            ]);

            $jenisDokumen = JenisDokumen::create([
                'kode_dokumen' => strtoupper($request->kode_dokumen),
                'nama_dokumen' => $request->nama_dokumen,
                'deskripsi' => $request->deskripsi,
                'is_active' => $request->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data Jenis Dokumen berhasil disimpan!',
                'data' => $jenisDokumen
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(JenisDokumen $jenisDokumen)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $jenisDokumen,
                'message' => 'Data Jenis Dokumen berhasil dimuat'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data Jenis Dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, JenisDokumen $jenisDokumen)
    {
        try {
            $request->validate([
                'kode_dokumen' => 'required|string|max:10|unique:jenis_dokumen,kode_dokumen,'.$jenisDokumen->id,
                'nama_dokumen' => 'required|string|max:100',
                'deskripsi' => 'nullable|string|max:255',
                'is_active' => 'required|boolean',
            ], [
                'kode_dokumen.required' => 'Kode dokumen harus diisi',
                'kode_dokumen.unique' => 'Kode dokumen sudah digunakan',
                'kode_dokumen.max' => 'Kode dokumen maksimal 10 karakter',
                'nama_dokumen.required' => 'Nama dokumen harus diisi',
                'nama_dokumen.max' => 'Nama dokumen maksimal 100 karakter',
                'deskripsi.max' => 'Deskripsi maksimal 255 karakter',
                'is_active.required' => 'Status harus dipilih',
            ]);

            $jenisDokumen->update([
                'kode_dokumen' => strtoupper($request->kode_dokumen),
                'nama_dokumen' => $request->nama_dokumen,
                'deskripsi' => $request->deskripsi,
                'is_active' => $request->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jenis Dokumen berhasil diperbarui',
                'data' => $jenisDokumen
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui Jenis Dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(JenisDokumen $jenisDokumen)
    {
        try {
            // Check if jenis dokumen is being used in bill_of_material
            // Pastikan relasi billOfMaterials ada di model JenisDokumen
            if (method_exists($jenisDokumen, 'billOfMaterials') && $jenisDokumen->billOfMaterials()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jenis Dokumen tidak dapat dihapus karena masih digunakan pada Bill of Material'
                ], 422);
            }

            $jenisDokumen->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Jenis Dokumen berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Jenis Dokumen: ' . $e->getMessage()
            ], 500);
        }
    }
}