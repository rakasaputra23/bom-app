<?php

namespace App\Http\Controllers;

use App\Models\BillOfMaterial;
use App\Models\ItemBom;
use App\Models\KodeMaterial;
use App\Models\Proyek;
use App\Models\Revisi;
use App\Models\Uom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BillOfMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if request wants JSON (for AJAX)
        if ($request->wantsJson()) {
            $billOfMaterials = BillOfMaterial::with(['proyek', 'revisi', 'itemBom.kodeMaterial.uom'])
                ->orderBy('tanggal', 'desc')
                ->get();
            return response()->json(['data' => $billOfMaterials]);
        }

        $billOfMaterials = BillOfMaterial::with(['proyek', 'revisi', 'itemBom.kodeMaterial.uom'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('bom.index', compact('billOfMaterials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua data proyek dengan kode dan nama
        $proyeks = Proyek::select('id', 'kode_proyek', 'nama_proyek')
            ->orderBy('kode_proyek')
            ->get()
            ->map(function ($proyek) {
                $proyek->display_name = $proyek->kode_proyek . ' - ' . $proyek->nama_proyek;
                return $proyek;
            });

        // Ambil semua data revisi
        $revisis = Revisi::select('id', 'jenis_revisi', 'keterangan')
            ->orderBy('jenis_revisi')
            ->get()
            ->map(function ($revisi) {
                $revisi->nama_revisi = $revisi->jenis_revisi . (!empty($revisi->keterangan) ? ' - ' . $revisi->keterangan : '');
                return $revisi;
            });

        // Ambil semua data material dengan relasi UOM
        $materials = KodeMaterial::with('uom')
            ->select('id', 'kode_material', 'nama_material', 'spesifikasi', 'uom_id')
            ->orderBy('kode_material')
            ->get()
            ->map(function ($material) {
                $material->satuan = $material->uom ? $material->uom->satuan : '';
                $material->qty_uom = $material->uom ? $material->uom->qty : 0;
                $material->display_name = $material->kode_material . ' - ' . $material->nama_material;
                return $material;
            });

        return view('bom.create', compact('proyeks', 'revisis', 'materials'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
        'nomor_bom' => 'required|string|max:50',
        'kategori' => 'required|in:JIG, TOOL DAN MAL,TOOLS,CONSUMABLE TOOLS,SPECIAL PROCESS',
        'proyek_id' => 'required|exists:proyek,id',
        'revisi_id' => 'required|exists:revisi,id',
        'tanggal' => 'required|date',
        'items' => 'required|array|min:1',
        'items.*.material_id' => 'required|exists:kode_material,id',
        'items.*.qty' => 'required|numeric|min:0.01', // Validasi qty terpisah
        'items.*.satuan' => 'required|string|max:20', // Validasi satuan terpisah
        'items.*.keterangan' => 'nullable|string|max:255'
    ], [
        'nomor_bom.required' => 'Nomor BOM harus diisi',
        'nomor_bom.unique' => 'Nomor BOM sudah ada',
        'kategori.required' => 'Kategori harus dipilih',
        'kategori.in' => 'Kategori tidak valid',
        'proyek_id.required' => 'Proyek harus dipilih',
        'proyek_id.exists' => 'Proyek tidak valid',
        'revisi_id.required' => 'Revisi harus dipilih',
        'revisi_id.exists' => 'Revisi tidak valid',
        'tanggal.required' => 'Tanggal harus diisi',
        'items.required' => 'Item BOM harus diisi',
        'items.min' => 'Minimal harus ada 1 item',
        'items.*.material_id.required' => 'Material harus dipilih',
        'items.*.material_id.exists' => 'Material tidak valid',
        'items.*.qty.required' => 'Quantity harus diisi',
        'items.*.qty.numeric' => 'Quantity harus berupa angka',
        'items.*.qty.min' => 'Quantity minimal 0.01',
        'items.*.satuan.required' => 'Satuan harus dipilih',
        'items.*.satuan.string' => 'Satuan harus berupa teks',
        'items.*.satuan.max' => 'Satuan maksimal 20 karakter'
    ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Simpan Bill of Material
            $billOfMaterial = BillOfMaterial::create([
                'nomor_bom' => $request->nomor_bom,
                'kategori' => $request->kategori,
                'proyek_id' => $request->proyek_id,
                'revisi_id' => $request->revisi_id,
                'tanggal' => $request->tanggal
            ]);

            // Simpan Item BOM
            foreach ($request->items as $item) {
                if (!empty($item['material_id'])) {
                    ItemBom::create([
                        'bill_of_material_id' => $billOfMaterial->id,
                        'kode_material_id' => $item['material_id'],
                        'keterangan' => $item['keterangan'] ?? null,
                        'qty' => $item['qty'] ?? 0,
                        'satuan' => $item['satuan'] ?? ''
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('bom.index')
                ->with('success', 'Bill of Material berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    try {
        $bom = BillOfMaterial::with(['proyek', 'revisi', 'itemBom.kodeMaterial.uom'])->findOrFail($id);
        
        // Format UOM untuk response
        $bom->itemBom->each(function ($item) {
            if ($item->kodeMaterial && $item->kodeMaterial->uom) {
                $item->qty = $item->kodeMaterial->uom->qty;
                $item->satuan = $item->kodeMaterial->uom->satuan;
            }
        });
        
        $bom->tanggal_formatted = date('d/m/Y', strtotime($bom->tanggal));
        
        return response()->json($bom);
    } catch (\Exception $e) {
        return response()->json([
            'error' => true,
            'message' => 'Data tidak ditemukan'
        ], 404);
    }
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $billOfMaterial = BillOfMaterial::with(['itemBom.kodeMaterial.uom'])
            ->findOrFail($id);

        // Ambil data untuk dropdown
        $proyeks = Proyek::select('id', 'kode_proyek', 'nama_proyek')
            ->orderBy('kode_proyek')
            ->get()
            ->map(function ($proyek) {
                $proyek->display_name = $proyek->kode_proyek . ' - ' . $proyek->nama_proyek;
                return $proyek;
            });

        $revisis = Revisi::select('id', 'jenis_revisi', 'keterangan')
            ->orderBy('jenis_revisi')
            ->get()
            ->map(function ($revisi) {
                $revisi->nama_revisi = $revisi->jenis_revisi . (!empty($revisi->keterangan) ? ' - ' . $revisi->keterangan : '');
                return $revisi;
            });

        $materials = KodeMaterial::with('uom')
            ->select('id', 'kode_material', 'nama_material', 'spesifikasi', 'uom_id')
            ->orderBy('kode_material')
            ->get()
            ->map(function ($material) {
                $material->satuan = $material->uom ? $material->uom->satuan : '';
                $material->qty_uom = $material->uom ? $material->uom->qty : 0;
                $material->display_name = $material->kode_material . ' - ' . $material->nama_material;
                return $material;
            });

        return view('bom.edit', compact('billOfMaterial', 'proyeks', 'revisis', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nomor_bom' => 'required|string|max:50',
            'kategori' => 'required|in:JIG,TOOL DAN MAL,TOOLS,CONSUMABLE TOOLS,SPECIAL PROCESS',
            'proyek_id' => 'required|exists:proyek,id',
            'revisi_id' => 'required|exists:revisi,id',
            'tanggal' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:kode_material,id',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.satuan' => 'required|string|max:20',
            'items.*.keterangan' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Ambil data BOM lama
            $oldBom = BillOfMaterial::findOrFail($id);

            // Buat entri BOM baru sebagai hasil revisi
            $newBom = BillOfMaterial::create([
                'nomor_bom' => $oldBom->nomor_bom,
                'kategori' => $request->kategori,
                'proyek_id' => $request->proyek_id,
                'revisi_id' => $request->revisi_id,
                'tanggal' => $request->tanggal
            ]);

            // Simpan Item BOM baru
            foreach ($request->items as $item) {
                ItemBom::create([
                    'bill_of_material_id' => $newBom->id,
                    'kode_material_id' => $item['material_id'],
                    'qty' => $item['qty'],
                    'satuan' => $item['satuan'],
                    'keterangan' => $item['keterangan'] ?? null
                ]);
            }

            DB::commit();

            return redirect()->route('bom.index')
                ->with('success', 'Revisi BOM berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $bom = BillOfMaterial::findOrFail($id);
            
            // Hapus semua item BOM yang terkait
            ItemBom::where('bill_of_material_id', $bom->id)->delete();
            
            // Hapus BOM
            $bom->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'BOM berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus BOM: ' . $e->getMessage()
            ], 500);
        }
    }
}