$(document).ready(function () {
    function convertArrayToJson(arr) {
        data = {};
        $.each(arr, function () {
            data[this.name] = this.value;
        });
        return data;
    }

    $("#saveOnlyBtn").click(function (e) {
        e.preventDefault();

        const formElementData = $("#supplier_form").not("#supplier_id").serializeArray();
        const data= collectData(formElementData);
console.log(data)
        saveData("save_only",data);
    });

    function collectData(formElementData){
        const formData = convertArrayToJson(formElementData);
        // const formData = formElementData;
        
        
        formData.products = [];
        formData.additional_charges=[];
        formData.total_amount=parseFloat($("#totalAmount").text());
        formData.total_additional_charge=parseFloat($("#totalAdditionalCharges").text());
        formData.net_amount = parseFloat($("#NetAmount").text());
    
        $("#product_Details_Tbody tr").not("#totalResult").each(function (indx, value) {
            let data = {
                product_id:$(this).find(".select2-product :selected").val(),
                barcode:$(this).find(".product_barcode").val(),
                qty:parseInt($(this).find(".product_qty").val()),
                free_qty:parseInt($(this).find(".product_free_qty").val()),
                unit_cost:parseFloat($(this).find(".product_unit_cost").val()),
                net_rate:parseFloat($(this).find(".product_net_rate").val()),
                mrp:parseFloat($(this).find(".product_mrp").val()),
                selling_price:parseFloat($(this).find(".product_selling_price").val()),
                gst_amount:parseFloat($(this).find(".product_gst_amount").text()),
                margin:parseFloat($(this).find(".product_margin").val()),
                total:parseFloat($(this).find(".product_total").val()),
            }
            formData.products.push(data);
            formData.additional_charges.push()
        })
        $("#additional_charge_body tr").not("#additionalChargeResult").each(function(indx,value){
            let data ={
                charge_name:$(this).find(".AdditionalCharge").val(),
                charge_value:$(this).find(".AdditionalChargeValue").val(),
            }
            formData.additional_charges.push(data);
        })
    

        return formData;
    }
    function saveData(type,data_) {
        ajxHeader();
    
        $.ajax({
            url: saveOnlyUrl,
            method: 'post',
            data:{type:type, data:data_},
            beforeSend: function () {

            },
            success: function (res) {
                location.reload();
            }
        })
    }
});