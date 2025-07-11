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
            'nomor_bom' => 'required|string|max:50|unique:bill_of_material,nomor_bom',
            'kategori' => 'required|in:JIG, TOOL DAN MAL,TOOLS,CONSUMABLE TOOLS,SPECIAL PROCESS',
            'proyek_id' => 'required|exists:proyek,id',
            'revisi_id' => 'required|exists:revisi,id',
            'tanggal' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:kode_material,id',
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
            'items.*.material_id.exists' => 'Material tidak valid'
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
            
            // Format tanggal untuk response
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
        $billOfMaterial = BillOfMaterial::findOrFail($id);

        // Validasi input
        $validator = Validator::make($request->all(), [
            'nomor_bom' => 'required|string|max:50|unique:bill_of_material,nomor_bom,' . $id,
            'kategori' => 'required|in:JIG, TOOL DAN MAL,TOOLS,CONSUMABLE TOOLS,SPECIAL PROCESS',
            'proyek_id' => 'required|exists:proyek,id',
            'revisi_id' => 'required|exists:revisi,id',
            'tanggal' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:kode_material,id',
            'items.*.keterangan' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Update Bill of Material
            $billOfMaterial->update([
                'nomor_bom' => $request->nomor_bom,
                'kategori' => $request->kategori,
                'proyek_id' => $request->proyek_id,
                'revisi_id' => $request->revisi_id,
                'tanggal' => $request->tanggal
            ]);

            // Hapus item BOM yang lama
            ItemBom::where('bill_of_material_id', $billOfMaterial->id)->delete();

            // Simpan item BOM yang baru
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
                ->with('success', 'Bill of Material berhasil diperbarui');

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