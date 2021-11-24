<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Models\Activity;
use App\Models\Asuransi;
use App\Models\Cabang;
use App\Models\Document;
use App\Models\Instype;
use App\Models\Insured;
use App\Models\KodePos;
use App\Models\Okupasi;
use App\Models\Pricing;
use App\Models\Sequential;
use App\Models\Transaksi;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CetakController extends Controller
{
    public function createPDF()
    {
        // retreive all records from db
        $data = User::all();

        // share data to view
        view()->share('employee', $data);
        $pdf = PDF::loadView('prints/pdf_view', $data);

        // download PDF file with download method
        // return $pdf->download('pdf_file.pdf');

        // viewing pdf
        return $pdf->stream("dompdf_out.pdf", array("Attachment" => false));

        exit(0);
    }

    public function cetakInvoice($transid)
    {
        $transaksi = Transaksi::find($transid);
        $parameter = [
            'id' => $transid,
        ];
        $parameter = Crypt::encrypt($parameter);
        $url = url('cek_invoice') . "/" . $parameter;
        if (!empty($transaksi)) {
            if ($transaksi->id_status == 5) {
                // return $transaksi->billing_at;
                if (empty($transaksi->billing_at)) {
                    Transaksi::where('transid',$transaksi->transid)->update(['billing_at' => date('Y-m-d')]);
                    $transaksi = Transaksi::find($transid);
                }
                $data = [
                    'instype'     => Instype::find($transaksi->id_instype),
                    'asuransi'    => Asuransi::find($transaksi->id_asuransi),
                    'transaksi'   => $transaksi,
                    'insured'     => Insured::find($transaksi->id_insured),
                    'pricing'     => Pricing::where('id_transaksi',$transaksi->transid)->orderBy('id_kodetrans')->get(),
                    'cabang'      => Cabang::find($transaksi->id_cabang),
                    'due_date'    => Activity::where([
                        ['id_transaksi',$transaksi->transid],
                        ['id_status',4],
                    ])->get(),
                ];
                $qrcode = base64_encode(QrCode::format('svg')->size(70)->errorCorrection('H')->generate($url));
                // share data to view
                // view()->share('employee', $data);
                $pdf = PDF::loadView('prints/invoice', compact(
                    'data',
                    'qrcode'
                ));
                // Download PDF without viewing
                // return $pdf->download('pdf_file.pdf');
        
                // Streaming PDF, not saved on local
                // return $pdf->setpaper('a4','portrait')->stream("dompdf_out.pdf", array("Attachment" => false));
                // exit(0);
        
                // Saving PDF to local and redirect to the file
                $output = $pdf->setpaper('a4', 'portrait')->output();
                $path   = "public/files/$transid/";
                if (!is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                }
                file_put_contents($path . "Invoice_BDS-$transid.pdf", $output);
                return redirect($path . "Invoice_BDS-$transid.pdf");
            } elseif ($transaksi->id_status < 5) {
                abort(403, "Belum disetujui oleh asuransi");
            } else {
                abort(403, "Sudah dibayarkan, invoice tidak dapat dicetak kembali");
            }
        } else {
            abort(404);
        }
    }

    public function cetakCoverNote($transid)
    {
        // DB::enableQueryLog();
        $transaksi = Transaksi::find($transid);
        if (!empty($transaksi)) {
            if ($transaksi->id_status >= 4) {
                // return $transaksi->billing_at;
                $data = [
                    'transaksi'   => $transaksi,
                    'asuransi'    => Asuransi::find($transaksi->id_asuransi),
                    'instype'     => Instype::find($transaksi->id_instype),
                    'tgl_aktif'   => Activity::where('id_transaksi',$transaksi->transid)->where('id_status','4')->orderBy('created_at','DESC')->first(),
                    'sequential'  => Sequential::where('seqdesc','transid')->first(),
                    'tertanggung' => Insured::find($transaksi->id_insured),
                    'cabang'      => Cabang::find($transaksi->id_cabang),
                    'okupasi'     => Okupasi::find($transaksi->id_okupasi),
                    'kodepos'     => KodePos::find($transaksi->id_kodepos),
                    'pricing'     => Pricing::where('id_transaksi',$transaksi->transid)
                        ->join('transaksi_kode as tk','transaksi_pricing.id_kodetrans','=','tk.kodetrans_id')
                        ->orderBy('id_kodetrans','ASC')
                        ->get(),
                    'tsi'         => Pricing::where('id_transaksi', $transaksi->transid)
                        ->where('tsi',1)
                        ->where('transaksi_pricing.value','<>',0)
                        ->join('transaksi_kode as tk', 'transaksi_pricing.id_kodetrans', '=', 'tk.kodetrans_id')->get()
                ];
                // dd($data['pricing']);
                $data['covernote'] = substr($data['transaksi']->transid, -$data['sequential']->seqlen)."/CN/".$data['asuransi']->akronim."/".Functions::angka_romawi(date('m'))."/".date('Y');
                $transaksi->update(['cover_note'=>$data['covernote']]);
                $parameter = [
                    'id' => $data['covernote'],
                ];
                $parameter = Crypt::encrypt($parameter);
                $url = url('cek_covernote') . "/" . $parameter;
                // return $data['tsi'];
                
                $qrcode = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($url));
                // share data to view
                // view()->share('employee', $data);
                $pdf = PDF::loadView('prints/cover_note', compact(
                    'data',
                    'qrcode'
                ));
                // Download PDF without viewing
                // return $pdf->download('pdf_file.pdf');

                // Streaming PDF, not saved on local
                // return $pdf->setpaper('a4','portrait')->stream("dompdf_out.pdf", array("Attachment" => false));
                // exit(0);

                // Saving PDF to local and redirect to the file
                $output = $pdf->setpaper('a4', 'portrait')->output();
                $path   = "public/files/$transid/";
                $filename = "Cover_Note-$transid.pdf";
                if (!is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                }
                file_put_contents($path . $filename, $output);
                // return redirect($path . $filename);
                $insert = [
                    'id_transaksi'  => $transaksi->transid,
                    'nama_file'     => $filename,
                    'tipe_file'     => "pdf",
                    'ukuran_file'   => File::size(public_path("files/$transid/$filename")) / 1024000,
                    'lokasi_file'   => $path . $filename,
                    'jenis_file'    => "COVERNOTE",
                    'created_by'    => 1,
                ];

                Document::create($insert);

                return $data['covernote'];
            } elseif ($transaksi->id_status < 4) {
                abort(403, "Belum disetujui oleh asuransi");
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    public function cetakAkseptasi($transid)
    {
        // DB::enableQueryLog();
        $transaksi  = Transaksi::find($transid);
        if (!empty($transaksi)) {
            $asuransi   = Asuransi::find($transaksi->id_asuransi);
            if ($transaksi->id_status >= 3) {
                // return $transaksi->billing_at;
                $data = [
                    'transaksi'   => $transaksi,
                    'asuransi'    => Asuransi::find($transaksi->id_asuransi),
                    'instype'     => Instype::find($transaksi->id_instype),
                    'tgl_aktif'   => Activity::where('id_transaksi', $transaksi->transid)->where('id_status', '4')->orderBy('created_at', 'DESC')->first(),
                    'sequential'  => Sequential::where('seqdesc', 'transid')->first(),
                    'tertanggung' => Insured::find($transaksi->id_insured),
                    'cabang'      => Cabang::find($transaksi->id_cabang),
                    'okupasi'     => Okupasi::find($transaksi->id_okupasi),
                    'pricing'     => Pricing::where('id_transaksi', $transaksi->transid)
                        ->join('transaksi_kode as tk', 'transaksi_pricing.id_kodetrans', '=', 'tk.kodetrans_id')
                        ->orderBy('id_kodetrans', 'ASC')
                        ->get(),
                    'tsi'         => Pricing::where('id_transaksi', $transaksi->transid)
                        ->where('tsi', 1)
                        ->where('transaksi_pricing.value', '<>', 0)
                        ->join('transaksi_kode as tk', 'transaksi_pricing.id_kodetrans', '=', 'tk.kodetrans_id')->get()
                ];
                // share data to view
                // view()->share('employee', $data);
                $pdf = PDF::loadView('prints/akseptasi', compact(
                    'data',
                ));
                // Download PDF without viewing
                // return $pdf->download('pdf_file.pdf');

                // Streaming PDF, not saved on local
                // return $pdf->setpaper('a4', 'portrait')->stream("dompdf_out.pdf", array("Attachment" => false));
                // exit(0);

                // Saving PDF to local and redirect to the file
                $output = $pdf->setpaper('a4', 'portrait')->output();
                $path   = "public/files/$transid/";
                $filename = "Akseptasi_".$asuransi->akronim."-$transid.pdf";
                if (!is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                }
                file_put_contents($path . $filename, $output);
                // return redirect($path . $filename);
                $insert = [
                    'id_transaksi'  => $transaksi->transid,
                    'nama_file'     => $filename,
                    'tipe_file'     => "pdf",
                    'ukuran_file'   => File::size(public_path("files/$transid/$filename")) / 1024000,
                    'lokasi_file'   => $path . $filename,
                    'jenis_file'    => "AKSEPTASI",
                    'created_by'    => 1,
                ];

                Document::create($insert);

                return response()->json([
                    'message'   => 'Berhasil cetak Akseptasi',
                ], 200);
            } elseif ($transaksi->id_status < 3) {
                abort(403, "Belum disetujui oleh asuransi");
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    public function cekInvoice($params)
    {
        $encrypted = Crypt::decrypt($params);
        $data = [
            'id'    => $encrypted['id'],
        ];

        
        $transaksi = Transaksi::where('transid','=',$encrypted['id'])
        ->join('insured', 'insured.id', '=', 'transaksi.id_insured')
        ->join('cabang', 'cabang.id', '=', 'transaksi.id_cabang')
        ->first();
        // DB::enableQueryLog();
        if ($transaksi->id_status >= 4) {
            $data['data']    = $transaksi;
            $data['pricing'] = Pricing::where('id_transaksi',$transaksi->transid)->orderBy('id_kodetrans','ASC')->get();
        }
        // return DB::getQueryLog();

        // dd($data['pricing']);

        return view('cek/invoice', $data);
    }

    public function cekCoverNote($params)
    {
        $encrypted = Crypt::decrypt($params);
        $data = [
            'id'    => $encrypted['id'],
        ];
        
        $transaksi = Transaksi::where('cover_note','=',$encrypted['id'])
        ->join('insured', 'insured.id', '=', 'transaksi.id_insured')
        ->join('cabang', 'cabang.id', '=', 'transaksi.id_cabang')
        ->first();
        if (!empty($transaksi)){
            if ($transaksi->id_status >= 4) {
                $data['data']       = $transaksi;
                $data['tgl_aktif']  = Activity::where('id_transaksi',$transaksi->transid)->where('id_status','4')->orderBy('created_at','DESC')->first();
                $data['pricing']    = Pricing::where('id_transaksi', $transaksi->transid)->orderBy('id_kodetrans','ASC')->get();
                $data['asuransi']   = Asuransi::find($transaksi->id_asuransi);
            } else {
                return abort(403, "Belum disetujui oleh asuransi");
            }
        } else {
            $data['data'] = null;
        }

        // return "$data";

        // dd($data['pricing']);

        return view('cek/cover_note', $data);
    }
}
