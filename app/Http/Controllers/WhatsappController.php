<?php

/**
 * @Author: Abhi Bhatt
 * @Date:   2022-05-25 00:45:41
 * @Last Modified by:   Abhi Bhatt
 * @Last Modified time: 2022-05-31 00:26:24
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use Session;
use PDF;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\Product;
use App\ProductTranslation;
use App\ProductOrder;
use App\ProductOrderItem;
use App\User;
use App\Zone;
use Anam\PhantomMagick\Converter;
use Imagick;
use Storage;

class WhatsappController extends Controller
{
	public function __construct() {
        $this->apiUrl = 'https://betablaster.in/api/';
        $this->accessToken = '5c5321aaf715ee14825f92251b6e3b6f';
        $this->instance_id = '628A5391953D9';
    }

    function createInstance() {
    	$instance = '';
    	$url = $this->apiUrl.'createinstance.php?access_token='.$this->accessToken;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		$response = curl_exec($ch);
		if (curl_errno($ch)) {
		    $error_msg = curl_error($ch);

		}
		curl_close($ch);
		if($response!='') {
			$response = json_decode($response);
			if($response->status=='success') {
				$instance = $response->instance_id;
			}
		}
		return $instance; 
    }

    function sendFile($id,$phone_number) {
    	//$fileArray = $this->getPdf($id); // for old invoice //
    	$fileArray = $this->getOrderPdf($id); // for pos order invoice //
    	$instance = $this->instance_id;
    	$url = $this->apiUrl.'send.php?number='.$phone_number.'&type=media&message=Darbaarmart%20invoice&media_url='.$fileArray['file_url'].'&filename='.$fileArray['file_name'].'&instance_id='.$this->instance_id.'&access_token='.$this->accessToken;
    	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		$response = curl_exec($ch);
		if (curl_errno($ch)) {
		    $error_msg = curl_error($ch);
		}
		curl_close($ch);
		if($response!='') {
			$response = json_decode($response);
			/*echo '<pre>';
			print_r($response);
			echo '</pre>';*/
		}	
		return;
    }

    function sendText($phone_number) {
    	$instance = $this->instance_id;
    	$url = $this->apiUrl.'send.php?number='.$phone_number.'&type=text&message=test%20message&instance_id='.$instance.'&access_token='.$this->accessToken;
    	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		$response = curl_exec($ch);
		if (curl_errno($ch)) {
		    $error_msg = curl_error($ch);
		}
		curl_close($ch);
		if($response!='') {
			$response = json_decode($response);
			/*echo '<pre>';
			print_r($response);
			echo '</pre>';*/
		}	
		return;
    }

    function getPdf($id){
    	$file = '';
    	$orders_details = ProductOrder::with(['ProductOrderItem','vendor','driver','shopper','User','zone','PaymentMode'])->findOrFail($id);
    	
    	$pdf = PDF::loadView('admin.pages.order.pdfdownload', compact('orders_details'));
        $path_to_pdf= "public/invoices/".$orders_details->order_code."-invoice.pdf";
        Storage::put($path_to_pdf, $pdf->output());
        file_put_contents($path_to_pdf, $pdf->output());
        $file = url("public/invoices/".$orders_details->order_code."-invoice.pdf");
        unlink(storage_path('app/public/invoices/'.$orders_details->order_code.'-invoice.pdf'));
        return ['file_url'=>$file,'file_name'=>$orders_details->order_code.'-invoice.pdf'];
    }

    function getOrderPdf($id){
    	$file = '';
    	$is_print = false;
    	$customPaper = array(0,0,567.00,283.80);
    	$orders_details = ProductOrder::with(['ProductOrderItem','vendor','driver','shopper','User','zone','PaymentMode'])->findOrFail($id);
    	if(isset($orders_details)){
            $orders_details->user = User::where('id',$orders_details->user_id)->first();
        }
        $orders_details->id = $id;
    	
    	$pdf = PDF::loadView('admin.pages.pos.orders.print', compact('orders_details','id','is_print'))->setPaper($customPaper, 'landscape');
        $path_to_pdf= "public/invoices/".$orders_details->order_code."-order-invoice.pdf";
        Storage::put($path_to_pdf, $pdf->output());
        file_put_contents($path_to_pdf, $pdf->output());
        $file = url("public/invoices/".$orders_details->order_code."-order-invoice.pdf");
        unlink(storage_path('app/public/invoices/'.$orders_details->order_code.'-order-invoice.pdf'));
        return ['file_url'=>$file,'file_name'=>$orders_details->order_code.'-order-invoice.pdf'];
    }
}