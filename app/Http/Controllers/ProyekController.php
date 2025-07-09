<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProyekController extends Controller
{
    public function index()
    {
        return view('master.proyek');
    }

    public function getData(Request $request)
    {
        $query = Proyek::query()->select('proyek.*');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                return '<button class="btn btn-sm btn-warning edit-btn" data-id="'.$row->id.'" 
                        data-kode="'.$row->kode_proyek.'" data-nama="'.$row->nama_proyek.'">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'" 
                        data-kode="'.$row->kode_proyek.'" data-nama="'.$row->nama_proyek.'">
                        <i class="fas fa-trash"></i>
                    </button>';
            })
            ->filter(function ($query) use ($request) {
                if ($request->has('search_kode') && $request->search_kode != '') {
                    $query->where('kode_proyek', 'like', '%'.$request->search_kode.'%');
                }
                if ($request->has('search_nama') && $request->search_nama != '') {
                    $query->where('nama_proyek', 'like', '%'.$request->search_nama.'%');
                }
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
                'message' => 'Data berhasil disimpan!',
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
                'message' => 'Data berhasil diupdate!',
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
                'message' => 'Gagal mengupdate data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Proyek $proyek)
    {
        try {
            $isUsed = DB::table('item_bom')
                ->where('proyek_id', $proyek->id)
                ->exists();
            
            if ($isUsed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak dapat dihapus karena sudah digunakan di BOM!'
                ], 400);
            }
            
            $proyek->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}