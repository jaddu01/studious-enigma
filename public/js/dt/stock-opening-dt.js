db_table='';
filter_val=null;
$(document).ready(function(){
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
                name:'product_name',
                data:'product_name',
                searchable:true,
                orderable:false,
            
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
                name: 'created_at',
                data: 'date',
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

    $("#dttbl").on("click",'.editBtn',function(){
        $("#openingStockModal").modal('show');
    });
})