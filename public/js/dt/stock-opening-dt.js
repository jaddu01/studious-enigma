db_table = '';
filter_val = null;
$(document).ready(function () {
    // alert("Working");
    function dbTble(filterVal = filter_val) {
        db_table = $("#dttbl").DataTable({
            serverSide: true,
            stateSave: false,
            pageLength: 10,
            language: {
                searchPlaceholder: "Search Product Name"
            },
            ajax: {
                url: uRL,
                data: {
                    'filterVal': filterVal
                },
                beforeSend: function () {
                    // showLoader();
                },
            },
            columns: [{
                name: 'sr_no',
                data: 'sr_no',
                // orderable:false,

            },
            {
                name: 'product_name',
                data: 'product_name',
                searchable: true,
                orderable: false,

            },

            {
                name: 'price',
                data: 'price',
                orderable: false
            },
            {
                name: 'qty',
                data: 'qty',
                orderable: false
            },
            {
                name: 'status',
                data: 'status',
                orderable: false
            },
            {
                name: 'created_at',
                data: 'date',
                orderable: false
            },
            {
                name: 'updated_at',
                data: 'update_date',
                orderable: false
            },
            {
                name: 'action',
                data: 'action',
                orderable: false
            },
            ],
            order: [0, 'asc'],
            drawCallback: function (settings, json) {
                // $('[rel="tooltip"]').tooltip();
                // hideLoader();
            },

        });
    }
    dbTble();
    $("#dttbl").on("click", '.editBtn', function () {
        $("#openingStockModal").find('input[name=product_id]').val($(this).attr('product-id'));
        $("#openingStockModal").find('input[name=product]').val($(this).attr('product'));
        $("#openingStockModal").find('input[name=qty]').val($(this).attr('qty'));
        $("#openingStockModal").find('#barcode').val($(this).attr('barcode'));
        $("#openingStockModal").find('#skucode').val($(this).attr('sku-code'));
        const status = ($(this).attr('status')==1)?true:false;
        $('#statusBtn').prop('checked', status).change()
        // statusBtn

        $("#openingStockModal").find('input[name=purchase_price]').val($(this).attr('purchase-price'));
        $("#openingStockModal").find('input[name=best_price]').val($(this).attr('selling-price'));
        $("#openingStockModal").find('input[name=price]').val($(this).attr('price'));
        $("#openingStockModal").modal('show');

    });

    //click to save btn

    $("#saveBtn").click(function () {
        const formElementData = $("#openingStockForm").serializeArray();
        const data = ArrayToJson(formElementData)
        // console.log(data);
        updateStock(data);
    });


    const updateStock = (data_) => {
        ajxHeader();
        $.ajax({
            url: openingStockUpdateUrl,
            method: 'post',
            data: data_,
            success: function (res) {
                // console.log(res)
            
                $("#openingStockModal").modal('hide');
                new PNotify({
                    title: 'Success',
                    text: res.msg,
                    type: 'success',
                    styling: 'bootstrap3',
                    delay: 1000,

                });
                refreshTbl();


            },
            error: function (err) {
                // console.error(err);
                $("#openingStockModal").modal('hide');

            }
        })
    }

    const refreshTbl = () => {
        db_table.destroy();
        dbTble();
        // db_table.ajax.reload();
    }

    $("#openingStockModal").find("input[type=number]").on('input',function(){
     const positive = Math.abs($(this).val());
     $(this).val(positive);
    });
})