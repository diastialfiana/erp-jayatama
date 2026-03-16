<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FixedAssetController extends Controller
{
    public function index()
    {
        return view('inventory.fixed_assets.index');
    }

    public function showPublic($code)
    {
        // For demonstration, we'll return the view with some dummy data based on the code
        // and matching the user's manual screenshots.
        return view('inventory.fixed_assets.public_detail', [
            'code' => $code,
        ]);
    }
}
