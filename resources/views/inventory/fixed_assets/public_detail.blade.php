<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assets Detail - {{ $code }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            color: #333;
            max-width: 500px;
            margin: 0 auto;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            border-left: 1px solid #f0f0f0;
            border-right: 1px solid #f0f0f0;
        }
        header {
            padding: 15px 20px;
            border-bottom: 1px solid #eeeeee;
            display: flex;
            align-items: center;
            gap: 15px;
            background-color: #fff;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .logo-container {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .company-info {
            font-size: 11px;
            line-height: 1.3;
            color: #1a1a1a;
        }
        .company-name {
            font-weight: 900;
            font-size: 14px;
            margin-bottom: 1px;
        }

        .content {
            padding: 25px 20px;
            flex: 1;
        }

        h1 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: -0.5px;
            color: #000;
        }

        .detail-item {
            margin-bottom: 20px;
        }

        .label {
            font-size: 16px;
            color: #666;
            margin-bottom: 8px;
            font-weight: 400;
        }

        .value-box {
            background-color: #f4f4f4;
            padding: 15px 20px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 400;
            color: #333;
            min-height: 50px;
            display: flex;
            align-items: center;
            border: 1px solid #eaeaea;
        }

        .section-title {
            font-size: 20px;
            font-weight: 800;
            margin-top: 35px;
            margin-bottom: 20px;
            text-transform: uppercase;
            color: #000;
        }

        footer {
            background-color: #1a1a1a;
            color: #fff;
            padding: 40px 20px;
            font-size: 14px;
        }
        .copyright {
            color: #fff;
            margin-bottom: 8px;
            font-weight: 400;
        }
        .copyright span {
            color: #fff;
            font-weight: 800;
        }
        .design-by {
            color: #888;
            font-size: 13px;
        }
        .design-by span {
            color: #888;
            font-weight: 700;
        }

        @media (max-width: 400px) {
            h1 { font-size: 26px; }
            .label, .value-box { font-size: 15px; }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="{{ asset('images/JSU.jpg') }}" alt="Jayatama Logo">
        </div>
        <div class="company-info">
            <div class="company-name">PT. JASA SWADAYA UTAMA</div>
            <div>Menara Bank Mega Ground Floor</div>
            <div>Jl. Kapt. Tendean Kav. 12 No. 14A,</div>
            <div>Mampang Jakarta Selatan</div>
        </div>
    </header>

    <div class="content">
        <h1>ASSETS DETAIL</h1>

        <div class="detail-item">
            <div class="label">Assets Code</div>
            <div class="value-box">{{ $code }}</div>
        </div>

        <div class="detail-item">
            <div class="label">Brand Description</div>
            <div class="value-box">{{ $asset['brand'] ?: '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="label">Assets Name</div>
            <div class="value-box">{{ strtoupper($asset['name']) }}</div>
        </div>

        <div class="detail-item">
            <div class="label">Type Description</div>
            <div class="value-box">{{ $asset['type'] ?: '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="label">Serial Number</div>
            <div class="value-box">{{ $asset['serial'] ?: '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="label">Assets Category</div>
            <div class="value-box">{{ $asset['category'] ?: '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="label">Location Assets</div>
            <div class="value-box">{{ $asset['location'] ?: '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="label">Register Date</div>
            <div class="value-box">{{ $asset['initial_date'] ? \Carbon\Carbon::parse($asset['initial_date'])->format('F d, Y') : '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="label">P.I.C Name</div>
            <div class="value-box">{{ strtoupper($asset['asset_user']) ?: '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="label">Valid Guaranty</div>
            <div class="value-box">{{ $asset['valid_guaranty'] ? \Carbon\Carbon::parse($asset['valid_guaranty'])->format('n/j/Y g:i:s A') : '-' }}</div>
        </div>

        <div class="detail-item">
            <div class="label">Useful Live (In Year)</div>
            <div class="value-box">{{ is_numeric($asset['useful_life']) ? round($asset['useful_life'] / 12, 1) : '-' }}</div>
        </div>

        <div class="section-title">NOTES</div>

        <div class="detail-item">
            <div class="value-box" style="align-items: flex-start; min-height: 150px; line-height: 1.4;">{{ $asset['note'] ?: '-' }}</div>
        </div>
    </div>

    <footer>
        <div class="copyright">&copy; <span>JAYATAMA</span>, Inc. All rights Reserved.</div>
        <div class="design-by">Design by <span>IBP Software House</span></div>
    </footer>
</body>
</html>
