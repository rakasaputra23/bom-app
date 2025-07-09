<?php

namespace App\Http\Controllers;

use App\Models\Uom;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UomController extends Controller
{
    public function index()
    {
        return view('master.uom');
    }

    public function getData(Request $request)
    {
        $query = Uom::query();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
                    <button class="btn btn-sm btn-warning edit-btn" data-id="' . $row->id . '" data-satuan="' . $row->satuan . '" data-qty="' . $row->qty . '">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" data-satuan="' . $row->satuan . '" data-qty="' . $row->qty . '">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50|unique:uom,satuan'
        ]);

        try {
            Uom::create([
                'qty' => $request->qty,
                'satuan' => $request->satuan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data UoM berhasil disimpan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Uom $uom)
    {
        return response()->json([
            'success' => true,
            'data' => $uom
        ]);
    }

    public function update(Request $request, Uom $uom)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50|unique:uom,satuan,' . $uom->id
        ]);

        try {
            $uom->update([
                'qty' => $request->qty,
                'satuan' => $request->satuan
            ]);

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

    public function destroy(Uom $uom)
    {
        try {
            $uom->delete();

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
