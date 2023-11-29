<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Milon\Barcode\DNS1D;
use PDF;

class BarcodeController extends Controller
{
    public function index(Request $request)
    {
        $title = "Barcode Generator";
        return view('admin.pages.barcode-generator.index', compact('title'));
    }

    public function barcodeSize(Request $request)
    {
        try {
            if ($request->ajax()) {
                $DNSID = new DNS1D();
                $barcode = $DNSID->getBarcodePNG('DAR-0001', 'C128', 2, $request->barcodeSize, [0, 0, 0, 0], true);
                $img = "data:image/png;base64,$barcode";

                return response()->json([
                    'barcodeImg' => $img
                ]);
            }
        } catch (\Exception $e) {
            
        }
    }

    public function barcodePrint(Request $request){
        $barcodeSize = $request->barcodeSize;
        $productName = $request->productName;
        $qty = $request->qty;
        $mrp = $request->mrp;
        $barcode= $request->barcode;
        $printsize=$request->printsize;
      

    
    $pdf= PDF::loadView('admin.pages.barcode-generator.pdf.barcodes',compact('barcodeSize','productName',
    'qty','mrp','barcode','printsize'));
    $pdf->setOptions(['dpi'=>88,'defaultFont'=>'Courier']);

   
    switch($printsize){
        case '1_ups':
            $pdf->setPaper([25,0,249,165])->setWarnings(false);
            break;
            break;
        case '2_ups':
          
            $pdf->setPaper([22,0,498,165])->setWarnings(false);
            break;
        case '3_ups':
            break;
        case 'A4':
            $pdf->setPaper('a4', 'portrait')->setWarnings(false);
            
            break;
    }
    return $pdf->stream();

    // $pdf->setPaper('a4', 'landscape')->setWarnings(false);




        // return view('admin.pages.barcode-generator.pdf.barcodes');
    }


    public function pdfView(){

        return view('admin.pages.barcode-generator.pdf.barcodes1');


    }
}
