$(document).ready(function(){
    
    $("#addNewProductForm").submit(function(e){
        e.preventDefault();
    }).validate({
        rules:{
            'name:en':{
                required:true,
                minlength: 3,
            },
            'print_name:en':{
                required:true,
                minlength: 3
            },
            'category_id[]':{
                required:true,
            },
            'price':{
                required:true,
                
            },
            'best_price':{
                required:true,
            },
            'qty':{
                required:true
            },
            'disclaimer:en':{
                required:true,
            },
            'description:en':{
                required:true
            },
            measurement_value:{
                required:true,
            }
        },
        messages:{
            'name:en':{
                required:"Product name is required",
            },
            'print_name:en':{
                required:"Print name is required"
            },
            'category_id[]':{
                required:"Select minimum one category"
            },
            'price':{
                required:"MRP is required"
            },
            'best_price':{
                required:"Best price is required"
            },
            'qty':{
                required:"Qty is required"
            },
            'description:en':{
                required:"Description is required",
            },
            'disclaimer:en':{
                required:"Disclaimer is required"
            },
            measurement_value:{
                required:"Measurement value is required"
            }

        },
        errorElement:'span',
        errorPlacement:function(err,element){
            err.addClass('invalid-feedback');
            element.closest('.form-group').append(err);
        },
        highlight:function(element,errorClass,validClass){
            $(element).addClass('is-invalid');
            // $(element).closest('.select2-container .select2-selection--single').addClass('is-invalid');
        },
        unhighlight:function(element,errorClass,validClass){
            $(element).removeClass('is-invalid');
            // $(element).closest('.select2-container .select2-selection--single').removeClass('is-invalid');

        },
        submitHandler:function(){
            const formElement = $("#addNewProductForm").not("#productImgFileUplaodBtn").serializeArray();
            const formData = new FormData();

            // console.log(formElement);
            $.each(formElement,function(){
                formData.append(this.name,this.value)
            });

            const image = $("#productImgFileUplaodBtn")[0].files[0];
            if(image!=undefined){
                formData.append('image',image);
            }
            saveData(formData);
        }
    })
    const saveData=(data)=> {
        ajxHeader();
        $.ajax({
            url: addNewProductUrl,
            enctype:'multipart/form-data',
            method: 'post',
            data:data,
            contentType: false,
            processData: false,
            beforeSend: function () {
            },
            success: function (res) {
                // $("#addNewProductForm").trigger('reset');
                successMsg(res.msg);
                $("#addProductModal").modal('hide');

            //    location.href=view_purchase_url;
            }
        })
    }
});