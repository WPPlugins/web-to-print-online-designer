var NBD_STAGE = {
    'width' : 500,
    'height' : 500
};
var _round = function(num, dec){
    return Number((num).toFixed(dec)); 
};  
jQuery(document).ready(function ($) {
    NBDESIGNADMIN.loopConfigAreaDesign();
    NBDESIGNADMIN.init_color_picker();
    if($('#_nbdesigner_enable').prop("checked")){
        $('.nbdesigner-right.add_more').show();
    };
    NBDESIGNADMIN.collapseAll('com');
    $('#_nbdesigner_enable').change(function () {
        $('#nbdesigner-boxes').toggleClass('nbdesigner-disable');     
        $('#nbdesigner_dpi_con').toggleClass('nbdesigner-disable');     
        $('#nbdesigner-option').toggleClass('nbdesigner-disable');     
        $('.nbdesigner-right.add_more').toggle();
    });
    $('#nbdesigner_add_font_cat').on('click', function () {
        var html = '<input class="form-required nbdesigner_font_name" type="text" id="nbdesigner_name_font_newcat"><br /><br />';
        html += '<input type="button" id="nbdesigner_save_font_cat" onclick="NBDESIGNADMIN.add_font_cat(this)" value="Add new" class="button-primary">';
        html += '<input type="button" id="nbdesigner_cancel_add_font_cat" onclick="NBDESIGNADMIN.cancel_add_font_cat()" value="Cancel" class="button-primary">';
        html += '<img src="' + admin_nbds.url_gif + '" class="nbdesigner_loaded" id="nbdesigner_img_loading" style="margin-left: 15px;"/>';
        $('#nbdesigner_font_newcat').append(html);
        var scroll = $('#nbdesigner_list_cats').parent('.inside');
        scroll.animate({ scrollTop: scroll.prop("scrollHeight") }, 'slow'); 
        $(this).hide();
    });
    $('#nbdesigner_add_art_cat').on('click', function () {
        var html = '<input class="form-required nbdesigner_art_name" type="text" id="nbdesigner_name_art_newcat"><br /><br />';
        html += '<input type="button" id="nbdesigner_save_art_cat" onclick="NBDESIGNADMIN.add_art_cat(this)" value="Add new" class="button-primary">';
        html += '<input type="button" id="nbdesigner_cancel_add_art_cat" onclick="NBDESIGNADMIN.cancel_add_art_cat()" value="Cancel" class="button-primary">';
        html += '<img src="' + admin_nbds.url_gif + '" class="nbdesigner_loaded" id="nbdesigner_img_loading" style="margin-left: 15px;"/>';
        $('#nbdesigner_art_newcat').append(html);
        var scroll = $('#nbdesigner_list_art_cats').parent('.inside');
        scroll.animate({ scrollTop: scroll.prop("scrollHeight") }, 'slow');         
        $('#nbdesigner_add_art_cat').hide();
    });
    $('#nbdesigner_order_design_check_all').click(function(){
        if ($(this).is(':checked')) {
            $('.nbdesigner_design_file').prop('checked', true);
        } else {
            $('.nbdesigner_design_file').prop('checked', false);
        }        
    });
    $('#nbdesigner_order_file_submit').on('click', function(e){
        e.preventDefault();
        var formdata = $('#nbdesigner_order_info').find('input, select').serialize();
        var approve_action = $('#nbdesigner_order_info select[name="nbdesigner_order_file_approve"]').val();
        $('#nbdesigner_order_submit_loading').removeClass('nbdesigner_loaded');
        formdata = formdata + '&action=nbdesigner_design_approve';
        $.post(admin_nbds.url, formdata, function(data) {
            $('#nbdesigner_order_submit_loading').addClass('nbdesigner_loaded');
            if(data.mes == 'success'){
                $('#nbdesigner_order_info input[class^="nbdesigner_design_file"]:checked').each(function(){
                    if (approve_action == 'accept') {
                        var newclass = 'approved';
                    } else {
                        var newclass = 'declined';
                    }
                    $(this).attr('checked', false);
                    $(this).parent('.nbdesigner_container_item_order').attr('class', 'nbdesigner_container_item_order '+newclass);                    
                });
            }else {
                alert(data.mes);
            }
        }, 'json');
    });
    $('#nbdesigner_uploads_email_submit').on('click', function(e){
        e.preventDefault();
        var formdata = $('#nbdesigner_order_email_info').find('textarea, select, input').serialize();
        formdata = formdata + '&action=nbdesigner_design_order_email';
        $('#nbdesigner_order_mail_loading').removeClass('nbdesigner_loaded');
        $.post(admin_nbds.url, formdata, function(data) {
            $('#nbdesigner_order_mail_loading').addClass('nbdesigner_loaded');
            if(data.success == 1) {
                $('#nbdesigner_order_email_error').fadeOut('fast');
                $('#nbdesigner_order_email_success').html(admin_nbds.mes_success).fadeIn('fast');
                $('textarea#nbdesigner_design_email_order_content').val('');
            } else {

                $('#nbdesigner_order_email_success').fadeOut('fast');
                $('#nbdesigner_order_email_error').html(data.error).fadeIn('fast');
            }            
        }, 'json');
    });
    $('#nbdesigner_get_key').on('click', function(e){
        e.preventDefault();
        var email = $('#nbdesigner_license input[name*="email"]').val(),
        name = $('#nbdesigner_license input[name*="name"]').val();
        if(email == '' || name == ''){
            alert('Please enter your name and your email!');
            return;
        };
        $('#nbdesigner_license_loading').removeClass('nbdesigner_loaded');
        var formdata = $('#nbdesigner_license').find('textarea, select, input').serialize();
        formdata = formdata + '&action=nbdesigner_get_license_key';
        $.post(admin_nbds.url, formdata, function(data){
            $('#nbdesigner_license_loading').addClass('nbdesigner_loaded');
            data = JSON.parse(data);
            if(data.mes){
                $('#nbdesigner_key_mes').html(data.mes).css('color', '#0085ba');
            }             
        });
    });
    $('#nbdesigner_active_key').on('click', function(e){
        e.preventDefault();
        $('#nbdesigner_license_active_loading').removeClass('nbdesigner_loaded');
        var formdata = $('#nbdesigner_active_license').find('textarea, select, input').serialize();
        formdata = formdata + '&action=nbdesigner_get_info_license';
        $.post(admin_nbds.url, formdata, function(data){
            $('#nbdesigner_license_active_loading').addClass('nbdesigner_loaded');
            data = JSON.parse(data);
            if(data.mes){
                $('#nbdesigner_license_mes').html(data.mes).css('color', '#0085ba');
            }
            if(data.flag == 1){
                $('#nbdesigner_active_key').prop('disabled', true);
                $('#nbdesigner_license').hide();
            }            
        });
    });
    $('#nbdesigner_remove_key').on('click', function(e){
        e.preventDefault();
        var con = confirm('Are you sure remove this license');
        if(con){
            var formdata = $('#nbdesigner_active_license').find('textarea, select, input').serialize();
            formdata = formdata + '&action=nbdesigner_remove_license';
            $('#nbdesigner_license_active_loading').removeClass('nbdesigner_loaded');
            $.post(admin_nbds.url, formdata, function(data){
                $('#nbdesigner_license_active_loading').addClass('nbdesigner_loaded');
                data = JSON.parse(data);
                if(data.mes){
                    $('#nbdesigner_license_mes').html(data.mes).css('color', '#0085ba');
                }
                if(data.flag == 1){
                    $('#nbdesigner_remove_key').prop('disabled', true);
                    $('#nbdesigner_active_key').prop('disabled', false);
                    $('#nbdesigner_input_key').val('').removeAttr('readonly');
                    $('#nbdesigner_license').show();
                }                  
            });
        }
    });
    $('#nbdesigner-gen-sec-key').on('click', function(){
        jQuery.ajax({
            url: admin_nbds.url,
            method: "POST",
            data: {'action': 'nbdesigner_get_security_key', 'nonce': admin_nbds.nonce}          
        }).done(function (data) {
            var res = JSON.parse(data);
            if(res['mes'] == 'success'){
                $("#nbdesigner-sec-key").val(res['key']);
                $('#nbdesigner-toggle-show-sec-key').show();
                var check = parseInt($('#nbdesigner-check-toggle-show').val());
                if(check == 0){
                    $('#nbdesigner-toggle-show-sec-key .nbdesigner-hide-text').show();
                }else{
                    $('#nbdesigner-toggle-show-sec-key .nbdesigner-show-text').show();
                }
            }
        });        
    });
    $('#nbdesigner-toggle-show-sec-key').on('click', function(){
        var check = parseInt($('#nbdesigner-check-toggle-show').val());
        if(check == 0){
            $('#nbdesigner-check-toggle-show').val(1);
            $("#nbdesigner-sec-key").attr('type', 'password');
            $('#nbdesigner-toggle-show-sec-key .nbdesigner-hide-text').hide();
            $('#nbdesigner-toggle-show-sec-key .nbdesigner-show-text').show();
            $('#nbdesigner-toggle-show-sec-key .dashicons').addClass('dashicons-visibility').removeClass('dashicons-hidden'); 
        }else{
            $('#nbdesigner-check-toggle-show').val(0);
            $("#nbdesigner-sec-key").attr('type', 'text');
            $('#nbdesigner-toggle-show-sec-key .nbdesigner-hide-text').show();
            $('#nbdesigner-toggle-show-sec-key .nbdesigner-show-text').hide();
            $('#nbdesigner-toggle-show-sec-key .dashicons').removeClass('dashicons-visibility').addClass('dashicons-hidden');                    
        }
    });
    $('#nbdesigner_update_data_migrate').on('click', function(e){
        e.preventDefault();
        var formdata = $('#nbdesigner-migrate-info').find('textarea, select, input').serialize();
        formdata = formdata + '&action=nbdesigner_migrate_domain';
        $('#nbdesigner_migrate_loading').removeClass('nbdesigner_loaded');
        $.post(admin_nbds.url, formdata, function(_data){
            $('#nbdesigner_migrate_loading').addClass('nbdesigner_loaded');
            var data = JSON.parse(_data);
            if (data.flag == 1) {
                swal(admin_nbds.nbds_lang.complete, data.mes, "success");
            }else{
                swal({
                    title: "Oops!",
                    text: data.mes,
                    imageUrl: admin_nbds.assets_images + "dinosaur.png"
                });
            }                
        });
    });    
    $('#nbdesigner_resote_data_migrate').on('click', function(e){
        e.preventDefault();
        swal({
            title: admin_nbds.nbds_lang.are_you_sure,
            text: admin_nbds.nbds_lang.warning_mes_backup_data,
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function(){
            $('#nbdesigner_migrate_loading').removeClass('nbdesigner_loaded');
            $.ajax({
                url : admin_nbds.url,
                method : 'POST',
                data : {'action' : 'nbdesigner_restore_data_migrate_domain', 'nonce': admin_nbds.nonce}
            }).done(function(_data){
                $('#nbdesigner_migrate_loading').addClass('nbdesigner_loaded');
                var data = JSON.parse(_data);
                if (data.flag == 1) {
                    swal(admin_nbds.nbds_lang.complete, data.mes, "success");
                }else{
                    swal({
                        title: "Oops!",
                        text: data.mes,
                        imageUrl: admin_nbds.assets_images + "dinosaur.png"
                    });
                }                   
            })
        })
    });  
    $('#nbdesigner_check_theme').on('click', function(e){
        e.preventDefault();
        var formdata = jQuery('#nbdesign-theme-check').find('textarea, select, input').serialize();
        formdata = formdata + '&action=nbdesigner_theme_check';
        jQuery('#nbdesigner_check_theme_loading').removeClass('nbdesigner_loaded');
        jQuery.post(admin_nbds.url, formdata, function(data){
            jQuery('#nbdesigner_check_theme_loading').addClass('nbdesigner_loaded');
            data = JSON.parse(data);
            if(data.flag == 'ok'){
                jQuery('.theme_check_note').html(data.html);
                //alert('Update success!');
            }else{
                alert('Oops! Try again!');
            }                  
        });         
    });
    $('#nbdesigner_update_product').on('click', function(e){
        e.preventDefault();
        var formdata = jQuery('.update-setting-data').find('textarea, select, input').serialize();
        formdata = formdata + '&action=nbdesigner_update_all_product';
        jQuery('#nbdesigner_update_product_loading').removeClass('nbdesigner_loaded');
        jQuery.post(admin_nbds.url, formdata, function(data){
            jQuery('#nbdesigner_update_product_loading').addClass('nbdesigner_loaded');
            data = JSON.parse(data);
            if(parseInt(data.flag) == 1){
                alert('Update success!');
            }else {
                alert('Oops! Try again!');
            }              
        });         
    });
    $('#woocommerce-product-data').on('woocommerce_variations_loaded', function(event) {
        NBDESIGNADMIN.loopConfigAreaDesign();
    });  
    $('#nbdesigner-values-group-input--hex_key, .nbdesigner-color-picker').wpColorPicker({
        change: function (evt, ui) {
            var $input = $(this);
            setTimeout(function () {
                if ($input.wpColorPicker('color') !== $input.data('tempcolor')) {
                    $input.change().data('tempcolor', $input.wpColorPicker('color'));
                    $input.val($input.wpColorPicker('color'));
                }
            }, 10);
        }
    });  
    $('.nbdesigner-values-group-add').on('click', function(e){
        e.preventDefault();
        var $this = $(this),
            $tbody = $this.parents('table:first').find('tbody'),
            $inputs = $this.parents('tr:first').find('input[type="text"]');
        var values = [];
        $inputs.each(function(i, item) {
            values.push(item.value);
        });       
        _appendValuesGroupRow($tbody, values);
        _saveValuesGroup($tbody);       
    });
    function _appendValuesGroupRow($tbody, values) {
        var row = '<tr>',
                prefix = '';
        for (var i = 0; i < values.length; ++i) {
            if ($tbody.prev('thead').find('input').eq(i).prev('span').length > 0) {
                prefix = $tbody.prev('thead').find('input').eq(i).prev('span').html();
            }
            if(values[i][0] == "#"){
                row += '<td>' + prefix + '<span class="nbdesigner-values-group-td-value button" style="background: '+values[i]+'">' + values[i] + '</span></td>';
            }else{
                row += '<td>' + prefix + '<span class="nbdesigner-values-group-td-value">' + values[i] + '</span></td>';
            }           
        };      
        row += '<td><a href="#" class="nbdesigner-values-group-remove">&times;</a></td></tr>';
        $tbody.append(row).find('tr:last .nbdesigner-values-group-remove').click(function (evt) {
            evt.preventDefault();
            $(this).parents('tr:first').remove();
            _saveValuesGroup($tbody);
        });
    };   
    function _saveValuesGroup($tbody) {
        var inputValue = '',
            $rows = $tbody.find('tr');
        $rows.each(function(i, row) {
            var $tds = $(row).children('td:not(:last)');
            $tds.each(function(j, td) {
                inputValue += $(td).children('.nbdesigner-values-group-td-value').text();
                if(j < $tds.length-1) {
                        inputValue += ':';
                }
            });
            if(i < $rows.length-1) {
                inputValue += ',';
            }
        });
        $tbody.parents('.nbdesigner-option-type--values-group:first').children('.nbdesigner-option-value').val(inputValue);
    };    
    $('.nbdesigner-option-type--values-group .nbdesigner-option-value').each(function(i, item) {
        var $tbody = $(this).parent().find('tbody'),
            value = item.value;
        if(value.trim().length <= 0) {
            return false;
        }
        var values = value.split(',');
        for(var i=0; i < values.length; ++i) {
            _appendValuesGroupRow($tbody, values[i].split(':'));
        }
    });    
    $('.nbdesigner-multi-values .select-all').on('click', function(){
        $(this).parents('.nbdesigner-multi-values').find('input:checkbox').attr('checked','checked');
    });
    $('.nbdesigner-multi-values .select-none').on('click', function(){
        $(this).parents('.nbdesigner-multi-values').find('input:checkbox').removeAttr('checked');
    });    
    if($('input[name="nbdesigner_show_all_color"]:checked').val() == 'yes') $('#color-setting > tbody tr:nth-child(2)').hide();
    $('input[name="nbdesigner_show_all_color"]').on('click', function(){
        var value = $(this).val();
        if(value == 'no'){
            $('#color-setting > tbody tr:nth-child(2)').show()
        }else{
            $('#color-setting > tbody tr:nth-child(2)').hide()
        }
    });
});
var NBDESIGNADMIN = {
    add_font_cat: function (e) {
        var cat_name = jQuery(e).parent().find('.nbdesigner_font_name').val(),
                cat_id = jQuery('#nbdesigner_current_font_cat_id').val();
        if(cat_name == "") {
            alert(admin_nbds.nbds_lang.warning_mes_fill_category_name);
            return;
        };        
        jQuery.ajax({
            url: admin_nbds.url,
            method: "POST",
            data: {'action': 'nbdesigner_add_font_cat', 'name': cat_name, 'id': cat_id, 'nonce': admin_nbds.nonce},
            beforeSend: function () {
                jQuery('#nbdesigner_img_loading').removeClass('nbdesigner_loaded');
            },
            complete: function () {
                jQuery('#nbdesigner_img_loading').addClass('nbdesigner_loaded');
            }
        }).done(function (_data) {
            var data = JSON.parse(_data);
            if (data.flag == 1) {
                swal(admin_nbds.nbds_lang.complete, data.mes, "success");
                var html = '<li id="nbdesigner_cat_font_' + cat_id + '" class="nbdesigner_action_delete_cf"><label>';
                html += '<input value="' + cat_id + '" type="checkbox" name="nbdesigner_font_cat[]" /></label>';
                //html += '<span class="nbdesigner-right nbdesigner-delete-item" onclick="NBDESIGNADMIN.delete_cat_font(this)">&times;</span>'+cat_name+'</li>';
                html += '<span class="nbdesigner-right nbdesigner-delete-item dashicons dashicons-no-alt" onclick="NBDESIGNADMIN.delete_cat_font(this)"></span>';
                html += '<span class="dashicons dashicons-edit nbdesigner-right nbdesigner-delete-item" onclick="NBDESIGNADMIN.edit_cat_font(this)"></span>';
                html += '<a href="?page=nbdesigner_manager_fonts&cat_id='+cat_id+'" class="nbdesigner-cat-link">'+cat_name+'</a>';
                html += '<input value="'+cat_name+'" class="nbdesigner-editcat-name" type="text"/>';
                html += '<span class="dashicons dashicons-yes nbdesigner-delete-item nbdesigner-editcat-name" onclick="NBDESIGNADMIN.save_cat_font(this)"></span>';
                html += '<span class="dashicons dashicons-no nbdesigner-delete-item nbdesigner-editcat-name" onclick="NBDESIGNADMIN.remove_action_cat_font(this)"></span>';
                html += '</span>';
                jQuery('#nbdesigner_list_cats').append(html);
                jQuery('#nbdesigner_current_font_cat_id').val(parseInt(cat_id) + 1);
                jQuery('#nbdesigner_font_newcat').html('');
            } else if(data){
                swal({
                    title: "Oops!",
                    text: data.mes,
                    imageUrl: admin_nbds.assets_images + "dinosaur.png"
                });
                jQuery('#nbdesigner_font_newcat').html('');
            }
            jQuery('#nbdesigner_add_font_cat').show();
        });
    },
    cancel_add_font_cat: function(){
        jQuery('#nbdesigner_font_newcat').html('');
        jQuery('#nbdesigner_add_font_cat').show();
    },   
    cancel_add_art_cat: function(){
        jQuery('#nbdesigner_art_newcat').html('');
        jQuery('#nbdesigner_add_art_cat').show();
    },    
    add_art_cat: function (e) {     
        var cat_name = jQuery(e).parent().find('.nbdesigner_art_name').val(),
                cat_id = jQuery('#nbdesigner_current_art_cat_id').val();
        if(cat_name == "") {
            alert(admin_nbds.nbds_lang.warning_mes_fill_category_name);
            return;
        };
        jQuery.ajax({
            url: admin_nbds.url,
            method: "POST",
            data: {'action': 'nbdesigner_add_art_cat', 'name': cat_name, 'id': cat_id, 'nonce': admin_nbds.nonce},
            beforeSend: function () {
                jQuery('#nbdesigner_img_loading').removeClass('nbdesigner_loaded');
            },
            complete: function () {
                jQuery('#nbdesigner_img_loading').addClass('nbdesigner_loaded');
            }
        }).done(function (_data) {
            var data = JSON.parse(_data);
            if (data.flag == 1) {
                swal(admin_nbds.nbds_lang.complete, data.mes, "success");
                var html = '<li id="nbdesigner_cat_art_' + cat_id + '" class="nbdesigner_action_delete_art_cat"><label>';
                html += '<input value="' + cat_id + '" type="checkbox" name="nbdesigner_art_cat[]" /></label>';
                html += '<span class="nbdesigner-right nbdesigner-delete-item dashicons dashicons-no-alt" onclick="NBDESIGNADMIN.delete_cat_art(this)"></span>';
                html += '<span class="dashicons dashicons-edit nbdesigner-right nbdesigner-delete-item" onclick="NBDESIGNADMIN.edit_cat_art(this)"></span>';
                html += '<a href="?page=nbdesigner_manager_arts&cat_id='+cat_id+'" class="nbdesigner-cat-link">'+cat_name+'</a>';
                html += '<input value="'+cat_name+'" class="nbdesigner-editcat-name" type="text"/>';
                html += '<span class="dashicons dashicons-yes nbdesigner-delete-item nbdesigner-editcat-name" onclick="NBDESIGNADMIN.save_cat_art(this)"></span>';
                html += '<span class="dashicons dashicons-no nbdesigner-delete-item nbdesigner-editcat-name" onclick="NBDESIGNADMIN.remove_action_cat_art(this)"></span>';
                html += '</span>';                
                jQuery('#nbdesigner_list_art_cats').append(html);
                jQuery('#nbdesigner_current_art_cat_id').val(parseInt(cat_id) + 1);
                jQuery('#nbdesigner_art_newcat').html('');
            } else if(data){
                swal({
                    title: "Oops!",
                    text: data.mes,
                    imageUrl: admin_nbds.assets_images + "dinosaur.png"
                });
                jQuery('#nbdesigner_art_newcat').html('');
            }
            jQuery('#nbdesigner_add_art_cat').show();
        });
    },
    delete_cat_font: function (e) {
        var index = jQuery(e).parent().find('input').val();
        var cat_id = jQuery('#nbdesigner_current_font_cat_id').val();
        swal({
            title: admin_nbds.nbds_lang.are_you_sure,
            text: admin_nbds.nbds_lang.warning_mes_delete_category,
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function(){  
            var data = {'action': 'nbdesigner_delete_font_cat', 'id': index, 'nonce': admin_nbds.nonce};
            jQuery.ajax({
                url: admin_nbds.url,
                method: "POST",
                data: data,
                beforeSend: function () {
                    jQuery('#nbdesigner_img_loading').removeClass('nbdesigner_loaded');
                },
                complete: function () {
                    jQuery('#nbdesigner_img_loading').addClass('nbdesigner_loaded');
                }
            }).done(function (_data) {
                data = JSON.parse(_data);
                if (data.flag == 1) {
                    swal(admin_nbds.nbds_lang.complete, data.mes, "success");
                    jQuery('#nbdesigner_list_cats').find('#nbdesigner_cat_font_' + index).remove();
                    jQuery.each(jQuery('#nbdesigner_list_cats li label input'), function (key, val) {
                        jQuery(this).val(key);
                    });
                    jQuery('#nbdesigner_current_font_cat_id').val(parseInt(cat_id) - 1);
                    jQuery.each(jQuery('.nbdesigner_action_delete_cf'), function (key, val) {
                        jQuery(this).attr('id', 'nbdesigner_cat_font_' + key);
                    });
                }else{
                    swal({
                        title: "Oops!",
                        text: data.mes,
                        imageUrl: admin_nbds.assets_images + "dinosaur.png"
                    });                    
                }
            })
        });
    },
    delete_cat_art: function (e) {
        var index = jQuery(e).parent().find('input').val();
        var cat_id = jQuery('#nbdesigner_current_art_cat_id').val();
        swal({
            title: admin_nbds.nbds_lang.are_you_sure,
            text: admin_nbds.nbds_lang.warning_mes_delete_category,
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function(){
            var data = {'action': 'nbdesigner_delete_art_cat', 'id': index, 'nonce': admin_nbds.nonce};
            jQuery.ajax({
                url: admin_nbds.url,
                method: "POST",
                data: data,
                beforeSend: function () {
                    jQuery('#nbdesigner_img_loading').removeClass('nbdesigner_loaded');
                },
                complete: function () {
                    jQuery('#nbdesigner_img_loading').addClass('nbdesigner_loaded');
                }
            }).done(function (_data) {
                data = JSON.parse(_data);
                if (data.flag == 1) {
                    swal(admin_nbds.nbds_lang.complete, data.mes, "success");
                    jQuery('#nbdesigner_list_art_cats').find('#nbdesigner_cat_art_' + index).remove();
                    jQuery.each(jQuery('#nbdesigner_list_art_cats li label input'), function (key, val) {
                        jQuery(this).val(key);
                    });
                    jQuery('#nbdesigner_current_art_cat_id').val(parseInt(cat_id) - 1);
                    jQuery.each(jQuery('.nbdesigner_action_delete_art_cat'), function (key, val) {
                        jQuery(this).attr('id', 'nbdesigner_cat_art_' + key);
                    });
                }else{
                    swal({
                        title: "Oops!",
                        text: data.mes,
                        imageUrl: admin_nbds.assets_images + "dinosaur.png"
                    });
                }
            });
        });
    },
    delete_font: function (type, e) {
        var index = jQuery(e).data('index');
        var total = jQuery('#nbdesigner_current_index_google_font').val();
        swal({
            title: admin_nbds.nbds_lang.are_you_sure,
            text: admin_nbds.nbds_lang.warning_mes_delete_file,
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function(){
            var data = {'action': 'nbdesigner_delete_font', 'id': index, 'nonce': admin_nbds.nonce, 'type': type};
            jQuery.ajax({
                url: admin_nbds.url,
                method: "POST",
                data: data
            }).done(function (_data) {
                data = JSON.parse(_data);
                if (data.flag == 1) {
                    swal(admin_nbds.nbds_lang.complete, data.mes, "success");
                    jQuery(e).parent().remove();
                    if (type == 'google') {
                        jQuery('#nbdesigner_current_index_google_font').val(parseInt(total) - 1);
                        jQuery.each(jQuery('.nbdesigner_action_delete_gf'), function (key, val) {
                            jQuery(this).attr('data-index', key);
                        });
                    } else if (type == 'custom') {
                        jQuery.each(jQuery('.nbdesigner_action_delete_cfont'), function (key, val) {
                            jQuery(this).attr('data-index', key);
                        });
                    }
                }else{
                    swal({
                        title: "Oops!",
                        text: data.mes,
                        imageUrl: admin_nbds.assets_images + "dinosaur.png"
                    });
                }
            });
        });
    },
    delete_art: function (e) {
        var index = jQuery(e).data('index');
        swal({
            title: admin_nbds.nbds_lang.are_you_sure,
            text: admin_nbds.nbds_lang.warning_mes_delete_file,
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function(){
            var data = {'action': 'nbdesigner_delete_art', 'id': index, 'nonce': admin_nbds.nonce};
            jQuery.ajax({
                url: admin_nbds.url,
                method: "POST",
                data: data
            }).done(function (_data) {
                data = JSON.parse(_data);
                if (data.flag == 1) {
                    swal(admin_nbds.nbds_lang.complete, data.mes, "success");
                    jQuery(e).parent().remove();
                    jQuery.each(jQuery('.nbdesigner_action_delete_art'), function (key, val) {
                        jQuery(this).attr('data-index', key);
                    });
                }else{
                    swal({
                        title: "Oops!",
                        text: data.mes,
                        imageUrl: admin_nbds.assets_images + "dinosaur.png"
                    });
                }
                ;
            });
        });
    },
    add_google_font: function (e) {
        var name = jQuery(e).prev('input').val(),
                index = jQuery('#nbdesigner_current_index_google_font').val();
        jQuery.ajax({
            url: admin_nbds.url,
            method: "POST",
            data: {'action': 'nbdesigner_add_google_font', 'name': name, "id": index, 'nonce': admin_nbds.nonce},
            beforeSend: function () {
                jQuery('#nbdesigner_google_font_loading').removeClass('nbdesigner_loaded');
            },
            complete: function () {
                jQuery('#nbdesigner_google_font_loading').addClass('nbdesigner_loaded');
            }
        }).done(function (data) {
            var html = '<span class="nbdesigner_google_link "><a href="https://fonts.google.com/specimen/' + name + '" target="_blank"><span>' + name + '</span></a><span data-index="' + index + '" onclick="NBDESIGNADMIN.delete_font(\'google\',this)">&times;</span></span>';
            jQuery('#nbdesigner_no_google_font').hide();
            jQuery('#nbdesigner_container_list_google_font').append(html);
            jQuery('#nbdesigner_current_index_google_font').val(parseInt(index) + 1);
        });
    },
    loadImage: function (e) {
        var upload;
        if (upload) {
            upload.open();
            return;
        }
        var self = this;
        var index = jQuery(e).data('index'),
            _img = jQuery(e).parents('.nbdesigner-box-collapse').find('.designer_img_src'),
            _input = jQuery(e).parents('.nbdesigner-box-collapse').find('.hidden_img_src');
        upload = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
        upload.on('select', function () {
            attachment = upload.state().get('selection').first().toJSON();
            _img.attr('src', attachment.url);
            _img.show();
            _input.val(attachment.url);
        });
        upload.open();
    },
    loadImageOverlay: function(e){
        var upload;
        if (upload) {
            upload.open();
            return;
        }  
        var ip_image = jQuery(e).parents('.nbdesigner-box-collapse').find('.hidden_overlay_src'),  
            image = jQuery(e).parents('.nbdesigner-box-collapse').find('.img_overlay');  
        upload = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
        upload.on('select', function () {
            attachment = upload.state().get('selection').first().toJSON();
            image.attr('src', attachment.url);
            image.show();
            ip_image.val(attachment.url);
        });
        upload.open();        
    },
    deleteOrientation: function (e) {
        var variantion = jQuery(e).data('variation');
        if((jQuery('.nbdesigner-box-container').length) > 1){
            jQuery(e).parents('.nbdesigner-box-container').remove();
            this.resetBoxes(variantion);            
        }else{
            jQuery(e).parents('.nbdesigner-box-container').hide();
        };
    },
    resetBoxes: function (command) {
        if(command == 'com') command = '';
        var index = '#nbdesigner-boxes' + command,
        name = '_designer_setting' + command;
        jQuery.each(jQuery(index + ' .nbdesigner-box-container'), function (key, val) {
            jQuery(this).find('.orientation_name').attr('name', name + '[' + key + '][orientation_name]');
            jQuery(this).find('.delete_orientation').attr('data-index', key);
            jQuery(this).find('.hidden_img_src').attr('name', name + '[' + key + '][img_src]');
            jQuery(this).find('.hidden_img_src_top').attr('name', name + '[' + key + '][img_src_top]');
            jQuery(this).find('.hidden_img_src_left').attr('name', name + '[' + key + '][img_src_left]');
            jQuery(this).find('.hidden_img_src_width').attr('name', name + '[' + key + '][img_src_width]');
            jQuery(this).find('.hidden_img_src_height').attr('name', name + '[' + key + '][img_src_height]');
            jQuery(this).find('.nbdesigner_move').attr('data-index', key);
            jQuery(this).find('.nbdesigner-add-image').attr('data-index', key);
            jQuery(this).find('.real_width').attr('name', name + '[' + key + '][real_width]');
            jQuery(this).find('.real_height').attr('name', name + '[' + key + '][real_height]');
            jQuery(this).find('.real_top').attr('name', name + '[' + key + '][real_top]');
            jQuery(this).find('.real_left').attr('name', name + '[' + key + '][real_left]');
            jQuery(this).find('.area_design_top').attr('name', name + '[' + key + '][area_design_top]');
            jQuery(this).find('.area_design_left').attr('name', name + '[' + key + '][area_design_left]');
            jQuery(this).find('.area_design_width').attr('name', name + '[' + key + '][area_design_width]');
            jQuery(this).find('.area_design_height').attr('name', name + '[' + key + '][area_design_height]');
            jQuery(this).find('.product_width').attr('name', name + '[' + key + '][product_width]');
            jQuery(this).find('.product_height').attr('name', name + '[' + key + '][product_height]');
            jQuery(this).find('.nbd-color-picker').attr('name', name + '[' + key + '][bg_color_value]');
            jQuery(this).find('.bg_type').attr('name', name + '[' + key + '][bg_type]');
            jQuery(this).find('.hidden_overlay_src').attr('name', name + '[' + key + '][img_overlay]');
            jQuery(this).find('.show_overlay').attr('name', name + '[' + key + '][show_overlay]');
            jQuery(this).find('.hidden_nbd_version').attr('name', name + '[' + key + '][version]');
        });
        this.loopConfigAreaDesign();
    },
    calcPositionImg: function (e) {
        setTimeout(function(){
            var p = e.parent(),
            top = e.offset().top - jQuery(p).offset().top,
            left = e.offset().left - jQuery(p).offset().left,
            width = e.width(),
            height = e.height();
            e.parents('.nbdesigner-image-box').find('.hidden_img_src_top').val(top);
            e.parents('.nbdesigner-image-box').find('.hidden_img_src_left').val(left);
            e.parents('.nbdesigner-image-box').find('.hidden_img_src_width').val(width);
            e.parents('.nbdesigner-image-box').find('.hidden_img_src_height').val(height);
        },0);       
    },
    loopConfigAreaDesign: function () {
        var parent = this;
        jQuery('.nbdesigner-area-design').each(function (key, val) {
            var self = this;
            jQuery(this).on('click', function () {
                jQuery('.nbdesigner-area-design').removeClass('selected');
                jQuery(this).addClass('selected');
            });
            jQuery(this).resizable({
                handles: "ne, se, sw, nw",
                aspectRatio: false,
                maxWidth: NBD_STAGE.width,
                maxHeight: NBD_STAGE.height,
                resize: function (event, ui) {
                    parent.updateDimension(self, ui.size.width, ui.size.height, ui.position.left, ui.position.top);
                },
                start: function (event, ui) {
                    /*TODO*/
                }
            }).draggable({containment: "parent",
                drag: function (event, ui) {
                    parent.updateDimension(self, null, null, ui.position.left, ui.position.top);
                }
            });
        });
        this.init_color_picker();
    },
    calcMargin: function (w, h, _img) {
        setTimeout(function(){
            var h_d = _img.parent().height();
            if ((w < h) && (h >= h_d)) {
                _img.css('margin-top', '0');
            };
            if ((w <= h_d) && (h <= h_d)) {
                var offset = (h_d - h) / 2;
                _img.css('margin-top', offset + 'px');
            };
            if ((w >= h) && (w > h_d)) {
                h = h * h_d / w;
                var offset = (h_d - h) / 2;
                _img.css('margin-top', offset + 'px');
            };
        },0);      
    },
    nbdesigner_move: function (e, command) {
        var parent = jQuery(e).parents('.nbdesigner-box-collapse'),
        area = parent.find('.nbdesigner-area-design'),
        overlay = parent.find('.nbdesigner-image-overlay'),
        left = area.css('left'),
        top = area.css('top'),
        w = area.width(),
        h = area.height(),
        ip_left = parent.find('.area_design_left'),
        ip_top = parent.find('.area_design_top'),
        ip_width = parent.find('.area_design_width'),
        ip_height = parent.find('.area_design_height');
        switch (command) {
            case 'left':
                area.css('left', parseFloat(left) - 1);
                overlay.css('left', parseFloat(left) - 1);
                ip_left.val(parseFloat(left) - 1);
                break;
            case 'right':
                area.css('left', parseFloat(left) + 1);
                overlay.css('left', parseFloat(left) + 1);
                ip_left.val(parseFloat(left) + 1);
                break;
            case 'down':
                area.css('top', parseFloat(top) + 1);
                overlay.css('top', parseFloat(top) + 1);
                ip_top.val(parseFloat(top) + 1);
                break;
            case 'up':
                area.css('top', parseFloat(top) - 1);
                overlay.css('top', parseFloat(top) - 1);
                ip_top.val(parseFloat(top) - 1);
                break;
            case 'center':
                left = (NBD_STAGE.width - w) / 2;
                top = (NBD_STAGE.height - h) / 2;
                area.css({'top': top + 'px', 'left': left + 'px'});
                overlay.css({'top': top + 'px', 'left': left + 'px'});
                ip_left.val(left);
                ip_top.val(top);
                break;
            case 'fit':             
                var width = parent.find('.nbdesigner-image-original').width(),
                height = parent.find('.nbdesigner-image-original').height(),
                p_width = parent.find('.product_width').val(),
                p_height = parent.find('.product_height').val();
                parent.find('.real_width').val(p_width);
                parent.find('.real_height').val(p_height);
                parent.find('.real_top').val(0);
                parent.find('.real_left').val(0);
                left = (NBD_STAGE.width - width) / 2;
                top = (NBD_STAGE.height - height) / 2;
                area.css({'top': top + 'px', 'left': left + 'px', 'width': width + 'px',  'height': height + 'px'});
                overlay.css({'top': top + 'px', 'left': left + 'px', 'width': width + 'px',  'height': height + 'px'});
                ip_left.val(left);
                ip_top.val(top);                
                ip_width.val(width);                
                ip_height.val(height);   
                break;
        }
        parent.find('.nbdesiger-update-area-design').addClass('active');
    },
    ajustImage: function () {
        var self = this;   
        jQuery.each(jQuery('.designer_img_src'), function () {
            var _img = jQuery(this),
            w = jQuery(this).width(),
            h = jQuery(this).height();
            self.calcMargin(w, h, _img);
            self.calcPositionImg(_img);
        });      
    },
    updateDimension: function (e, width, height, left, top) {
        var parent = jQuery(e).parents('.nbdesigner-box-collapse');
        var ip_left = parent.find('.area_design_left'),
            ip_top = parent.find('.area_design_top'),
            ip_width = parent.find('.area_design_width'),
            ip_height = parent.find('.area_design_height');
        if (left) ip_left.val(left);
        if (top) ip_top.val(top);
        if (width) ip_width.val(width);
        if (height) ip_height.val(height);
        parent.find('.nbdesiger-update-area-design').addClass('active');
        var area = parent.find('.nbdesigner-area-design');
        parent.find('.nbdesigner-image-overlay').css({
                'width': area.css('width'),           
                'height': area.css('height'),           
                'left': area.css('left'),           
                'top': area.css('top')           
            });
    },
    updatePositionDesignArea: function (e) {
        var att = jQuery(e).data('index'),
        value = jQuery(e).val(),
        parent = jQuery(e).parents('.nbdesigner-box-collapse').find('.nbdesigner-info-box'),              
        area = jQuery(e).parents('.nbdesigner-box-collapse').find('.nbdesigner-area-design'),
        overlay = jQuery(e).parents('.nbdesigner-box-collapse').find('.nbdesigner-image-overlay'),
        height = parent.find('.area_design_height').val(),
        width = parent.find('.area_design_width').val(),
        left = parent.find('.area_design_left').val(),
        top = parent.find('.area_design_top').val(),
        sefl = jQuery(e);
        if(att == 'width'){
            if(value < 0) value = 0;
            if(value > (NBD_STAGE.width - left)) value = NBD_STAGE.width - left;
        } else if(att == 'height'){
            if(value < 0) value = 0;
            if(value > (NBD_STAGE.height - top)) value = NBD_STAGE.height - top;         
        } else if(att == 'left'){
            if(value < 0) value = 0;
            if(value > (NBD_STAGE.width - width)){
                if(value > NBD_STAGE.width) value = NBD_STAGE.width;
                parent.find('.area_design_width').val(NBD_STAGE.width -value);
                area.css('width', (NBD_STAGE.width - value) + 'px');
            }            
        } else if(att == 'top'){
            if(value < 0) value = 0;  
            if(value > (NBD_STAGE.height - height)){
                if(value > NBD_STAGE.height) value = NBD_STAGE.height;
                parent.find('.area_design_height').val(NBD_STAGE.height -value);
                area.css('height', (NBD_STAGE.height - value) + 'px');
            }              
        }
        parent.find('.nbdesiger-update-area-design').addClass('active');
        area.css(att, value + 'px');
        overlay.css(att, value + 'px');
        sefl.val(value);
    },
    updateSolutionImage: function(){
        var dpi = jQuery('#nbdesigner_dpi').val();
        if(dpi < 1) dpi = 1;
        jQuery.each(jQuery('#nbdesigner-boxes .nbdesigner-box-container, .nbdesigner-variation-setting .nbdesigner-box-container'), function (key, val) {
            var width = jQuery(this).find('.real_width_hidden').html(),
            height = jQuery(this).find('.real_height_hidden').html();
            jQuery(this).find('.real_width_px').html(parseInt(width * dpi / 2.54));
            jQuery(this).find('.real_height_px').html(parseInt(height * dpi / 2.54));
        });
        jQuery('#nbdesigner_dpi').val(dpi);
    },
    updateDimensionRealOutputImage: function(e, command){
        var parent = jQuery(e).parents('.nbdesigner-box-collapse').find('.nbdesigner-info-box'),
        width = parent.find('.area_design_width'),
        height = parent.find('.area_design_height'),
        dpi = jQuery('#nbdesigner_dpi').val(),
        sefl = jQuery(e);
        switch (command) {
            case 'width':
                var w = sefl.val(),
                original_val = parseInt(parent.find('.real_width_hidden').html()),        
                h = parent.find('.real_height').val(),
                _h = parseInt(h * w / original_val);
                parent.find('.real_height').val(_h);
                parent.find('.real_width_hidden').html(w);
                parent.find('.real_height_hidden').html(_h);
                parent.find('.real_width_px').html(parseInt(w * dpi / 2.54));
                parent.find('.real_height_px').html(parseInt(_h * dpi / 2.54));
                break;
            case 'height':
                var h = sefl.val(),
                original_val = parseInt(parent.find('.real_height_hidden').html()),        
                w = parent.find('.real_width').val(),
                _w = parseInt(w * h / original_val);
                parent.find('.real_width').val(_w);  
                parent.find('.real_width_hidden').html(_w);
                parent.find('.real_height_hidden').html(h);   
                parent.find('.real_width_px').html(parseInt(_w * dpi / 2.54));
                parent.find('.real_height_px').html(parseInt(h * dpi / 2.54));
                break;
        }
    },
    addOrientation: function (command) {
        var _command = command;
        if(command == 'com') _command = '';
        var id = '#nbdesigner-boxes' + _command;
        var old_box = jQuery(id+' .nbdesigner-box-container').last();
        if(old_box.css('display') == 'none'){
            old_box.show();
        }else{
            var checked = old_box.find('.bg_type:checked').val();
            var new_box = old_box.clone();
            new_box.appendTo(id);           
            jQuery(new_box).find('.bg_type').each(function(index, attr) { 
                jQuery(this).attr('name', 'bg_type_clone');
            });
            jQuery(old_box).find('.bg_type[value="'+checked+'"]').prop('checked', true);
            jQuery(new_box).find('.bg_type[value="'+checked+'"]').prop('checked', true);
            new_box.find('.ui-resizable-handle').remove();
            new_box.find('.nbd-helper').remove();
            new_box.find('.nbdesigner_bg_color').html("");
            new_box.find('.nbdesigner_bg_color').append('<input type="text" name="_designer_setting[0][bg_color_value]" value="#ffffff" class="nbd-color-picker" />');
            this.resetBoxes(command);            
        };
        
    },
    collapseBox: function (e) {
        var clicked_element = jQuery(e);
        var toggle_element = jQuery(e).parents('.nbdesigner-box-container').find('.nbdesigner-box-collapse');
        toggle_element.slideToggle(function () {
            if (toggle_element.is(':visible')) {
                clicked_element.html('<span class="dashicons dashicons-arrow-up"></span> Less setting');
            } else {
                clicked_element.html('<span class="dashicons dashicons-arrow-down"></span> More setting');
            }
        });                   
    },
    changeLang: function(){
        var code = jQuery("#nbdesigner-translate-code").val();
        jQuery("#nbdesigner-trans-code").attr('data-code', code);
        jQuery.ajax({
            url: admin_nbds.url,
            method: "POST",
            data: {'action': 'nbdesigner_get_language', 'code': code, 'nonce': admin_nbds.nonce},
            beforeSend: function () {
                jQuery('#nbdesigner_translate_loading').removeClass('nbdesigner_loaded');
            },
            complete: function () {
                jQuery('#nbdesigner_translate_loading').addClass('nbdesigner_loaded');
            }
        }).done(function (result) { 
            var data = JSON.parse(result);
            if(data.mes == "success"){
                var html = "";
                jQuery.each(data.langs, function(key, value ){
                    html += '<li><p class="click_edit" data-label="'+key+'">'+value+'</p></li>';
                });
                jQuery(".nbdesigner-translate").html(html);
                jQuery('.click_edit').editable(function(value, settings) {
                    return(value);
                },{ 
                    submit : 'OK',
                    tooltip : 'Click to edit...'
                });
            }
        });  
    },
    saveLang: function(e){        
        var langs = {},
        code = jQuery(e).data('code');       
        jQuery('.click_edit').each(function(){
            var label = jQuery(this).data('label');
            var text = jQuery(this).text();
            langs[label] = text.replace(/"/g,"");
        });       
        jQuery.ajax({
            url: admin_nbds.url,
            method: "POST",
            data: {'action': 'nbdesigner_save_language', 'code': code, 'nonce': admin_nbds.nonce, 'langs': langs},
            beforeSend: function () {
                jQuery('#nbdesigner_translate_loading').removeClass('nbdesigner_loaded');
            },
            complete: function () {
                jQuery('#nbdesigner_translate_loading').addClass('nbdesigner_loaded');
            }
        }).done(function (_data) {
            var data = JSON.parse(_data);
            if(parseInt(data.flag) == 1){
                swal(admin_nbds.nbds_lang.complete, data.mes, "success");
            }else{
                swal({
                    title: "Oops!",
                    text: data.mes,
                    imageUrl: admin_nbds.assets_images + "dinosaur.png"
                });
            }
        });        
    },
    deleteLang : function(e){
        var code = jQuery("#nbdesigner-translate-code").val(),
            index =  jQuery("#nbdesigner-translate-code").find(":selected").data('index'),   
            self = this;   
        swal({
            title: admin_nbds.nbds_lang.are_you_sure,
            text: admin_nbds.nbds_lang.warning_mes_delete_lang,
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function(){
            jQuery.ajax({
                url: admin_nbds.url,
                method: "POST",
                data: {'action': 'nbdesigner_delete_language', 'code': code, 'nonce': admin_nbds.nonce, 'index': index},
                beforeSend: function () {
                    jQuery('#nbdesigner_translate_loading').removeClass('nbdesigner_loaded');
                },
                complete: function () {
                    jQuery('#nbdesigner_translate_loading').addClass('nbdesigner_loaded');
                }           
            }).done(function (_data) {
                var data = JSON.parse(_data);
                if(parseInt(data.flag) == 1){
                    swal(admin_nbds.nbds_lang.complete, data.mes, "success");
                    jQuery('#nbdesigner-translate-code option[data-index="'+index+'"]').remove();
                    self.resetIndexLang();
                    var html = "";
                    jQuery.each(data.langs, function(key, value ){
                        html += '<li><p class="click_edit" data-label="'+key+'">'+value+'</p></li>';
                    });
                    jQuery(".nbdesigner-translate").html(html);
                    jQuery('.click_edit').editable(function(value, settings) {
                        return(value);
                    },{ 
                        submit : 'OK',
                        tooltip : 'Click to edit...'
                    });                     
                }else{
                    swal({
                        title: "Oops!",
                        text: data.mes,
                        imageUrl: admin_nbds.assets_images + "dinosaur.png"
                    });
                }
            })     
        });
    },
    resetIndexLang : function(){
        jQuery.each(jQuery('#nbdesigner-translate-code option'), function(key, value ){
            jQuery(this).attr('data-index', key);
        })        
    },
    createLang: function(){
        var self = this;    
        var formdata = jQuery('#nbdesigner-new-lang-con').find('textarea, select, input').serialize();
        var nbdesigner_namelang = jQuery('#nbdesign-language-option option:selected').text();
        formdata = formdata + '&nbdesigner_namelang='+nbdesigner_namelang+'&action=nbdesigner_create_language';
        jQuery('#nbdesigner_new_translate_loading').removeClass('nbdesigner_loaded');
        jQuery.post(admin_nbds.url, formdata, function(result){
            jQuery('#nbdesigner_new_translate_loading').addClass('nbdesigner_loaded');     
            var data = JSON.parse(result);
            if (data.flag == 1) {
                swal(admin_nbds.nbds_lang.complete, data.mes, "success");
                var html = "";
                jQuery.each(data.langs, function(key, value ){
                    html += '<li><p class="click_edit" data-label="'+key+'">'+value+'</p></li>';
                });
                jQuery(".nbdesigner-translate").html(html);
                jQuery('.click_edit').editable(function(value, settings) {
                    return(value);
                },{ 
                    submit : 'OK',
                    tooltip : 'Click to edit...'
                });  
                jQuery('#nbdesigner-translate-code').append('<option value="'+data.code+'" selected>'+data.name+'</option>');
                jQuery("#nbdesigner-trans-code").attr('data-code', data.code);
                self.resetIndexLang();
                tb_remove();
            }else{
                tb_remove();
                swal({
                    title: "Oops!",
                    text: data.mes,
                    imageUrl: admin_nbds.assets_images + "dinosaur.png"
                });
            }
        });        
    },
    edit_cat_art: function(e){   
        jQuery(e).parents('#nbdesigner_list_art_cats').find('.nbdesigner-editcat-name').hide();
        jQuery(e).parents('#nbdesigner_list_art_cats').find('.nbdesigner-cat-link').show();
        jQuery(e).parent().find('.nbdesigner-cat-link').hide();
        jQuery(e).parent().find('.nbdesigner-editcat-name').show(); 
        jQuery(e).parents('#nbdesigner_list_art_cats').find('li').removeClass('active');
        jQuery(e).parent().addClass('active');
    },
    remove_action_cat_art: function(e){
        jQuery(e).parents('#nbdesigner_list_art_cats').find('.nbdesigner-cat-link').show();
        jQuery(e).parents('#nbdesigner_list_art_cats').find('.nbdesigner-editcat-name').hide();
        jQuery(e).parents('#nbdesigner_list_art_cats').find('li').removeClass('active');
        return;
    },
    save_cat_art: function(e){
        var index = jQuery(e).parent().find('input').val(),
        name = jQuery(e).parent().find('input.nbdesigner-editcat-name').val(),
        sefl = jQuery(e);
        var data = {'action': 'nbdesigner_add_art_cat', 'id': index, 'name': name, 'nonce': admin_nbds.nonce};
        jQuery.ajax({
            url: admin_nbds.url,
            method: "POST",
            data: data,
            beforeSend: function () {
                jQuery('.nbdesigner_editcat_loading').removeClass('nbdesigner_loaded');
            },
            complete: function () {
                jQuery('.nbdesigner_editcat_loading').addClass('nbdesigner_loaded');
            }
        }).done(function (_data) {
            data = JSON.parse(_data);
            if (data.flag == 1) {
                swal(admin_nbds.nbds_lang.complete, data.mes, "success");
                sefl.parent().find('.nbdesigner-cat-link').html(name).show();
                sefl.parent().find('input.nbdesigner-editcat-name').val(name); 
                sefl.parent().find('.nbdesigner-editcat-name').val(name).hide(); 
            }else{
                swal({
                    title: "Oops!",
                    text: data.mes,
                    imageUrl: admin_nbds.assets_images + "dinosaur.png"
                });
                sefl.parent().find('.nbdesigner-cat-link').show();
                sefl.parent().find('.nbdesigner-editcat-name').val(name).hide(); 
            }
        }); 
        jQuery(e).parents('#nbdesigner_list_art_cats').find('li').removeClass('active');
    },
    edit_cat_font: function(e){
        jQuery(e).parents('#nbdesigner_list_cats').find('.nbdesigner-editcat-name').hide();
        jQuery(e).parents('#nbdesigner_list_cats').find('.nbdesigner-cat-link').show();
        jQuery(e).parent().find('.nbdesigner-cat-link').hide();
        jQuery(e).parent().find('.nbdesigner-editcat-name').show(); 
        jQuery(e).parents('#nbdesigner_list_cats').find('li').removeClass('active');
        jQuery(e).parent().addClass('active');
    },
    remove_action_cat_font: function(e){
        jQuery(e).parents('#nbdesigner_list_cats').find('.nbdesigner-cat-link').show();
        jQuery(e).parents('#nbdesigner_list_cats').find('.nbdesigner-editcat-name').hide();
        jQuery(e).parents('#nbdesigner_list_cats').find('li').removeClass('active');
        return;
    },  
    save_cat_font: function(e){
        var index = jQuery(e).parent().find('input').val(),
        name = jQuery(e).parent().find('input.nbdesigner-editcat-name').val(),
        sefl = jQuery(e);
        var data = {'action': 'nbdesigner_add_font_cat', 'id': index, 'name': name, 'nonce': admin_nbds.nonce};
        jQuery.ajax({
            url: admin_nbds.url,
            method: "POST",
            data: data,
            beforeSend: function () {
                jQuery('.nbdesigner_editcat_loading').removeClass('nbdesigner_loaded');
            },
            complete: function () {
                jQuery('.nbdesigner_editcat_loading').addClass('nbdesigner_loaded');
            }
        }).done(function (_data) {
            data = JSON.parse(_data);
            if (data.flag == 1) {
                swal(admin_nbds.nbds_lang.complete, data.mes, "success");
                sefl.parent().find('.nbdesigner-cat-link').html(name).show();
                sefl.parent().find('input.nbdesigner-editcat-name').val(name); 
                sefl.parent().find('.nbdesigner-editcat-name').val(name).hide(); 
            }else{
                swal({
                    title: "Oops!",
                    text: data.mes,
                    imageUrl: admin_nbds.assets_images + "dinosaur.png"
                });
                sefl.parent().find('.nbdesigner-cat-link').show();
                sefl.parent().find('.nbdesigner-editcat-name').val(name).hide(); 
            }
        }); 
        jQuery(e).parents('#nbdesigner_list_cats').find('li').removeClass('active');
    },
    make_primary_design: function(id){
        var task = jQuery('#nbdesigner-admin-template-action').val();
        if(task == -1){
            return;
        }
        var val = jQuery('input[name=nbdesigner_primary]:checked').val(),
        data = {'action': 'nbdesigner_make_primary_design', 'id': id, 'folder': val, 'nonce': admin_nbds.nonce, 'task' : task};
        //if(val == 'primary') return;
        if(task == 'delete'){
            if(val == 'primary'){
                swal("Oops..", "Can't delete primary design", "error");
                return;
            }
            swal({
                title: "Are you sure?",
                text: "You will be delete this template!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: false,
                closeOnCancel: true
            },function (isConfirm) {
                if (isConfirm) {
                    jQuery.ajax({
                        url: admin_nbds.url,
                        method: "POST",
                        data: data,
                        beforeSend: function () {
                            jQuery('.nbdesigner_primary_design').removeClass('nbdesigner_loaded');
                        },
                        complete: function () {
                            jQuery('.nbdesigner_primary_design').addClass('nbdesigner_loaded');
                        }
                    }).done(function (data) {
                        data = JSON.parse(data);
                        if (data['mes'] == 'success') {
                            jQuery('#nbdesigner-template-item-' + val).remove();
                            swal("Deleted!", "Your template has been deleted.", "success");      
                        }else{
                            swal("Oops..", "Something went wrong!", "error");
                        };
                    }); 
                } 
            });  
        }else{      
            jQuery.ajax({
                url: admin_nbds.url,
                method: "POST",
                data: data,
                beforeSend: function () {
                    jQuery('.nbdesigner_primary_design').removeClass('nbdesigner_loaded');
                },
                complete: function () {
                    jQuery('.nbdesigner_primary_design').addClass('nbdesigner_loaded');
                }
            }).done(function (data) {
                data = JSON.parse(data);
                if (data['mes'] == 'success') {
                    swal("Changed!", "Your template status has been changed.", "success"); 
                }else{
                    swal("Oops..", "Something went wrong!", "error");
                };
            })
        }
    },
    check_theme: function(e){
        e.preventDefault();
        var formdata = jQuery('#nbdesign-theme-check').find('textarea, select, input').serialize();
        formdata = formdata + '&action=nbdesigner_theme_check';
        jQuery('#nbdesigner_check_theme_loading').removeClass('nbdesigner_loaded');
        jQuery.post(admin_nbds.url, formdata, function(data){
            jQuery('#nbdesigner_check_theme_loading').addClass('nbdesigner_loaded');
            data = JSON.parse(data);
            if(data.flag == 'ok'){
                jQuery('.theme_check_note').html(data.html);
                //alert('Update success!');
            }else{
                alert('Oops! Try again!');
            }                  
        });        
    },
    show_variation_config : function(e){
        var self = this;
        var parent = jQuery(e).parents('.nbdesigner-setting-variation');
        if(jQuery(e).prop("checked")){
            parent.find('.nbdesigner-right.add_more').show();
            parent.find('.nbdesigner-variation-setting').show();
            jQuery.each(parent.find('.nbdesigner-area-design'), function (key, val) {
                var _this = this;
                jQuery(this).resizable({
                    handles: "ne, se, sw, nw",
                    aspectRatio: false,
                    maxWidth: NBD_STAGE.width,
                    maxHeight: NBD_STAGE.height,
                    resize: function (event, ui) {
                        self.updateDimension(_this, ui.size.width, ui.size.height, ui.position.left, ui.position.top);
                    },
                    start: function (event, ui) {
                        /*TODO*/
                    }
                }).draggable({containment: "parent",
                    drag: function (event, ui) {
                        self.updateDimension(_this, null, null, ui.position.left, ui.position.top);
                    }
                });                  
            })
          
        }else{
            parent.find('.nbdesigner-right.add_more').hide();
            parent.find('.nbdesigner-variation-setting').hide();            
        }
    },
    init_color_picker: function(){
        jQuery.each(jQuery('.nbd-color-picker'), function () {
            jQuery(this).wpColorPicker({
                change: function (evt, ui) {
                    var $input = jQuery(this);
                    setTimeout(function () {
                        if ($input.wpColorPicker('color') !== $input.data('tempcolor')) {
                            $input.change().data('tempcolor', $input.wpColorPicker('color'));
                            $input.val($input.wpColorPicker('color'));
                            $input.parents('.nbdesigner-box-collapse').find('.nbdesigner-image-original').css("background", ""); 
                            $input.parents('.nbdesigner-box-collapse').find('.nbdesigner-image-original').css("background", $input.wpColorPicker('color')); 
                        }
                    }, 10);
                }
            });            
        })
    },
    toggleShowOverlay : function(e){
        jQuery(e).parents('.nbdesigner-box-collapse').find('.nbdesigner-image-overlay').toggle();
        jQuery(e).parents('.nbdesigner-box-collapse').find('.overlay-toggle').toggle();
    },
    change_background_type : function(e){
        var value = jQuery(e).val();
        if(value == 'image'){
            jQuery(e).parents('.nbdesigner-box-collapse').find('.nbdesigner_bg_image').show();
            jQuery(e).parents('.nbdesigner-box-collapse').find('.nbdesigner_bg_color').hide();   
            jQuery(e).parents('.nbdesigner-box-collapse').find('.designer_img_src').show();
            jQuery(e).parents('.nbdesigner-box-collapse').find('.nbdesigner-image-original').removeClass("background-transparent");  
        }else if(value == 'color'){
            jQuery(e).parents('.nbdesigner-box-collapse').find('.nbdesigner_bg_image').hide();
            jQuery(e).parents('.nbdesigner-box-collapse').find('.nbdesigner_bg_color').show();      
            var color = jQuery(e).parents('.nbdesigner-box-collapse').find('.nbd-color-picker').val();
            jQuery(e).parents('.nbdesigner-box-collapse').find('.nbdesigner-image-original').removeClass("background-transparent"); 
            jQuery(e).parents('.nbdesigner-box-collapse').find('.nbdesigner-image-original').css("background", color);      
            jQuery(e).parents('.nbdesigner-box-collapse').find('.designer_img_src').hide();
        }else{
            jQuery(e).parents('.nbdesigner-box-collapse').find('.nbdesigner_bg_image').hide();
            jQuery(e).parents('.nbdesigner-box-collapse').find('.nbdesigner_bg_color').hide();    
            jQuery(e).parents('.nbdesigner-box-collapse').find('.designer_img_src').hide();
            jQuery(e).parents('.nbdesigner-box-collapse').find('.nbdesigner-image-original').css("background", "");      
            jQuery(e).parents('.nbdesigner-box-collapse').find('.nbdesigner-image-original').addClass("background-transparent");      
        }
    },
    change_dimension_product: function(e){
        var parent = jQuery(e).parents('.nbdesigner-box-collapse'),
        ip_left = parent.find('.hidden_img_src_left'),
        ip_top = parent.find('.hidden_img_src_top'),
        ip_width = parent.find('.hidden_img_src_width'),
        ip_height = parent.find('.hidden_img_src_height');
        var config = this.initParameter(e);
        ip_left.val(config.proSize.left);
        ip_top.val(config.proSize.top);
        ip_width.val(config.proSize.width);
        ip_height.val(config.proSize.height);
        parent.find('.nbdesigner-image-original').css({
            'width' : config.proSize.width,
            'height' : config.proSize.height,
            'left' : config.proSize.left,
            'top' : config.proSize.top
        });
        this.updateRelativePosition(e, 'width');
        this.updateRelativePosition(e, 'height');
        this.updateRelativePosition(e, 'top');
        this.updateRelativePosition(e, 'left');
    },
    updateRelativePosition: function(e, command){
        var parent = jQuery(e).parents('.nbdesigner-box-collapse');
        parent.find('.nbd-has-notice').removeClass('nbd-notice');
        var config = this.initParameter(e),
            new_value = 0;
        switch (command) {
            case 'width':
                new_value =  Math.round(config.ratio *  config.vRealWidth);
                config.iRelWidth.val(new_value);
                break;
            case 'height':
                new_value =  Math.round(config.ratio *  config.vRealHeight);
                config.iRelHeight.val(new_value);
                break;
            case 'top':             
                new_value =  Math.round(config.ratio *  (config.vRealTop + config.offset.top));
                config.iRelTop.val(new_value);             
                break;
            case 'left':
                new_value =  Math.round(config.ratio *  (config.vRealLeft + config.offset.left));
                config.iRelLeft.val(new_value);               
                break;
        }
        config.design_area.css(command, new_value);
        config.overlay_area.css(command, new_value);
        if((config.vRealWidth + config.vRealLeft) > config.vProWidth) parent.find('.notice-width').addClass('nbd-notice');
        if((config.vRealHeight + config.vRealTop) > config.vProHeight) parent.find('.notice-height').addClass('nbd-notice');
    },
    collapseAll : function(command){
        if(command == 'com') command = '';
        var id = '#nbdesigner-boxes' + command;
        jQuery.each(jQuery(id + ' .nbdesigner-collapse'), function(){
            var self = jQuery(this),
            toggle_element = self.parents('.nbdesigner-box-container').find('.nbdesigner-box-collapse');
            if (toggle_element.is(':visible')) {
                self.html('<span class="dashicons dashicons-arrow-down"></span> More setting');
                toggle_element.slideToggle();
            }          
        });
        return false;
    },
    updateDesignAreaSize : function(e){
        var config = this.initParameter(e);
        var vRealWidth = _round(config.vRelWidth / config.ratio, 2),
            vRealHeight = _round(config.vRelHeight / config.ratio, 2),
            vRealLeft = _round(config.vRelLeft / config.ratio - config.offset.left, 2),
            vRealTop = _round(config.vRelTop / config.ratio - config.offset.top, 2);
        config.iRealWidth.val(vRealWidth) ;   
        config.iRealHeight.val(vRealHeight) ;   
        config.iRealLeft.val(vRealLeft) ;   
        config.iRealTop.val(vRealTop) ;   
        config.updateRealSizeButton.removeClass('active');
        var config = this.initParameter(e);
    },
    initParameter: function(e){
        var parent = jQuery(e).parents('.nbdesigner-box-collapse'),
            iProWidth = parent.find('.product_width'),
            iProHeight = parent.find('.product_height'),
            vProWidth = parseFloat(iProWidth.val()),
            vProHeight = parseFloat(iProHeight.val()),
            iRealWidth = parent.find('.real_width'),
            iRealHeight = parent.find('.real_height'),
            iRealLeft = parent.find('.real_left'),
            iRealTop = parent.find('.real_top'),
            vRealWidth = parseFloat(iRealWidth.val()),
            vRealHeight = parseFloat(iRealHeight.val()),
            vRealLeft = parseFloat(iRealLeft.val()),
            vRealTop = parseFloat(iRealTop.val()),
            iRelWidth = parent.find('.area_design_width'),
            iRelHeight = parent.find('.area_design_height'),
            iRelLeft = parent.find('.area_design_left'),
            iRelTop = parent.find('.area_design_top'),
            vRelWidth = parseFloat(iRelWidth.val()),
            vRelHeight = parseFloat(iRelHeight.val()),
            vRelLeft = parseFloat(iRelLeft.val()),
            vRelTop = parseFloat(iRelTop.val()),         
            design_area = parent.find('.nbdesigner-area-design'),
            overlay_area = parent.find('.nbdesigner-image-overlay'),
            updateRealSizeButton = parent.find('.nbdesiger-update-area-design'),
            offset = {'left' : parseFloat(vProHeight - vProWidth)/2, 'top' : 0},
            ratio = NBD_STAGE.height / vProHeight,
            proSize = {
                'height' : NBD_STAGE.height,
                'width'  : round(vProWidth * ratio),
                'left'   : round((NBD_STAGE.width - vProWidth * ratio) / 2),
                'top'    : 0
            };
            if(vProWidth/vProHeight > NBD_STAGE.width/NBD_STAGE.height) {
                ratio = NBD_STAGE.width / vProWidth;
                offset = {'left' : 0, 'top' : parseFloat(vProWidth - vProHeight)/2};
                proSize = {
                    'width' : NBD_STAGE.width,
                    'height'  : round(vProHeight * ratio),
                    'top'   : round((NBD_STAGE.height - vProHeight * ratio) / 2),
                    'left'    : 0
                };                
            }            
        return {
            iProWidth : iProWidth,
            iProHeight : iProHeight,
            vProWidth : vProWidth,
            vProHeight : vProHeight,
            iRealWidth : iRealWidth,
            iRealHeight : iRealHeight,
            iRealLeft : iRealLeft,
            iRealTop : iRealTop,
            vRealWidth : vRealWidth,
            vRealHeight : vRealHeight,
            vRealLeft : vRealLeft,
            vRealTop : vRealTop,
            iRelWidth : iRelWidth,
            iRelHeight : iRelHeight,
            iRelLeft : iRelLeft,
            iRelTop : iRelTop,
            vRelWidth : vRelWidth,
            vRelHeight : vRelHeight,
            vRelLeft : vRelLeft,
            vRelTop : vRelTop,
            design_area : design_area,
            overlay_area : overlay_area,
            ratio : ratio,
            offset : offset,
            updateRealSizeButton : updateRealSizeButton,
            proSize : proSize
        };
    }
};
function base64Encode(str) {
  var CHARS = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
  var out = "", i = 0, len = str.length, c1, c2, c3;
  while (i < len) {
    c1 = str.charCodeAt(i++) & 0xff;
    if (i == len) {
      out += CHARS.charAt(c1 >> 2);
      out += CHARS.charAt((c1 & 0x3) << 4);
      out += "==";
      break;
    }
    c2 = str.charCodeAt(i++);
    if (i == len) {
      out += CHARS.charAt(c1 >> 2);
      out += CHARS.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));
      out += CHARS.charAt((c2 & 0xF) << 2);
      out += "=";
      break;
    }
    c3 = str.charCodeAt(i++);
    out += CHARS.charAt(c1 >> 2);
    out += CHARS.charAt(((c1 & 0x3) << 4) | ((c2 & 0xF0) >> 4));
    out += CHARS.charAt(((c2 & 0xF) << 2) | ((c3 & 0xC0) >> 6));
    out += CHARS.charAt(c3 & 0x3F);
  }
  return out;
}