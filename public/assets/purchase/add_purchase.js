
function setTblData(count = null, isSelectedWithGst) {
    let gst_amount_And_gst_percentage_column = '';
    if (isSelectedWithGst) {
        gst_amount_And_gst_percentage_column = `<td><span class="product_gst_amount">0</span></td>
        <td><span class="product_gst_percentage amount="">0</span></td>`;
    }

    const tableRow = `<tr>
    <td>
    <div style="width:70px;" class="text-center">
    <i class="fa fa-times-circle btn-pill1 product_delete_field_row_btn ml-1 ${(count === 1) ? 'display-hide' : ''}" aria-hidden="true"></i>

    <i class="fa fa-plus-circle btn-pill product_add_new_field_row_btn" aria-hidden="true"></i>
</div>
    </td>
    <td><span class="product_no">1</span>
    </td>
    <td><input type="text" placeholder="Barcode" style="width:130px;"
            class="form-control product_barcode"></td>
    < <td><div class="inline-flx"><select class="form-control select2-product" style="width:200px;"></select>
    <button class="btn btn-success btn-sm add_new_product_btn display-hide">Add New</button></div>
    </td>
   
    <td><input type="number" value="0" placeholder="0"
            class="product-tbl-column-width form-control product_qty product-field">
    </td>
    <td><input type="number" value="0" placeholder="0"
            class="product-tbl-column-width form-control product-field product_free_qty">
    </td>
    <td><input type="number" value="0" placeholder="0"
            class="product-tbl-column-width form-control product-field product_unit_cost">
    </td>
  
   
    <td><input type="number" value="0" placeholder="0"
            class="product-tbl-column-width form-control product-field product_net_rate">
    </td>
    <td><input type="number" value="0" placeholder="0"
            class="product-tbl-column-width form-control product-field product_mrp">
    </td>
    <td><input type="number" value="0" placeholder="0"
            class="product-tbl-column-width form-control product-field  product_selling_price">
    </td>
    ${gst_amount_And_gst_percentage_column}

    <td><input type="number" value="0" placeholder="0"
            class="product-tbl-column-width form-control product-field product_margin">
    </td>
    <td><input type="number" value="0" placeholder="0"
            class="product-tbl-column-width form-control product-field product_total">
    </td>
    
    </tr>`;

    $(tableRow).insertBefore('#totalResult');
    $('#product_Details_Tbody:last').find('.select2-product').select2({

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
                // console.log(res.term);
                const searchText = params.term;
                const self = $(this)[0].$element[0];
                const btn = $(self).parent().find('.add_new_product_btn');
                params.page = params.page || 1;
                if (res.data.length == 0) {
                    btn.removeClass('display-hide');
                    btn.attr('value', searchText);
                } else {
                    btn.addClass('display-hide');

                }
                btn.click(function (e) {
                    e.preventDefault();
                    AddNewProductBtnClick(searchText);
                });
                return {
                    results: res.data,
                };
            },
        },
        cache: true,
    },

    );

    const AddNewProductBtnClick = (searchTxt) => {
        $("#addNewProductForm").trigger('reset');
        $('#productCategories,#brandsList,#measurementClass,#related_products,#variant_products').val(null).trigger('change');
        $("#addProductModal").modal('show');
        $("#prodcutName").val(searchTxt);
        $("#productImgUploader").parent().removeClass('display-hide');
        $("#prodcutImageViewer").removeAttr('src').parent().addClass('display-hide');

    }

    $("#product_Details_Tbody:last").find(".select2-product").on('change', function () {
        const product_id = $(this).val();
        const self = $(this);
        const uRL = supplier_product_info_url + '/' + product_id;
        getProductDetails(uRL, self);
    });

    $("#product_Details_Tbody:last").find('.product_barcode').blur(function () {
        const uRL = supplier_product_info_url;
        const self = $(this);
        const barcode = $(this).val();

        getProductDetails(uRL, self, type = 'barcode', barcode);
    });

}

//stop negative value
$('#product_Details_Tbody').on('input', '.product-field', function () {

    const positive_number = Math.abs($(this).val());
    $(this).val(positive_number);
})
const setAdditionalChargeTblRow = (count = null) => {
    const additionalChargeTblRow = ` <tr>
    <td>
        <div style="width:70px;" class="text-center">
            <i class="fa fa-times-circle btn-pill1 additional_charge_row_delete_btn  ml-1 ${(count === 1) ? 'display-hide' : ''}"
                aria-hidden="true"></i>
            <i class="fa fa-plus-circle btn-pill additional_charge_row_add_btn "
                aria-hidden="true"></i>
        </div>
    </td>
    <td><span class="additional_charge additional_charge_number">1</span></td>
    <td>
        <div class="text-center"><input type="text"
                class="additional_charge AdditionalCharge text-center"
                style="width:400px;"></div>
    </td>
    <td><input type="number" class="additional_charge AdditionalChargeValue text-center"
            style="width:100px;"></td>
   

</tr>`;
    $(additionalChargeTblRow).insertBefore("#additionalChargeResult");

}

