let ZOOM_DELTA = 1;

function zoom_element(element, delta, zoom_delta = 0.05, animate_speed = 1) {
    let undo_zoom_button = $('#undo_zoom');

    if (typeof (delta) !== "number") {
        delta = delta.deltaY * delta.deltaFactor;
    }

    if (Number(delta) >= 1) {
        ZOOM_DELTA += zoom_delta;
    } else if (Number(delta) < 0 && ZOOM_DELTA > 0.15) {
        ZOOM_DELTA -= zoom_delta;
    }
    element.animate({'zoom': ZOOM_DELTA}, animate_speed);

    if (ZOOM_DELTA !== 1) {
        undo_zoom_button.css({'display': 'block'});
    } else {
        undo_zoom_button.css({'display': 'none'});
    }

}

function undo_zoom(element, animate_speed = 400) {
    ZOOM_DELTA = 1;
    element.animate({'zoom': ZOOM_DELTA}, animate_speed);

    let undo_zoom_button = $('#undo_zoom');
    undo_zoom_button.css({'display': 'none'});

    element.css({'left': 20, 'top': 60});
}

function getCookie(name) {
    let cookie_arr = document.cookie.split('; ');
    let cookie_obj = {};

    for (let i = 0; i < cookie_arr.length; i++) {
        let nv = cookie_arr[i].split('=');
        cookie_obj[nv[0]] = nv[1];
    }

    return cookie_obj[name];
}

function setCookie(name, value, set_sec) {
    let date = new Date(new Date().getTime() + set_sec * 1000);
    document.cookie = name + "=" + value + "; path=/; expires=" + date.toUTCString();
}


function formatDate(date) {

    function padNum(num) {
        return num.toString().padStart(2, '0');
    }

    let day = padNum(date.getDate());
    let monthIndex = padNum(date.getMonth() + 1);
    let year = date.getFullYear();

    return day + '.' + monthIndex + '.' + year;
}