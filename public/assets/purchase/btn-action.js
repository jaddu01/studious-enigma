const count = () => {
        return $("#product_Details_Tbody tr").length;

}
$(document).ready(function () {

     
        $("#product_Details_Tbody").on("click", ".product_add_new_field_row_btn", function () {
                setTblData();
                $(this).addClass('display-hide');
                $(this).parent().find('.product_delete_field_row_btn').removeClass('display-hide');
                increaseNo();
                showTblResult();

        }).on("click", ".product_delete_field_row_btn", function () {
                const is_add_btn_hidden = $(this).parent().find('.product_add_new_field_row_btn').hasClass('display-hide');
                $(this).parent().parent().parent().remove();
                let cnt = count();
                increaseNo();
                if(!is_add_btn_hidden){
                        
                        $("#product_Details_Tbody tr:nth-last-child(2)").find('.product_add_new_field_row_btn').removeClass('display-hide');

                }
                if(cnt==2){
                        $("#product_Details_Tbody:first").find('.product_delete_field_row_btn').addClass('display-hide');
                        $("#product_Details_Tbody:first").find('.product_add_new_field_row_btn').removeClass('display-hide');
                }
                // console.log(is_add_btn_hidden)
               



        });

        const increaseNo =()=>{
                $("#product_Details_Tbody tr").each(function(indx,value){
                                $(this).find(".product_no").text(indx+1);
                                // console.log(value);
                })
        }

});


