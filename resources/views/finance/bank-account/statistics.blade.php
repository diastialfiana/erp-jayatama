@extends('layouts.app')
@section('title', 'Bank Account – Statistics')

@push('styles')
<style>
    :root { --navy:#1E3A8A; --blue:#2563EB; --accent:#4F46E5; --bg:#F8FAFC; }

    .page-fade { animation: pgIn .4s ease both; }
    @keyframes pgIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

    .tab-link { color:#64748b;text-decoration:none;padding-bottom:8px;margin-bottom:-9px;white-space:nowrap;font-size:13.5px;transition:color .2s; }
    .tab-link:hover { color:#2563EB; }
    .tab-link.active { color:#2563EB;font-weight:700;border-bottom:2.5px solid #2563EB; }

    /* Bank info bar */
    .info-field {
        display:flex;flex-direction:column;gap:2px;
        padding-right:22px;border-right:1px solid #e2e8f0;margin-right:22px;
    }
    .info-field:last-child { border-right:none;padding-right:0;margin-right:0; }
    .info-label { font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase; }
    .info-value { font-size:15px;font-weight:700;color:#1e293b; }

    /* Year filter */
    .year-input {
        display:flex;align-items:center;gap:6px;background:#fff;border:1px solid #e2e8f0;
        border-radius:9px;padding:6px 12px;transition:box-shadow .2s;
    }
    .year-input:focus-within { box-shadow:0 0 0 3px rgba(37,99,235,.12);border-color:#2563EB; }
    .year-input input { border:none;outline:none;font-size:13px;font-weight:600;color:#1e293b;width:64px;background:transparent; }
    .btn-go { background:linear-gradient(135deg,#1E3A8A,#2563EB);color:#fff;font-size:12px;font-weight:600;border:none;border-radius:8px;padding:7px 16px;cursor:pointer;transition:opacity .2s; }
    .btn-go:hover { opacity:.88; }

    /* Summary KPI cards */
    .kpi-card { background:#fff;border-radius:16px;border:1px solid #e2e8f0;padding:18px 22px;box-shadow:0 4px 16px rgba(30,58,138,.06);transition:transform .25s,box-shadow .25s; }
    .kpi-card:hover { transform:scale(1.015);box-shadow:0 12px 36px rgba(30,58,138,.12); }
    .kpi-bar { height:3px;border-radius:2px;margin-top:12px; }

    /* Statistics table */
    .stat-table { width:100%;border-collapse:collapse; }
    .stat-table th {
        font-size:10px;font-weight:700;color:#64748b;letter-spacing:.07em;
        text-transform:uppercase;padding:10px 14px;border-bottom:2px solid #e2e8f0;
        background:#f8fafc;text-align:left;
    }
    .stat-table th.num { text-align:right; }
    .stat-table td { padding:9px 14px;font-size:13px;border-bottom:1px solid #f8fafc;vertical-align:middle; }
    .stat-table tr:hover td { background:#f8fafc; }
    .stat-table tr.special-row td { background:#f0f4ff;font-weight:700; }
    .stat-table tr.special-row:hover td { background:#e8eeff; }

    .mono { font-family:'Courier New',monospace; }
    .zero { color:#cbd5e1; }
    .nonzero { color:#1e293b;font-weight:600; }
    .row-label { font-size:11px;font-weight:700;color:#64748b;letter-spacing:.06em;text-transform:uppercase; }
</style>
@endpush

@section('content')

@php
    /* Chart labels and data – filter out zero months for chart clarity */
    $chartLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    $chartData   = array_values($monthlyBalances);  // 12 values
@endphp

<div class="page-fade" style="background:var(--bg);min-height:calc(100vh - 62px);padding:28px 24px;">
<div style="max-width:1200px;margin:0 auto;">

    {{-- BREADCRUMB --}}
    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#94a3b8;margin-bottom:14px;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <a href="{{ route('finance.index') }}" style="color:#94a3b8;text-decoration:none;">Finance</a>
        <span>/</span>
        <a href="{{ route('finance.bank-accounts.index') }}" style="color:#94a3b8;text-decoration:none;">Bank Account</a>
        <span>/</span>
        <span style="color:#2563EB;font-weight:600;">Statistics</span>
    </div>

    {{-- PAGE HEADER --}}
    <div style="margin-bottom:18px;">
        <h1 style="font-size:26px;font-weight:800;margin:0 0 3px;color:#1e293b;display:flex;align-items:center;gap:10px;">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#1E3A8A,#2563EB);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            </span>
            Bank Account Statistics
        </h1>
        <p style="font-size:13px;color:#64748b;margin:0 0 0 46px;">Monthly balance breakdown for the selected bank account and year.</p>
    </div>

    {{-- TAB BAR --}}
    <div style="display:flex;gap:24px;border-bottom:1px solid #e2e8f0;padding-bottom:1px;margin-bottom:22px;overflow-x:auto;">
        <a href="{{ route('finance.bank-accounts.record-detail') }}" class="tab-link">Record Detail</a>
        <a href="{{ route('finance.bank-accounts.records-list') }}" class="tab-link">Records List</a>
        <a href="{{ route('finance.bank-accounts.statistics') }}" class="tab-link active">Statistics</a>
        <a href="{{ route('finance.bank-accounts.activity') }}" class="tab-link">Activity</a>
        <a href="{{ route('finance.bank-accounts.backdate') }}" class="tab-link">Backdate</a>
        <a href="{{ route('finance.bank-accounts.summary') }}" class="tab-link">Summary</a>
    </div>

    {{-- BANK INFO BAR + YEAR FILTER --}}
    <div style="background:#fff;border-radius:16px;border:1px solid #e2e8f0;padding:16px 22px;margin-bottom:20px;box-shadow:0 2px 12px rgba(30,58,138,.06);display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:16px;">
        {{-- Bank Info --}}
        <div style="display:flex;flex-wrap:wrap;align-items:center;">
            <div class="info-field">
                <span class="info-label">Code</span>
                <span class="info-value">{{ $bank?->code ?? '00001' }}</span>
            </div>
            <div class="info-field">
                <span class="info-label">Currency</span>
                <span class="info-value" style="color:#2563EB;">{{ $bank?->currency ?? 'IDR' }}</span>
            </div>
            <div class="info-field">
                <span class="info-label">Bank Name</span>
                <span class="info-value">{{ $bank?->bank_name ?? 'BANK MEGA CAB. TENDEAN' }}</span>
            </div>
        </div>

        {{-- Year Filter --}}
        <form method="GET" action="{{ route('finance.bank-accounts.statistics') }}" style="display:flex;align-items:center;gap:10px;">
            <label style="font-size:11px;font-weight:700;color:#64748b;letter-spacing:.07em;text-transform:uppercase;">Year</label>
            <div class="year-input">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <input type="number" name="year" value="{{ $year }}" min="2000" max="2099">
            </div>
            <button type="submit" class="btn-go">Apply</button>
        </form>
    </div>

    {{-- KPI CARDS --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:22px;">

        {{-- Current Balance --}}
        <div class="kpi-card">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px;">
                <div>
                    <p style="font-size:10.5px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;margin:0 0 3px;">Current Balance</p>
                    <p style="font-size:11px;color:#64748b;margin:0;">As of today</p>
                </div>
                <div style="width:40px;height:40px;border-radius:11px;background:linear-gradient(135deg,#1E3A8A,#2563EB);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                </div>
            </div>
            <p class="mono" style="font-size:20px;font-weight:800;color:#1E3A8A;margin:0;">{{ number_format($currentBalance, 2, '.', ',') }}</p>
            <div class="kpi-bar" style="background:linear-gradient(90deg,#1E3A8A,#2563EB);"></div>
        </div>

        {{-- Beginning Balance --}}
        <div class="kpi-card">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px;">
                <div>
                    <p style="font-size:10.5px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;margin:0 0 3px;">Beginning Balance</p>
                    <p style="font-size:11px;color:#64748b;margin:0;">Start of {{ $year }}</p>
                </div>
                <div style="width:40px;height:40px;border-radius:11px;background:rgba(37,99,235,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                </div>
            </div>
            <p class="mono" style="font-size:20px;font-weight:800;color:#1e293b;margin:0;">{{ number_format($beginningBalance, 2, '.', ',') }}</p>
            <div class="kpi-bar" style="background:linear-gradient(90deg,#2563EB,#4F46E5);"></div>
        </div>

        {{-- Net Change --}}
        @php $netChange = $currentBalance - $beginningBalance; @endphp
        <div class="kpi-card">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px;">
                <div>
                    <p style="font-size:10.5px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;margin:0 0 3px;">Net Change</p>
                    <p style="font-size:11px;color:#64748b;margin:0;">Current − Beginning</p>
                </div>
                <div style="width:40px;height:40px;border-radius:11px;background:{{ $netChange >= 0 ? 'rgba(22,163,74,.1)' : 'rgba(220,38,38,.08)' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="{{ $netChange >= 0 ? '#16a34a' : '#dc2626' }}" stroke-width="2.2">
                        @if($netChange >= 0)
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>
                        @else
                            <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/>
                        @endif
                    </svg>
                </div>
            </div>
            <p class="mono" style="font-size:20px;font-weight:800;color:{{ $netChange >= 0 ? '#16a34a' : '#dc2626' }};margin:0;">
                {{ ($netChange >= 0 ? '+' : '') . number_format($netChange, 2, '.', ',') }}
            </p>
            <div class="kpi-bar" style="background:linear-gradient(90deg,{{ $netChange >= 0 ? '#16a34a,#4ade80' : '#dc2626,#f87171' }});"></div>
        </div>
    </div>

    {{-- LAYOUT: Chart + Table side by side --}}
    <div style="display:grid;grid-template-columns:1fr 1.15fr;gap:20px;align-items:start;">

        {{-- CHART --}}
        <div style="background:#fff;border-radius:18px;border:1px solid #e2e8f0;padding:22px;box-shadow:0 4px 18px rgba(30,58,138,.06);">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <div>
                    <p style="font-size:13.5px;font-weight:700;color:#1e293b;margin:0;">Balance Trend {{ $year }}</p>
                    <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">Monthly movement</p>
                </div>
            </div>
            <canvas id="balanceChart" height="220"></canvas>
        </div>

        {{-- MONTHLY TABLE --}}
        <div style="background:#fff;border-radius:18px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 4px 18px rgba(30,58,138,.06);">
            <div style="padding:14px 18px;border-bottom:1px solid #f1f5f9;">
                <p style="font-size:13.5px;font-weight:700;color:#1e293b;margin:0;">Monthly Statistics</p>
                <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">Balance & Audit Balance per month</p>
            </div>

            <div style="overflow-y:auto;max-height:480px;">
                <table class="stat-table">
                    <thead>
                        <tr>
                            <th style="min-width:140px;">Period</th>
                            <th class="num">Balance</th>
                            <th class="num">[Balance]</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Current & Beginning --}}
                        <tr class="special-row">
                            <td><span class="row-label">Current Balance</span></td>
                            <td class="mono" style="text-align:right;color:#1E3A8A;">{{ number_format($currentBalance, 2, '.', ',') }}</td>
                            <td class="mono" style="text-align:right;color:#64748b;">{{ number_format($currentBalance, 2, '.', ',') }}</td>
                        </tr>
                        <tr class="special-row">
                            <td><span class="row-label">Beginning Balance</span></td>
                            <td class="mono" style="text-align:right;color:#1E3A8A;">{{ number_format($beginningBalance, 2, '.', ',') }}</td>
                            <td class="mono" style="text-align:right;color:#64748b;">{{ number_format($beginningBalance, 2, '.', ',') }}</td>
                        </tr>

                        {{-- January → December --}}
                        @foreach($months as $idx => $month)
                        @php
                            $num = $idx + 1;
                            $val = $monthlyBalances[$num] ?? 0;
                            $isZero = $val == 0;
                        @endphp
                        <tr>
                            <td><span class="row-label">{{ $month }}</span></td>
                            <td class="mono {{ $isZero ? 'zero' : 'nonzero' }}" style="text-align:right;">
                                {{ number_format($val, 2, '.', ',') }}
                            </td>
                            <td class="mono {{ $isZero ? 'zero' : 'nonzero' }}" style="text-align:right;color:#64748b;">
                                {{ number_format($val, 2, '.', ',') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#f0f4ff;">
                            <td style="padding:10px 14px;font-size:10.5px;font-weight:700;color:#1E3A8A;letter-spacing:.06em;text-transform:uppercase;">TOTAL YTD</td>
                            @php $ytd = array_sum($monthlyBalances); @endphp
                            <td class="mono" style="padding:10px 14px;text-align:right;font-weight:700;color:#1E3A8A;">{{ number_format($ytd, 2, '.', ',') }}</td>
                            <td class="mono" style="padding:10px 14px;text-align:right;font-weight:700;color:#64748b;">{{ number_format($ytd, 2, '.', ',') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
(function() {
    const labels  = @json($chartLabels);
    const data    = @json($chartData);
    const ctx     = document.getElementById('balanceChart').getContext('2d');

    const grad = ctx.createLinearGradient(0, 0, 0, 220);
    grad.addColorStop(0, 'rgba(37,99,235,.25)');
    grad.addColorStop(1, 'rgba(37,99,235,.0)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Balance',
                    data,
                    backgroundColor: data.map(v => v > 0 ? 'rgba(37,99,235,.75)' : 'rgba(37,99,235,.15)'),
                    borderRadius: 6,
                    borderSkipped: false,
                },
                {
                    type: 'line',
                    label: 'Trend',
                    data,
                    borderColor: '#1E3A8A',
                    borderWidth: 2,
                    pointBackgroundColor: '#2563EB',
                    pointRadius: data.map(v => v > 0 ? 4 : 2),
                    tension: 0.4,
                    fill: true,
                    backgroundColor: grad,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 9, font: { size: 10 }, padding: 12 } },
                tooltip: {
                    callbacks: {
                        label: ctx => ' ' + Number(ctx.parsed.y).toLocaleString('en-US', { minimumFractionDigits: 2 })
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                y: {
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        font: { size: 9 },
                        callback: v => v >= 1e6 ? (v/1e6).toFixed(1)+'M' : v >= 1e3 ? (v/1e3).toFixed(0)+'K' : v
                    }
                }
            }
        }
    });
})();
</script>
@endsection
