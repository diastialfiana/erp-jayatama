<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\CostCenter;
use Illuminate\Http\Request;

class CostCenterController extends Controller
{
    public function detail(Request $request, $id = null)
    {
        $cost = null;
        $firstId = null;
        $lastId = null;
        $prevId = null;
        $nextId = null;
        $count = CostCenter::count();
        $position = 0;

        if ($count > 0) {
            $firstId = CostCenter::orderBy('id', 'asc')->value('id');
            $lastId = CostCenter::orderBy('id', 'desc')->value('id');

            if ($id) {
                $cost = CostCenter::findOrFail($id);
                $prevId = CostCenter::where('id', '<', $id)->max('id');
                $nextId = CostCenter::where('id', '>', $id)->min('id');
                $position = CostCenter::where('id', '<=', $id)->count();
            } else {
                $position = 0; 
            }
        }

        return view('accounting.cost-center.detail', compact(
            'cost', 'firstId', 'lastId', 'prevId', 'nextId', 'count', 'position'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:cost_centers,code',
            'description' => 'nullable|string',
        ]);

        $cost = CostCenter::create($validated);

        return redirect()->route('accounting.cost-center.detail', ['id' => $cost->id])
                         ->with('success', 'Cost Center saved.');
    }

    public function update(Request $request, $id)
    {
        $cost = CostCenter::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|unique:cost_centers,code,' . $cost->id,
            'description' => 'nullable|string',
        ]);

        $cost->update($validated);

        return redirect()->route('accounting.cost-center.detail', ['id' => $cost->id])
                         ->with('success', 'Cost Center updated.');
    }

    public function list()
    {
        $costs = CostCenter::orderBy('code', 'asc')->get();
        return view('accounting.cost-center.list', compact('costs'));
    }
    
    public function statistic()
    {
        $costs = CostCenter::orderBy('code', 'asc')->get();

        $transactions = \App\Models\AccountTransaction::selectRaw('cost_center_id, MONTH(date) as month, SUM(debit - credit) as total')
            ->whereNotNull('cost_center_id')
            ->whereYear('date', now()->year)
            ->groupBy('cost_center_id', 'month')
            ->get();

        // Map data
        $statData = [];
        $monthlyTotals = array_fill(1, 12, 0);

        foreach ($costs as $cost) {
            $monthData = array_fill(1, 12, 0);
            
            foreach ($transactions as $tx) {
                if ($tx->cost_center_id == $cost->id) {
                    $monthData[$tx->month] = (float) $tx->total;
                    $monthlyTotals[$tx->month] += (float) $tx->total;
                }
            }

            $statData[] = [
                'id' => $cost->id,
                'code' => $cost->code,
                'description' => $cost->description,
                'months' => $monthData
            ];
        }

        return view('accounting.cost-center.statistic', compact('statData', 'monthlyTotals'));
    }
}
