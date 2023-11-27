<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Milon\Barcode\DNS1D;

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
}
