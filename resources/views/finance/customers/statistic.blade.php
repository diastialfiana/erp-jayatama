@extends('layouts.app')
@section('title', 'Statistic – Customer Finance')

@push('styles')
<style>
    :root { --blue:#2563EB; --navy:#1E3A8A; --accent:#4F46E5; --bg:#F8FAFC; }

    .page-fade { animation: pageFadeIn .45s ease both; }
    @keyframes pageFadeIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

    .stat-card { transition: transform .25s ease, box-shadow .25s ease; }
    .stat-card:hover { transform: scale(1.015); box-shadow: 0 12px 40px rgba(30,58,138,.13); }

    .icon-wrap {
        width:44px; height:44px; border-radius:12px;
        display:flex; align-items:center; justify-content:center; flex-shrink:0;
    }

    /* stat table */
    .stat-table th { font-size:11px; letter-spacing:.06em; text-transform:uppercase; font-weight:700; color:#64748b; padding:12px 16px; border-bottom:2px solid #e2e8f0; background:#f8fafc; }
    .stat-table td { padding:11px 16px; font-size:13.5px; border-bottom:1px solid #f1f5f9; }
    .stat-table tbody tr:hover td { background:#eff6ff; }
    .stat-table tbody tr.special td { font-weight:700; background:#f0f4ff; color:#1E3A8A; }
    .stat-table tbody tr.special:hover td { background:#e0eaff; }
    .stat-table tbody tr:last-child td { border-bottom:none; }

    .scroll-y { max-height:420px; overflow-y:auto; }
    .scroll-y::-webkit-scrollbar { width:5px; }
    .scroll-y::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:4px; }

    .tab-link { color:#64748b; text-decoration:none; padding-bottom:8px; margin-bottom:-9px; white-space:nowrap; font-size:13.5px; transition:color .2s; }
    .tab-link:hover { color:#2563EB; }
    .tab-link.active { color:#2563EB; font-weight:700; border-bottom:2.5px solid #2563EB; }
</style>
@endpush

@section('content')
<div class="page-fade" style="background:var(--bg); min-height:calc(100vh - 62px); padding:28px 24px;">
<div style="max-width:1340px; margin:0 auto;">

    {{-- BREADCRUMB --}}
    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#94a3b8;margin-bottom:14px;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <a href="{{ route('finance.customers.index') }}" style="color:#94a3b8;text-decoration:none;">Finance</a>
        <span>/</span><a href="{{ route('finance.customers.index') }}" style="color:#94a3b8;text-decoration:none;">Customer List</a>
        <span>/</span><span style="color:#2563EB;font-weight:600;">Statistic</span>
    </div>

    {{-- PAGE HEADER --}}
    <div style="margin-bottom:22px;">
        <h1 style="font-size:26px;font-weight:800;margin:0 0 3px;color:#1e293b;display:flex;align-items:center;gap:10px;">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#1E3A8A,#2563EB);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </span>
            Customer Financial Statistic
        </h1>
        <p style="font-size:13px;color:#64748b;margin:0 0 0 46px;">Monthly balance and down payment overview per customer.</p>
    </div>

    {{-- TAB BAR --}}
    <div style="display:flex;gap:24px;border-bottom:1px solid #e2e8f0;padding-bottom:1px;margin-bottom:22px;overflow-x:auto;">
        <a href="{{ route('finance.customers.detail') }}" class="tab-link">Detail View</a>
        <a href="{{ route('finance.customers.index') }}" class="tab-link">List All</a>
        <a href="{{ route('finance.customers.statistic') }}" class="tab-link active">Statistic</a>
        <a href="{{ route('finance.customers.activity') }}" class="tab-link">Activity</a>
        <a href="{{ route('finance.customers.backdate') }}" class="tab-link">Backdate</a>
        <a href="{{ route('finance.customers.summary') }}" class="tab-link">Summary</a>
    </div>

    {{-- CUSTOMER INFO BAR --}}
    <div style="display:flex;flex-wrap:wrap;align-items:center;gap:0;background:#fff;border-radius:16px;box-shadow:0 2px 12px rgba(30,58,138,.07);border:1px solid #e2e8f0;padding:16px 22px;margin-bottom:24px;">
        <div style="display:flex;flex-direction:column;gap:2px;padding-right:24px;border-right:1px solid #e2e8f0;margin-right:24px;">
            <span style="font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;">Code</span>
            <span style="font-size:15px;font-weight:700;color:#1e293b;">001</span>
        </div>
        <div style="display:flex;flex-direction:column;gap:2px;padding-right:24px;border-right:1px solid #e2e8f0;margin-right:24px;">
            <span style="font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;">Currency</span>
            <span style="font-size:15px;font-weight:700;color:#1e293b;">IDR</span>
        </div>
        <div style="display:flex;flex-direction:column;gap:2px;">
            <span style="font-size:10px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;">Customer Name</span>
            <span style="font-size:15px;font-weight:700;color:#1E3A8A;">PT. JASA SWADAYA UTAMA</span>
        </div>
    </div>

    @php
        $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        $balances = [0,0,12500000,8750000,15200000,9800000,22100000,17500000,11300000,19700000,14200000,25600000];
        $dps      = [0,0,5000000,2500000,6000000,3500000,8000000,6500000,4000000,7500000,5500000,10000000];
        $totalBal = array_sum($balances);
        $totalDp  = array_sum($dps);
    @endphp

    {{-- SUMMARY CARDS --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:18px;margin-bottom:24px;">
        {{-- Balance Card --}}
        <div class="stat-card" style="background:#fff;border-radius:18px;border:1px solid #e2e8f0;padding:20px 22px;box-shadow:0 4px 18px rgba(30,58,138,.06);">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                <div>
                    <p style="font-size:11px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;margin:0 0 4px;">Balance</p>
                    <p style="font-size:11px;color:#64748b;margin:0;">Total Balance</p>
                </div>
                <div class="icon-wrap" style="background:rgba(37,99,235,.1);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                </div>
            </div>
            <p style="font-size:20px;font-weight:800;color:#1e293b;margin:0;">Rp {{ number_format($totalBal) }}</p>
            <div style="height:3px;background:linear-gradient(90deg,#2563EB,#4F46E5);border-radius:2px;margin-top:14px;"></div>
        </div>

        {{-- Down Payment Card --}}
        <div class="stat-card" style="background:#fff;border-radius:18px;border:1px solid #e2e8f0;padding:20px 22px;box-shadow:0 4px 18px rgba(30,58,138,.06);">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                <div>
                    <p style="font-size:11px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;margin:0 0 4px;">Down Payment</p>
                    <p style="font-size:11px;color:#64748b;margin:0;">Total DP</p>
                </div>
                <div class="icon-wrap" style="background:rgba(79,70,229,.1);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2.2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                </div>
            </div>
            <p style="font-size:20px;font-weight:800;color:#1e293b;margin:0;">Rp {{ number_format($totalDp) }}</p>
            <div style="height:3px;background:linear-gradient(90deg,#4F46E5,#818cf8);border-radius:2px;margin-top:14px;"></div>
        </div>

        {{-- Transactions Card --}}
        <div class="stat-card" style="background:#fff;border-radius:18px;border:1px solid #e2e8f0;padding:20px 22px;box-shadow:0 4px 18px rgba(30,58,138,.06);">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                <div>
                    <p style="font-size:11px;font-weight:700;color:#94a3b8;letter-spacing:.07em;text-transform:uppercase;margin:0 0 4px;">Transactions</p>
                    <p style="font-size:11px;color:#64748b;margin:0;">Total Transaction</p>
                </div>
                <div class="icon-wrap" style="background:rgba(30,58,138,.1);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1E3A8A" stroke-width="2.2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
            </div>
            <p style="font-size:20px;font-weight:800;color:#1e293b;margin:0;">{{ count(array_filter($balances)) }}</p>
            <div style="height:3px;background:linear-gradient(90deg,#1E3A8A,#2563EB);border-radius:2px;margin-top:14px;"></div>
        </div>
    </div>

    {{-- CHART + TABLE ROW --}}
    <div style="display:grid;grid-template-columns:1fr 1.5fr;gap:20px;align-items:start;">

        {{-- CHART CARD --}}
        <div style="background:#fff;border-radius:18px;border:1px solid #e2e8f0;padding:22px;box-shadow:0 4px 18px rgba(30,58,138,.06);">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <div>
                    <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0;">Balance Trend</p>
                    <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">Monthly balance movement</p>
                </div>
                <span style="font-size:10px;font-weight:600;color:#2563EB;background:#eff6ff;padding:4px 10px;border-radius:20px;">2026</span>
            </div>
            <canvas id="balanceTrendChart" height="200"></canvas>
        </div>

        {{-- STATISTIC TABLE CARD --}}
        <div style="background:#fff;border-radius:18px;border:1px solid #e2e8f0;overflow:hidden;box-shadow:0 4px 18px rgba(30,58,138,.06);">
            <div style="padding:18px 22px 14px;border-bottom:1px solid #f1f5f9;">
                <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0;">Monthly Balance Detail</p>
                <p style="font-size:11px;color:#94a3b8;margin:3px 0 0;">Balance and DP per month</p>
            </div>
            <div class="scroll-y">
                <table class="stat-table" style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th style="text-align:left;">Month</th>
                            <th style="text-align:right;">Balance</th>
                            <th style="text-align:right;">Balance DP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="special">
                            <td>Current</td>
                            <td style="text-align:right;font-family:monospace;">{{ number_format($balances[count($balances)-1], 2) }}</td>
                            <td style="text-align:right;font-family:monospace;">{{ number_format($dps[count($dps)-1], 2) }}</td>
                        </tr>
                        <tr class="special">
                            <td>Beg Balance</td>
                            <td style="text-align:right;font-family:monospace;">0.00</td>
                            <td style="text-align:right;font-family:monospace;">0.00</td>
                        </tr>
                        @foreach($months as $i => $month)
                        <tr>
                            <td>{{ $month }}</td>
                            <td style="text-align:right;font-family:monospace;">{{ number_format($balances[$i], 2) }}</td>
                            <td style="text-align:right;font-family:monospace;">{{ number_format($dps[$i], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
(function(){
    const labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const balData = @json($balances);
    const dpData  = @json($dps);

    const ctx = document.getElementById('balanceTrendChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0,0,0,200);
    gradient.addColorStop(0,'rgba(37,99,235,.25)');
    gradient.addColorStop(1,'rgba(37,99,235,.0)');

    new Chart(ctx, {
        type:'line',
        data:{
            labels,
            datasets:[
                {
                    label:'Balance',
                    data: balData,
                    borderColor:'#2563EB',
                    backgroundColor: gradient,
                    fill: true,
                    tension:.4,
                    borderWidth:2.5,
                    pointRadius:4,
                    pointBackgroundColor:'#fff',
                    pointBorderColor:'#2563EB',
                    pointBorderWidth:2,
                },
                {
                    label:'Balance DP',
                    data: dpData,
                    borderColor:'#4F46E5',
                    backgroundColor:'transparent',
                    fill:false,
                    tension:.4,
                    borderWidth:2,
                    borderDash:[5,4],
                    pointRadius:3,
                    pointBackgroundColor:'#4F46E5',
                }
            ]
        },
        options:{
            responsive:true,
            plugins:{
                legend:{ position:'bottom', labels:{ boxWidth:10, font:{size:11}, padding:14 } },
                tooltip:{ callbacks:{ label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID') } }
            },
            scales:{
                x:{ grid:{ display:false }, ticks:{ font:{size:10} } },
                y:{
                    grid:{ color:'#f1f5f9' },
                    ticks:{ font:{size:10}, callback: v => v >= 1e6 ? 'Rp '+(v/1e6).toFixed(0)+'M' : v }
                }
            }
        }
    });
})();
</script>
@endpush
@endsection