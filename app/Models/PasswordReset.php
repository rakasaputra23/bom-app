<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordReset extends Model
{
    protected $table = 'password_reset_tokens';
    
    protected $primaryKey = 'nip';
    
    public $incrementing = false;
    
    protected $keyType = 'string';
    
    public $timestamps = false;

    protected $fillable = [
        'nip',
        'token',
        'created_at',
    ];

    protected $dates = [
        'created_at',
    ];

    /**
     * FIXED: Generate token untuk reset password dengan error handling
     */
    public static function generateToken($nip)
    {
        try {
            // Hapus token lama jika ada
            static::where('nip', $nip)->delete();
            
            // Generate token baru
            $token = Str::random(64);
            
            // Simpan token dengan DB transaction
            DB::beginTransaction();
            
            $created = static::create([
                'nip' => $nip,
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]);
            
            DB::commit();
            
            Log::info('Password reset token generated', [
                'nip' => $nip,
                'token_length' => strlen($token)
            ]);
            
            return $token;
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to generate password reset token', [
                'nip' => $nip,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw new \Exception('Gagal membuat token reset password: ' . $e->getMessage());
        }
    }

    /**
     * FIXED: Validasi token reset password dengan logging
     */
    public static function validateToken($nip, $token)
    {
        try {
            $record = static::where('nip', $nip)->first();
            
            if (!$record) {
                Log::warning('Password reset token not found', ['nip' => $nip]);
                return false;
            }

            // Cek apakah token sudah expired (60 menit)
            $createdAt = Carbon::parse($record->created_at);
            $expiredAt = $createdAt->copy()->addMinutes(60);
            
            if ($expiredAt->isPast()) {
                Log::info('Password reset token expired', [
                    'nip' => $nip,
                    'created_at' => $createdAt->toDateTimeString(),
                    'expired_at' => $expiredAt->toDateTimeString()
                ]);
                
                $record->delete();
                return false;
            }

            // Verifikasi token
            $isValid = Hash::check($token, $record->token);
            
            Log::info('Password reset token validation', [
                'nip' => $nip,
                'is_valid' => $isValid,
                'token_age_minutes' => $createdAt->diffInMinutes(Carbon::now())
            ]);
            
            return $isValid;
            
        } catch (\Exception $e) {
            Log::error('Error validating password reset token', [
                'nip' => $nip,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return false;
        }
    }

    /**
     * FIXED: Hapus token setelah digunakan dengan error handling
     */
    public static function deleteToken($nip)
    {
        try {
            $deleted = static::where('nip', $nip)->delete();
            
            Log::info('Password reset token deleted', [
                'nip' => $nip,
                'deleted_count' => $deleted
            ]);
            
            return $deleted > 0;
            
        } catch (\Exception $e) {
            Log::error('Failed to delete password reset token', [
                'nip' => $nip,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * FIXED: Cleanup token yang expired dengan logging
     */
    public static function cleanupExpiredTokens()
    {
        try {
            $expiredTime = Carbon::now()->subHours(1);
            $deleted = static::where('created_at', '<', $expiredTime)->delete();
            
            if ($deleted > 0) {
                Log::info('Expired password reset tokens cleaned up', [
                    'deleted_count' => $deleted,
                    'expired_before' => $expiredTime->toDateTimeString()
                ]);
            }
            
            return $deleted;
            
        } catch (\Exception $e) {
            Log::error('Failed to cleanup expired password reset tokens', [
                'error' => $e->getMessage()
            ]);
            
            return 0;
        }
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'nip', 'nip');
    }

    /**
     * Cek apakah tabel ada dan dapat diakses
     */
    public static function checkTableExists()
    {
        try {
            DB::table('password_reset_tokens')->limit(1)->get();
            return true;
        } catch (\Exception $e) {
            Log::error('Password reset tokens table not accessible', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}