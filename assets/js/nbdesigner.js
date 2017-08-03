jQuery(document).ready(function () {
    
    var width = jQuery(window).innerWidth();
    var height = jQuery(window).height();
    var w = -width;
    var h = -height;
    var showDesignFrame = function(){
        jQuery('#container-online-designer').addClass('show');
        jQuery('#container-online-designer').stop().animate({
            top: 0,
            opacity: 1,
            bottom: 0
        }, 500);        
    };
    jQuery('#container-online-designer').css({'width': width, 'height': height, 'top': h, 'opacity': 0, 'bottom': 0});
    jQuery('#triggerDesign').on('click', function () {
        showDesignFrame();
    });
    jQuery('#closeFrameDesign').on('click', function () {
        hideDesignFrame();
    });
    hideDesignFrame = function (mes) {
        var _h = -jQuery(window).height();
        jQuery('#container-online-designer').stop().animate({
            top: _h,
            opacity: 0
        }, 500);
        if (mes != null) {
            setTimeout(function () {
                alert(mes);
            }, 700);
        }
    };
    jQuery('#nbdesign-new-template').on('click', function(){
        showDesignFrame();
    });
	jQuery(window).on('resize', function () {
		var width = jQuery(window).width(),
				height = jQuery(window).height();
		jQuery('#container-online-designer').css({'width': width, 'height': height});
	});	
});
var NBDESIGNERPRODUCT = {
    insert_customer_design: function (data) {

    },
    hide_iframe_design: function () {
        console.log('something');
        var height = -jQuery(window).height();
        jQuery('#container-online-designer').removeClass('show');
        jQuery('#container-online-designer').stop().animate({
            top: height,
            opacity: 0
        }, 500);
    },
    show_design_thumbnail: function (arr, task) {
        jQuery('#nbdesigner-preview-title').show();
        jQuery('#nbdesign-new-template').show();
        if(task == 'create_template' || task == 'edit_template'){
            jQuery('#triggerDesign').text('Edit Template');
        }
        var html = '';
        jQuery.each(arr, function (key, val) {
            html += '<div class="img-con"><img src="' + val + '" /></div>'
        });
        jQuery('#nbdesigner_frontend_area').html('');
        jQuery('#nbdesigner_frontend_area').append(html);
    },
    nbdesigner_ready: function(){
        if(jQuery('input[name="variation_id"]').length > 0){
            var vid = jQuery('input[name="variation_id"]').val();
            if(vid != '' && parseInt(vid) > 0) {
                jQuery('.nbdesign-button').removeClass('nbdesigner-disable');
            }
        }else{
            jQuery('.nbdesign-button').removeClass('nbdesigner-disable');
        }
        jQuery('.nbdesigner-img-loading').hide();
    },
    nbdesigner_unready: function(){
        jQuery('.nbdesign-button').addClass('nbdesigner-disable');
        jQuery('.nbdesigner-img-loading').show();
    },
    get_sugget_design: function(pid){
        if(!jQuery('.nbdesigner-related-product-image').length) return;
        var products = [];
        jQuery.each(jQuery('.nbdesigner-related-product-image'), function(){
            products.push(jQuery(this).attr('data-id'));
            jQuery(this).parent('.nbdesigner-related-product-item').find('.nbdesigner-overlay').addClass('open');
        });
        jQuery.ajax({
            url: nbds_frontend.url,
            method: "POST",
            data: {
                "action": "nbdesigner_get_suggest_design",
                "products": products,
                "ref" : pid,
                "sid": nbds_frontend.sid,
                "nonce": nbds_frontend.nonce
            }            
        }).done(function(data){
            data = JSON.parse(data);
            jQuery.each(jQuery('.nbdesigner-related-product-image'), function(){
                if(data['flag']){
                    var href = jQuery(this).attr('href'),
                    data_id = jQuery(this).attr('data-id');
                    jQuery(this).attr('href', addParameter(href, 'nbds-ref', pid, false));       
                    jQuery(this).find('img').attr({'src' : data['images'][data_id][0], 'srcset' : ''});
                }
                jQuery(this).parent('.nbdesigner-related-product-item').find('.nbdesigner-overlay').removeClass('open');
            });
        });
    }
};
function addParameter(url, parameterName, parameterValue, atStart/*Add param before others*/) {
    var replaceDuplicates = true;
    var urlhash = '';
    if (url.indexOf('#') > 0) {
        var cl = url.indexOf('#');
        urlhash = url.substring(url.indexOf('#'), url.length);
    } else {
        urlhash = '';
        cl = url.length;
    }
    var sourceUrl = url.substring(0, cl);
    var urlParts = sourceUrl.split("?");
    var newQueryString = "";
    if (urlParts.length > 1){
        var parameters = urlParts[1].split("&");
        for (var i = 0; (i < parameters.length); i++)
        {
            var parameterParts = parameters[i].split("=");
            if (!(replaceDuplicates && parameterParts[0] == parameterName))
            {
                if (newQueryString == "")
                    newQueryString = "?";
                else
                    newQueryString += "&";
                newQueryString += parameterParts[0] + "=" + (parameterParts[1] ? parameterParts[1] : '');
            }
        }
    }
    if (newQueryString == "") newQueryString = "?";
    if (atStart) {
        newQueryString = '?' + parameterName + "=" + parameterValue + (newQueryString.length > 1 ? '&' + newQueryString.substring(1) : '');
    } else {
        if (newQueryString !== "" && newQueryString != '?')
            newQueryString += "&";
        newQueryString += parameterName + "=" + (parameterValue ? parameterValue : '');
    }
    return urlParts[0] + newQueryString + urlhash;
};