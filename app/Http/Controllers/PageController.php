<?php

namespace App\Http\Controllers;

use App\Http\Controllers\api\DataController;
use App\Models\Cabang;
use App\Models\KodePos;
use App\Models\KodeTrans;
use App\Models\Laporan;
use App\Models\Master;
use App\Models\Okupasi;
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

    function pengajuan($transid = null)
    {
        $data = [
            'cabang'    => Cabang::all(),
            'okupasi'   => Okupasi::all(),
            'level'     => Master::where('mstype', 'level')->get(),
            'instype'   => Master::where('mstype', 'instype')->get(),
            'act'       => 'add',
            'price'     => KodeTrans::where('visible',true)->orderBy('kodetrans_index','ASC')->get(),
        ];
        // dd($data['cabang']);
        if (!empty($transid)) {
            $dataController     = new DataController;
            $data['act']        = 'edit';
            $data['data']       = $dataController->dataPengajuan($transid);
            $data['pricing']    = $dataController->dataPricing($transid);
        }
        return view('pengajuan', $data);
    }

    function laporan($transid = null)
    {
        $data = [
            'cabang'    => Cabang::all(),
            'asuransi'  => Master::where('mstype', 'insurance')->get(),
            'laporan'   => Laporan::where('laplevel', Auth::user()->level)->get(),
            'level'     => Master::where('mstype', 'level')->get(),
            'instype'   => Master::where('mstype', 'instype')->get(),
            'act'       => 'add',
        ];

        if (!empty($transid)) {
            $data['act'] = 'edit';
        }
        return view('laporan', $data);
    }

    function inquiry($search = null)
    {
        $data = [
            'cabang'    => Cabang::all(),
            'level'     => Master::where('mstype', 'level')->get(),
            'provinsi'  => KodePos::select('provinsi')->distinct()->get(),
            'search'    => 'hidden',
            'qsearch'   => $search
        ];
        return view('inquiry', $data);
    }
}
