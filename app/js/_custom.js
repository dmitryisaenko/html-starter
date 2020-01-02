document.addEventListener("DOMContentLoaded", function() {

	// Скрытие/отображение пунктов меню для моб. версии
    var $toggleButton = $('.toggle-button'),
		$navWrap = $('.nav-wrap');
		$menuItemHasChildren = $('.menu-item-has-children');
		
		$toggleButton.on('click', function() {
            $(this).toggleClass('button-open');
            $navWrap.toggleClass(['menu-show', 'mobile']);
            // $navWrap.toggleClass('mobile');
		});

		//Show sub-menu item in mobile version
		$menuItemHasChildren.click(function() {
			$menuItemHasChildren.not(this).children('.sub-menu').hide(300);
			$(this).children('.sub-menu').toggle(300);
		});

	
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
