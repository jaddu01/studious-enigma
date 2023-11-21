$(document).ready(function () {

    $("#productImgUploader,#productImgUpdateIconBtn").click(function () {
        $("#productImgFileUplaodBtn").trigger('click');
    });

    $('#productImgFileUplaodBtn').on('change', function (e) {
        const file = e.target.files[0];
        const url = window.URL.createObjectURL(file);
        $("#productImgUploader").parent().addClass('display-hide');
        $("#prodcutImageViewer").attr('src', url).parent().removeClass('display-hide');
    });


    $(".product-image-uploder").find(".imageViwer").on('mouseenter',function(){
        $("#prodcutImageViewer").addClass('blurImg');
        $("#productImgUpdateIconBtn").removeClass('display-hide');
    }).on('mouseleave',function(){
        $("#prodcutImageViewer").removeClass('blurImg');
        $("#productImgUpdateIconBtn").addClass('display-hide');


    })

})