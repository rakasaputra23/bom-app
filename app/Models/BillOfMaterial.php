<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\JenisDokumen;
use Carbon\Carbon;

class BillOfMaterial extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bill_of_material';

    protected $fillable = [
        'nomor_bom',
        'kategori',
        'jenis_dokumen_id',
        'proyek_id',
        'revisi_id',
        'tanggal',
        'created_by',
        'status',
        'approved_by_1',
        'approved_by_1_at',
        'approved_by_1_note',
        'approved_by_2',
        'approved_by_2_at',
        'approved_by_2_note',
        'rejected_by',
        'rejected_at',
        'rejected_note'
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'tanggal' => 'date',
        'approved_by_1_at' => 'datetime',
        'approved_by_2_at' => 'datetime',
        'rejected_at' => 'datetime',
        'deleted_at' => 'datetime',
        // PERBAIKAN: Pastikan semua field user ID di-cast sebagai integer
        'created_by' => 'integer',
        'approved_by_1' => 'integer',
        'approved_by_2' => 'integer',
        'rejected_by' => 'integer',
    ];

    // PERBAIKAN: Set timezone Indonesia untuk semua attribute tanggal
    protected function serializeDate(\DateTimeInterface $date)
    {
        // Pastikan semua tanggal ditampilkan dalam timezone Indonesia
        return Carbon::instance($date)->setTimezone('Asia/Jakarta');
    }

    // PERBAIKAN: Override accessor untuk semua datetime dengan timezone Indonesia
    public function getUpdatedAtAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->setTimezone('Asia/Jakarta');
        }
        return null;
    }

    public function getCreatedAtAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->setTimezone('Asia/Jakarta');
        }
        return null;
    }

    public function getApprovedBy1AtAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->setTimezone('Asia/Jakarta');
        }
        return null;
    }

    public function getApprovedBy2AtAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->setTimezone('Asia/Jakarta');
        }
        return null;
    }

    public function getRejectedAtAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->setTimezone('Asia/Jakarta');
        }
        return null;
    }

    // Status constants
    const STATUS_DRAFT = 'DRAFT';
    const STATUS_PENDING_APPROVAL_1 = 'PENDING_APPROVAL_1';
    const STATUS_PENDING_APPROVAL_2 = 'PENDING_APPROVAL_2';
    const STATUS_APPROVED = 'APPROVED';
    const STATUS_REJECTED = 'REJECTED';


    
/**
 * Generate nomor BOM berdasarkan jenis dokumen
 * Format: {kode_unit}/{nama_perusahaan}/{jenis_dokumen}-{kode_proyek}/{tahun}
 * Kode unit: 4 + nomor urut (berdasarkan jenis dokumen, bukan proyek)
 */
