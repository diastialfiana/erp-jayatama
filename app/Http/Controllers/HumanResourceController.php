<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class HumanResourceController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy('full_name', 'asc')->get();
        return view('inventory.human_resources.index', compact('employees'));
    }

    public function getDetails($id)
    {
        $employee = Employee::with(['files', 'attributes'])->findOrFail($id);
        return response()->json($employee);
    }
}
