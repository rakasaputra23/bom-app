<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillOfMaterial extends Model
{
    use HasFactory;

    protected $table = 'bill_of_material';

    protected $fillable = [
        'nomor_bom',
        'kategori',
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

    protected $casts = [
        'tanggal' => 'date',
        'approved_by_1_at' => 'datetime',
        'approved_by_2_at' => 'datetime',
        'rejected_at' => 'datetime',
        // PERBAIKAN: Pastikan semua field user ID di-cast sebagai integer
        'created_by' => 'integer',
        'approved_by_1' => 'integer',
        'approved_by_2' => 'integer',
        'rejected_by' => 'integer',
    ];

    // Status constants
    const STATUS_DRAFT = 'DRAFT';
    const STATUS_PENDING_APPROVAL_1 = 'PENDING_APPROVAL_1';
    const STATUS_PENDING_APPROVAL_2 = 'PENDING_APPROVAL_2';
    const STATUS_APPROVED = 'APPROVED';
    const STATUS_REJECTED = 'REJECTED';

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
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_REJECTED]);
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
            $this->approved_by_1_at = now();
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
            $this->approved_by_2_at = now();
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
            $this->rejected_at = now();
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
            case self::STATUS_REJECTED:
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