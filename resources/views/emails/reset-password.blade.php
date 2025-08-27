<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - BOM System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Source Sans Pro', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px 0;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
            overflow: hidden;
        }
        
        .email-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 30px 20px;
            text-align: center;
            position: relative;
        }
        
        .email-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="%23ffffff" opacity="0.1"><path d="M0,0 C150,100 350,0 500,50 C650,100 850,0 1000,50 L1000,100 L0,100 Z"/></svg>');
            background-size: cover;
        }
        
        .brand-logo {
            position: relative;
            z-index: 2;
            margin-bottom: 15px;
        }
        
        .brand-logo img {
            height: 50px;
            margin-bottom: 10px;
            filter: brightness(0) invert(1) drop-shadow(0 2px 4px rgba(0,0,0,0.3));
            display: block;
            max-width: 100%;
        }
        
        .logo-fallback {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.8);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: 700;
            font-size: 20px;
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            letter-spacing: 1px;
        }
        
        .logo-image {
            display: block;
        }
        
        .logo-hidden {
            display: none;
        }
        
        .brand-text {
            position: relative;
            z-index: 2;
        }
        
        .brand-bom {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .brand-system {
            font-size: 16px;
            font-weight: 400;
            opacity: 0.9;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
        
        .brand-subtitle {
            font-size: 14px;
            opacity: 0.8;
            margin-top: 8px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
        
        .email-content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        
        .message {
            font-size: 16px;
            color: #555;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        
        .info-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
            border-left: 4px solid #007bff;
        }
        
        .info-card h3 {
            color: #007bff;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 8px 15px;
            font-size: 14px;
        }
        
        .info-label {
            font-weight: 600;
            color: #495057;
        }
        
        .info-value {
            color: #6c757d;
        }
        
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            text-decoration: none;
            padding: 15px 35px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .url-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            word-break: break-all;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            color: #495057;
        }
        
        .security-warning {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            border: 1px solid #ffc107;
            border-left: 4px solid #ffc107;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        
        .security-warning h3 {
            color: #856404;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .warning-list {
            list-style: none;
            padding: 0;
        }
        
        .warning-list li {
            color: #6c5d03;
            font-size: 14px;
            margin-bottom: 8px;
            padding-left: 20px;
            position: relative;
        }
        
        .warning-list li::before {
            content: '•';
            color: #ffc107;
            font-weight: bold;
            position: absolute;
            left: 0;
        }
        
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #dee2e6, transparent);
            margin: 30px 0;
        }
        
        .email-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            padding: 25px 30px;
            text-align: center;
        }
        
        .footer-text {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .footer-contact {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }
        
        .footer-contact:hover {
            text-decoration: underline;
        }
        
        .footer-copyright {
            color: #adb5bd;
            font-size: 12px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
        }
        
        /* Responsive Design */
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            
            .email-container {
                border-radius: 10px;
                margin: 0 10px;
            }
            
            .email-header {
                padding: 25px 15px;
            }
            
            .brand-bom {
                font-size: 24px;
            }
            
            .brand-system {
                font-size: 14px;
            }
            
            .email-content {
                padding: 30px 20px;
            }
            
            .greeting {
                font-size: 18px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
                gap: 5px;
            }
            
            .info-label {
                font-weight: 600;
                margin-bottom: 2px;
            }
            
            .info-value {
                margin-bottom: 10px;
                padding-bottom: 10px;
                border-bottom: 1px solid #e9ecef;
            }
            
            .reset-button {
                padding: 12px 25px;
                font-size: 14px;
            }
            
            .email-footer {
                padding: 20px 15px;
            }
        }
        
        @media (max-width: 480px) {
            .info-card, .security-warning {
                padding: 15px;
            }
            
            .url-box {
                padding: 12px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header dengan branding yang konsisten dengan login page -->
        <div class="email-header">
            <div class="brand-logo">
                <!-- Logo dengan embedded image dan fallback -->
                <img src="cid:logo.png" alt="BOM System Logo" class="logo-image" onerror="this.style.display='none'; document.querySelector('.logo-fallback').style.display='flex';">
                <div class="logo-fallback" style="display: none;">
                    BOM
                </div>
            </div>
            <div class="brand-text">
                <div class="brand-bom">BOM</div>
                <div class="brand-system">System</div>
                <div class="brand-subtitle">Bill of Materials Management</div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="email-content">
            <div class="greeting">
                Halo, <strong>{{ $user->nama }}</strong>
            </div>

            <div class="message">
                Anda telah meminta untuk mereset password akun BOM System Anda. 
                Silakan gunakan tombol di bawah ini untuk melanjutkan proses reset password.
            </div>

            <!-- Info Card dengan detail yang lebih terstruktur -->
            <div class="info-card">
                <h3>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    Detail Permintaan Reset Password
                </h3>
                <div class="info-grid">
                    <span class="info-label">NIP:</span>
                    <span class="info-value">{{ $user->nip }}</span>
                    
                    <span class="info-label">Nama:</span>
                    <span class="info-value">{{ $user->nama }}</span>
                    
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $user->email }}</span>
                    
                    <span class="info-label">Waktu Permintaan:</span>
                    <span class="info-value">{{ now()->format('d/m/Y H:i:s') }} WIB</span>
                    
                    <span class="info-label">Berlaku Hingga:</span>
                    <span class="info-value">{{ $validUntil }} WIB</span>
                </div>
            </div>

            <!-- Reset Button dengan styling yang konsisten -->
            <div class="button-container">
                <a href="{{ $resetUrl }}" class="reset-button">
                    Reset Password
                </a>
            </div>

            <div class="divider"></div>

            <div class="message">
                Jika tombol di atas tidak berfungsi, salin dan tempelkan URL berikut di browser Anda:
            </div>

            <div class="url-box">
                {{ $resetUrl }}
            </div>

            <!-- Security Warning dengan styling yang lebih profesional -->
            <div class="security-warning">
                <h3>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                    </svg>
                    Penting untuk Keamanan
                </h3>
                <ul class="warning-list">
                    <li>Link ini hanya berlaku selama <strong>60 menit</strong> sejak email dikirim</li>
                    <li>Jangan bagikan link ini kepada siapa pun</li>
                    <li>Jika Anda tidak meminta reset password, abaikan email ini</li>
                    <li>Segera hubungi administrator jika ada aktivitas mencurigakan</li>
                    <li>Gunakan password yang kuat dengan kombinasi huruf, angka, dan simbol</li>
                </ul>
            </div>
        </div>

        <!-- Footer dengan informasi kontak -->
        <div class="email-footer">
            <div class="footer-text">
                Email ini dikirim secara otomatis oleh <strong>BOM System</strong>
            </div>
            <div class="footer-text">
                Butuh bantuan? Hubungi: 
                <a href="mailto:support@bomsystem.com" class="footer-contact">support@bomsystem.com</a>
            </div>
            <div class="footer-copyright">
                &copy; {{ date('Y') }} BOM System - PT. INKA Multi Solusi. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>