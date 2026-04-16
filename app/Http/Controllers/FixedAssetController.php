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
        $jsonRecords = '[
            {"code":"001101", "name":"lemari", "brand":"", "type":"Perabot", "category":"Perabot Kantor Unsur Logam", "serial":"", "location":"HEAD OFFICE", "initial_date":"2017-07-18", "asset_user":"", "valid_guaranty":"", "useful_life":"96", "note":""},
            {"code":"001102", "name":"meja kerja", "brand":"", "type":"Perabot", "category":"Perabot Kantor Bukan Logam", "serial":"", "location":"HEAD OFFICE", "initial_date":"2017-07-18", "asset_user":"", "valid_guaranty":"", "useful_life":"60", "note":""},
            {"code":"001103", "name":"tempat sampah", "brand":"", "type":"Perabot", "category":"Perabot Kantor Bukan Logam", "serial":"", "location":"HEAD OFFICE", "initial_date":"2017-07-18", "asset_user":"", "valid_guaranty":"", "useful_life":"48", "note":""},
            {"code":"001104", "name":"timbangan merk camry", "brand":"CAMRY", "type":"Mesin", "category":"Mesin Kantor", "serial":"CMR-2017-001", "location":"HEAD OFFICE", "initial_date":"2017-07-18", "asset_user":"GA Dept", "valid_guaranty":"", "useful_life":"48", "note":"Timbangan area kantin"},
            {"code":"001105", "name":"stopwhatch merk Q&Q", "brand":"Q&Q", "type":"Mesin", "category":"Mesin Kantor", "serial":"QQ-SW-001", "location":"HEAD OFFICE", "initial_date":"2017-07-18", "asset_user":"", "valid_guaranty":"", "useful_life":"36", "note":""},
            {"code":"001106", "name":"meteran type GEA bdn", "brand":"GEA", "type":"Mesin", "category":"Mesin Kantor", "serial":"GEA-MT-001", "location":"HEAD OFFICE", "initial_date":"2017-07-18", "asset_user":"", "valid_guaranty":"", "useful_life":"24", "note":""},
            {"code":"001107", "name":"kursi", "brand":"", "type":"Perabot", "category":"Perabot Kantor Unsur Logam", "serial":"", "location":"HEAD OFFICE", "initial_date":"2017-07-18", "asset_user":"", "valid_guaranty":"", "useful_life":"96", "note":"Kursi tamu lobby"},
            {"code":"001108", "name":"Kipas", "brand":"", "type":"Mesin", "category":"Mesin Kantor", "serial":"", "location":"HEAD OFFICE", "initial_date":"2017-07-18", "asset_user":"", "valid_guaranty":"", "useful_life":"48", "note":"Kipas angin ruang rapat"},
            {"code":"001109", "name":"Stage marawis", "brand":"", "type":"Perabot", "category":"Perabot Kantor Bukan Logam", "serial":"", "location":"HEAD OFFICE", "initial_date":"2017-07-18", "asset_user":"Events Team", "valid_guaranty":"", "useful_life":"60", "note":"Panggung kegiatan marawis"},
            {"code":"001110", "name":"Rekondisi penggantian mesin & sperpart Mobil", "brand":"", "type":"Kendaraan", "category":"Kendaraan Mobil", "serial":"", "location":"HEAD OFFICE", "initial_date":"2017-07-20", "asset_user":"Driver Pool", "valid_guaranty":"", "useful_life":"60", "note":"Rekondisi mesin kendaraan operasional"},
            {"code":"001111", "name":"bendera ptk.bordr", "brand":"", "type":"Perabot", "category":"Perabot Kantor Bukan Logam", "serial":"", "location":"HEAD OFFICE", "initial_date":"2017-07-28", "asset_user":"", "valid_guaranty":"", "useful_life":"24", "note":""},
            {"code":"001112", "name":"bendera merah putih", "brand":"", "type":"Perabot", "category":"Perabot Kantor Bukan Logam", "serial":"", "location":"HEAD OFFICE", "initial_date":"2017-07-28", "asset_user":"", "valid_guaranty":"", "useful_life":"24", "note":""},
            {"code":"001113", "name":"garuda kayu", "brand":"", "type":"Perabot", "category":"Perabot Kantor Bukan Logam", "serial":"", "location":"HEAD OFFICE", "initial_date":"2017-07-28", "asset_user":"", "valid_guaranty":"", "useful_life":"36", "note":"Lambang garuda dinding lobby"},
            {"code":"001114", "name":"gbr presiden dan wakil", "brand":"", "type":"Perabot", "category":"Perabot Kantor Bukan Logam", "serial":"", "location":"HEAD OFFICE", "initial_date":"2017-07-28", "asset_user":"", "valid_guaranty":"", "useful_life":"36", "note":"Foto resmi negara frame kayu"},
            {"code":"001115", "name":"tempat air gelas", "brand":"", "type":"Perabot", "category":"Perabot Kantor Bukan Logam", "serial":"", "location":"HEAD OFFICE", "initial_date":"2017-07-28", "asset_user":"", "valid_guaranty":"", "useful_life":"24", "note":""},
            {"code":"001116", "name":"asbak besar", "brand":"", "type":"Perabot", "category":"Perabot Kantor Bukan Logam", "serial":"", "location":"HEAD OFFICE", "initial_date":"2017-07-28", "asset_user":"", "valid_guaranty":"", "useful_life":"12", "note":""},
            {"code":"001117", "name":"dispenser", "brand":"", "type":"Mesin", "category":"Mesin Kantor", "serial":"", "location":"HEAD OFFICE", "initial_date":"2017-07-28", "asset_user":"", "valid_guaranty":"", "useful_life":"48", "note":"Dispenser ruang kantor"},
            {"code":"001118", "name":"papan tulis", "brand":"", "type":"Perabot", "category":"Perabot Kantor Bukan Logam", "serial":"", "location":"HEAD OFFICE", "initial_date":"2017-07-28", "asset_user":"", "valid_guaranty":"", "useful_life":"36", "note":"Papan whiteboard besar"},
            {"code":"001119", "name":"meja tamu", "brand":"", "type":"Perabot", "category":"Perabot Kantor Bukan Logam", "serial":"", "location":"HEAD OFFICE", "initial_date":"2017-07-28", "asset_user":"", "valid_guaranty":"", "useful_life":"60", "note":"Meja tamu lobby utama"},
            {"code":"001120", "name":"mesin absensi face recognition", "brand":"ZKTeco", "type":"Mesin", "category":"Komputer (Hardware)", "serial":"ZK-FA-2017-001", "location":"HEAD OFFICE", "initial_date":"2017-08-01", "asset_user":"IT Dept", "valid_guaranty":"2021-08-01", "useful_life":"48", "note":"Mesin absensi wajah karyawan"}
        ]';

        $records = json_decode($jsonRecords, true);
        $asset = collect($records)->firstWhere('code', $code);

        if (!$asset) {
            // fallback
            $asset = [
                'code' => $code,
                'name' => 'Unknown Asset',
                'brand' => '-',
                'type' => '-',
                'category' => '-',
                'serial' => '-',
                'location' => '-',
                'initial_date' => '-',
                'asset_user' => '-',
                'valid_guaranty' => '-',
                'useful_life' => '-',
                'note' => ''
            ];
        }

        return view('inventory.fixed_assets.public_detail', [
            'code' => $code,
            'asset' => $asset
        ]);
    }
}
