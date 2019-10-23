let ZOOM_DELTA = 1;

function zoom_element(element, delta, zoom_delta = 0.05, animate_speed = 1) {
    let undo_zoom_button = $('#undo_zoom');

    if (typeof(delta) !== "number") {
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