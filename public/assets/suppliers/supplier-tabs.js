$(document).ready(function(){
    
    $("#supplier_tabs").find(".supplier-tab").click(function(){
        $("#supplier_tabs").find(".supplier-tab").removeClass('active');
        $("#supplier_tabs").find(".supplier-tab >a").removeClass('blue-text');
        $(this).find('a').addClass('blue-text');
        $(this).addClass('active');
        $("#tabs-details").find(`.tabs-details`).addClass('display-hide');
        $("#tabs-details").find(`#${$(this).attr('value')}`).removeClass('display-hide');
    });
})