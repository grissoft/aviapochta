var settings = {
        autoReinitialise: true,
        verticalDragMinHeight: 50,
        verticalDragMaxHeight: 50
    };
// var pane = $('.popup-content')
// pane.jScrollPane(settings);
// var contentPane = pane.data('jsp').getContentPane();

var contentPane = $('.popup-content');
var hidePlanet, showPlanet;
     
function stop(){
    hidePlanet = setTimeout(function(){
//        $('.planet').fadeOut(700);
//        $('.main-bg').fadeIn();
    }
    ,300
    );
}

function start(){
    showPlanet = setTimeout(function(){
//        $('.planet').fadeIn(700);
//        $('.main-bg').fadeOut();
    }
    ,200
    );
}

$(function(e) {


    $(document).mouseup(function (e)
    {
        var container = $(".popup, .lang ul");          
        var container2 = $(".lang ul");          
        var el = $('.show_info');
        if (!$(e.target).hasClass('show_info')){
            if (container.has(e.target).length === 0){
                container.fadeOut();
            }
            if (container2.has(e.target).length === 0){
                container2.hide();
                $('.hide-lang').toggleClass('show-lang hide-lang');
            }
        }        
    });


    $('.about').mouseenter(function(){
        $('.about-bg').addClass('hover');
    }).mouseleave(function(){
        $('.about-bg').removeClass('hover');
    }).click(function(){
        contentPane.empty();
        contentPane.load('index.php?route=information/information/info&information_id=4');
        $('.popup').fadeIn();
        return false;
    });

    $('.contacts').mouseenter(function(){
        $('.contacts-bg').addClass('hover');
        
    }).mouseleave(function(){
        $('.contacts-bg').removeClass('hover');        
    }).click(function(){
        contentPane.empty();
        contentPane.load('index.php?route=information/information/info&information_id=7');
        $('.popup').fadeIn();
        return false;
    });

    $('.vacancies').mouseenter(function(){
        $('.partners-bg').addClass('hover');
        
    }).mouseleave(function(){
        $('.partners-bg').removeClass('hover');
        
    }).click(function(){
        contentPane.empty();
        contentPane.load('vacancies.html');
        $('.popup').fadeIn();
        return false;
    });

    $('.services').mouseenter(function(){
        $('.services-bg').addClass('hover');
        $('.services ul').show();        
    }).mouseleave(function(){
        $('.services-bg').removeClass('hover');
        $('.services ul').hide();        
    });
    
    
    $('.services .btn').click(function(){
        contentPane.empty();
        contentPane.load('index.php?route=account/pack/info&pack_id='+$('#pack-id').val());
        $('.popup').fadeIn();
        return false;
    });
    
    $('.services ul a').click(function(){
        contentPane.empty();
        contentPane.load( $(this).parent().attr('class') +'.html');
        $('.popup').fadeIn();
        return false;
    });

    $('.page-table').height($(window).height());

    $(window).resize(function(){
        $('.page-table').height($(window).height());
    });

    $('.logo a').mouseenter(function(){
        clearTimeout(hidePlanet);
        start();
    }).mouseleave(function(){
        clearTimeout(showPlanet);
        stop();        
    });

    $('.popup .close').click(function(){
        $('.popup').fadeOut();
        contentPane.empty();
    });

    $('.lang').click(function(){
        if ($(this).hasClass('show-lang')){
            $('.lang ul').show();
            $(this).toggleClass('show-lang hide-lang');
        }            
        else{
            $('.lang ul').hide();
            $(this).toggleClass('show-lang hide-lang');
        }            
    });
    $('.lang li').click(function(){
        $('.lang span').text($(this).text());
        $('.lang li').removeClass('current');
        $(this).addClass('current');
        document.location.pathname = document.location.pathname + 'en';
        console.log(document.location.pathname);
    });

    // $('.popup-content').jScrollPane({
    //     autoReinitialise: true,
    //     verticalDragMinHeight: 50,
    //     verticalDragMaxHeight: 50,
    //     verticalGutter: 30,
    //     horizontalGutter: 30
    // });
});