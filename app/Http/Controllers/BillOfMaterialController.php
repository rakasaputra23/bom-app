<?php

namespace App\Http\Controllers;

use App\Models\BillOfMaterial;
use App\Models\ItemBom;
use App\Models\KodeMaterial;
use App\Models\Proyek;
use App\Models\Revisi;
use App\Models\Uom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BillOfMaterialController extends Controller
{
    /**
     * Get current user ID safely
     * Memastikan kita selalu mendapatkan ID user, bukan NIP
     */
    private function getCurrentUserId()
    {
        $user = Auth::user();
        if (!$user) {
            throw new \Exception('User not authenticated');
        }
        
        // Pastikan kita mengembalikan ID (integer), bukan NIP (string)
        return (int) $user->id;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if request wants JSON (for AJAX)
        if ($request->wantsJson()) {
            $billOfMaterials = BillOfMaterial::with(['proyek', 'revisi', 'itemBom.kodeMaterial.uom', 'createdBy', 'approvedBy1', 'approvedBy2', 'rejectedBy'])
                ->orderBy('tanggal', 'desc')
                ->get();
            return response()->json(['data' => $billOfMaterials]);
        }

        $billOfMaterials = BillOfMaterial::with(['proyek', 'revisi', 'itemBom.kodeMaterial.uom', 'createdBy', 'approvedBy1', 'approvedBy2', 'rejectedBy'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('bom.index', compact('billOfMaterials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Generate nomor BOM otomatis
        $nomorBom = $this->generateNomorBom();

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

        return view('bom.create', compact('proyeks', 'revisis', 'materials', 'nomorBom'));
    }

    /**
     * Generate nomor BOM otomatis
     */
    private function generateNomorBom()
    {
        $year = date('Y');
        $month = date('m');
        
        // Format: BOM/YYYY/MM/XXXX
        $prefix = "BOM/{$year}/{$month}";
        
        // Cari nomor terakhir untuk bulan ini
        $lastBom = BillOfMaterial::where('nomor_bom', 'like', "{$prefix}/%")
            ->orderBy('nomor_bom', 'desc')
            ->first();
        
        $counter = 1;
        if ($lastBom) {
            $parts = explode('/', $lastBom->nomor_bom);
            $counter = intval(end($parts)) + 1;
        }
        
        return $prefix . '/' . str_pad($counter, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nomor_bom' => 'required|string|max:50',
            'kategori' => 'required|in:JIG,TOOL,MAL,TOOLS,CONSUMABLE TOOLS,SPECIAL PROCESS',
            'proyek_id' => 'required|exists:proyek,id',
            'revisi_id' => 'required|exists:revisi,id',
            'tanggal' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:kode_material,id',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.satuan' => 'required|string|max:20',
            'items.*.keterangan' => 'nullable|string|max:255'
        ], [
            'nomor_bom.required' => 'Nomor BOM harus diisi',
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
            // Tentukan status berdasarkan action
            $status = BillOfMaterial::STATUS_DRAFT;
            $submitForApproval = $request->has('submit_for_approval') && $request->input('submit_for_approval') == 'true';
            
            if ($submitForApproval) {
                $status = BillOfMaterial::STATUS_PENDING_APPROVAL_1;
            }

            // PERBAIKAN: Pastikan menggunakan ID user, bukan NIP
            $currentUserId = $this->getCurrentUserId();

            // Simpan Bill of Material dengan status yang sesuai dan created_by
            $billOfMaterial = BillOfMaterial::create([
                'nomor_bom' => $request->nomor_bom,
                'created_by' => $currentUserId, // PERBAIKAN: Menggunakan fungsi helper
                'kategori' => $request->kategori,
                'proyek_id' => $request->proyek_id,
                'revisi_id' => $request->revisi_id,
                'tanggal' => $request->tanggal,
                'status' => $status
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

            $message = $submitForApproval 
                ? 'BOM berhasil dibuat dan disubmit untuk approval' 
                : 'BOM berhasil dibuat dengan status DRAFT';

            return redirect()->route('bom.index')
                ->with('success', $message);

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
        $bom = BillOfMaterial::with([
            'proyek', 
            'revisi', 
            'itemBom.kodeMaterial.uom', 
            'createdBy', 
            'approvedBy1', 
            'approvedBy2', 
            'rejectedBy'
        ])->findOrFail($id);
        
        // Format tanggal
        $bom->tanggal_formatted = date('d/m/Y', strtotime($bom->tanggal));
        
        // Pastikan semua relasi approval tersedia dalam response
        $response = $bom->toArray();
        
        // Tambahkan informasi approval yang mungkin diperlukan
        if ($bom->approvedBy1) {
            $response['approvedBy1'] = $bom->approvedBy1->toArray();
        }
        
        if ($bom->approvedBy2) {
            $response['approvedBy2'] = $bom->approvedBy2->toArray();
        }
        
        if ($bom->rejectedBy) {
            $response['rejectedBy'] = $bom->rejectedBy->toArray();
        }
        
        // Tambahkan status badge
        $response['status_badge'] = $bom->status_badge;
        
        return response()->json($response);
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
        $billOfMaterial = BillOfMaterial::with(['itemBom.kodeMaterial.uom', 'createdBy'])
            ->findOrFail($id);

        // Check permission to edit
        if (!$billOfMaterial->canBeEdited()) {
            return redirect()->route('bom.index')
                ->with('error', 'BOM dengan status ' . $billOfMaterial->status_text . ' tidak dapat diedit');
        }

        // PERBAIKAN: Menggunakan fungsi helper untuk mendapatkan user ID
        $currentUserId = $this->getCurrentUserId();
        
        // Check if user is the creator or has permission
        if ($billOfMaterial->created_by !== $currentUserId && !Auth::user()->can('bom.edit')) {
            return redirect()->route('bom.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit BOM ini');
        }

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

        // PERBAIKAN: Tambahkan data kategori yang sama dengan create
        $categories = [
            'JIG' => 'JIG',
            'TOOL' => 'TOOL', 
            'MAL' => 'MAL',
            'TOOLS' => 'TOOLS',
            'CONSUMABLE TOOLS' => 'CONSUMABLE TOOLS',
            'SPECIAL PROCESS' => 'SPECIAL PROCESS'
        ];

        return view('bom.edit', compact('billOfMaterial', 'proyeks', 'revisis', 'materials', 'categories'));
    }

    /**
 * Update the specified resource in storage.
 */
public function update(Request $request, $id)
{
    $billOfMaterial = BillOfMaterial::findOrFail($id);

    // Check permission to edit
    if (!$billOfMaterial->canBeEdited()) {
        return redirect()->back()
            ->with('error', 'BOM dengan status ' . $billOfMaterial->status_text . ' tidak dapat diedit');
    }

    $currentUserId = $this->getCurrentUserId();
    
    // Check if user is the creator or has permission
    if ($billOfMaterial->created_by !== $currentUserId && !Auth::user()->can('bom.edit')) {
        return redirect()->back()
            ->with('error', 'Anda tidak memiliki akses untuk mengedit BOM ini');
    }

    // Validasi input - HILANGKAN validasi unique untuk nomor_bom
    $validator = Validator::make($request->all(), [
        'nomor_bom' => 'required|string|max:50', // Tidak ada unique lagi
        'kategori' => 'required|in:JIG,TOOL,MAL,TOOLS,CONSUMABLE TOOLS,SPECIAL PROCESS',
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
        $submitForApproval = $request->has('submit_for_approval') && $request->input('submit_for_approval') == 'true';
        
        if ($billOfMaterial->status === BillOfMaterial::STATUS_REJECTED) {
            
            // BOM REJECTED - SELALU BUAT BOM BARU (file lama tetap ada)
            $newStatus = $submitForApproval 
                ? BillOfMaterial::STATUS_PENDING_APPROVAL_1 
                : BillOfMaterial::STATUS_DRAFT;

            // Buat BOM baru (duplikat nomor diizinkan)
            $newBillOfMaterial = BillOfMaterial::create([
                'nomor_bom' => $request->nomor_bom, // Nomor boleh sama dengan yang lain
                'created_by' => $currentUserId,
                'kategori' => $request->kategori,
                'proyek_id' => $request->proyek_id,
                'revisi_id' => $request->revisi_id,
                'tanggal' => $request->tanggal,
                'status' => $newStatus
            ]);

            // Simpan Item BOM untuk BOM baru
            foreach ($request->items as $item) {
                if (!empty($item['material_id'])) {
                    ItemBom::create([
                        'bill_of_material_id' => $newBillOfMaterial->id,
                        'kode_material_id' => $item['material_id'],
                        'qty' => $item['qty'],
                        'satuan' => $item['satuan'],
                        'keterangan' => $item['keterangan'] ?? null
                    ]);
                }
            }

            // File rejected tetap ada, tidak diubah
            $message = $submitForApproval 
                ? 'BOM baru berhasil dibuat dan disubmit untuk approval. BOM yang rejected tetap tersimpan sebagai history.'
                : 'BOM baru berhasil dibuat sebagai draft. BOM yang rejected tetap tersimpan sebagai history.';
                
        } elseif ($billOfMaterial->status === BillOfMaterial::STATUS_DRAFT) {
            
            // BOM DRAFT - OVERWRITE FILE YANG ADA
            if ($submitForApproval) {
                // DRAFT -> SUBMIT: Update BOM yang ada
                $billOfMaterial->update([
                    'nomor_bom' => $request->nomor_bom,
                    'kategori' => $request->kategori,
                    'proyek_id' => $request->proyek_id,
                    'revisi_id' => $request->revisi_id,
                    'tanggal' => $request->tanggal,
                    'status' => BillOfMaterial::STATUS_PENDING_APPROVAL_1
                ]);

                $message = 'BOM berhasil diupdate dan disubmit untuk approval';
                
            } else {
                // DRAFT -> SAVE AS DRAFT: Update BOM yang ada
                $billOfMaterial->update([
                    'nomor_bom' => $request->nomor_bom,
                    'kategori' => $request->kategori,
                    'proyek_id' => $request->proyek_id,
                    'revisi_id' => $request->revisi_id,
                    'tanggal' => $request->tanggal,
                    'status' => BillOfMaterial::STATUS_DRAFT
                ]);

                $message = 'BOM berhasil diupdate dengan status DRAFT';
            }

            // Update items untuk BOM yang ada
            ItemBom::where('bill_of_material_id', $billOfMaterial->id)->delete();

            foreach ($request->items as $item) {
                if (!empty($item['material_id'])) {
                    ItemBom::create([
                        'bill_of_material_id' => $billOfMaterial->id,
                        'kode_material_id' => $item['material_id'],
                        'qty' => $item['qty'],
                        'satuan' => $item['satuan'],
                        'keterangan' => $item['keterangan'] ?? null
                    ]);
                }
            }
            
        } else {
            // Status lain tidak boleh diedit
            throw new \Exception('BOM dengan status ' . $billOfMaterial->status . ' tidak dapat diedit');
        }

        DB::commit();

        return redirect()->route('bom.index')->with('success', $message);

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
            
            // PERBAIKAN: Hanya BOM yang sudah APPROVED tidak boleh dihapus
            // BOM yang sudah di-approve tidak dapat dihapus
            if ($bom->status === BillOfMaterial::STATUS_APPROVED) {
                return response()->json([
                    'success' => false,
                    'message' => 'BOM yang sudah di-approve tidak dapat dihapus'
                ], 400);
            }
            
            // PERBAIKAN: Menggunakan fungsi helper untuk mendapatkan user ID
            $currentUserId = $this->getCurrentUserId();
            
            // Check permission - hanya creator atau user dengan permission khusus yang bisa hapus
            if ($bom->created_by !== $currentUserId && !Auth::user()->can('bom.destroy')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk menghapus BOM ini'
                ], 403);
            }
            
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

    /**
     * Get pending approvals for current user
     */
    public function pendingApprovals()
    {
        $user = Auth::user();
        $pendingBoms = collect();

        // Get BOM pending approval 1 if user has permission
        if ($user->can('bom.approve.1')) {
            $pending1 = BillOfMaterial::with(['proyek', 'revisi', 'createdBy'])
                ->where('status', BillOfMaterial::STATUS_PENDING_APPROVAL_1)
                ->get();
            $pendingBoms = $pendingBoms->merge($pending1);
        }

        // Get BOM pending approval 2 if user has permission
        if ($user->can('bom.approve.2')) {
            $pending2 = BillOfMaterial::with(['proyek', 'revisi', 'createdBy', 'approvedBy1'])
                ->where('status', BillOfMaterial::STATUS_PENDING_APPROVAL_2)
                ->get();
            $pendingBoms = $pendingBoms->merge($pending2);
        }

        return view('bom.pending-approvals', compact('pendingBoms'));
    }

    /**
     * Submit BOM for approval
     */
    public function submit(Request $request, $id)
    {
        try {
            $bom = BillOfMaterial::findOrFail($id);
            
            // PERBAIKAN: Menggunakan fungsi helper untuk mendapatkan user ID
            $currentUserId = $this->getCurrentUserId();
            
            // Check permission
            if ($bom->created_by !== $currentUserId && !Auth::user()->can('bom.submit')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk submit BOM ini'
                ], 403);
            }

            if ($bom->submitForApproval()) {
                return response()->json([
                    'success' => true,
                    'message' => 'BOM berhasil disubmit untuk approval'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'BOM tidak dapat disubmit dalam status saat ini'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal submit BOM: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve BOM by level 1
     */
    public function approve1(Request $request, $id)
    {
        try {
            $bom = BillOfMaterial::findOrFail($id);
            
            if (!Auth::user()->can('bom.approve.1')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk approve level 1'
                ], 403);
            }

            $note = $request->input('note');
            
            // PERBAIKAN: Pastikan menggunakan ID user untuk approval
            $currentUserId = $this->getCurrentUserId();
            
            if ($bom->approveBy1($currentUserId, $note)) {
                return response()->json([
                    'success' => true,
                    'message' => 'BOM berhasil di-approve level 1'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'BOM tidak dapat di-approve dalam status saat ini'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal approve BOM: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve BOM by level 2
     */
    public function approve2(Request $request, $id)
    {
        try {
            $bom = BillOfMaterial::findOrFail($id);
            
            if (!Auth::user()->can('bom.approve.2')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk approve level 2'
                ], 403);
            }

            $note = $request->input('note');
            
            // PERBAIKAN: Pastikan menggunakan ID user untuk approval
            $currentUserId = $this->getCurrentUserId();
            
            if ($bom->approveBy2($currentUserId, $note)) {
                return response()->json([
                    'success' => true,
                    'message' => 'BOM berhasil di-approve dan telah dipublish'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'BOM tidak dapat di-approve dalam status saat ini'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal approve BOM: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject BOM
     */
    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'note' => 'required|string|max:500'
        ], [
            'note.required' => 'Alasan penolakan harus diisi'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            $bom = BillOfMaterial::findOrFail($id);
            
            if (!Auth::user()->can('bom.reject')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk reject BOM'
                ], 403);
            }

            $note = $request->input('note');
            
            // PERBAIKAN: Pastikan menggunakan ID user untuk rejection
            $currentUserId = $this->getCurrentUserId();
            
            if ($bom->reject($currentUserId, $note)) {
                return response()->json([
                    'success' => true,
                    'message' => 'BOM berhasil di-reject'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'BOM tidak dapat di-reject dalam status saat ini'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal reject BOM: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get materials for AJAX
     */
    public function getMaterials(Request $request)
    {
        $search = $request->get('search', '');
        
        $materials = KodeMaterial::with('uom')
            ->when($search, function($query, $search) {
                return $query->where('kode_material', 'like', "%{$search}%")
                           ->orWhere('nama_material', 'like', "%{$search}%");
            })
            ->orderBy('kode_material')
            ->limit(50)
            ->get()
            ->map(function ($material) {
                return [
                    'id' => $material->id,
                    'text' => $material->kode_material . ' - ' . $material->nama_material,
                    'nama_material' => $material->nama_material,
                    'spesifikasi' => $material->spesifikasi,
                    'satuan' => $material->uom ? $material->uom->satuan : '',
                    'qty_uom' => $material->uom ? $material->uom->qty : 1
                ];
            });

        return response()->json($materials);
    }

    /**
     * Generate BOM report
     */
    public function generateReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'proyek_id' => 'nullable|exists:proyek,id',
            'status' => 'nullable|in:DRAFT,PENDING_APPROVAL_1,PENDING_APPROVAL_2,APPROVED,REJECTED',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            $query = BillOfMaterial::with(['proyek', 'revisi', 'itemBom.kodeMaterial.uom', 'createdBy', 'approvedBy1', 'approvedBy2']);

            // Apply filters
            if ($request->proyek_id) {
                $query->where('proyek_id', $request->proyek_id);
            }

            if ($request->status) {
                $query->where('status', $request->status);
            }

            if ($request->start_date) {
                $query->whereDate('tanggal', '>=', $request->start_date);
            }

            if ($request->end_date) {
                $query->whereDate('tanggal', '<=', $request->end_date);
            }

            $boms = $query->orderBy('tanggal', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $boms
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate report: ' . $e->getMessage()
            ], 500);
        }
    }

    

    /**
     * Get BOM statistics
     */
    public function getStatistics(Request $request)
    {
        try {
            $stats = [
                'total_bom' => BillOfMaterial::count(),
                'draft' => BillOfMaterial::where('status', BillOfMaterial::STATUS_DRAFT)->count(),
                'pending_approval_1' => BillOfMaterial::where('status', BillOfMaterial::STATUS_PENDING_APPROVAL_1)->count(),
                'pending_approval_2' => BillOfMaterial::where('status', BillOfMaterial::STATUS_PENDING_APPROVAL_2)->count(),
                'approved' => BillOfMaterial::where('status', BillOfMaterial::STATUS_APPROVED)->count(),
                'rejected' => BillOfMaterial::where('status', BillOfMaterial::STATUS_REJECTED)->count(),
            ];

            // Monthly statistics for current year
            $monthlyStats = BillOfMaterial::selectRaw('MONTH(tanggal) as month, COUNT(*) as count')
                ->whereYear('tanggal', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->keyBy('month');

            $monthlyData = [];
            for ($i = 1; $i <= 12; $i++) {
                $monthlyData[] = [
                    'month' => date('M', mktime(0, 0, 0, $i, 1)),
                    'count' => $monthlyStats->get($i)->count ?? 0
                ];
            }

            $stats['monthly'] = $monthlyData;

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan statistik: ' . $e->getMessage()
            ], 500);
        }
    }
}