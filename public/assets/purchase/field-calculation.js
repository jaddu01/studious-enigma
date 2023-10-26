$(document).ready(function(){
    $("#product_Details_Tbody").on("blur",".product-field",function(){
        const self = $(this);
        calculation(self);
        showTblResult();
    }).on("keyup",".product-field",function(){
        const self = $(this);
        calculation(self);
        showTblResult();
    });


    function calculation(self){
        const fieldValue = getFieldValue(self);
        const net_rate = NetRateCalculation(fieldValue.unit_cost,fieldValue.gst);
        const gst_amount = GstAmountCalculation(fieldValue.qty,fieldValue.unit_cost,fieldValue.gst);
        const margin = MarginCalculation(fieldValue.mrp,fieldValue.net_rate);
        const total = TotalAmountCalculation(fieldValue.net_rate,fieldValue.qty);

        setFieldValue(self,net_rate,gst_amount,margin,total);
    }
    function getFieldValue(self){
        const parent = self.parent().parent();
        const  unit_cost = parent.find('.product_unit_cost').val();
        const qty = parent.find('.product_qty').val();
        const net_rate = parent.find('.product_net_rate').val();
       const gst = parent.find('.product_gst_percentage').attr('amount');
       const mrp = parent.find('.product_mrp').val();

    //    console.log('Gst:',gst);

       const data ={
        "unit_cost":unit_cost,
        "qty":qty,
        "net_rate":net_rate,
        "gst":parseFloat(gst),
        "mrp":mrp
       }

       return data;
    }

    const setFieldValue=(self,net_rate,gst_amount,margin,total)=>{
        const parent = self.parent().parent();
        parent.find('.product_net_rate').val(net_rate);
        parent.find('.product_gst_amount').text(gst_amount);
        parent.find('.product_margin').val(margin);
        parent.find('.product_total').val(total);
    }


    const NetRateCalculation=(unit_cost,gst)=>{
        const NetRate =  unit_cost*(gst+100)/100;
        // console.log("Result",(gst+100))
        // console.log("Net Rate:",NetRate);
        // console.log(`GST:${gst}, Unit Cost:${unit_cost}`)
        return twoDecimalPalace(NetRate);
    }

    const GstAmountCalculation=(qty,unit_cost,gst)=>{
        const gstAmount = (qty*unit_cost)*(gst/100);
        return twoDecimalPalace(gstAmount);
    }

    const MarginCalculation=(mrp,net_rate)=>{
        const margin = Math.abs(net_rate-mrp);
        return twoDecimalPalace(margin);
    }

    const TotalAmountCalculation = (net_rate,qty)=>{
        const total = net_rate*qty;
        return twoDecimalPalace(total);
    }




    const twoDecimalPalace =(value)=>{
        return value.toFixed(2)
    }
});