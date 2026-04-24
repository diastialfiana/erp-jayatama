<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – JES Enterprise System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0a0f1e;
            overflow: hidden;
            position: relative;
        }

        /* ── Animated Background ── */
        .bg-canvas {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #060d20 0%, #0f1b3d 30%, #1E3A8A 65%, #2d1b69 100%);
            background-size: 300% 300%;
            animation: bgPan 18s ease infinite;
            z-index: 0;
        }

        @keyframes bgPan {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Grid overlay */
        .bg-grid {
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.025) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* Glow orbs */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.22;
            animation: drift linear infinite;
            z-index: 0;
        }

        .orb-1 {
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, #4F46E5, transparent);
            top: -200px;
            left: -150px;
            animation-duration: 20s;
        }

        .orb-2 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, #2563EB, transparent);
            bottom: -180px;
            right: -150px;
            animation-duration: 25s;
            animation-direction: reverse;
        }

        .orb-3 {
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, #7c3aed, transparent);
            top: 40%;
            right: 20%;
            animation-duration: 17s;
        }

        @keyframes drift {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg);
            }

            33% {
                transform: translate(30px, -30px) rotate(120deg);
            }

            66% {
                transform: translate(-20px, 20px) rotate(240deg);
            }
        }

        /* ── Card ── */
        .page-wrap {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 20px;
            animation: fadeSlideUp 0.65s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(36px) scale(0.97);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .card {
            background: rgba(255, 255, 255, 0.055);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 48px 44px;
            box-shadow:
                0 0 0 1px rgba(255, 255, 255, 0.04) inset,
                0 32px 80px rgba(0, 0, 0, 0.5),
                0 8px 24px rgba(79, 70, 229, 0.15);
        }

        /* Logo */
        .logo-area {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 68px;
            height: 68px;
            background: linear-gradient(135deg, #1d4ed8, #4F46E5);
            border-radius: 20px;
            margin-bottom: 18px;
            box-shadow: 0 8px 28px rgba(79, 70, 229, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.08) inset;
            animation: badgePulse 3s ease-in-out infinite;
        }

        @keyframes badgePulse {

            0%,
            100% {
                box-shadow: 0 8px 28px rgba(79, 70, 229, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.08) inset;
            }

            50% {
                box-shadow: 0 12px 36px rgba(79, 70, 229, 0.75), 0 0 0 1px rgba(255, 255, 255, 0.1) inset;
            }
        }

        .logo-badge svg {
            width: 34px;
            height: 34px;
            fill: none;
            stroke: white;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .brand-jes {
            font-size: 2rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.04em;
            line-height: 1;
        }

        .brand-full {
            font-size: 0.72rem;
            color: rgba(255, 255, 255, 0.4);
            letter-spacing: 0.14em;
            text-transform: uppercase;
            margin-top: 5px;
        }

        /* Error */
        .error-box {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 24px;
            animation: shake 0.5s ease both;
        }

        @keyframes shake {

            10%,
            90% {
                transform: translateX(-3px);
            }

            20%,
            80% {
                transform: translateX(5px);
            }

            30%,
            50%,
            70% {
                transform: translateX(-7px);
            }

            40%,
            60% {
                transform: translateX(7px);
            }
        }

        .error-box p {
            font-size: 0.82rem;
            color: #fca5a5;
            margin: 0;
        }

        /* Fields */
        .field {
            margin-bottom: 20px;
        }

        .field-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.45);
            margin-bottom: 9px;
        }

        .input-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.3);
            transition: color 0.2s;
            pointer-events: none;
        }

        .field-icon svg {
            width: 17px;
            height: 17px;
            display: block;
        }

        .field-input {
            width: 100%;
            padding: 14px 14px 14px 46px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: #fff;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: all 0.25s;
        }

        .field-input::placeholder {
            color: rgba(255, 255, 255, 0.22);
        }

        .field-input:focus {
            background: rgba(255, 255, 255, 0.11);
            border-color: #4F46E5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.22);
        }

        .input-wrap:focus-within .field-icon {
            color: #818cf8;
        }

        /* Remember */
        .remember {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 28px;
        }

        .remember input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: #4F46E5;
            cursor: pointer;
        }

        .remember label {
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.45);
            cursor: pointer;
        }

        /* Button */
        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #2563EB 0%, #4F46E5 50%, #7c3aed 100%);
            background-size: 200% 200%;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 0.92rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            letter-spacing: 0.03em;
            box-shadow: 0 6px 24px rgba(79, 70, 229, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 36px rgba(79, 70, 229, 0.6);
            background-position: right center;
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Ripple */
        .btn-submit::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }

        .btn-submit:hover::after {
            transform: translateX(100%);
        }

        /* Footer */
        .page-footer {
            text-align: center;
            margin-top: 26px;
            font-size: 0.72rem;
            color: rgba(255, 255, 255, 0.2);
        }

        @media (max-width: 480px) {
            .card {
                padding: 36px 26px;
            }
        }
    </style>
</head>

<body>
    <div class="bg-canvas"></div>
    <div class="bg-grid"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="page-wrap">
        <div class="card">
            <div class="logo-area">
                <div class="logo-badge">
                    <svg viewBox="0 0 24 24">
                        <polygon points="12 2 2 7 12 12 22 7 12 2" />
                        <polyline points="2 17 12 22 22 17" />
                        <polyline points="2 12 12 17 22 12" />
                    </svg>
                </div>
                <div class="brand-jes">JES</div>
                <div class="brand-full">Jayatama Enterprise System</div>
            </div>

            @if($errors->any())
                <div class="error-box">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf
                <div class="field">
                    <label class="field-label" for="name">Nama Pegawai</label>
                    <div class="input-wrap">
                        <span class="field-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </span>
                        <input type="text" name="name" id="name" class="field-input" placeholder="Masukkan nama lengkap"
                            value="{{ old('name') }}" autocomplete="off" list="user-list" autofocus required>
                        <datalist id="user-list">
                            @if(isset($users))
                                @foreach($users as $user)
                                    <option value="{{ $user->name }} ({{ $user->username }})"></option>
                                @endforeach
                            @endif
                        </datalist>
                    </div>
                </div>

                <div class="field">
                    <label class="field-label" for="password">Password</label>
                    <div class="input-wrap">
                        <span class="field-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                        </span>
                        <input type="password" name="password" id="password" class="field-input"
                            placeholder="Masukkan password" autocomplete="current-password" required>
                    </div>
                </div>

                <div class="remember">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Ingat saya di perangkat ini</label>
                </div>

                <button type="submit" class="btn-submit" id="loginBtn">
                    Masuk ke Sistem
                </button>
            </form>
        </div>
        <div class="page-footer">© 2026 Jayatama Enterprise System &mdash; All Rights Reserved</div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function () {
            const btn = document.getElementById('loginBtn');
            btn.innerHTML = '<svg style="display:inline;vertical-align:middle;margin-right:8px;animation:spin 0.8s linear infinite" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-dasharray="60" stroke-dashoffset="15" opacity="0.4"/><path d="M12 2a10 10 0 0 1 10 10"/></svg>Memproses...';
            btn.style.opacity = '0.85';
        });
        const style = document.createElement('style');
        style.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
        document.head.appendChild(style);
    </script>
</body>

</html>