public static function generateNomorBom($proyekId, $jenisDokumenId)
{
    try {
        // Get data proyek dan jenis dokumen
        $proyek = \App\Models\Proyek::findOrFail($proyekId);
        $jenisDokumen = \App\Models\JenisDokumen::findOrFail($jenisDokumenId);
        
        // 1. Nama Perusahaan - otomatis IMS
        $namaPerusahaan = 'IMS';
        
        // 2. Jenis Dokumen - dari tabel jenis_dokumen (BRM, ERC, dll)
        $jenisDokumenKode = $jenisDokumen->kode_dokumen;
        
        // 3. Kode Proyek - dari tabel proyek
        $kodeProyek = $proyek->kode_proyek;
        
        // 4. Tahun - tahun pembuatan BOM
        $tahun = date('Y');
        
        // 5. Cari nomor urut terakhir berdasarkan JENIS DOKUMEN dan TAHUN saja
        // Mencari semua BOM dengan jenis dokumen yang sama (tidak peduli proyek)
        $lastBom = static::where('jenis_dokumen_id', $jenisDokumenId)
            ->whereYear('created_at', $tahun)
            ->orderByRaw('CAST(SUBSTRING_INDEX(nomor_bom, "/", 1) AS UNSIGNED) DESC')
            ->first();
        
        $nomorUrut = 1; // Default nomor urut mulai dari 01
        
        if ($lastBom && $lastBom->nomor_bom) {
            // Extract nomor urut dari kode unit (4XX)
            $parts = explode('/', $lastBom->nomor_bom);
            if (count($parts) >= 4 && strlen($parts[0]) >= 3) { // 401/IMS/BRM-E12/2025
                $kodeUnit = $parts[0]; // Ambil bagian pertama (4XX)
                if (substr($kodeUnit, 0, 1) === '4') { // Pastikan dimulai dengan 4
                    $lastNumber = intval(substr($kodeUnit, 1)); // Ambil XX dari 4XX
                    $nomorUrut = $lastNumber + 1;
                }
            }
        }
        
        // 6. Kode Unit - 4 + nomor urut (4XX)
        $kodeUnit = '4' . str_pad($nomorUrut, 2, '0', STR_PAD_LEFT);
        
        // Format akhir: 401/IMS/BRM-E12/2025
        $nomorBom = "{$kodeUnit}/{$namaPerusahaan}/{$jenisDokumenKode}-{$kodeProyek}/{$tahun}";
        
        return $nomorBom;
        
    } catch (\Exception $e) {
        // Log error untuk debugging
        \Log::error('Error generating nomor BOM: ' . $e->getMessage());
        
        // Fallback ke format lama jika ada error
        return static::generateFallbackNomorBom();
    }
}

    /**
 * Fallback method untuk generate nomor BOM jika terjadi error
 */
public static function generateFallbackNomorBom()
{
    $timestamp = time();
    $randomNumber = rand(10, 99);
    return "TEMP_{$timestamp}_{$randomNumber}";
}

    /**
 * Get next nomor urut untuk jenis dokumen tertentu
 */
public static function getNextNomorUrut($jenisDokumenId, $tahun = null)
{
    if (!$tahun) {
        $tahun = date('Y');
    }
    
    $lastBom = self::where('jenis_dokumen_id', $jenisDokumenId)
                   ->whereYear('created_at', $tahun)
                   ->orderByRaw('CAST(SUBSTRING_INDEX(nomor_bom, "/", 1) AS UNSIGNED) DESC')
                   ->first();
    
    if (!$lastBom || !$lastBom->nomor_bom) {
        return 1;
    }
    
    // Extract nomor urut dari format: 401/IMS/BRM-4566/2025
    $parts = explode('/', $lastBom->nomor_bom);
    if (count($parts) >= 4 && strlen($parts[0]) >= 2) {
        $kodeUnit = $parts[0]; // 4XX
        $lastNomorUrut = intval(substr($kodeUnit, 1)); // XX dari 4XX
        return $lastNomorUrut + 1;
    }
    
    return 1;
}

/**
 * Check if nomor BOM already exists
 */
public static function isNomorBomExists($nomorBom, $excludeId = null)
{
    $query = self::where('nomor_bom', $nomorBom);
    
    if ($excludeId) {
        $query->where('id', '!=', $excludeId);
    }
    
    return $query->exists();
}


    /**
 * Validate nomor BOM format
 * Expected format: 401/IMS/BRM-4566/2025 atau 402/IMS/ERC-1234/2025
 */
