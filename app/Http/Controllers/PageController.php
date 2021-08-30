<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\KodePos;
use App\Models\Laporan;
use App\Models\Master;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    function dashboard()
    {
        return view('index');
    }

    function profile()
    {
        $data = [
            'cabang'    => Cabang::all(),
            'level'     => Master::where('mstype', 'level')->get(),
            'parent'    => User::all(),
        ];
        return view('profile', $data);
    }

    function pengajuan($noapp = null)
    {
        $data = [
            'cabang'    => Cabang::all(),
            'level'     => Master::where('mstype', 'level')->get(),
            'instype'   => Master::where('mstype', 'instype')->get(),
            'act'       => 'add',
        ];
        // dd($data['cabang']);
        if (!empty($noapp)) {
            $data['act'] = 'edit';
        }
        return view('pengajuan', $data);
    }

    function laporan($noapp = null)
    {
        $data = [
            'cabang'    => Cabang::all(),
            'asuransi'  => Master::where('mstype', 'insurance')->get(),
            'laporan'   => Laporan::where('laplevel', Auth::user()->level)->get(),
            'level'     => Master::where('mstype', 'level')->get(),
            'instype'   => Master::where('mstype', 'instype')->get(),
            'act'       => 'add',
        ];

        if (!empty($noapp)) {
            $data['act'] = 'edit';
        }
        return view('laporan', $data);
    }

    function inquiry()
    {
        $data = [
            'cabang'    => Cabang::all(),
            'level'     => Master::where('mstype', 'level')->get(),
            'provinsi'  => KodePos::select('provinsi')->distinct()->get(),
            'search'    => 'hidden',
        ];
        return view('inquiry', $data);
    }
}
