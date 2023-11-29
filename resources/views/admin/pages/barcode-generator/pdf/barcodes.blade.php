<style>
    .barcode-display {
        width: auto;
        text-align: left;
        /* margin-left:20px; */
        height: auto;



    }

    .barcode-display div {
        text-align: center;
        width: 230px;
        font-size: 12px;
    }

    table tr td {
        padding-right: 50px;
    }
</style>
@inject('DNSID', App\Helpers\Milon\Barcode\DNS1D)

@if ($printsize == '2_ups')
    <table>
        @foreach ($productName as $key => $value)
            @for ($i = 0; $i < $qty[$key] / 2; $i++)
                <tr>
                    <td>
                        <div class="barcode-display">
                            <div>
                                <u>Darbaar Mart</u><br>
                                <b>{{ $value }}</b><br>
                                <b>Mrp : {{ $mrp[$key] }}</b>
                            </div>
                            <img src="data:image/png;base64,{{ $DNSID->getBarcodePNG($barcode[$key], 'C128', 2, $barcodeSize, [0, 0, 0, 0], true) }}"
                                alt="barcode" id="barcode-sample-img" /><br>
                        </div>
                    </td>
                    <td>
                        <div class="barcode-display">
                            <div>
                                <u>Darbaar Mart</u><br>
                                <b>{{ $value }}</b><br>
                                <b>Mrp : {{ $mrp[$key] }}</b>
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
