<x-layout>
    <div class="erp-container">
        <div class="erp-header">
            <h2>Overview Finance</h2>
        </div>
        <div class="erp-body" style="padding: 20px; background: #f8fafc;">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
                <div class="erp-box" style="padding: 20px; border-left: 4px solid #2563eb; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <p style="color: #64748b; font-size: 0.85rem; text-transform: uppercase; font-weight: 700; margin-bottom: 5px;">Total Bank Balance</p>
                    <h3 style="font-size: 1.8rem; margin: 0; color: #020617;">Rp {{ number_format($totalBankBalance, 2) }}</h3>
                </div>
                <div class="erp-box" style="padding: 20px; border-left: 4px solid #10b981; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <p style="color: #64748b; font-size: 0.85rem; text-transform: uppercase; font-weight: 700; margin-bottom: 5px;">Total Cash In & Receipts</p>
                    <h3 style="font-size: 1.8rem; margin: 0; color: #020617;">Rp {{ number_format($totalUangMasuk, 2) }}</h3>
                </div>
                <div class="erp-box" style="padding: 20px; border-left: 4px solid #ef4444; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <p style="color: #64748b; font-size: 0.85rem; text-transform: uppercase; font-weight: 700; margin-bottom: 5px;">Total Cash Out</p>
                    <h3 style="font-size: 1.8rem; margin: 0; color: #020617;">Rp {{ number_format($totalCashOut, 2) }}</h3>
                </div>
            </div>

            <div class="group-bar" style="margin-bottom: 12px;">
                <h4 style="margin: 0; color: #0f172a;">Bank Accounts Live Status</h4>
            </div>
            
            <div class="erp-box" style="padding: 0; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <table class="erp-table">
                    <thead>
                        <tr>
                            <th style="padding: 12px 16px;">Kode</th>
                            <th style="padding: 12px 16px;">Nama Bank</th>
                            <th style="padding: 12px 16px;">No. Rekening</th>
                            <th style="padding: 12px 16px; text-align: center;">Mata Uang</th>
                            <th style="padding: 12px 16px; text-align: right;">Saldo Aktual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banks as $b)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 12px 16px;">{{ $b->code }}</td>
                            <td style="padding: 12px 16px;">{{ $b->bank_name }}</td>
                            <td style="padding: 12px 16px;">{{ $b->bank_account }}</td>
                            <td style="padding: 12px 16px; text-align: center;">{{ $b->currency }}</td>
                            <td style="padding: 12px 16px; text-align: right; font-weight: 600; color: {{ $b->balance < 0 ? '#ef4444' : '#10b981' }}">
                                {{ number_format($b->balance, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="padding: 24px; text-align: center; color: #94a3b8;">Tidak ada data bank account yang tersedia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
