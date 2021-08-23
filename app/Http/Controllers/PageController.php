<?php

namespace App\Http\Controllers;

use App\Models\KodePos;
use App\Models\Master;
use App\Models\User;
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
            'parent'    => User::all(),
        ];
        return view('profile', $data);
    }

    function pengajuan($noapp = null)
    {
        $data = [
            'cabang'    => Master::where('mstype', 'cabang')->get(),
            'level'     => Master::where('mstype', 'level')->get(),
            'instype'   => Master::where('mstype', 'instype')->get(),
            'act'       => 'add',
        ];
        if (!empty($noapp)) {
            $data['act'] = 'edit';
        }
        return view('pengajuan', $data);
    }

    function inquiry()
    {
        $data = [
            'cabang'    => Master::where('mstype', 'cabang')->get(),
            'level'     => Master::where('mstype', 'level')->get(),
            'provinsi'  => KodePos::select('provinsi')->distinct()->get(),
            'search'    => 'hidden',
        ];
        return view('inquiry', $data);
    }
}
