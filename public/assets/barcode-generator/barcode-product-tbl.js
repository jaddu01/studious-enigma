window.SR_no=1;
$(document).ready(function(){
    //select to product on product dropdown 
    $(".select2-product").on('change',function(){
        const product_id= $(this).val();
        const uRL = supplier_product_info_url + '/' + product_id;
        getProductDetails(uRL);
    });

    //click to delete icon inside the table
    $("#product-tbl-body").on("click",".product_delete_field_row_btn",function(){
        $(this).parent().parent().parent().remove();
        displayRows();
    });

    //click display tble rows with click delete button click
    function displayRows(){
        let new_sr_no=1;
        $("#product-tbl-body >tr").each(function(indx,value){
            // console.log("value:",value);
            $(this).find(".SR_no").text(new_sr_no);
            new_sr_no++;

        })
        SR_no=new_sr_no;
        BarcodeGenratorButtonEnableDisable(SR_no-1);
        
    }
    

//table rows
    const tableData = (product)=>{
        const html = `<tr><td><span class="SR_no">${SR_no}</span></td>
        <td  class="text-center"><span>${product.name}</span> <input type="hidden" value="${product.name}" name="productName[]"></td>
        <td  class="text-center"><span>${product.barcode}</span></td>
        <td class="text-center"><input type="number" class="qty-field" min="1" name="qty[]" value="${product.qty}"></td>
        <td><span>${product.mrp}</span><input type="hidden" name="mrp[]" value="${product.mrp}"></td>
        <td class="text-center">
        <div style="width:70px;" class="text-center barcode-selling-price-deleteBtn">
        <span>${product.selling_price}</span>
        <i class="fa fa-times-circle btn-pill1 product_delete_field_row_btn ml-1" aria-hidden="true"></i>
        <input type="hidden" name="barcode[]" value="${product.barcode}">
    </div>
        </td></tr>
        `;
        return html;
    }

//get product click
    function getProductDetails(uRL){
        $.ajax({
            url: uRL,
            data: {type:''},
            success: function (res) {
                // console.log(res);
                const tbody= tableData(res);
                $("#product-tbl-body").append(tbody);
                SR_no++;
                BarcodeGenratorButtonEnableDisable(SR_no);
            }
        });
    }

    $("#product-tbl-body").on("input",".qty-field",function(){
        const value = $(this).val();
        $(this).val(Math.abs(value));
    })

    //enable disable genearte barcode button
    const BarcodeGenratorButtonEnableDisable=(SerialNumber)=>{
        if(SerialNumber>0){
            $("#barcode-generator-btn").prop('disabled',false);

        }else{
            $("#barcode-generator-btn").prop('disabled',true);

        }

    }


    //click to barcode generator btn
// $("#barcode-generator-btn").click(function(){
//     alert("working...");
//     barcodePrint();
// });

});
