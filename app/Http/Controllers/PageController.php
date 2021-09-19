<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\DataController;
use App\Models\Asuransi;
use App\Models\Cabang;
use App\Models\KodePos;
use App\Models\KodeTrans;
use App\Models\Laporan;
use App\Models\Master;
use App\Models\Okupasi;
use App\Models\Page;
use App\Models\Sequential;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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

    function pengajuan($transid = null, Request $request)
    {
        $data = [
            'cabang'    => Cabang::where('visible',1)->orderBy('nama_cabang','asc')->get(),
            'asuransi'  => Asuransi::all(),
            'okupasi'   => Okupasi::all(),
            'level'     => Master::where('mstype', 'level')->get(),
            'instype'   => Master::where('mstype', 'instype')->get(),
            'act'       => 'add',
            'price'     => KodeTrans::where('visible', true)->orderBy('kodetrans_index', 'ASC')->get(),
            'transid'   => Sequential::where('seqdesc', 'transid')->first(),
            'method'    => $request->method,
        ];
        // echo $data['transid']->seqno;
        // die;
        // dd($data['cabang']);
        if (!empty($transid)) {
            $dataController     = new DataController;
            $data['act']        = 'edit';
            $data['data']       = $dataController->dataPengajuan($transid);
            $data['pricing']    = $dataController->dataPricing($transid);
            $data['activity']   = $dataController->dataAktifitas($transid);
            // dd($data['document']);
        } else {
            Sequential::where('seqdesc', 'transid')->update(['seqno' => $data['transid']->seqno + 1]);
        }
        return view('pengajuan', $data);
    }

    function laporan($transid = null)
    {
        // dd(Page::roleHasPages(Auth::user()->id,1,4)->get());
        // Permission::insert([
        //     [
        //         "name"  => "ajukan pengajuan",
        //         "guard_name" => "web"
        //     ],
        //     [
        //         "name"  => "aktifkan pengajuan",
        //         "guard_name" => "web"
        //     ],
        //     [
        //         "name"  => "edit pengajuan",
        //         "guard_name" => "web"
        //     ],
        //     [
        //         "name"  => "delete pengajuan",
        //         "guard_name" => "web"
        //     ],
        //     [
        //         "name"  => "approve pengajuan",
        //         "guard_name" => "web"
        //     ],
        // ]);

        // $role = Role::findById(5);
        // $role->givePermissionTo([
        //     'create pengajuan',
        //     'edit pengajuan',
        //     'delete pengajuan',
        //     'approve pengajuan',
        //     'rollback pengajuan',
        //     'proses pengajuan',
        //     'ajukan pengajuan',
        //     'aktifkan pengajuan',
        // ]);

        // $user = User::find(5);
        // $user->assignRole('checker');

        $data = [
            'cabang'    => Cabang::all(),
            'asuransi'  => Master::where('mstype', 'insurance')->get(),
            'laporan'   => Laporan::where('laplevel', Auth::user()->level)->get(),
            'level'     => Master::where('mstype', 'level')->get(),
            'instype'   => Master::where('mstype', 'instype')->get(),
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

    function user($search = null)
    {
        $data = [
            'cabang'    => Cabang::all(),
            'level'     => Master::where('mstype', 'level')->get(),
            'provinsi'  => KodePos::select('provinsi')->distinct()->get(),
            'search'    => 'hidden',
            'qsearch'   => $search
        ];
        return view('user', $data);
    }
}
