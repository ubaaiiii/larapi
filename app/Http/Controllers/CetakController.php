<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Models\Activity;
use App\Models\Asuransi;
use App\Models\Cabang;
use App\Models\Document;
use App\Models\Installment;
use App\Models\Instype;
use App\Models\Insured;
use App\Models\KodePos;
use App\Models\Master;
use App\Models\Okupasi;
use App\Models\Pembayaran;
use App\Models\Pricing;
use App\Models\Sequential;
use App\Models\Transaksi;
use App\Models\TransaksiObjek;
use App\Models\TransaksiPerluasan;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

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
            if ($transaksi->id_status >= 5 AND $transaksi->id_status <= 10) {
                // return $transaksi->billing_at;
                if (empty($transaksi->billing_at)) {
                    Transaksi::where('transid',$transaksi->transid)->update(['billing_at' => date('Y-m-d')]);
                    $transaksi = Transaksi::find($transid);
                }
                $dataPricing = Pricing::where('id_transaksi', $transaksi->transid)->orderBy('id_kodetrans')->get();
                foreach ($dataPricing as $row) {
                    // echo $row->id_kodetrans;
                    $pricing[$row->id_kodetrans] = $row;
                }

                $data = [
                    'instype'     => Instype::find($transaksi->id_instype),
                    'asuransi'    => Asuransi::find($transaksi->id_asuransi),
                    'transaksi'   => $transaksi,
                    'insured'     => Insured::find($transaksi->id_insured),
                    'pricing'     => $pricing,
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
                $path   = "files/$transid/";
                $filename = "Invoice_BDS-$transid.pdf";
                if (!is_dir(public_path($path))) {
                    mkdir(public_path($path), 0777, TRUE);
                }
                $eksis = false;
                if (file_exists(public_path($path . $filename))) {
                    $eksis = true;
                }

                file_put_contents(public_path($path) . $filename, $output);
                // return redirect($path . $filename);
                $insert = [
                    'id_transaksi'  => $transaksi->transid,
                    'nama_file'     => $filename,
                    'tipe_file'     => "pdf",
                    'ukuran_file'   => File::size(public_path("files/$transid/$filename")) / 1024000,
                    'lokasi_file'   => "public/" . $path . $filename,
                    'jenis_file'    => "INVOICE",
                    'visible_by'    => "broker,maker,checker,approver,finance,adm",
                    'created_by'    => 1,
                ];

                if (!$eksis) {
                    Document::create($insert);
                }

                return redirect(url("public/".$path) ."/". $filename);
            } elseif ($transaksi->id_status < 5) {
                abort(403, "Belum disetujui oleh asuransi");
            } else {
                abort(403, "Sudah dibayarkan, invoice tidak dapat dicetak kembali");
            }
        } else {
            abort(404);
        }
    }

    public function cetakNotaPembayaran($transid)
    {
        $transaksi = Transaksi::find($transid);
        $parameter = [
            'id' => $transid,
        ];
        $parameter = Crypt::encrypt($parameter);
        $url = url('cek_nota_pembayaran') . "/" . $parameter;
        if (!empty($transaksi)) {
            if ($transaksi->id_status >= 6 AND $transaksi->id_status <= 10) {
                // return $transaksi->billing_at;
                if (empty($transaksi->billing_at)) {
                    Transaksi::where('transid',$transaksi->transid)->update(['billing_at' => date('Y-m-d')]);
                    $transaksi = Transaksi::find($transid);
                }
                $dataPricing = Pricing::where('id_transaksi', $transaksi->transid)->orderBy('id_kodetrans')->get();
                foreach ($dataPricing as $row) {
                    // echo $row->id_kodetrans;
                    $pricing[$row->id_kodetrans] = $row;
                }

                $data = [
                    'instype'     => Instype::find($transaksi->id_instype),
                    'asuransi'    => Asuransi::find($transaksi->id_asuransi),
                    'transaksi'   => $transaksi,
                    'pembayaran'  => Pembayaran::where('id_transaksi',$transid)->where('paid_type','PD02')->first(),
                    'insured'     => Insured::find($transaksi->id_insured),
                    'pricing'     => $pricing,
                    'cabang'      => Cabang::find($transaksi->id_cabang),
                ];
                $qrcode = base64_encode(QrCode::format('svg')->size(90)->errorCorrection('H')->generate($url));
                // share data to view
                // view()->share('employee', $data);
                $pdf = PDF::loadView('prints/nota_pembayaran', compact(
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
                $path   = "files/$transid/";
                $filename = "Nota_Pembayaran_BDS-$transid.pdf";
                if (!is_dir(public_path($path))) {
                    mkdir(public_path($path), 0777, TRUE);
                }
                $eksis = false;
                if (file_exists(public_path($path . $filename))) {
                    $eksis = true;
                }

                file_put_contents(public_path($path . $filename), $output);
                // return redirect($path . $filename);
                $insert = [
                    'id_transaksi'  => $transaksi->transid,
                    'nama_file'     => $filename,
                    'tipe_file'     => "pdf",
                    'ukuran_file'   => File::size(public_path($path . $filename)) / 1024000,
                    'lokasi_file'   => "public/" . $path . $filename,
                    'jenis_file'    => "NOTAPEMBAYARAN",
                    'visible_by'    => "broker,insurance,finance,adm",
                    'created_by'    => 1,
                ];

                if (!$eksis) {
                    Document::create($insert);
                }

                return redirect(url("public/".$path) ."/". $filename);
            } elseif ($transaksi->id_status < 6) {
                abort(403, "Belum dibayar oleh Broker");
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
                $dataPricing = Pricing::where('id_transaksi', $transaksi->transid)
                    ->join('transaksi_kode as tk','transaksi_pricing.id_kodetrans','=','tk.kodetrans_id')
                    ->orderBy('id_kodetrans','ASC')
                    ->get();
                foreach ($dataPricing as $row) {
                    // echo $row->id_kodetrans;
                    $pricing[$row->id_kodetrans] = $row;
                }

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
                    'jaminan'     => Master::where('msid', $transaksi->id_jaminan)->where('mstype', 'jaminan')->first(),
                    'pricing'     => $pricing,
                    'tsi'         => Pricing::where('id_transaksi', $transaksi->transid)
                        ->where('tsi',1)
                        ->where('transaksi_pricing.value','<>',0)
                        ->join('transaksi_kode as tk', 'transaksi_pricing.id_kodetrans', '=', 'tk.kodetrans_id')->get()
                ];
                // dd($data['pricing']);
                $data['covernote'] = substr($data['transaksi']->transid, -$data['sequential']->seqlen)."/CN-BDS/".$data['asuransi']->akronim."/".Functions::angka_romawi(date('m'))."/".date('Y');
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
                $path   = "files/$transid/";
                $filename = "Cover_Note-$transid.pdf";
                if (!is_dir(public_path($path))) {
                    mkdir(public_path($path), 0777, TRUE);
                }

                $eksis = false;
                if (file_exists(public_path($path . $filename))) {
                    $eksis = true;
                }

                file_put_contents(public_path($path) . $filename, $output);
                // return redirect($path . $filename);
                $insert = [
                    'id_transaksi'  => $transaksi->transid,
                    'nama_file'     => $filename,
                    'tipe_file'     => "pdf",
                    'ukuran_file'   => File::size(public_path("files/$transid/$filename")) / 1024000,
                    'lokasi_file'   => "public/".$path . $filename,
                    'jenis_file'    => "COVERNOTE",
                    'created_by'    => 1,
                ];
                
                if (!$eksis) {
                    Document::create($insert);
                }

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

    public function cetakPlacing($transid)
    {
        // DB::enableQueryLog();
        $transaksi = Transaksi::find($transid);
        if (!empty($transaksi)) {
            if ($transaksi->id_status >= 3) {
                $dataPricing = Pricing::where('id_transaksi', $transaksi->transid)
                    ->join('transaksi_kode as tk', 'transaksi_pricing.id_kodetrans', '=', 'tk.kodetrans_id')
                    ->orderBy('id_kodetrans', 'ASC')
                    ->get();
                foreach ($dataPricing as $row) {
                    // echo $row->id_kodetrans;
                    $pricing[$row->id_kodetrans] = $row;
                }

                $data = [
                    'transaksi'   => $transaksi,
                    'asuransi'    => Asuransi::find($transaksi->id_asuransi),
                    'instype'     => Instype::find($transaksi->id_instype),
                    'tertanggung' => Insured::find($transaksi->id_insured),
                    'cabang'      => Cabang::find($transaksi->id_cabang),
                    'okupasi'     => Okupasi::find($transaksi->id_okupasi),
                    'kodepos'     => KodePos::find($transaksi->id_kodepos),
                    'jaminan'     => Master::where('msid', $transaksi->id_jaminan)->where('mstype', 'jaminan')->first(),
                    'pricing'     => $pricing,
                    'tsi'         => Pricing::where('id_transaksi', $transaksi->transid)
                        ->where('tsi', 1)
                        ->where('transaksi_pricing.value', '<>', 0)
                        ->join('transaksi_kode as tk', 'transaksi_pricing.id_kodetrans', '=', 'tk.kodetrans_id')->get()
                ];

                // share data to view
                // view()->share('employee', $data);
                $pdf = PDF::loadView('prints/placing', compact(
                    'data',
                ));
                // Download PDF without viewing
                // return $pdf->download('pdf_file.pdf');

                // Streaming PDF, not saved on local
                // return $pdf->setpaper('a4','portrait')->stream("dompdf_out.pdf", array("Attachment" => false));
                // exit(0);

                // Saving PDF to local and redirect to the file
                $output = $pdf->setpaper('a4', 'portrait')->output();
                $path   = "files/$transid/";
                $filename = "Placing_Slip_Format-$transid.pdf";
                if (!is_dir(public_path($path))) {
                    mkdir(public_path($path), 0777, TRUE);
                }

                $eksis = false;
                if (file_exists(public_path($path . $filename))) {
                    $eksis = true;
                }

                file_put_contents(public_path($path . $filename), $output);
                $insert = [
                    'id_transaksi'  => $transaksi->transid,
                    'nama_file'     => $filename,
                    'tipe_file'     => "pdf",
                    'visible_by'    => 'broker,insurance,adm',
                    'ukuran_file'   => File::size(public_path($path . $filename)) / 1024000,
                    'lokasi_file'   => "public/".$path . $filename,
                    'jenis_file'    => "PLACING",
                    'created_by'    => 1,
                ];
                
                if (!$eksis) {
                    Document::create($insert);
                }

                // return redirect($path . $filename);

                return Redirect::to(url('public/'.$path.$filename));
            } elseif ($transaksi->id_status < 3) {
                abort(403, "Belum Diverifikasi oleh Broker");
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    //Wholesales
    public function cetakKlausula($transid, $jenis, $id_asuransi)
    {
        // DB::enableQueryLog();
        $transaksi = Transaksi::find($transid);
        if (!empty($transaksi)) {
            if ($transaksi->id_status >= 3) {
                $dataPricing = Pricing::where('id_transaksi', $transaksi->transid)
                    ->join('transaksi_kode as tk', 'transaksi_pricing.id_kodetrans', '=', 'tk.kodetrans_id')
                    ->whereNull('id_objek')
                    ->orderBy('id_kodetrans', 'ASC')
                    ->get();
                foreach ($dataPricing as $row) {
                    // echo $row->id_kodetrans;
                    $pricing[$row->id_kodetrans] = $row;
                }

                if ($jenis == "placing") {
                    $jenis_text = "PLACING SLIP";
                    $jenis_kode = "PS";
                    $jenis_link = "cek_placing";
                    $jenis_file = "PLACING";
                } else if ($jenis == "ftc") {
                    $jenis_text = "FINAL TERM CONDITION";
                    $jenis_kode = "FTC";
                    $jenis_link = "cek_ftc";
                    $jenis_file = "FTC";
                } else {
                    abort(404);
                }
                // DB::enableQueryLog();
                $data = [
                    'transaksi'   => $transaksi,
                    'instype'     => Instype::find($transaksi->id_instype),
                    'tertanggung' => Insured::find($transaksi->id_insured),
                    'cabang'      => Cabang::find($transaksi->id_cabang),
                    'installment' => Installment::where('id_transaksi', '=', $transaksi->transid)->get(),
                    'asuransi'    => Asuransi::where('id', '=', $id_asuransi)->first(),
                    'sequential'  => Sequential::where('seqdesc', 'transid')->first(),
                    'jenis'       => $jenis_text,
                    'pricing'     => $pricing,
                    'objek'       => TransaksiObjek::join('kodepos', 'id_kodepos', '=', 'kodepos.id')
                        ->leftJoin('okupasi', 'id_okupasi', '=', 'okupasi.id')
                        ->leftJoin('kelas_pertanggungan', 'id_kelas', '=', 'kelas_pertanggungan.id')
                        ->where('transaksi_objek.id_transaksi', $transaksi->transid)
                        ->select([
                            'transaksi_objek.id       AS objek_id',
                            'transaksi_objek.*',
                            'kelas_pertanggungan.*',
                            'okupasi.*',
                            'kodepos.*',
                        ])
                        ->get(),
                    'objek_pricing' => TransaksiObjek::join('transaksi_pricing', function ($q) use ($transaksi) {
                            $q->on('id_objek', '=', 'transaksi_objek.id')
                                ->whereNotNull('id_objek')
                                ->where('transaksi_pricing.id_transaksi', '=', $transaksi->transid);
                        })
                        ->leftJoin('transaksi_kode', 'id_kodetrans', '=', 'kodetrans_id')
                        ->where('transaksi_objek.id_transaksi', $transaksi->transid)->get(),
                    'perluasan'   => TransaksiPerluasan::join('perluasan', 'id_perluasan', '=', 'perluasan.id')
                        ->where('id_transaksi', $transaksi->transid)
                        ->select([
                            'perluasan.id',
                            'perluasan.kode',
                            'perluasan.keterangan',
                            'transaksi_perluasan.id_objek',
                            'transaksi_perluasan.id_perluasan',
                            DB::raw('IF (transaksi_perluasan.rate IS NOT NULL, transaksi_perluasan.rate, perluasan.rate) as rate'),
                            DB::raw('IF (transaksi_perluasan.value IS NOT NULL, transaksi_perluasan.value, 0) as value')
                        ])
                        ->get(),
                    // 'tsi'         => Pricing::where('id_transaksi', $transaksi->transid)
                    //     ->where('tsi', 1)
                    //     ->where('transaksi_pricing.value', '<>', 0)
                    //     ->join('transaksi_kode as tk', 'transaksi_pricing.id_kodetrans', '=', 'tk.kodetrans_id')->get(),
                ];
                // return DB::getQueryLog();
                // dd($data['asuransi']);
                $data['nomor_surat'] = substr($data['transaksi']->transid, -$data['sequential']->seqlen) . "/" . $jenis_kode . "/" . $data['instype']->id . "/UW-01/BDS/" . Functions::angka_romawi(date('m')) . "/" . date('Y');
                $parameter = [
                    'id' => $transaksi->transid,
                ];
                $parameter = Crypt::encrypt($parameter);
                $url = url($jenis_link) . "/" . $parameter;
                // return $data['tsi'];

                $qrcode = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($url));
                // share data to view
                // view()->share('employee', $data);
                $pdf = PDF::loadView('prints/wholesales_klausula', compact(
                    'data',
                    'qrcode',
                ));

                // $pdf->set_option("isPhpEnabled", true);
                // Download PDF without viewing
                // return $pdf->download('pdf_file.pdf');

                // Streaming PDF, not saved on local
                return $pdf->setpaper('a4', 'portrait')->stream("dompdf_out.pdf", array("Attachment" => false));
                exit(0);

                // Saving PDF to local and redirect to the file
                $output = $pdf->setpaper('a4', 'portrait')->output();
                $path   = "files/$transid/";

                if ($jenis == "placing") {
                    $filename = "PS-" . substr($data['transaksi']->transid, -$data['sequential']->seqlen) . "- " . $data['instype']->id . " - " . $data['tertanggung']->nama_insured . ".pdf";
                } else if ($jenis == "ftc") {
                    $filename = "FTC-" . substr($data['transaksi']->transid, -$data['sequential']->seqlen) . "- " . $data['instype']->id . " - " . $data['tertanggung']->nama_insured . ".pdf";
                } else {
                    abort(404);
                }

                if (!is_dir(public_path($path))) {
                    mkdir(public_path($path), 0777, TRUE);
                }

                $eksis = false;
                if (file_exists(public_path($path . $filename))) {
                    $eksis = true;
                }

                file_put_contents(public_path($path) . $filename, $output);
                // return redirect($path . $filename);
                $insert = [
                    'id_transaksi'  => $transaksi->transid,
                    'nama_file'     => $filename,
                    'tipe_file'     => "pdf",
                    'ukuran_file'   => File::size(public_path("files/$transid/$filename")) / 1024000,
                    'lokasi_file'   => "public/" . $path . $filename,
                    'jenis_file'    => $jenis_file,
                    'created_by'    => Auth::user()->id,
                ];

                if (!$eksis) {
                    Document::create($insert);
                }

                return redirect(url("public/" . $path) . "/" . $filename);
            } elseif ($transaksi->id_status < 3) {
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
                $dataPricing = Pricing::where('id_transaksi', $transaksi->transid)
                    ->join('transaksi_kode as tk', 'transaksi_pricing.id_kodetrans', '=', 'tk.kodetrans_id')
                    ->orderBy('id_kodetrans', 'ASC')
                    ->get();
                foreach ($dataPricing as $row) {
                    // echo $row->id_kodetrans;
                    $pricing[$row->id_kodetrans] = $row;
                }

                $data = [
                    'transaksi'   => $transaksi,
                    'asuransi'    => Asuransi::find($transaksi->id_asuransi),
                    'instype'     => Instype::find($transaksi->id_instype),
                    'tgl_aktif'   => Activity::where('id_transaksi', $transaksi->transid)->where('id_status', '4')->orderBy('created_at', 'DESC')->first(),
                    'sequential'  => Sequential::where('seqdesc', 'transid')->first(),
                    'tertanggung' => Insured::find($transaksi->id_insured),
                    'cabang'      => Cabang::find($transaksi->id_cabang),
                    'okupasi'     => Okupasi::find($transaksi->id_okupasi),
                    'kodepos'     => KodePos::find($transaksi->id_kodepos),
                    'jaminan'     => Master::where('msid', $transaksi->id_jaminan)->where('mstype', 'jaminan')->first(),
                    'pricing'     => $pricing,
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
                $path   = "files/$transid/";
                $filename = "Akseptasi_".$asuransi->akronim."-$transid.pdf";
                if (!is_dir(public_path($path))) {
                    mkdir(public_path($path), 0777, TRUE);
                }
                
                $eksis = false;
                if (file_exists(public_path($path . $filename))) {
                    $eksis = true;
                }

                file_put_contents(public_path($path) . $filename, $output);
                // return redirect($path . $filename);
                $insert = [
                    'id_transaksi'  => $transaksi->transid,
                    'nama_file'     => $filename,
                    'tipe_file'     => "pdf",
                    'ukuran_file'   => File::size(public_path("files/$transid/$filename")) / 1024000,
                    'lokasi_file'   => "public/" . $path . $filename,
                    'jenis_file'    => "AKSEPTASI",
                    'created_by'    => 1,
                ];

                if (!$eksis) {
                    Document::create($insert);
                }

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

        $dataPricing = Pricing::where('id_transaksi', $transaksi->transid)->orderBy('id_kodetrans')->get();
        foreach ($dataPricing as $row) {
            // echo $row->id_kodetrans;
            $pricing[$row->id_kodetrans] = $row;
        }

        if ($transaksi->id_status >= 4) {
            $data['data']       = $transaksi;
            $data['pricing']    = $pricing;
            $data['dokumen']    = Document::where('id_transaksi', $transaksi->transid)->where('jenis_file', 'INVOICE')->first();
            $data['pembayaran'] = Pembayaran::where('id_transaksi', $transaksi->transid)->where('paid_type', 'PD01')->first();
        }
        // return DB::getQueryLog();

        // dd($data['pricing']);

        return view('cek/invoice', $data);
    }

    public function cekNotaPembayaran($params)
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

        $dataPricing = Pricing::where('id_transaksi', $transaksi->transid)->orderBy('id_kodetrans')->get();
        foreach ($dataPricing as $row) {
            // echo $row->id_kodetrans;
            $pricing[$row->id_kodetrans] = $row;
        }
        if ($transaksi->id_status >= 4) {
            $data['data']       = $transaksi;
            $data['pricing']    = $pricing;
            $data['dokumen']    = Document::where('id_transaksi', $transaksi->transid)->where('jenis_file', 'NOTAPEMBAYARAN')->first();
            $data['penerimaan'] = Pembayaran::where('id_transaksi', $transaksi->transid)->where('paid_type', 'PD01')->first();
            $data['pembayaran'] = Pembayaran::where('id_transaksi', $transaksi->transid)->where('paid_type', 'PD02')->first();
            $data['asuransi']   = Asuransi::find($transaksi->id_asuransi);
            $data['user_bayar'] = User::find($data['pembayaran']->created_by);
        }
        // return DB::getQueryLog();

        // dd($data['pricing']);

        return view('cek/nota_pembayaran', $data);
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
                $dataPricing = Pricing::where('id_transaksi', $transaksi->transid)->orderBy('id_kodetrans', 'ASC')->get();
                foreach ($dataPricing as $row) {
                    // echo $row->id_kodetrans;
                    $pricing[$row->id_kodetrans] = $row;
                }

                $data['data']       = $transaksi;
                $data['tgl_aktif']  = Activity::where('id_transaksi',$transaksi->transid)->where('id_status','4')->orderBy('created_at','DESC')->first();
                $data['pricing']    = $pricing;
                $data['asuransi']   = Asuransi::find($transaksi->id_asuransi);
                $data['dokumen']    = Document::where('id_transaksi',$transaksi->transid)->where('jenis_file','COVERNOTE')->first();
            } else {
                return abort(403, "Belum disetujui oleh asuransi");
            }
        } else {
            $data['data'] = null;
        }

        return view('cek/cover_note', $data);
    }

}
