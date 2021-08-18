<?php

namespace App\Http\Controllers;

use App\Models\KodePos;
use App\Models\Master;
use Illuminate\Http\Request;

class PageController extends Controller
{
    function dashboard()
    {
        return view('index');
    }

    function profile()
    {
        $data = [
            'cabang'    => Master::where('mstype', 'cabang')->get(),
            'level'     => Master::where('mstype', 'level')->get(),
        ];
        return view('profile', $data);
    }

    function pengajuan()
    {
        $data = [
            'cabang'    => Master::where('mstype', 'cabang')->get(),
            'level'     => Master::where('mstype', 'level')->get(),
            'provinsi'  => KodePos::select('provinsi')->distinct()->get(),
        ];
        return view('pengajuan', $data);
    }

    function inquiry()
    {
        $data = [
            'cabang'    => Master::where('mstype', 'cabang')->get(),
            'level'     => Master::where('mstype', 'level')->get(),
            'provinsi'  => KodePos::select('provinsi')->distinct()->get(),
        ];
        return view('inquiry', $data);
    }
}
