<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div id="nbdesigner-setting-container">
    <?php wp_nonce_field('nbdesigner_setting_box', 'nbdesigner_setting_box_nonce'); ?>		
    <div class="nbdesigner-left">
        <input type="hidden" value="0" name="_nbdesigner_enable"/>
        <label for="_nbdesigner_enable" class="nbdesigner-setting-box-label"><?php echo _e('Enable Design', 'nbdesigner'); ?></label>
        <input type="checkbox" value="1" name="_nbdesigner_enable" id="_nbdesigner_enable" <?php checked($enable); ?> class="short" />
    </div>
    <div class="nbdesigner-right add_more" style="display: none;">
        <a class="button button-primary" onclick="NBDESIGNADMIN.addOrientation('com')"><?php echo __('Add More', 'nbdesigner'); ?></a>
        <a class="button button-primary" onclick="NBDESIGNADMIN.collapseAll('com')"><?php echo __('Collapse All', 'nbdesigner'); ?></a>
    </div>
    <div class="nbdesigner-clearfix"></div>
    <div id="nbdesigner_dpi_con" class="<?php if (!get_post_meta($post_id, '_nbdesigner_enable', true)) echo 'nbdesigner-disable'; ?>">
        <label for="nbdesigner_dpi" class="nbdesigner-setting-box-label"><?php echo _e('DPI', 'nbdesigner'); ?></label>
        <input name="_nbdesigner_dpi" id="nbdesigner_dpi" value="<?php echo $dpi;?>" type="number"  min="0" max="300" style="width: 60px;" onchange="NBDESIGNADMIN.updateSolutionImage()">&nbsp;<small>(<?php _e('Dots Per Inch', 'nbdesigner'); ?>)</small>   
    </div>
    <div id="nbdesigner-boxes" class="<?php if (!$enable) echo 'nbdesigner-disable'; ?>">
        <?php $count = 0;
        foreach ($designer_setting as $k => $v): ?>
            <div class="nbdesigner-box-container">
                <div class="nbdesigner-box">
                    <label class="nbdesigner-setting-box-label"><?php _e('Name', 'nbdesigner'); ?></label>
                    <div class="nbdesigner-setting-box-value">
                        <input name="_designer_setting[<?php echo $k; ?>][orientation_name]" class="short orientation_name" 
                               value="<?php echo $v['orientation_name']; ?>" type="text" required/>
                        <?php if($k ==0): ?>
                        <small class="nbd-helper"><?php _e('(Click', 'nbdesigner'); ?>  <span class="dashicons dashicons-editor-help"></span><?php _e('to know how to setting product design)', 'nbdesigner'); ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="nbdesigner-right">
                        <a class="button nbdesigner-collapse" onclick="NBDESIGNADMIN.collapseBox(this)"><span class="dashicons dashicons-arrow-up"></span><?php _e('Less setting', 'nbdesigner'); ?></a>
                        <a class="button nbdesigner-delete delete_orientation" data-index="<?php echo $k; ?>" data-variation="com" onclick="NBDESIGNADMIN.deleteOrientation(this)">&times;</a>
                    </div>
                </div>
                <div class="nbdesigner-box nbdesigner-box-collapse" data-variation="com">
                    <div class="nbdesigner-image-box">
                        <div class="nbdesigner-image-inner">
                            <?php 
                                if($v['product_width'] >= $v['product_height']){
                                    $ratio = 500 / $v['product_width'];
                                    $style_width = 500;
                                    $style_height = round($v['product_height'] * $ratio);
                                    $style_left = 0;
                                    $style_top = round((500 - $style_height) / 2);
                                } else {
                                    $ratio = 500 / $v['product_height'];
                                    $style_height = 500;
                                    $style_width = round($v['product_width'] * $ratio);
                                    $style_top = 0;
                                    $style_left = round((500 - $style_width) / 2);                                    
                                }
                            ?>
                            <div class="nbdesigner-image-original <?php if($v['bg_type'] == 'tran') echo "background-transparent"; ?>"
                                style="width: <?php echo $style_width; ?>px;
                                       height: <?php echo $style_height; ?>px;
                                       left: <?php echo $style_left; ?>px;
                                       top: <?php echo $style_top; ?>px;
                                <?php if($v['bg_type'] == 'color') echo 'background: ' .$v['bg_color_value']?>"       
                            >
                                <img src="<?php echo $v['img_src']; ?>" 
                                    <?php if($v['bg_type'] != 'image') echo ' style="display: none;"' ?>
                                     class="designer_img_src "
                                    />
                            </div>
                            <?php $overlay_style = 'none'; if($v['show_overlay']) $overlay_style = 'block'; ?>
                            <div class="nbdesigner-image-overlay"
                                style="width: <?php echo $v['area_design_width']; ?>px;
                                       height: <?php echo $v['area_design_height']; ?>px;
                                       left: <?php echo $v['area_design_left']; ?>px;
                                       top: <?php echo $v['area_design_top']; ?>px;
                                       display: <?php echo $overlay_style; ?>"                                 
                            >
                                <img src="<?php echo $v['img_overlay']; ?>" class="img_overlay"/>
                            </div>
                            <div class="nbdesigner-area-design" id="nbdesigner-area-design-<?php echo $k; ?>" 
                                 style="width: <?php echo $v['area_design_width'] . 'px'; ?>; 
                                        height: <?php echo $v['area_design_height'] . 'px'; ?>; 
                                        left: <?php echo $v['area_design_left'] . 'px'; ?>; 
                                        top: <?php echo $v['area_design_top'] . 'px'; ?>;"> </div>
                        </div>
                        <input type="hidden" class="hidden_img_src" name="_designer_setting[<?php echo $k; ?>][img_src]" value="<?php echo $v['img_src']; ?>" >
                        <input type="hidden" class="hidden_img_src_top" name="_designer_setting[<?php echo $k; ?>][img_src_top]" value="<?php echo $v['img_src_top']; ?>">
                        <input type="hidden" class="hidden_img_src_left" name="_designer_setting[<?php echo $k; ?>][img_src_left]" value="<?php echo $v['img_src_left']; ?>">
                        <input type="hidden" class="hidden_img_src_width" name="_designer_setting[<?php echo $k; ?>][img_src_width]" value="<?php echo $v['img_src_width']; ?>">
                        <input type="hidden" class="hidden_img_src_height" name="_designer_setting[<?php echo $k; ?>][img_src_height]" value="<?php echo $v['img_src_height']; ?>">
                        <input type="hidden" class="hidden_overlay_src" name="_designer_setting[<?php echo $k; ?>][img_overlay]" value="<?php echo $v['img_overlay']; ?>">
                        <input type="hidden" class="hidden_nbd_version" name="_designer_setting[<?php echo $k; ?>][version]" value="<?php echo $v['version']; ?>">
                        <div>	
                            <a class="button nbdesigner_move nbdesigner_move_left" data-index="<?php echo $k; ?>" onclick="NBDESIGNADMIN.nbdesigner_move(this, 'left')">&larr;</a>
                            <a class="button nbdesigner_move nbdesigner_move_right" data-index="<?php echo $k; ?>" onclick="NBDESIGNADMIN.nbdesigner_move(this, 'right')">&rarr;</a>
                            <a class="button nbdesigner_move nbdesigner_move_up" data-index="<?php echo $k; ?>" onclick="NBDESIGNADMIN.nbdesigner_move(this, 'up')">&uarr;</a>
                            <a class="button nbdesigner_move nbdesigner_move_down" data-index="<?php echo $k; ?>" onclick="NBDESIGNADMIN.nbdesigner_move(this, 'down')">&darr;</a>
                            <a class="button nbdesigner_move nbdesigner_move_center" data-index="<?php echo $k; ?>" onclick="NBDESIGNADMIN.nbdesigner_move(this, 'center')">&frac12;</a>
                            <a class="button nbdesigner_move nbdesigner_move_center" style="padding-left: 7px; padding-right: 7px;" data-index="<?php echo $k; ?>" onclick="NBDESIGNADMIN.nbdesigner_move(this, 'fit')"><i class="mce-ico mce-i-dfw" style="margin: 4px 0px 0px 0px !important; padding: 0 !important;"></i></a>
                        </div>
                        <div>
                            <p>
                                <label for="nbdesigner_bg_type" class="nbdesigner-setting-box-label"><?php _e('Background type'); ?></label>
                                <label class="nbdesigner-lbl-setting"><input type="radio" name="_designer_setting[<?php echo $k; ?>][bg_type]" value="image" 
                                    <?php checked($v['bg_type'], 'image', true); ?> class="bg_type"
                                    onclick="NBDESIGNADMIN.change_background_type(this)"   /><?php _e('Image', 'nbdesigner'); ?></label>
                                <label class="nbdesigner-lbl-setting"><input type="radio" name="_designer_setting[<?php echo $k; ?>][bg_type]" value="color" 
                                    <?php checked($v['bg_type'], 'color', true); ?> class="bg_type"
                                    onclick="NBDESIGNADMIN.change_background_type(this)"   /><?php _e('Color', 'nbdesigner'); ?></label>
                                <label class="nbdesigner-lbl-setting"><input type="radio" name="_designer_setting[<?php echo $k; ?>][bg_type]" value="tran" 
                                    <?php checked($v['bg_type'], 'tran', true); ?> class="bg_type"
                                    onclick="NBDESIGNADMIN.change_background_type(this)"   /><?php _e('Transparent', 'nbdesigner'); ?></label>
                            </p>
                        </div> 
                        <div class="nbdesigner_bg_image" <?php if($v['bg_type'] != 'image') echo ' style="display: none;"' ?>>
                            <a class="button nbdesigner-button nbdesigner-add-image" onclick="NBDESIGNADMIN.loadImage(this)" data-index="<?php echo $k; ?>"><?php echo __('Set image', 'nbdesigner'); ?></a>     
                        </div>
                        <div class="nbdesigner_bg_color" <?php if($v['bg_type'] != 'color') echo ' style="display: none;"' ?>>
                            <input type="text" name="_designer_setting[<?php echo $k; ?>][bg_color_value]" value="<?php echo $v['bg_color_value'] ?>" class="nbd-color-picker" />
                        </div>
                        <div class="nbdesigner_overlay_box">
                            <label class="nbdesigner-setting-box-label"><?php  _e('Overlay', 'nbdesigner'); ?></label>
                            <input type="hidden" value="0" name="_designer_setting[<?php echo $k; ?>][show_overlay]" class="show_overlay"/>                   
                            <input type="checkbox" value="1" 
                                name="_designer_setting[<?php echo $k; ?>][show_overlay]" id="_designer_setting[<?php echo $k; ?>][bg_type]" <?php checked($v['show_overlay']); ?> 
                                class="show_overlay" onchange="NBDESIGNADMIN.toggleShowOverlay(this)"/>  
                            <a class="button overlay-toggle" onclick="NBDESIGNADMIN.loadImageOverlay(this)" style="display: <?php if($v['show_overlay']) {echo 'inline-block';} else {echo 'none';} ?>">
                                <?php echo __('Set image', 'nbdesigner'); ?>
                            </a>
                            <img style="display: <?php if($v['show_overlay']) {echo 'inline-block';} else {echo 'none';} ?>"
                                 src="<?php if ($v['img_overlay'] != '') {echo $v['img_overlay'];} else {echo NBDESIGNER_PLUGIN_URL . 'assets/images/overlay.png';} ?>" class="img_overlay"/>                            
                        </div>
                    </div>
                    <div class="nbdesigner-info-box">
                        <?php if($k ==0): ?>
                        <p>
                            <span style="background: #b8dce8; width: 15px; height: 15px; display: inline-block;"></span>&nbsp;<?php _e('Product area', 'nbdesigner'); ?>&nbsp;
                            <span style="background: #dddacd; width: 15px; height: 15px; display: inline-block;"></span>&nbsp;<?php _e('Design area', 'nbdesigner'); ?><br />
                            <span style="border:2px solid #f0c6f6; width: 11px; height: 11px; display: inline-block;"></span>&nbsp;<?php _e('Bounding box', 'nbdesigner'); ?><small> (<?php _e('product always align vertical/horizontal center bounding box', 'nbdesigner'); ?>)</small>
                        </p>
                        <?php endif; ?>                        
                        <p class="nbd-setting-section-title">
                            <?php echo __('Product size', 'nbdesigner'); ?>
                            <?php if($k ==0): ?>
                            <span class="nbdesign-config-size-tooltip dashicons dashicons-editor-help nbd-helper"></span>
                            <?php endif; ?>
                        </p>
                        <div class="nbdesigner-info-box-inner notice-width nbd-has-notice">
                            <label class="nbdesigner-setting-box-label"><?php echo __('Width', 'nbdesigner'); ?><br /><small>(W<sub>p</sub>)</small></label>
                            <div>
                                <input type="number" step="any" min="0" name="_designer_setting[<?php echo $k; ?>][product_width]" 
                                       value="<?php echo $v['product_width']; ?>" class="short product_width" 
                                       onchange="NBDESIGNADMIN.change_dimension_product(this, 'width')"> <?php echo $unit; ?>
                            </div>
                        </div>
                        <div class="nbdesigner-info-box-inner notice-height nbd-has-notice">
                            <label class="nbdesigner-setting-box-label"><?php echo __('Height', 'nbdesigner'); ?><br /><small>(H<sub>p</sub>)</small></label>
                            <div>
                                <input type="number" step="any" min="0" name="_designer_setting[<?php echo $k; ?>][product_height]" 
                                       value="<?php echo $v['product_height']; ?>" class="short product_height"  
                                       onchange="NBDESIGNADMIN.change_dimension_product(this, 'height')"> <?php echo $unit; ?>
                            </div>
                        </div> 
                        <p class="nbd-setting-section-title">
                            <?php echo __('Design area size', 'nbdesigner'); ?>
                            <?php if($k ==0): ?>
                            <span class="nbdesign-config-realsize-tooltip dashicons dashicons-editor-help nbd-helper"></span>
                            <?php endif; ?>                              
                        </p>
                        <div class="nbdesigner-info-box-inner notice-width nbd-has-notice">
                            <label class="nbdesigner-setting-box-label"><?php echo __('Width', 'nbdesigner'); ?><br /><small>(W<sub>d</sub>)</small></label>
                            <div>
                                <input type="number" step="any" name="_designer_setting[<?php echo $k; ?>][real_width]" value="<?php echo $v['real_width']; ?>" class="short real_width" 
                                       onchange="NBDESIGNADMIN.updateRelativePosition(this, 'width')"> <?php echo $unit; ?> 
                            </div>
                        </div>
                        <div class="nbdesigner-info-box-inner notice-height nbd-has-notice">
                            <label class="nbdesigner-setting-box-label"><?php echo __('Height', 'nbdesigner'); ?><br /><small>(H<sub>d</sub>)</small></label>
                            <div>
                                <input type="number" step="any" min="0" name="_designer_setting[<?php echo $k; ?>][real_height]" value="<?php echo $v['real_height']; ?>" class="short real_height"  
                                       onchange="NBDESIGNADMIN.updateRelativePosition(this, 'height')"> <?php echo $unit; ?> 
                            </div>
                        </div>   
                        <div class="nbdesigner-info-box-inner notice-height nbd-has-notice">
                            <label class="nbdesigner-setting-box-label"><?php echo __('Top', 'nbdesigner'); ?><br /><small>(T<sub>d</sub>)</small></label>
                            <div>
                                <input type="number" step="any" min="0" name="_designer_setting[<?php echo $k; ?>][real_top]" value="<?php echo $v['real_top']; ?>" class="short real_top"  
                                       onchange="NBDESIGNADMIN.updateRelativePosition(this, 'top')"> <?php echo $unit; ?> 
                            </div>
                        </div> 
                        <div class="nbdesigner-info-box-inner">
                            <label class="nbdesigner-setting-box-label notice-width nbd-has-notice"><?php echo __('Left', 'nbdesigner'); ?><br /><small>(L<sub>d</sub>)</small></label>
                            <div>
                                <input type="number" step="any" min="0" name="_designer_setting[<?php echo $k; ?>][real_left]" value="<?php echo $v['real_left']; ?>" class="short real_left"  
                                       onchange="NBDESIGNADMIN.updateRelativePosition(this, 'left')"> <?php echo $unit; ?> 
                            </div>
                        </div>                         
                        <p class="nbd-setting-section-title">
                            <?php echo __('Relative position', 'nbdesigner'); ?>&nbsp;
                            <?php if($k == 0): ?> 
                            <span class="nbdesign-config-tooltip dashicons dashicons-editor-help nbd-helper"></span>
                            <?php endif; ?>
                            <span class="dashicons dashicons-update nbdesiger-update-area-design" onclick="NBDESIGNADMIN.updateDesignAreaSize(this)"></span>
                        </p>
                        <div class="nbdesigner-info-box-inner">
                            <label class="nbdesigner-setting-box-label"><?php echo __('Width', 'nbdesigner'); ?></label>
                            <div>
                                <input type="number" step="any"  min="0" name="_designer_setting[<?php echo $k; ?>][area_design_width]" 
                                       value="<?php echo $v['area_design_width']; ?>" class="short area_design_dimension area_design_width" data-index="width" 
                                       onchange="NBDESIGNADMIN.updatePositionDesignArea(this)">&nbsp;px
                            </div>
                        </div>
                        <div class="nbdesigner-info-box-inner">
                            <label class="nbdesigner-setting-box-label"><?php echo __('Height', 'nbdesigner'); ?></label>
                            <div>
                                <input type="number"  step="any" min="0" name="_designer_setting[<?php echo $k; ?>][area_design_height]" value="<?php echo $v['area_design_height']; ?>" class="short area_design_dimension area_design_height" data-index="height" onchange="NBDESIGNADMIN.updatePositionDesignArea(this)">&nbsp;px
                            </div>
                        </div>	
                        <div class="nbdesigner-info-box-inner">
                            <label class="nbdesigner-setting-box-label"><?php echo __('Left', 'nbdesigner'); ?></label>
                            <div>
                                <input type="number" step="any"  min="0" name="_designer_setting[<?php echo $k; ?>][area_design_left]" value="<?php echo $v['area_design_left']; ?>" class="short area_design_dimension area_design_left" data-index="left" onchange="NBDESIGNADMIN.updatePositionDesignArea(this)">&nbsp;px
                            </div>
                        </div>	                        
                        <div class="nbdesigner-info-box-inner">
                            <label class="nbdesigner-setting-box-label"><?php echo __('Top', 'nbdesigner'); ?></label>
                            <div>
                                <input type="number" step="any"  min="0" name="_designer_setting[<?php echo $k; ?>][area_design_top]" value="<?php echo $v['area_design_top']; ?>" class="short area_design_dimension area_design_top" data-index="top" onchange="NBDESIGNADMIN.updatePositionDesignArea(this)">&nbsp;px
                                
                            </div>
                        </div>                                                                   
                    </div>	
                </div>
            </div>
    <?php $count++;
