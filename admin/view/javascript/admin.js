var admin_block_width = 270;
var cl = 'uac-controls';
var ADMIN_TIMER;
var clear_timeout = 1000;

$(function() {
    /* For demo purposes */
    var demo = $("#admin-button").css({
        position: "fixed",
        top: "150px",
        right: "0",
        background: "rgba(0, 0, 0, 0.7)",
        "border-radius": "5px 0px 0px 5px",
        padding: "10px 15px",
        "font-size": "16px",
        "z-index": "999999",
        cursor: "pointer",
        color: "#ddd"
    }).addClass("no-print");

    var demo_settings = $("#admin-block").css({
        "padding": "10px",
        position: "fixed",
        top: "130px",
        right: "-" + admin_block_width + "px",
        background: "#fff",
        border: "3px solid rgba(0, 0, 0, 0.7)",
        "width": admin_block_width + "px",
        "z-index": "999999"
    }).addClass("no-print");

    demo.click(function() {
        if (!$(this).hasClass("open")) {
            $(this).css("right", admin_block_width + "px");
            demo_settings.css("right", "0");
            $(this).addClass("open");
        } else {
            $(this).css("right", "0");
            demo_settings.css("right", "-" + admin_block_width + "px");
            $(this).removeClass("open");
        }
    });

});

$(document).ready(function() {
    
        $('.checkbox-3-value').kCheckbox({
            onChecked: function() {
                var ug_id = $(this).attr('ug-id');
                $('input.acc-cb[user-group="' + ug_id + '"]').prop('checked', true);
            },
            onUnchecked: function() {
                var ug_id = $(this).attr('ug-id');
                $('input.acc-cb[user-group="' + ug_id + '"]').prop('checked', false);
            }
        });
    
});

function adminMode(mode) {
        if(mode === 0) {
                $('.uacc-mode-action').addClass('hidden');
                $('#admin-activate').removeClass('hidden');
                $('.' + cl).remove();
                $('.uam').remove();
        } else {
                adminMode(0);
                $('.uacc-mode-action').removeClass('hidden');
                $('#admin-activate').addClass('hidden');
                loadUserAccess();
        }
}

function saveOnServer() {
        var els = [];
        $('*[uac-c]').each(function() {
            uacc = $(this).attr('uac-c');
            els.push(uacc);
        });

        data = $('.uam input:checked').serializeArray();
        $.ajax({
            url: 'index.php?route=user/user_permission/update&token=' + getURLVar('token'),
            data: {
                data: data,
                els: els,
                _route: getURLVar('route')
            },
            type: 'post',
            dataType: 'json',
            success: function(data) {
                if(data.success) {
                    alert('Настройки сохранены');
                } else {
                    if(data.message) {
                        alert(data.message);
                    } else {
                        alert('Ошибка сохранения');
                    }
                }
            }
        });        
}

function saveUserGroupAccess() {
        saveOnServer();
        adminMode(0);
}

function cancelUserGroupAccess() {
        adminMode(0);
}

function loadUserAccess() {
        
        var els = [];
        
        $('*[uac-c]').each(function() {
            $this = $(this);
            uacc = $this.attr('uac-c');
            $this.prepend('<div class="' + cl + '" cont-menu="' + uacc + '"><i class="fa fa-gear"></i></div>');
            html_menu = '<ul class="dropdown-menu uam uam-' + uacc + '" role="menu" style="display:none">' + uacc_html + '</ul>';
            $html_menu = $(html_menu);
            $html_menu.find('input[type="checkbox"]').attr('value', uacc);
            if(!((uacc.indexOf('lm-') > -1) || (uacc.indexOf('tm-') > -1))) {
                    $html_menu.find('input[type="checkbox"]').addClass('acc-cb');
            }
            $('body').append($html_menu);
            els.push(uacc);
        });
        
        $.ajax({
            url: 'index.php?route=user/user_permission/access&token=' + getURLVar('token'),
            data: {
                els: els,
                _route: getURLVar('route')
            },
            type: 'post',
            dataType: 'json',
            beforeSend: function (xhr) {
                $('.uacc-mode-action input[ug-id]').val(0).trigger('refresh');
            },
            success: function (data, textStatus, jqXHR) {
                if(data.success) {
                    $.each(data.groups, function(i1, v1) {
                        ug_id = v1.user_group_id;
                        $.each(v1.access, function(i2, v2) {
                            $('input[user-group="' + ug_id + '"][value="' + v2 + '"]').prop('checked', true);
                        });
                        checkCheckboxes(ug_id);
                    });
                    
                    $("." + cl).accessMenu({});

                    ADMIN_TIMER = setTimeout(function() { checkNewElements(); }, clear_timeout);
                }
            }
        });
}

function checkCheckboxes(ug_id) {
        if(!$('input.acc-cb[user-group="' + ug_id + '"]:checked').length) {
            $('input[ug-id="' + ug_id + '"]').val(0).trigger('refresh');
        } else {
            if($('input.acc-cb[user-group="' + ug_id + '"]:checked').length == $('input.acc-cb[user-group="' + ug_id + '"]').length) {
                $('input[ug-id="' + ug_id + '"]').val(1).trigger('refresh');
            } else {
                $('input[ug-id="' + ug_id + '"]').val(-1).trigger('refresh');
            }
        }
}

function checkNewElements() {
        
        clearTimeout(ADMIN_TIMER);
        ADMIN_TIMER = setTimeout(function() { checkNewElements(); }, clear_timeout);
}

(function ($, window) {

    $.fn.accessMenu = function (settings) {
        
        $(this).each(function() {
            var $el = $(this);
            var $menu = $('.uam-' + $el.attr('cont-menu'));
            $el.on('mouseover', function(e) {
                $('.uam').hide();
                if($menu.css('display') != 'none') {
                        return false;
                }
                $menu.css({
                        position: "absolute",
                        left: getLeftLocation(e, $menu),
                        top: getTopLocation(e, $menu),
                        padding: '10px'
                    }).show();
            });
            $menu.on('mouseleave', function() {
                $menu.hide();
            });
        });
        
        function getLeftLocation(e, el) {
            var mouseWidth = e.pageX;
            var pageWidth = $(window).width();
            var menuWidth = $(el).width();

            // opening menu would pass the side of the page
            if ((mouseWidth + menuWidth + 40) > pageWidth &&
                menuWidth < mouseWidth) {
                return mouseWidth - menuWidth;
            } 
            return mouseWidth;
        }        
        
        function getTopLocation(e, el) {
            var mouseHeight = e.pageY;
            var pageHeight = $(window).height();
            var menuHeight = $(el).height();

            // opening menu would pass the bottom of the page
            if (mouseHeight + menuHeight > pageHeight &&
                menuHeight < mouseHeight) {
                return mouseHeight - menuHeight;
            } 
            return mouseHeight;
        }

    };
})(jQuery, window);

