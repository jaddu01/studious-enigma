$(document).ready(function(){
const ActiveCurrentSectionVal = $("#ActiveSidebarCurrentSection").val();
const ActiveCurrentPageVal = $("#ActiveSidebarCurrentPage").val();
console.log("Active section:",ActiveCurrentSectionVal);
console.log("Active Page:",ActiveCurrentPageVal);

$(`#${ActiveCurrentSectionVal}`).addClass('active');
$(`#${ActiveCurrentSectionVal}`).find('.child_menu:first').css('display','block');
$(`#${ActiveCurrentPageVal}`).addClass('current-page');
$(`#${ActiveCurrentSectionVal}`).click();

});