endforeach; ?>
        <input type="hidden" value="<?php echo $count; ?>" id="nbdesigner-count-box"/>
    </div>
    <div id="nbdesigner-option" class="nbdesigner-option <?php if (!$enable) echo 'nbdesigner-disable'; ?>">
        <div class="nbdesigner-opt-inner">
            <label for="_nbdesigner_admindesign" class="nbdesigner-setting-box-label"><?php echo _e('Admin design template', 'nbdesigner'); ?></label>
            <input type="checkbox" value="1" name="_nbdesigner_option[admindesign]" id="_nbdesigner_admindesign" <?php checked(isset($option['admindesign']) ? $option['admindesign'] : false); ?> class="short"/>
            <?php if($enable && isset($option['admindesign'])): ?>
                <?php if($priority):?>
                <a class="button nbd-admin-tem-link" href="<?php echo $link_admindesign.'&priority=primary&task=edit_template'; ?>" target="_blank">
                    <span class="dashicons dashicons-admin-network"></span> 
                    <?php echo _e('Edit Primary Template', 'nbdesigner'); ?>
                </a>
                <a class="button nbd-admin-tem-link" href="<?php echo $link_admindesign.'&priority=extra&task=create_template'; ?>" target="_blank">
                    <span class="dashicons dashicons-plus"></span> 
                    <?php echo _e('Add Template', 'nbdesigner'); ?>
                </a>
                <?php else:?>
                <a class="button nbd-admin-tem-link" href="<?php echo $link_admindesign.'&priority=primary&task=create_template'; ?>" target="_blank">
                    <span class="dashicons dashicons-art"></span>
                    <?php echo _e('Create Template', 'nbdesigner'); ?>
                </a>
                <?php 
                    endif;
                    $link_manager_template = add_query_arg(array('pid' => $post_id, 'view' => 'templates'), admin_url('admin.php?page=nbdesigner_manager_product'));
                ?>
                <a href="<?php echo $link_manager_template; ?>" class="button nbd-admin-tem-link">
                    <span class="dashicons dashicons-images-alt"></span>
                    <?php echo _e('Manager Templates', 'nbdesigner'); ?>
                </a>
            <?php else: ?>
            <small><?php echo _e('After save product, you\'ll see link to start design templates', 'nbdesigner'); ?></small>
            <?php endif; ?>
        </div>  
        <div class="nbdesigner-opt-inner" style="display: none;">
            <label for="_nbdesigner_customprice" class="nbdesigner-setting-box-label"><?php echo _e('Custom price', 'nbdesigner'); ?></label>
            <input type="number" step="any" class="short nbdesigner-short-input" id="_nbdesigner_customprice" name="_nbdesigner_option[customprice]" value="<?php if(isset($option['customprice'])) echo $option['customprice']; ?>"/>
        </div>
    </div>    
