<?php
if (!function_exists('add_action')) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

class Nbdesigner_Plugin {
    public $textdomain;
    public $plugin_id;
    public $plugin_path_data;
    public $activedomain;
    public $removedomain;
    public function __construct() {
        $this->plugin_id = 'nbdesigner';
        $this->plugin_path_data = NBDESIGNER_DATA_DIR . '/';
        $this->activedomain = 'activedomain/netbase/';
        $this->removedomain = 'removedomain/netbase/';   
    }
    public function init(){
        $this->nbdesigner_hook();
        $this->nbdesigner_schehule();
        $this->nbdesigner_lincense_notices();    
        add_action( 'nbdesigner_lincense_event', array($this, 'nbdesigner_lincense_event_action') ); 
        if (in_array('woocommerce/woocommerce.php',get_option('active_plugins')) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' )) {
            $this->nbdesigner_woocommerce_hook();
        }
        if (is_admin()) {
            $this->nbdesigner_admin_hook();           
            $this->nbdesigner_ajax();           
            $this->nbdesigner_admin_enqueue_scripts();  
            
        } else {    
            $this->nbdesigner_frontend_hook();    
            $this->nbdesigner_frontend_enqueue_scripts();
        }          
    }
    public function nbdesigner_ajax(){
        // Nbdesigner_EVENT => nopriv
        $ajax_events = array(
            'nbdesigner_add_font_cat' => false,
            'nbdesigner_add_art_cat' => false,
            'nbdesigner_add_google_font' => false,
            'nbdesigner_delete_font_cat' => false,
            'nbdesigner_delete_art_cat' => false,
            'nbdesigner_delete_font' => false,
            'nbdesigner_delete_art' => false,
            'nbdesigner_get_product_info' => true,
            'nbdesigner_save_customer_design' => true,
            'nbdesigner_get_qrcode' => true,
            'nbdesigner_get_facebook_photo' => true,
            'nbdesigner_get_art' => true,
            'nbdesigner_design_approve' => false,
            'nbdesigner_design_order_email' => false,
            'nbdesigner_customer_upload' => true,
            'nbdesigner_get_font' => true,
            'nbdesigner_get_pattern' => true,
            'nbdesigner_get_info_license' => false,
            'nbdesigner_remove_license' => false,
            'nbdesigner_get_license_key' => false,
            'nbdesigner_get_security_key' => false,
            'nbdesigner_get_language' => true,
            'nbdesigner_save_language' => false,
            'nbdesigner_create_language' => false,
            'nbdesigner_make_primary_design' => false,
            'nbdesigner_load_admin_design' => true,
            'nbdesigner_save_webcam_image' => true,
            'nbdesigner_migrate_domain' => false,
            'nbdesigner_restore_data_migrate_domain' => false,
            'nbdesigner_theme_check' => false,
            'nbdesigner_custom_css' => false,
            'nbdesigner_copy_image_from_url' => true,
            'nbdesigner_get_suggest_design' => true,
            'nbdesigner_save_design_to_pdf' => false,
            'nbdesigner_save_partial_customer_design' => true,
            'nbdesigner_save_customer_design2' => true,
            'nbdesigner_delete_language' => false,
            'nbdesigner_update_all_product' => false,
            'nbd_save_customer_design' => true
        );
	foreach ($ajax_events as $ajax_event => $nopriv) {
            add_action('wp_ajax_' . $ajax_event, array($this, $ajax_event));
            if ($nopriv) {
                // NBDesigner AJAX can be used for frontend ajax requests
                add_action('wp_ajax_nopriv_' . $ajax_event, array($this, $ajax_event));
            }
        }
    }
    public function nbdesigner_hook(){
        add_action('plugins_loaded', array($this, 'translation_load_textdomain'));
        add_action('init', array($this, 'nbdesigner_start_session'), 1);
        add_action('wp_logout', array($this, 'nbdesigner_end_session'));
        add_action('wp_login', array($this, 'nbdesigner_change_folder_design'), 10, 2);
        add_action( 'user_register', array($this,'nbdesigner_registration_save'), 10, 1 ); 
        add_filter( 'cron_schedules', array($this, 'nbdesigner_set_schedule'));      
        add_filter( 'query_vars', array($this, 'nbdesigner_add_query_vars_filter') );    
        add_shortcode( 'nbdesigner_redesign', array($this, 'nbdesigner_redesign_func') );
        add_shortcode( 'nbdesigner_admindesign', array($this, 'nbdesigner_admindesign_func') );
        add_shortcode( 'nbdesigner_gallery', array($this,'nbdesigner_gallery_func') );
        add_shortcode( 'nbdesigner_button', array($this,'nbdesigner_button') );
        add_filter('the_content', array($this,'nbdesigner_add_shortcode_page_design'));
        add_action( 'template_redirect', array( $this, 'nbdesigner_editor_html' ) );    
        add_action('admin_head', array($this, 'nbdesigner_add_tinymce_editor'));
    }
    public function nbdesigner_admin_hook(){    
        add_action('plugins_loaded', array($this, 'nbdesigner_user_role'));
        add_action('admin_menu', array($this, 'nbdesigner_menu'));
        add_action('add_meta_boxes', array($this, 'add_design_box'), 30);
        add_action('save_post', array($this, 'save_design'));
        add_filter('upload_mimes', array($this, 'upload_mimes'));
        add_filter('manage_product_posts_columns', array($this, 'nbdesigner_add_design_column'));
        add_action('manage_product_posts_custom_column', array($this, 'nbdesigner_display_posts_design'), 1, 2);
        add_filter('nbdesigner_notices', array($this, 'nbdesigner_notices'));     
        add_filter( 'set-screen-option', array($this, 'set_screen' ), 10, 3 );
        add_filter( 'parse_query', array($this, 'nbdesigner_admin_posts_filter') );
        add_filter( 'views_edit-product', array( $this, 'nbdesigner_product_sorting_nbd' ),30 );
    }
    public function nbdesigner_frontend_hook(){
       //TODO
    }
    public function nbdesigner_woocommerce_hook(){
        /* ADMIN */
        add_filter( 'woocommerce_admin_order_actions', array($this, 'add_nbdesinger_order_actions_button'), 30, 2 );
        
        /* FRONTEND */
        add_filter('woocommerce_cart_item_name', array($this, 'nbdesigner_render_cart'), 1, 3);
        add_action('woocommerce_add_to_cart', array($this, 'nbdesigner_add_custom_data_design'), 1, 2);
        if( ! is_woo_v3() ){		
            add_action('woocommerce_add_order_item_meta', array($this, 'nbdesigner_add_order_design_data'), 1, 3);
        }else{
            add_action('woocommerce_new_order_item', array($this, 'nbdesigner_add_new_order_item'), 1, 3);
        } 
        add_action('woocommerce_checkout_order_processed', array($this, 'nbdesigner_change_foler_after_order'), 1, 1);
        add_filter('woocommerce_locate_template', array($this, 'nbdesigner_locate_plugin_template'), 20, 3 );
        add_filter( 'woocommerce_order_item_name', array($this, 'nbdesigner_order_item_name'), 1, 2);
        add_filter( 'woocommerce_order_item_quantity_html', array($this, 'nbdesigner_order_item_quantity_html'), 1, 2);
        add_filter( 'woocommerce_hidden_order_itemmeta', array($this, 'nbdesigner_hidden_custom_order_item_metada'));
        add_action( 'nbdesigner_admin_notifications_event', array($this, 'nbdesigner_admin_notifications_event_action') );
        add_action( 'woocommerce_cart_item_removed', array($this, 'nbdesigner_remove_cart_item_design'), 10, 2 );
        add_action( 'woocommerce_product_after_variable_attributes', array($this,'nbdesigner_variation_settings_fields'), 10, 3 );
        add_action( 'woocommerce_save_product_variation', array($this,'nbdesigner_save_variation_settings_fields'), 10, 2 );
        add_filter( 'woocommerce_add_cart_item_data', array($this, 'nbdesigner_force_individual_cart_items'), 10, 2 );      
        $position = nbdesigner_get_option('nbdesigner_position_button_product_detail');
        if($position == 1){
            add_action('woocommerce_before_add_to_cart_button', array($this, 'nbdesigner_button'), 30);
        }else if($position == 2){
            add_action('woocommerce_before_add_to_cart_form', array($this, 'nbdesigner_button'), 30);
        }else if($position == 3){
            add_action('woocommerce_after_add_to_cart_form', array($this, 'nbdesigner_button'), 30);
        } 
        $catalog_button_pos = nbdesigner_get_option('nbdesigner_position_button_in_catalog');
        if($catalog_button_pos == 2){
           add_action( 'woocommerce_after_shop_loop_item', array(&$this, 'add_catalog_nbdesign_button'), 20 );
        }elseif($catalog_button_pos == 1) {
            add_filter( 'woocommerce_loop_add_to_cart_link', array(&$this, 'nbdesigner_add_to_cart_shop_link'), 10, 2 );
        }
    }    
    public function nbdesigner_add_to_cart_shop_link($handler, $product){
        if(is_nbdesigner_product($product->id)){    
            $label = __('Start Design', 'nbdesigner');
            return sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="button product_type_%s">%s</a>',
                esc_url( get_permalink($product->id) ),
                esc_attr( $product->id ),
                esc_attr( $product->get_sku() ),
                esc_attr( $product->product_type ),
                esc_html( $label )
            );              
        }
        return $handler;
    }
    public function add_catalog_nbdesign_button(){
        global $product;
        if(is_nbdesigner_product($product->id)){  
            $label = __('Start Design', 'nbdesigner');
            printf( '<a href="%s" rel="nofollow" class="button">%s</a>',
                esc_url( get_permalink($product->id) ),
                esc_html( $label )
            );            
        }
    }
    public function nbdesigner_admin_enqueue_scripts(){
        add_action('admin_enqueue_scripts', function($hook) {   
            wp_register_style('nbd-general', NBDESIGNER_CSS_URL . 'nbd-general.css', array('dashicons'), NBDESIGNER_VERSION);
            wp_enqueue_style(array('nbd-general'));     
            if (($hook == 'post.php') || ($hook == 'post-new.php') || ($hook == 'toplevel_page_nbdesigner') ||
                    ($hook == 'nbdesigner_page_nbdesigner_manager_product' ) || ($hook == 'toplevel_page_nbdesigner_shoper') || ($hook == 'nbdesigner_page_nbdesigner_frontend_translate') ||
                    ($hook == 'nbdesigner_page_nbdesigner_manager_fonts') || ($hook == 'nbdesigner_page_nbdesigner_manager_arts') 
                     || ($hook == 'nbdesigner_page_nbdesigner_tools')) {
                wp_register_style('admin_nbdesigner', NBDESIGNER_CSS_URL . 'admin-nbdesigner.css', array('wp-color-picker'), NBDESIGNER_VERSION);
                wp_register_script('admin_nbdesigner', NBDESIGNER_JS_URL . 'admin-nbdesigner.js', array('jquery', 'jquery-ui-resizable', 'jquery-ui-draggable', 'jquery-ui-autocomplete', 'wp-color-picker'), NBDESIGNER_VERSION);
                wp_localize_script('admin_nbdesigner', 'admin_nbds', array(
                    'url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('nbdesigner_add_cat'),
                    'mes_success' => 'Success!',
                    'url_check' => NBDESIGNER_AUTHOR_SITE,
                    'sku' => NBDESIGNER_SKU,       
                    'url_gif' => NBDESIGNER_PLUGIN_URL . 'assets/images/loading.gif',
                    'assets_images'  =>  NBDESIGNER_PLUGIN_URL . 'assets/images/',
                    'nbds_lang' => Nbdesigner_Plugin::nbdesigner_get_javascript_multilanguage() ));                
                wp_enqueue_style(array('wp-pointer', 'wp-jquery-ui-dialog', 'admin_nbdesigner'));
                wp_enqueue_script(array('wp-pointer', 'wpdialogs', 'admin_nbdesigner'));                            
            }
            if($hook == 'admin_page_nbdesigner_detail_order'){
                wp_enqueue_media();
                wp_register_style(
                        'admin_nbdesigner_detail_order_slider',
                        NBDESIGNER_CSS_URL . 'owl.carousel.css'
                        );
                wp_register_style(
                        'admin_nbdesigner_detail_order', 
                        NBDESIGNER_CSS_URL . 'detail_order.css', 
                        array('jquery-ui-style-css'), NBDESIGNER_VERSION);
                wp_register_style(
                        'jquery-ui-style-css', 
                        '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.min.css', 
                        array(), '1.11.4');                
                wp_enqueue_style(array('admin_nbdesigner_detail_order_slider', 'admin_nbdesigner_detail_order','jquery-ui-style-css'));
                wp_register_script(
                        'admin_nbdesigner_detail_order_slider',
                        NBDESIGNER_JS_URL . 'owl.carousel.min.js', 
                        array('jquery', 'jquery-ui-tabs', 'jquery-ui-resizable', 'jquery-ui-draggable'), 
                        NBDESIGNER_VERSION);
                wp_enqueue_script('admin_nbdesigner_detail_order_slider');              
            }
            if($hook == 'nbdesigner_page_nbdesigner_frontend_translate'){
                wp_register_script('admin_nbdesigner_jeditable', NBDESIGNER_JS_URL . 'jquery.jeditable.js', array('jquery'));
                wp_enqueue_script('admin_nbdesigner_jeditable');
            }
            if($hook == 'nbdesigner_page_nbdesigner_tools'){
                wp_enqueue_style('admin_nbdesigner_codemirror', NBDESIGNER_PLUGIN_URL . 'assets/codemirror/codemirror.css');
                wp_enqueue_script( 'nbdesigner_codemirror_js', NBDESIGNER_PLUGIN_URL . 'assets/codemirror/codemirror.js' , array());
                wp_enqueue_script( 'nbdesigner_codemirror_css_js', NBDESIGNER_PLUGIN_URL . 'assets/codemirror/css.js' , array());
            }
            if($hook == 'nbdesigner_page_nbdesigner_admin_template' || $hook == 'nbdesigner_page_nbdesigner_manager_arts'
                || $hook == 'admin_page_nbdesigner_detail_order' || $hook == 'nbdesigner_page_nbdesigner_manager_fonts'   
                || $hook == 'nbdesigner_page_nbdesigner_tools' || $hook == 'nbdesigner_page_nbdesigner_frontend_translate'   ){
                wp_enqueue_style('nbdesigner_sweetalert_css', NBDESIGNER_CSS_URL . 'sweetalert.css');
                wp_enqueue_script( 'nbdesigner_sweetalert_js', NBDESIGNER_JS_URL . 'sweetalert.min.js' , array('jquery'));
            }
            if($hook == 'toplevel_page_nbdesigner'){
                wp_enqueue_style('nbdesigner_settings_css', NBDESIGNER_CSS_URL . 'admin-settings.css', array(), NBDESIGNER_VERSION);
            }
            if(is_rtl()){
                wp_enqueue_style('nbd-rtl',NBDESIGNER_CSS_URL . 'nbd-rtl.css',array(), NBDESIGNER_VERSION);   
            }      
        });
    }
    public function nbdesigner_frontend_enqueue_scripts(){
        add_action('wp_enqueue_scripts', function() {
            wp_register_style('nbdesigner', NBDESIGNER_CSS_URL . 'nbdesigner.css', array(), NBDESIGNER_VERSION);
            wp_enqueue_style('nbdesigner');              
            wp_register_script('nbdesigner', NBDESIGNER_JS_URL . 'nbdesigner.js', array('jquery'), NBDESIGNER_VERSION);
            wp_localize_script('nbdesigner', 'nbds_frontend', array(
                'url' => admin_url('admin-ajax.php'),
                'sid' => session_id(),
                'nonce' => wp_create_nonce('save-design'),
                'nonce_get' => wp_create_nonce('nbdesigner-get-data')));
            wp_enqueue_script('nbdesigner');
        });
    }
    public static function plugin_activation() {
        if (version_compare($GLOBALS['wp_version'], NBDESIGNER_MINIMUM_WP_VERSION, '<')) {
            $message = sprintf(__('<p>Plugin <strong>not compatible</strong> with WordPress %s. Requires WordPress %s to use this Plugin.</p>', 'nbdesigner'), $GLOBALS['wp_version'], NBDESIGNER_MINIMUM_WP_VERSION);
            die($message);
        }
        if(version_compare(PHP_VERSION, '5.4.0', '<=')){
            $message = sprintf(__('<p>Plugin <strong>not compatible</strong> with PHP %s. Requires PHP %s to use this Plugin.</p>', 'nbdesigner'), PHP_VERSION, NBDESIGNER_MINIMUM_PHP_VERSION);
            die($message);            
        }
        if (!is_plugin_active('woocommerce/woocommerce.php')) {
            $message = '<div class="error"><p>' . sprintf(__('WooCommerce is not active. Please activate WooCommerce before using %s.', 'nbdesigner'), '<b>Nbdesigner</b>') . '</p></div>';
            die($message);
        }
        if (!file_exists(NBDESIGNER_TEMP_DIR)) {
            wp_mkdir_p(NBDESIGNER_TEMP_DIR);
        } 
        if (!file_exists(NBDESIGNER_DOWNLOAD_DIR)) {
            wp_mkdir_p(NBDESIGNER_DOWNLOAD_DIR);
        }  
        if (!file_exists(NBDESIGNER_ADMINDESIGN_DIR)) {
            wp_mkdir_p(NBDESIGNER_ADMINDESIGN_DIR);
        }   
        if (!file_exists(NBDESIGNER_FONT_DIR)) {
            wp_mkdir_p(NBDESIGNER_FONT_DIR);
        }     
        if (!file_exists(NBDESIGNER_PDF_DIR)) {
            wp_mkdir_p(NBDESIGNER_PDF_DIR);
        }        
        $path_lang = NBDESIGNER_DATA_CONFIG_DIR . '/language';
        if (!file_exists($path_lang)) {
            wp_mkdir_p($path_lang);
        } 
        if (!file_exists(NBDESIGNER_SUGGEST_DESIGN_DIR)) {
            wp_mkdir_p(NBDESIGNER_SUGGEST_DESIGN_DIR);
        }  
        $check_version_150 = false;
        $version = get_option("nbdesigner_version_plugin");
        if (version_compare($version, "1.5.0", '<')) {    
            $check_version_150 = true;
        }         
        self::nbdesigner_add_custom_page();
        self::nbdesigner_create_table_templates();
        $version = get_option("nbdesigner_version_plugin");
        if ($check_version_150) {    
            self::nbdesigner_update_data_150();
        }               
    }
    public function nbdesigner_set_schedule($schedules){   
        if(!isset($schedules['hourly'])){
            $schedules['hourly'] = array('interval' => 60*60, 'display' => __('Once Hourly'));
            //$schedules['every5min'] = array('interval' => 60*5, 'display' => __('Every 5 Minutes', 'nbdesigner'));
        }
        return $schedules;
    }
    public function nbdesigner_schehule(){
        $timestamp = wp_next_scheduled( 'nbdesigner_lincense_event' );
        if( $timestamp == false ){
            wp_schedule_event( time(), 'daily', 'nbdesigner_lincense_event' );
        }   
        $timestamp2 = wp_next_scheduled( 'nbdesigner_admin_notifications_event' );
        $notifications = get_option('nbdesigner_notifications', false);
        $recurrence = 'hourly';	        
        if( $timestamp2 == false && $notifications === false){
            wp_schedule_event( time(), $recurrence, 'nbdesigner_admin_notifications_event' );
        }         
    }
    public function nbdesigner_lincense_event_action(){
        $path = NBDESIGNER_PLUGIN_DIR . 'data/license.json';   
        $path_data = NBDESIGNER_DATA_CONFIG_DIR . '/license.json';
        if(file_exists($path) || file_exists($path_data)){
            $license = $this->nbdesigner_check_license();
            $now = strtotime("now");
            if(isset($license['type']) && $license['type'] != 'free' && isset($license['expiry']) && $license['expiry'] < $now ){
                $result = $this->nbdesiger_request_license($license['key'], $this->activedomain);
                if($result){
                    $data = (array) json_decode($result);               
                    $data['key'] = $license['key'];
                    if($data['type'] == 'free') $data['number_domain'] = "5";
                    $data['salt'] = md5($license['key'].$data['type']);                   
                    $this->nbdesigner_write_license(json_encode($data));  
                }   
            }
        }
        add_action( 'admin_notices', array( $this, 'nbdesigner_lincense_notices' ) );	
    }
    public function nbdesigner_admin_notifications_event_action(){       
        $notifications = get_option('nbdesigner_notifications', false);
        $owner_email = get_option('nbdesigner_notifications_emails', false);
        if($notifications != false){
            global $woocommerce;         
            if($notifications == 'yes'){                
                if( version_compare( $woocommerce->version, "2.2", ">=" ) ){
                    $post_status = array( 'wc-processing', 'wc-completed', 'wc-on-hold', 'wc-pending' );
                }else{
                    $post_status = 'publish';
                }	               
                $args = array(
                    'post_type' => 'shop_order',
                    'meta_key' => '_nbdesigner_order_changed',
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'posts_per_page'=>-1,
                    'post_status' => $post_status,
                    'meta_query' => array(
                        array(
                            'key' => '_nbdesigner_order_changed',
                            'value' => 1,
                        )
                    )
        	); 
                $post_orders = get_posts($args);   
                $orders = array();
                foreach ($post_orders AS $order) {
                    $the_order = new WC_Order($order->ID);
                    $orders[$order->ID] = $the_order->get_order_number();                    
                }
                if (count($orders)) {
                    foreach ($orders AS $order => $order_number) {
                        update_post_meta($order, '_nbdesigner_order_changed', 0);
                    }                    
                    $subject = __('New / Modified order(s)', 'nbdesigner');
                    $mailer = $woocommerce->mailer();
                    ob_start();
                    wc_get_template('emails/nbdesigner-admin-notifications.php', array(
                        'plugin_id' => 'nbdesigner',
                        'orders' => $orders,
                        'heading' => $subject
                    )); 
                    $emails = new WC_Emails();
                    $woo_recipient = $emails->emails['WC_Email_New_Order']->recipient;
                    if($owner_email == ''){
                        if(!empty($woo_recipient)) {
                            $user_email = esc_attr($woo_recipient);
                        } else {
                            $user_email = get_option( 'admin_email' );
                        }                        
                    }else{
                        $user_email = $owner_email;
                    }
                    $body = ob_get_clean();                  
                    if (!empty($user_email)) {                                            
                        $mailer->send($user_email, $subject, $body);
                    }                    
                }
            }
        }
    }
    public function nbdesigner_add_query_vars_filter($vars){
        $vars[] = "nbds-adid";
        $vars[] = "nbds-ref";
        return $vars;   
    }
    public function nbdesigner_admin_posts_filter($query){
        global $typenow;
        if ( 'product' == $typenow ) {
            if ( !empty($_GET['has_nbd']) ) {
                $query->query_vars['meta_key'] = '_nbdesigner_enable';
                $query->query_vars['meta_value']    = esc_sql($_GET['has_nbd']);
            }    
        }    
    }
    public function nbdesigner_product_sorting_nbd($views){
        global $wp_query;
        $class            = '';
        $query_string     = remove_query_arg(array( 'orderby', 'order' ));
        $query_string     = add_query_arg( 'has_nbd', urlencode('1'), $query_string );        
	$views['has_nbd'] = '<a href="' . esc_url( $query_string ) . '" class="' . esc_attr( $class ) . '">' . __( 'Has NBDesigner', 'nbdesigner' ) . '</a>';        
        return $views;
    }
    public function nbdesigner_lincense_notices(){            
        $license = $this->nbdesigner_check_license();
        if($license['status'] == 0){
            add_action( 'admin_notices', array( $this, 'nbdesigner_lincense_notices_content' ) );     
        } 
    }
    public function nbdesigner_lincense_notices_content(){     
        $mes = $this->nbdesigner_custom_notices('notices', 'You\'re using NBDesigner free version (full features and function but for max 5 products) or expired pro version. <a href="http://cmsmart.net/wordpress-plugins/woocommerce-online-product-designer-plugin" target="_blank">Please buy the Premium version here to use for all product </a>');
        printf($mes);
    }
    public function translation_load_textdomain() {	 
        load_plugin_textdomain('nbdesigner', false, dirname(dirname( plugin_basename( __FILE__ ))) . '/langs/');
    }
    public static function plugin_deactivation() {
        wp_clear_scheduled_hook( 'nbdesigner_lincense_event' );
        wp_clear_scheduled_hook( 'nbdesigner_admin_notifications_event' );
    }
    public function upload_mimes($mimes) {
        $mimes['svg'] = 'image/svg+xml';
        $mimes['svgz'] = 'image/svg+xml';
        $mimes['woff'] = 'application/x-font-woff';
        $mimes['ttf'] = 'application/x-font-ttf';
        $mimes['eps'] = 'application/postscript';
        return $mimes;
    }
    public function nbdesigner_settings(){
        $page_id = 'nbdesigner';
        $tabs = apply_filters('nbdesigner_settings_tabs', array(
            'general' => '<span class="dashicons dashicons-admin-generic"></span> ' . __('General', 'nbdesigner'),
            'frontend' => '<span class="dashicons dashicons-admin-customizer"></span> '. __('Design Tool', 'nbdesigner'),            
            'color' => '<span class="dashicons dashicons-art"></span> '. __('Colors', 'nbdesigner')            
        ));
        require_once(NBDESIGNER_PLUGIN_DIR . 'includes/settings/general.php');
        require_once(NBDESIGNER_PLUGIN_DIR . 'includes/settings/frontend.php');
        require_once(NBDESIGNER_PLUGIN_DIR . 'includes/settings/colors.php');
        $Nbdesigner_Settings = new Nbdesigner_Settings(array(
            'page_id' => $page_id,
            'tabs' => $tabs    
        ));   
        $blocks = apply_filters('nbdesigner_settings_blocks', array(
            'general' => array(
                'general-settings' => __('General Settings', 'nbdesigner'),
                'admin-notifications' => __('Notifications', 'nbdesigner'),
                'application' => __('Application', 'nbdesigner')
            ),
            'frontend' => array(
                'tool-text' =>  __('Text Options', 'nbdesigner'),
                'tool-clipart' =>  __('Clipart Options', 'nbdesigner'),
                'tool-image' =>  __('Image Options', 'nbdesigner'),
                'tool-draw' =>  __('Free draw Options', 'nbdesigner'),
                'tool-qrcode' =>  __('Qr Code Options', 'nbdesigner')
            ),
            'color' => array(
                'color-setting' =>  __('Setting color', 'nbdesigner')
            )
        ));  
        $Nbdesigner_Settings->add_blocks($blocks);
        $Nbdesigner_Settings->add_blocks_description(array());
        $frontend_options = Nbdesigner_Settings_Frontend::get_options();
        $general_options = Nbdesigner_Settings_General::get_options();
        $color_options = Nbdesigner_Settings_Colors::get_options();
        $options = apply_filters('nbdesigner_settings_options', array(
            'general-settings' => $general_options['general-settings'],
            'admin-notifications' => $general_options['admin-notifications'],
            'application' => $general_options['application'],
            'tool-text' => $frontend_options['tool-text'],
            'tool-clipart' => $frontend_options['tool-clipart'],
            'tool-image' => $frontend_options['tool-image'],
            'tool-draw' => $frontend_options['tool-draw'],
            'tool-qrcode' => $frontend_options['tool-qrcode'],
            'color-setting' => $color_options['color-setting'],
        ));    
        foreach($options as $key => $option){
            $Nbdesigner_Settings->add_block_options( $key, $option);  
        }    
        do_action( 'nbdesigner_before_options_save', $page_id );
        if ( isset($_POST['nbdesigner_save_options_'.$page_id]) ) {
            check_admin_referer( $page_id.'_nonce' );             
            $Nbdesigner_Settings->save_options();
        }
        else if( isset($_POST['nbdesigner_reset_options_'.$page_id]) ) {
            check_admin_referer( $page_id.'_nonce' );
            $Nbdesigner_Settings->reset_options();
        } 
        add_action('nbdesigner_settings_header_start', array(&$this, 'display_license_key'));
        $Nbdesigner_Settings->output();    
        add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 1 );
    }
    public function admin_footer_text($footer_text){
	$footer_text = sprintf( __( 'If you like <strong>NBDesigner</strong> please leave us a %s&#9733;&#9733;&#9733;&#9733;&#9733;%s rating. A huge thanks in advance!', 'nbdesigner' ), '<a href="https://wordpress.org/support/view/plugin-reviews/web-to-print-online-designer?filter=5#new-post" target="_blank" class="nbd-rating-link" data-rated="' . esc_attr__( 'Thanks :)', 'nbdesigner' ) . '">', '</a>' );
        return $footer_text;
    }
    public function display_license_key(){
        $license = $this->nbdesigner_check_license();
        $site_title = get_bloginfo( 'name' );
        $site_url = base64_encode(rtrim(get_bloginfo('wpurl'), '/'));   
        require_once(NBDESIGNER_PLUGIN_DIR . 'views/nbdesigner-settings.php');
    }
    public function nbdesigner_menu() {       
        if (current_user_can('manage_nbd_setting')) {
            add_menu_page('Nbdesigner', 'NBDesigner', 'manage_nbd_setting', 'nbdesigner', array($this, 'nbdesigner_settings'), NBDESIGNER_PLUGIN_URL . 'assets/images/logo.png', 26);
            $nbdesigner_manage = add_submenu_page(
                    'nbdesigner', 'NBDesigner Settings', 'Settings', 'manage_nbd_setting', 'nbdesigner', array($this, 'nbdesigner_settings')
            );
            add_action('load-'.$nbdesigner_manage, array('Nbdesigner_Helper', 'settings_helper'));
        }
        if(current_user_can('manage_nbd_product')){
            $product_hook = add_submenu_page(
                    'nbdesigner', 'Manager Products', 'Products', 'manage_nbd_product', 'nbdesigner_manager_product', array($this, 'nbdesigner_manager_product')
            );
            add_action( "load-$product_hook", array( $this, 'nbdesigner_template_screen_option' ));
            add_submenu_page(
                    '', 'Detail Design Order', 'Detail Design Order', 'manage_nbd_product', 'nbdesigner_detail_order', array($this, 'nbdesigner_detail_order')
            );
        }
        if(current_user_can('manage_nbd_art')){    
            add_submenu_page(
                    'nbdesigner', 'Manager Cliparts', 'Cliparts', 'manage_nbd_art', 'nbdesigner_manager_arts', array($this, 'nbdesigner_manager_arts')
            );
        }
        if(current_user_can('manage_nbd_font')){    
            add_submenu_page(
                    'nbdesigner', 'Manager Fonts', 'Fonts', 'manage_nbd_font', 'nbdesigner_manager_fonts', array($this, 'nbdesigner_manager_fonts')
            );
        }
        if(current_user_can('manage_nbd_language')){  
            add_submenu_page(
                    'nbdesigner', 'Frontend Translate', 'Frontend Translate', 'manage_nbd_language', 'nbdesigner_frontend_translate', array($this, 'nbdesigner_frontend_translate')
            );             
        }
        if (current_user_can('manage_nbd_tool')) {    
            add_submenu_page(
                    'nbdesigner', 'NBDesigner Tools', 'Tools', 'manage_nbd_tool', 'nbdesigner_tools', array($this, 'nbdesigner_tools')
            );             
        }
    }
    public function nbdesigner_template_screen_option() {
        if(isset($_GET['view']) && $_GET['view'] == 'templates'){
            $option = 'per_page';
            $args   = array(
                'label'   => 'Templates',
                'default' => 10,
                'option'  => 'templates_per_page'
            );
            add_screen_option( $option, $args );            
        }
    }    
    public static function set_screen( $status, $option, $value ) {
        return $value;
    }
    public function nbdesigner_get_license_key(){       
        if (!wp_verify_nonce($_POST['nbdesigner_getkey_hidden'], 'nbdesigner-get-key') || !current_user_can('administrator')) {
            die('Security error');
        }        
        if(isset($_POST['nbdesigner'])){
            $data =$_POST['nbdesigner'];
            $email = base64_encode($data['email']);
            $domain = $data['domain'];
            $title = ($data['name'] != '') ? urlencode($data['name']) : urlencode($data['title']);
            $ip = base64_encode($this->nbdesigner_get_ip());
            $url = NBDESIGNER_AUTHOR_SITE.'subcrible/WPP1074/'.$email.'/'.$domain.'/'.$title .'/'.$ip;	 	            
            $result = nbd_file_get_contents($url);
            if(isset($result)) {
                echo $result;
            }else{
                echo 'Please try later!';
            }
            wp_die();
        }
    }
    public function nbdesigner_get_ip(){
        $ip = $_SERVER['REMOTE_ADDR'];
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $ip;        
    }
    public static function nbdesigner_add_action_links($links){	       
        $mylinks = array(
            'setting' => '<a href="' . admin_url('options-general.php?page=nbdesigner') . '">Settings</a>'
        );
        return array_merge($mylinks, $links);
    }
    public static function nbdesigner_plugin_row_meta( $links, $file ) {      
        if($file == NBDESIGNER_PLUGIN_BASENAME){           
            $row_meta = array(
                'upgrade' => '<a href="https://cmsmart.net/support_ticket" target="_blank">Support</a>'
            );
            return array_merge( $links, $row_meta );
        }
        return (array) $links;
    }
    public function nbdesigner_manager_product() {
        if(isset($_GET['view']) && $_GET['view'] == "templates"){
            $pid = $_GET['pid'];
            $pro = wc_get_product($pid);            
            $templates_obj = new Product_Template_List_Table();  
            include_once(NBDESIGNER_PLUGIN_DIR . 'views/nbdesigner-admin-template.php');
        }else{
            $args_query = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'meta_key' => '_nbdesigner_enable',
                'orderby' => 'date',
                'order' => 'DESC',
                'posts_per_page'=> -1,
                'meta_query' => array(
                    array(
                        'key' => '_nbdesigner_enable',
                        'value' => 1,
                    )
                )
            );      
            $products = get_posts($args_query);
            $_pro = array();
            $number_pro = 0;
            $limit = 20;
            foreach ($products as $product) {
                $_product = wc_get_product($product->ID);
                $_pro[] = array(
                    'url'   => admin_url('post.php?post=' . absint($product->ID) . '&action=edit'),
                    'img'   => $_product->get_image(),
                    'name'  => $product->post_title,
                    'id'    => absint($product->ID)
                );
                $number_pro++;
            }
            $page = filter_input(INPUT_GET, "p", FILTER_VALIDATE_INT);
            if(isset($page)){
                $_tp = ceil($number_pro / $limit);
                if($page > $_tp) $page = $_tp;
                $pro = array_slice($_pro, ($page-1)*$limit, $limit);
            }else{
                $pro = $_pro;
                if($number_pro > $limit) $pro = array_slice($_pro, ($page-1)*$limit, $limit);	
            }
            $url = admin_url('admin.php?page=nbdesigner_manager_product');
            require_once NBDESIGNER_PLUGIN_DIR . 'includes/class.nbdesigner.pagination.php';
            $paging = new Nbdesigner_Pagination();
            $config = array(
                'current_page'  => isset($page) ? $page : 1, 
                'total_record'  => $number_pro,
                'limit'         => $limit,
                'link_full'     => $url.'&p={p}',
                'link_first'    => $url              
            );	        
            $paging->init($config);            
            include_once(NBDESIGNER_PLUGIN_DIR . 'views/nbdesigner-manager-product.php');
        }
    }
    public function nbdesigner_add_font_cat() {
        $data = array(
                'mes'   =>  __('You do not have permission to add/edit font category!', 'nbdesigner'),
                'flag'  => 0
            );	        
        if (!wp_verify_nonce($_POST['nonce'], 'nbdesigner_add_cat') || !current_user_can('edit_nbd_font')) {
            echo json_encode($data);
            wp_die();
        }
        $path = NBDESIGNER_DATA_DIR . '/font_cat.json';
        $list = $this->nbdesigner_read_json_setting($path);
        $cat = array(
            'name' => $_POST['name'],
            'id' => $_POST['id']
        );
        $this->nbdesigner_update_json_setting($path, $cat, $cat['id']);
        $data['mes'] = __('Category has been added/edited successfully!', 'nbdesigner');
        $data['flag'] = 1;        
        echo json_encode($data);
        wp_die();
    }
    public function nbdesigner_add_art_cat() {    
        $data = array(
                'mes'   =>  __('You do not have permission to add/edit clipart category!', 'nbdesigner'),
                'flag'  => 0
            );	        
        if (!wp_verify_nonce($_POST['nonce'], 'nbdesigner_add_cat') || !current_user_can('edit_nbd_art')) {
            echo json_encode($data);
            wp_die();
        }
        $path = NBDESIGNER_DATA_DIR . '/art_cat.json';
        $cat = array(
            'name' => sanitize_text_field($_POST['name']),
            'id' => $_POST['id']
        );
        $this->nbdesigner_update_json_setting($path, $cat, $cat['id']);
        $data['mes'] = __('Category has been added/edited successfully!', 'nbdesigner');
        $data['flag'] = 1;        
        echo json_encode($data);
        wp_die();
    }
    public function nbdesigner_delete_font_cat() {
        $data = array(
                'mes'   =>  __('You do not have permission to delete font category!', 'nbdesigner'),
                'flag'  => 0
            );        
        if (!wp_verify_nonce($_POST['nonce'], 'nbdesigner_add_cat') || !current_user_can('edit_nbd_font')) {
            echo json_encode($data);
            wp_die();
        }
        $path = NBDESIGNER_DATA_DIR . '/font_cat.json';
        $id = $_POST['id'];
        $this->nbdesigner_delete_json_setting($path, $id, true);
        $font_path = NBDESIGNER_DATA_DIR . '/fonts.json';
        $this->nbdesigner_update_json_setting_depend($font_path, $id);
        $data['mes'] = __('Category has been delete successfully!', 'nbdesigner');
        $data['flag'] = 1;        
        echo json_encode($data);
        wp_die();
    }
    public function nbdesigner_delete_art_cat() {
        $data = array(
                'mes'   =>  __('You do not have permission to delete clipart category!', 'nbdesigner'),
                'flag'  => 0
            );          
        if (!wp_verify_nonce($_POST['nonce'], 'nbdesigner_add_cat') || !current_user_can('delete_nbd_art')) {
            echo json_encode($data);
            wp_die();
        }
        $path = NBDESIGNER_DATA_DIR . '/art_cat.json';
        $id = $_POST['id'];
        $this->nbdesigner_delete_json_setting($path, $id, true);
        $art_path = NBDESIGNER_DATA_DIR . '/arts.json';
        $this->nbdesigner_update_json_setting_depend($art_path, $id);
        $data['mes'] = __('Category has been delete successfully!', 'nbdesigner');
        $data['flag'] = 1;        
        echo json_encode($data);
        wp_die();
    }
    public function nbdesigner_get_list_google_font() {
        $path = NBDESIGNER_PLUGIN_DIR . 'data/listgooglefonts.json';
        $data = (array) $this->nbdesigner_read_json_setting($path);
        return json_encode($data);
    }
    public function nbdesigner_add_google_font() {
        $data = array(
                'mes'   =>  __('You do not have permission to add font!', 'nbdesigner'),
                'flag'  => 0
            );        
        if (!wp_verify_nonce($_POST['nonce'], 'nbdesigner_add_cat') || !current_user_can('edit_nbd_font')) {
            die('Security error');
        }
        $name = $_POST['name'];
        $id = $_POST['id'];
        $path_font = NBDESIGNER_DATA_DIR . '/googlefonts.json';
        $data = array("name" => $name, "id" => $id);
        $this->nbdesigner_update_json_setting($path_font, $data, $id);
        $data['mes'] = __('The font has been added successfully!', 'nbdesigner');
        $data['flag'] = 1;        
        echo json_encode($data);
        wp_die();
    }
    public function nbdesigner_manager_fonts() {
        $notice = '';
        $font_id = 0;
        $cats = array("0");
        $current_font_cat_id = 0;
        $update = false;
        $list = $this->nbdesigner_read_json_setting($this->plugin_path_data . 'fonts.json');	
        $cat = $this->nbdesigner_read_json_setting($this->plugin_path_data . 'font_cat.json');
        $data_font_google = $this->nbdesigner_read_json_setting($this->plugin_path_data . 'googlefonts.json');
        $list_all_google_font = $this->nbdesigner_get_list_google_font();
        $current_cat = filter_input(INPUT_GET, "cat_id", FILTER_VALIDATE_INT);
        if (is_array($cat))
            $current_font_cat_id = sizeof($cat);
        if (isset($_GET['id'])) {
            $font_id = $_GET['id'];
            $update = true;
            if (isset($list[$font_id])) {
                $font_data = $list[$font_id];
                $cats = $font_data->cat;
            }
        }
        if (isset($_POST[$this->plugin_id . '_hidden']) && wp_verify_nonce($_POST[$this->plugin_id . '_hidden'], $this->plugin_id) && current_user_can('edit_nbd_font')) {
            $font = array();
            $font['name'] = esc_html($_POST['nbdesigner_font_name']);
            $font['alias'] = 'nbfont' . substr(md5(rand(0, 999999)), 0, 10);
            $font['id'] = $_POST['nbdesigner_font_id'];
            $font['cat'] = $cats;
            if (isset($_POST['nbdesigner_font_cat']))
                $font['cat'] = $_POST['nbdesigner_font_cat'];
            if (isset($_FILES['woff']) && ($_FILES['woff']['size'] > 0) && ($_POST['nbdesigner_font_name'] != '')) {
                $uploaded_file_name = basename($_FILES['woff']['name']);	               
                $allowed_file_types = array('woff', 'ttf');
                $font['type'] = $this->nbdesigner_get_extension($uploaded_file_name);              
                if (Nbdesigner_IO::checkFileType($uploaded_file_name, $allowed_file_types)) {
                    $upload_overrides = array('test_form' => false);
                    $uploaded_file = wp_handle_upload($_FILES['woff'], $upload_overrides);
                    if (isset($uploaded_file['url'])) {
                        $new_path_font = $this->plugin_path_data . 'fonts/' . $font['alias'] .'.'. $font['type'];
                        $font['file'] = $uploaded_file['file'];
                        $font['url'] = $uploaded_file['url'];
                        if (!copy($font['file'], $new_path_font)) {
                            $notice = apply_filters('nbdesigner_notices', $this->nbdesigner_custom_notices('error', __('Failed to copy.', 'nbdesigner')));
                        }else{
                            $font['file'] = $this->plugin_path_data . 'fonts/' . $font['alias'] .'.'. $font['type'];
                            $font['url'] = content_url().'/uploads/nbdesigner/fonts/' . $font['alias'] .'.'. $font['type'];
                        }
                        if ($update) {
                            $this->nbdesigner_update_list_fonts($font, $font_id);
                        } else {
                            $this->nbdesigner_update_list_fonts($font);
                        }
                        $notice = apply_filters('nbdesigner_notices', $this->nbdesigner_custom_notices('success', __('Your font has been saved.', 'nbdesigner')));
                    } else {
                        $notice = apply_filters('nbdesigner_notices', $this->nbdesigner_custom_notices('error', __('Error while upload font, please try again!', 'nbdesigner')));
                    }
                } else {
                    $notice = apply_filters('nbdesigner_notices', $this->nbdesigner_custom_notices('error', __('Incorrect file extensions.', 'nbdesigner')));
                }
            } else if ($update && ($_POST['nbdesigner_font_name'] != '')) {
                $font_data->name = $_POST['nbdesigner_font_name'];
                $font_data->cat = $font['cat'];
                $this->nbdesigner_update_list_fonts($font_data, $font_id);
                $notice = apply_filters('nbdesigner_notices', $this->nbdesigner_custom_notices('success', __('Your font has been saved.', 'nbdesigner')));
            } else {
                $notice = apply_filters('nbdesigner_notices', $this->nbdesigner_custom_notices('warning', __('Please choose font file or font name.', 'nbdesigner')));
            }
            $list = $this->nbdesigner_read_json_setting($this->plugin_path_data . 'fonts.json');
            $cats = $font['cat'];
        }        
        if(isset($current_cat)){
            $new_list = array();
            foreach($list as $art){    
                if(in_array((string)$current_cat, $art->cat)) $new_list[] = $art;
            }
            foreach($cat as $c){
                if($c->id == $current_cat){
                    $name_current_cat = $c->name;
                    break;
                } 
                $name_current_cat = 'uploaded';
            }
            $list = $new_list;             
        }else{
            $name_current_cat = 'uploaded';
        }
        $total = sizeof($list);
        include_once(NBDESIGNER_PLUGIN_DIR . 'views/nbdesigner-manager-fonts.php');
    }
    /**
     * Analytics and statistics customer used product design
     * @since 1.5
     * 
    */    
    public function nbdesigner_analytics() {
        //TODO something analytics
        include_once(NBDESIGNER_PLUGIN_DIR . 'views/nbdesigner-analytics.php');
    }    
    public function nbdesigner_update_list_fonts($font, $id = null) {
        if (isset($id)) {
            $this->nbdesigner_update_font($font, $id);
            return;
        }
        $list_font = array();
        $path = $this->plugin_path_data . 'fonts.json';
        $list = $this->nbdesigner_read_json_setting($path);
        if (is_array($list)) {
            $list_font = $list;
            $id = sizeOf($list_font);
            $font['id'] = (string) $id;
        }
        $list_font[] = $font;
        $res = json_encode($list_font);
        file_put_contents($path, $res);
    }
    public function nbdesigner_update_list_arts($art, $id = null) {
        $path = $this->plugin_path_data . 'arts.json';
        if (isset($id)) {
            $this->nbdesigner_update_json_setting($path, $art, $id);
            return;
        }
        $list_art = array();
        $list = $this->nbdesigner_read_json_setting($path);
        if (is_array($list)) {
            $list_art = $list;
            $id = sizeOf($list_art);
            $art['id'] = (string) $id;
        }
        $list_art[] = $art;
        $res = json_encode($list_art);
        file_put_contents($path, $res);
    }
    public function nbdesigner_read_json_setting($fullname) {
        if (file_exists($fullname)) {
            $list = json_decode(file_get_contents($fullname));           
        } else {
            $list = '';
            file_put_contents($fullname, $list);
        }
        return $list;
    }
    public function nbdesigner_delete_json_setting($fullname, $id, $reindex = true) {
        $list = $this->nbdesigner_read_json_setting($fullname);
        if (is_array($list)) {
            array_splice($list, $id, 1);
            if ($reindex) {
                $key = 0;
                foreach ($list as $val) {
                    $val->id = (string) $key;
                    $key++;
                }
            }
        }
        $res = json_encode($list);
        file_put_contents($fullname, $res);
    }
    public function nbdesigner_update_json_setting($fullname, $data, $id) {
        $list = $this->nbdesigner_read_json_setting($fullname);
        if (is_array($list))
            $list[$id] = $data;
        else {
            $list = array();
            $list[] = $data;
        }
        $_list = array();
        foreach ($list as $val) {
            $_list[] = $val;
        }
        $res = json_encode($_list);
        file_put_contents($fullname, $res);
    }
    public function nbdesigner_update_json_setting_depend($fullname, $id) {
        $list = $this->nbdesigner_read_json_setting($fullname);
        if (!is_array($list)) return;
        foreach ($list as $val) {             
            if (!((sizeof($val) > 0))) continue;
            //if (!sizeof($val->cat)) break;           
            foreach ($val->cat as $k => $v) {
                if ($v == $id) {                   
                    array_splice($val->cat, $k, 1);
                    break;
                }
            }
            foreach ($val->cat as $k => $v) {
                if ($v > $id) {
                    $new_v = (string) --$v;
                    unset($val->cat[$k]);
                    array_splice($val->cat, $k, 0, $new_v);
                    //$val->cat[$k] = (string)--$v;										
                }
            }
        }
        $res = json_encode($list);
        file_put_contents($fullname, $res);
    }
    public function nbdesigner_delete_font() {
        $data = array(
                'mes'   =>  __('You do not have permission to delete font!', 'nbdesigner'),
                'flag'  => 0
            );        
        if (!wp_verify_nonce($_POST['nonce'], 'nbdesigner_add_cat') || !current_user_can('delete_nbd_font')) {
            echo json_encode($data);
            wp_die();
        }
        $id = $_POST['id'];
        $type = $_POST['type'];
        if ($type == 'custom') {
            $path = NBDESIGNER_DATA_DIR . '/fonts.json';
            $list = $this->nbdesigner_read_json_setting($path);
            $file_font = $list[$id]->file;
            unlink($file_font);
        } else
            $path = NBDESIGNER_DATA_DIR . '/googlefonts.json';
        $this->nbdesigner_delete_json_setting($path, $id);
        $data['mes'] = __('Clipart has been deleted successfully!', 'nbdesigner');
        $data['flag'] = 1;
        echo json_encode($data);
        wp_die();
    }
    public function nbdesigner_delete_art() {
        $data = array(
                'mes'   =>  __('You do not have permission to delete clipart!', 'nbdesigner'),
                'flag'  => 0
            );
        if (!wp_verify_nonce($_POST['nonce'], 'nbdesigner_add_cat') || !current_user_can('delete_nbd_art')) {
            echo json_encode($data);
            wp_die();
        }
        $id = $_POST['id'];
        $path = NBDESIGNER_DATA_DIR . '/arts.json';
        $list = $this->nbdesigner_read_json_setting($path);
        $file_art = $list[$id]->file;
        unlink($file_art);
        $this->nbdesigner_delete_json_setting($path, $id);
        $data['mes'] = __('Clipart has been deleted successfully!', 'nbdesigner');
        $data['flag'] = 1;
        echo json_encode($data);
        wp_die();
    }
    public function nbdesigner_update_font($font, $id) {
        $path = NBDESIGNER_DATA_DIR . '/fonts.json';
        $this->nbdesigner_update_json_setting($path, $font, $id);
    }
    public function nbdesigner_notices($value = '') {
        return $value;
    }
    public function nbdesigner_custom_notices($command, $mes) {
        switch ($command) {
            case 'success':
                if (!isset($mes))
                    $mes = __('Your settings have been saved.', 'nbdesigner');
                $notice = '<div class="updated notice notice-success is-dismissible">
                                <p>' . $mes . '</p>
                                <button type="button" class="notice-dismiss">
                                    <span class="screen-reader-text">Dismiss this notice.</span>
                                </button>				  
                            </div>';
                break;
            case 'error':
                if (!isset($mes))
                    $mes = __('Irks! An error has occurred.', 'nbdesigner');
                $notice = '<div class="notice notice-error is-dismissible">
                                <p>' . $mes . '</p>
                                <button type="button" class="notice-dismiss">
                                    <span class="screen-reader-text">Dismiss this notice.</span>
                                </button>				  
                            </div>';
                break;
            case 'notices':
                if (!isset($mes))
                    $mes = __('Irks! An error has occurred.', 'nbdesigner');
                $notice = '<div class="notice notice-warning">
                                <p>' . $mes . '</p>				  
                            </div>';
                break;             
            case 'warning':
                if (!isset($mes))
                    $mes = __('Warning.', 'nbdesigner');
                $notice = '<div class="notice notice-warning is-dismissible">
                                <p>' . $mes . '</p>
                                <button type="button" class="notice-dismiss">
                                    <span class="screen-reader-text">Dismiss this notice.</span>
                                </button>				  
                            </div>';
                break;
            default:
                $notice = '';
        }
        return $notice;
    }
    public function nbdesigner_manager_arts() {
        $notice = '';
        $current_art_cat_id = 0;
        $art_id = 0;
        $update = false;
        $cats = array("0");
        $list = $this->nbdesigner_read_json_setting($this->plugin_path_data . 'arts.json');
        $cat = $this->nbdesigner_read_json_setting($this->plugin_path_data . 'art_cat.json');
        $total = sizeof($list);
        $limit = 40;
        if (is_array($cat))
            $current_art_cat_id = sizeof($cat);
        if (isset($_GET['id'])) {
            $art_id = $_GET['id'];
            $update = true;
            if (isset($list[$art_id])) {
                $art_data = $list[$art_id];
                $cats = $art_data->cat;
            }
        }
        $page = filter_input(INPUT_GET, "p", FILTER_VALIDATE_INT);
        $current_cat = filter_input(INPUT_GET, "cat_id", FILTER_VALIDATE_INT);

        if (isset($_POST[$this->plugin_id . '_hidden']) && wp_verify_nonce($_POST[$this->plugin_id . '_hidden'], $this->plugin_id) && current_user_can('edit_nbd_art')) {
            $art = array();
            $art['name'] = esc_html($_POST['nbdesigner_art_name']);
            $art['id'] = $_POST['nbdesigner_art_id'];
            $art['cat'] = $cats;
            if (isset($_POST['nbdesigner_art_cat']))
                $art['cat'] = $_POST['nbdesigner_art_cat'];
            if (isset($_FILES['svg']) && ($_FILES['svg']['size'] > 0) && ($_POST['nbdesigner_art_name'] != '')) {
                $uploaded_file_name = basename($_FILES['svg']['name']);
                $allowed_file_types = array('svg');
                if (Nbdesigner_IO::checkFileType($uploaded_file_name, $allowed_file_types)) {
                    $upload_overrides = array('test_form' => false);
                    $uploaded_file = wp_handle_upload($_FILES['svg'], $upload_overrides);
                    if (isset($uploaded_file['url'])) {
                        $art['file'] = $uploaded_file['file'];
                        $art['url'] = $uploaded_file['url'];
                        if ($update) {
                            $this->nbdesigner_update_list_arts($art, $art_id);
                        } else {
                            $this->nbdesigner_update_list_arts($art);
                        }
                        $notice = apply_filters('nbdesigner_notices', $this->nbdesigner_custom_notices('success', __('Your art has been saved.', 'nbdesigner')));
                    } else {
                        $notice = apply_filters('nbdesigner_notices', $this->nbdesigner_custom_notices('error', __('Error while upload art, please try again!', 'nbdesigner')));
                    }
                } else {
                    $notice = apply_filters('nbdesigner_notices', $this->nbdesigner_custom_notices('error', __('Incorrect file extensions.', 'nbdesigner')));
                }
            } else if ($update && ($_POST['nbdesigner_art_name'] != '')) {
                $art_data->name = $_POST['nbdesigner_art_name'];
                $art_data->cat = $art['cat'];
                $this->nbdesigner_update_list_arts($art_data, $art_id);
                $notice = apply_filters('nbdesigner_notices', $this->nbdesigner_custom_notices('success', __('Your art has been saved.', 'nbdesigner')));
            } else {
                $notice = apply_filters('nbdesigner_notices', $this->nbdesigner_custom_notices('warning', __('Please choose art file or art name.', 'nbdesigner')));
            }
            $list = $this->nbdesigner_read_json_setting($this->plugin_path_data . 'arts.json');
            $cats = $art['cat'];
            $total = sizeof($list);
            
        }
        $name_current_cat = 'uploaded';
        if($total){
            if(isset($current_cat)){
                $new_list = array();
                foreach($list as $art){  
                    if(in_array((string)$current_cat, $art->cat)) $new_list[] = $art;
                    if(($current_cat == 0) && sizeof($art->cat) == 0) $new_list[] = $art;
                }
                foreach($cat as $c){
                    if($c->id == $current_cat){
                        $name_current_cat = $c->name;
                        break;
                    } 
                    $name_current_cat = 'uploaded';
                }
                $list = $new_list;
                $total = sizeof($list);               
            }else{
                $name_current_cat = 'uploaded';
            }
            if(isset($page)){
                $_tp = ceil($total / $limit);
                if($page > $_tp) $page = $_tp;
                $_list = array_slice($list, ($page-1)*$limit, $limit);
            }else{
                $_list = $list;
                if($total > $limit) $_list = array_slice($list, 0, $limit);	
            }
        } else{
            $_list = array();
        }        
        if(isset($current_cat)){
            $url = add_query_arg(array('cat_id' => $current_cat), admin_url('admin.php?page=nbdesigner_manager_arts'));
        }else{
            $url = admin_url('admin.php?page=nbdesigner_manager_arts');   
        }
        require_once NBDESIGNER_PLUGIN_DIR . 'includes/class.nbdesigner.pagination.php';
        $paging = new Nbdesigner_Pagination();
        $config = array(
            'current_page'  => isset($page) ? $page : 1, 
            'total_record'  => $total,
            'limit'         => $limit,
            'link_full'     => $url.'&p={p}',
            'link_first'    => $url              
        );	        
        $paging->init($config);         
        include_once(NBDESIGNER_PLUGIN_DIR . 'views/nbdesigner-manager-arts.php');
    }
    public function admin_success() {
        if (isset($_POST[$this->plugin_id . '_hidden']) && wp_verify_nonce($_POST[$this->plugin_id . '_hidden'], $this->plugin_id)){
            echo '<div class="updated notice notice-success is-dismissible">
                        <p>' . __('Your settings have been saved.', 'nbdesigner') . '</p>
                        <button type="button" class="notice-dismiss">
                            <span class="screen-reader-text">Dismiss this notice.</span>
                        </button>				  
                  </div>';
        }
    }
    public function add_design_box() {
        add_meta_box('nbdesigner_setting', __('Setting NBDesigner', 'nbdesigner'), array($this, 'setting_design'), 'product', 'normal', 'high');
        add_meta_box('nbdesigner_order', __('Customer Design', 'nbdesigner'), array($this, 'order_design'), 'shop_order', 'side', 'default');
    }
    public function nbdesigner_detail_order() {
        if(isset($_GET['order_id'])){
            $order_id = $_GET['order_id'];
            $order = new WC_Order($order_id);
            if(is_woo_v3()){
                $user_id = $order->get_customer_id();  
            }else{
                $user_id = $order->user_id;  
            }   
            if($user_id == ''){
                $iid = get_post_meta($order_id, '_nbdesigner_order_userid', true);
                if(!empty($iid))
                    $user_id = $iid;
            }            
            if(isset($_GET['download-all']) && ($_GET['download-all'] == 'true')){
                $zip_files = array();
                $_data_designs = unserialize(get_post_meta($order_id, '_nbdesigner_design_file', true));
                if(isset($_data_designs) && is_array($_data_designs))    $data_designs = $_data_designs;
                $products = $order->get_items();
                foreach($products AS $order_item_id => $product){
                    $has_design = wc_get_order_item_meta($order_item_id, '_nbdesigner_has_design');                  
                    if($has_design == 'has_design'){
                        $folder_design = wc_get_order_item_meta($order_item_id, '_nbdesigner_folder_design');
                        if($folder_design){
                            $path = NBDESIGNER_CUSTOMER_DIR . '/' . $user_id . '/' . $order_id .'/' .$folder_design;
                        }else{
                            $path = NBDESIGNER_CUSTOMER_DIR . '/' . $user_id . '/' . $order_id .'/' .$product["product_id"];
                        }                        
                        $list_images = $this->nbdesigner_list_thumb($path, 1);
                        if(count($list_images) > 0){
                            foreach($list_images as $key => $image){
                                $zip_files[] = $image;
                            }
                        }
                    }              
                }
                $pathZip = NBDESIGNER_DATA_DIR.'/download/customer-design-'.$_GET['order_id'].'.zip';
                $nameZip = 'customer-design-'.$_GET['order_id'].'.zip';
                $this->zip_files_and_download($zip_files, $pathZip, $nameZip);
            }
            if(isset($_GET['product_id'])){
                $license = $this->nbdesigner_check_license();
                $product_id = $_GET['product_id'];
				$variation_id = $_GET['vid'];
                $path = NBDESIGNER_CUSTOMER_DIR . '/' . $user_id . '/' . $order_id .'/' .$product_id;  
                if(isset($_GET['order_item_id'])){
                    $order_item_id = $_GET['order_item_id'];
                    $folder_design = wc_get_order_item_meta($order_item_id, '_nbdesigner_folder_design');     
                    $path = NBDESIGNER_CUSTOMER_DIR . '/' . $user_id . '/' . $order_id .'/' .$folder_design;  
                }
                if($variation_id > 0){
                    $datas = unserialize(get_post_meta($variation_id, '_designer_setting'.$variation_id, true)); 
                }else{
                    $datas = unserialize(get_post_meta($product_id, '_designer_setting', true)); 
                }
                $list_design = array();
                $list_images = Nbdesigner_IO::get_list_thumbs($path, 1);
                foreach ($list_images as $img){
                    $name = basename($img);
                    $arr = explode('.', $name);
                    $_frame = explode('_', $arr[0]);
                    $frame = $_frame[1];
                    $list_design[$frame] = Nbdesigner_IO::convert_path_to_url($img);
                }
            }
        }
        require_once NBDESIGNER_PLUGIN_DIR .'views/nbdesigner-detail-order.php';
    }
    public function setting_design() {
        $current_screen = get_current_screen();
        $default =  nbd_default_product_setting();    
        $designer_setting = array();
        $designer_setting[0] = $default;
        $post_id = get_the_ID();
        $_designer_setting = unserialize(get_post_meta($post_id, '_designer_setting', true));
        $dpi = get_post_meta($post_id, '_nbdesigner_dpi', true);
        $enable = get_post_meta($post_id, '_nbdesigner_enable', true);
        $option = unserialize(get_post_meta($post_id, '_nbdesigner_option', true));
        $priority = get_post_meta($post_id, '_nbdesigner_admintemplate_primary', true);    
        $link_admindesign = getUrlPageNBD('template').'?product_id='.$post_id;
        $unit = nbdesigner_get_option('nbdesigner_dimensions_unit');
        if($dpi == "") $dpi = nbdesigner_get_option('nbdesigner_default_dpi');
        if (isset($_designer_setting[0])){
            $designer_setting = $_designer_setting;
            if(! isset($designer_setting[0]['version']) || $_designer_setting[0]['version'] < 160) {
                $designer_setting = $this->update_config_product_160($designer_setting);              
            }
        }
        include_once(NBDESIGNER_PLUGIN_DIR . 'views/nbdesigner-box-design-setting.php');
    }
    public function update_config_product_160($designer_setting){
        $newSetting = array();
        $default =  nbd_default_product_setting();    
        foreach ($designer_setting as $key => $setting){
            $setting160 = array();     
            $scale = 1;
            if(($setting['img_src_width'] > $setting['img_src_height']) && ($setting['img_src_width'] < 300)) $scale = 300/ $setting['img_src_width'];
            if(($setting['img_src_width'] < $setting['img_src_height']) && ($setting['img_src_height'] < 300)) $scale = 300/ $setting['img_src_height'];
            $ratio150 = 300 / $setting['area_design_width'] / $scale * $setting['real_width'];
            $product_width = round($ratio150 * $setting['img_src_width'] * $scale / 300, 2);
            $product_height = round($ratio150 * $setting['img_src_height'] * $scale / 300, 2);
            $area_design_width = round($setting['area_design_width'] * $scale * 5/3);
            $area_design_height = round($setting['area_design_height'] * $scale * 5/3);
            $old_real_top = $ratio150 * $setting['area_design_top'] * $scale / 300;
            $old_real_left = $ratio150 * $setting['area_design_left'] * $scale / 300;
            $ratio = 500 / $product_height;
            $img_src_top = 0;
            $img_src_left = round(($product_height - $product_width) * $ratio / 2, 2);
            $img_src_height = 500;
            $img_src_width = round($product_width * $ratio);
            $real_top = round($old_real_top, 2);
            $real_left = round($old_real_left - ($product_height - $product_width) / 2, 2);
            if($product_width > $product_height){
                $img_src_top = round(($product_width - $product_height) * $ratio / 2, 2);
                $img_src_left = 0;
                $img_src_width = 500;
                $img_src_height = round($product_height * $ratio);
                $real_left = round($old_real_left, 2);
                $real_top = round($old_real_top - ($product_width - $product_height) / 2, 2);
            }
            $area_design_left = round($setting['area_design_left'] * $scale * 5/3);
            $area_design_top = round($setting['area_design_top'] * $scale * 5/3);
            $setting160['product_width'] = $product_width;
            $setting160['product_height'] = $product_height;
            $setting160['real_width'] = $setting['real_width'];
            $setting160['real_height'] = $setting['real_height'];
            $setting160['real_left'] = $real_left;
            $setting160['real_top'] = $real_top;
            $setting160['area_design_left'] = $area_design_left;
            $setting160['area_design_top'] = $area_design_top;
            $setting160['area_design_width'] = $area_design_width;
            $setting160['area_design_height'] = $area_design_height;
            $setting160['img_src_top'] = $img_src_top;
            $setting160['img_src_left'] = $img_src_left;
            $setting160['img_src_height'] = $img_src_height;
            $setting160['img_src_width'] = $img_src_width;
            $setting160['img_src'] = $setting['img_src'];
            $setting160['orientation_name'] = $setting['orientation_name'];   
            $setting160 = array_merge($default, $setting160);
            $newSetting[$key] = $setting160;
        }
        return $newSetting;
    }
    public function nbdesigner_update_all_product(){
        if (!wp_verify_nonce($_POST['_nbdesigner_cupdate_product'], 'nbdesigner-update-product') || !current_user_can('administrator')) {
            die('Security error');
        }         
        $args_query = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'meta_key' => '_nbdesigner_enable',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page'=> -1,
            'meta_query' => array(
                array(
                    'key' => '_nbdesigner_enable',
                    'value' => 1,
                )
            )
        ); 
        $posts = get_posts($args_query);   
        $result = array('flag' => 1);
        if(is_array($posts)){    
            foreach ($posts as $post){
                $product = wc_get_product($post->ID);       
                if( $product->is_type( 'variable' ) ) { 
                    $variations = $product->get_available_variations( false );
                    foreach ($variations as $variation){
                        $vid = $variation['variation_id'];
                        $designer_setting = unserialize(get_post_meta($vid, '_designer_setting'.$vid, true));
                        if(! isset($designer_setting[0]['version']) || $designer_setting[0]['version'] < 160) {
                            $newSetting = $this->update_config_product_160($designer_setting);
                            update_post_meta($vid, '_designer_setting'.$vid, serialize($newSetting));                     
                        }                          
                    }
                }
                $designer_setting = unserialize(get_post_meta($post->ID, '_designer_setting', true));
                if(! isset($designer_setting[0]['version']) || $designer_setting[0]['version'] < 160) {
                    $newSetting = $this->update_config_product_160($designer_setting);
                    update_post_meta($post->ID, '_designer_setting', serialize($newSetting));                     
                }                  
            }
        }
        echo json_encode($result);
        wp_die();
    }
    public function order_design($post) {
        $order = new WC_Order($post->ID);
        if( is_woo_v3() ){
            $user_id = $order->get_customer_id();     
        }else{
            $user_id = $order->user_id;  
        }        
        if($user_id == ''){
            $iid = get_post_meta($post->ID, '_nbdesigner_order_userid', true);
            if(!empty($iid))
                $user_id = $iid;
        }
        $order_id = $post->ID;
        $products = $order->get_items();
        $_data_designs = unserialize(get_post_meta($order_id, '_nbdesigner_design_file', true));
        if(isset($_data_designs) && is_array($_data_designs))    $data_designs = $_data_designs;
        $has_design = get_post_meta($order_id, '_nbdesigner_order_has_design', true);
        include_once(NBDESIGNER_PLUGIN_DIR . 'views/nbdesigner-box-order-metadata.php');
    }
    public function nbdesigner_allow_create_product($id){
        $args = array(
            'post_type'  => 'product',
            'meta_key' => '_nbdesigner_enable',
            'meta_value' => 1
        );
        $query = new WP_Query($args);
        $pros = get_posts($args);
        $list = array();
        foreach ($pros as $pro){
            $list[] = $pro->ID;
        }        
        $count = $query->found_posts;
        $license = $this->nbdesigner_check_license();
        if(!isset($license['key'])) return false;
        if(in_array($id, $list)) return true;
        $salt = md5($license['key'].'pro'); 
        if(($salt != $license['salt']) && ($count > 5)) return false;
        return true;
    }
    public function nbdesigner_get_info_license(){
        if (!wp_verify_nonce($_POST['_nbdesigner_license_nonce'], 'nbdesigner-active-key') || !current_user_can('administrator')) {
            die('Security error');
        } 
        $result = array();
        if(isset($_POST['nbdesigner']['license'])) {
            $license = $_POST['nbdesigner']['license'];            
            $result_from_json = $this->nbdesiger_request_license($license, $this->activedomain);
            $data = (array)json_decode($result_from_json);            
            if(isset($data)) {
                switch ($data["code"]) {
                    case -1 :
                        $result['mes'] = __('Missing necessary information!', 'nbdesigner');
                        $result['flag'] = 0;
                        break;
                    case 0 :
                        $result['mes'] = __('Incorrect information, check again license key', 'nbdesigner');
                        $result['flag'] = 0;
                        break;     
                    case 1 :
                        $result['mes'] = __('Incorrect License key', 'nbdesigner');
                        $result['flag'] = 0;
                        break;
                    case 2 :
                        $result['mes'] = __('License key is locked ', 'nbdesigner');
                        $result['flag'] = 0;
                        break; 
                    case 3 :
                        $result['mes'] = __('License key have expired', 'nbdesigner');
                        $result['flag'] = 0;
                        break;
                    case 4 :
                        $result['mes'] = __('Link your website incorrect', 'nbdesigner');
                        $result['flag'] = 0;
                        break;     
                    case 5 :
                        $result['mes'] = __('License key can using', 'nbdesigner');
                        $result['flag'] = 1;
                        break;
                    case 6 :
                        $result['mes'] = __('Domain has been added successfully', 'nbdesigner');
                        $result['flag'] = 1;
                        break;     
                    case 7 :
                        $result['mes'] = __('Exceed your number of domain license', 'nbdesigner');
                        $result['flag'] = 0;
                        break;
                    case 8 :
                        $result['mes'] = __('Unsuccessfully active license key', 'nbdesigner');
                        $result['flag'] = 0;
                        break;                     
                }            
                $data['key'] = $license;
                $data['salt'] = md5($license.$data['type']);
                if($data['type'] == 'free') $data['number_domain'] = "5";
                if(($data["code"] == 5) || ($data["code"] == 6)){
                    $this->nbdesigner_write_license(json_encode($data));  
                }                      
            }else{
                $result['mes'] = __('Try again later!', 'nbdesigner');
            }
        }else {
            $result['mes'] = __('Please fill your license!', 'nbdesigner');
        }
        echo json_encode($result);
        wp_die();
    }
    public function nbdesigner_remove_license(){
        if (!wp_verify_nonce($_POST['_nbdesigner_license_nonce'], 'nbdesigner-active-key') || !current_user_can('administrator')) {
            die('Security error');
        } 	        
        $result = array();
        $result['flag'] = 0;
        $path = NBDESIGNER_PLUGIN_DIR . 'data/license.json';
        $path_data = NBDESIGNER_DATA_CONFIG_DIR . '/license.json';
        if(file_exists($path_data)) {
            $path = $path_data;
        }
        $license = $this->nbdesigner_check_license();
        if(!file_exists($path)){
            $result['mes'] = __('You haven\'t any license!', 'nbdesigner');
        }else{
            $license = $this->nbdesigner_check_license();
            $key = (isset($license['key'])) ? $license['key'] : '';
            $_request = $this->nbdesiger_request_license($key, $this->removedomain);             
            if(isset($_request)){              
                $request = (array)json_decode($_request);                   
                switch ($request["code"]) {
                    case -1:
                        $result['mes'] = __('Missing necessary information', 'nbdesigner');
                        break;
                    case 0:
                        $result['mes'] = __('Incorrect information', 'nbdesigner');
                        break;
                    case 1:
                        $result['mes'] = __('Incorrect License key', 'nbdesigner');
                        break;
                    case 2: 
                        if(!unlink($path)){
                            $result['mes'] = __('Error, try again later!', 'nbdesigner');
                        }else{
                            $_path = NBDESIGNER_PLUGIN_DIR . 'data/license.json';
                            if(file_exists($_path)) unlink($_path);                        
                            $result['mes'] = __('Remove license key Successfully', 'nbdesigner');
                            $result['flag'] = 1;
                        };                        
                        break;  
                    case 3:
                        $result['mes'] = __('Remove license key Unsuccessfully!', 'nbdesigner');
                        break;                    
                }
            }
        }       
        echo json_encode($result);
        wp_die();        
    }
    private function nbdesiger_request_license($license, $task){
        $url_root = base64_encode(rtrim(get_bloginfo('wpurl'), '/'));	
        $url = NBDESIGNER_AUTHOR_SITE.$task.NBDESIGNER_SKU.'/'.$license.'/'.$url_root;	        
        return nbd_file_get_contents($url);
    }
    private function nbdesigner_write_license($license){
        $path_data = NBDESIGNER_DATA_CONFIG_DIR . '/license.json';
        if (!file_exists(NBDESIGNER_DATA_CONFIG_DIR)) {
            wp_mkdir_p(NBDESIGNER_DATA_CONFIG_DIR);
        }         
        file_put_contents($path_data, $license);
    }
    private function nbdesigner_check_license(){
        $path_data = NBDESIGNER_DATA_CONFIG_DIR . '/license.json';
        $path = NBDESIGNER_PLUGIN_DIR . 'data/license.json';
        if(file_exists($path_data)) $path = $path_data;
        $result = array();
        $result['status'] = 1;
        if(!file_exists($path)){
            $result['status'] = 0;
        }else{
            $data = (array) json_decode(file_get_contents($path));
            $code = (isset($data["code"])) ? $data["code"] : 10;
            if(($code != 5) && ($code != 6)){
                $result['status'] = 0;	               
            }
            $now = strtotime("now");
            $expiry_date = (isset($data["expiry-date"])) ? $data["expiry-date"] : 0;
            $result['expiry'] = $expiry_date;
            if($expiry_date < $now){
                $result['status'] = 0;             
            }
            if(isset($data['key'])) $result['key'] = $data['key'];
            if(isset($data['type'])) {	                
                $result['type'] = $data['type'];
                $license = (isset($data['key'])) ? $data['key'] : 'somethingiswrong';
                $salt = (isset($data['salt'])) ? $data['salt'] : 'somethingiswrong';
                $new_salt = md5($license.'pro');	                
                if($salt == $new_salt){
                    $result['type'] = $data['type'];
                }else{
                    $result['type'] = 'free';
                    $result['status'] = 0;    
                } 
                $result['salt'] = $salt;
            }
        }
        return $result;
    }
    private function nbdesigner_get_site_url() {
        if (isset($_SERVER['HTTPS'])) {
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        } else {
            $protocol = 'http';
        }
        $base_url = $protocol . "://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER["REQUEST_URI"] . '?') . '/';
        return base64_encode($base_url);
    }
    public function save_design($post_id) {   
        if (!isset($_POST['nbdesigner_setting_box_nonce']) || !wp_verify_nonce($_POST['nbdesigner_setting_box_nonce'], 'nbdesigner_setting_box')
            || !(current_user_can('administrator') || current_user_can('shop_manager'))) {
            return $post_id;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }
        }
        $enable = $_POST['_nbdesigner_enable']; 
        $dpi = $_POST['_nbdesigner_dpi']; 
        $option = serialize($_POST['_nbdesigner_option']); 
        if(!is_numeric($dpi)) $dpi = nbdesigner_get_option('nbdesigner_default_dpi');
        $dpi = abs($dpi);
        $setting = serialize($_POST['_designer_setting']);  
        if(!$enable){            
            $setting = '';
            update_post_meta($post_id, '_designer_setting', $setting);
            update_post_meta($post_id, '_nbdesigner_dpi', $dpi);
            update_post_meta($post_id, '_nbdesigner_option', $option);
            update_post_meta($post_id, '_nbdesigner_enable', $enable);
            return;
        }
        if(!$this->nbdesigner_allow_create_product($post_id)) return;
        update_post_meta($post_id, '_designer_setting', $setting);
        update_post_meta($post_id, '_nbdesigner_dpi', $dpi);
        update_post_meta($post_id, '_nbdesigner_option', $option);
        update_post_meta($post_id, '_nbdesigner_enable', $enable);
    }
    public function nbdesigner_get_extension($file_name) {
        $filetype = explode('.', $file_name);
        $file_exten = $filetype[count($filetype) - 1];
        return $file_exten;
    }
    public function nbdesigner_button($att = false) {
        $temp = get_query_var( 'nbds-adid' ) ? get_query_var( 'nbds-adid' ) : 0;
        $ref = get_query_var( 'nbds-ref' ) ? get_query_var( 'nbds-ref' ) : 0;
        if(is_array($att)){
            $pid = absint($att['id']);
        }else{
            $pid = get_the_ID();
        }    
        $is_nbdesign = get_post_meta($pid, '_nbdesigner_enable', true);
        $uid = get_current_user_id();
        $sid = session_id();
        $order = 'nb_order';
        if ($is_nbdesign) {
            if ($uid > 0) {
                $iid = $uid;
                if (isset($_GET['orderid']))
                    $order = $_GET['orderid'];
            }else {
                $iid = $sid;
            }; 
            $label = __('Start Design', 'nbdesigner');
            $div_image = '<div id="nbdesigner_frontend_area"></div>';
            $button = '<div class="nbdesigner_frontend_container">';
            $button .= '<a class="button nbdesign-button nbdesigner-disable" id="triggerDesign" >'
                    . '<img class="nbdesigner-img-loading rotating" src="'.NBDESIGNER_PLUGIN_URL.'assets/images/loading.png'.'"/>'.$label.'</a><br />';
            $button .= '<h4 id="nbdesigner-preview-title" style="display: none;">'.__('Preview your design', 'nbdesigner').'</h4>' . $div_image;
            $button .= '</div><br />';
            $src = add_query_arg(array('action' => 'nbdesigner_editor_html', 'product_id' => $pid), site_url());                    
            if(is_numeric($order)) $src .= '&orderid='.$order;
            if($temp) $src .= '&template_folder='.$temp;
            if($ref) $src .= '&reference_product='.$ref;
            $button .= '<div style="position: fixed; top: 0; left: 0; z-index: 999999; opacity: 0; width: 100%; height: 100%;" id="container-online-designer"><iframe id="onlinedesigner-designer"  width="100%" height="100%" scrolling="no" frameborder="0" noresize="noresize" allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true" src="' . $src . '"></iframe><span id="closeFrameDesign"  class="nbdesigner_pp_close">&times;</span></div>';
            
            if(is_array($att)){
                if($att['button'] == 'yes'){                   
                    ob_start();            
                    nbdesigner_get_template('add-to-cart.php', array('pid' => $pid));
                    $content = ob_get_clean();                
                    return $button.$content;                    
                }else{
                    return $button;
                }
            }else{
                echo $button;
            }
        }
    }
    public function get_all_product_has_design(){
        $args_query = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'meta_key' => '_nbdesigner_enable',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page'=>$number,
            'meta_query' => array(
                array(
                    'key' => '_nbdesigner_enable',
                    'value' => 1,
                )
            )
        ); 
        $posts = get_posts($args_query);  
        return $posts;
    }
    public function nbdesigner_get_product_info() {
        if (!wp_verify_nonce($_POST['nonce'], 'save-design')) {
            die('Security error');
        }
        $task = (isset($_POST['task']) &&  $_POST['task'] != '') ? $_POST['task'] : '';
        $order_id = (isset($_POST['order_id']) &&  $_POST['order_id'] != '') ? absint($_POST['order_id']) : '';
        $product_id = (isset($_POST['product_id']) &&  $_POST['product_id'] != '') ? absint($_POST['product_id']) : '';
        $variation_id = (isset($_POST['variation_id']) &&  $_POST['variation_id'] != '') ? absint($_POST['variation_id']) : 0;
        $template_folder = (isset($_POST['template_folder']) &&  $_POST['template_folder'] != '') ? $_POST['template_folder'] : '';
        $reference_product = (isset($_POST['reference_product']) &&  $_POST['reference_product'] != '') ? $_POST['reference_product'] : '';
        $order_item_folder = (isset($_POST['order_item_folder']) &&  $_POST['order_item_folder'] != '') ? $_POST['order_item_folder'] : '';
        $template_priority = (isset($_POST['template_priority']) &&  $_POST['template_priority'] != '') ? $_POST['template_priority'] : '';
        $user_id = (get_current_user_id() > 0) ? get_current_user_id() : session_id();         
        $data = nbd_get_product_info($user_id, $product_id, $variation_id, $task, $reference_product, $template_folder, $order_id, $order_item_folder);  
        if($task != 'create_template' && $task != 'edit_template' && $task != 'redesign' && $template_folder != '' && $product_id != ''){
            $this->update_template_hit($product_id, $template_folder);
        }
        echo json_encode($data);
        wp_die();
    }
    public function nbdesigner_save_partial_customer_design(){
        if (!wp_verify_nonce($_POST['nonce'], 'save-design')) {
            die('Security error');
        } 
        $result = array();
        $force_new_folder = true;
        $result['flag'] = 0;       
        $sid = esc_html($_POST['sid']);
        $pid = $_POST['product_id'];  
        $flag = (int)$_POST['flag'];  
        $order = 'nb_order';
        if(!$flag){
            $config = str_replace('\"', '"', $_POST['config']);
            $fonts = str_replace('\"', '"', $_POST['fonts']);            
        }
        if(isset($_POST['image'])){
            $data = $_POST['image']['img'];
            if(!$flag){
                $json = str_replace('\"', '"', $_POST['image']['json']);	
                $json = str_replace('\\\\', '\\', $json);	                
            }
        } else{
            die('Incorect data!');
        }        
        $uid = get_current_user_id(); 
        $iid = $sid;
        if($uid > 0) $iid = $uid;    
        $path = NBDESIGNER_CUSTOMER_DIR . '/' . $iid . '/' . $order . '/' . $pid;
        $path_thumb = $path . '/thumbs';
        if(!$flag){      
            $json_file = $path . '/design.json';
            $json_config = $path . '/config.json';
            $json_used_font = $path . '/used_font.json';    
            file_put_contents($json_file, $json);
            file_put_contents($json_config, $config);
            file_put_contents($json_used_font, $fonts);            
        }
        if(file_exists($path) && !$flag){
            Nbdesigner_IO::delete_folder($path);
        }    
        if (!file_exists($path) ) {
            if (wp_mkdir_p($path)) {
                if (!file_exists($path_thumb))
                    if (!wp_mkdir_p($path_thumb)) {
                        $result['mes'] = __('Your server not allow creat folder', 'nbdesigner');
                    }
            } else {
                $result['mes'] = __('Your server not allow creat folder', 'nbdesigner');
            }
        } 
        $configs = unserialize(get_post_meta($pid, '_designer_setting', true));      
        $thumb_width = nbdesigner_get_option('nbdesigner_thumbnail_width');
        $thumb_height = nbdesigner_get_option('nbdesigner_thumbnail_height');
        $thumb_quality = nbdesigner_get_option('nbdesigner_thumbnail_quality');  
        if($configs[$flag]["area_design_width"] > $configs[$flag]["area_design_height"]){
            $thumb_height = round($thumb_width * $configs[$flag]['area_design_height'] / $configs[$flag]['area_design_width']);
        }else {
            $thumb_width = round($thumb_height * $configs[$flag]['area_design_width'] / $configs[$flag]['area_design_height']);
        }         
        foreach ($data as $key => $val) {
            $temp = explode(';base64,', $val);
            $buffer = base64_decode($temp[1]);
            $full_name = $path . '/' . $key . '.png';
            if (Nbdesigner_IO::save_data_to_file($full_name, $buffer)) {
                $image = wp_get_image_editor($full_name);
                if (!is_wp_error($image)) {
                    $thumb_file = $path_thumb . '/' . $key . '.png';
                    $image->resize($thumb_width, $thumb_height, 1);
                    $image->set_quality($thumb_quality);
                    if ($image->save($thumb_file, 'image/png')) $result['link'] = Nbdesigner_IO::secret_image_url($thumb_file);
                }                
            } else {
                $result['mes'] = __('Your server not allow writable file', 'nbdesigner');
            }            
        }       
        $result['flag'] = "success";
        echo json_encode($result);
        wp_die();        
    }
    public function nbd_save_customer_design(){      
        if (!wp_verify_nonce($_POST['nonce'], 'save-design')) {
            die('Security error');
        }     
        $result = array();
        do_action('before_nbd_save_customer_design');
        $product_id = (isset($_POST['product_id']) && $_POST['product_id'] != '') ? absint($_POST['product_id']) : 0;        
        $variation_id = (isset($_POST['variation_id']) && $_POST['variation_id'] != '') ? absint($_POST['variation_id']) : 0;        
        if(!$product_id) {
            $result['mes'] = __('Not a product', 'nbdesigner'); echo json_encode($result); wp_die();        
        }
        $user_id = (get_current_user_id() > 0) ? get_current_user_id() : session_id();
        $task = (isset($_POST['task']) && $_POST['task'] != '') ? $_POST['task'] : '';
        $order_item_folder = (isset($_POST['order_item_folder']) && $_POST['order_item_folder'] != '') ? $_POST['order_item_folder'] : '';
        $order_id = (isset($_POST['order_id']) && $_POST['order_id'] != '') ? $_POST['order_id'] : '';
        $template_folder = (isset($_POST['template_folder']) && $_POST['template_folder'] != '') ? $_POST['template_folder'] : '';
        $save_status = (isset($_POST['save_status']) && $_POST['save_status'] != '') ? absint($_POST['save_status']) : 1;             
        $data = $_FILES;       
        $path = '';      
        if($task == 'redesign'){
            if(!get_current_user_id()) {
                $result['mes'] = __('You must login to re-design', 'nbdesigner'); echo json_encode($result); wp_die();
            }
            $path_old = NBDESIGNER_CUSTOMER_DIR . '/' .$user_id. '/' .$order_id. '/' .$order_item_folder ;
            $path = NBDESIGNER_CUSTOMER_DIR . '/' . $user_id . '/nb_order/' . $product_id;   
        }else if($task == 'create_template' || $task == 'edit_template'){
            if(!current_user_can('edit_nbd_template')){
                $result['mes'] = __('You have not permission to create or edit template', 'nbdesigner'); echo json_encode($result); wp_die();
            }
            $priority = (isset($_POST['priority']) && $_POST['priority'] == 'primary') ? 1 : 0; 
            $path = NBDESIGNER_ADMINDESIGN_DIR . '/' . $product_id . '/' . $template_folder;
        }else {
            $path = NBDESIGNER_CUSTOMER_DIR . '/' . $user_id . '/nb_order/' . $product_id;   
        }     
        $data_after_save_image = $this->nbdesigner_save_design_to_image2($data, $path, $product_id, $variation_id);          
        if (!count($data_after_save_image['mes'])) {              
            $result['image'] = $data_after_save_image['link'];
            $result['flag'] = 'success';     
            if(($task == 'create_template' || $task == 'edit_template')){
                $this->nbdesigner_create_thumbnail_design($path, $path.'/preview', $product_id, 500, 500);     
                if(!$save_status){
                    $this->nbdesigner_insert_table_templates($product_id, $template_folder, $priority, 1, 0);
                    if($priority){
                        update_post_meta($product_id, '_nbdesigner_admintemplate_primary', 1);
                    }                    
                }                 
            }else if($task == 'redesign'){
                Nbdesigner_IO::delete_folder($path_old);
                if(wp_mkdir_p($path_old)){
                    Nbdesigner_IO::copy_dir($path, $path_old);
                    $result['redesign'] = __("Your design has been saved success! Please wait response email!", 'nbdesigner');
                    update_post_meta($order_id, '_nbdesigner_order_changed', 1);                     
                }
            }else {
                $_SESSION['nbdesigner']['nbdesigner_' . $product_id] = json_encode(Nbdesigner_IO::get_list_thumbs($path . '/thumbs'));
            }          
        }else{
            $result['mes'] = $data_after_save_image['mes'];
        }
        do_action('after_nbd_save_customer_design', $result);
        echo json_encode($result);
        wp_die();        
    }
    public function nbdesigner_save_customer_design2() {
        if (!wp_verify_nonce($_POST['nonce'], 'save-design')) {
            die('Security error');
        }  
        $sid = esc_html($_POST['sid']);
        $pid = $_POST['product_id'];  
        $oid = $_POST['orderid'];	  
        $task = $_POST['task'];	 
        $folder_design = $_POST['folder'];	 
        $oiid = $_POST['oiid'];	 
        $config = str_replace('\"', '"', $_POST['config']);
        $fonts = str_replace('\"', '"', $_POST['fonts']);
        $iid = $sid;
        $data = $_FILES;
        if(isset($_POST['json'])){            
            $json = str_replace('\"', '"', $_POST['json']);	
            $json = str_replace('\\\\', '\\', $json);	
        } else{
            die('Incorect data!');
        }     
        $uid = get_current_user_id();  
        if($uid > 0) $iid = $uid;
        if (!is_numeric($pid) || !isset($data) || !is_array($data)) die('Incorect data!');
        $result['flag'] = 'Fails to save design.';
        $result['redesign'] = '';
        $order = 'nb_order';
        $accept_save =  true;
        if(($oid != '')){
            $order_design_approve = unserialize(get_post_meta($oid, '_nbdesigner_design_file', true));
            $index = 'nbds_'.$oiid;    
            if((!isset($order_design_approve[$index])) || (isset($order_design_approve[$index]) && ($order_design_approve[$index] == 'accept')))
                $accept_save = false;
        }
        if ($uid > 0) {
            if($task == 'create_template' || $task == 'edit_template'){
                if($_POST['priority'] == 'primary'){
                    $ad_priority = 'primary';
                    $ad_folder = 'primary';
                    $priority = 1;
                }else if($_POST['priority'] == 'extra'){
                    if(isset($_POST['adid']) && $_POST['adid'] != '') $ad_folder = $_POST['adid'];
                    $ad_priority = 'extra';
                    $priority = 0;
                }                
                $data_after_save_image = $this->nbdesigner_save_design_to_image2($data, $sid, $pid, array('priority' => $ad_priority, 'folder' => $ad_folder));
                $json_file = $this->plugin_path_data . 'admindesign/' . $pid . '/' . $ad_folder . '/design.json';    
                $json_used_font = $this->plugin_path_data . 'admindesign/' . $pid . '/' . $ad_folder .'/used_font.json'; 
                $json_config = $this->plugin_path_data . 'admindesign/' . $pid . '/' . $ad_folder . '/config.json';                 
                if (!count($data_after_save_image['mes'])) {                    
                    $ad_path = $this->plugin_path_data . 'admindesign/' . $pid . '/' . $ad_folder;
                    $this->nbdesigner_create_thumbnail_design($ad_path, $ad_path.'/preview', $pid, 500, 500);     
                    if($ad_priority == 'primary'){
                        update_post_meta($pid, '_nbdesigner_admintemplate_primary', 1);
                    }
                    if($_POST['save_status'] == "0"){
                        $this->nbdesigner_insert_table_templates($pid, $ad_folder, $priority, 1, 0);
                    }
                }             
            }else{
                $_folder = $pid;
                if($folder_design != '') $_folder = $folder_design;
                $json_file = $this->plugin_path_data . 'designs/' . $uid . '/' . $order . '/' . $_folder . '/design.json';
                $json_config = $this->plugin_path_data . 'designs/' . $uid . '/' . $order . '/' . $_folder . '/config.json';
                $json_used_font = $this->plugin_path_data . 'designs/' . $uid . '/' . $order . '/' . $_folder . '/used_font.json';
                if($accept_save) $data_after_save_image = $this->nbdesigner_save_design_to_image2($data, $uid, $_folder, '');
            }    
        } else {         
            $iid = $sid;
            $_folder = $pid;
            if($folder_design != '') $_folder = $folder_design;            
            if($accept_save) $data_after_save_image = $this->nbdesigner_save_design_to_image2($data, $sid, $_folder, '');
            $json_file = $this->plugin_path_data . 'designs/' . $sid . '/' . $order . '/' . $_folder . '/design.json';
            $json_config = $this->plugin_path_data . 'designs/' . $sid . '/' . $order . '/' . $_folder . '/config.json';
            $json_used_font = $this->plugin_path_data . 'designs/' . $sid . '/' . $order . '/' . $_folder . '/used_font.json';
        } 
        if($accept_save){
            file_put_contents($json_file, $json);
            file_put_contents($json_config, $config);
            file_put_contents($json_used_font, $fonts);
            if (!count($data_after_save_image['mes'])) {
                $result['image'] = $data_after_save_image['link'];
                $result['flag'] = 'success';
                $path = $this->plugin_path_data . 'designs/' . $iid . '/nb_order/' . $pid . '/thumbs';
                if(($oid == '') && ($task != 'admindesign')){
                    $_SESSION['nbdesigner']['nbdesigner_' . $pid] = json_encode($this->nbdesigner_list_thumb($path));
                }
            }
        }
        if(($oid != '')){           
            if(isset($order_design_approve[$index]) && ($order_design_approve[$index] == 'decline')){          
                $path_product = $this->plugin_path_data. 'designs/' .$iid. '/' .$oid. '/' .$folder_design;
                $path_old = $this->plugin_path_data. 'designs/' .$iid. '/' .$oid. '/' .$folder_design. '_old';
                $path_new = $this->plugin_path_data. 'designs/' .$iid. '/nb_order/' .$folder_design;
                if(file_exists($path_old)) $this->nbdesigner_delete_folder($path_old);
                rename($path_product, $path_old);
                if(wp_mkdir_p($path_product)){
                    $this->nbdesigner_copy_dir($path_new, $path_product);
                    $result['redesign'] = __("Your design has been saved success! Please wait response email!", 'nbdesigner');
                    update_post_meta($oid, '_nbdesigner_order_changed', 1);                  
                }
            }else {
                $result['flag'] = 'pendding';
                $result['redesign'] = __("Your design has been approved or pendding to review!", 'nbdesigner');
            }
        }
        echo json_encode($result);
        wp_die();
    }    
    public function nbdesigner_start_session() {
        if (!session_id()){
            @session_start();
        }
    }
    /**
     * Create table manager template
     * @since 1.5.0
     */
    public static function nbdesigner_create_table_templates(){
        global $wpdb;
        $collate = '';
        if ( $wpdb->has_cap( 'collation' ) ) {
            $collate = $wpdb->get_charset_collate();
        } 
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        if (NBDESIGNER_VERSION != get_option("nbdesigner_version_plugin")) {
            //PRIMARY KEY must have 2 spaces before for dbDelta to work
            $tables =  "
CREATE TABLE {$wpdb->prefix}nbdesigner_templates ( 
 id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
 product_id BIGINT(20) UNSIGNED NOT NULL,
 folder varchar(255) NOT NULL,
 user_id BIGINT(20) NULL, 
 created_date DATETIME NOT NULL default '0000-00-00 00:00:00',
 publish TINYINT(1) NOT NULL default 1,
 private TINYINT(1) NOT NULL default 0,
 priority  TINYINT(1) NOT NULL default 0,
 hit BIGINT(20) NULL, 
 PRIMARY KEY  (id) 
) $collate;
            ";
            @dbDelta($tables);
        }
        update_option('nbdesigner_version_plugin', NBDESIGNER_VERSION);
        return true;
    }
    
    public function nbdesigner_user_role(){
        $capabilities = array(
            1   =>    'manage_nbd_tool',
            2   =>    'manage_nbd_art',
            3   =>    'manage_nbd_language',
            4   =>    'manage_nbd_template',
            5   =>    'manage_nbd_product',
            6   =>    'manage_nbd_analytic',
            7   =>    'manage_nbd_design_store',
            8   =>    'manage_nbd_font',
            9   =>    'export_nbd_font',
            10   =>    'export_nbd_art',
            11   =>    'export_nbd_product',
            12   =>    'export_nbd_language',
            13   =>    'import_nbd_font',
            14   =>    'import_nbd_art',
            15   =>    'import_nbd_product',
            16   =>    'import_nbd_language',
            17   =>    'edit_nbd_font',
            18   =>    'edit_nbd_art',
            19   =>    'edit_nbd_language',
            20   =>    'edit_nbd_template',
            21   =>    'delete_nbd_font',
            22   =>    'delete_nbd_art',
            23   =>    'delete_nbd_language',
            24   =>    'delete_nbd_template',
            25   =>    'sell_nbd_design',
            26   =>    'update_nbd_data',
			27   =>    'manage_nbd_setting'
        );
        $admin_role = get_role('administrator');
        if (null != $admin_role) {
            foreach ($capabilities as $cap){
                $admin_role->add_cap($cap);    
            }     
        }
        $shop_manager_role = get_role('shop_manager');
        if (null != $shop_manager_role) {
            foreach ($capabilities as $cap){
                $shop_manager_role->add_cap($cap);    
            }           
        }
        $shop_capabilities = $shop_manager_role->capabilities;
        $shop_capabilities['export_nbd_font'] = false;
        $shop_capabilities['export_nbd_art'] = false;
        $shop_capabilities['export_nbd_product'] = false;
        $shop_capabilities['export_nbd_language'] = false;        
        $shop_capabilities['import_nbd_font'] = false;
        $shop_capabilities['import_nbd_art'] = false;
        $shop_capabilities['import_nbd_product'] = false;
        $shop_capabilities['import_nbd_language'] = false;     
        $shop_capabilities['edit_nbd_font'] = false;
        $shop_capabilities['edit_nbd_language'] = false;
        $shop_capabilities['edit_nbd_template'] = false;
        $shop_capabilities['edit_nbd_art'] = false;        
        $shop_capabilities['delete_nbd_font'] = false;
        $shop_capabilities['delete_nbd_art'] = false;
        $shop_capabilities['delete_nbd_language'] = false;
        $shop_capabilities['delete_nbd_template'] = false;          
        $shop_capabilities['update_nbd_data'] = false;          
        $nbd_viewer = add_role('nbdesigner_viewer', 'NBDesigner Viewer', $shop_manager_role->capabilities);
        if (null === $nbd_viewer) {
            $nbd_viewer = get_role('nbdesigner_viewer');           
            $nbd_viewer->remove_cap('export_nbd_font');
            $nbd_viewer->remove_cap('export_nbd_art');
            $nbd_viewer->remove_cap('export_nbd_product');
            $nbd_viewer->remove_cap('export_nbd_language');
            $nbd_viewer->remove_cap('import_nbd_font');
            $nbd_viewer->remove_cap('import_nbd_art');
            $nbd_viewer->remove_cap('import_nbd_product');
            $nbd_viewer->remove_cap('import_nbd_language');
            $nbd_viewer->remove_cap('edit_nbd_font');
            $nbd_viewer->remove_cap('edit_nbd_art');
            $nbd_viewer->remove_cap('edit_nbd_language');
            $nbd_viewer->remove_cap('edit_nbd_template');
            $nbd_viewer->remove_cap('delete_nbd_font');
            $nbd_viewer->remove_cap('delete_nbd_art');
            $nbd_viewer->remove_cap('delete_nbd_language');
            $nbd_viewer->remove_cap('delete_nbd_template');
            $nbd_viewer->remove_cap('update_nbd_data');
        }      
    }
    /**
     * Insert table templates
     * @since 1.5.0
     * 
     * @global type $wpdb
     * @param int $product_id
     * @param varchar $folder
     * @param int $priority
     * @param int $publish
     * @param int $private
     */
    private function nbdesigner_insert_table_templates($product_id, $folder, $priority, $publish = 1, $private = 0){
        global $wpdb;
        $created_date = new DateTime();
        $user_id = wp_get_current_user()->ID;
        $table_name =  $wpdb->prefix . 'nbdesigner_templates';
        $wpdb->insert($table_name, array(
            'product_id' => $product_id,
            'folder' => $folder,
            'user_id' => $user_id,
            'created_date' => $created_date->format('Y-m-d H:i:s'),
            'publish' => $publish,
            'private' => $private,
            'priority' => $priority
        ));
        return true;
    }
    /**
     * Update table templates
     * @since 1.5.0
     * 
     * @global type $wpdb
     * @param int $product_id product id
     * @param char $folder folder name
     * @param int $priority priority ex: primary, extra
     * @param int $publish publish or unpublish
     * @param int $private private or publish
     * @return boolean
     */
    private function nbdesigner_update_table_templates($product_id, $folder, $priority = '', $publish = '', $private = ''){
        global $wpdb;
        $data = array();
        if($priority !== '') $data['priority'] = $priority;
        if($publish !== '') $data['publish'] = $publish;
        if($private !== '') $data['private'] = $private;
        $wpdb->update( $wpdb->prefix . 'nbdesigner_templates', $data, array( 'product_id' => $product_id, 'folder' => $folder ) );
        return true;
    }
    private function update_template_hit($pid, $folder){
        global $wpdb;
        $table_name = $wpdb->prefix . 'nbdesigner_templates';
        $sql = "SELECT * FROM $table_name WHERE product_id = '$pid' AND folder = '$folder' ORDER BY created_date DESC";
        $tem =  $wpdb->get_results($sql, ARRAY_A);
        $data = array();
        if(count($tem)){
            if($tem[0]['hit']){
                $data['hit'] = $tem[0]['hit'] + 1; 
            }else{
                $data['hit'] = 1;
            }             
            $wpdb->update( $wpdb->prefix . 'nbdesigner_templates', $data, array( 'product_id' => $pid, 'folder' => $folder ) );
        }   
        return true;        
    }
    private function nbdesigner_delete_record_templates($product_id, $folder){
        global $wpdb;
        $wpdb->delete( $wpdb->prefix . 'nbdesigner_templates', array( 'product_id' => $product_id, 'folder' => $folder ) );
        return true;
    }
    private function nbdesigner_get_template_from_db($product_id, $status = 'publish'){
        global $wpdb;
        $table_name = $wpdb->prefix . 'nbdesigner_templates';
        if($status == 'unpublish'){
            $sql = "SELECT * FROM $table_name WHERE product_id = '$product_id' AND publish = 0 ORDER BY created_date DESC";
        }else if($status == 'publish'){
            $sql = "SELECT * FROM $table_name WHERE product_id = '$product_id' AND publish = 1 ORDER BY created_date DESC";
        }else {
            $sql = "SELECT * FROM $table_name WHERE product_id = '$product_id' AND private = 1 ORDER BY created_date DESC";
        }
        $results = $wpdb->get_results($sql);
        return $results;
    }
    public function nbdesigner_end_session() {
        if(isset($_SESSION['nbdesigner'])) unset($_SESSION['nbdesigner']);
    }
    public function nbdesigner_save_design_to_image2($data, $path, $pid, $vid) {
        $links = array();
        $mes = array();
        $path_thumb = $path . '/thumbs';
        if(file_exists($path)){
            $this->nbdesigner_delete_folder($path);
        }
        if (!file_exists($path)) {
            if (wp_mkdir_p($path)) {
                if (!file_exists($path_thumb))
                    if (!wp_mkdir_p($path_thumb)) {
                        $mes[] = __('Your server not allow creat folder', 'nbdesigner');
                    }
            } else {
                $mes[] = __('Your server not allow creat folder', 'nbdesigner');
            }
        }   
        if($vid > 0){
            $configs = unserialize(get_post_meta($vid, '_designer_setting'.$vid, true)); 
            if (!isset($configs[0])){
                $configs = unserialize(get_post_meta($pid, '_designer_setting', true)); 
            }            
        }else {
            $configs = unserialize(get_post_meta($pid, '_designer_setting', true)); 
        }
        foreach ($data as $key => $val) {
            if($key == 'design'){
                $full_name = $path . '/design.json';
            }else if($key == 'used_font'){
                $full_name = $path . '/used_font.json';
            }else if($key == 'config'){
                $full_name = $path . '/config.json';
            }else{
                $full_name = $path . '/' . $key . '.png';
                $_key = explode('_', $key);                
            }
            if (move_uploaded_file($val["tmp_name"],$full_name)) {
                if($key != 'used_font' && $key != 'config' && $key != 'design'){
                    $image = wp_get_image_editor($full_name); 
                    $_width = nbdesigner_get_option('nbdesigner_thumbnail_width');
                    $_height = nbdesigner_get_option('nbdesigner_thumbnail_height');
                    $_quality = nbdesigner_get_option('nbdesigner_thumbnail_quality');       
                    if($configs[$_key[1]]['area_design_width'] > $configs[$_key[1]]['area_design_height']){
                        $_height = round($_width * $configs[$_key[1]]['area_design_height'] / $configs[$_key[1]]['area_design_width']);
                    }else {
                        $_width = round($_height * $configs[$_key[1]]['area_design_width'] / $configs[$_key[1]]['area_design_height']);
                    }
                    if (!is_wp_error($image)) {
                        $thumb_file = $path_thumb . '/' . $key . '.png';
                        $image->resize($_width, $_height, 1);
                        $image->set_quality($_quality);
                        if ($image->save($thumb_file, 'image/png'))
                            $links[$key] = Nbdesigner_IO::secret_image_url($thumb_file);
                    }                    
                }
            } else {
                $mes[] = __('Your server not allow writable file', 'nbdesigner');
            }                
        }
        return array('link' => $links, 'mes' => $mes);
    }    
    public function nbdesigner_save_webcam_image(){
        if (!wp_verify_nonce($_POST['nonce'], 'save-design')) {
            die('Security error');
        } 
        $result = array();
        $img = $_POST['image'];
        $data = base64_decode($img);
        $full_name = $this->plugin_path_data . 'temp/' . time() .'.png';
        $success = file_put_contents($full_name, $data);
        if($success){
            $result['flag'] = 'success';
            $up = wp_upload_dir();			
            $base_path = $up['baseurl'];
            $mid_path = 'nbdesigner/temp/';
            $name = basename($full_name);
            $url = $base_path.'/'.$mid_path.$name;            
            $result['url'] = $url;
        }else{
            $result['flag'] = $full_name;
        }
        echo json_encode($result);
        wp_die();        
    }
    private function nbdesigner_create_thumbnail_design($from_path, $to_path, $pid, $_width = 500, $_height = 500){
        $configs = unserialize(get_post_meta($pid, '_designer_setting', true));        
        $path_preview = $to_path;
        if(!file_exists($path_preview)){
            wp_mkdir_p($path_preview);
        }
        foreach ($configs as $key => $val){
            $p_img = $from_path . '/frame_' . $key . '.png';
            if(file_exists($p_img)){
                $image_design = $this->nbdesigner_resize_imagepng($p_img, $val["area_design_width"], $val["area_design_height"]);
                $image_product_ext = pathinfo($val["img_src"]);
                if($val["bg_type"] == 'image'){
                    if($image_product_ext['extension'] == "png"){
                        $image_product = $this->nbdesigner_resize_imagepng($val["img_src"], $val["img_src_width"], $val["img_src_height"]);
                    }else{
                        $image_product = $this->nbdesigner_resize_imagejpg($val["img_src"], $val["img_src_width"], $val["img_src_height"]);
                    }     
                }
                $image = imagecreatetruecolor($_width, $_height);
                imagesavealpha($image, true);
                $color = imagecolorallocatealpha($image, 255, 255, 255, 127);
                imagefill($image, 0, 0, $color);
                if($val["bg_type"] == 'image'){
                    imagecopy($image, $image_product, $val["img_src_left"], $val["img_src_top"], 0, 0, $val["img_src_width"], $val["img_src_height"]);
                } else if($val["bg_type"] == 'color'){
                    $_color = hex_code_to_rgb($val["bg_color_value"]);
                    $color = imagecolorallocate($image, $_color[0], $_color[1], $_color[2]);
                    imagefilledrectangle($image, $val["img_src_left"], $val["img_src_top"], $val["img_src_left"] + $val["img_src_width"], $val["img_src_top"] + $val["img_src_height"], $color);
                }
                imagecopy($image, $image_design, $val["area_design_left"], $val["area_design_top"], 0, 0, $val["area_design_width"], $val["area_design_height"]);
                imagepng($image, $path_preview. '/frame_' . $key . '.png');
                imagedestroy($image);
                imagedestroy($image_design);
            }
        }
    }
    private function nbdesigner_resize_imagepng($file, $w, $h){
        list($width, $height) = getimagesize($file);
        $src = imagecreatefrompng($file);
        $dst = imagecreatetruecolor($w, $h);
        imagesavealpha($dst, true);
        $color = imagecolorallocatealpha($dst, 255, 255, 255, 127);
        imagefill($dst, 0, 0, $color);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
        imagedestroy($src);
        return $dst;        
    }
    private function nbdesigner_resize_imagejpg($file, $w, $h) {
       list($width, $height) = getimagesize($file);
       $src = imagecreatefromjpeg($file);
       $dst = imagecreatetruecolor($w, $h);
       imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
       imagedestroy($src);
       return $dst;
    }    
    public function nbdesigner_save_data_to_image($path, $data) {
        if (!$fp = fopen($path, 'w')) {
            return FALSE;
        }
        flock($fp, LOCK_EX);
        fwrite($fp, $data);
        flock($fp, LOCK_UN);
        fclose($fp);
        return TRUE;
    }
    public function nbdesigner_create_secret_image_url($file_path) {
        $type = pathinfo($file_path, PATHINFO_EXTENSION);
        $data = file_get_contents($file_path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);   
        return $base64;
    }
    public function nbdesigner_registration_save($user_id){
        require_once ABSPATH . 'wp-admin/includes/file.php';
        global $wpdb;
        $uid = $user_id;
        $sid = session_id();
        $folder_path = $this->plugin_path_data . 'designs/' . $sid . '/nb_order';
        $user_path = $this->plugin_path_data . 'designs/' . $uid . '/nb_order';
        $user_path_old = $this->plugin_path_data . 'designs/' . $uid . '/nb_order_old';   
        if (file_exists($user_path_old)) {
            $this->nbdesigner_delete_folder($user_path_old);
        }else{
            if (file_exists($user_path)) {
                rename($user_path, $user_path_old);
            }            
        }
        if (!file_exists($user_path)) {
            wp_mkdir_p($user_path);
        }
        if (file_exists($folder_path)) {
            $this->nbdesigner_copy_dir($folder_path, $user_path);
            $this->nbdesigner_delete_folder($folder_path);
        }
        if (isset($_SESSION['nbdesigner'])) {
            foreach ($_SESSION['nbdesigner'] as $key => $designs) {
                $arr = json_decode($designs);
                $new_sess = array();
                foreach ($arr as $img) {
                    $old = '/' . $sid . '/';
                    $new = '/' . $uid . '/';
                    $img = str_replace($old, $new, $img);
                    $new_sess[] = $img;
                }
                $_SESSION['nbdesigner'][$key] = json_encode($new_sess);
            }
        }
    }
    public function nbdesigner_change_folder_design($user_login, $user) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        global $wpdb;
        $uid = $user->ID;
        $sid = session_id();
        $folder_path = $this->plugin_path_data . 'designs/' . $sid . '/nb_order';
        $user_path = $this->plugin_path_data . 'designs/' . $uid . '/nb_order';
        $user_path_old = $this->plugin_path_data . 'designs/' . $uid . '/nb_order_old';   
        if (file_exists($user_path_old)) {
            $this->nbdesigner_delete_folder($user_path_old);
        }else{
            if (file_exists($user_path)) {
                rename($user_path, $user_path_old);
            }            
        }
        if (!file_exists($user_path)) {
            wp_mkdir_p($user_path);
        }
        if (file_exists($folder_path)) {
            $this->nbdesigner_copy_dir($folder_path, $user_path);
            $this->nbdesigner_delete_folder($folder_path);
        }
        if (isset($_SESSION['nbdesigner'])) {
            foreach ($_SESSION['nbdesigner'] as $key => $designs) {
                $arr = json_decode($designs);
                $new_sess = array();
                foreach ($arr as $img) {
                    $old = '/' . $sid . '/';
                    $new = '/' . $uid . '/';
                    $img = str_replace($old, $new, $img);
                    $new_sess[] = $img;
                }
                $_SESSION['nbdesigner'][$key] = json_encode($new_sess);
            }
        }
    }
    public function nbdesigner_change_foler_after_order($order_id) {
        global $woocommerce;
        $items = $woocommerce->cart->get_cart();
        foreach($items as $item => $values){
            $data = WC()->session->get($item . '_nbdesigner');
            if($data == 'has_design'){
                WC()->session->__unset($item . '_nbdesigner'); 
            }
        }
        $this->nbdesigner_end_session();
        $uid = get_current_user_id();
        $sid = session_id();
        $iid = ($uid > 0 ) ? $uid : $sid;
        $path = $this->plugin_path_data . 'designs/' . $iid . '/nb_order';
        $new_path = $this->plugin_path_data . 'designs/' . $iid . '/' . $order_id;
        if (file_exists($path)){
            rename($path, $new_path);
        }     
        $order = new WC_Order($order_id);
        $products = $order->get_items();
        $order_has_design = false;
        foreach($products as $order_item_id => $product){
            $has_design = wc_get_order_item_meta($order_item_id, '_nbdesigner_has_design');
            if($has_design == 'has_design') {
                $order_has_design = true;
                wc_add_order_item_meta($order_item_id, '_nbdesign_order', $order_id);
                wc_add_order_item_meta($order_item_id, '_nbdesign_order_item_id', $order_item_id);
            }
        }
        if($order_has_design){
            update_post_meta($order_id, '_nbdesigner_order_has_design', 'has_design');
            update_post_meta($order_id, '_nbdesigner_version_design', NBDESIGNER_VERSION);
            update_post_meta($order_id, '_nbdesigner_order_userid', $iid);
            add_post_meta($order_id, '_nbdesigner_order_changed', 1);
        } 
    }
    public function nbdesigner_copy_dir($src, $dst) {
        if (file_exists($dst)) $this->nbdesigner_delete_folder($dst);
        if (is_dir($src)) {
            wp_mkdir_p($dst);
            $files = scandir($src);
            foreach ($files as $file){
                if ($file != "." && $file != "..") $this->nbdesigner_copy_dir("$src/$file", "$dst/$file");
            }
        } else if (file_exists($src)) copy($src, $dst);
    }
    public function nbdesigner_list_thumb($path, $level = 2) {
        $list = array();
        $_list = $this->nbdesigner_list_files($path, $level);
        $list = preg_grep('/\.(jpg|jpeg|png|gif)(?:[\?\#].*)?$/i', $_list);
        return $list;
    }
    public function nbdesigner_list_files($folder = '', $levels = 100) {
        if (empty($folder))
            return false;
        if (!$levels)
            return false;
        $files = array();
        if ($dir = @opendir($folder)) {
            while (($file = readdir($dir) ) !== false) {
                if (in_array($file, array('.', '..')))
                    continue;
                if (is_dir($folder . '/' . $file)) {
                    $files2 = $this->nbdesigner_list_files($folder . '/' . $file, $levels - 1);
                    if ($files2)
                        $files = array_merge($files, $files2);
                    else
                        $files[] = $folder . '/' . $file . '/';
                } else {
                    $files[] = $folder . '/' . $file;
                }
            }
        }
        @closedir($dir);
        return $files;
    }
    public function nbdesigner_delete_folder($path) {
        if (is_dir($path) === true) {
            $files = array_diff(scandir($path), array('.', '..'));
            foreach ($files as $file) {
                $this->nbdesigner_delete_folder(realpath($path) . '/' . $file);
            }
            return rmdir($path);
        } else if (is_file($path) === true) {
            return unlink($path);
        }
        return false;
    }
    public function nbdesigner_display_posts_design($column, $post_id) {
        if ($column == 'design') {
            $is_design = get_post_meta($post_id, '_nbdesigner_enable', true);
            echo '<input type="checkbox" disabled', ( $is_design ? ' checked' : ''), '/>';
        }
    }
    public function nbdesigner_add_design_column($columns) {
        return array_merge($columns, array('design' => __('Design', 'nbdesigner')));
    }
    public function nbdesigner_render_cart($title = null, $cart_item = null, $cart_item_key = null) {
        if ($cart_item_key && is_cart()) {
            $data = WC()->session->get($cart_item_key . '_nbdesigner');         
            $_show_design = nbdesigner_get_option('nbdesigner_show_in_cart');
            if ($data == 'has_design' && $_show_design == 'yes') {
                //$product_id = $cart_item['product_id'];
                //@since 1.5.0 data design store in session with key $cart_item_key instead product id
                //$data_design = $_SESSION['nbdesigner']['nbdesigner_' . $product_id];
                $data_design = $_SESSION['nbdesigner'][$cart_item_key];
                $html = $title . '<br /><div>';
                $list = json_decode($data_design);
                foreach ($list as $img) {
                    $src = $this->nbdesigner_create_secret_image_url($img);
                    $html .= '<img style="max-width: 60px; max-height: 60px; border-radius: 3px; border: 1px solid #ddd; margin-top: 5px; margin-right: 5px; display: inline-block;" src="' . $src . '"/>';
                }
                $html .= '</div>';
                echo $html;
            } else {
                echo $title;
            }
        }else{
            echo $title;
        }
    }
    /**
     * Declare item cart has design
     * @since 1.0.0
     * 
     * @param text $cart_item_key 
     * @param int $product_id
     */
    public function nbdesigner_add_custom_data_design($cart_item_key, $product_id) {        
        $sid = session_id();
        $uid = get_current_user_id();
        $iid = ($uid > 0) ? $uid : $sid;
        if (isset($_SESSION['nbdesigner']) && isset($_SESSION['nbdesigner']['nbdesigner_' . $product_id])) {            
            $this->nbdesigner_change_folder_after_add_to_cart($cart_item_key, $product_id);
            WC()->session->set($cart_item_key . '_nbdesigner', 'has_design');
        }
    }
    /**
     * Copy folder design after add to cart.
     * Store new path image design into session.
     * Fix bug multi design for same product (or variantion)
     * @since 1.5.0
     * 
     * @param text $cart_item_key
     * @param int $product_id
     * @return boolean
     */
    private function nbdesigner_change_folder_after_add_to_cart($cart_item_key, $product_id){ 
        $uid = get_current_user_id();
        $sid = session_id();
        $iid = ($uid > 0) ? $uid : $sid;
        $old_path = $this->plugin_path_data . 'designs/' . $iid . '/nb_order/'.$product_id;
        $new_path = $this->plugin_path_data . 'designs/' . $iid . '/nb_order/'.$cart_item_key;
        $this->nbdesigner_copy_dir($old_path, $new_path);
        $path = $new_path . '/thumbs';
        $_SESSION['nbdesigner'][$cart_item_key] = json_encode($this->nbdesigner_list_thumb($path));
    }
    public function nbdesigner_remove_cart_item_design($removed_cart_item_key, $instance){
        $line_item = $instance->removed_cart_contents[ $removed_cart_item_key ];
        $product_id = $line_item[ 'product_id' ];      
        if(isset($_SESSION['nbdesigner']['nbdesigner_' . $product_id])){
            unset($_SESSION['nbdesigner']['nbdesigner_' . $product_id]);   
            WC()->session->__unset($removed_cart_item_key . '_nbdesigner');          
        }     
    }
    public function nbdesigner_add_new_order_item($item_id, $item, $order_id){
        if (WC()->session->__isset($item->legacy_cart_item_key . '_nbdesigner')) {
            wc_add_order_item_meta($item_id, "_nbdesigner_has_design", WC()->session->get($item->legacy_cart_item_key . '_nbdesigner'));
            wc_add_order_item_meta($item_id, "_nbdesigner_folder_design", $item->legacy_cart_item_key);
        }
    } 	
    public function nbdesigner_add_order_design_data($item_id, $values, $cart_item_key) {
        if (WC()->session->__isset($cart_item_key . '_nbdesigner')) {
            wc_add_order_item_meta($item_id, "_nbdesigner_has_design", WC()->session->get($cart_item_key . '_nbdesigner'));
            wc_add_order_item_meta($item_id, "_nbdesigner_folder_design", $cart_item_key);
        }
    }
    public function nbdesigner_design_approve(){
        check_admin_referer('approve-designs', '_nbdesigner_approve_nonce');	
        $order_id = $_POST['nbdesigner_design_order_id'];
        if(isset($_POST['_nbdesigner_design_file']))
            $_design_file = $_POST['_nbdesigner_design_file'];       
        $_design_action = $_POST['nbdesigner_order_file_approve'];       
        $response['mes'] = '';       
        if (is_numeric($order_id) && isset($_design_file) && is_array($_design_file)) {
            $design_data = unserialize(get_post_meta($order_id, '_nbdesigner_design_file', true));                  
            if(is_array($design_data)){
                foreach($_design_file as $val){    
                    $check = false;
                    foreach($design_data as $key => $status){
                        //@since 1.5.0 store into order_item_id 
                        //$_key = str_replace('nbds_', '', trim($key));
                        $_key = str_replace('nbds_', '', trim($key));
                        if($_key == $val){
                            $design_data[$key] = $_design_action;  
                            $check = true;
                        }
                    }   
                    if(!$check) $design_data['nbds_'.$val] = $_design_action; 
                }
            }else{
                $design_data = array();
                foreach ($_design_file as $val){
                    $design_data['nbds_'.$val] = $_design_action;
                }
            }             
            $design_data = serialize($design_data);       
            if (update_post_meta($order_id, '_nbdesigner_design_file', $design_data)){
                update_post_meta($order_id, '_nbdesigner_order_changed', 0);
                $response['mes'] = 'success';
            }else{
                update_post_meta($order_id, '_nbdesigner_order_changed', 0);
                $response['mes'] = __('You don\'t change anything? Or an error occured saving the data, please refresh this page and check if changes took place.', 'nbdesigner');
            }
        } else if(!isset($_design_file) || !is_array($_design_file)){
            $response['mes'] = __('You haven\'t chosen a item.', 'nbdesigner');
        }
        echo json_encode($response);
        wp_die();
    }
    public function nbdesigner_design_order_email(){
        check_admin_referer('approve-design-email', '_nbdesigner_design_email_nonce');	
        $response['success'] = 0;
        if (empty($_POST['nbdesigner_design_email_order_content'])) {
            $response['error'] = __('The reason cannot be empty', 'nbdesigner');        
        } elseif (!is_numeric($_POST['nbdesigner_design_email_order_id'])) {
            $response['error'] = __('Error while sending mail', 'nbdesigner');
        } 
        if (empty($response['error'])) {
            $message = $_POST['nbdesigner_design_email_order_content'];
            $order = new WC_Order($_POST['nbdesigner_design_email_order_id']);  
            $reason = ($_POST['nbdesigner_design_email_reason'] == 'approved')?__('Your design accepted', 'nbdesigner'): 'Your design rejected';
            $send_email = $this->nbdesigner_send_email($order, $reason, $message);
            if ($send_email)
                $response['success'] = 1;
            else
                $response['error'] = __('Error while sending mail', 'nbdesigner');            
        }
        echo json_encode($response);
        wp_die();        
    }
    public function nbdesigner_send_email($order, $reason, $message){
        global $woocommerce;
        $user_email = $order->billing_email;
        if (!empty($user_email)) {
            $mailer = $woocommerce->mailer();
            ob_start();
            wc_get_template('emails/nbdesigner-approve-order-design.php', array(
                'plugin_id' => 'nbdesigner',
                'order' => $order,
                'reason' => $reason,
                'message' => $message,
                'my_order_url' => $order->get_view_order_url(),
            ));
            $body = ob_get_clean();
            $subject = $reason . ' - Order ' . $order->get_order_number();	            
            $mailer->send($user_email, $subject, $body);
            return true;
        } else {
            return false;
        }
    }
    public function nbdesigner_locate_plugin_template($template, $template_name, $template_path){
        global $woocommerce;
        $_template = $template;
        if ( ! $template_path ) $template_path = $woocommerce->template_url;
        $plugin_path  = NBDESIGNER_PLUGIN_DIR . 'templates/';
        $template = locate_template(array(
            $template_path . $template_name,
            $template_name
        ));
        if ( ! $template && file_exists( $plugin_path . $template_name ) )
            $template = $plugin_path . $template_name;
        if ( ! $template )
          $template = $_template;
        return $template;
    }
    public function nbdesigner_order_item_name($item_name, $item){    
        $order_id = 0;
        $_nbdesign_order_item_id = 0;
        if(is_woo_v3()){
            if(isset($item["item_meta"]["_nbdesign_order"])) $order_id =  $item["item_meta"]["_nbdesign_order"];
            if(isset($item["item_meta"]["_nbdesign_order_item_id"])) $_nbdesign_order_item_id =  $item["item_meta"]["_nbdesign_order_item_id"];
        }else{           
            if(isset($item["item_meta"]["_nbdesign_order"])) $order_id =  $item["item_meta"]["_nbdesign_order"][0];
            if(isset($item["item_meta"]["_nbdesign_order_item_id"])) $_nbdesign_order_item_id =  $item["item_meta"]["_nbdesign_order_item_id"][0];            
        }
        $_product = wc_get_product($item["product_id"]);   
        $_show_design_order = nbdesigner_get_option('nbdesigner_show_in_order');
        if($order_id && ($_show_design_order == 'yes')){ 
            $notice = '';
            $html = '';
            $oiid = '';
            $design_data = unserialize(get_post_meta($order_id, '_nbdesigner_design_file', true));
            $index = 'nbds_'.$item["product_id"];
            if($_nbdesign_order_item_id) $index = 'nbds_'.$_nbdesign_order_item_id;
            $folder = false;
            if(isset($item["item_meta"]["_nbdesigner_folder_design"])){
                if(is_woo_v3()){
                    $folder = $item["item_meta"]["_nbdesigner_folder_design"];
                }else{
                    $folder = $item["item_meta"]["_nbdesigner_folder_design"][0];
                } 
                if($_nbdesign_order_item_id) $oiid = $_nbdesign_order_item_id;
            }            
            $redesign_url = getUrlPageNBD('redesign');
            if($folder){
                $link_redesign = $redesign_url.'?product_id='.$item["product_id"].'&orderid='.$order_id.'&folder='.$folder.'&oiid='.$oiid;
            }else{
                $link_redesign = $redesign_url.'?product_id='.$item["product_id"].'&orderid='.$order_id;
            }  
            if(isset($design_data[$index]) && ($design_data[$index] == 'decline')){
                $notice = "<small style='color:red;'>". __('(Rejected! Click ', 'web-to-print-online-designer')."<a href='".$link_redesign."' target='_blank'>". __('here ', 'web-to-print-online-designer'). "</a>". __(' to design again', 'web-to-print-online-designer')."!)</small>";
            }
            if(isset($design_data[$index]) && ($design_data[$index] == 'accept')){
                $notice = __('<small> (Approved!)</small>', 'web-to-print-online-designer');
            }
            if(isset($item["item_meta"]["_nbdesigner_has_design"])) $has_design =  $item["item_meta"]["_nbdesigner_has_design"];
            if(isset($has_design)){               
                $uid = get_current_user_id();
                $sid = session_id();
                $iid = ($uid > 0) ? $uid : $sid;
                //@since 1.5.0 - fix bug multiple design same product
                $path = $this->plugin_path_data. 'designs/'. $iid . '/' .$order_id. '/' . $item["product_id"] . '/thumbs';	
                if($folder){
                    $path = $this->plugin_path_data. 'designs/'. $iid . '/' .$order_id. '/' . $folder . '/thumbs';
                }                     
                if(file_exists($path)){                    
                    $images = $this->nbdesigner_list_thumb($path);       
                    if(is_array($images)){
                        $html = '<br />';                        
                        foreach ($images as $img){                           
                            $src = $this->nbdesigner_create_secret_image_url($img);                            
                            $html .= '<img style="width: 60px; border: 1px solid #ddd; border-radius: 3px; display: inline-block !important; margin-left: 5px; margin-bottom: 5px;" src="'.$src.'" />';
                        }
                    }
                }
            }
            $link = get_permalink( $item['product_id']);
            /* $link = add_query_arg(array('orderid' => $order_id[0]), $_link); */
            $item_name = sprintf( '<a href="%s">%s</a>&times;<strong class="product-quantity">%s</strong>%s<br />%s', $link, $item['name'], $item['qty'], $html, $notice );
        }else{
            if($_product->is_visible()){
                $item_name = sprintf( '<a href="%s">%s</a>', get_permalink( $item['product_id'] ), $item['name'] );
            }
            else {
                $item_name = $item['name'];
            }
        }      
        return $item_name;
    }
    public function nbdesigner_order_item_quantity_html($strong, $item){
        $_show_design_order = nbdesigner_get_option('nbdesigner_show_in_order');
        if($_show_design_order == 'yes'){
            if(isset($item["item_meta"]["_nbdesigner_has_design"])) $has_design =  $item["item_meta"]["_nbdesigner_has_design"];
            if(isset($has_design)){ 
                return '';
            }
        }
        return ' <strong class="product-quantity">' . sprintf( '&times; %s', $item['qty'] ) . '</strong>';
    }
    public function nbdesigner_hidden_custom_order_item_metada($order_items){
        $order_items[] = '_nbdesigner_has_design';
        $order_items[] = '_nbdesigner_folder_design';
        $order_items[] = '_nbdesigner_version_design';
        $order_items[] = '_nbdesign_order';
        $order_items[] = '_nbdesign_order_item_id';
        return $order_items;
    }
    public function nbdesigner_customer_upload(){       
        if (!wp_verify_nonce($_POST['nonce'], 'save-design')) {
            die('Security error');
        } 
        $allow_extension = array('jpg','jpeg','png','gif');
        $max_size = nbdesigner_get_option('nbdesigner_maxsize_upload');
        $allow_max_size = $max_size * 1024 * 1024;
        $result =   true;
        $res = array();
        $size   =   $_FILES['file']["size"];
        $name   =   $_FILES['file']["name"];    
        $ext = $this->nbdesigner_get_extension($name);
        $new_name = strtotime("now").substr(md5(rand(1111,9999)),0,8).'.'.$ext;        
        if(empty($name)) {
            $result = false;
            $res['mes'] = __('Error occurred with file upload!', 'nbdesigner');            
        }
        if($size > $allow_max_size){
            $result = false;
            $res['mes'] = __('Too large file !', 'nbdesigner');                
        }
        $check = Nbdesigner_IO::checkFileType($name, $allow_extension);
        if(!$check){
            $result = false;
            $res['mes'] = __('Invalid file format!', 'nbdesigner');
        }    
        $path = Nbdesigner_IO::create_image_path(NBDESIGNER_TEMP_DIR, $new_name);
        if($result){
            if(move_uploaded_file($_FILES['file']["tmp_name"],$path['full_path'])){
                $res['mes'] = __('Upload success !', 'nbdesigner');       
            }else{
                $result = false;
                $res['mes'] = __('Error occurred with file upload!', 'nbdesigner');            
            }                     
        }
        if($result){
            $res['src'] = NBDESIGNER_TEMP_URL.$path['date_path'];
            $res['flag'] = 1;
        }else{
            $res['flag'] = 0;
        }	        
        echo json_encode($res);
        wp_die();
    }
    public function nbdesigner_get_qrcode(){
        $result = array();
        $result['flag'] = 0;
        if (!wp_verify_nonce($_REQUEST['nonce'], 'save-design')) {
            die('Security error');
        } 
        if(isset($_REQUEST['data'])){
            $content = $_REQUEST['data'];
            include_once NBDESIGNER_PLUGIN_DIR.'includes/class-qrcode.php';
            $qr = new Nbdesigner_Qrcode();
            $qr->setText($content);
            $image = $qr->getImage(500);
            $file_name = strtotime("now") . '.png';
            $full_name = $this->plugin_path_data .'temp/'. $file_name;
            if($this->nbdesigner_save_data_to_image($full_name, $image)){
                $result['flag'] = 1;
                $result['src'] =  content_url().'/uploads/nbdesigner/temp/'.$file_name;
            };          
        }
        echo json_encode($result);
        wp_die();
    }
    public function nbdesigner_get_facebook_photo(){
        if (!wp_verify_nonce($_POST['nonce'], 'save-design')) {
            die('Security error');
        }        
        $result = array();
        $_accessToken = $_POST['accessToken'];
        require_once NBDESIGNER_PLUGIN_DIR.'includes/class.nbdesigner.facebook.php';
        echo json_encode($result);
        wp_die();
    }
    public function nbdesigner_get_art(){
        if (!wp_verify_nonce($_REQUEST['nonce'], 'nbdesigner-get-data')) {
            die('Security error');
        }   
        $result = array();
        $path_cat = $this->plugin_path_data. 'art_cat.json';
        $path_art = $this->plugin_path_data. 'arts.json';
        $result['flag'] = 1;
        $result['cat'] = $this->nbdesigner_read_json_setting($path_cat);
        $result['arts'] = $this->nbdesigner_read_json_setting($path_art);	        
        echo json_encode($result);
        wp_die();        
    }
    public function nbdesigner_get_font(){ 	        
        if (!wp_verify_nonce($_REQUEST['nonce'], 'nbdesigner-get-data')) {
            die('Security error');
        }   
        $result = array();
        $path_cat = $this->plugin_path_data. 'font_cat.json';
        $path_font = $this->plugin_path_data. 'fonts.json';
        $path_google_font = $this->plugin_path_data. 'googlefonts.json';
        $result['flag'] = 1;
        $result['cat'] = $this->nbdesigner_read_json_setting($path_cat);
        $result['fonts'] = $this->nbdesigner_read_json_setting($path_font);	        
        $result['google_font'] = $this->nbdesigner_read_json_setting($path_google_font);	        
        echo json_encode($result);
        wp_die();        
    }
    public function nbdesigner_get_pattern(){ 	        
        if (!wp_verify_nonce($_REQUEST['nonce'], 'nbdesigner-get-data')) {
            die('Security error');
        }   
        $result = array();
        $path = NBDESIGNER_PLUGIN_DIR. 'data/pattern.json';
        $result['flag'] = 1;
        $result['data'] = $this->nbdesigner_read_json_setting($path);	        
        echo json_encode($result);
        wp_die();        
    }    
    private function zip_files_and_download($file_names, $archive_file_name, $nameZip){
        if(file_exists($archive_file_name)){
            unlink($archive_file_name);
        }        
        if (class_exists('ZipArchive')) {
            $zip = new ZipArchive();
            if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
              exit("cannot open <$archive_file_name>\n");
            }
            foreach($file_names as $file)
            {
                $path_arr = explode('/', $file);
                $name = $path_arr[count($path_arr) - 2].'_'.$path_arr[count($path_arr) - 1];                
                $zip->addFile($file, $name);
            }
            $zip->close();
        }else{         
            require_once(ABSPATH . 'wp-admin/includes/class-pclzip.php');
            $archive = new PclZip($archive_file_name);
            foreach($file_names as $file)
            {
                $path_arr = explode('/', $file);
                $dir = dirname($file).'/';                
                $archive->add($file, PCLZIP_OPT_REMOVE_PATH, $dir, PCLZIP_OPT_ADD_PATH, $path_arr[count($path_arr) - 2]);               
            }            
        }
        if ( !is_file( $archive_file_name ) ){
            header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
            exit;
        } elseif ( !is_readable( $archive_file_name ) ){
            header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
            exit;
        } else {
            header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
            header("Pragma: public");
            header("Expires: 0");
            header("Accept-Ranges: bytes");
            header("Connection: keep-alive");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: public");
            header("Content-type: application/zip");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=\"".$nameZip."\"");
            header('Content-Length: '.filesize($archive_file_name));
            header("Content-Transfer-Encoding: binary");
            ob_clean();
            @readfile($archive_file_name);
            exit;    
        }      
    }
    /**
     * 
     * Create pages: Re-design, Admin-design, Studio if they're not exist.
     * 
     * @global type $wpdb
     */
    public static function nbdesigner_add_custom_page(){
	global $wpdb;
	$check = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name='".NBDESIGNER_PAGE_REDESIGN."'");	        
        if ($check == ''){        
            $post = array(
                'post_name' => NBDESIGNER_PAGE_REDESIGN,
                'post_status' => 'publish',
                'post_title' => __('Customer re-design product', 'nbdesigner'),
                'post_type' => 'page',
                'post_date' => date('Y-m-d H:i:s')
            );      
            $page = wp_insert_post($post, false);	
        }
        $admindesign = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name='".NBDESIGNER_PAGE_CREATE_TEMPLATE."'");
        if ($admindesign == ''){        
            $post = array(
                'post_name' => NBDESIGNER_PAGE_CREATE_TEMPLATE,
                'post_status' => 'publish',
                'post_title' => __('Create Template', 'nbdesigner'),
                'post_type' => 'page',
                'post_date' => date('Y-m-d H:i:s')
            );      
            $page = wp_insert_post($post, false);	
        }       
        $studio = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name='".NBDESIGNER_PAGE_STUDIO."'");
        if ($studio == ''){        
            $post = array(
                'post_name' => NBDESIGNER_PAGE_STUDIO,
                'post_status' => 'publish',
                'post_title' => __('Designer Studio', 'nbdesigner'),
                'post_type' => 'page',
                'post_date' => date('Y-m-d H:i:s')
            );      
            $page = wp_insert_post($post, false);	
        }         
    }
    public function nbdesigner_add_shortcode_page_design($content){
        global $post;
        if($post->post_type === 'page' && $post->post_name === NBDESIGNER_PAGE_REDESIGN){
            if ( has_shortcode($content, 'nbdesigner_redesign') ){           
                return $content;
            }else{              
                $content = '[nbdesigner_redesign]';
                return $content;                
            }            
        }
        if($post->post_type === 'page' && $post->post_name === NBDESIGNER_PAGE_CREATE_TEMPLATE){
            if ( has_shortcode($content, 'nbdesigner_admindesign') ){
                return $content;
            }else{
                $content = '[nbdesigner_admindesign]';              
                return $content;                
            }            
        }        
        return $content;
    }
    public function nbdesigner_redesign_func(){       
        $html = '';
        $uid = get_current_user_id();
        $list_image = array();
        $order = 'nb_order';        
        $product_id = (isset($_GET['product_id']) && $_GET['product_id'] != '') ? $_GET['product_id'] : '';
        $order_id = (isset($_GET['orderid']) && $_GET['orderid'] != '') ? $_GET['orderid'] : '';
        $folder = (isset($_GET['folder']) && $_GET['folder'] != '') ? $_GET['folder'] : '';
        $order_item_id = (isset($_GET['oiid']) && $_GET['oiid'] != '') ? $_GET['oiid'] : '';
        if(!isset($product_id) || !isset($order_id)) return $html;
        $src_iframe = add_query_arg(array(
            'action' => 'nbdesigner_editor_html', 
            'product_id' => $product_id, 
            'orderid' => $order_id, 
            'task' => 'redesign',
            'order_item_folder' => $folder,
            'oiid' => $order_item_id ), site_url());
        $path = $this->plugin_path_data . 'designs/' . $uid . '/' . $order_id . '/' . $folder . '/thumbs';
        $list_image = $this->nbdesigner_list_thumb($path);
        if (count($list_image) > 0) {
            $div_image = '<div id="nbdesigner_frontend_area"><h4>' . __('Preview your design', 'nbdesigner') . '</h4>';
            foreach ($list_image as $img) {
                $src = $this->nbdesigner_create_secret_image_url($img);
                $div_image .= '<div class="img-con"><img src="' . $src . '" /></div>';
            }  
            $div_image .= '</div>';
        } else {
            $div_image = '<div id="nbdesigner_frontend_area"></div>';
        }        
        $html .= '<div class="woocommerce_msrp">';
        $html .= '<a class="button nbdesign-button nbdesigner-disable" id="triggerDesign" >' . '<img class="nbdesigner-img-loading rotating" src="'.NBDESIGNER_PLUGIN_URL.'assets/images/loading.png'.'"/>'.__('Design again', 'nbdesigner').'</a><br />' . $div_image;
        $html .= '</div><br />';  
        $html .= '<div style="position: fixed; top: 0; left: 0; z-index: 999999; opacity: 0; width: 100%; height: 100%;" id="container-online-designer"><iframe id="onlinedesigner-designer"  width="100%" height="100%" scrolling="no" frameborder="0" noresize="noresize" allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true" src="' . $src_iframe . '"></iframe><span id="closeFrameDesign"  class="nbdesigner_pp_close">&times;</span></div>';
        return $html;
    }
    public function nbdesigner_admindesign_func(){
        $uid = get_current_user_id();
        $list_image = array();    
        $templates = array();
        $product_id = (isset($_GET['product_id']) && $_GET['product_id'] != '') ? $_GET['product_id'] : '';       
        $priority = (isset($_GET['priority']) && $_GET['priority'] != '') ? $_GET['priority'] : '';       
        $task = (isset($_GET['task']) && $_GET['task'] != '') ? $_GET['task'] : '';       
        $template_folder = (isset($_GET['template_folder']) && $_GET['template_folder'] != '') ? $_GET['template_folder'] : '';       
        if(!($uid > 0) || !isset($product_id) || !(current_user_can('edit_nbd_template'))) return $html = __("Oops! you can't accsess this page!", 'nbdesigner');
        $path = NBDESIGNER_ADMINDESIGN_DIR . '/' . $product_id . '/primary/thumbs';
        if($template_folder != '') $path = NBDESIGNER_ADMINDESIGN_DIR . '/' . $product_id . '/'.$template_folder.'/thumbs';
        $query_array = array(
            'action' => 'nbdesigner_editor_html', 
            'product_id' => $product_id, 
            'task' => $task, 
            'priority' => $priority, 
            'template_folder' => $template_folder           
        );
        if(isset($_GET['temp'])){
            $query_array['temp'] = $_GET['temp'];
            $path = NBDESIGNER_ADMINDESIGN_DIR . '/' . $product_id . '/' . $_GET['temp'] .'/thumbs';
        }
        if(isset($_GET['redesign'])){
            $query_array['redesign'] = $_GET['redesign'];
        }
        $src_iframe = add_query_arg($query_array, site_url());
        
        if(file_exists($path))  $list_image = Nbdesigner_IO::get_list_thumbs($path); 
        $atts = array(
            'src_iframe' => $src_iframe,
            'list_image' => $list_image,
            'templates' => $templates
        );
        ob_start();            
        nbdesigner_get_template('admin-manager-templates.php', $atts);
        $content = ob_get_clean();            
        return $content;
    }    
    private function nbdesigner_generate_ramdom_key($length = 10){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-+';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function nbdesigner_get_security_key(){
        if (!wp_verify_nonce($_POST['nonce'], 'nbdesigner_add_cat') || !current_user_can('administrator')) {
            die('Security error');
        }        
        $result['key'] = $this->nbdesigner_generate_ramdom_key(24);
        $result['mes'] = 'success';
        echo json_encode($result);
        wp_die();
    }
    public function nbdesigner_frontend_translate(){
        require_once ABSPATH . 'wp-admin/includes/translation-install.php';
        $languages = wp_get_available_translations();
        $path = NBDESIGNER_PLUGIN_DIR . 'data/language.json';  
        $path_data = $this->plugin_path_data . 'data/language.json';
        if(file_exists($path_data)) $path = $path_data;  
        $path_lang = NBDESIGNER_PLUGIN_DIR . 'data/language/en_US.json';
        $path_data_lang = $this->plugin_path_data . 'data/language/en_US.json';
        if(file_exists($path_data_lang)) $path_lang = $path_data_lang;  
        $list = json_decode(file_get_contents($path));     
        $lang = json_decode(file_get_contents($path_lang)); 
        if(is_array($lang)){
            $langs = (array)$lang[0];
        }
        require(NBDESIGNER_PLUGIN_DIR . 'views/nbdesigner-translate.php');
    }
    public function nbdesigner_save_language(){
        $data = array(
            'mes'   =>  __('You do not have permission to edit language!', 'nbdesigner'),
            'flag'  => 0
        );	        
        if (!wp_verify_nonce($_POST['nonce'], 'nbdesigner_add_cat') || !current_user_can('edit_nbd_language')) {
            echo json_encode($data);
            wp_die();
        }        
        if(isset($_POST['langs'])){
            $langs = array();
            $langs[0] = $_POST['langs'];
        }
        if(isset($_POST['code'])){
            $code = $_POST['code'];
        } 
        if(isset($langs) && isset($code)){
            $path_lang = NBDESIGNER_PLUGIN_DIR . 'data/language/'.$code.'.json';
            $path_data = $this->plugin_path_data . 'data/language/'.$code.'.json';
            if(file_exists($path_data)){
                $path_lang = $path_data;                   
            }else{
                if($code = "en_US") {
                    $path_lang = NBDESIGNER_DATA_CONFIG_DIR . '/language/en_US.json';
                }
            }                   
            foreach ($langs[0] as $key => $lang){
                $langs[0][$key] = strip_tags($lang);
            }           
            $res = json_encode($langs);
            file_put_contents($path_lang, $res);   
            $data['mes'] =  __('Update language success!', 'nbdesigner');
            $data['flag'] = 1;
        }else{
            $data['mes'] = __('Update language failed!', 'nbdesigner');
        }
        echo json_encode($data);
        wp_die();
    }
    public function nbdesigner_get_language($code){
        if (!(wp_verify_nonce($_POST['nonce'], 'nbdesigner_add_cat') || wp_verify_nonce($_POST['nonce'], 'save-design'))) {
            die('Security error');
        }         
        $data = array();
        $data['mes'] = 'success';
        if(!isset($code)){
            $code = "en";
        }else if(isset($_POST['code'])) {
            $code = $_POST['code'];          
        }
        $path = NBDESIGNER_PLUGIN_DIR . 'data/language.json';
        $path_data = $this->plugin_path_data . 'data/language.json';
        if(file_exists($path_data)) $path = $path_data;
        $list = json_decode(file_get_contents($path)); 
        $path_lang = NBDESIGNER_PLUGIN_DIR . 'data/language/'.$code.'.json';
        $path_data_lang = $this->plugin_path_data . 'data/language/'.$code.'.json';
        if(file_exists($path_data_lang)) $path_lang = $path_data_lang;
        $path_original_lang = NBDESIGNER_PLUGIN_DIR . 'data/language/en_US.json';
        if(!file_exists($path_lang)) $path_lang = $path_original_lang;
        $lang_original = json_decode(file_get_contents($path_original_lang)); 
        $lang = json_decode(file_get_contents($path_lang)); 
        if(is_array($lang)){
            $data_langs = (array)$lang[0];
            if(is_array($lang_original)){
                $data_langs_origin = (array)$lang_original[0];
                $data_langs = array_merge($data_langs_origin, $data_langs);
            }
            $data['langs'] = $data_langs;
            $data['code'] = $code;
        }else{
            $data['mes'] = 'error';
        }
        if(is_array($list)){
            $data['cat'] = $list;
        }else{
            $data['mes'] = 'error';
        }
        echo json_encode($data);
        wp_die();
    }    
    public function nbdesigner_delete_language(){
        $data = array(
            'mes'   =>  __('You do not have permission to delete language!', 'nbdesigner'),
            'flag'  => 0
        );	        
        if (!wp_verify_nonce($_POST['nonce'], 'nbdesigner_add_cat') || !current_user_can('edit_nbd_language')) {
            echo json_encode($data);
            wp_die();
        } 
        $code = $_POST['code'];
        $index = $_POST['index'];
        $path_lang = NBDESIGNER_DATA_CONFIG_DIR . '/language/'.$code.'.json';
        $path_data_cat_lang = NBDESIGNER_DATA_CONFIG_DIR . '/language.json';         
        $cats = json_decode(file_get_contents($path_data_cat_lang)); 
        $primary_lang_code = $cats[0]->code;
        $path_primary_lang = NBDESIGNER_DATA_CONFIG_DIR . '/language/'.$primary_lang_code.'.json';
        if(!file_exists($path_primary_lang)){
            $path_primary_lang = NBDESIGNER_PLUGIN_DIR . 'data/language/'.$primary_lang_code.'.json';
        }
        if($index != 0){
            if(unlink($path_lang)) {
                $data['flag'] = 1;
                $this->nbdesigner_delete_json_setting($path_data_cat_lang, $index);
                $data['mes'] = __('Delete language success!', 'nbdesigner');
                $langs = json_decode(file_get_contents($path_primary_lang)); 
                $data['langs'] = (array)$langs[0];
            }            
        }else {
            $data['mes'] = __('Oops! Can not delete primary language!', 'nbdesigner');
        }
        echo json_encode($data);
        wp_die();
    }
    public function nbdesigner_create_language(){    
        $data = array(
            'mes'   =>  __('You do not have permission to create language!', 'nbdesigner'),
            'flag'  => 0
        );        
        if (!wp_verify_nonce($_POST['nbdesigner_newlang_hidden'], 'nbdesigner-new-lang') || !current_user_can('edit_nbd_language')) {
            echo json_encode($data);
            wp_die();
        } 
        if(isset($_POST['nbdesigner_codelang']) && isset($_POST['nbdesigner_namelang'])){           
            $code = sanitize_text_field($_POST['nbdesigner_codelang']);
            $path_lang = NBDESIGNER_DATA_CONFIG_DIR . '/language/'.$code.'.json';
            $path_original_lang = NBDESIGNER_PLUGIN_DIR . 'data/language/en_US.json';
            $path_original_data_lang = NBDESIGNER_DATA_CONFIG_DIR . '/language/en_US.json';
            if(file_exists($path_original_data_lang)) $path_original_lang = $path_original_data_lang;
            $path_cat_lang = NBDESIGNER_PLUGIN_DIR . 'data/language.json';
            $path_data_cat_lang = NBDESIGNER_DATA_CONFIG_DIR . '/language.json';
            if(file_exists($path_data_cat_lang)) $path_cat_lang = $path_data_cat_lang;            
            $cats = json_decode(file_get_contents($path_cat_lang)); 
            $lang = json_decode(file_get_contents($path_original_lang)); 
            $_cat = array();
            $_cat['id'] = 1;
            if(is_array($cats)){                 
                foreach($cats as $cat){                  
                    if($cat->code == $code){
                        $code .=  rand(1,100);
                    }
                    $_cat['id'] = $cat->id;
                }
                $_cat['id'] += 1;
            }else{
                $data['mes'] = 'error';
                echo json_encode($data);
                wp_die();                
            } 
            if(is_array($lang)){
                $data['langs'] = (array)$lang[0];
                $data['code'] = $code;
                $_cat['code'] = $code; 
                $data['name'] = sanitize_text_field($_POST['nbdesigner_namelang']);
                $_cat['name'] = $data['name'];
                if (!copy($path_original_lang, $path_lang)) {
                    $data['mes'] = 'error';
                }else{
                    array_push($cats, $_cat);                  
                    file_put_contents($path_cat_lang, json_encode($cats));   
                    file_put_contents($path_data_cat_lang, json_encode($cats));   
                }
                $data['mes'] = 'Your language has been created successfully!';
                $data['flag'] = 1;
            }else{
                $data['mes'] = 'error';
            }            
        }  
        echo json_encode($data);
        wp_die();
    }
    public function nbdesigner_editor_html(){
        if ( ( ! defined('DOING_AJAX') || ! DOING_AJAX ) && ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != 'nbdesigner_editor_html' ) ) return;
        $path = NBDESIGNER_PLUGIN_DIR . 'views/nbdesigner-frontend-template.php';
        include($path);exit();
    }
    public function nbdesigner_make_primary_design(){
        if (!wp_verify_nonce($_POST['nonce'], 'nbdesigner_template_nonce') || !current_user_can('administrator')) {
            die('Security error');
        }
        $result = array();
        if(isset($_POST['id']) && isset($_POST['folder']) && isset($_POST['task'])){
            $pid = $_POST['id'];
            $folder = $_POST['folder'];
            $task = $_POST['task'];
            $check = true;
            if($task == 'primary'){
                $path_primary = $this->plugin_path_data . 'admindesign/' . $pid . '/primary'; 
                $path_primary_old = $this->plugin_path_data . 'admindesign/' . $pid . '/primary_old'; 
                $path_primary_new = $this->plugin_path_data . 'admindesign/' . $pid . '/' .$folder; 
                if(!rename($path_primary, $path_primary_old)) $check = false; 
                if(!rename($path_primary_new, $path_primary)) $check = false; 
                if(!rename($path_primary_old, $path_primary_new)) $check = false;                 
            }
            if( $check ) $result['mes'] = 'success'; else $result['mes'] = 'error';             
        }else{
            $result['mes'] = 'Invalid data';
        }  
        echo json_encode($result);
        wp_die();
    }
    public function nbdesigner_load_admin_design(){       
        if (!wp_verify_nonce($_POST['nonce'], 'save-design')) {
            die('Security error');
        }  
        $result = array();
        if(isset($_POST['id'])){
            $pid = absint($_POST['id']);
            $list_design = array();
            $templates = $this->nbdesigner_get_templates_by_page('', '', '', $pid, true);
            if(count($templates)){
                foreach ($templates as $tem){
                    $list_design[$tem['adid']] = $tem['image'];
                }
            }
            $result['data'] = $list_design;
            $result['mes'] = 'success';
        }else{
            $result['mes'] = 'Invalid data';
        }
        echo json_encode($result);
        wp_die();        
    }
    public function nbdesigner_tools(){
        $custom_css = Nbdesigner_DebugTool::get_custom_css();
        include_once(NBDESIGNER_PLUGIN_DIR . 'views/nbdesigner-tools.php');
    }
    public function nbdesigner_variation_settings_fields($loop, $variation_data, $variation){
        $vid = $variation->ID;
        $enable = get_post_meta($vid, '_nbdesigner_enable'.$vid, true);
        $default =  nbd_default_product_setting();    
        $designer_setting = array();
        $designer_setting[0] = $default;
        $dpi = get_post_meta($vid, '_nbdesigner_dpi', true);
        if($dpi == "") $dpi = nbdesigner_get_option('nbdesigner_default_dpi');
        $unit = nbdesigner_get_option('nbdesigner_dimensions_unit');     
        $_designer_setting = unserialize(get_post_meta($vid, '_designer_setting'.$vid, true));
        if (isset($_designer_setting[0])){
            $designer_setting = $_designer_setting;
            if(! isset($designer_setting[0]['version']) || $_designer_setting[0]['version'] < 160) {
                $designer_setting = $this->update_config_product_160($designer_setting);
            }
        }
        include(NBDESIGNER_PLUGIN_DIR . 'views/nbdesigner-box-design-setting-variation.php');
    }
    public function nbdesigner_save_variation_settings_fields($post_id){
        if(!(current_user_can('administrator') || current_user_can('shop_manager')) || !isset($_POST['_nbdesigner_enable'.$post_id])){
            return $post_id;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }  
        $var = get_post($post_id);
        $enable = $_POST['_nbdesigner_enable'.$post_id]; 
        $setting = serialize($_POST['_designer_setting'.$post_id]);  
        if(!$this->nbdesigner_allow_create_product($var->post_parent)) return;
        update_post_meta($post_id, '_nbdesigner_enable'.$post_id, $enable);
        update_post_meta($post_id, '_designer_setting'.$post_id, $setting);
    }    
    public function nbdesigner_copy_image_from_url(){
        if (!wp_verify_nonce($_POST['nonce'], 'save-design')) {
            die('Security error');
        }  
        $url = $_POST['url'];
        $ext = $this->nbdesigner_get_extension($url);
        $allow_extension = array('jpg','jpeg','png','gif');
        if(!in_array(strtolower($ext), $allow_extension)) $ext = 'png';
        $new_name = strtotime("now").substr(md5(rand(1111,9999)),0,8).'.'.$ext;
        $path = $this->plugin_path_data. 'temp/'.$new_name;
        $res['src'] = content_url().'/uploads/nbdesigner/temp/'.$new_name;
        if(@copy($url, $path)){
            $res['flag'] = 1;
        } else {
            $res['flag'] = 0;
        }  
        echo json_encode($res);
        wp_die();
    }
    public function nbdesigner_gallery_func($atts, $content = null) {
        $page = (get_query_var('paged')) ? get_query_var('paged') : 1; 
        $atts = shortcode_atts(array(
            'row' => 5,
            'per_row' => 3,
            'pagination' => 'true',
            'des' => 'Gallery design templates',
            'page' => $page,
            'templates' => array(),
            'total' => $this->count_total_template()
            ), $atts);
        $atts['templates'] = $this->nbdesigner_get_templates_by_page($page, absint($atts['row']), absint($atts['per_row']));
        return nbdesigner_get_template('gallery.php', $atts);
    }
    public function count_total_template($pid = false){
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}nbdesigner_templates AS t";     
        $sql .= " LEFT JOIN {$wpdb->prefix}posts AS p ON t.product_id = p.ID";
        $sql .= " WHERE t.publish = 1 AND p.post_status = 'publish'";     
        if($pid) $sql .= " AND t.product_id = ".$pid; 
        $count = $wpdb->get_var($sql);  
        return $count ? $count : 0;
    }
    public function nbdesigner_get_templates_by_page($page = 1, $row = 3, $per_row = 4, $pid = false, $get_all = false){
        $listTemplates = array();
        global $wpdb;
        $limit = $row * $per_row;
        $offset = $limit * ($page -1);
        $sql = "SELECT p.ID, t.folder FROM {$wpdb->prefix}nbdesigner_templates AS t";     
        $sql .= " LEFT JOIN {$wpdb->prefix}posts AS p ON t.product_id = p.ID";
        $sql .= " WHERE t.publish = 1 AND p.post_status = 'publish'";     
        if($pid) $sql .= " AND t.product_id = ".$pid; 
        $sql .= " ORDER BY t.created_date DESC";
        if(!$get_all){
            $sql .= " LIMIT ".$limit." OFFSET ".$offset;
        }       
        $posts = $wpdb->get_results($sql, 'ARRAY_A');     
        foreach ($posts as $p){
            $path_preview = NBDESIGNER_ADMINDESIGN_DIR . '/' . $p['ID'] .'/'.$p['folder']. '/preview';
            $listThumb = Nbdesigner_IO::get_list_thumbs($path_preview);
            $image = '';
            if(count($listThumb)){
                $image = Nbdesigner_IO::convert_path_to_url(end($listThumb));
            }
            $listTemplates[] = array('id' => $p['ID'], 'image' => $image, 'adid' => $p['folder']);          
        }         
        return $listTemplates;
    }
    protected static function nbdesigner_get_javascript_multilanguage(){
        $lang = array(
            'error' => __('Oops! Try again later!', 'nbdesigner'),
            'complete' => __('Complete!', 'nbdesigner'),
            'are_you_sure' => __('Are you sure?', 'nbdesigner'),
            'warning_mes_delete_file' => __('You will not be able to recover this file!', 'nbdesigner'),
            'warning_mes_delete_category' => __('You will not be able to recover this category!', 'nbdesigner'),
            'warning_mes_fill_category_name' => __('Please fill category name!', 'nbdesigner'),
            'warning_mes_backup_data' => __('Restore your last data!', 'nbdesigner'),
            'warning_mes_delete_lang' => __('You will not be able to recover this language!', 'nbdesigner')
        );
        return $lang;
    }
    public function nbdesigner_add_tinymce_editor(){
        global $typenow;
        // check user permissions
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) return;
        $post_types = get_post_types();
        if (!is_array($post_types)) $post_types = array('post', 'page');
        // verify the post type
        if (!in_array($typenow, $post_types)) return;
        // check if WYSIWYG is enabled
        if (get_user_option('rich_editing') == 'true') {
            add_filter('mce_external_plugins', array($this, 'nbdesigner_add_tinymce_shortcode_editor_plugin'));
            add_filter('mce_buttons', array($this, 'nbdesigner_add_tinymce_shortcode_editor_button'));
        }
        add_action('in_admin_footer', array($this, 'nbdesigner_add_tiny_mce_shortcode_dialog'));
    }   
    public function nbdesigner_add_tinymce_shortcode_editor_plugin($plugin_array){
        $plugin_array['nbdesigner_button'] = NBDESIGNER_JS_URL . 'nbdesigner-tinymce-shortcode.js';		
        return $plugin_array;        
    }
    public function nbdesigner_add_tinymce_shortcode_editor_button($buttons){   
        array_push($buttons, "nbdesigner_button");		
        return $buttons;         
    }    
    public function nbdesigner_add_tiny_mce_shortcode_dialog(){
        include (NBDESIGNER_PLUGIN_DIR . 'views/nbdesigner-shortcode-dialog.php');
    }
    public function nbdesigner_get_suggest_design(){
        if (!wp_verify_nonce($_POST['nonce'], 'save-design')) {
            die('Security error');
        }          
        $result = array();
        $result['mes'] = 'success';
        $result['flag'] = 1;           
        if(isset($_POST['ref']) && isset($_POST['products']) && isset($_POST['sid'])){    
            $ref_pid = $_POST['ref'];
            $products = $_POST['products'];
            $sid = esc_html($_POST['sid']);
            $uid = get_current_user_id();
            $iid = $sid;
            if ($uid > 0) $iid = $uid;
            $up = wp_upload_dir();
            $base_path = $up['baseurl'];             
            $from_path = $this->plugin_path_data . 'designs/' . $iid . '/nb_order/' . $ref_pid;
            if(is_array($products)){
                foreach ($products as $pro){
                    $folder = substr(md5(rand(0, 999999)), 0, 10);
                    $path_suggest = $this->plugin_path_data . 'suggest_designs/' . $folder;
                    $this->nbdesigner_create_thumbnail_design($from_path, $path_suggest, $pro, 500, 500);
                    $list = $this->nbdesigner_list_thumb($path_suggest, $level = 1);
                    $mid_path = 'nbdesigner/suggest_designs/'.$folder.'/';
                    foreach ($list as $img){
                        $name = basename($img);
                        $url = $base_path.'/'.$mid_path.$name;
                        $result['images'][$pro][] = $url;
                    }	                    
                }
            }else{
                $result['mes'] = __('Missing product!', 'nbdesigner');
                $result['flag'] = 0;                    
            }
        }else{
            $result['mes'] = __('Missing information!', 'nbdesigner');
            $result['flag'] = 0;            
        }        
        echo json_encode($result);
        wp_die();
    }
    /**
     * Allow multi design.
     * Add Product To Cart Multiple Times But As Different items.
     * since 1.5.0
     * 
     */
    public function nbdesigner_force_individual_cart_items($cart_item_data, $product_id) {
        if (isset($_SESSION['nbdesigner']['nbdesigner_' . $product_id])) {
            $unique_cart_item_key = md5(microtime() . rand());
            $cart_item_data['unique_key'] = $unique_cart_item_key;
        }
        return $cart_item_data;
    }
    /**
     * Update data admin templates in older version (before 1.5.0)
     * @since 1.5.0
     * 
     */
    public static function nbdesigner_update_data_150(){
        global $wpdb;
        $origin_path = NBDESIGNER_ADMINDESIGN_DIR . '/';
        $listTemplates = array();
        $args = array(
            'post_type' => 'product',
            'meta_key' => '_nbdesigner_admintemplate_primary',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page'=>-1,
            'meta_query' => array(
                array(
                    'key' => '_nbdesigner_admintemplate_primary',
                    'value' => 1,
                )
            )
        );   
        $posts = get_posts($args); 
        foreach ($posts as $p){
            $pro = wc_get_product($p->ID);
            $list_folder = array();
            $path = $origin_path . $p->ID;
            if ($dir = @opendir($path)) {
                while (($file = readdir($dir) ) !== false) {
                    if (in_array($file, array('.', '..')))
                        continue;
                    if (is_dir($path . '/' . $file)) {
                        $list_folder[] =  $file;
                    }
                }
            }
            @closedir($dir);   
            if(is_array($list_folder)){
                foreach($list_folder as $folder){
                    $listTemplates[] = array('product_id' => $p->ID, 'folder' => $folder);
                }
            }           
        }   
        if(is_array($listTemplates)){
            foreach($listTemplates as $temp){
                $created_date = new DateTime();
                $user_id = wp_get_current_user()->ID;
                $table_name =  $wpdb->prefix . 'nbdesigner_templates';
                $priority = 0;
                if($temp['folder'] == 'primary') $priority = 1;
                $wpdb->insert($table_name, array(
                    'product_id' => $temp['product_id'],
                    'folder' => $temp['folder'],
                    'user_id' => $user_id,
                    'created_date' => $created_date->format('Y-m-d H:i:s'),
                    'publish' => 1,
                    'private' => 0,
                    'priority' => $priority
                ));  
            }                       
        }
    }
    public function nbdesigner_migrate_domain(){
        Nbdesigner_DebugTool::update_data_migrate_domain();
    }
    public function nbdesigner_restore_data_migrate_domain(){
        Nbdesigner_DebugTool::restore_data_migrate_domain();
    }
    public function nbdesigner_theme_check(){
        Nbdesigner_DebugTool::theme_check_hook();
    }
    public function nbdesigner_custom_css(){
        Nbdesigner_DebugTool::save_custom_css();
    }    
    public function nbdesigner_save_design_to_pdf(){       
        if (!wp_verify_nonce($_POST['_wpnonce'], 'nbdesigner_pdf_nonce')) {
            die('Security error');
        }    
        require_once(NBDESIGNER_PLUGIN_DIR.'includes/tcpdf/tcpdf.php');
        $pdfs = $_POST['pdf'];
        $force = $_POST['force_same_format'];
        $order_id = $_POST['order_id'];
        $order_item_id = $_POST['order_item_id'];
        if(!is_array($pdfs)) die('Security error');
        $result = array();
        if($force){
            $mTop = $pdfs[0]["margin-top"];
            $mBottom = $pdfs[0]["margin-bottom"];
            $pdf_format = $pdfs[0]["format"];   
            $mLeft = $pdfs[0]["margin-left"];
            $mRight = $pdfs[0]["margin-right"];  
            $bgWidth = $pdfs[0]['product-width'];        
            $bgHeight = $pdfs[0]['product-height'];             
            if($pdf_format == '-1'){
                $pWidth = $bgWidth + $mLeft + $mRight;
                $pHeight = $bgHeight + $mTop + $mBottom;
                $pdf_format = array($pWidth, $pHeight);
                if($pWidth > $pHeight){
                    $orientation = "L";
                }else {
                    $orientation = "P";
                }
            }  
            $pdf = new TCPDF($orientation, 'mm', $pdf_format, true, 'UTF-8', false);
            $pdf->SetMargins($mLeft, $mTop, $mRight, true);     
            $pdf->SetCreator( get_site_url() );
            $pdf->SetTitle(get_bloginfo( 'name' ));
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);       
            $pdf->SetAutoPageBreak(TRUE, 0);              
        }             
        foreach($pdfs as $key => $_pdf){
            $customer_design = $_pdf['customer-design'];    
            $bTop = (float)$_pdf['bleed-top'];
            $bLeft = (float)$_pdf['bleed-left'];
            $bRight = (float)$_pdf['bleed-right'];
            $bBottom = (float)$_pdf['bleed-bottom'];        
            $bgWidth = (float)$_pdf['product-width'];        
            $bgHeight = (float)$_pdf['product-height'];   
            $showBleed = $_pdf['show-bleed-line'];   
            $orientation = $_pdf['orientation'];
            $mTop = (float)$_pdf["margin-top"];
            $mLeft = (float)$_pdf["margin-left"];
            $mRight = (float)$_pdf["margin-right"];
            $mBottom = (float)$_pdf["margin-bottom"];
            $cdTop = (float)$_pdf["cd-top"];
            $cdLeft = (float)$_pdf["cd-left"];
            $cdWidth = (float)$_pdf["cd-width"];
            $cdHeight = (float)$_pdf["cd-height"];    
            $background = $_pdf['background'];
            $pdf_format = $_pdf['format'];       
            $bg_type = $_pdf['bg_type'];       
            $bg_color_value = $_pdf['bg_color_value'];   
            if($bg_type == 'image'){
                $path_bg = Nbdesigner_IO::convert_url_to_path($background);
            }
            if($customer_design != ''){
                $path_cd = Nbdesigner_IO::convert_url_to_path($customer_design);  
            }            
            if($pdf_format == '-1'){
                $pWidth = $bgWidth + $mLeft + $mRight;
                $pHeight = $bgHeight + $mTop + $mBottom;
                $pdf_format = array($pWidth, $pHeight);
                if($pWidth > $pHeight){
                    $orientation = "L";
                }else {
                    $orientation = "P";
                }
            }  
            if(!$force){
                $pdf = new TCPDF($orientation, 'mm', $pdf_format, true, 'UTF-8', false);
                $pdf->SetMargins($mLeft, $mTop, $mRight, true);     
                $pdf->SetCreator( get_site_url() );
                $pdf->SetTitle(get_bloginfo( 'name' ));
                $pdf->setPrintHeader(false);
                $pdf->setPrintFooter(false);       
                $pdf->SetAutoPageBreak(TRUE, 0);   
            }         
            $pdf->AddPage();
            if($bg_type == 'image'){
                $img_ext = array('jpg','jpeg','png');
                $svg_ext = array('svg');
                $eps_ext = array('eps','ai');
                $check_img = Nbdesigner_IO::checkFileType(basename($path_bg), $img_ext);
                $check_svg = Nbdesigner_IO::checkFileType(basename($path_bg), $svg_ext);
                $check_eps = Nbdesigner_IO::checkFileType(basename($path_bg), $eps_ext);
                $ext = pathinfo($path_bg);
                if($check_img){
                    $pdf->Image($path_bg,$mLeft, $mTop, $bgWidth, $bgHeight, '', '', '', false, '');
                }
                if($check_svg){
                    $pdf->ImageSVG($path_bg, $mLeft,$mTop, $bgWidth, $bgHeight, '', '', '', 0, true);
                }     
                if($check_eps){
                   $pdf->ImageEps($path_bg, $mLeft,$mTop, $bgWidth, $bgHeight, '', true, '', '', 0, true);
                }                 
            }elseif($bg_type == 'color') {
                $pdf->Rect($mLeft, $mTop,  $bgWidth, $bgHeight, 'F', '', hex_code_to_rgb($bg_color_value));
            }
            if($customer_design != ''){
                $pdf->Image($path_cd, $mLeft + $cdLeft, $mTop + $cdTop, $cdWidth,$cdHeight, '', '', '', false, '');  
            }             
            if($showBleed == 'yes'){
                $pdf->Line(0, $mTop + $bTop, $mLeft + $bLeft, $mTop + $bTop, array('color' => array(0,0,0), 'width' => 0.05));
                $pdf->Line(0, $mTop + $bgHeight - $bBottom, $mLeft + $bLeft, $mTop + $bgHeight - $bBottom, array('color' => array(0,0,0), 'width' => 0.05));
                $pdf->Line($bgWidth + $mLeft - $bRight, $mTop + $bTop, $bgWidth + $mLeft + $mRight, $mTop + $bTop, array('color' => array(0,0,0), 'width' => 0.05));
                $pdf->Line($bgWidth + $mLeft - $bRight, $mTop + $bgHeight - $bBottom, $bgWidth + $mLeft + $mRight, $mTop + $bgHeight - $bBottom, array('color' => array(0,0,0), 'width' => 0.05));
                $pdf->Line($mLeft + $bLeft, 0, $mLeft + $bLeft, $mTop + $bTop, array('color' => array(0,0,0), 'width' => 0.05));
                $pdf->Line($mLeft + $bLeft, $mTop + $bgHeight - $bBottom, $mLeft + $bLeft, $mTop + $bgHeight + $mBottom, array('color' => array(0,0,0), 'width' => 0.05));
                $pdf->Line($mLeft + $bgWidth - $bRight, 0, $mLeft + $bgWidth - $bRight, $mTop + $bTop, array('color' => array(0,0,0), 'width' => 0.05));
                $pdf->Line($mLeft + $bgWidth - $bRight, $mTop + $bgHeight - $bBottom, $mLeft + $bgWidth - $bRight, $mTop + $bgHeight + $mBottom, array('color' => array(0,0,0), 'width' => 0.05));
            }   
            if(!$force){
                $folder = NBDESIGNER_PDF_DIR . '/' .$order_id .'/'.$order_item_id;
                if(!file_exists($folder)){
                    wp_mkdir_p($folder);
                }
                $output_file = $folder .'/'. $key .'_'.time().'.pdf';
                $pdf->Output($output_file, 'F');              
                $result[] = array(
                    'link' => Nbdesigner_IO::convert_path_to_url($output_file),
                    'title' => $_pdf['name']
                );
            }
        }
        if($force){
            $folder = NBDESIGNER_PDF_DIR . '/' .$order_id .'/'.$order_item_id;
            if(!file_exists($folder)){
                wp_mkdir_p($folder);
            }
            $output_file = $folder .'/'. $order_id .'_'. time() .'.pdf';
            $pdf->Output($output_file, 'F');              
            $result[] = array(
                'link' => Nbdesigner_IO::convert_path_to_url($output_file),
                'title' => $_pdf['name']
            );                
        }        
        echo json_encode($result);
        wp_die();
    }
    public function nbd_test_ajax(){
        foreach ($_FILES as $key => $value) {
            $path = NBDESIGNER_TEMP_DIR . '/test/' .$key. time() . '.png';
            move_uploaded_file($value["tmp_name"],$path);
        }
    }
    public function add_nbdesinger_order_actions_button($actions, $the_order){
        if(is_woo_v3()){
            $id = $the_order->get_id();
        }else{
            $id = $the_order->id;
        } 
        $has_design = get_post_meta($id, '_nbdesigner_order_has_design', true);
        if($has_design == 'has_design'){
            $actions['view_nbd'] = array(
                'url'       => admin_url( 'post.php?post='.$id.'&action=edit' ).'#nbdesigner_order',
                'name'      => __( 'Has Design', 'nbdesigner' ),
                'action'    => "has-nbd"
            );            
        } 
        return $actions;
    }
}   
/**
 * Locate template.
 *
 * Locate the called template.
 * Search Order:
 * 1. /themes/theme/web-to-print-online-designer/$template_name
 * 2. /themes/theme/$template_name
 * 3. /plugins/web-to-print-online-designer/templates/$template_name.
 *
 * @since 1.3.1
 *
 * @param 	string 	$template_name			Template to load.
 * @param 	string 	$string $template_path	        Path to templates.
 * @param 	string	$default_path			Default path to template files.
 * @return 	string 					Path to the template file.
 */    
