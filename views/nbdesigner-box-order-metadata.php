<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<?php if(isset($has_design) && ($has_design == 'has_design')) : 
    $count_img_design = 0;
?>
<div id="nbdesigner_order_info">
	<?php foreach($products AS $order_item_id => $product): ?>
		<?php 
                    $has_design = wc_get_order_item_meta($order_item_id, '_nbdesigner_has_design');
                    if($has_design == 'has_design'): 
                    $index_accept = 'nbds_'.$order_item_id;
                    $folder = wc_get_order_item_meta($order_item_id, '_nbdesigner_folder_design');
                    $item_meta = new WC_Order_Item_Meta( $product );
					$variation = '';
                    if(!is_woo_v3()){
                        $variation = $item_meta->display($flat=true,$return=true);    
                    }                   
		?>
                    <div>
                        <h4 class="nbdesigner_order_product_name">
                            <?php echo $product['name']; ?>
                            <?php echo (!empty($variation))?'<span class="nbt-umf-ou-upload-product-variation"> - '.$variation.'</span>':''; ?>
                        </h4>
                        <div class="nbdesigner_container_item_order <?php if(isset($data_designs[$index_accept])) { $status = ($data_designs[$index_accept] == 'accept') ? 'approved' : 'declined'; echo $status;}; ?>">
                        <?php 
                            if($folder != ''){
                                $path = $this->plugin_path_data . 'designs/' . $user_id . '/' . $order_id .'/' . $folder .'/thumbs';
                            }else{
                                $path = $this->plugin_path_data . 'designs/' . $user_id . '/' . $order_id .'/' .$product["product_id"] .'/thumbs';
                            }
                            $list_images = $this->nbdesigner_list_thumb($path, 1);											
                            if(count($list_images) > 0):					
                        ?>
                            <input type="checkbox" name="_nbdesigner_design_file[]" class="nbdesigner_design_file" value="<?php echo $order_item_id; ?>" />
                            <?php foreach($list_images as $key => $image): 
                                $count_img_design++;
                                $src = $this->nbdesigner_create_secret_image_url($image);						
                            ?>						
                                    <img class="nbdesigner_order_image_design" src="<?php echo $src; ?>" />
                            <?php endforeach; ?>
                            <?php 
                                if($folder != ''){
                                    $arr = array('product_id' => $product["product_id"], 'order_id' => $order_id, 'order_item_id' => $order_item_id, 'vid' => $product['variation_id']);
                                    $link_view_detail = add_query_arg($arr, admin_url('admin.php?page=nbdesigner_detail_order'));
                                }else{
                                    $link_view_detail = add_query_arg(array('product_id' => $product["product_id"], 'order_id' => $order_id), admin_url('admin.php?page=nbdesigner_detail_order'));
                                }
                            ?>
                            <a class="nbdesigner-right button button-small button-secondary"  href="<?php echo $link_view_detail; ?>"><?php _e('View detail', 'nbdesigner'); ?></a>
                        <?php  endif; ?>
                        </div>
                    </div>
		<?php endif; ?>
	<?php endforeach;?>
    <br /> 
    <div class="nbdesigner-left" style="padding: 5px;">
        <input type="checkbox" class="" id="nbdesigner_order_design_check_all" />
        <label for="nbdesigner_order_design_check_all"><small><?php _e('Check all', 'nbdesigner'); ?></small></label>
    </div>
	<div class="nbdesigner-right" style="padding: 5px;">
		<?php  if($count_img_design > 0): ?>
			<a href="<?php echo add_query_arg(array('download-all' => 'true', 'order_id' => $order_id), admin_url('admin.php?page=nbdesigner_detail_order')); ?>" class="button button-small button-secondary"><?php _e('Download all', 'nbdesigner'); ?></a>
		<?php else: ?>
			<span class="button button-small button-disabled" style="color: #dedede;"><?php _e('Download all', 'nbdesigner'); ?></span>
		<?php endif; ?>
	</div>
	<div class="nbdesigner-clearfix"></div>
	<div>
		<?php _e('With selected:', 'nbdesigner'); ?>
		<img src="<?php echo NBDESIGNER_PLUGIN_URL.'assets/images/loading.gif' ?>" class="nbdesigner_loaded" id="nbdesigner_order_submit_loading" style="margin-left: 15px;"/>
		<div class="nbdesigner-right">
            <select name="nbdesigner_order_file_approve" class="">
                <option value="accept"><?php _e('Accept', 'nbdesigner'); ?></option>
                <option value="decline"><?php _e('Decline', 'nbdesigner'); ?></option>
            </select>
            <a href="#" class="button button-primary" id="nbdesigner_order_file_submit"><?php _e('GO', 'nbdesigner'); ?></a>			
		</div>
	</div>
	<input type="hidden" name="nbdesigner_design_order_id" value="<?php echo $order_id; ?>" />
	<?php wp_nonce_field('approve-designs', '_nbdesigner_approve_nonce'); ?>
	<div class="nbdesigner-clearfix"></div>
</div>
<div class="nbdesigner_container_order_email" id="nbdesigner_order_email_info">
	<h4><?php _e('Send mail','nbdesigner'); ?></h4>
	<?php wp_nonce_field('approve-design-email', '_nbdesigner_design_email_nonce'); ?>
	<input type="hidden" name="nbdesigner_design_email_order_id" value="<?php echo $order_id; ?>" />
    <div id="nbdesigner_order_email_error" class="nbdesigner_order_email_message hidden"></div>
    <div id="nbdesigner_order_email_success" class="nbdesigner_order_email_message hidden"></div>	
    <div>
        <label for="nbdesigner_design_email_order_content"><?php _e('Reason accepted / declined:', 'nbdesigner'); ?></label>
        <textarea name="nbdesigner_design_email_order_content" id="nbdesigner_design_email_order_content" rows="3" style="width: 100%;"></textarea>
    </div>	
    <div class="nbdesigner-right">
		<img src="<?php echo NBDESIGNER_PLUGIN_URL.'assets/images/loading.gif' ?>" class="nbdesigner_loaded" id="nbdesigner_order_mail_loading" style="margin-left: 15px;"/>
        <select name="nbdesigner_design_email_reason" class="">
            <option value="approved"><?php _e('Files accepted', 'nbdesigner'); ?></option>
            <option value="declined"><?php _e('Files rejected', 'nbdesigner'); ?></option>
        </select>
        <a href="#" class="button button-primary" id="nbdesigner_uploads_email_submit"><?php _e('Send mail','nbdesigner'); ?></a>
    </div>	
	<div class="nbdesigner-clearfix"></div>
</div>
<?php else: ?>
<p><?php _e('No design in this order', 'nbdesigner'); ?></p>
<?php endif; ?>