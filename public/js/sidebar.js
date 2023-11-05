$(document).ready(function(){
const ActiveCurrentSectionVal = $("#ActiveSidebarCurrentSection").val();
const ActiveCurrentPageVal = $("#ActiveSidebarCurrentPage").val();


$(`#${ActiveCurrentSectionVal}`).addClass('active');
$(`#${ActiveCurrentSectionVal}`).find('.child_menu:first').css('display','block');
$(`#${ActiveCurrentPageVal}`).addClass('current-page');
$(`#${ActiveCurrentSectionVal}`).click();

});