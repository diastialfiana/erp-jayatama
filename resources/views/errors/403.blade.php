<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
            overflow: hidden;
            position: relative;
        }

        /* Animated Background Elements */
        .bg-glow {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(239, 68, 68, 0.15) 0%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
            animation: pulse 4s infinite alternate;
        }

        @keyframes pulse {
            0% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.5;
            }

            100% {
                transform: translate(-50%, -50%) scale(1.1);
                opacity: 1;
            }
        }

        .card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 50px 40px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
            width: 100%;
            max-width: 450px;
            z-index: 10;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-code {
            font-size: 6rem;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
            text-shadow: 0 10px 30px rgba(239, 68, 68, 0.3);
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 16px;
        }

        .error-message {
            font-size: 0.95rem;
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 24px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn svg {
            width: 18px;
            height: 18px;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .btn-danger {
            background: #ef4444;
            color: #ffffff;
        }

        .btn-danger:hover {
            background: #f87171;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(239, 68, 68, 0.5);
        }

        .spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #ffffff;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .btn-danger.loading .spinner {
            display: inline-block;
        }

        .btn-danger.loading .btn-icon {
            display: none;
        }
    </style>
</head>

<body>
    <div class="bg-glow"></div>

    <div class="card">
        <div class="error-code">403</div>
        <h1 class="error-title">Akses Ditolak</h1>
        <p class="error-message">
            Maaf, Anda tidak memiliki izin <i>(permission)</i> untuk mengakses halaman ini. <br><br>
            <strong style="color: #cbd5e1;">Silakan hubungin admin untuk mendapatkan akses.</strong>
        </p>

        <div class="action-buttons">
            <a href="{{ route('login') }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Beranda
            </a>

            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                @csrf
                <button type="submit" class="btn btn-danger" id="logoutBtn">
                    <span class="spinner"></span>
                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span class="btn-text">Logout & Login Ulang</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('logoutForm').addEventListener('submit', function () {
            const btn = document.getElementById('logoutBtn');
            btn.classList.add('loading');
            btn.disabled = true;
            btn.querySelector('.btn-text').textContent = 'Memproses...';
        });

        // Auto rescue: Redirect users back to their allowed entry point after 7 seconds
        // (This redirects to /login, which our Auth logic will automatically bounce
        // back to their allowed dashboard/menu since they are authenticated)
        setTimeout(() => {
            window.location.href = "/login";
        }, 7000);
    </script>
</body>

</html>