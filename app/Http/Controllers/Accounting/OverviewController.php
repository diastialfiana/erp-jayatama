<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance\BankAccount;
use App\Models\Accounting\JournalEntry;

use App\Models\Account;

class OverviewController extends Controller
{
    public function index()
    {
        $stats = [
            'total_accounts' => Account::count(),
            'journal_count' => 0,
            'pending_posting' => 0
        ];

        return view('accounting.index', compact('stats'));
    }
}
