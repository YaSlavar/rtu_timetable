$(document).ready(function () {

    let overlay_div = $('.message_box');
    let close_message_box = $('#close_message_box');

    if (getCookie('hide_message') !== 'yes') {
        overlay_div.css('display', 'block');
    }

    close_message_box.off('click').on('click', function () {
        setCookie('hide_message', 'yes', 31536000);
        overlay_div.css('display', 'none');
    });
});



