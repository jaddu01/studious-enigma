const count = (selector) => {
        return $(`#${selector} tr`).length;
}

$(document).ready(function () {

        $("#product_Details_Tbody").on("click", ".product_add_new_field_row_btn", function () {
                let isSelectedWithGst = ($("#gstSelected :selected").val()==1?true:false);
                
                setTblData(null, isSelectedWithGst);
                $(this).addClass('display-hide');
                $(this).parent().find('.product_delete_field_row_btn').removeClass('display-hide');
                increaseNo();
                showTblResult();

        }).on("click", ".product_delete_field_row_btn", function () {
                const is_add_btn_hidden = $(this).parent().find('.product_add_new_field_row_btn').hasClass('display-hide');
                $(this).parent().parent().parent().remove();
                let cnt = count('product_Details_Tbody');
                increaseNo();
                if (!is_add_btn_hidden) {
                        $("#product_Details_Tbody tr:nth-last-child(2)").find('.product_add_new_field_row_btn').removeClass('display-hide');
                }
                if (cnt == 2) {
                        $("#product_Details_Tbody:first").find('.product_delete_field_row_btn').addClass('display-hide');
                        $("#product_Details_Tbody:first").find('.product_add_new_field_row_btn').removeClass('display-hide');
                }
                showTblResult();



        });

        $("#additional_charge_body").on("click",".additional_charge_row_add_btn",function(){
                // alert("working");
                setAdditionalChargeTblRow();
                $(this).addClass('display-hide');
                $(this).parent().find('.additional_charge_row_delete_btn').removeClass('display-hide');
                increaseAdditionalChargeRowNo();
                showAdditionalChargeTblResult();
        }).on("click",".additional_charge_row_delete_btn",function(){
                const is_add_btn_hidden = $(this).parent().find('.additional_charge_row_add_btn').hasClass('display-hide');
                $(this).parent().parent().parent().remove();
                let cnt = count('additional_charge_body');
                increaseAdditionalChargeRowNo();
                if (!is_add_btn_hidden) {

                        $("#additional_charge_body tr:nth-last-child(2)").find('.additional_charge_row_add_btn').removeClass('display-hide');

                }
                if (cnt == 2) {
                        $("#additional_charge_body:first").find('.additional_charge_row_delete_btn').addClass('display-hide');
                        $("#additional_charge_body:first").find('.additional_charge_row_add_btn').removeClass('display-hide');
                }
                showAdditionalChargeTblResult();

        }).on("blur",".AdditionalChargeValue",function(){
                showAdditionalChargeTblResult();

        });

        const increaseNo = () => {
                $("#product_Details_Tbody tr").each(function (indx, value) {
                        $(this).find(".product_no").text(indx + 1);
                       
                })
        }

        const increaseAdditionalChargeRowNo = () => {
                $("#additional_charge_body tr").each(function (inx, value) {
                        $(this).find(".additional_charge_number").text(inx + 1);
                })
        }

        //additional charge

        $("#additionalChargeBtn").click(function () {
                $('#addionalCharges').collapse('show');
                if (count('additional_charge_body') == 1) {
                        $("#additionalChargeResult").removeClass('display-hide');
                        setAdditionalChargeTblRow(1);
                }

        });

});


