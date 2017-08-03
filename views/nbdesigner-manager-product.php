<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<h2 class="nbd-title-page"><?php echo __('Manager NBDesigner Products', 'nbdesigner'); ?></h2>
<div class="wrap postbox nbdesigner-manager-product">
    <div>
	<?php 
            global $wpdb;
            $link_create_template_page = getUrlPageNBD('template');
            foreach($pro as $key => $val): 
            $id = $val["id"];    
            $link_create_template = $link_create_template_page.'?product_id='.$id.'&priority=extra&task=create_template';
            $primary = get_post_meta($id, '_nbdesigner_admintemplate_primary', true);
            if(!$primary) $link_create_template = $link_create_template_page.'?product_id='.$id.'&priority=primary&task=create_template';
            $link_manager_template = add_query_arg(array('pid' => $id, 'view' => 'templates'), admin_url('admin.php?page=nbdesigner_manager_product'));
        ?>
		<div class="nbdesigner-product">
                    <a class="nbdesigner-product-title"><span><?php echo $val['name']; ?></span></a>
                    <div class="nbdesigner-product-inner">
                        <a href="<?php echo $val['url']; ?>" class="nbdesigner-product-link"><?php echo $val['img']; ?></a> 
                    </div>
                    <p class="nbdesigner-product-link">
                        <a href="<?php echo $val['url'].'#nbdesigner_setting'; ?>" title="<?php _e('Edit product', 'nbdesigner'); ?>"><span class="dashicons dashicons-edit"></span></a>
                        <a href="<?php echo get_permalink($val['id']); ?>" title="<?php _e('View product', 'nbdesigner'); ?>"><span class="dashicons dashicons-visibility"></span></a>
                        <a href="<?php echo $link_create_template; ?>" target="_blank" title="<?php _e('Create template', 'nbdesigner'); ?>"><span class="dashicons dashicons-admin-customizer"></span></a>
                        <a href="<?php echo $link_manager_template; ?>" title="<?php _e('Manager template', 'nbdesigner'); ?>"><span class="dashicons dashicons-images-alt"></span></a>
                    </p>                     
		</div>		
	<?php endforeach;?>
    </div>
    <div class="tablenav top">
        <div class="tablenav-pages">
            <span class="displaying-num"><?php echo $number_pro.' '. __('Products', 'nbdesigner'); ?></span>
            <?php echo $paging->html();  ?>
        </div>
    </div>    
</div>