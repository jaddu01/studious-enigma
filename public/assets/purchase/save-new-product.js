$(document).ready(function(){
    
    $("#addproductModalBtn").click(function(e){
        e.preventDefault();
        saveData(null);
    })

    const saveData=(data)=> {
        ajxHeader();
        $.ajax({
            url: addNewProductUrl,
            method: 'post',
            data:data,
            contentType: false,
            processData: false,
            beforeSend: function () {
            },
            success: function (res) {
               location.href=view_purchase_url;
            }
        })
    }
});