$(document).ready(function(){
  

    $("#supplier_id").on('change',function(){
        const  supplier_id = $(this).val();
        const url =supplier_address_get_url+'/'+supplier_id;
        console.log(url);
        getSupplierAddress(url);
    });

    function getSupplierAddress(url){
        ajxHeader();
        $.ajax({
            url:url,
            method:'get',
            beforeSend:function(){

            },
            success:function(res){
                let contact_number = res.contact_number;
                let phone_number = res.phone_number;
                if(contact_number!=undefined)
                {
                    contact_number=`${contact_number},`;
                }
                if(phone_number!=undefined){
                    phone_number= `${phone_number}`;
                }
                $("#supplier_address").find('#Billing-address').html('');
                $("#supplier_address").find('#state').text(res.state)
                $("#supplier_address").find('#gst_no').text(res.gstin);
                $("#supplier_address").find('#Billing-address').html(`<div>${res.company_name}<br>${res.address}, ${res.pincode}, ${res.state}, ${res.country}</div>
                <i class="fa fa-phone"></i> ${contact_number} ${phone_number}`);
                $("#supplier_address").find('#billing-not-provided').hide();






            }
        })
    }

    
//search product using barcode
$("#product_Details_Tbody").find(".select2-barcode").select2(
    
    {
        placeholder:"Search Barcode",
        allowClear:true,
    minimumInputLength: 3 ,
    ajax: {
      url: search_product_url,
      data: function (params) {
        var query = {
          search: params.term,
          page: params.page,
          type: 'barcode'
        }
        return query;
      },
    
  
    processResults: function (res, params) {
        params.page = params.page||1;

        return {
            results: res.data,
        };
    },
    },
 
    cache:true,
},

);

//search product using product name
$("#product_Details_Tbody").find(".select2-product").select2({
    placeholder: "Select Product",
    allowClear: true,
    minimumInputLength: 3 ,
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
    //   processResults: function (res) {
    //     // Transforms the top-level key of the response object from 'items' to 'results'
    //     return {
    //       results: res.data
    //     };
    //   }
  
    processResults: function (res, params) {
        params.page = params.page||1;
        // console.log(res.meta.total_item);
        
        return {
            results: res.data,
            // pagination: {
            //     more: (params.page * 10) < res.meta.total
            // }
        };
    },
    },
 
    cache:true,
},

);

//click to barcode searched output
$("#product_Details_Tbody").find(".select2-barcode").on('change',function(){
    const product_id = $(this).val();
    
})

//click to product searched output
$("#product_Details_Tbody").find(".select2-product").on('change',function(){
    const product_id = $(this).val();
    const uRL = supplier_product_info_url+'/'+product_id;
    getProductDetails(uRL);
    
});

function getProductDetails(uRL){
    $.ajax({
        url:uRL,
        success:function(res){
            console.log(res);
        }
    })
}

  
});