<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div class="wrap nbdesigner ">
    <h2><?php echo __('Settings', 'nbdesigner'); ?></h2>
    <div>
        <h3><?php echo __("License", 'nbdesigner'); ?></h3>
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
                            <?php if(!isset($license['type']) || (isset($license['type']) && $license['type'] == 'free')) echo '<a href="'.$this->author_site.'wordpress-plugins/woocommerce-online-product-designer-plugin" target="_blank">Upgrade Pro version!</a>';?>
                        </small>
                    </div>
                </td>
            </tr>
        </table>     
    </div> 
    <hr />
    <h3><?php echo __("General Info", 'nbdesigner'); ?></h3>
    <form name="post" action="" method="post" autocomplete="off">
        <?php wp_nonce_field($this->plugin_id, $this->plugin_id . '_hidden'); ?>		
        <table class="form-table">
            <tr valign="top" style="display: none;">
                <th scope="row" class="titledesc"><?php echo __("Iframe security key", 'nbdesigner'); ?> </th>
                <td class="forminp-text">
                    <input  id="nbdesigner-sec-key" type="password" class="regular-text" name="nbdesigner[iframe_securitykey]" value="<?php echo $opt_val['iframe_securitykey']; ?>" />
                    <button type="button" class="button button-secondary" id="nbdesigner-gen-sec-key"><?php echo __("Generate Key", 'nbdesigner'); ?></button>
                    <button type="button" class="button button-secondary nbdesigner-key-show-hide wp-hide-pw" id="nbdesigner-toggle-show-sec-key" style="display: none;">
                        <span class="dashicons dashicons-visibility"></span>
                        <span class="nbdesigner-show-text text"><?php echo __("Show", 'nbdesigner'); ?></span>
                        <span class="nbdesigner-hide-text text"><?php echo __("Hide", 'nbdesigner'); ?></span>
                    </button>
                    <input type="hidden" value="1" id="nbdesigner-check-toggle-show">
                    <div><small><?php echo __("With security key, your website safer.", 'nbdesigner') ?></small></div>
                </td>
            </tr>            
            <tr valign="top">
                <th scope="row" class="titledesc"><?php echo __("Label of button design", 'nbdesigner'); ?> </th>
                <td class="forminp-text">
                    <input type="text" name="nbdesigner[btname]" value="<?php echo $opt_val['btname']; ?>" />						
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc"><?php echo __("Position of button design", 'nbdesigner'); ?> </th>
                <td class="forminp-text">
                    <input type="radio" name="nbdesigner[btn_position]" id="btn_position_1" value="1" <?php checked($opt_val['btn_position'], 1) ?>/>	
                    <label for="btn_position_1"><?php echo __("Before add to cart button and after variantions option", 'nbdesigner'); ?></label><br />
                    <input type="radio" name="nbdesigner[btn_position]" id="btn_position_2" value="2"  <?php checked($opt_val['btn_position'], 2) ?>/>	
                    <label for="btn_position_2"><?php echo __("Before variantions option", 'nbdesigner'); ?></label><br />
                    <input type="radio" name="nbdesigner[btn_position]" id="btn_position_3" value="3"  <?php checked($opt_val['btn_position'], 3) ?>/>	
                    <label for="btn_position_3"><?php echo __("After add to cart button", 'nbdesigner'); ?></label><br />                  					
                </td>
            </tr> 
            <tr><td colspan="2"><hr /></td></tr>
            <tr valign="top">
                <th scope="row" class="titledesc"><?php echo __("Max size upload", 'nbdesigner'); ?> </th>
                <td class="forminp-text">
                    <input type="number" class="small-text" name="nbdesigner[upload_max]" value="<?php echo $opt_val['upload_max']; ?>" min="1"/>&nbsp;MB						
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc"><?php echo __("Preview thumbnail size", 'nbdesigner'); ?> </th>
                <td class="forminp-text">
                    <label><?php echo __("Width:", 'nbdesigner'); ?></label>
                    <input type="number" class="small-text" name="nbdesigner[thumbnail_width]" value="<?php echo $opt_val['thumbnail_width']; ?>" />&nbsp;px&nbsp;&nbsp;	
                    <label><?php echo __("Height:", 'nbdesigner'); ?></label>
                    <input type="number" class="small-text" name="nbdesigner[thumbnail_height]" value="<?php echo $opt_val['thumbnail_height']; ?>" />&nbsp;px                    
                </td>             
            </tr> 
            <tr valign="top">
                <th scope="row" class="titledesc"><?php echo __("Thumbnail quality", 'nbdesigner'); ?> </th>
                <td class="forminp-text">
                    <input type="number" class="small-text" name="nbdesigner[thumbnail_quality]" value="<?php echo $opt_val['thumbnail_quality']; ?>" />&nbsp;%
                    <div><small><?php echo __("Quality of the generated thumbnails between 0 - 100", 'nbdesigner') ?></small></div>
                </td>
            </tr> 
            <tr><td colspan="2"><hr /></td></tr>
            <tr valign="top">
                <th scope="row" class="titledesc"><?php echo __("Show customer design in cart", 'nbdesigner'); ?> </th>
                <td class="forminp-text">
                    <input type="hidden" value="0" name="nbdesigner[show_design]"/>
                    <input type="checkbox" name="nbdesigner[show_design]" value="1" <?php checked($opt_val['show_design']); ?> />
                    <label><?php echo __("Enable", 'nbdesigner'); ?></label>
                </td>
            </tr>  
            <tr valign="top">
                <th scope="row" class="titledesc"><?php echo __("Show customer design in order", 'nbdesigner'); ?> </th>
                <td class="forminp-text">
                    <input type="hidden" value="0" name="nbdesigner[show_design_order]"/>
                    <input type="checkbox" name="nbdesigner[show_design_order]" value="1" <?php checked($opt_val['show_design_order']); ?> />
                    <label><?php echo __("Enable", 'nbdesigner'); ?></label>
                </td>
            </tr>      
            <tr><td colspan="2"><hr /></td></tr>
            <tr valign="top">
                <th scope="row" class="titledesc"><?php echo __("Admin notifications", 'nbdesigner'); ?> </th>
                <td class="forminp-text">
                    <input type="hidden" value="0" name="nbdesigner[notifications_enable]"/>
                    <input type="checkbox" name="nbdesigner[notifications_enable]" value="1" <?php checked($opt_val['notifications_enable']); ?> />
                    <label><?php echo __("Enable", 'nbdesigner'); ?></label>
                    <div><small><?php echo __("Send a message to the admin when customer design saved / changed.", 'nbdesigner'); ?></small></div>
                </td>
            </tr> 
            <tr valign="top">
                <th scope="row" class="titledesc"><?php echo __("Recurrence", 'nbdesigner'); ?> </th>
                <td class="forminp-text">
                    <select name="nbdesigner[owner_recurrence]">
                        <option value="hourly" <?php selected($opt_val['owner_recurrence'], 'hourly'); ?> ><?php echo __("Hourly", 'nbdesigner'); ?></option>
                        <option value="twicedaily" <?php selected($opt_val['owner_recurrence'], 'twicedaily'); ?> ><?php echo __("Twice a day", 'nbdesigner'); ?></option>
                        <option value="daily" <?php selected($opt_val['owner_recurrence'], 'daily'); ?> ><?php echo __("Daily", 'nbdesigner'); ?></option>                        
                    </select>
                    <div><small><?php echo __("Choose how many times you want to receive an e-mail.", 'nbdesigner'); ?></small></div>
                </td>
            </tr>             
            <tr valign="top">
                <th scope="row" class="titledesc"><?php echo __("Recipients", 'nbdesigner'); ?> </th>
                <td class="forminp-text">
                    <input type="text" class="regular-text" name="nbdesigner[owner_email]" value="<?php echo $opt_val['owner_email']; ?>" placeholder="Enter your email"/>
                    <div><small><?php echo sprintf(__( 'Enter recipients (comma separated) for this email. Defaults to %s', 'nbdesigner' ),'<code>'.get_option('admin_email').'</code>');?></small></div>
                </td>
            </tr>   
            <tr><td colspan="2"><hr /></td></tr>            
            <tr valign="top">
                <th scope="row" class="titledesc"><?php echo __("Facebook API Key", 'nbdesigner'); ?> </th>
                <td class="forminp-text">
                    <input type="text" name="nbdesigner[facebook_api_key]" value="<?php echo $opt_val['facebook_api_key']; ?>" />
                    <a href="javascript:void(0)" onclick="toggleproviderhelp()"><?php _e("Where do I get this info?", 'nbdesigner') ?></a>
                </td>
            </tr>	
            <tr valign="top">
                <th scope="row" class="titledesc"><?php echo __("Facebook App Secret", 'nbdesigner'); ?> </th>
                <td class="forminp-text">
                    <input type="text" name="nbdesigner[facebook_secret_key]" value="<?php echo $opt_val['facebook_secret_key']; ?>" />
                    <a href="javascript:void(0)" onclick="toggleproviderhelp()"><?php _e("Where do I get this info?", 'nbdesigner') ?></a>
                </td>
            </tr>
            <tr valign="top" style="display: none;" id="how-to-get-facebook-api">
                <td colspan="2">
                    <div>
                        <?php _e('<span style="color:#CB4B16;">Application</span> Key and Secret (also sometimes referred as <span style="color:#CB4B16;">Consumer</span> key and secret or <span style="color:#CB4B16;">Client</span> id and secret) are what we call an application credentials', 'nbdesigner') ?>.
                        <?php echo sprintf( __( 'This application will link your website <code>%s</code> to <code>%s API</code> and these credentials are needed in order for <b>%s</b> users to access your website', 'wordpress-social-login'), $_SERVER["SERVER_NAME"], 'Facebook', 'Facebook' ) ?>.
                        <br />
                        <?php _e("These credentials may also differ in format, name and content depending on the social network.", 'nbdesigner') ?>
                        <br />
                        <br />
                        <?php echo sprintf( __('To enable authentication with this provider and to register a new <b>%s API Application</b>, follow the steps', 'nbdesigner'), 'Facebook' ) ?>
                        :<br />      
                        <div style="margin-left:40px;">
                            <p><b>1. </b><?php _e('First go to:', 'nbdesigner')  ?><a href="https://developers.facebook.com/apps">https://developers.facebook.com/apps</a></p>
                            <p><b>2. </b><?php _e('Select <b>Add a New App</b> from the <b>Apps</b> menu at the top', 'nbdesigner')  ?></p>
                            <p><b>3. </b><?php _e('Fill out Display Name, Namespace, choose a category and click <b>Create App</b>', 'nbdesigner')  ?></p>
                            <p><b>4. </b><?php _e('Go to Settings page and click on <b>Add Platform</b>. Choose website and enter in the new screen your website url in <b>App Domains</b> and <b>Site URL</b> fields', 'nbdesigner')  ?>.<?php _e("They should match with the current hostname", 'nbdesigner') ?> <em style="color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"]; ?></em>.</p>
                            <p><b>5. </b><?php _e('Go to the <b>App Review</b> page and choose <b>yes</b> where it says <b>Do you want to make this app and all its live features available to the general public?</b>', 'nbdesigner')  ?>.
                            <?php _e('In section "Submit Items for Approval" click <b>Start a Submission</b>, in popup check "user_photos" and complete all steps', 'nbdesigner'); ?>
                            </p>
                            <p><b>6. </b><?php _e('Go back to the <b>Dashboard</b> page and past the created application credentials (APP ID and Secret) into the boxes above', 'nbdesigner')  ?></p>
                        </div>
                        <hr />
                        <div>
                            <p>
                                <b><?php _e("And that's it!", 'nbdesigner') ?></b><br />
                                <?php echo sprintf( __( 'If for some reason you still can\'t manage to create an application for %s, first try to <a href="https://www.google.com/search?q=%s API create application" target="_blank">Google it</a>, then check it on <a href="http://www.youtube.com/results?search_query=%s API create application " target="_blank">Youtube</a>, and if nothing works <a href="https://cmsmart.net/support_ticket">ask for support</a>', 'nbdesigner'), 'Facebook', 'Facebook', 'Facebook' ) ?>
                            </p>
                        </div>
                    </div>
                </td>
            </tr>            
<!--            <tr valign="top">
                <th scope="row" class="titledesc"><?php echo __("Instagram API Key", 'nbdesigner'); ?> </th>
                <td class="forminp-text">
                    <input type="text" name="nbdesigner[instagram_api_key]" value="<?php echo $opt_val['instagram_api_key']; ?>" />
                </td>
            </tr>	
            <tr valign="top">
                <th scope="row" class="titledesc"><?php echo __("Instagram App Secret", 'nbdesigner'); ?> </th>
                <td class="forminp-text">
                    <input type="text" name="nbdesigner[instagram_secret_key]" value="<?php echo $opt_val['instagram_secret_key']; ?>" />
                </td>
            </tr>	            -->
        </table>
        <p class="submit">
            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
        </p>		
    </form>

</div>
<script>
jQuery(document).ready(function(){
    toggleproviderhelp = function(){
        jQuery('#how-to-get-facebook-api').toggle("slow");
    }
})
</script>