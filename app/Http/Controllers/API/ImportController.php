<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Imports\ImportPembayaran;
use App\Models\Pembayaran;
use App\Models\Transaksi;
use Illuminate\Http\Request;
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
            $status      = "";

            if (!empty($transaksi)) {
                $cek    = "checked";
                $status = "OK";
                $pembayaran = Pembayaran::where('id_transaksi',$transid);
                if ($row[4] !== 0) {
                    $pembayaran->where('paid_type','PD02')->get();
                    if ($pembayaran->count() > 0) {
                        $cek    = "";
                        $status = "Sudah Dibayar Ke Asuransi";
                    }
                } elseif ($row[5] !== 0) {
                    $pembayaran->where('paid_type','PD01')->get();
                    if ($pembayaran->count() > 0) {
                        $cek    = "";
                        $status = "Sudah Dibayar Oleh Bank";
                    }
                }
            } else {
                $status = "Tidak Ditemukan";
            }

            $arr[] = [
                '<p hidden></p><input type="checkbox" name="imports" value="'. $transid .'" '. $cek .'>',
                $row[0],
                Date::excelToDateTimeObject($row[1])->format('Y-m-d H:i:s'),
                explode(" ",$row[2])[1],
                explode(" ",$row[2])[3],
                number_format($row[4], 2),
                number_format($row[5], 2),
                number_format($row[6], 2),
                $status
            ];
        } 
        // return DB::getQueryLog();
        return response()->json($arr);
    }
}
