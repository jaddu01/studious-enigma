$(document).ready(function(){
    //get products
    $('.select2-product').select2({
        placeholder: "Search Product",
        allowClear: true,
        minimumInputLength: 3,
        ajax: {
            url: search_product_url,
            data: function (params) {
                var query = {
                    search: params.term,
                    page: params.page,
                    type: 'products'
                }
                return query;
            },

            processResults: function (res, params) {
             
                params.page = params.page || 1;
                return {
                    results: res.data,
                };
            },
        },
        cache: true,
    },

    );
    //select barcode size
    $("#barcode-size-dropdown").on('change',function(){
        const size = $(this).val();
        // if(size==='A4_84'){
        //     $("#product-tbl-body").find(".qty-field").val(84);
        // }else if(size==='A4_65'){
        //     $("#product-tbl-body").find(".qty-field").val(65);
        // }
        getBarcodeSizeSample(size);
    });

    function getBarcodeSizeSample(size){
        ajxHeader();
        $.ajax({
            url:barcodesize_url,
            type:'POST',
            data:{'barcodeSize':size},
            success:function(res){
                $("#barcode-sample-img").attr('src',res.barcodeImg);
            }
        })
    }
});