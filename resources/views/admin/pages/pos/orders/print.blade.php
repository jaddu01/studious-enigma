<?php //echo '<pre>'; print_r($orders_details); echo '</pre>'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- <link rel="stylesheet" href="style.css"> -->
        <title>Bill Invoice - {{$orders_details->order_code}}</title>
        <style type="text/css">
            body{
                padding: 0;
                margin: 0;
                width: 100%;
            }
            #invoice-POS {
            /* box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
               padding: 1mm;*/
              margin: 0;
              padding: 10px;
              width: 79mm;
              background: #FFF;
               word-wrap: break-word;
            }
            #invoice-POS td{
                padding: 0px;
                margin: 0x;
            }

            #invoice-POS ::selection {
              background: #f31544;
              color: #FFF;
            }
            #invoice-POS ::moz-selection {
              background: #f31544;
              color: #FFF;
            }
            #invoice-POS h1 {
              font-size: 1.5em;
              color: #222;
            }
            #invoice-POS h2 {
              font-size: 0.9em;
            }
            #invoice-POS h3 {
              font-size: 1.2em;
              font-weight: 300;
              line-height: 2em;
            }
            #invoice-POS p {
              font-size: 0.8em;
              color: #000;
              /*line-height: 1.2em;*/
              padding: 0px;
               margin: 1px;
            }
            #invoice-POS #top, #invoice-POS #mid,#invoice-POS #top-mid, #invoice-POS #bot {
              /* Targets all id with 'col-' */
              border-bottom: 1px solid #d2d2d2;
            }
            #invoice-POS #top {
              min-height: 70px;
            }
            #invoice-POS #mid {
              min-height: 80px;
            }
            #invoice-POS #bot {
              min-height: 50px;
            }
            #invoice-POS #top .logo {
              height: 60px;
              width: 60px;
              background: url(<?php  echo url('storage/app/public/upload/logo.png') ?>) no-repeat;
              background-size: 60px 60px;
            }
            #invoice-POS .info {
              display: block;
              margin-left: 0;
            }
            #invoice-POS .title {
              float: right;
            }
            #invoice-POS .title p {
              text-align: right;
            }
            #invoice-POS table {
              width: 100%;
              border-collapse: collapse;
            }
            #invoice-POS .border {
              border-top: 1px solid #d2d2d2;
              border-bottom: 1px solid #d2d2d2;
            }
            #invoice-POS .tabletitle {
              font-size: 0.5em;
              border-top: 1px solid #d2d2d2;
              border-bottom: 1px solid #d2d2d2;
            }
            #invoice-POS .service {
              /*border-bottom: 1px solid #EEE;*/
            }
            #invoice-POS .item {
              width: 24mm;
            }
            #invoice-POS .itemtext {
              font-size: 0.6em;
            }
            #invoice-POS #legalcopy {
              margin-top: 5mm;
            }
            .text-center{
              text-align: center;
            }
        </style>
    </head>
    <body>
        <div id="invoice-POS">
            <center id="top">
              <div class="logo"></div>
            </center>
            <div id="mid-bottom">
                <div class="info text-center border">
                    <p><b>TAX INVOICE</b></br></p>
                </div>
            </div>
            <center id="top-mid">
                <div class="info"> 
                    <p> 
                        DARBAAR BROTHERS INDIA PRIVATE LIMITED</br>
                        {{$orders_details->vendor->address}}</br>
                        </br>
                        GSTIN : 08AAFCD4983R1ZR</br>
                        FSSAI NO   : 12220009000492</br>
                         </br>
                        Call :- +{{$orders_details->vendor->phone_code.'-'.$orders_details->vendor->phone_number}}</br>
                        Email :- {{$orders_details->vendor->email}}</br>
                    </p>
                </div><!--End Info-->
            </center><!--End InvoiceTop-mis-->
            <div id="mid">
                <div class="info">
                    <p> 
                        <b>Bill No.</b> : {{$orders_details->order_code}}</br>
                        <b>Biller</b>   : {{$orders_details->shopper->name}}</br>
                        <b>Date</b>   : {{date('d/m/Y',strtotime($orders_details->created_at))}}</br>
                        <b>Time</b>   : {{date('H:i:s',strtotime($orders_details->created_at))}}</br>
                    </p>
                </div>
            </div><!--End Invoice Mid-->
            <div id="mid-bottom">
                <div class="info">
                    <p>
                        <b>Customer Name</b> : -{{$orders_details->user->name}}
                        @if(isset($orders_details->user->address) && $orders_details->user->address!='')
                            @php
                                $address = json_decode($orders_details->user->address);
                            @endphp
                            <br/><b>Customer Address</b> : - {{$address->address}}
                        @endif
                        <br/><b>Customer Number</b> : - {{$orders_details->user->phone_number}}
                    </p>
                </div>
            </div><!--End Invoice Mid-bottom-->
            <div id="bot">
                <div id="table">
                    <table>
                        <tr class="tabletitle">
                            <td class="sr no">Sr No.</th>
                            <td class="sku">HSN</th>
                            <td class="item">Particulars</th>
                            <td class="Hours">Qty.</th>
                            <td class="Rate">Rate</th>
                            <td class="Value">Value</th>
                        </tr>
                        @if(isset($orders_details->ProductOrderItem) && !empty($orders_details->ProductOrderItem))
                            @php 
                            $total_qty = 0;
                            $i = 0;
                            $total_amount = 0
                            @endphp
                            @foreach($orders_details->ProductOrderItem as $key=>$value)
                                @php
                                $data = json_decode($value->data);
                                $total_qty = $total_qty+$data->vendor_product->qty;
                                $total_amount = $total_amount+$data->vendor_product->price;
                                $i++;
                                @endphp
                                <tr class="service">
                                    <td class="tableitem"><p class="itemtext">{{$i}}).</p></td>
                                    <td class="tableitem"><p class="itemtext">{{(isset($data->vendor_product->product->hsn_code) && $data->vendor_product->product->hsn_code!='') ? $data->vendor_product->product->hsn_code : '----'}}</p></td>
                                    <td class="tableitem"><p class="itemtext">{{$data->vendor_product->product->name}}</p></td>
                                    <td class="tableitem"><p class="itemtext">{{$data->vendor_product->qty}}</p></td>
                                    <td class="tableitem"><p class="itemtext">{{round($data->vendor_product->price/$data->vendor_product->qty,2)}}</p></td>
                                    <td class="tableitem"><p class="itemtext">{{$data->vendor_product->price}}</p></td>
                                </tr>
                            @endforeach
                        @endif
                        <tr class="tabletitle">
                            <td colspan="2">
                            <strong>Items :- {{$i}}  </strong>| 
                        </td>
                        <td colspan="2">
                            <strong>Qty :- {{$total_qty}} </strong>| 
                        </td>
                        <td colspan="2">
                            <strong>Total :- {{number_format((float)$total_amount, 2, '.', '')}}</strong>
                        </td>
                            </tr>
                    </table>
                </div><!--End Table-->
                <div>
                    <p class="text-center"><------- GST BREAKUPS -------></p>
                </div>
                <div id="table">
                    <table>
                        <tr class="tabletitle">
                            <td class="sr no"><h2>GST IND</h2></td>
                            <td class="sku"><h2>Taxable Amt.</h2></td>
                            <td class="item"><h2>CGST</h2></td>
                            <td class="Hours"><h2>SGST.</h2></td>
                            <td class="Rate"><h2>CESS</h2></td>
                            <td class="Value"><h2>Total Amt.</h2></td>
                        </tr>
                        @if(isset($orders_details->ProductOrderItem) && !empty($orders_details->ProductOrderItem))
                            @php 
                            $total_qty = 0;
                            $i = 0;
                            $total_gst = 0;
                            $total_cgst = 0;
                            $total_sgst = 0;
                            $total_taxable_amount = 0;
                            $total_amount = 0;

                            @endphp
                            @foreach($orders_details->ProductOrderItem as $key=>$value)
                                @php
                                $data = json_decode($value->data);
                                $gst = $data->vendor_product->product->gst;
                                $gst_amount = ($data->vendor_product->price/100)*$gst;
                                $taxable_amount = $data->vendor_product->price-$gst_amount;
                                $cgst = $gst_amount/2;
                                $sgst = $gst_amount/2;
                                $total_gst = $total_gst+$gst_amount;
                                $total_cgst = $total_cgst+$cgst;
                                $total_sgst = $total_cgst+$sgst;
                                $total_taxable_amount = $total_taxable_amount+$taxable_amount;
                                $total_amount = $total_amount+$data->vendor_product->price;

                                $total_qty = $total_qty+$data->vendor_product->qty;
                                $i++;
                                @endphp
                                <tr class="service">
                                    <td class="tableitem"><p class="itemtext">{{$i}}).</p></td>
                                    <td class="tableitem"><p class="itemtext">{{number_format((float)$taxable_amount, 2, '.', '')}}</p></td>
                                    <td class="tableitem"><p class="itemtext">{{number_format((float)$cgst, 2, '.', '')}}</p></td>
                                    <td class="tableitem"><p class="itemtext">{{number_format((float)$sgst, 2, '.', '')}}</p></td>
                                    <td class="tableitem"><p class="itemtext">----</p></td>
                                    <td class="tableitem"><p class="itemtext">{{$data->vendor_product->price}}</p></td>
                                </tr>
                            @endforeach
                        @endif

                    </table>
                </div><!--End Table-->
                <div id="legalcopy">
                    <p class="legal text-center"><strong>Thanks for your purchase!</strong>  
                    </p>
                </div>
            </div>
        </div>
        <?php if($is_print)  {?>
            <button id="btnPrint" class="hidden-print">Print</button>
            <!-- <script src="script.js"></script> -->
            <script type="text/javascript">
                const $btnPrint = document.querySelector("#btnPrint");
                $btnPrint.addEventListener("click", () => {
                    window.print();
                });
            </script>
        <?php } ?>
    </body>
</html>