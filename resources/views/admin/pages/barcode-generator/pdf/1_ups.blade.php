<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <style>
        .barcode-display {
            /* width: auto; */
            text-align: center;
            width: 11.02cm;
            /* margin-left:20px; */
            height: auto;
        }
    
        .barcode-display div {
            text-align: center;
            font-size: 8px;
        }
    
        table tr td {
            /* padding-right: 50px; */
        }
    
      
    </style>
</head>
<body>
    @inject('DNSID', App\Helpers\Milon\Barcode\DNS1D)

    {{-- 1 ups --}}
    @if ($printsize == '1_ups')
        <table>
            @foreach ($productName as $key => $value)
                @for ($i = 0; $i < $qty[$key]; $i++)
                    <tr>
                        <td>
                            <div class="barcode-display">
                                <div>
                                    <u>Darbaar Mart</u><br>
                                    <b>{{ $value }}</b><br>
                                    <b>MRP : {{ $mrp[$key] }}</b>
                                </div>
                                <img src="data:image/png;base64,{{ $DNSID->getBarcodePNG($barcode[$key], 'C128', 2, $barcodeSize, [0, 0, 0, 0], true) }}"
                                    alt="barcode" id="barcode-sample-img" /><br>
                            </div>
                        </td>
                    </tr>
                @endfor
            @endforeach
        </table>
    @endif

</body>
</html>



