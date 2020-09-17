
$(document).ready(function() {


    /*function initTexarea(block) {
        let maxlength = block.attr('maxlength');
        let el = $(block).emojioneArea({
            search: false,
            saveEmojisAs: 'shortname',
            textcomplete: {

            },
            filters: {
                recent: {
                    title: 'Часто используемые'
                },
                smileys_people: {
                    title: 'Эмоции и жесты'
                },
                animals_nature: {
                    title: 'Животные и растения'
                },
                food_drink: {
                    title: 'Еда'
                },
                activity: {
                    title: 'Спорт и активности'
                },
                travel_places: {
                    title: 'Путешествия и транспорт'
                },
                objects: {
                    title: 'Предметы'
                },
                symbols: {
                    title: 'Символы'
                },
                flags: {
                    title: 'Флаги'
                },
            }
        });
    }
    initTexarea($('.textarea-block__textarea'));*/


    $(document).on('keydown', '.maxlength .emojionearea-editor', function(e){
        let block = $(this);
        let wrap_all = block.closest('.textarea');
        let block_maxlength = wrap_all.find('.textarea-block__textarea').attr('maxlength');
        let img_length = block.find('img').length;
        let text_length = block.text().length + img_length;
        if ( e.keyCode == 8 || e.key == 'ArrowUp' || e.key == 'ArrowDown' || e.key == 'ArrowLeft' || e.key == 'ArrowRight' || e.key == 'Escape' || e.keyCode == '35' || e.keyCode == '36' || e.keyCode >= 112 || e.keyCode >= 123 || e.keyCode == 9 || e.keyCode == 16 || e.keyCode == 20 || e.keyCode == 17 || e.keyCode == 18 || e.keyCode == 91 ) {

        }else{
            if ( text_length > block_maxlength ) {
                alert('Максимальное кол-во символов: '+block_maxlength);
            }
        }
    });


// var cancelKeypress = false;
// let bool = true;
    $(document).on('keypress', '.maxlength .emojionearea-editor', function(e){
        let bool = $(this).closest('.textarea').attr('data-bool');
        bool = parseInt(bool);
        if ( bool == 0 ) {
            bool = false;
        }else{
            bool = true;
        }
        if ( e.keyCode == 8 ) {
        }else{
            checkEmoji($(this).closest('.textarea'));
            return bool;
        }
    });

    function checkEmoji(wrap){
        let bool = $(wrap).attr('data-bool');
        bool = parseInt(bool);
        if ( bool == 0 ) {
            bool = false;
        }else{
            bool = true;
        }
        if ( !bool ) {
            $(wrap).find('.emojionearea-emojis-list').addClass('disabled');
        }else{
            $(wrap).find('.emojionearea-emojis-list').removeClass('disabled');
        }
    }

    $(document).on('click', '.emojibtn', function(e){
        checkEmoji($(this).closest('.textarea'));
    });

    $(document).on('DOMSubtreeModified', '.maxlength .emojionearea-editor', function(){
        let block = $(this);
        let wrap_all = block.closest('.textarea');
        let block_maxlength = wrap_all.find('.textarea-block__textarea').attr('maxlength');
        let img_length = block.find('img').length;
        let text_length = block.text().length + img_length;
        if ( text_length >= block_maxlength ) {
            $(wrap_all).attr('data-bool', 0);
        }else{
            $(wrap_all).attr('data-bool', 1);
        }
        wrap_all.find('.count_limit').text(block_maxlength - text_length);
        checkEmoji($(this).closest('.textarea'),text_length,block_maxlength);
    });


    let max_tags = 4;
    $('.select_jq').select2({
        maximumSelectionLength: max_tags,
    });

    function closePopUp(wrap) {
        wrap.fadeOut(400);
        $('body').removeClass('noscroll');
        return false;
    }

    function openPopUp(wrap) {
        $('.wrap-pop-up').fadeOut(100);
        $(wrap).fadeIn(300);
        $('body').addClass('noscroll');
        return false;
    }

    $('.registration_open').on('click', function() {
        openPopUp($('#registration'));
        return false;
    });

    $('.signIn_open').on('click', function() {
        openPopUp($('#sign_in'));
        return false;
    });

    $('.forgotPass_open').on('click', function() {
        openPopUp($('#forgot-pass'));
        return false;
    });

    $('.wrap-pop-up .close-window').on('click', function() {
        closePopUp($(this).closest('.wrap-pop-up'));
    });

    jQuery(function($){
        $('.wrap-pop-up').mouseup(function (e){
            var div = $('.pop-up-body');
            if (!div.is(e.target)
                && div.has(e.target).length === 0) {
                closePopUp($('.wrap-pop-up'));
                return false;
            }
        });
    });

    $(document).on('input','.inp_maxlength', function() {
        var wrap = $(this).closest('.input-el');
        var maxlength = $(this).attr('maxlength');
        var val = $(this).val().length;
        wrap.find('.count_limit').text( maxlength - val );
    });

    $('.images').slick({ // это изначально slick слайдер для основного блока изображений
        slidesToShow: 1,  // по одному слайдеру
        slidesToScroll: 1, // по одному менять
        arrows:true, // включение стрелок (если не нужны false)
        asNavFor: '.imagesnew_dotted',
        nextArrow: '<div class="slick-next"></div>',
        prevArrow: '<div class="slick-prev"></div>',
        infinite: false,
    });

    $('.imagesnew_dotted').slick({ // настройка навигации
        slidesToShow: 4, // указываем что нужно показывать 3 навигационных изображения
        asNavFor: '.images', // указываем что это навигация для блока выше
        focusOnSelect: true,
        arrows: false,
        swipe: false,
        infinite: false,
    });

    $('.mobMenu a').click(function() {
        return false;
    });

    $('.mobBurger a').on('click', function() {
        if ( $('.wrap-menu-window-mobile').hasClass('active') ) {
            $('.wrap-menu-window-mobile').removeClass('active');
            $('body').removeClass('noscroll');
        }else{
            $('.wrap-menu-window-mobile').addClass('active');
            $('body').addClass('noscroll');
        }
        return false;
    });

// $('.inp_comment').emojiarea({button: '.emoji'});

    $('.user').on('click', function() {
        let wrap_all = $(this);
        if ( wrap_all.hasClass('open') ) {
            wrap_all.removeClass('open');
            closeUserMenu();
        }else{
            wrap_all.addClass('open');
            wrap_all.find('.user-menu').fadeIn(200);
        }
    });

    function closeUserMenu() {
        $('.user').removeClass('open');
        $('.user-menu').removeClass('open');
        $('.user-menu').fadeOut(200);
    }

    jQuery(function($){
        $('html,body').mouseup(function (e){
            var div = $('.main-content-top .user');
            if (!div.is(e.target)
                && div.has(e.target).length === 0) {
                closeUserMenu();
                return false;
            }
        });
    });

    $('#messages-content-body').scrollTop(100000);

    if ( $(window).width() <= 1000 ) {
        // $('body').scrollTop($('body').outerHeight());
    }

    $('.settings-content-el-top').on('click', function() {
        var wrap = $(this).closest('.settings-content-el');
        if ( wrap.hasClass('active') ) {
            closeSettignsEl(wrap);
        }else{
            wrap.addClass('active');
            wrap.find('.settings-content-el-body').slideDown(250);
        }
    });

    function closeSettignsEl(wrap) {
        wrap.removeClass('active');
        wrap.find('.settings-content-el-body').slideUp(250);
    }

    $('form.form-settigns-el').on('submit', function(e) {
        e.preventDefault();
        closeSettignsEl($(this).closest('.settings-content-el'));
        return false;
    });

    if ( $(window).width() > 600 ) {
        // var el = $('.textarea-block__textarea').emojioneArea({
        // 	search: false,
        // });
        $('.inp_comment').click(function(){
            $(this).focus();
        });

    }


    function closeSearchResults() {
        $('.search-results').fadeOut(100);
    }

    jQuery(function($){
        $('html,body').mouseup(function (e){
            var div = $('.search');
            if (!div.is(e.target)
                && div.has(e.target).length === 0) {
                closeSearchResults();
                return false;
            }
        });
    });


    $('.close-mail').click(function() {
        $('.confirm-email').fadeOut(100);
    });



    $(document).on('click', '.reply-comment-btn', function() {
        $('.reply-comment-btn').removeClass('btn-form');
        $(this).addClass('btn-form');
        var wrap_all = $(this).closest('.comment-reply');
        var textarea_block = wrap_all.find('.wrap-fade');
        if ( wrap_all.hasClass('init') ) {
            if ( wrap_all.hasClass('active') ) {
                // wrap_all.removeClass('active');
                // $('.comment-reply').find('.textarea-block').fadeOut(300);
            }else{
                $('.content-comments').find('.wrap-fade').fadeOut(300);
                $('.content-comments').find('.comment-reply').removeClass('active');
                wrap_all.addClass('active');
                textarea_block.fadeIn(300);
            }
        }else{
            $('.content-comments').find('.wrap-fade').fadeOut(300);
            $('.content-comments').find('.comment-reply').removeClass('active');

            wrap_all.addClass('init');
            wrap_all.addClass('active');

            // var inner_block = '\
            // 	<div class="textarea-block">\
            // 		<textarea class="textarea-block__textarea" data-emojiable="true" placeholder="Напишите сообщение"></textarea>\
            // 		<div class="textarea-block-media">\
            // 			<div class="textarea-block-media_el">\
            // 				<div class="load_media">\
            // 					<label class="unselectable">\
            // 						<input type="file" multiple="" class="inp-file-textarea" data-maxfiles="2">\
            // 						<img src="/img/camera.svg" alt="">\
            // 					</label>\
            // 				</div>\
            // 			</div>\
            // 		</div>\
            // 	</div>';
            // wrap_all.prepend(inner_block);

            // initTexarea(wrap_all.find('.textarea-block__textarea'));
            textarea_block.fadeIn(300);
            textarea_block.fadeIn(300);
        }
        return false;
    });



    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || $(window).width() <= 700 ) {

    }else{
        $(document).on('keydown', '.emojionearea-editor', function(event) {
            if (event.keyCode == 13) {
                if(event.shiftKey){
                } else {
                    location.reload(); //сюда вешаешь обработку
                    return false;
                }
            }
        });
    }




    function errorFiles(maxFiles){
        $('#error-files .count_max').text(maxFiles);
        openPopUp($('#error-files'));
        for( var i=0; i < $('.wrap-files .file').length; i++ ){
            if ( i<10 ) {
            }else{
                $(`.wrap-files .file:eq(${i})`).remove();
            }
        }
    }

// Здесь отслеживаю добавление картинок в html
    $(document).on('DOMSubtreeModified', '.wrap-files', function(){
        let maxFiles = $('.inp-file').data('maxfiles');
        if ( $(this).find('.file').length > maxFiles ) {
            errorFiles(maxFiles);
        }
    });

//Здесь отслеживаю добавление картинок через браузер
    $(document).on('change', '.inp-file-textarea', function(){
        let maxFiles = $(this).data('maxfiles');
        if ( $(this)[0].files.length > maxFiles ) {
            errorFiles(maxFiles);
        }
    });

// $('.confirm-email').click(function(){
// 	$('.wrap-files').append(`</li><li class="file">
// 		<div class="delete" title="Удалить"></div>
// 		<img src="/img/slider/1.png" alt="">
// 	</li>`);
// });

});
