<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\DataController;
use App\Http\Controllers\API\LaporanController;
use App\Models\Asuransi;
use App\Models\Cabang;
use App\Models\Instype;
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
            'cabang'    => Cabang::withTrashed()->get(),
            'level'     => Master::where('mstype', 'level')->get(),
            'parent'    => User::all(),
        ];
        return view('profile', $data);
    }

    function pengajuan($transid = null, Request $request)
    {
        $data = [
            'cabang'    => Cabang::withoutTrashed()->orderBy('nama_cabang','asc')->get(),
            'asuransi'  => Asuransi::all(),
            'okupasi'   => Okupasi::all(),
            'instype'   => Instype::all(),
            'jaminan'   => Master::withoutTrashed()->where('mstype', 'jaminan')->get(),
            'act'       => 'add',
            'price'     => KodeTrans::withoutTrashed()->where('tsi', true)->orderBy('kodetrans_index', 'ASC')->get(),
            'formula'   => KodeTrans::withoutTrashed()->whereNotNull('kodetrans_formula')->orderBy('kodetrans_index', 'ASC')->get(),
            'value'     => KodeTrans::withoutTrashed()->whereNull('kodetrans_formula')->orderBy('kodetrans_index', 'ASC')->get(),
            'hitung'    => KodeTrans::where('hitung', true)->orderBy('kodetrans_index', 'ASC')->get(),
            'transid'   => Sequential::where('seqdesc', 'transid')->first(),
            'method'    => $request->method,
        ];
        // echo $data['transid']->seqno;
        // die;
        // dd($data['hitung']);
        if (!empty($transid)) {
            $dataController     = new DataController;
            $transaksi = $dataController->dataPengajuan($transid);
            $data['act']        = 'edit';
            $data['data']       = $transaksi;
            $data['pricing']    = $dataController->dataPricing($transid);
            $data['status']     = Master::where('msid', $transaksi->id_status)->where('mstype','status')->first();
            // dd($data['document']);
        } else {
            Sequential::where('seqdesc', 'transid')->update(['seqno' => $data['transid']->seqno + 1]);
        }
        return view('pengajuan', $data);
    }

    function perpanjangan($transid = null, Request $request)
    {
        $data = [
            'cabang'    => Cabang::orderBy('nama_cabang','asc')->get(),
            'asuransi'  => Asuransi::all(),
            'okupasi'   => Okupasi::all(),
            'instype'   => Instype::all(),
            'jaminan'   => Master::where('mstype', 'jaminan')->get(),
            'act'       => 'add',
            'price'     => KodeTrans::where('tsi', true)->orderBy('kodetrans_index', 'ASC')->get(),
            'formula'   => KodeTrans::whereNotNull('kodetrans_formula')->orderBy('kodetrans_index', 'ASC')->get(),
            'value'     => KodeTrans::whereNull('kodetrans_formula')->orderBy('kodetrans_index', 'ASC')->get(),
            'hitung'    => KodeTrans::where('hitung', true)->orderBy('kodetrans_index', 'ASC')->get(),
            'transid'   => Sequential::where('seqdesc', 'transid')->first(),
            'method'    => $request->method,
        ];
        // echo $data['transid']->seqno;
        // die;
        // dd($data['hitung']);
        if (!empty($transid)) {
            $dataController     = new DataController;
            $transaksi = $dataController->dataPengajuan($transid);
            $data['act']        = 'edit';
            $data['data']       = $transaksi;
            $data['pricing']    = $dataController->dataPricing($transid);
            $data['status']     = Master::where('msid', $transaksi->id_status)->where('mstype','status')->first();
            // dd($data['document']);
        } else {
            Sequential::where('seqdesc', 'transid')->update(['seqno' => $data['transid']->seqno + 1]);
        }
        return view('pengajuan', $data);
    }

    function notifikasi($transid = null, Request $request)
    {
        $data = [
            
        ];
        
        return view('notifikasi', $data);
    }

    function laporan(Request $request)
    {
        // $role = Role::create(['name' => 'finance']);
        // $permission = Permission::create(['name' => 'edit articles']);
        // $permission->assignRole('');
        // die;
        $level = Auth::user()->getRoleNames()[0];
        if (count($request->all()) == 0) {
            $cabang = Cabang::all();
            $asuransi = Asuransi::all();
            switch ($level) {
                case 'maker':
                    $cabang = Cabang::where('id',Auth::user()->id_cabang)->get();
                    break;

                case 'insurance':
                    $asuransi = Asuransi::where('id',Auth::user()->id_asuransi)->get();
                    break;
                    
                default:
                    break;
            }
            
            
            $data = [
                'cabang'    => $cabang,
                'asuransi'  => $asuransi,
                'laporan'   => Laporan::join('role_has_laporan as rl','laporan.id','=','rl.laporan_id')
                ->join('model_has_roles as mr','rl.role_id','=','mr.role_id')
                ->where('mr.model_id','=',Auth::user()->id)
                ->get(),
                'instype'   => Instype::all(),
            ];
            
        } else {
            // return $request->all();
            $laporan     = new LaporanController;
            $data = [
                'data'      => $request,
                'columns'   => $laporan->tableLaporan($request),
                // 'dataLaporan'   => $dataController->dataLaporan($request),
            ];
            // return "nyampe sini";
        }

        return view('laporan', $data);
    }

    function inquiry(Request $request, $search = null)
    {
        $data = [
            'cabang'    => Cabang::all(),
            'level'     => Master::where('mstype', 'level')->get(),
            'provinsi'  => KodePos::select('provinsi')->distinct()->get(),
            'search'    => 'hidden',
            'qsearch'   => $search,
            'request'   => $request->all(),
        ];
        return view('inquiry', $data);
    }

    function pembayaran(Request $request, $search = null)
    {
        $data = [
            'cabang'    => Cabang::all(),
            'level'     => Master::where('mstype', 'level')->get(),
            'provinsi'  => KodePos::select('provinsi')->distinct()->get(),
            'search'    => 'hidden',
            'qsearch'   => $search,
            'request'   => $request->all(),
        ];
        return view('pembayaran', $data);
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
