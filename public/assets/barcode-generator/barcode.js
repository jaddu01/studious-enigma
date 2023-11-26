$(document).ready(function(){
    $('.select2-product').select2({
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
             
                params.page = params.page || 1;
                // if (res.data.length == 0) {
                //     btn.removeClass('display-hide');
                //     btn.attr('value', searchText);
                // } else {
                //     btn.addClass('display-hide');

                // }
                // btn.click(function (e) {
                //     e.preventDefault();
                //     AddNewProductBtnClick(searchText);
                // });
                console.log(res.data);
                return {
                    results: res.data,
                };
            },
        },
        cache: true,
    },

    );
});