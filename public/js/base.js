/* *************************************************************************** */
/* ==============================    APP CODE =================================*/
/* *************************************************************************** */
$(function () {

    $('#user_logout').click(function () {
        $.post('/user/logout',function () { location.href = "/" });
        return false;
    });
    // ----------- корзина --------
    if (location.href.indexOf('openCart') > 0) openCart();
});

function addFavorite(item_id) {
    $.get('/addFavorite/' + item_id, function () {
        $('#removeFavorite_butt').show();
        $('#addFavorite_butt').hide();
    })
}

function removeFavorite(item_id) {
    $.get('/removeFavorite/' + item_id, function () {
        $('#removeFavorite_butt').hide();
        $('#addFavorite_butt').show();
    })
}


/* *************************************************************************** */
/* ==============================    TOOLS    =================================*/
/* *************************************************************************** */
function copytext(id) {
    var copyText = document.getElementById(id);
    copyText.select();
    document.execCommand("copy");
    alert('Ссылка на страницу скопирована в буфер обмена');
}

function checkboxProcess(id) {
    if ($('#' + id + ' input[type=checkbox]').attr('checked') == 'checked')
        $('#' + id + ' input[type=hidden]').val(0)
    else $('#' + id + ' input[type=hidden]').val(1);
    $('#' + id + ' input[type=hidden]').trigger('change');
}
function IsEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

$(function () {
    /* ============= open in new window */
    $('.external').on("click",
        function () {
            w = 1000;
            h = 600;
            atr = 'toolbar=no,';
            atr = atr + 'width=' + w + ',' + 'height=' + h + ',';
            atr = atr + ' left=' + String((screen.width - w) / 2) + ', top=150';
            new_window = window.open($(this).attr('href'), '_blank', atr);
            new_window.focus();
            return false;
        }
    );
});


/* *************************************************************************** */
/* ============================== vega DIALOG && modal-block ==================*/
/* *************************************************************************** */
$(function () {
    $(document).on('click', '.vega_dialog', function (e) {
        var div = $(this);
        if (div.is(e.target) && div.has(e.target).length === 0) {
            if(div.data('no-close')!=1) div.hide();
        }
    });
    $(document).on('click', '.vega_dialog .vega_dialog_close', function () {
        $(this).closest('.vega_dialog').hide();
    });
    // close bloks from click ousides
    $(document).mouseup(function (e) {
        var container = $(".modal");
        if (container.has(e.target).length === 0) {
            container.hide();
        }
    });
});

function vega_dialog_open(id) {
    $('#' + id).attr('style', 'display: flex;');
    $('#' + id + ' .vega_dialog_content').css('maxHeight', ($(window).height() - 150));
}
function vega_dialog_close(id) {
    $('#' + id).attr('style', 'display: none;');
}
function vega_confirm(id,descr,butt_name,callback) {
    $('#'+id+' #descr').html(descr);
    $('#'+id+' #button').html(butt_name);
    $(document).off('click', '#'+id+' #button');
    $(document).on('click', '#'+id+' #button', function () {
        if (callback instanceof Function) callback();
        vega_dialog_close(id);
    });
    vega_dialog_open(id);
}
/* *************************************************************************** */
/* ============================ vega AJAX ==================================== */

/* *************************************************************************** */
function vegaFormSubmit(form, url, success_callback, error_callback) {
    $(form + ' #form_load_ico').show();
    $.post(url, $(form).serialize(), function (data) {
        $(form + ' .vega_form_row').each(function () {
            $(this).removeClass('error');
            $(this).find('.error_msg').text('')
        })
        $(form + ' #form_load_ico').hide();
        $(form + ' #save_error').hide();
        $(form + ' #save_success').show();
        setTimeout(function () {
            $(form + ' #save_success').hide();
        }, 2000)
        if (success_callback instanceof Function) success_callback(data);
    })
        .fail(function (data) {
            errors = data.responseJSON.errors;
            $(form + ' .vega_form_row').each(function () {
                $(this).removeClass('error');
                $(this).find('.error_msg').text('')
            })
            for (var key in errors) {
                $(form + ' #' + key ).closest('.vega_form_row').addClass('error')
                $(form + ' #' + key ).closest('.vega_form_row').find('.error_msg').text(errors[key])
            }
            $(form + ' #form_load_ico').hide();
            $(form + ' #save_success').hide();
            $(form + ' #save_error').show();

            $('html, body').stop().animate({
                'scrollTop': $(form + ' .error').offset().top-80
            }, 100, 'swing');

            if (error_callback instanceof Function) error_callback(data);
        });
}

$(document).on('click', '.vega_form_row [type=checkbox]', function (e) {
    var val = $(this).prop('checked') ? 1 : 0;
    var input = $(this).closest('.vega_form_row').find('[type=hidden]');
    input.val(val).trigger('change');
});

