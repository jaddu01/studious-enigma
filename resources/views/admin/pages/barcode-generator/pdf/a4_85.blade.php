<style>
    .barcode-display {
        width: 46mm;
        text-align: left;
        height: 11mm;



    }

    .barcode-display div {
        text-align: center;
        width: 30mm;
        font-size: 8px;
    }

    /* table tr td {
        padding-right: 50px;
    } */


</style>
@inject('DNSID', App\Helpers\Milon\Barcode\DNS1D)


{{-- a4_85 --}}
@if ($printsize == 'A4_84')
    <table>
        @foreach ($productName as $key => $value)
            @for ($i = 0; $i < $qty[$key] / 4; $i++)
                <tr>
                    <td>
                        <div class="barcode-display a4_size_display">
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
                        <div class="barcode-display a4_size_display">
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
                        <div class="barcode-display a4_size_display">
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
                        <div class="barcode-display a4_size_display">
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