public static function validateNomorBomFormat($nomorBom)
{
    // Pattern untuk format: 4XX/IMS/JENIS_DOKUMEN-KODE_PROYEK/YYYY
    $pattern = '/^4\d{2}\/IMS\/[A-Z0-9]+-[A-Z0-9\-]+\/\d{4}$/';
    
    return preg_match($pattern, $nomorBom);
}


    // Available categories
    public static function getAvailableCategories()
    {
        return [
            'JIG' => 'JIG',
            'MAL' => 'MAL',
            'TOOL' => 'TOOL',
            'TOOLS' => 'TOOLS',
            'CONSUMABLE TOOLS' => 'CONSUMABLE TOOLS',
            'SPECIAL PROCESS' => 'SPECIAL PROCESS'
        ];
    }

    /**
     * PERBAIKAN: Mutator untuk memastikan created_by selalu integer
     */
    public function setCreatedByAttribute($value)
    {
        // Pastikan value adalah integer, bukan string/NIP
        if (is_numeric($value)) {
            $this->attributes['created_by'] = (int) $value;
        } else {
            throw new \InvalidArgumentException('created_by harus berupa ID user (integer), bukan NIP');
        }
    }

    /**
     * PERBAIKAN: Mutator untuk memastikan approved_by_1 selalu integer
     */
    public function setApprovedBy1Attribute($value)
    {
        if ($value === null) {
            $this->attributes['approved_by_1'] = null;
        } elseif (is_numeric($value)) {
            $this->attributes['approved_by_1'] = (int) $value;
        } else {
            throw new \InvalidArgumentException('approved_by_1 harus berupa ID user (integer), bukan NIP');
        }
    }

    /**
     * PERBAIKAN: Mutator untuk memastikan approved_by_2 selalu integer
     */
    public function setApprovedBy2Attribute($value)
    {
        if ($value === null) {
            $this->attributes['approved_by_2'] = null;
        } elseif (is_numeric($value)) {
            $this->attributes['approved_by_2'] = (int) $value;
        } else {
            throw new \InvalidArgumentException('approved_by_2 harus berupa ID user (integer), bukan NIP');
        }
    }

    /**
     * PERBAIKAN: Mutator untuk memastikan rejected_by selalu integer
     */
    public function setRejectedByAttribute($value)
    {
        if ($value === null) {
            $this->attributes['rejected_by'] = null;
        } elseif (is_numeric($value)) {
            $this->attributes['rejected_by'] = (int) $value;
        } else {
            throw new \InvalidArgumentException('rejected_by harus berupa ID user (integer), bukan NIP');
        }
    }

    /**
     * Relasi dengan proyek
     */
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }

    public function jenisDokumen()
    {
        return $this->belongsTo(JenisDokumen::class, 'jenis_dokumen_id');
    }

    /**
     * Relasi dengan revisi
     */
    public function revisi()
    {
        return $this->belongsTo(Revisi::class, 'revisi_id');
    }

    /**
     * Relasi dengan item BOM
     */
    public function itemBom()
    {
        return $this->hasMany(ItemBom::class, 'bill_of_material_id');
    }

    /**
     * Relasi dengan user yang membuat
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi dengan approver 1
     */
    public function approvedBy1()
    {
        return $this->belongsTo(User::class, 'approved_by_1');
    }

    /**
     * Relasi dengan approver 2
     */
    public function approvedBy2()
    {
        return $this->belongsTo(User::class, 'approved_by_2');
    }

    /**
     * Relasi dengan user yang reject
     */
    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk BOM yang perlu approval level 1
     */
    public function scopePendingApproval1($query)
    {
        return $query->where('status', self::STATUS_PENDING_APPROVAL_1);
    }

    /**
     * Scope untuk BOM yang perlu approval level 2
     */
    public function scopePendingApproval2($query)
    {
        return $query->where('status', self::STATUS_PENDING_APPROVAL_2);
    }

    /**
     * Scope untuk BOM yang sudah approved
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Check if BOM can be edited
     */
    public function canBeEdited()
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_REJECTED]);
    }

    /**
     * Check if BOM can be submitted for approval
     */
    public function canBeSubmitted()
    {
        return in_array($this->status, [self::STATUS_DRAFT]);
    }

    /**
     * Check if BOM can be approved by level 1
     */
    public function canBeApprovedBy1()
    {
        return $this->status === self::STATUS_PENDING_APPROVAL_1;
    }

    /**
     * Check if BOM can be approved by level 2
     */
    public function canBeApprovedBy2()
    {
        return $this->status === self::STATUS_PENDING_APPROVAL_2;
    }

    /**
     * Status badge accessor
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'DRAFT' => '<span class="badge badge-secondary">Draft</span>',
            'PENDING_APPROVAL_1' => '<span class="badge badge-warning">Pending Approval 1</span>',
            'PENDING_APPROVAL_2' => '<span class="badge badge-info">Pending Approval 2</span>',
            'APPROVED' => '<span class="badge badge-success">Approved</span>',
            'REJECTED' => '<span class="badge badge-danger">Rejected</span>',
        ];
        
        return $badges[$this->status] ?? '<span class="badge badge-light">' . $this->status . '</span>';
    }

    /**
     * Status text accessor
     */
    public function getStatusTextAttribute()
    {
        $texts = [
            'DRAFT' => 'Draft',
            'PENDING_APPROVAL_1' => 'Menunggu Approval 1',
            'PENDING_APPROVAL_2' => 'Menunggu Approval 2', 
            'APPROVED' => 'Approved',
            'REJECTED' => 'Rejected',
        ];
        
        return $texts[$this->status] ?? $this->status;
    }

    /**
     * Submit BOM for approval
     */
    public function submitForApproval()
    {
        if ($this->canBeSubmitted()) {
            $this->status = self::STATUS_PENDING_APPROVAL_1;
            // Reset approval data when resubmitting
            $this->approved_by_1 = null;
            $this->approved_by_1_at = null;
            $this->approved_by_1_note = null;
            $this->approved_by_2 = null;
            $this->approved_by_2_at = null;
            $this->approved_by_2_note = null;
            $this->rejected_by = null;
            $this->rejected_at = null;
            $this->rejected_note = null;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Approve by level 1
     * PERBAIKAN: Validasi input dan logging untuk debugging
     */
    public function approveBy1($userId, $note = null)
    {
        // PERBAIKAN: Validasi userId adalah integer
        if (!is_numeric($userId)) {
            throw new \InvalidArgumentException("User ID untuk approval harus berupa integer, diterima: " . gettype($userId) . " dengan nilai: " . $userId);
        }

        $userId = (int) $userId;

        // PERBAIKAN: Log untuk debugging
        \Log::info("Attempting to approve BOM ID {$this->id} by user ID {$userId}");

        if ($this->canBeApprovedBy1()) {
            $this->status = self::STATUS_PENDING_APPROVAL_2;
            $this->approved_by_1 = $userId;
            // PERBAIKAN: Pastikan menggunakan timezone Indonesia
            $this->approved_by_1_at = Carbon::now('Asia/Jakarta');
            $this->approved_by_1_note = $note;
            
            // PERBAIKAN: Validasi sebelum save
            $this->validate();
            $this->save();
            
            \Log::info("BOM ID {$this->id} successfully approved by user ID {$userId}");
            return true;
        }
        
        \Log::warning("BOM ID {$this->id} cannot be approved in current status: {$this->status}");
        return false;
    }

    /**
     * Approve by level 2 (Final Approval)
     * PERBAIKAN: Validasi input dan logging untuk debugging
     */
    public function approveBy2($userId, $note = null)
    {
        // PERBAIKAN: Validasi userId adalah integer
        if (!is_numeric($userId)) {
            throw new \InvalidArgumentException("User ID untuk approval harus berupa integer, diterima: " . gettype($userId) . " dengan nilai: " . $userId);
        }

        $userId = (int) $userId;

        // PERBAIKAN: Log untuk debugging
        \Log::info("Attempting to final approve BOM ID {$this->id} by user ID {$userId}");

        if ($this->canBeApprovedBy2()) {
            $this->status = self::STATUS_APPROVED;
            $this->approved_by_2 = $userId;
            // PERBAIKAN: Pastikan menggunakan timezone Indonesia
            $this->approved_by_2_at = Carbon::now('Asia/Jakarta');
            $this->approved_by_2_note = $note;
            
            // PERBAIKAN: Validasi sebelum save
            $this->validate();
            $this->save();
            
            \Log::info("BOM ID {$this->id} successfully final approved by user ID {$userId}");
            return true;
        }
        
        \Log::warning("BOM ID {$this->id} cannot be final approved in current status: {$this->status}");
        return false;
    }

    /**
     * Reject BOM
     * PERBAIKAN: Validasi input dan logging untuk debugging
     */
    public function reject($userId, $note = null)
    {
        // PERBAIKAN: Validasi userId adalah integer
        if (!is_numeric($userId)) {
            throw new \InvalidArgumentException("User ID untuk rejection harus berupa integer, diterima: " . gettype($userId) . " dengan nilai: " . $userId);
        }

        $userId = (int) $userId;

        // PERBAIKAN: Log untuk debugging
        \Log::info("Attempting to reject BOM ID {$this->id} by user ID {$userId}");

        if (in_array($this->status, [self::STATUS_PENDING_APPROVAL_1, self::STATUS_PENDING_APPROVAL_2])) {
            $this->status = self::STATUS_REJECTED;
            $this->rejected_by = $userId;
            // PERBAIKAN: Pastikan menggunakan timezone Indonesia
            $this->rejected_at = Carbon::now('Asia/Jakarta');
            $this->rejected_note = $note;
            
            // PERBAIKAN: Validasi sebelum save
            $this->validate();
            $this->save();
            
            \Log::info("BOM ID {$this->id} successfully rejected by user ID {$userId}");
            return true;
        }
        
        \Log::warning("BOM ID {$this->id} cannot be rejected in current status: {$this->status}");
        return false;
    }

    /**
     * PERBAIKAN: Validasi data sebelum save
     */
    public function validate()
    {
        // Validasi bahwa semua user ID adalah integer atau null
        $userFields = ['created_by', 'approved_by_1', 'approved_by_2', 'rejected_by'];
        
        foreach ($userFields as $field) {
            $value = $this->attributes[$field] ?? null;
            if ($value !== null && !is_int($value) && !ctype_digit($value)) {
                throw new \InvalidArgumentException("Field {$field} harus berupa integer (user ID), bukan: " . gettype($value));
            }
        }
    }

    /**
     * Get approval history
     */
    public function getApprovalHistoryAttribute()
    {
        $history = [];
        
        if ($this->approved_by_1) {
            $history[] = [
                'level' => 'Approval 1',
                'approver' => $this->approvedBy1,
                'date' => $this->approved_by_1_at,
                'note' => $this->approved_by_1_note,
                'type' => 'approval'
            ];
        }
        
        if ($this->approved_by_2) {
            $history[] = [
                'level' => 'Final Approval',
                'approver' => $this->approvedBy2,
                'date' => $this->approved_by_2_at,
                'note' => $this->approved_by_2_note,
                'type' => 'approval'
            ];
        }
        
        if ($this->rejected_by) {
            $history[] = [
                'level' => 'Rejected',
                'approver' => $this->rejectedBy,
                'date' => $this->rejected_at,
                'note' => $this->rejected_note,
                'type' => 'rejection'
            ];
        }
        
        return collect($history)->sortBy('date');
    }

    /**
     * Check if user can submit this BOM
     */
    public function canBeSubmittedBy($userId)
    {
        return $this->created_by === (int) $userId && $this->canBeSubmitted();
    }

    /**
     * Check if BOM is in pending state
     */
    public function isPending()
    {
        return in_array($this->status, [self::STATUS_PENDING_APPROVAL_1, self::STATUS_PENDING_APPROVAL_2]);
    }

    /**
     * Get next approval level required
     */
    public function getNextApprovalLevel()
    {
        switch ($this->status) {
            case self::STATUS_DRAFT:
                return 'submit';
            case self::STATUS_PENDING_APPROVAL_1:
                return 'approval_1';
            case self::STATUS_PENDING_APPROVAL_2:
                return 'approval_2';
            case self::STATUS_APPROVED:
                return 'completed';
            default:
                return 'unknown';
        }
    }
}