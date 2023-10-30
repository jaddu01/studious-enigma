$(document).ready(function(){
    $("#add_bank_detailsBtn").click(function(){
        $("#bankdDetails").collapse('toggle');
        hideShowPlusBtn($(this));
    })

    $("#add_general_details_btn").click(function(){
        $("#generalDetails").collapse('toggle');
        hideShowPlusBtn($(this));

    })

    const hideShowPlusBtn=(btn)=>{
        if(btn.hasClass('fa-plus-circle')){
            btn.removeClass('fa-plus-circle').addClass('fa-minus-circle');
        }else{
            btn.addClass('fa-plus-circle').removeClass('fa-minus-circle');

        }
    }
})