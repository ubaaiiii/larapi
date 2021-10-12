<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use PDF;

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

    public function cetakInvoice(Request $request)
    {
        // retreive all records from db
        $data = User::all();

        // share data to view
        // view()->share('employee', $data);
        $pdf = PDF::loadView('prints/invoice', compact(
            'data',
        ));
        // Download PDF without viewing
        // return $pdf->download('pdf_file.pdf');

        // Streaming PDF, not saved on local
        return $pdf->setPaper([0, 0, 775.6621,609.4488],'portrait')->stream("dompdf_out.pdf", array("Attachment" => false));

        exit(0);

        // Saving PDF to local and redirect to the file
        // $output = $pdf->setPaper([0, 0, 210, 165])->output();
        // $path = "public/files/BDS2110000001/";
        // if (!is_dir($path)) {
        //     mkdir($path, 0777, TRUE);
        // }
        // file_put_contents($path.'invoice.pdf', $output);
        // return redirect($path.'invoice.pdf');
    }
}
