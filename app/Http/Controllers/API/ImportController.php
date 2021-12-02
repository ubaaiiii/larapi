<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Imports\ImportPembayaran;
use App\Models\Activity;
use App\Models\Master;
use App\Models\Pembayaran;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use \PhpOffice\PhpSpreadsheet\Shared\Date;

class ImportController extends Controller
{
    public function importPembayaran(Request $request)
    {
        $rows = Excel::toCollection(new ImportPembayaran, $request->file('file-import'))[0];
        $arr = [];
        // DB::enableQueryLog();
        foreach($rows as $row)
        {
            if (!is_numeric($row[0])) {
                continue;
            }
            $transid     = explode(" ", $row[2])[1];
            $tertanggung = explode(" ", $row[2])[3];
            $transaksi   = Transaksi::find($transid);
            $cek         = "";
            $i           = 0;
            $status      = "";

            if (!empty($transaksi)) {
                $cek    = "checked";
                $i      = 1;
                $status = "OK";
                $pembayaran = Pembayaran::where('id_transaksi',$transid);
                if ($row[4] !== 0) {
                    $pembayaran->where('paid_type','PD02')->get();
                    if ($pembayaran->count() > 0) {
                        $cek    = "";
                        $i      = 0;
                        $status = "Sudah Dibayar Ke Asuransi";
                    }
                } elseif ($row[5] !== 0) {
                    $pembayaran->where('paid_type','PD01')->get();
                    if ($pembayaran->count() > 0) {
                        $cek    = "";
                        $i      = 0;
                        $status = "Sudah Dibayar Oleh Bank";
                    }
                }

                if ($transaksi->id_status < 5 or in_array($transaksi->id_status,[11,12,13])) {
                    $master = Master::where('msid', $transaksi->id_status)->where('mstype','status')->first();
                    $cek    = "";
                    $i      = 0;
                    $status = "Status Belum Sesuai: ".$master->msdesc;
                }
            } else {
                $status = "Tidak Ditemukan";
            }

            $arr[] = [
                '<p hidden>'. $i .$cek.'</p>',
                $row[0],
                Date::excelToDateTimeObject($row[1])->format('Y-m-d H:i:s'),
                explode(" ",$row[2])[1],
                explode(" ",$row[2])[3],
                $row[4],
                $row[5],
                $row[6],
                $status
            ];
        } 
        // return DB::getQueryLog();
        return response()->json($arr);
    }

    public function simpanImport(Request $request)
    {

        $request->validate([
            'data'      => 'required|array|min:1',
            'data.*'    => 'required|array|min:9',
            'data.*.1'  => 'required|numeric',
            'data.*.2'  => 'required|date',
            'data.*.3'  => 'required|alpha_num',
            'data.*.4'  => 'required|regex:/^[\pL\s\-]+$/u',
            'data.*.5'  => 'required|numeric',
            'data.*.6'  => 'required|numeric',
            'data.*.7'  => 'required|numeric',
            'data.*.8'  => 'required',
        ]);

        $data = $request->data;
        $data_error  = [];
        $data_sukses = [];
        $i = 0;
        foreach ($data as $row) {
            // $checked        = $row[0];  // "checked"
            // $nomor          = $row[1];  // nomor dari excel
            $tgl_bayar      = $row[2];
            $id_transaksi   = $row[3];
            $nama_insured   = $row[4];
            $debit          = $row[5];
            $credit         = $row[6];
            $saldo          = $row[7];
            $status_excel   = $row[8];  // harusnya "OK"

            // Bayar ke Asuransi
            if ($debit != "0") {
                $paid_type  = "PD02";
                $nominal    = $debit;
                $dc         = "D";
                $status     = "9";
            } 

            // Pembayaran dari Bank
            elseif ($credit != "0") {
                $paid_type  = "PD01";
                $nominal    = $credit;
                $dc         = "C";
                $status     = "6";
            }
            
            // Tidak ada nominal credit & debit
            else {
                $data_error[] = $row;
                continue;  // skip save data
            }

            $cekPembayaran = Pembayaran::where('id_transaksi', $id_transaksi)->where('paid_type', $paid_type)->get();
            if ($cekPembayaran->count() > 0) {
                continue;  // skip, cegah duplikat
            }

            $master = Master::where('msid',$paid_type)->where('mstype','paidtype')->first();

            Pembayaran::create([ 
                'id_transaksi'  => $id_transaksi,
                'paid_amount'   => $nominal,
                'paid_at'       => $tgl_bayar,
                'created_by'    => Auth::user()->id,
                'paid_type'     => $paid_type,
                'dc'            => $dc,
                'saldo'         => $saldo,
            ]);
            
            Activity::create([  
                'id_transaksi'  => $id_transaksi,
                'id_status'     => $status,
                'deskripsi'     => $master->msdesc.". Status pembayaran: ". $status_excel,
                'created_by'    => Auth::user()->id
            ]);
            $data_sukses[] = $row;
            $i++;
        }

        return response()->json([
            'message'    => 'Berhasil Import '.$i.' Data',
            'data'       => $data_sukses,
            'data_error' => $data_error,
        ], 200);
    }
}
