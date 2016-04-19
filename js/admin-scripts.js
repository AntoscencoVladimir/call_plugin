// JavaScript Document
// необходимые скрипты для админ панели

function deleteService() {
    var answer = confirm("Вы действительно хотите удалить этот звонок?");
    return answer;
}

jQuery(document).ready(function () {

    if (jQuery('#status span').hasClass('wait-ob')) {
        jQuery('.ob-call').show();
        jQuery('.ob-esc').hide();
    } else {
        jQuery('.ob-call').hide();
        jQuery('.ob-esc').show();
    }

});




