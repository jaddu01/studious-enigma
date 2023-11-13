db_table = '';

$(document).ready(function () {
    function dbTble() {
        db_table = $("#billDttbl").DataTable({
            serverSide: true,
            stateSave: false,
            pageLength: 10,
            language: {
                searchPlaceholder: "Search by Invoice No"
            },
            ajax: {
                url: supplier_bill_details_dt_list,
                data: {
                    'supplier_id': supplier_bill_id
                },
                beforeSend: function () {
                    // showLoader();
                },
            },
            columns: [
                {
                    name: 'sr_no',
                    data: 'sr_no'
                },
                {
                    name: 'invoice_no',
                    data: 'invoice_no'
                },
                {
                    name: 'bill_date',
                    data: 'bill_date'
                },
                {
                    name: 'paid_amount',
                    data: 'paid_amount'
                },
                {
                    name: 'due_amount',
                    data: 'due_amount'
                },
                {
                    name: 'net_amount',
                    data: 'net_amount'
                },
                {
                    name: 'action',
                    data: 'action',
                    orderable: false,

                },


            ],
            order: [1, 'asc'],
            drawCallback: function (settings, json) {
                // $('[rel="tooltip"]').tooltip();
                // hideLoader();
            },

        });
    }
    dbTble();

});