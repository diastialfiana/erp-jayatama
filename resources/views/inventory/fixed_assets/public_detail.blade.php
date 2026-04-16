<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assets Detail - {{ $code }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
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
        }
        header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .logo-container {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .logo-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .company-info {
            font-size: 0.75rem;
            line-height: 1.2;
        }
        .company-name {
            font-weight: 800;
            font-size: 0.9rem;
            margin-bottom: 2px;
        }

        .content {
            padding: 30px 20px;
            flex: 1;
        }

        h1 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: -1px;
        }

        .detail-item {
            margin-bottom: 25px;
        }

        .label {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .value-box {
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 4px;
            font-size: 1.1rem;
            font-weight: 600;
            color: #444;
            min-height: 60px;
            display: flex;
            align-items: center;
        }

        .section-title {
            font-size: 2.2rem;
            font-weight: 800;
            margin-top: 40px;
            margin-bottom: 30px;
            text-transform: uppercase;
        }

        footer {
            background-color: #222;
            color: #fff;
            padding: 40px 20px;
            font-size: 0.9rem;
        }
        .copyright {
            color: #eee;
            margin-bottom: 5px;
        }
        .copyright span {
            color: #2bd48d;
            font-weight: 700;
        }
        .design-by {
            color: #aaa;
        }
        .design-by span {
            color: #2bd48d;
            font-weight: 700;
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
            <div class="value-box" style="align-items: flex-start; min-height: 120px;">{{ $asset['note'] ?: '-' }}</div>
        </div>
    </div>

    <footer>
        <div class="copyright">&copy; <span>JAYATAMA</span>, Inc. All rights Resevered.</div>
        <div class="design-by">Design by <span>IBP Software House</span></div>
    </footer>
</body>
</html>
