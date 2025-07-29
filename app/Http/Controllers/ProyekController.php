<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProyekController extends Controller
{
    public function index()
    {
        return view('master.proyek');
    }

    public function getData(Request $request)
    {
        $query = Proyek::query();

        return DataTables::of($query)
            ->filter(function ($query) use ($request) {
                if ($request->has('search_kode') && $request->search_kode != '') {
                    $query->where('kode_proyek', 'like', '%' . $request->search_kode . '%');
                }
                if ($request->has('search_nama') && $request->search_nama != '') {
                    $query->where('nama_proyek', 'like', '%' . $request->search_nama . '%');
                }
            })
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-warning" onclick="editProyek('.$row->id.')" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteProyek('.$row->id.', \''.$row->kode_proyek.'\', \''.$row->nama_proyek.'\')" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_proyek' => 'required|string|max:50|unique:proyek,kode_proyek',
            'nama_proyek' => 'required|string|max:100',
        ]);

        try {
            $proyek = Proyek::create([
                'kode_proyek' => $request->kode_proyek,
                'nama_proyek' => $request->nama_proyek
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data Proyek berhasil disimpan!',
                'data' => $proyek
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Proyek $proyek)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $proyek,
                'message' => 'Data Proyek berhasil dimuat'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data Proyek'
            ], 500);
        }
    }

    public function update(Request $request, Proyek $proyek)
    {
        $request->validate([
            'kode_proyek' => 'required|string|max:50|unique:proyek,kode_proyek,'.$proyek->id,
            'nama_proyek' => 'required|string|max:100',
        ]);

        try {
            $proyek->update([
                'kode_proyek' => $request->kode_proyek,
                'nama_proyek' => $request->nama_proyek
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Proyek berhasil diperbarui',
                'data' => $proyek
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui Proyek'
            ], 500);
        }
    }

    public function destroy(Proyek $proyek)
    {
        try {
            $proyek->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Proyek berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Proyek'
            ], 500);
        }
    }
}