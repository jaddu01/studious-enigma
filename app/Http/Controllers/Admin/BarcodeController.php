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

    public function barcodePrint(Request $request)
    {
        $barcodeSize = $request->barcodeSize;
        $productName = $request->productName;
        $qty = $request->qty;
        $mrp = $request->mrp;
        $barcode = $request->barcode;
        $printsize = $request->printsize;



  

        switch ($printsize) {
            case '1_ups':
                $pdf = PDF::loadView('admin.pages.barcode-generator.pdf.1_ups', compact(
                    'barcodeSize',
                    'productName',
                    'qty',
                    'mrp',
                    'barcode',
                    'printsize'
                ));
                $pdf->setOptions(['dpi' => 88, 'defaultFont' => 'Courier']);
                $pdf->setPaper([70, 15, 311, 183])->setWarnings(false);
                break;
                break;
            case '2_ups':
                $pdf = PDF::loadView('admin.pages.barcode-generator.pdf.2_ups', compact(
                    'barcodeSize',
                    'productName',
                    'qty',
                    'mrp',
                    'barcode',
                    'printsize'
                ));
                $pdf->setOptions(['dpi' => 150, 'defaultFont' => 'Courier']);
                $pdf->setPaper([43, 14, 311, 150])->setWarnings(false);
                
                break;
            case '3_ups':
                break;
            case 'A4_84':
                $pdf = PDF::loadView('admin.pages.barcode-generator.pdf.a4_85', compact(
                    'barcodeSize',
                    'productName',
                    'qty',
                    'mrp',
                    'barcode',
                    'printsize'
                ));
                // $pdf->setOptions(['dpi' => 88, 'defaultFont' => 'Courier']);
                $pdf->setOptions(['dpi' =>203, 'defaultFont' => 'Courier']);
                $pdf->setPaper('a4', 'portrait')->setWarnings(false);
                // $pdf->setPaper([22, 0, 130, 31])->setWarnings(false);
                break;
            case 'A4_65':
                // $pdf->setPaper('a4', 'portrait')->setWarnings(false);
                break;
        }
        return $pdf->stream();

        // $pdf->setPaper('a4', 'landscape')->setWarnings(false);




        // return view('admin.pages.barcode-generator.pdf.barcodes');
    }


    public function pdfView()
    {

        return view('admin.pages.barcode-generator.pdf.barcodes1');
    }
}
