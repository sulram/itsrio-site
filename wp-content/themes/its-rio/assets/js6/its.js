let Vue = require('vue');

import { map, filter } from 'lodash';

Vue.prototype.filters = {
	filterBy(list, value){
		return list.filter(function(item) {
			return item.indexOf(value) > -1;
		});
	},
	findBy(list, value){
		return list.filter(function(item) {
			return item == value
		});
	},
	reverse(value){
		return value.split('').reverse().join('');
	},
	limit(i, max){
		return i <= max;
	}
}

Vue.component('its-aulas', {
	data(){
		return { aulas };
	}
});	

Vue.component('its-map', {
	data(){
		return { markers, selectedMarker : false }
	},
	methods:{
		openMarker(marker){
			this.selectedMarker = marker;
			setTimeout(function(){
				jQuery('.map-info-carousel').flickity({
					wrapAround: true,
					cellSelector: '.map-info-carousel-item'
				});
			},1000);
		}
	}
});

Vue.component('its-comunicados', {
	data(){
		return { comunicados };
	}
});	

Vue.component('its-pessoas', {
	data(){
		return { pessoas };
	}
});

Vue.component('its-informacoes', {
	data(){
		return typeof aulas != 'undefined' ? { informacoes, aulas } : { informacoes } ;
	}
});

new Vue({
	el : '#content_all',
	data : site_data ,
	mounted(){
		$ = jQuery;

		$('.home-cover').css('height',$(window).height()+'px');

		$('.related-post .large-4:gt(2)').hide();

		$('.comunicados h2 > a').click(function(){
			if($(this).text().indexOf("ver") > -1){
				$('.content-area:not(.comunicados)').hide();
				$(this).text('voltar para institucional');

				$('.related-post .large-4').show();

				$('html, body').animate({
					scrollTop: 0
				}, 300);
			}else{
				$('.content-area').show();
				$('.related-post .large-4:gt(2)').hide();

				$(this).text('ver todos');
			}

			$('.comunicados .related-post').masonry({
				columnWidth : '.large-4',
				selector : '.large-4',
				percentPosition: true,
			});
		});

			//Fixa o menu interno no menu global ao dar scroll
		var menu = $('.header-single-menu');

		try{
			var top = menu.position().top;
			$(window).scroll(function(){
				if($(this).scrollTop() >= top - 65)
					menu.addClass('fixed');
				else
					menu.removeClass('fixed');
			});
		}catch(e){}

			//Adiciona a classe de active ao post type correspondente no menu global.
		$("a[href='/"+post_type+"']").parent().addClass('current-menu-item');


			//Smooth scroll
		$('a[href*="#"]:not([href="#"]), .single-menu ul li ').click(function() {
			var el =  $(this).is('a') ? this : $(this).find('a')[0];

			if (location.pathname.replace(/^\//,'') == el.pathname.replace(/^\//,'') && location.hostname == el.hostname) {
				var target = $(el.hash);
				target = target.length ? target : $('[name=' + el.hash.slice(1) +']');
				if (target.length) {
					$('.single-menu ul li a').removeClass('active');
					$(el).addClass('active');

					$('html, body').animate({
						scrollTop: target.offset().top - 100
					}, 300);
					return false;
				}
			}
		});

		$('.comunicados .related-post').masonry({
			columnWidth : '.large-4',
			selector : '.large-4',
			percentPosition: true,
		});
	}
});