function nbdesigner_locate_template($template_name, $template_path = '', $default_path = '') {
    // Set variable to search in web-to-print-online-designer folder of theme.
    if (!$template_path) :
        $template_path = 'web-to-print-online-designer/';
    endif;
    // Set default plugin templates path.
    if (!$default_path) :
        $default_path = NBDESIGNER_PLUGIN_DIR . 'templates/'; // Path to the template folder
    endif;
    // Search template file in theme folder.
    $template = locate_template(array(
        $template_path . $template_name,
        $template_name
    ));
    // Get plugins template file.
    if (!$template) :
        $template = $default_path . $template_name;
    endif;
    return apply_filters('nbdesigner_locate_template', $template, $template_name, $template_path, $default_path);
}
/**
 * Get template.
 *
 * Search for the template and include the file.
 *
 * @since 1.3.1
 *
 * @see wcpt_locate_template()
 *
 * @param string 	$template_name			Template to load.
 * @param array 	$args				Args passed for the template file.
 * @param string 	$string $template_path	        Path to templates.
 * @param string	$default_path			Default path to template files.
 */
function nbdesigner_get_template($template_name, $args = array(), $tempate_path = '', $default_path = '') {
    if (is_array($args) && isset($args)) :
        extract($args);
    endif;
    $template_file = nbdesigner_locate_template($template_name, $tempate_path, $default_path);
    if (!file_exists($template_file)) :
        _doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $template_file), '1.3.1');
        return;
    endif;
    include $template_file;
}
