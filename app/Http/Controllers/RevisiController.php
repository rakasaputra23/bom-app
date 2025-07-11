<?php

namespace App\Http\Controllers;

use App\Models\Revisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RevisiController extends Controller
{
    public function index()
    {
        return view('master.revisi');
    }

    public function getData(Request $request)
    {
        $query = Revisi::query()->select('revisi.*');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                return '<button class="btn btn-sm btn-warning edit-btn" data-id="'.$row->id.'" 
                        data-jenis="'.$row->jenis_revisi.'" data-keterangan="'.$row->keterangan.'">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'" 
                        data-jenis="'.$row->jenis_revisi.'" data-keterangan="'.$row->keterangan.'">
                        <i class="fas fa-trash"></i>
                    </button>';
            })
            ->filter(function ($query) use ($request) {
                if ($request->has('search_jenis') && $request->search_jenis != '') {
                    $query->where('jenis_revisi', 'like', '%'.$request->search_jenis.'%');
                }
                if ($request->has('search_keterangan') && $request->search_keterangan != '') {
                    $query->where('keterangan', 'like', '%'.$request->search_keterangan.'%');
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_revisi' => 'required|string|max:100',
            'keterangan' => 'required|string'
        ]);

        try {
            $revisi = Revisi::create([
                'jenis_revisi' => $request->jenis_revisi,
                'keterangan' => $request->keterangan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan!',
                'data' => $revisi
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

    public function update(Request $request, Revisi $revisi)
    {
        $request->validate([
            'jenis_revisi' => 'required|string|max:100',
            'keterangan' => 'required|string'
        ]);

        try {
            $revisi->update([
                'jenis_revisi' => $request->jenis_revisi,
                'keterangan' => $request->keterangan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate!',
                'data' => $revisi
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

    public function destroy(Revisi $revisi)
    {
        try {
            $revisi->delete();

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