</div>
<?php
function  add_js_code(){
?><script>
    jQuery(document).ready( function($) {
        var direction = "<?php if(is_rtl()) echo 'right'; else echo 'left'; ?>";
        var options = {
            "content":"<h3>" + "<?php _e('Notice', 'nbdesigner'); ?>" + "<\/h3>" +
                       "<p>" + "<?php _e('Bellow values must in range from 0 to 500px', 'nbdesigner'); ?>" + "<\/p>" + 
                       "<p>" + "<?php _e('There are relative position of design area in bounding box.', 'nbdesigner'); ?>" + "<\/p>" +
                       "<p><img style='max-width: 100%;' src='"+"<?php echo NBDESIGNER_PLUGIN_URL .'assets/images/bounding-box.png'; ?>"+"' /><br /><a href='"+"<?php echo NBDESIGNER_PLUGIN_URL .'assets/images/bounding-box.png'; ?>"+"' target='_blank'>" + "<?php  _e('See detail', 'nbdesigner'); ?>" + "</a></p>",
            "position": {"edge":direction, "align":"center"}
        };
        if ( ! options ) return;
        options = $.extend( options, {
            close: function() {
                //to do
            }
        });
        $('.nbdesign-config-tooltip').first().pointer( options );
        $('.nbdesign-config-tooltip').first().on('click', function(){
            $(this).pointer("open")
        });
        var size_options = {
            "content" : "<h3>" + "<?php _e('Notice', 'nbdesigner'); ?>" + "<\/h3>" +
                        "<p>"+"<?php _e('Please upload background image with aspect ratio', 'nbdesigner'); ?>"+": W<sub>p</sub>&timesH<sub>p</sub>.</p>" +
                        "<p>" + "<?php _e('Make sure setting', 'nbdesigner'); ?>" + " <span style='font-weight: bold; background: #b8dce8;'>" + "<?php _e('Product size', 'nbdesigner'); ?>" + "</span> " + "<?php _e('must always be the top priority!', 'nbdesigner'); ?>" + "</p>" +
                        "<p>" + "<?php _e('You have two order setting options', 'nbdesigner'); ?>" + 
                        ": <br /><strong>1</strong> - <span style='font-weight: bold; background: #b8dce8;'>" + "<?php _e('Product size', 'nbdesigner'); ?>" + "</span> →"+
                        " <span style='font-weight: bold; background: #dddacd;'>" + "<?php _e('Design area size', 'nbdesigner'); ?>" + "</span> "+
                        " (<span style='font-weight: bold; background: #f0c6f6;'>" + "<?php _e('Relative position', 'nbdesigner'); ?>" + "</span> "+"<?php _e('will automatic update', 'nbdesigner'); ?>"+")" +
                        "<br /><strong>2</strong> - <span style='font-weight: bold; background: #b8dce8;'>" + "<?php _e('Product size', 'nbdesigner'); ?>" + "</span> →"+
                        " <span style='font-weight: bold; background: #f0c6f6;'>" + "<?php _e('Relative position', 'nbdesigner'); ?>" + "</span> → "+  
                        "<?php _e('click', 'nbdesigner'); ?>" + "<span class='dashicons dashicons-update'></span> "+"<?php _e('to update', 'nbdesigner'); ?>"+" <span style='font-weight: bold; background: #f0c6f6;'>" + "<?php _e('Design area size', 'nbdesigner'); ?>" + "</span>"+ 
                        "</p>",
            "position": {"edge":direction, "align":"center"}
        };
        $('.nbdesign-config-size-tooltip').first().pointer( size_options );
        $('.nbdesign-config-size-tooltip').first().on('click', function(){
            $(this).pointer("open")
        });
        var da_option = {
            "content" : "<h3>" + "<?php _e('Notice', 'nbdesigner'); ?>" + "<\/h3>" +
                        "<p>"+"<?php _e('After change bellow', 'nbdesigner'); ?>"+" <span style='background: #dddacd; font-weight: bold;'>"+"<?php _e('values', 'nbdesigner'); ?>"+"</span>, "+"<span style='background: #f0c6f6; font-weight: bold;'>"+"<?php _e('relative position', 'nbdesigner'); ?>"+"</span> "+"<?php _e('of design area in bounding box will automatic update.', 'nbdesigner'); ?>"+"</p>" +
                        "<p>" + "<?php _e('Notice', 'nbdesigner'); ?>" + ": W<sub>p</sub> &gt;= W<sub>d</sub> + L<sub>d</sub>" +
                        " | H<sub>p</sub> &gt;= H<sub>d</sub> + T<sub>d</sub>" +
                        "<br />"+"<?php _e('If color labels change to ', 'nbdesigner'); ?>"+"<span style='color: red'>"+"<?php _e('red', 'nbdesigner'); ?>"+"</span>, "+"<?php _e('check values again.', 'nbdesigner'); ?>"+"</p>" +                       
                        "<p>"+"<?php _e('There', 'nbdesigner'); ?>"+" <span style='background: #dddacd; font-weight: bold;'>"+"<?php _e('values', 'nbdesigner'); ?>"+"</span> "+"<?php _e('will decide dimensions of output images.', 'nbdesigner'); ?>"+"</p>" +
                        "<p>"+"<?php _e('If you modify', 'nbdesigner'); ?>"+" <span style='background: #f0c6f6; font-weight: bold;'>"+"<?php _e('relative position', 'nbdesigner'); ?>"+"</span>, "+"<?php _e('click button', 'nbdesigner'); ?>"+" <span class='dashicons dashicons-update'></span> "+"<?php _e('to update', 'nbdesigner'); ?>"+"<span style='background: #dddacd; font-weight: bold;'> "+"<?php _e('Design area.', 'nbdesigner'); ?>"+"</span>"+"</p>" ,
            "position": {"edge":direction, "align":"center"}            
        };
        $('.nbdesign-config-realsize-tooltip').first().pointer( da_option );
        $('.nbdesign-config-realsize-tooltip').first().on('click', function(){
            $(this).pointer("open")
        });        
    });
</script>
<?php
}
add_action("admin_footer", "add_js_code");
?>