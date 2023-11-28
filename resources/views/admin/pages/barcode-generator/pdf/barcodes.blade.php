<style>
    .barcode-display {
        width: auto;
        text-align: left;
        /* margin-top: 10px; */
        margin-bottom: 30px;

        

    }

    .barcode-display div {
        text-align: center;
        width: 230px;
    }

    .right-side {
        float: right;
    }

    .clearfix::after {
        content: "";
        clear: both;
        display: table;
    }
</style>
@inject('DNSID', App\Helpers\Milon\Barcode\DNS1D)
{{-- {{-- <div style="padding:20px;"> --}}
    <div style="padding-bottom:40px;">
@foreach ($productName as $key=>$value)

<div class="clearfix">
    @for ($i = 0; $i < ($qty[$key]); $i++)
    <div class="barcode-display {{($i%2==0)?'right-side':''}}">
        <div>
            <u>Darbaar</u><br>
            <b>{{$value}}</b><br><br>
            <b>Price : {{$mrp[$key]}}</b>
        </div>
        <img src="data:image/png;base64,{{ $DNSID->getBarcodePNG($barcode[$key], 'C128', 2, $barcodeSize, [0, 0, 0, 0], true) }}"
            alt="barcode" id="barcode-sample-img" /><br>
    </div>
    @endfor

@endforeach
    

</div>
