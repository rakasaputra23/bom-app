<?php

namespace App\Http\Controllers;

use App\Models\Revisi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RevisiController extends Controller
{
    public function index()
    {
        return view('master.revisi');
    }

    public function getData(Request $request)
    {
        $query = Revisi::query();

        return DataTables::of($query)
            ->filter(function ($query) use ($request) {
                if ($request->has('search_jenis') && $request->search_jenis != '') {
                    $query->where('jenis_revisi', 'like', '%' . $request->search_jenis . '%');
                }
                if ($request->has('search_keterangan') && $request->search_keterangan != '') {
                    $query->where('keterangan', 'like', '%' . $request->search_keterangan . '%');
                }
            })
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-warning" onclick="editRevisi('.$row->id.')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteRevisi('.$row->id.', \''.addslashes($row->jenis_revisi).'\')">
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
            'jenis_revisi' => 'required|string|max:100|unique:revisi',
            'keterangan' => 'required|string|max:255'
        ]);

        try {
            $revisi = Revisi::create($request->only(['jenis_revisi', 'keterangan']));

            return response()->json([
                'success' => true,
                'message' => 'Data revisi berhasil disimpan!',
                'data' => $revisi
            ]);
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
            'jenis_revisi' => 'required|string|max:100|unique:revisi,jenis_revisi,'.$revisi->id,
            'keterangan' => 'required|string|max:255'
        ]);

        try {
            $revisi->update($request->only(['jenis_revisi', 'keterangan']));

            return response()->json([
                'success' => true,
                'message' => 'Data revisi berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Revisi $revisi)
    {
        try {
            $revisi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data revisi berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}