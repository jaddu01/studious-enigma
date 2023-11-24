$(document).ready(function () {
    let selectedGst = 1;

    $("#product_Details_Tbody").on("blur", ".product-field", function () {
        const self = $(this);
        calculation(self);
        showTblResult();
    })
    // .on("keyup",".product-field",function(){
    //     const self = $(this);
    //     calculation(self);
    //     showTblResult();
    // });
    //click to gst radio button
    $("#gstSelected").on('change',function () {
        selectedGst = $(this).val();
        console.log("selected gst:", selectedGst);
        $("#product_Details_Tbody").trigger('blur');
    })


    function calculation(self) {
        const fieldValue = getFieldValue(self);
        const net_rate = NetRateCalculation(fieldValue.unit_cost, fieldValue.gst);
        const gst_amount = GstAmountCalculation(fieldValue.net_rate, fieldValue.unit_cost);
        const margin = MarginCalculation(fieldValue.mrp, fieldValue.net_rate);
        const total = TotalAmountCalculation(fieldValue.net_rate, fieldValue.qty);
        const unit_cost = UnitCostCalculation(fieldValue.net_rate, fieldValue.gst);


        setFieldValue(self, net_rate, gst_amount, margin, total, unit_cost);
    }
    function getFieldValue(self) {
        const parent = self.parent().parent();
        const unit_cost = parent.find('.product_unit_cost').val();
        const qty = parent.find('.product_qty').val();
        const net_rate = parent.find('.product_net_rate').val();
        let gst = parent.find('.product_gst_percentage').attr('amount');
        gst = (selectedGst == 1 || selectedGst == '1') ? gst : 0;
        console.log("gst:",gst);
        const mrp = parent.find('.product_mrp').val();

        //    console.log('Gst:',gst);

        const data = {
            "unit_cost": unit_cost,
            "qty": qty,
            "net_rate": net_rate,
            "gst": parseFloat(gst),
            "mrp": mrp,
        }

        return data;
    }


    const setFieldValue = (self, net_rate, gst_amount, margin, total, unit_cost) => {
        
        const parent = self.parent().parent();
        parent.find('.product_gst_amount').text(gst_amount);
        parent.find('.product_gst_percentage').text(gst_amount);
        parent.find('.product_margin').val(margin);
        parent.find('.product_total').val(total);
        parent.find('.product_net_rate').val(net_rate);
        if (parent.find('.product_net_rate').val() == 0 || parent.find('.product_net_rate').val() == '') {
            parent.find('.product_net_rate').val(net_rate);
        }

        if (parent.find('.product_unit_cost').val() == 0 || parent.find('.product_unit_cost').val() == '') {
            parent.find('.product_unit_cost').val(unit_cost);

        }

    }

    const UnitCostCalculation = (net_rate, gst) => {
        const unit_cost = (net_rate * 100) / (100 + gst);
        return unit_cost;
    }
    const NetRateCalculation = (unit_cost, gst) => {
        const NetRate = unit_cost * (gst + 100) / 100;
        return twoDecimalPalace(NetRate);
    }

    const GstAmountCalculation = (net_rate, unit_cost) => {
        // const gstAmount = (qty*unit_cost)*(gst/100);
        const gstAmount = Math.abs(net_rate - unit_cost)

        return twoDecimalPalace(gstAmount);
    }

    const MarginCalculation = (mrp, net_rate) => {
        const profit = Math.abs(net_rate - mrp);
        const margin = (profit * 100) / mrp;
        console.log("net_rate:",net_rate);
        console.log("mrp:",mrp);
        console.log("marget:",margin);

        return twoDecimalPalace(margin);
    }

    const TotalAmountCalculation = (net_rate, qty) => {
        const total = net_rate * qty;
        return twoDecimalPalace(total);
    }




    const twoDecimalPalace = (value) => {
        return value.toFixed(2)
    }
});