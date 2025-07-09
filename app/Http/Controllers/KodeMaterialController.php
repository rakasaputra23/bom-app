<?php

namespace App\Http\Controllers;

use App\Models\KodeMaterial;
use App\Models\Uom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KodeMaterialController extends Controller
{
    public function index()
    {
        $uoms = Uom::select('id', 'qty', 'satuan')->get();
        return view('master.kode_material', compact('uoms'));
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
                'message' => 'Data berhasil disimpan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(KodeMaterial $kodeMaterial)
    {
        $kodeMaterial->load(['uom' => function($query) {
            $query->select('id', 'qty', 'satuan');
        }]);
        
        return response()->json([
            'success' => true,
            'data' => $kodeMaterial
        ]);
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
                'message' => 'Data berhasil diupdate!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(KodeMaterial $kodeMaterial)
    {
        try {
            $isUsed = DB::table('item_bom')
                ->where('kode_material_id', $kodeMaterial->id)
                ->exists();
            
            if ($isUsed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak dapat dihapus karena sudah digunakan di BOM!'
                ], 400);
            }
            
            $kodeMaterial->delete();
            
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

    public function getData(Request $request)
    {
        $query = KodeMaterial::with(['uom' => function($query) {
            $query->select('id', 'qty', 'satuan');
        }])->select('kode_material.*');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                return '<button class="btn btn-sm btn-warning edit-btn" data-id="'.$row->id.'" data-kode="'.$row->kode_material.'" data-nama="'.$row->nama_material.'">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'" data-kode="'.$row->kode_material.'" data-nama="'.$row->nama_material.'">
                    <i class="fas fa-trash"></i>
                </button>';
            })
            ->addColumn('satuan', function($row) {
                return optional($row->uom)->full_format;
            })
            ->filter(function ($query) use ($request) {
                if ($request->has('search_kode') && $request->search_kode != '') {
                    $query->where('kode_material', 'like', '%'.$request->search_kode.'%');
                }
                if ($request->has('search_nama') && $request->search_nama != '') {
                    $query->where('nama_material', 'like', '%'.$request->search_nama.'%');
                }
                if ($request->has('search_spesifikasi') && $request->search_spesifikasi != '') {
                    $query->where('spesifikasi', 'like', '%'.$request->search_spesifikasi.'%');
                }
                if ($request->has('search_satuan') && $request->search_satuan != '') {
                    $query->whereHas('uom', function($q) use ($request) {
                        $q->where('satuan', $request->search_satuan);
                    });
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}