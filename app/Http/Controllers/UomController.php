<?php

namespace App\Http\Controllers;

use App\Models\Uom;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;

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
        ->filter(function ($query) use ($request) {
            if ($request->has('search_satuan') && $request->search_satuan != '') {
                $query->where('satuan', 'like', '%' . $request->search_satuan . '%');
            }
            if ($request->has('search_qty') && $request->search_qty != '') {
                $query->where('qty', 'like', '%' . $request->search_qty . '%');
            }
        })
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
            'satuan' => 'required|string|max:50',
        ]);

        // Cek apakah kombinasi qty + satuan sudah ada
        $exists = Uom::where('qty', $request->qty)
                    ->where('satuan', $request->satuan)
                    ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Kombinasi qty dan satuan sudah ada.'
            ], 422);
        }

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
    try {
        return response()->json([
            'success' => true,
            'data' => $uom,
            'message' => 'Data UoM berhasil dimuat'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal memuat data UoM'
        ], 500);
    }
}

public function update(Request $request, Uom $uom)
{
    try {
        $validated = $request->validate([
            'satuan' => 'required|string|max:50',
            'qty' => 'required|numeric'
        ]);

        $uom->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'UoM berhasil diperbarui',
            'data' => $uom
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'success' => false,
            'errors' => $e->errors(),
            'message' => 'Validasi gagal'
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal memperbarui UoM'
        ], 500);
    }
}

public function destroy(Uom $uom)
{
    try {
        $uom->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'UoM berhasil dihapus'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus UoM'
        ], 500);
    }
}
}
