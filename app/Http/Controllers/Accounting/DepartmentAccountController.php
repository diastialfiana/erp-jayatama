<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\DepartmentAccount;
use Illuminate\Http\Request;

class DepartmentAccountController extends Controller
{
    public function detail(Request $request, $id = null)
    {
        $dept = null;
        $firstId = null;
        $lastId = null;
        $prevId = null;
        $nextId = null;
        $count = DepartmentAccount::count();
        $position = 0;

        if ($count > 0) {
            $firstId = DepartmentAccount::orderBy('id', 'asc')->value('id');
            $lastId = DepartmentAccount::orderBy('id', 'desc')->value('id');

            if ($id) {
                $dept = DepartmentAccount::findOrFail($id);
                // Navigation queries
                $prevId = DepartmentAccount::where('id', '<', $id)->max('id');
                $nextId = DepartmentAccount::where('id', '>', $id)->min('id');
                $position = DepartmentAccount::where('id', '<=', $id)->count();
            } else {
                // optional: if no ID, you could redirect to firstId or just show empty form for Create
                // In this case, we follow the user prompt which says "Support Create (when $id is null)"
                $position = 0; // new record
            }
        }

        return view('accounting.dept-account.detail', compact(
            'dept', 'firstId', 'lastId', 'prevId', 'nextId', 'count', 'position'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:department_accounts,code',
            'description' => 'nullable|string',
        ]);

        $dept = DepartmentAccount::create($validated);

        return redirect()->route('accounting.dept-account.detail', ['id' => $dept->id])
                         ->with('success', 'Department saved.');
    }

    public function update(Request $request, $id)
    {
        $dept = DepartmentAccount::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|unique:department_accounts,code,' . $dept->id,
            'description' => 'nullable|string',
        ]);

        $dept->update($validated);

        return redirect()->route('accounting.dept-account.detail', ['id' => $dept->id])
                         ->with('success', 'Department updated.');
    }

    public function list()
    {
        $departments = DepartmentAccount::orderBy('code', 'asc')->get();
        return view('accounting.dept-account.list', compact('departments'));
    }
}
