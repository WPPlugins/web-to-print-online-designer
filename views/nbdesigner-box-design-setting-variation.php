<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div class="nbdesigner-setting-variation">
    <h3><?php __('Setting Design', 'nbdesigner'); ?></h3>
    <div class="nbdesigner-left">
        <input type="hidden" value="0" name="_nbdesigner_enable<?php echo $vid; ?>"/>
        <label for="_nbdesigner_enable<?php echo $vid; ?>" class="nbdesigner-setting-box-label"><?php echo _e('Enable Design for this variation', 'nbdesigner'); ?></label>
        <input type="checkbox" value="1" name="_nbdesigner_enable<?php echo $vid; ?>" <?php checked($enable); ?> class="short nbdesigner_variation_enable" onchange="NBDESIGNADMIN.show_variation_config(this)"/>
    </div>    
    <div class="nbdesigner-right add_more" style="<?php if(!$enable) echo 'display: none;'; ?>">
        <a class="button button-primary" onclick="NBDESIGNADMIN.addOrientation(<?php echo $vid; ?>)"><?php echo __('Add More', 'nbdesigner'); ?></a>
        <a class="button button-primary" onclick="NBDESIGNADMIN.collapseAll(<?php echo $vid; ?>)"><?php echo __('Collapse All', 'nbdesigner'); ?></a>
    </div>    
    <div class="nbdesigner-clearfix"></div>
    <div id="nbdesigner-boxes<?php echo $vid; ?>" class="<?php if (!$enable) echo 'nbdesigner-disable'; ?> nbdesigner-variation-setting">
        <?php foreach ($designer_setting as $k => $v): ?>
            <div class="nbdesigner-box-container">
                <div class="nbdesigner-box">
                    <label class="nbdesigner-setting-box-label"><?php _e('Name', 'nbdesigner'); ?></label>
                    <div class="nbdesigner-setting-box-value">
                        <input name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][orientation_name]" class="short orientation_name" value="<?php echo $v['orientation_name']; ?>" type="text" required/>
                    </div>
                    <div class="nbdesigner-right">
                        <a class="button nbdesigner-collapse" onclick="NBDESIGNADMIN.collapseBox(this)"><span class="dashicons dashicons-arrow-down"></span><?php _e('More setting', 'nbdesigner'); ?></a>
                        <a class="button nbdesigner-delete delete_orientation" data-index="<?php echo $k; ?>" data-variation="<?php echo $vid; ?>" onclick="NBDESIGNADMIN.deleteOrientation(this)">&times;</a>
                    </div>
                </div>
                <div class="nbdesigner-box nbdesigner-box-collapse" data-variation="<?php echo $vid; ?>" style="display: none;">
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
                                <img src="<?php if ($v['img_src'] != '') {echo $v['img_src'];} else {echo NBDESIGNER_PLUGIN_URL . 'assets/images/default.png';} ?>" 
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
                                <img src="<?php if ($v['img_overlay'] != '') {echo $v['img_overlay'];} else {echo NBDESIGNER_PLUGIN_URL . 'assets/images/overlay.png';} ?>" class="img_overlay"/>
                            </div>
                            <div class="nbdesigner-area-design" id="nbdesigner-area-design-<?php echo $k; ?>" 
                                 style="width: <?php echo $v['area_design_width'] . 'px'; ?>; 
                                        height: <?php echo $v['area_design_height'] . 'px'; ?>; 
                                        left: <?php echo $v['area_design_left'] . 'px'; ?>; 
                                        top: <?php echo $v['area_design_top'] . 'px'; ?>;"> </div>                            
                        </div>
                        <input type="hidden" class="hidden_img_src" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][img_src]" value="<?php echo $v['img_src']; ?>" >
                        <input type="hidden" class="hidden_img_src_top" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][img_src_top]" value="<?php echo $v['img_src_top']; ?>" >
                        <input type="hidden" class="hidden_img_src_left" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][img_src_left]" value="<?php echo $v['img_src_left']; ?>">
                        <input type="hidden" class="hidden_img_src_width" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][img_src_width]" value="<?php echo $v['img_src_width']; ?>">
                        <input type="hidden" class="hidden_img_src_height" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][img_src_height]" value="<?php echo $v['img_src_height']; ?>">
                        <input type="hidden" class="hidden_overlay_src" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][img_overlay]" value="<?php echo $v['img_overlay']; ?>">
                        <input type="hidden" class="hidden_nbd_version" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][version]" value="<?php echo $v['version']; ?>">
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
                                <label class="nbdesigner-setting-box-label"><?php _e('Background type'); ?>:</label>
                                <label class="nbdesigner-lbl-setting"><input type="radio" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][bg_type]" value="image" 
                                    <?php checked($v['bg_type'], 'image', true); ?> class="bg_type"
                                    onclick="NBDESIGNADMIN.change_background_type(this)"   /><?php _e('Image', 'nbdesigner'); ?></label>
                                <label class="nbdesigner-lbl-setting"><input type="radio" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][bg_type]" value="color" 
                                    <?php checked($v['bg_type'], 'color', true); ?> class="bg_type"
                                    onclick="NBDESIGNADMIN.change_background_type(this)"   /><?php _e('Color', 'nbdesigner'); ?></label>
                                <label class="nbdesigner-lbl-setting"><input type="radio" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][bg_type]" value="tran" 
                                    <?php checked($v['bg_type'], 'tran', true); ?> class="bg_type"
                                    onclick="NBDESIGNADMIN.change_background_type(this)"   /><?php _e('Transparent', 'nbdesigner'); ?></label>
                            </p>
                        </div>   
                        <div class="nbdesigner_bg_image" <?php if($v['bg_type'] != 'image') echo ' style="display: none;"' ?>>
                            <a class="button nbdesigner-button nbdesigner-add-image" onclick="NBDESIGNADMIN.loadImage(this)" data-index="<?php echo $k; ?>"><?php echo __('Set image', 'nbdesigner'); ?></a>     
                        </div>     
                        <div class="nbdesigner_bg_color" <?php if($v['bg_type'] != 'color') echo ' style="display: none;"' ?>>
                            <input type="text" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][bg_color_value]" value="<?php echo $v['bg_color_value'] ?>" class="nbd-color-picker" />
                        </div> 
                        <div class="nbdesigner_overlay_box">
                            <label class="nbdesigner-setting-box-label"><?php _e('Overlay', 'nbdesigner'); ?></label>
                            <input type="hidden" value="0" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][show_overlay]" class="show_overlay"/>                   
                            <input type="checkbox" value="1" 
                                name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][show_overlay]" id="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][bg_type]" <?php checked($v['show_overlay']); ?> 
                                class="show_overlay" onchange="NBDESIGNADMIN.toggleShowOverlay(this)"/>  
                            <a class="button overlay-toggle" onclick="NBDESIGNADMIN.loadImageOverlay(this)" style="display: <?php if($v['show_overlay']) {echo 'inline-block';} else {echo 'none';} ?>">
                                <?php echo __('Set image', 'nbdesigner'); ?>
                            </a>
                            <img style="display: <?php if($v['show_overlay']) {echo 'inline-block';} else {echo 'none';} ?>"
                                 src="<?php if ($v['img_overlay'] != '') {echo $v['img_overlay'];} else {echo NBDESIGNER_PLUGIN_URL . 'assets/images/overlay.png';} ?>" class="img_overlay"/>                            
                        </div>                        
                    </div>
                    <hr style="clear: both;"/>
                    <div class="nbdesigner-info-box">
                        <p class="nbd-setting-section-title"><?php echo __('Product size:', 'nbdesigner'); ?></p>
                        <div class="nbdesigner-info-box-inner notice-width nbd-has-notice">
                            <label class="nbdesigner-setting-box-label"><?php echo __('Width', 'nbdesigner'); ?></label>
                            <div>
                                <input type="number" step="any" min="0" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][product_width]" 
                                       value="<?php echo $v['product_width']; ?>" class="short product_width" 
                                       onchange="NBDESIGNADMIN.change_dimension_product(this, 'width')"> <?php echo $unit; ?>
                            </div>
                        </div>
                        <div class="nbdesigner-info-box-inner notice-height nbd-has-notice">
                            <label class="nbdesigner-setting-box-label"><?php echo __('Height', 'nbdesigner'); ?></label>
                            <div>
                                <input type="number" step="any" min="0" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][product_height]" 
                                       value="<?php echo $v['product_height']; ?>" class="short product_height"  
                                       onchange="NBDESIGNADMIN.change_dimension_product(this, 'height')"> <?php echo $unit; ?>
                            </div>
                        </div>
                        <p class="nbd-setting-section-title"><?php echo __('Design area size:', 'nbdesigner'); ?></p>
                        <div class="nbdesigner-info-box-inner notice-width nbd-has-notice">
                            <label class="nbdesigner-setting-box-label"><?php echo __('Width', 'nbdesigner'); ?></label>
                            <div>
                                <input type="number" step="any" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][real_width]" 
                                       value="<?php echo $v['real_width']; ?>" class="short real_width" 
                                       onchange="NBDESIGNADMIN.updateRelativePosition(this, 'width')"> <?php echo $unit; ?> 
                            </div>
                        </div>
                        <div class="nbdesigner-info-box-inner notice-height nbd-has-notice">
                            <label class="nbdesigner-setting-box-label"><?php echo __('Height', 'nbdesigner'); ?></label>
                            <div>
                                <input type="number" step="any" min="0" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][real_height]" 
                                       value="<?php echo $v['real_height']; ?>" class="short real_height"  
                                       onchange="NBDESIGNADMIN.updateRelativePosition(this, 'height')"> <?php echo $unit; ?> 
                            </div>
                        </div>   
                        <div class="nbdesigner-info-box-inner notice-height nbd-has-notice">
                            <label class="nbdesigner-setting-box-label"><?php echo __('Top', 'nbdesigner'); ?></label>
                            <div>
                                <input type="number" step="any" min="0" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][real_top]" 
                                       value="<?php echo $v['real_top']; ?>" class="short real_top"  
                                       onchange="NBDESIGNADMIN.updateRelativePosition(this, 'top')"> <?php echo $unit; ?> 
                            </div>
                        </div> 
                        <div class="nbdesigner-info-box-inner notice-width nbd-has-notice">
                            <label class="nbdesigner-setting-box-label"><?php echo __('Left', 'nbdesigner'); ?></label>
                            <div>
                                <input type="number" step="any" min="0" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][real_left]" 
                                       value="<?php echo $v['real_left']; ?>" class="short real_left"  
                                       onchange="NBDESIGNADMIN.updateRelativePosition(this, 'left')"> <?php echo $unit; ?> 
                            </div>
                        </div> 	
                        <p class="nbd-setting-section-title">
                            <?php echo __('Relative position:', 'nbdesigner'); ?>
							<span class="dashicons dashicons-update nbdesiger-update-area-design" onclick="NBDESIGNADMIN.updateDesignAreaSize(this)"></span>
                        </p>
                        <div class="nbdesigner-info-box-inner">
                            <label class="nbdesigner-setting-box-label"><?php echo __('Width', 'nbdesigner'); ?></label>
                            <div>
                                <input type="number" step="any"  min="0" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][area_design_width]" 
                                       value="<?php echo $v['area_design_width']; ?>" class="short area_design_dimension area_design_width" data-index="width" 
                                       onchange="NBDESIGNADMIN.updatePositionDesignArea(this)">&nbsp;px
                            </div>
                        </div>
                        <div class="nbdesigner-info-box-inner">
                            <label class="nbdesigner-setting-box-label"><?php echo __('Height', 'nbdesigner'); ?></label>
                            <div>
                                <input type="number"  step="any" min="0" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][area_design_height]" 
                                       value="<?php echo $v['area_design_height']; ?>" class="short area_design_dimension area_design_height" data-index="height" 
                                       onchange="NBDESIGNADMIN.updatePositionDesignArea(this)">&nbsp;px
                            </div>
                        </div>	
                        <div class="nbdesigner-info-box-inner">
                            <label class="nbdesigner-setting-box-label"><?php echo __('Left', 'nbdesigner'); ?></label>
                            <div>
                                <input type="number" step="any"  min="0" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][area_design_left]" 
                                       value="<?php echo $v['area_design_left']; ?>" class="short area_design_dimension area_design_left" data-index="left" 
                                       onchange="NBDESIGNADMIN.updatePositionDesignArea(this)">&nbsp;px
                            </div>
                        </div>	                        
                        <div class="nbdesigner-info-box-inner">
                            <label class="nbdesigner-setting-box-label"><?php echo __('Top', 'nbdesigner'); ?></label>
                            <div>
                                <input type="number" step="any"  min="0" name="_designer_setting<?php echo $vid; ?>[<?php echo $k; ?>][area_design_top]" 
                                       value="<?php echo $v['area_design_top']; ?>" class="short area_design_dimension area_design_top" data-index="top" 
                                       onchange="NBDESIGNADMIN.updatePositionDesignArea(this)">&nbsp;px                                
                            </div>
                        </div>                         
                    </div>	
                </div>
            </div>        
        <?php endforeach; ?>
    </div>
</div>