const setResult = () => {
    let additionalCharge = 0;
    let ttl = 0;
    if ($("#totalAddionalChargeTbl").text()) {
        additionalCharge = parseFloat($("#totalAddionalChargeTbl").text());
        $("#totalAdditionalCharges").text(additionalCharge.toFixed(2));
    } else {
        $("#totalAdditionalCharges").text(0.00);

    }

    // if ($("#total").text()) {
    //     ttl = parseFloat($("#total").text());

    // }else{
    //     $("#totalAmount").text(0.00)

    // }
    $("#totalAmount").text(parseFloat($("#total").text()).toFixed(2));
    ttl = parseFloat($("#total").text());

    $("#NetAmount").text((additionalCharge + ttl).toFixed(2))
}
const showTblResult = () => {
    $("#totalResult").removeClass('display-hide');
    let totalQty = gstAmount = total = 0;

    $("#product_Details_Tbody tr").not("#totalResult").each(function (indx, value) {
        if ($(this).find(".product_qty")) {
            totalQty += parseFloat($(this).find(".product_qty").val());

        }
        if ($(this).find(".product_gst_amount").text()) {
            gstAmount += parseFloat($(this).find(".product_gst_amount").text());

        }
        if ($(this).find(".product_total").val()) {
            total += parseFloat(parseFloat($(this).find(".product_total").val()));

        }

    })

    $("#totalQty").text(totalQty.toFixed(2));
    $("#totalGstAmount").text(gstAmount.toFixed(2));
    $("#total").text(total.toFixed(2));
    setResult();
}

const showAdditionalChargeTblResult = () => {
    $("#additionalChargeResult").removeClass('display-hide');
    let total = 0;
    $("#additional_charge_body tr").not("#additionalChargeResult").each(function (indx, value) {
        let totalVal = $(this).find(".AdditionalChargeValue").val();

        if (totalVal) {
            total += parseFloat(totalVal);
        }

    })
    $("#totalAddionalChargeTbl").text(total.toFixed(2));
    setResult();

}

$(document).ready(function () {
    //click to select supplier dropdown
    $("#supplier_id").on('change', function () {
        const supplier_id = $(this).val();
        const url = supplier_address_get_url + '/' + supplier_id;

        getSupplierAddress(url);
    });

    //click to with gst and without gst dropdown
    $("#gstSelected").on('change', function () {
        let isSelectedWithGst = true;

        $("#productTbl").removeClass('display-hide');
        isSelectedWithGst = ($(this).val() == 1 ? true : false);

        $(this).prop('disabled', true);
        setTblData(1, isSelectedWithGst);
        showTblResult();

        if (!isSelectedWithGst) {
            $("#gstAmountID,#gst_percentage,.tempcolum").addClass('display-hide');
            $("#totalGstAmount").parent().addClass('display-hide');
        } else {
            $("#gstAmountID,#gst_percentage,.tempcolum").removeClass('display-hide');
            $("#totalGstAmount").parent().removeClass('display-hide');

            // $(this).prop('disabled',false);


        }
        // console.log('selected gst:',isSelectedWithGst);

    })


    function getSupplierAddress(url) {
        ajxHeader();
        $.ajax({
            url: url,
            method: 'get',
            beforeSend: function () {

            },
            success: function (res) {
                let contact_number = res.contact_number;
                let phone_number = res.phone_number;
                if (contact_number != undefined) {
                    contact_number = `${contact_number}`;
                }
                if (phone_number != undefined) {
                    phone_number = `${phone_number}`;
                }
                $("#supplier_address").find('#Billing-address').html('');
                $("#supplier_address").find('#state').text(res.state ?? '')
                $("#supplier_address").find('#gst_no').text(res.gstin ?? '');
                $("#supplier_address").find('#Billing-address').html(`<div>${res.company_name ?? ''}<br>${(res.address != null) ? res.address + ',' : ''} ${(res.pincode != null) ? res.pincode + ',' : ''} ${(res.state != null) ? res.state + ',' : ''} ${res.country ?? ''}</div>
              ${(contact_number != null || phone_number != null) ? `<i class="fa fa-phone"></i>${contact_number ?? ''} ${phone_number ?? ''}` : ''} `);
                $("#supplier_address").find('#billing-not-provided').hide();
                $("#gstSelected").prop('disabled', false);
                // if ($("#product_Details_Tbody tr").length == 1) {
                //     setTblData(1);
                //     showTblResult();
                // }

            }
        })
    }

    //add variant and products in pop modal

    $(".select2-related-products").select2({
        tags: true,
        multiple: true,
        width: '100%',
        tokenSeparators: [','],
        minimumInputLength: 2,
        minimumResultsForSearch: 10,
        ajax: {
            url: product_list_search,
            dataType: "json",
            type: "GET",
            data: function (params) {
                var queryParameters = {
                    term: params.term
                }
                return queryParameters;
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            }
        }
    });

    //variant products list
    $(".select2-variant-products").select2({
        tags: true,
        multiple: true,
        width: '100%',
        tokenSeparators: [','],
        minimumInputLength: 2,
        minimumResultsForSearch: 10,
        ajax: {
            url: varient_product_list_search,
            dataType: "json",
            type: "GET",
            data: function (params) {
                var queryParameters = {
                    term: params.term
                }
                return queryParameters;
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            }
        }
    });


});

function getProductDetails(uRL, self, type = null, barcode = null) {
    // console.log("type:",type)
    $.ajax({
        url: uRL,
        data: { 'type': type, 'barcode': barcode },
        success: function (res) {

            console.log("type:", type);
            let parent = self.parent().parent();
            if (type != 'barcode' || type == null) {
                parent = self.parent().parent().parent();

            }
            parent.find(".product-field").val('');
            parent.find('.product_mrp').val(res.mrp);

            parent.find('.product_gst_percentage').text(res.gst_percentage);
            parent.find('.product_gst_percentage').attr('amount', res.gst_percentage);
            parent.find('.product_barcode').val(res.barcode);

            if (type == 'barcode') {
                const newOption = new Option(res.name, res.id, false, false);
                parent.find(".select2-product").append(newOption).trigger('change');
            }

        }
    })
}



