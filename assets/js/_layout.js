var mobile = false,
w2 = 140;
var toggleShowTool = function(){
    if($('#addition_tool .menu_right').hasClass('open')) $('.menu_right').triggerHandler('click');
    if($('#menu').hasClass('open')) $('#menu').triggerHandler('click');
    if($('#layer').hasClass('open')) $('#layer').triggerHandler('click');
    if($('.m-center').hasClass('open')) $('.m-center').triggerHandler('click');
    if($('.menu_cart').hasClass('open')) $('.menu_cart').triggerHandler('click');
    if($('#od_config').hasClass('open')){
        $(' #gesture, .container_menu, #layer, #info, #addition_tool').fadeOut();
    }else{
        $(' #gesture, .container_menu, #layer, #info, #addition_tool').fadeIn();
    }
};
var showConfig = function(){
    if(mobile){
        if(!$('#layer').hasClass('open')){
            $('#od_config').addClass('open'); 
            toggleShowTool();			                       
        }
        $('#od_config').addClass('open'); 
    };
};
var hideConfig = function(){
    if(mobile){                   
        $('#od_config').removeClass('open');     
        toggleShowTool();
    };            
};
$(document).ready(function(){
    setTimeout(function() {
        $('#menu').triggerHandler('click');
    },10);
        
	var w = $(window).width(),
	h = $(window).height();
	w2 = w/2 -20;	
	if(w < 768) mobile = true;
	$('#menu').on('click',function(){
		$(this).toggleClass('open');
		$('.tool_draw').toggleClass('open');
		$('.tool_draw li a').each(function(i, val){
			var seft = this;
			setTimeout(function(){
				$(seft).toggleClass('menuUp');
			},200+ i*100);
		});
	});
	$('.menu_right').on('click',function(){
            $(this).toggleClass('open');
            $('.tool-right').toggleClass('open');
            $('#addition_tool').toggleClass('open');
            $('#info').toggle();
            if($(this).hasClass('fa-cog')){
                    $(this).removeClass('fa-cog');
                    $(this).addClass('fa-times');
            }else{
                    $(this).removeClass('fa-times');
                    $(this).addClass('fa-cog');			
            };
            if(mobile){
                $('#gesture').fadeToggle(100);
            };                
	});	
	$('#layer').on('click', function(){
		$(this).toggleClass('open');
		$('#container_layer').toggleClass('open');
                $('#config-style').removeClass('open');
	});
	$('#close_layer').on('click', function(){
            $('#layer, #container_layer').removeClass('open');
	});
        $('#container_layer h3 #_close_popover').on('click', function(){
            $('#layer, #container_layer').removeClass('open');
	});
	$('.m-center').on('click', function(){
		$(this).toggleClass('open');
                if($(this).hasClass('open')){
                    $('#frame').css('opacity', 0.3);
                }else{
                    $('#frame').css('opacity', 1);
                };
		if(mobile){
			if($('#addition_tool .menu_right').hasClass('open')) $('.menu_right').triggerHandler('click');
			if($('#menu').hasClass('open')) $('#menu').triggerHandler('click');
			if($('#layer').hasClass('open')) $('#layer').triggerHandler('click');
			if($('.menu_cart').hasClass('open')) $('.menu_cart').triggerHandler('click');
			$('.container_menu, #layer, #info, #addition_tool').fadeToggle();
		}
			
		if($(this).hasClass('fa-hand-o-up')){
			$(this).removeClass('fa-hand-o-up');
			$(this).addClass('fa-times');
		}else{
			$(this).removeClass('fa-times');
			$(this).addClass('fa-hand-o-up');			
		};
		$('#gesture').toggleClass('open');
		setTimeout(function(){
			$('#gesture .menu_gesture').toggleClass('open');
		},300);			
		if($('#gesture').hasClass('open'))
			{$('#gesture').css({'left': w2 + 'px'});}
		else
			{$('#gesture').css({'left': '140px'});};
		$('#od_config').fadeToggle(200);
	});
	$('#mobile').on('click', function(){
		if($(this).hasClass('fa-eye')){
			$(this).removeClass('fa-eye');
			$(this).addClass('fa-low-vision');
		}else{
			$(this).removeClass('fa-low-vision');
			$(this).addClass('fa-eye');			
		};		
		toggleShowTool();		
	});
	$('.container_frame span').on('click', function(){
		var i = $('.container_item .box-thumb').length;
		var left = $('.container_item').css('left');
		left = left.replace('px', '');
		left = parseInt(left);
		if($(this).hasClass('right')){
			if(-left < (50 * i - 200))
				left = left - 50;
			else left = 0;
		}else {
			if(left < 0)
				left = left + 50;
		};		
		$('.container_item').stop().animate({
				left: left
			}, 400);
	});
	$( ".od_tab" ).tabs({
		collapsible: true,
		active: 0 
	});
	$( ".od_tabs" ).tabs({
		collapsible: false,
		active: 0 
	});	
	$('.od_tab ul li').hover(function(){
        $el = $(this);
        leftPos = $el.position().left;
        $el.parents('.od_tab').find('.list-indicator').stop().animate({
            left: leftPos
        }, 200);		
	}, function(){
		if($el.parents('.od_tab').find('ul li.ui-state-active').length == 0){
			$el.parents('.od_tab').find('.list-indicator').stop().animate({
				left: 0
			}, 200);
		}
		else {
			var obj = $el.parents('.od_tab').find('ul li.ui-state-active')
			var left = obj.position().left;
			if(obj.is(':first-child')) left = 0;
			$el.parents('.od_tab').find('.list-indicator').stop().animate({
				left: left
			}, 200);
		}
	});
	$('#tool-top .help, .close-helpdesk').on('click', function(){
            $('.first_message').hide();
            $('#tool-top .help').removeClass('first_visitor');
            $('#helpdesk').toggleClass('open');
	});
        $('.first_message').on('click', function(){
            $(this).hide();
            $('#tool-top .help').removeClass('first_visitor');
            $('#helpdesk').toggleClass('open');
        });
	$('.toggle-popup-option').on('click', function(){
		var sefl = this;
		$.each($('.popup-option'), function(){
			var p = $(sefl).parent();		
			if(p.has(this).length == 0) {
				$(this).parent().find('.toggle-popup-option').removeClass('open');
				$(this).removeClass('open');
			} 
		});
		$(this).toggleClass('open');
		var pop = $(this).parent().find('.popup-option');
		var pleft = $(this).parent().position().left;
		var left = $(this).position().left;
		var width = $(this).width();
		var b_left = pleft + left + width/2 - 5;
		pop.find('.after').css('left', b_left + 'px');
		pleft = -pleft;
		pop.css('left', pleft + 'px');
		pop.toggleClass('open');
	});
	showLayer = function(){
		$('#layer, #container_layer').addClass('open');
	};
	$('.hide-config').on('click', function(){
            hideConfig();
	});
        $('.hide-tool-config').on('click', function(){
            $.each($('.od_tab'), function(key, val){
                var self = $(this);
                if(self.css('display') == 'block'){
                    self.find('ul li.ui-state-active a').triggerHandler('click');
                };                
            });
        });
    $('#dag-files-images, #uploaded-facebook, #nbdesigner_art_container, #nbdesigner_font_container, .nbdesigner_config_svg, #pattern-boddy, #tool-help, #design-help, #general-help, .nbdesigner-list-template').perfectScrollbar();   
    
		/* megnify */
    if ($(".magniflier").length) {
        var native_width = 0;
        var native_height = 0;
        var mouse = {x: 0, y: 0};
        var magnify;
        var cur_img;

        var ui = {
            magniflier: $('.magniflier')
        };
        if (ui.magniflier.length) {
            var div = document.createElement('div');
            div.setAttribute('class', 'glass');
            ui.glass = $(div);

            $('body').append(div);
        }
        var mouseMove = function (e) {
            var $el = $(this);
            var magnify_offset = cur_img.offset();
            mouse.x = e.pageX - magnify_offset.left;
            mouse.y = e.pageY - magnify_offset.top;
            if (
                mouse.x < cur_img.width() &&
                mouse.y < cur_img.height() &&
                mouse.x > 0 &&
                mouse.y > 0
                ) {
                magnify(e);
            } else {
                ui.glass.fadeOut(100);
            }
            return;
        };
        var magnify = function (e) {
            var rx = Math.round(mouse.x / cur_img.width() * native_width - ui.glass.width() / 2) * -1;
            var ry = Math.round(mouse.y / cur_img.height() * native_height - ui.glass.height() / 2) * -1;
            var bg_pos = rx + "px " + ry + "px";
            var glass_left = e.pageX - ui.glass.width() / 2;
            var glass_top = e.pageY - ui.glass.height() / 2;
            ui.glass.css({
                left: glass_left,
                top: glass_top,
                backgroundPosition: bg_pos
            });
            return;
        };
        $('.magniflier').on('mousemove', function () {
            ui.glass.fadeIn(100);
            cur_img = $(this);
            var large_img_loaded = cur_img.data('large-img-loaded');
            var src = cur_img.data('large') || cur_img.attr('src');
            if (src) {
                ui.glass.css({
                    'background-image': 'url(' + src + ')',
                    'background-repeat': 'no-repeat'
                });
            }
            if (!cur_img.data('native_width')) {
                var image_object = new Image();
                image_object.onload = function () {
                    native_width = image_object.width;
                    native_height = image_object.height;
                    cur_img.data('native_width', native_width);
                    cur_img.data('native_height', native_height);
                    mouseMove.apply(this, arguments);
                    ui.glass.on('mousemove', mouseMove);
                };
                image_object.src = src;
                return;
            } else {
                native_width = cur_img.data('native_width');
                native_height = cur_img.data('native_height');
            }
            mouseMove.apply(this, arguments);
            ui.glass.on('mousemove', mouseMove);
        });
        ui.glass.on('mouseout', function () {
            ui.glass.off('mousemove', mouseMove);
        });
    }
    $('#dg-preview').on('hidden.bs.modal', function () {
        $('.glass').hide();
    });
    $('.full_screen_preview').on('click', function(){
        var ele = document.getElementById('img_preview');
        requestFullScreen(ele);
    });
    document.addEventListener("fullscreenchange", function(){$('#img_preview').toggleClass('img_preview');});
    document.addEventListener("webkitfullscreenchange", function(){$('#img_preview').toggleClass('img_preview');});    
    document.addEventListener("mozfullscreenchange", function(){$('#img_preview').toggleClass('img_preview');});
    document.addEventListener("MSFullscreenChange", function(){$('#img_preview').toggleClass('img_preview');}); 
    var first_visitor = getCookie("nbdesigner_user");
    if (first_visitor != "") {
        $('.first_message').hide();
        $('#tool-top .help').removeClass('first_visitor');       
    }else{
        setCookie("nbdesigner_user", 'Hello World', 0.05);
    }
    $( "#scroll-layer-slider" ).slider({
        animate: true,
        max: 200,
        slide: function( event, ui ) {
            var target = $("#dg-layers"),
            max_height = $("#layers").height() - 140;
            target.stop().animate({
                scrollTop: ui.value * max_height / 200
            }, 400);                    
        }
    }); 
    $(".translate").on("click", function(){
        $(".translate-switch").toggleClass('open');
    });
    $('#toggle-config-style').on('click', function(){
        $('#config-style').toggleClass('open');
    });
    $('.nbd-tooltip').tooltipster({
        trigger: "click",
        side: "bottom",
        theme: 'tooltipster-borderless'
    });
    $('.nbd-tooltip').on('click', function(){
        $(this).tooltip('hide');
    });
});
$(window).on('resize' , function(){
    var w = $(window).width();	
    if(w < 768) mobile = true;  
    w2 = w/2 -20;
});
function requestFullScreen(element) {
    var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullScreen;
    if (requestMethod) { 
        requestMethod.call(element);
    } else if (typeof window.ActiveXObject !== "undefined") {
        var wscript = new ActiveXObject("WScript.Shell");
        if (wscript !== null) {
            wscript.SendKeys("{F11}");
        }
    }
}
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
};
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
};