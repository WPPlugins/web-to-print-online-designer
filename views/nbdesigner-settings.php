<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div class="nbd-license">
    <h3><span class="dashicons dashicons-id-alt"></span> <?php echo __("License", 'nbdesigner'); ?></h3>
    <table class="form-table">
        <tr valign="top" class="" id="nbdesigner_license" <?php if(isset($license['key'])) echo 'style="display: none;"'; ?>>
            <th scope="row" class="titledesc"><?php echo __("Get free license key", 'nbdesigner'); ?> </th>
            <td class="forminp-text">
                <input type="email" class="regular-text" name="nbdesigner[name]" placeholder="Enter your name"/><br /><br />
                <input type="email" class="regular-text" name="nbdesigner[email]" placeholder="Enter your email"/>
                <button  class="button-primary" id="nbdesigner_get_key" ><?php _e('Get key', 'nbdesigner'); ?></button>
                <img src="<?php echo NBDESIGNER_PLUGIN_URL.'assets/images/loading.gif' ?>" class="nbdesigner_loaded" id="nbdesigner_license_loading" style="margin-left: 15px;"/>
                <div class="description">
                    <small id="nbdesigner_key_mes"><?php _e('Please fill correct email. License key will be sent to your email.', 'nbdesigner'); ?></small>
                </div>
                <input type="hidden" name="nbdesigner[domain]" value="<?php echo $site_url; ?>"/>
                <input type="hidden" name="nbdesigner[title]" value="<?php echo $site_title; ?>"/>
                <?php wp_nonce_field($this->plugin_id.'-get-key', $this->plugin_id . '_getkey_hidden'); ?>
            </td>
        </tr>   
        <tr <?php if(isset($license['key'])) echo 'style="display: none;"'; ?>><td colspan="2"><hr /></td></tr>
        <tr valign="top" class="" id="nbdesigner_active_license">
            <?php wp_nonce_field('nbdesigner-active-key', '_nbdesigner_license_nonce'); ?>
            <th scope="row" class="titledesc"><?php echo __("Active license key", 'nbdesigner'); ?> </th>
            <td class="forminp-text">
                <input class="regular-text" type="text" id="nbdesigner_input_key" name="nbdesigner[license]" placeholder="Enter your key" value="<?php if(isset($license['key'])) echo $license['key']; ?>" <?php if(isset($license['key'])) echo ' readonly'; ?>/>
                <button  class="button-primary" id="nbdesigner_active_key" <?php if(isset($license['key'])) echo ' disabled'; ?>><?php _e('Active', 'nbdesigner'); ?></button>	
                <button  class="button-primary" id="nbdesigner_remove_key" ><?php _e('Remove', 'nbdesigner'); ?></button>	
                <img src="<?php echo NBDESIGNER_PLUGIN_URL.'assets/images/loading.gif' ?>" class="nbdesigner_loaded" id="nbdesigner_license_active_loading" style="margin-left: 15px;"/>
                <div>
                    <small id="nbdesigner_license_mes">
                        <?php //if(!isset($license['type'])) _e('Your license is incorrect or expired! ', 'nbdesigner');?>
                        <?php if(!isset($license['type']) || (isset($license['type']) && $license['type'] == 'free')) echo '<a href="'.NBDESIGNER_AUTHOR_SITE.'wordpress-plugins/woocommerce-online-product-designer-plugin" target="_blank">Upgrade Pro version!</a>';?>
                    </small>
                </div>
            </td>
        </tr>
    </table>    
</div>    
<script>
jQuery(document).ready(function(){
    jQuery('#nbdesigner_show_helper').on('click', function(e){
        e.preventDefault();
        jQuery("body").animate({ scrollTop: 0 }, 500, function(){
            jQuery('#contextual-help-link').trigger("click");
            jQuery('#tab-link-facebook a').trigger("click");            
        });        
    });
})
</script>