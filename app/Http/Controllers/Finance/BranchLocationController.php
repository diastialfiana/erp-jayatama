<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\BranchLocation;
use App\Models\Account;
use App\Models\Administration\CostCenter;
use App\Models\Administration\Department;
use Illuminate\Http\Request;

class BranchLocationController extends Controller
{
    /**
     * Display the list of branch locations (Record List).
     */
    public function recordsList(Request $request)
    {
        $search = $request->get('search');
        
        $query = BranchLocation::query();
        
        if ($search) {
            $query->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
        }
        
        $branchLocations = $query->orderBy('id', 'desc')->paginate(20);
        
        return view('finance.branch-location.records-list', compact('branchLocations', 'search'));
    }

    /**
     * Display the detail form for creating or editing.
     */
    public function detail($id = null)
    {
        $branchLocation = $id ? BranchLocation::findOrFail($id) : new BranchLocation();

        // Get adjacent record IDs for navigation
        $firstId = BranchLocation::orderBy('id', 'asc')->value('id');
        $lastId = BranchLocation::orderBy('id', 'desc')->value('id');
        
        $prevId = null;
        $nextId = null;

        if ($branchLocation->exists) {
            $prevId = BranchLocation::where('id', '<', $branchLocation->id)->orderBy('id', 'desc')->value('id');
            $nextId = BranchLocation::where('id', '>', $branchLocation->id)->orderBy('id', 'asc')->value('id');
        } else {
            // When creating new, prev is the last record
            $prevId = $lastId;
        }

        return view('finance.branch-location.detail', compact(
            'branchLocation',
            'firstId',
            'lastId',
            'prevId',
            'nextId'
        ));
    }

    /**
     * Store or Update record.
     */
    public function store(Request $request, $id = null)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:branch_locations,code,' . $id,
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'inventory_account_id' => 'nullable|exists:accounts,id',
            'cogs_account_id' => 'nullable|exists:accounts,id',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $branchLocation = $id ? BranchLocation::findOrFail($id) : new BranchLocation();
        $branchLocation->fill($request->all());
        $branchLocation->save();

        return response()->json([
            'success' => true,
            'message' => 'Branch Location saved successfully.',
            'data' => $branchLocation,
            'redirect' => route('finance.branch-locations.detail', $branchLocation->id)
        ]);
    }

    /**
     * API Endpoints for Select2
     */
    public function apiAccounts(Request $request)
    {
        $search = $request->get('q');
        $query = Account::select('id', 'code', 'name');
        
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
        }
        
        $accounts = $query->limit(50)->get()->map(function($item) {
            return [
                'id' => $item->id,
                'text' => $item->code . ' - ' . $item->name
            ];
        });
        
        return response()->json($accounts);
    }

    public function apiCostCenters(Request $request)
    {
        $search = $request->get('q');
        $query = CostCenter::select('id', 'code', 'description');
        
        if ($search) {
            $query->where('description', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
        }
        
        $costs = $query->limit(50)->get()->map(function($item) {
            return [
                'id' => $item->id,
                'text' => $item->code . ' - ' . $item->description
            ];
        });
        
        return response()->json($costs);
    }

    public function apiDepartments(Request $request)
    {
        $search = $request->get('q');
        $query = Department::select('id', 'code', 'name');
        
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
        }
        
        $depts = $query->limit(50)->get()->map(function($item) {
            return [
                'id' => $item->id,
                'text' => $item->code . ' - ' . $item->name
            ];
        });
        
        return response()->json($depts);
    }
}
