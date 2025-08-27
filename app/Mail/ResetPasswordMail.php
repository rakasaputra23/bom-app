<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use App\Models\User;

class ResetPasswordMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $token;
    public $resetUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
        $this->resetUrl = url('/password/reset/form?token=' . $token . '&nip=' . $user->nip);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        \Log::info('Custom ResetPasswordMail called with subject: BOM System Reset Password');
        
        return new Envelope(
            subject: 'BOM System - Reset Password - NIP: ' . $this->user->nip,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-password',
            with: [
                'user' => $this->user,
                'resetUrl' => $this->resetUrl,
                'validUntil' => now()->addMinutes(60)->format('d/m/Y H:i'),
            ]
        );
    }

    /**
     * Get the attachments for the message - FIXED: Logo embedding
     */
    public function attachments(): array
    {
        $logoPath = public_path('dist/img/logo-login.png');
        
        // Cek apakah file logo ada sebelum attach
        if (file_exists($logoPath)) {
            return [
                Attachment::fromPath($logoPath)
                    ->as('logo.png')
                    ->withMime('image/png'),
            ];
        }
        
        // Jika logo tidak ada, return empty array (akan menggunakan text logo)
        return [];
    }
}