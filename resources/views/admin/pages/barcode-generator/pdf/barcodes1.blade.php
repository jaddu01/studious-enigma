<style>
    .barcode-display {
        width: auto;
        text-align: left;
        /* margin-left:20px; */
        height:auto;
    }
    .barcode-display div {
        text-align: center;
        width: 158px;
        font-size: 12px;
    }
 
  table tr  td{
    padding-right:20px;
  }
</style>
@inject('DNSID', App\Helpers\Milon\Barcode\DNS1D)

    <table>
        <tr>
            <td>
                <div class="barcode-display">
                    <div>
                        <u>Darbaar</u><br>
                        <b>Amul Ghee</b><br>
                        <b>Price : 100</b>
                    </div>
                    <img src="data:image/png;base64,{{ $DNSID->getBarcodePNG('12345678', 'C128', 2, 50, [0, 0, 0, 0], true) }}"
                        alt="barcode" id="barcode-sample-img" />
                </div>
            </td>

            <td>
                <div class="barcode-display">
                    <div>
                        <u>Darbaar</u><br>
                        <b>Amul Ghee</b><br>
                        <b>Price : 100</b>
                    </div>
                    <img src="data:image/png;base64,{{ $DNSID->getBarcodePNG('12345678', 'C128', 2, 50, [0, 0, 0, 0], true) }}"
                        alt="barcode" id="barcode-sample-img" />
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <div class="barcode-display">
                    <div>
                        <u>Darbaar</u><br>
                        <b>Amul Ghee</b><br>
                        <b>Price : 100</b>
                    </div>
                    <img src="data:image/png;base64,{{ $DNSID->getBarcodePNG('12345678', 'C128', 2, 50, [0, 0, 0, 0], true) }}"
                        alt="barcode" id="barcode-sample-img" />
                </div>
            </td>

            <td>
                <div class="barcode-display">
                    <div>
                        <u>Darbaar</u><br>
                        <b>Amul Ghee</b><br>
                        <b>Price : 100</b>
                    </div>
                    <img src="data:image/png;base64,{{ $DNSID->getBarcodePNG('12345678', 'C128', 2, 50, [0, 0, 0, 0], true) }}"
                        alt="barcode" id="barcode-sample-img" />
                </div>
            </td>
        </tr>

    </table>
{{-- <div class="main">
    <div class="barcode-display">
        <div>
            <u>Darbaar</u><br>
            <b>Amul Ghee</b><br>
            <b>Price : 100</b>
        </div>
        <img src="data:image/png;base64,{{ $DNSID->getBarcodePNG('12345678', 'C128', 2, 50, [0, 0, 0, 0], true) }}"
            alt="barcode" id="barcode-sample-img" />
    </div>

   


    <div class="barcode-display">
        <div>
            <u>Darbaar</u><br>
            <b>Amul Ghee</b><br>
            <b>Price : 100</b>
        </div>
        <img src="data:image/png;base64,{{ $DNSID->getBarcodePNG('12345678', 'C128', 2, 50, [0, 0, 0, 0], true) }}"
            alt="barcode" id="barcode-sample-img" />
    </div>
 
 
    

</div> --}}


 