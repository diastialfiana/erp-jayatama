<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Your New Password - ERP Jayatama</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #0f172a;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #e2e8f0;
        }

        .card {
            background: #1e293b;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 420px;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 8px;
        }

        .header p {
            font-size: 0.875rem;
            color: #94a3b8;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #cbd5e1;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid #334155;
            padding: 12px 40px 12px 16px;
            border-radius: 8px;
            color: #ffffff;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: #cbd5e1;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 6px;
            display: block;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #ef4444;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 0.875rem;
        }

        .alert-error ul {
            margin-left: 20px;
            margin-top: 4px;
        }

        .btn-submit {
            width: 100%;
            background: #3b82f6;
            color: #ffffff;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
        }

        .btn-submit:hover:not(:disabled) {
            background: #2563eb;
        }

        .btn-submit:disabled {
            background: #475569;
            cursor: not-allowed;
            color: #94a3b8;
        }

        .spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #ffffff;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .btn-submit.loading .spinner {
            display: inline-block;
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="header">
            <h2>Set Your New Password</h2>
            <p>Untuk keamanan akun Anda, silakan ganti password default sebelum melanjutkan.</p>
        </div>

        @if($errors->any())
            <div class="alert-error">
                <strong>Terdapat Kesalahan:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" id="changePasswordForm">
            @csrf

            <div class="form-group">
                <label for="password" class="form-label">Password Baru</label>
                <div class="input-wrapper">
                    <input type="password" name="password" id="password" required
                           class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Minimal 6 karakter"
                           minlength="6">
                    <button type="button" class="toggle-password" onclick="togglePassword('password', this)">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="eye-icon"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <div class="input-wrapper">
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="form-control @error('password_confirmation') is-invalid @enderror" 
                           placeholder="Ulangi password baru">
                    <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', this)">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="eye-icon"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <span class="spinner"></span>
                <span class="btn-text">Simpan & Lanjutkan</span>
            </button>
        </form>
    </div>

    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('.eye-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
            }
        }

        document.getElementById('changePasswordForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.classList.add('loading');
            btn.disabled = true;
            btn.querySelector('.btn-text').textContent = 'Memproses...';
        });
    </script>
</body>
</html>