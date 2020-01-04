function message_box_control(json_param) {

    let notification_block = $('.toast');
    let notification_block_header = $('.notification_header_text');
    let notification_block_message = $('.toast-body');
    let notification_block_date = $('.notification_date');

    if (getCookie(json_param.key) !== 'yes' && json_param.is_show === true) {
        notification_block_header.html(json_param.header);
        notification_block_message.html(json_param.message);
        notification_block_date.html(json_param.date);

        notification_block.toast('show');
    } else {
        notification_block.toast('hide');
    }

    notification_block.on('hidden.bs.toast', function () {

        setCookie(json_param.key, 'yes', 31536000);
    });

}




