<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Asuransi;
use App\Models\Cabang;
use App\Models\Instype;
use App\Models\Insured;
use App\Models\Pricing;
use App\Models\Transaksi;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Crypt;

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
            if ($transaksi->id_status == 4) {
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
                return $pdf->setpaper('a4','portrait')->stream("dompdf_out.pdf", array("Attachment" => false));
                exit(0);
        
                // Saving PDF to local and redirect to the file
                $output = $pdf->output();
                $path   = "public/files/BDS2110000001/";
                if (!is_dir($path)) {
                    mkdir($path, 0777, TRUE);
                }
                file_put_contents($path . 'Invoice-BDS2110000001.pdf', $output);
                return redirect($path . 'Invoice-BDS2110000001.pdf');
            } elseif ($transaksi->id_status < 4) {
                abort(403, 'Belum disetujui oleh asuransi');
            } else {
                return redirect($url);
            }
        } else {
            abort(404);
        }
    }

    public function redirectInvoice()
    {
        $parameter = [
            'id' => "BDS2110000001",
        ];
        $parameter = Crypt::encrypt($parameter);

        return redirect('cek_invoice', $parameter);
    }

    public function cekInvoice($params)
    {
        $encrypted = Crypt::decrypt($params);
        $data = [
            'id'    => $encrypted['id'],
        ];

        $transaksi = Transaksi::find($encrypted['id']);
        if ($transaksi->id_status == 4) {
            $data['data'] = $transaksi;
        }

        return view('cekinvoice', $data);
    }
}
