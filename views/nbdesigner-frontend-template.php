<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<!DOCTYPE html>
<?php
    $hide_on_mobile = nbdesigner_get_option('nbdesigner_disable_on_smartphones');
    if(wp_is_mobile() && $hide_on_mobile == 'yes'):                           
?>
<html lang="<?php echo str_replace('-', '_', get_bloginfo('language')); ?>" ng-app="app">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <title>Online Designer</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=1, minimum-scale=0.5, maximum-scale=1.0"/>
        <meta content="Online Designer - HTML5 Designer - Online Print Solution" name="description" />
        <meta content="Online Designer" name="keywords" />
        <meta content="Netbaseteam" name="author"> 
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300italic,300' rel='stylesheet' type='text/css'>
        <style type="text/css">
            html {
                width: 100%;
                height: 100%;
            }
            body {
                width: 100%;
                height: 100%;
                margin: 0;
                background-color: #f4f4f4;
            }
            p {
                margin: 0;
                text-align: center;
                font-family: 'Roboto', sans-serif;
            }
            p.announce {
                padding-left: 15px;
                padding-right: 15px;                
                font-size: 17px;
                margin-top: 15px;
                color: #999;
            }
            p img {
                max-width: 100%;
            }
            a {
                display: inline-block;
                color: #fff;
                background: #f98332;
                margin-top: 15px;
                padding: 10px;
                text-transform: uppercase;
                font-size: 14px;
                border-radius: 5px;
                box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);      
                text-decoration: none;
            }
        </style>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                window.parent.NBDESIGNERPRODUCT.nbdesigner_ready();
            });           
        </script>
    </head>
    <body>
        <p><img src="<?php echo NBDESIGNER_PLUGIN_URL . 'assets/images/mobile.png'; ?>" /></p>
        <p class="announce"><?php _e('Sorry, our design tool is not currently supported on mobile devices.', 'nbdesigner'); ?></p>
        <p class="recommend"><a href="javascript:void(0)" onclick="window.parent.hideDesignFrame();"><?php _e('Back to product', 'nbdesigner'); ?></a></p>
    </body>
