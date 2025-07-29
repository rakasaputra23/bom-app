<?php

namespace App\Http\Controllers;

use App\Models\KodeMaterial;
use App\Models\Uom;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KodeMaterialController extends Controller
{
    public function index()
    {
        $uoms = Uom::select('id', 'qty', 'satuan')->get();
        return view('master.kode_material', compact('uoms'));
    }

    public function getData(Request $request)
    {
        $query = KodeMaterial::with(['uom' => function($query) {
            $query->select('id', 'qty', 'satuan');
        }]);

        return DataTables::of($query)
            ->filter(function ($query) use ($request) {
                if ($request->has('search_kode') && $request->search_kode != '') {
                    $query->where('kode_material', 'like', '%' . $request->search_kode . '%');
                }
                if ($request->has('search_nama') && $request->search_nama != '') {
                    $query->where('nama_material', 'like', '%' . $request->search_nama . '%');
                }
                if ($request->has('search_spesifikasi') && $request->search_spesifikasi != '') {
                    $query->where('spesifikasi', 'like', '%' . $request->search_spesifikasi . '%');
                }
                if ($request->has('search_satuan') && $request->search_satuan != '') {
                    $query->whereHas('uom', function($q) use ($request) {
                        $q->where('satuan', $request->search_satuan);
                    });
                }
            })
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-warning" onclick="editKodeMaterial('.$row->id.')" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteKodeMaterial('.$row->id.', \''.$row->kode_material.'\', \''.$row->nama_material.'\')" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                ';
            })
            ->addColumn('satuan', function($row) {
                return optional($row->uom)->full_format;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_material' => 'required|unique:kode_material|max:20',
            'nama_material' => 'required|max:100',
            'spesifikasi' => 'required|max:100',
            'uom_id' => 'required|exists:uom,id'
        ]);

        try {
            KodeMaterial::create($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Data Kode Material berhasil disimpan!'
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

    public function show(KodeMaterial $kodeMaterial)
    {
        try {
            $kodeMaterial->load(['uom' => function($query) {
                $query->select('id', 'qty', 'satuan');
            }]);
            
            return response()->json([
                'success' => true,
                'data' => $kodeMaterial,
                'message' => 'Data Kode Material berhasil dimuat'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data Kode Material'
            ], 500);
        }
    }

    public function update(Request $request, KodeMaterial $kodeMaterial)
    {
        $request->validate([
            'kode_material' => 'required|max:20|unique:kode_material,kode_material,' . $kodeMaterial->id,
            'nama_material' => 'required|max:100',
            'spesifikasi' => 'required|max:100',
            'uom_id' => 'required|exists:uom,id'
        ]);

        try {
            $kodeMaterial->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Kode Material berhasil diperbarui',
                'data' => $kodeMaterial
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
                'message' => 'Gagal memperbarui Kode Material'
            ], 500);
        }
    }

    public function destroy(KodeMaterial $kodeMaterial)
    {
        try {
            $kodeMaterial->delete();
            
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