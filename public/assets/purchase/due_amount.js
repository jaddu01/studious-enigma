$(document).ready(function () {
    $(".select2-supplier").on('change', function () {
        const supplier_id = $(this).val();
        GetDueAmount(supplier_id);
    })

    const GetDueAmount = (supplier_id) => {
        $.ajax({
            url: supplier_due_amount_url,
            data: { 'supplier_id': supplier_id },
            success: function (res) {
                $("#totalDueAmount").text(res.due_amount);
            }
        })
    }
});