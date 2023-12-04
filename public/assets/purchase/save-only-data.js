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
        if(!validation()){
            const formElementData = $("#supplier_form").not("#supplier_id").serializeArray();
            const data = collectData(formElementData);
            saveData("save_only", data);
        }
     
    });

    $(".validation-input").on("input",function(e){
        console.log("date:",$(this).val())
        if($(this).val().length > 0){
            $(this).removeClass('is-invalid');
            $(this).parent().find('.error').addClass('display-hide');
        }
    }).on('change',function(){
        console.log("date:",$(this).val())
        if($(this).val().length > 0){
            $(this).removeClass('is-invalid');
            $(this).parent().find('.error').addClass('display-hide');
        }
    });
    const validation=()=>{
        if($("#bill_date").val()==''){
            $("#bill_date").addClass('is-invalid');
            $("#bill-date-error").removeClass('display-hide');
            document.getElementById('bill_date').scrollIntoView();
            
        }else{
            $("#bill_date").removeClass('is-invalid');
            $("#bill-date-error").addClass('display-hide');
        }
        return true;
    }
    //Checked to online radio button
    $("#cashMode").click(function () {
        const isChecked = $(this).is(':checked');
        if (isChecked) {
            $("#paymentDateSection").addClass('display-hide');
        }
    });

    //Checked to online radio button
    $("#onlineMode").click(function () {
        const isChecked = $(this).is(':checked');
        if (isChecked) {
            $("#paymentDateSection").removeClass('display-hide');
        }
    });

    //Checked to cheque radio button
    $("#chequeMode").click(function () {
        const isChecked = $(this).is(':checked');
        if (isChecked) {
            $("#paymentDateSection").removeClass('display-hide');
        }
    });



    // Click to Save & Payment Button
    // $("#saveAndPaymentBtn").click(function (e) {
    //     e.preventDefault();

    //     $("#supplier_form").valid();

    //     $("#supplier_form").submit();

    // })

    $("#saveAndPaymentBtn").click(function (e) {

        e.preventDefault();

        const numberOfAddedProductRow = $("#product_Details_Tbody >tr").length;
        const totalProductPrice = $("#total").attr('aria-valuetext');

        if (numberOfAddedProductRow > 1 && totalProductPrice > 0) {
            $("#paymentModal").modal('show');
            $("#paymentform").find('#amount').val(parseFloat($("#NetAmount").text()));

        } else {

            errorMsg("Please Add Minimum one product");
            document.getElementById("gstSelected").scrollIntoView();
        }


    });

    //Click to modal save button
    $("#paymentModal").find("#saveBtn").click(function () {

        const formElementData = $("#supplier_form").not("#supplier_id").serializeArray();
        const PaymentFormData = $("#paymentform").serializeArray();
        const payementData = convertArrayToJson(PaymentFormData);
        const data = collectData(formElementData);
        const data_ = { ...payementData, ...data }
        saveData("save_with_payment", data_);

    });



    function collectData(formElementData) {
        const formData = convertArrayToJson(formElementData);

        formData.products_details = [];
        formData.additional_charges = [];
        formData.total_amount = parseFloat($("#totalAmount").text());
        formData.total_additional_charge = parseFloat($("#totalAdditionalCharges").text());
        formData.net_amount = parseFloat($("#NetAmount").text());

        $("#product_Details_Tbody tr").not("#totalResult").each(function (indx, value) {
            let data = {
                product_id: $(this).find(".select2-product :selected").val(),
                barcode: $(this).find(".product_barcode").val(),
                qty: parseInt($(this).find(".product_qty").val()),
                free_qty: parseInt($(this).find(".product_free_qty").val()),
                unit_cost: parseFloat($(this).find(".product_unit_cost").val()),
                net_rate: parseFloat($(this).find(".product_net_rate").val()),
                mrp: parseFloat($(this).find(".product_mrp").val()),
                selling_price: parseFloat($(this).find(".product_selling_price").val()),
                gst_amount: parseFloat($(this).find(".product_gst_amount").text()),
                margin: parseFloat($(this).find(".product_margin").val()),
                measurement_class: $(this).find(".select2-measurementclass :selected").val(),

                total: parseFloat($(this).find(".product_total").val()),
            }
            formData.products_details.push(data);
            formData.additional_charges.push()
        })
        $("#additional_charge_body tr").not("#additionalChargeResult").each(function (indx, value) {
            let data = {
                charge_name: $(this).find(".AdditionalCharge").val(),
                charge_value: $(this).find(".AdditionalChargeValue").val(),
            }
            formData.additional_charges.push(data);
        })

        return formData;
    }

    function saveData(type, data_) {
        ajxHeader();

        $.ajax({
            url: saveOnlyUrl,
            method: 'post',
            data: { type: type, data: data_ },
            beforeSend: function () {
            },
            success: function (res) {
                location.href = view_purchase_url;
            }
        })
    }
});