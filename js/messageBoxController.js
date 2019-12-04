function getCookie(name) {
    let cookie_arr = document.cookie.split('; ');
    let cookie_obj = {};

    for (let i = 0; i < cookie_arr.length; i++) {
        let nv = cookie_arr[i].split('=');
        cookie_obj[nv[0]] = nv[1];
    }

    return cookie_obj[name];
}




$(document).ready(function () {

    let overlay_div = $('.message_box');
    let close_message_box = $('#close_message_box');

    if (getCookie('hide_message') !== 'yes') {
        overlay_div.css('display', 'block');
    }

    close_message_box.off('click').on('click', function () {
        console.log("ok");
        let date = new Date(new Date().getTime() + 31536 * 1000000);
        document.cookie = "hide_message=yes; path=/; expires=" + date.toUTCString();

        overlay_div.css('display', 'none');
    });
});



