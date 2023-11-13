$(document).ready(function () {
    function paymentTbl() {
        $("#paymentTbl").DataTable({
            serverSide: true,
            stateSave: false,
            pageLength: 10,
            language: {
                searchPlaceholder: "Search by Payment No"
            },
            ajax: {
                url: supplier_payment_dt_list,
                data: {
                    'supplier_id': supplier_bill_id
                },
                beforeSend: function () {
                    // showLoader();
                },
            },
            columns: [
                { name: 'sr_no', data: 'sr_no' },
                {
                    name: 'payment_no',
                    data: 'payment_no',
                    width: '10px'
                },
                {
                    name: 'payment_date',
                    data: 'payment_date',
                    width: '30px'
                },
                {
                    name: 'payment_mode',
                    data: 'payment_mode',
                    width: '40px'
                },
                {
                    name: 'amount',
                    data: 'amount'
                },
                {
                    name: 'action',
                    data: 'action',
                    orderable:false,
                },


            ],
            order: [1, 'asc'],
            drawCallback: function (settings, json) {
                // $('[rel="tooltip"]').tooltip();
                // hideLoader();
            },

        });
    }
    paymentTbl();

});