function vegaBlockLoad(elem, url, success) {
    //$(elem).hide();
    $(elem + '_load').show();
    $(elem).load(url, function (data) {
        $(elem + '_load').hide();
        //$(elem).show();
        if (success instanceof Function) success(data);
    })
}

// ---------- подгрузка элементов -----------------------
function getItemsData(button) {
    url = $(button).attr('data-url');
    item_block_id = $(button).attr('item-block-id');
    page_size = parseInt($(button).attr('page-size'));
    items_all = parseInt($(button).attr('items-all'));
    offset = parseInt($(button).attr('offset'));

    page_size = parseInt(page_size);
    if (items_all > offset) {
        window.item_load_flag = 1;
        $(button).find('.js-items_load_ico').show();
        $.get(url + '&offset=' + offset, function (data) {
            $(item_block_id).append(data);
            $(button).find('.js-items_load_ico').hide();
            offset_current = parseInt($(button).attr('offset')) + page_size;
            $(button).attr('offset', offset_current);
            // -------- calc info
            $(item_block_id).find('.js-items_left').text(items_all - offset_current)
            if ((items_all - offset_current) < page_size)
                $(item_block_id).find('.js-page_size_left').text(items_all - offset_current);
            //if ((items_all - offset_current) <= 0) $(item_block_id).hide();
            // ----- for trigged of scroll
            window.item_load_flag = 0;
        })
    }
}

$(function () {
    // ---------- автоподгрузка элементов -----------------------
    window.items_list_block = '#auto_page_button';
    var element = $(window.items_list_block);
    if (element.length > 0) {
        window.item_load_flag = 0;
        $(window).scroll(function () {
            var scroll = $(window).scrollTop() + $(window).height();
            var offset = element.offset().top - 400;
            if (scroll > offset && window.item_load_flag == 0) {
                getItemsData(window.items_list_block);
            }
        });
    }
});


/* *************************************************************************** */
/* ======================= vega FILE UPLOADS =======================================*/
/* *************************************************************************** */
//todo уйти от параметров в урле или сделать проверку на белый список!
//todo уйти от инлайновых вызовов!
function vegaFileUpload(field_id, params, success_callback, error_callback) {
    var path = 'abc/files/temp/'
    var file_data = $('#' + field_id + ' #file').prop('files')[0];
    if(!file_data) {
        console.log('vegaFileUpload - file_data=undefined')
        return;
    }
    $('#' + field_id + ' .error_msg').html('');
    $('#' + field_id + ' #load_ico').show();
    vegaFilePush(file_data, params,
        function (data) {
            var url_n = '/' + path + data.filename;
            $('#' + field_id + ' input[type=hidden]').val(data.filename);
            $('#' + field_id + ' #image').attr('src', url_n);// если фото
            $('#' + field_id + ' #link').text(data.filename);// если файл
            $('#' + field_id + ' #link').attr('href', url_n);// если файл
            $('#' + field_id + ' input[type=hidden]').trigger('change');
            $('#' + field_id + ' .error_msg').html('');
            $('#' + field_id + ' #delete_button').show();
            $('#' + field_id + ' #load_ico').hide();
            if (success_callback instanceof Function) success_callback(data);
        },
        function (data) {
            $('#' + field_id + ' .error_msg').html(data.error);
            $('#' + field_id + ' #load_ico').hide();
            if (error_callback instanceof Function) error_callback(data);
        }
    );
}

function vegaFileClear(field_id) {
    //if (!confirm('Удалить ?')) return false;
    $('#' + field_id + ' input[type=hidden]').val('');
    $('#' + field_id + ' input[type=hidden]').trigger('change');
    $('#' + field_id + ' #image').removeClass('exist');// если фото
    $('#' + field_id + ' #add_label').removeClass('hide');// если фото
    $('#' + field_id + ' #update_label').addClass('hide');// если фото
    //$('#' + field_id + ' #delete_button').hide();
    $('#' + field_id + ' #image').attr('src', '/img/no_img.jpg');// если фото
    $('#' + field_id + ' #link').text('Файл не выбран');// если файл
    $('#' + field_id + ' #link').attr('href', '');// если файл
    return false;
}

function vegaFilePush(file_data, params, success_callback, error_callback) {
    var form_data = new FormData();
    form_data.append('file', file_data);
    $.ajax({
        url: '/ajaxupload?' + params, dataType: 'json', cache: false,
        contentType: false, processData: false,
        data: form_data, type: 'post',
        success: function (data) {
            if (typeof (data.error) != 'undefined') {
                if (error_callback instanceof Function) error_callback(data);
            } else {
                if (success_callback instanceof Function) success_callback(data);
            }
        },
        error: function (data) {
            alert(data.responseText);
        }
    });
}

function vegaFileMulti(t, params, success_callback, error_callback) {
    $.each($(t).prop('files'), function (i, file) {
        vegaFilePush(file, params, success_callback, error_callback);
    });
}

