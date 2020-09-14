function ajaxAction(obj, type) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: '/' + type + '/' + obj[0].className,
        data: type + '_id=' + obj.data('id'),
        success: function (data) {
            obj.attr('class', data);
            let child = $(obj).children()[0];
            let text = $(child).children()[0];

            let newText = data == 'follow' ? 'Подписаться' : 'Отписаться';
            let newClass = data == 'follow' ? 'btn_subscribe' : 'btn_unscribe';
            if (type == 'users')
            {
                newClass = data == 'follow' ? 'subscribe' : 'unscribe';
                if(newClass == 'unscribe') {
                    newClass += ' btn-green';
                }
            }
            $(text).text(newText);
            $(child).removeClass();
            $(child).addClass(newClass).addClass(' profile_user_btn');
        },
        error: function (data) {
            alert(data);
        }
    });
}
$(document).ready(function () {
    let sc = $('.chosen-choices');
    sc.change(function () {
        console.log(1);
    })
});
function sendComment() {
    let content = $('.detail-content-comments-add .emojionearea-editor')[0].innerHTML;
    let textarea = $('.detail-content-comments-add textarea');
    textarea.val(content);
    $('#fcomment').submit();
}


$(document).on('click', '.btn-form', function() {
    let id = $(this)[0].dataset.id;
    let content = $('.comment-reply .emojionearea-editor')[0].innerHTML;
    let textarea = $('.comment-reply textarea');

    textarea.val(content);

    $('#form-' + id).submit();
});
