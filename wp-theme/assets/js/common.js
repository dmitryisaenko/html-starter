
$(document).ready(function(){
    var $slideItem = $( "ul.carousel-dots li" );
    var ifClick = false;
    $slideItem.on('click', function (event) {
        event.preventDefault();
        $( "ul.carousel-dots li" ).not(this).removeClass("active");
        $(this).addClass("active");
        ifClick = true;
    });

    $('#header-carousel').on('slide.bs.carousel', function (ev) {
        if (ifClick) {
            ifClick = false;
        }
        else {
            $holder = $( "ul.carousel-dots li.active" );
            $holder.next( "li" ).addClass("active");
            if($holder.is(':last-child'))
            {
                $holder.removeClass("active");
                $("ul.carousel-dots li:first").addClass("active");
            }
            $holder.removeClass("active");
        }
    });
    
    

    // Скрытие/отображение пунктов меню для моб. версии
    var $toggleButton = $('.toggle-button'),
        $navWrap = $('.nav-wrap');
        $learnButton = $('.header-content a.learn-more-btn');
        $toggleButton.on('click', function() {
            $(this).toggleClass('button-open');
            $navWrap.toggleClass('menu-show');
            $learnButton.toggleClass('zindex2');
        });

    // $('body,html').on('click', function() {
    //     $navWrap.removeClass('menu-show');
    // });

    // $(document).mouseup(e => {
    //     if ( ($toggleButton.is(e.target) || (!$navWrap.is(e.target) && $navWrap.has(e.target).length === 0) ))
    //     {
    //             $toggleButton.removeClass('button-open');
    //             $navWrap.removeClass('menu-show');
    //             $toggleButton.removeClass('button-open');
    //         }
    // });

    // Плавный переход между разделами сайта при клике на пункты меню
    $("#menu").on("click","a", function (event) {
        event.preventDefault();
        var id  = $(this).attr('href'),
            top = $(id).offset().top;
        $('body,html').animate({scrollTop: top}, 1000);
    });

    

    
    // Кнопка "Наверх" с появлением
    var top_show = 550; // В каком положении полосы прокрутки начинать показ кнопки "Наверх"
    var delay = 1000; // Задержка прокрутки
    $(document).ready(function() {
        $(window).scroll(function () { // При прокрутке попадаем в эту функцию
        /* В зависимости от положения полосы прокрукти и значения top_show, скрываем или открываем кнопку "Наверх" */
        if ($(this).scrollTop() > top_show) $('#back-top').fadeIn();
        else $('#back-top').fadeOut();
        });
        $('#back-top').click(function () { // При клике по кнопке "Наверх" попадаем в эту функцию
        /* Плавная прокрутка наверх */
        $('body, html').animate({
            scrollTop: 0
        }, delay);
        });
    });
   
  });

  