/* *************************************************************************** */
/* ============================= multiselect ====================================*/
/* *************************************************************************** */
$(function () {
    $(document).on('click', '.multi_select .add', function () {
        $(this).closest('.multi_select').find('.list').show();
    })
    $(document).on('click', '.multi_select .elem', function () {
        var data = $(this).closest('.multi_select').find('.data');
        var exist = data.val().split(',').includes($(this).data('id').toString());
        if(exist==false) {
            data.val(data.val() + (data.val()==''?'':',') + $(this).data('id'));
            data.trigger('change');
            var content = $(this).closest('.multi_select').find('template').html();
            content = content.replace(/{id}/g, $(this).data('id'));
            content = content.replace(/{name}/g, $(this).data('name'));
            $(this).closest('.multi_select').find('.add').before(content)
        }
    })
    $(document).on('click', '.multi_select .value .delete_butt', function () {
        var data = $(this).closest('.multi_select').find('.data');
        var values = $(this).closest('.values');
        $(this).closest('.value').remove();
        var ids = [];
        values.find('.value').each(function () {
            ids.push($(this).data('id'))
        })
        data.val(ids.join(','));
        data.trigger('change');
    })
});

/* *************************************************************************** */
/* ============================= HYPERTEXT ====================================*/
/* *************************************************************************** */
$(function () {
    $(document).on("click", ".js_hypertext_add a", function () {
        var parent = $(this).parents(".hypertext"),
            n = 0;
        $(".hypertext_item", parent).each(function () {
            var i = $(this).data('n');
            if (i > n) n = i;
        });
        n++;
        template = $(this).data('template');
        var content = $(template).html();
        content = content.replace(/{n}/g, n);
        $(this).closest('.hypertext_item').before(content);
        hypertext_init();
        update=1;//глобальная переменная для отслеживания изменения формы
        return false;
    });
    $(document).on("click", ".js_hypertext_remove", function () {
        if(confirm('Подтверждение удаления')) {
            var id = $(this).closest('.hypertext_item').find('textarea').attr('id');
            if (id) {
                tinymce.EditorManager.execCommand('mceRemoveEditor', true, id);
            }
            $(this).closest('.hypertext_item').remove();
            update = 1;//глобальная переменная для отслеживания изменения формы
        }
        return false;
    });
    $(document).on("click", ".hypertext_images_item .delete", function () {
        $(this).closest('.hypertext_images_item').remove();
        update=1;//глобальная переменная для отслеживания изменения формы
        return false;
    });
    $(document).on("change", ".hypertext_item #file", function () {
        var id = '#'+$(this).closest('.hypertext_item').attr('id');
        var num = $(this).closest('.hypertext_item').data('n');
        hypertextImages(id,num);
        return false;
    });
    hypertext_init();
});

function hypertextImages(block_id, num) {
    var path = 'abc/files/temp/'
    var params = 'maxSize=10&types=jpg,jpeg,heic,png,gif&min_res=1x1';
    n = 0;
    $(block_id + ' .hypertext_images_item').each(function () {
        var i = $(this).data('i');
        if (i > n) n = i;
    });
    $.each($(block_id + ' #file').prop('files'), function (i, file) {
        vegaFilePush(file, path, params, function (data) {
            //console.log(data.filename)
            var content = $(block_id + ' template').html();
            content = content.replace(/{img}/g, '/' + path + data.filename);
            content = content.replace(/{file_name}/g, data.filename);
            content = content.replace(/{num}/g, num);
            n++;
            content = content.replace(/{i}/g, n);
            $(block_id + ' .images').append(content);
        });
    });
}

function hypertext_init() {
    tinymce.init({
        selector: ".tinymce textarea.hypertext",
        language: "ru",
        plugins: [
            "advlist autolink lists link image charmap anchor",//preview  print
            "visualblocks code",//fullscreen
            "media table contextmenu paste textcolor ",//
            "hr"
        ],
        browser_spellcheck: true,
        extended_valid_elements: "div[itemtype|itemscope|itemprop|style|class|id],span[itemtype|itemscope|itemprop|style|class],@[itemtype|itemscope|itemprop|id|class|style|title|dir<ltr?rtl|lang|xml::lang|onclick|ondblclick|onmousedown|onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|onkeydown|onkeyup],hr[id|title|alt|class|width|size|noshade|style],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style],a[id|class|name|href|target|title|onclick|rel|style],script[type|src]",
        toolbar1: "bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | style-h2 style-h3 | link unlink anchor | code | removeformat visualchars visualblocks nonbreaking | blockquote | undo redo |",
        menubar: false,
        image_title: true,
        content_css: "/abc/admin/templates2/assets/css/tinymce.css?",
        relative_urls: false,
        remove_script_host: false,
        setup: function (ed) {
            ed.on("blur", function (e) {
                //$(".form").data("changed",true);
                $(".form").trigger("input");
            });
            ed.on("change", function (e) {
                update=1;//глобальная переменная для отслеживания изменения формы
            });
        }
    });
}
