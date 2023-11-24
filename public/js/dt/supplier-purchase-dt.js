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
                searchPlaceholder: "Search by Supplier"
            },
            ajax: {
                url: supplier_purchase_list_url,
                data: {
                    'filterVal': filterVal
                },
                beforeSend: function () {
                    // showLoader();
                },
            },
            columns: [{
                name: 'invoice_no',
                data: 'invoice_no',
                // orderable:false,

            },
            {
                name: 'supplier',
                data: 'supplier',
                searchable: true,
                orderable: false,

            },

            {
                name: 'bill_date',
                data: 'bill_date',
                orderable: false
            },
            {
                name: 'due_date',
                data: 'due_date',
                orderable: false
            },
           
            {
                name: 'net_amount',
                data: 'net_amount',
                orderable: false
            },
            {
                name: 'paid_amount',
                data: 'paid_amount',
                orderable: false
            },
            {
                name: 'total_additional_charge',
                data: 'total_additional_charge',
                orderable: false
            },
            {
                name: 'due_amount',
                data: 'due_amount',
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
                $('[data-toggle="tooltip"]').tooltip();
                // hideLoader();
            },

        });
    }
    dbTble();
   
});