</html>
<?php else: ?>
<html lang="<?php echo str_replace('-', '_', get_bloginfo('language')); ?>" ng-app="app">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <title>Online Designer</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=1, minimum-scale=0.5, maximum-scale=1.0"/>
        <meta content="Online Designer - HTML5 Designer - Online Print Solution" name="description" />
        <meta content="Online Designer" name="keywords" />
        <meta content="Netbaseteam" name="author">
        <link type="text/css" href="<?php echo NBDESIGNER_PLUGIN_URL .'assets/css/jquery-ui.min.css'; ?>" rel="stylesheet" media="all" />
        <link type="text/css" href="<?php echo NBDESIGNER_PLUGIN_URL .'assets/css/font-awesome.min.css'; ?>" rel="stylesheet" media="all" />
        <link href='https://fonts.googleapis.com/css?family=Audiowide' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300italic,300' rel='stylesheet' type='text/css'>
        <link type="text/css" href="<?php echo NBDESIGNER_PLUGIN_URL .'assets/css/bootstrap.min.css'; ?>" rel="stylesheet" media="all"/>
        <link type="text/css" href="<?php echo NBDESIGNER_PLUGIN_URL .'assets/css/bundle.css'; ?>" rel="stylesheet" media="all"/>
        <link type="text/css" href="<?php echo NBDESIGNER_PLUGIN_URL .'assets/css/owl.carousel.css'; ?>" rel="stylesheet" media="all"/>
        <link type="text/css" href="<?php echo NBDESIGNER_PLUGIN_URL .'assets/css/tooltipster.bundle.min.css'; ?>" rel="stylesheet" media="all"/>
        <link type="text/css" href="<?php echo NBDESIGNER_PLUGIN_URL .'assets/css/style.min.css'; ?>" rel="stylesheet" media="all">
        <link type="text/css" href="<?php echo NBDESIGNER_PLUGIN_URL .'assets/css/custom.css'; ?>" rel="stylesheet" media="all">
        <?php if(is_rtl()): ?>
        <link type="text/css" href="<?php echo NBDESIGNER_PLUGIN_URL .'assets/css/nbdesigner-rtl.css'; ?>" rel="stylesheet" media="all">
        <?php endif; ?>
        <?php 
            $enableColor = nbdesigner_get_option('nbdesigner_show_all_color'); 
            if($enableColor == 'no'):
        ?>
        <link type="text/css" href="<?php echo NBDESIGNER_PLUGIN_URL .'assets/css/spectrum.css'; ?>" rel="stylesheet" media="all">
        <?php endif; ?>
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->	
        <?php 
            $task = (isset($_GET['task']) &&  $_GET['task'] != '') ? $_GET['task'] : '';
            $order_id = (isset($_GET['orderid']) &&  $_GET['orderid'] != '') ? absint($_GET['orderid']) : '';
            $product_id = (isset($_GET['product_id']) &&  $_GET['product_id'] != '') ? absint($_GET['product_id']) : '';
            $variation_id = (isset($_GET['variation_id']) &&  $_GET['variation_id'] != '') ? absint($_GET['variation_id']) : 0;
            $template_folder = (isset($_GET['template_folder']) &&  $_GET['template_folder'] != '') ? $_GET['template_folder'] : '';
            $reference_product = (isset($_GET['reference_product']) &&  $_GET['reference_product'] != '') ? $_GET['reference_product'] : '';
            $order_item_folder = (isset($_GET['order_item_folder']) &&  $_GET['order_item_folder'] != '') ? $_GET['order_item_folder'] : '';
            $template_priority = (isset($_GET['priority']) &&  $_GET['priority'] != '') ? $_GET['priority'] : '';
            $user_id = (get_current_user_id() > 0) ? get_current_user_id() : session_id();
            $ui_mode = 1;/*1: iframe popup, 2: div popup, 3: studio*/
            $product = wc_get_product($product_id);
            $vid = 0;
            if( $product->is_type( 'variable' ) ) { 
                $available_variations = $product->get_available_variations();   
                if(is_woo_v3()){
                    $default_attributes = $product->get_default_attributes();  
                }else{
                    $default_attributes = $product->get_variation_default_attributes();  
                } 
                foreach ( $available_variations as $variation ){
                    if(count($default_attributes) == count($variation['attributes'])){
                        $vid = $variation['variation_id'];
                        foreach ($default_attributes as $key => $attribute){
                            if($variation['attributes']['attribute_'.$key] != $attribute){
                                $vid = 0;
                                break;
                            }
                        }
                    }
                    if($vid > 0)  break;
                }     
            }  			
            if($task == 'edit_template'){
                $origin_folder = $template_folder;                 
            }else if($task == 'create_template' && $template_priority == 'primary'){
                $priority = get_post_meta($product_id, '_nbdesigner_admintemplate_primary', true);    
                if($priority){
                    $template_priority = 'extra';
                }
                $origin_folder = 'primary';
            }else {
                $origin_folder = '';
            }			
        ?>
        <script type="text/javascript">
            var NBDESIGNCONFIG = {
                lang_code   :   "<?php echo str_replace('-', '_', get_bloginfo('language')); ?>",
                lang_rtl    :   "<?php if(is_rtl()){ echo 'rtl'; } else {  echo 'ltr';  } ?>",
                is_mobile   :   "<?php echo wp_is_mobile(); ?>",
                ui_mode   :   "<?php echo $ui_mode; ?>",
                stage_dimension :   {'width' : 500, 'height' : 500},
                max_size_upload :   <?php echo nbdesigner_get_option('nbdesigner_maxsize_upload'); ?>,
                min_size_upload :   <?php echo nbdesigner_get_option('nbdesigner_minsize_upload'); ?>,
                default_text    :   "<?php echo nbdesigner_get_option('nbdesigner_default_text'); ?>",
                pattern_text    :   "<?php echo nbdesigner_get_option('nbdesigner_enable_textpattern'); ?>",
                curved_text :   "<?php echo nbdesigner_get_option('nbdesigner_enable_curvedtext'); ?>",
                nbdesigner_enable_upload_image  :   "<?php echo nbdesigner_get_option('nbdesigner_enable_upload_image'); ?>",
                nbdesigner_enable_image_webcam  :   "<?php echo nbdesigner_get_option('nbdesigner_enable_image_webcam'); ?>",
                nbdesigner_enable_facebook_photo    :   "<?php echo nbdesigner_get_option('nbdesigner_enable_facebook_photo'); ?>",
                nbdesigner_enable_image_url :   "<?php echo nbdesigner_get_option('nbdesigner_enable_image_url'); ?>",
                default_text_qrcode :   "<?php echo nbdesigner_get_option('nbdesigner_default_qrcode'); ?>",
                enable_text :    "<?php echo nbdesigner_get_option('nbdesigner_enable_text'); ?>",
                enable_clipart  :   "<?php echo nbdesigner_get_option('nbdesigner_enable_clipart'); ?>",
                enable_image    :   "<?php echo nbdesigner_get_option('nbdesigner_enable_image'); ?>",
                enable_qrcode   :   "<?php echo nbdesigner_get_option('nbdesigner_enable_qrcode'); ?>",
                enable_draw :    "<?php echo nbdesigner_get_option('nbdesigner_enable_draw'); ?>",
                enable_all_color    :   "<?php echo nbdesigner_get_option('nbdesigner_show_all_color'); ?>",
                _palette    :   "<?php echo nbdesigner_get_option('nbdesigner_hex_names'); ?>",
                nbdesigner_default_color    :   "<?php echo nbdesigner_get_option('nbdesigner_default_color'); ?>",
                font_url    :   "<?php echo NBDESIGNER_FONT_URL .'/'; ?>",
                is_designer :  <?php if(current_user_can('edit_nbd_template')) echo 1; else echo 0; ?>,
                origin_folder_template   :   "<?php echo $origin_folder;  ?>",
                order_id    :   "<?php echo $order_id; ?>",
                task    :   "<?php echo $task; ?>",
                template_priority   :   "<?php echo $template_priority; ?>",
                template_folder   :   "<?php echo $template_folder; ?>",
                save_status :   <?php if ($task == 'edit_template') echo 1; else echo 0; ?>,
                first_time_edit_temp    :   0,
                product_id  :   "<?php echo $product_id; ?>",
                variation_id  :   "<?php echo $product_id; ?>",
                assets_url  :   "<?php echo NBDESIGNER_PLUGIN_URL . 'assets/'; ?>",
                reference_product  :   "<?php echo $reference_product; ?>",
                order_item_folder  :   "<?php echo $order_item_folder; ?>",
                order_item_id    :   "<?php if(isset($_GET['oiid']) && $_GET['oiid'] != '') echo $_GET['oiid']; else echo ''; ?>",
                ajax_url    : "<?php echo admin_url('admin-ajax.php'); ?>",
                nonce   :   "<?php echo wp_create_nonce('save-design'); ?>",
                nonce_get   :   "<?php echo wp_create_nonce('nbdesigner-get-data'); ?>",
                product_data  :   <?php echo json_encode(nbd_get_product_info($user_id, $product_id, $vid, $task, $reference_product, $template_folder, $order_id, $order_item_folder)); ?>
            };                  
            var _colors = NBDESIGNCONFIG['_palette'].split(','),
            colorPalette = [], row = [];
            for(var i=0; i < _colors.length; ++i) {
                var color = _colors[i].split(':')[0];
                row.push(color);
                if(i % 10 == 9){
                    colorPalette.push(row);
                    row = [];
                }               
            }
            row.push(NBDESIGNCONFIG['nbdesigner_default_color']);
            colorPalette.push(row);           
            <?php 
                if($task == 'create_template'): 
                $folder =  ($template_priority == 'primary') ? 'primary' : time().$user_id;
            ?>
                NBDESIGNCONFIG['template_folder'] = "<?php echo $folder; ?>";
            <?php endif; ?>                             
            <?php 
                $settings = nbdesigner_get_all_frontend_setting();
                foreach ($settings as $key => $val):
            ?>
            NBDESIGNCONFIG['<?php echo $key; ?>'] = <?php echo $val; ?>;
            <?php endforeach; ?>
            <?php if($ui_mode == 1): ?>
                nbd_window = window.parent;
            <?php else: ?>      
                nbd_window = window;
            <?php endif; ?>     
        </script>
    </head>
    <body ng-controller="DesignerController">
        <div class="od_loading"></div>
        <div class="container-fluid" id="designer-controller">
            <?php
            include_once('components/menu.php');
            include_once('components/design_area.php');
            include_once('components/info.php');
            ?>
        </div>
        <div id="od_modal">
            <?php
            include_once('components/modal_clipart.php');
            include_once('components/modal_upload.php');
            include_once('components/modal_qrcode.php');
            include_once('components/modal_preview.php');
            include_once('components/modal_pattern.php');
            include_once('components/modal_fonts.php');
            include_once('components/modal_crop_image.php');
            include_once('components/modal_config_art.php');
            include_once('components/modal_share.php');		
            include_once('components/modal_expand_feature.php');		
            ?>
        </div>
        <div id="od_config" ng-class="modeMobile ? 'mobile' : 'modepc'">	
            <?php
            include_once('components/config_text.php');
            include_once('components/config_clipart.php');
            include_once('components/config_image.php');
            include_once('components/config_draw.php');
            ?>
            <span class="hide-config fa fa-chevron-down e-shadow e-hover-shadow item-config" ng-show="modeMobile"></span>
            <span class="hide-tool-config fa fa-chevron-down e-shadow e-hover-shadow item-config" ng-hide="modeMobile" ng-style="{'display' : (pop.text == 'block' || pop.art == 'block' || pop.qrcode == 'block' || pop.clipArt == 'block' || pop.draw == 'block') ? 'block' : 'none'}"></span>
        </div>
        <?php
        include_once('components/popover_layer.php');
        include_once('components/tool_top.php');
        include_once('components/helpdesk.php');
        ?>
        <div class="od_processing">
            <div id="nbdesigner_preloader">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>	
            <p id="first_message">{{(langs['NBDESIGNER_PROCESSING']) ? langs['NBDESIGNER_PROCESSING'] : "NBDESIGNER PROCESSING"}}...</p>
            <p ng-show="partialSave"><span id="saved_sides">0</span> / <span id="total_sides">1</span></p>
        </div>
        <script type='text/javascript' src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script type='text/javascript' src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/js/touch.js'; ?>"></script>
        <script type='text/javascript' src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script type='text/javascript' src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.0-rc.2/angular.min.js"></script>
        <script type='text/javascript' src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/2.4.1/lodash.js"></script>
        <script type="text/javascript" src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/js/bundle.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/js/fabric.curvedText.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/js/fabric.removeColor.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/js/_layout.js'; ?>"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/spectrum/1.3.0/js/spectrum.min.js"></script>    
        <script type="text/javascript" src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/js/designer.min.js'; ?>"></script>		
    </body>
</html>
<?php endif; ?>