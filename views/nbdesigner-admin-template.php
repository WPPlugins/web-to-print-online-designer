<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div class="wrap">
    <?php  
        $primary = get_post_meta($_GET['pid'], '_nbdesigner_admintemplate_primary', true);
        $link_add_template = getUrlPageNBD('template').'?product_id='.$_GET['pid'].'&priority=extra&task=create_template'; 
        if(!$primary) $link_add_template = getUrlPageNBD('template').'?product_id='.$_GET['pid'].'&priority=primary&task=create_template'; 
    ?>  
    <div class="wrap">
        <h1 class="nbd-title">
            <?php _e('Templates for', 'nbdesigner'); ?>: <a href="<?php echo get_edit_post_link($_GET['pid']); ?>"><?php echo $pro->get_title(); ?></a>
            <a class="button" href="<?php echo $link_add_template; ?>" target="_blank"><?php _e('Add Template'); ?></a>
            <a href="<?php echo admin_url('admin.php?page=nbdesigner_manager_product') ?>" class="button-primary nbdesigner-right"><?php _e('Back', 'nbdesigner'); ?></a>
        </h1>
        <div id="poststuff">
            <div id="post-body" class="metabox-holder">
                <div id="post-body-content">
                    <div class="meta-box-sortables ui-sortable">
                        <form method="post">
                        <?php
                            $templates_obj->prepare_items();
                            $templates_obj->display();
                        ?>
                        </form>
                    </div>
                </div>
            </div>
            <br class="clear">
        </div>
    </div>  
</div>
<style>
    .column-folder {
        width: 50%;
    }
    .column-user_id {
        width: 10%;
    }
    .column-folder img{
        width: 60px;
        margin-right: 5px;
        border: 1px solid #ddd;
        border-radius: 2px;
    }   
    .column-priority span {
        font-size: 20px;
    }
    .column-priority span.primary {
        color: #0085ba;
    }
</style>