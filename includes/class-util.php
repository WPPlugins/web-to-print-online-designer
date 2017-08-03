<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Necessary I/O functions
 * 
 */
class Nbdesigner_IO {
    public function __construct() {
        //TODO
    }
    /**
     * Get all images in folder by level
     * 
     * @param string $path path folder
     * @param int $level level scan dir
     * @return array Array path images in folder
     */
    public static function get_list_thumbs($path, $level = 100){
        $list = array();
        $_list = self::get_list_files($path, $level);
        $list = preg_grep('/\.(jpg|jpeg|png|gif)(?:[\?\#].*)?$/i', $_list);
        return $list;        
    }
    public static function get_list_files($folder = '', $levels = 100) {
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
                    $files2 = self::get_list_files($folder . '/' . $file, $levels - 1);
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
    public static function delete_folder($path) {
        if (is_dir($path) === true) {
            $files = array_diff(scandir($path), array('.', '..'));
            foreach ($files as $file) {
                self::delete_folder(realpath($path) . '/' . $file);
            }
            return rmdir($path);
        } else if (is_file($path) === true) {
            return unlink($path);
        }
        return false;
    } 
    public static function copy_dir($src, $dst) {
        if (file_exists($dst)) self::delete_folder($dst);
        if (is_dir($src)) {
            wp_mkdir_p($dst);
            $files = scandir($src);
            foreach ($files as $file){
                if ($file != "." && $file != "..") self::copy_dir("$src/$file", "$dst/$file");
            }
        } else if (file_exists($src)) copy($src, $dst);
    }        
    public static function create_image_path($upload_path, $filename, $ext=''){
	$date_path = '';
        if (!file_exists($upload_path))
            mkdir($upload_path);
        $year = @date() === false ? gmdate('Y') : date('Y');
        $date_path .= '/' . $year . '/';
        if (!file_exists($upload_path . $date_path))
            mkdir($upload_path . $date_path);
        $month = @date() === false ? gmdate('m') : date('m');
        $date_path .= $month . '/';
        if (!file_exists($upload_path . $date_path))
            mkdir($upload_path . $date_path);
        $day = @date() === false ? gmdate('d') : date('d');
        $date_path .= $day . '/';
        if (!file_exists($upload_path . $date_path))
            mkdir($upload_path . $date_path);
        $file_path = $upload_path . $date_path . $filename;
        $file_counter = 1;
        $real_filename = $filename;
        while (file_exists($file_path . '.' . $ext)) {
            $real_filename = $file_counter . '-' . $filename;
            $file_path = $upload_path . $date_path . $real_filename;
            $file_counter++;
        }
        return array(
            'full_path' => $file_path,
            'date_path' => $date_path . $real_filename
        );
    }   
    public static function secret_image_url($file_path){
        $type = pathinfo($file_path, PATHINFO_EXTENSION);
        $data = file_get_contents($file_path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);   
        return $base64;        
    }   
    public static function convert_path_to_url($path){
        $upload_dir = wp_upload_dir();
        $basedir = $upload_dir['basedir'];
        $arr = explode('/', $basedir);
        $upload = $arr[count($arr) - 1];
        if(is_multisite() && !is_main_site()) $upload = $arr[count($arr) - 3].'/'.$arr[count($arr) - 2].'/'.$arr[count($arr) - 1];
        return content_url( substr($path, strrpos($path, '/' . $upload . '/nbdesigner')) );
    }
    public static function convert_url_to_path($url){
        $upload_dir = wp_upload_dir();
        $basedir = $upload_dir['basedir'];
        $arr = explode('/', $basedir);
        $upload = $arr[count($arr) - 1];
        if(is_multisite() && !is_main_site()) $upload = $arr[count($arr) - 3].'/'.$arr[count($arr) - 2].'/'.$arr[count($arr) - 1];
        $arr_url = explode('/'.$upload, $url);
        return $basedir.$arr_url[1];
    }
    public static function save_data_to_file($path, $data){
        if (!$fp = fopen($path, 'w')) {
            return FALSE;
        }
        flock($fp, LOCK_EX);
        fwrite($fp, $data);
        flock($fp, LOCK_UN);
        fclose($fp);
        return TRUE;        
    }
    public static function checkFileType($file_name, $arr_mime) {
        $check = false;
        $filetype = explode('.', $file_name);
        $file_exten = $filetype[count($filetype) - 1];
        if (in_array(strtolower($file_exten), $arr_mime)) $check = true;
        return $check;
    }    
}
function nbd_file_get_contents($url){
    if(ini_get('allow_url_fopen')){
        $checkPHP = version_compare(PHP_VERSION, '5.6.0', '>=');
        if (is_ssl() && $checkPHP) {
            $result = file_get_contents($url, false, stream_context_create(array('ssl' => 
                array('verify_peer' => false, 'verify_peer_name' => false)))); 
        }else{
            $result = file_get_contents($url);    
        }                       
    }else{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSLVERSION, 3); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);                        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);          
        if(false === $result){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            $result = curl_exec($ch);
            curl_close($ch);          
        }
    }	        
    return $result;    
}
function hex_code_to_rgb($code){        
    list($r, $g, $b) = sscanf($code, "#%02x%02x%02x");
    $rgb = array($r, $g, $b);
    return $rgb;
}
function is_nbdesigner_product($id){
    $check = get_post_meta($id, '_nbdesigner_enable', true);
    if($check) return true;
    return false;
}
function nbdesigner_get_option($key){
    $option = get_option($key, false);
    if(false === $option) return nbdesigner_get_default_setting($key);
    return $option;
}
function nbdesigner_get_all_setting(){
    $default = nbdesigner_get_default_setting();
    foreach ($default as $key => $val){
        $default[$key] = nbdesigner_get_option($key);
    }
    return $default;
}
function nbdesigner_get_all_frontend_setting(){
    $default = default_frontend_setting();
    foreach ($default as $key => $val){
        $default[$key] = nbdesigner_get_option($key);
    }
    return $default;
}
function nbdesigner_get_default_setting($key = false){
    $frontend = default_frontend_setting();
    $nbd_setting = apply_filters('nbdesigner_default_settings', array_merge(array(
        'nbdesigner_button_label' => __('Start Design', 'nbdesigner'),
        'nbdesigner_position_button_in_catalog' => 1,
        'nbdesigner_position_button_product_detail' => 1,
        'nbdesigner_thumbnail_width' => 100,
        'nbdesigner_thumbnail_height' => 100,
        'nbdesigner_thumbnail_quality' => 60,
        'nbdesigner_default_dpi' => 150,
        'nbdesigner_show_in_cart' => 'yes',
        'nbdesigner_show_in_order' => 'yes',
        'nbdesigner_dimensions_unit' => 'cm',
        'nbdesigner_disable_on_smartphones' => 'no',
        'nbdesigner_upload_designs_php_logged_in' => 'no',
        'nbdesigner_notifications' => 'yes',
        'nbdesigner_notifications_recurrence' => 'hourly',
        'nbdesigner_notifications_emails' => '',
        'nbdesigner_facebook_app_id' => '',
        'nbdesigner_enable_text' => 'yes',
        'nbdesigner_default_text' => __('Text here', 'nbdesigner'),
        'nbdesigner_enable_curvedtext' => 'yes',
        'nbdesigner_enable_textpattern' => 'yes',
        'nbdesigner_enable_clipart' => 'yes',
        'nbdesigner_enable_image' => 'yes',
        'nbdesigner_enable_upload_image' => 'yes',
        'nbdesigner_enable_image_webcam' => 'yes',
        'nbdesigner_enable_facebook_photo' => 'yes',
        'nbdesigner_upload_show_term' => 'no',
        'nbdesigner_enable_image_url' => 'yes',
        'nbdesigner_upload_term' => __('Your term', 'nbdesigner'),
        'nbdesigner_enable_draw' => 'yes',
        'nbdesigner_enable_qrcode' => 'yes',
        'nbdesigner_default_qrcode' => __('example.com', 'nbdesigner'),
        'nbdesigner_show_all_color' => 'yes',
        'nbdesigner_maxsize_upload' => 5,
        'nbdesigner_minsize_upload' => 0,    
        'nbdesigner_default_color' => '#cc324b',
        'nbdesigner_hex_names' => '',
        'nbdesigner_instagram_app_id' => '',
        'nbdesigner_printful_key' => ''
    ), $frontend));
    if(!$key) return $nbd_setting;
    return $nbd_setting[$key];
}
function default_frontend_setting(){
    $default = array(
        'nbdesigner_text_change_font' => 1,
        'nbdesigner_text_italic' => 1,
        'nbdesigner_text_bold' => 1,
        'nbdesigner_text_underline' => 1,
        'nbdesigner_text_through' => 1,
        'nbdesigner_text_overline' => 1,
        'nbdesigner_text_align_left' => 1,
        'nbdesigner_text_align_right' => 1,
        'nbdesigner_text_align_center' => 1,
        'nbdesigner_text_color' => 1,
        'nbdesigner_text_background' => 1,
        'nbdesigner_text_shadow' => 1,
        'nbdesigner_text_line_height' => 1,
        'nbdesigner_text_font_size' => 1,
        'nbdesigner_text_opacity' => 1,
        'nbdesigner_text_outline' => 1,
        'nbdesigner_text_proportion' => 1,
        'nbdesigner_text_rotate' => 1,
        'nbdesigner_clipart_change_path_color' => 1,           
        'nbdesigner_clipart_rotate' => 1,           
        'nbdesigner_clipart_opacity' => 1,           
        'nbdesigner_image_unlock_proportion' => 1,           
        'nbdesigner_image_shadow' => 1,           
        'nbdesigner_image_opacity' => 1,           
        'nbdesigner_image_grayscale' => 1,           
        'nbdesigner_image_invert' => 1,           
        'nbdesigner_image_sepia' => 1,           
        'nbdesigner_image_sepia2' => 1,           
        'nbdesigner_image_remove_white' => 1,      
        'nbdesigner_image_transparency' => 1,           
        'nbdesigner_image_tint' => 1,           
        'nbdesigner_image_blend' => 1,           
        'nbdesigner_image_brightness' => 1,           
        'nbdesigner_image_noise' => 1,         
        'nbdesigner_image_pixelate' => 1,         
        'nbdesigner_image_multiply' => 1,     
        'nbdesigner_image_blur' => 1,           
        'nbdesigner_image_sharpen' => 1,         
        'nbdesigner_image_emboss' => 1,         
        'nbdesigner_image_edge_enhance' => 1,          
        'nbdesigner_image_rotate' => 1,          
        'nbdesigner_image_crop' => 1,          
        'nbdesigner_image_shapecrop' => 1,          
        'nbdesigner_draw_brush' => 1,          
        'nbdesigner_draw_shape' => 1          
    );
    return $default;
}
function nbd_not_empty($value) {
    return $value == '0' || !empty($value);
}
function nbd_default_product_setting(){
    return apply_filters('nbdesigner_default_product_setting', array(
                'orientation_name' => 'Side 1',
                'img_src' => NBDESIGNER_PLUGIN_URL . 'assets/images/default.png',
                'img_overlay' => NBDESIGNER_PLUGIN_URL . 'assets/images/overlay.png',
                'real_width' => 8,
                'real_height' => 6,
                'real_left' => 1,
                'real_top' => 1,
                'area_design_top' => 100,
                'area_design_left' => 50,
                'area_design_width' => 400,
                'area_design_height' => 300,
                'img_src_top' => 50,
                'img_src_left' => 0,
                'img_src_width' => 500,
                'img_src_height' => 400,
                'product_width' => 10,    
                'product_height' => 8,
                'bg_type'   => 'image',
                'bg_color_value' => "#ffffff",
                'show_overlay' => 0,
                'version' => NBDESIGNER_NUMBER_VERSION
            )); 
}
function getUrlPageNBD($page){
    global $wpdb;
    switch ($page) {
        case 'template':
            $post_name = NBDESIGNER_PAGE_CREATE_TEMPLATE; 
            break;     
        case 'redesign':
            $post_name = NBDESIGNER_PAGE_REDESIGN; 
            break; 
        case 'studio':
            $post_name = NBDESIGNER_PAGE_STUDIO; 
            break;             
    }
    $post = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name='".$post_name."'"); 
    if($post) return get_page_link($post);
    return '#';
}
function nbd_get_product_info($user_id, $product_id, $variation_id = 0, $task = '', $reference_product = '', $template_folder = '', $order_id = '', $order_item_folder = '' ){
    $path = '';
    $data = array();
    $data['product'] = unserialize(get_post_meta($product_id, '_designer_setting', true));
    $data['dpi'] = (get_post_meta($product_id, '_nbdesigner_dpi', true) != '') ?  get_post_meta($product_id, '_nbdesigner_dpi', true) : 96;
    if($variation_id > 0){
        $variation_enable = get_post_meta($variation_id, '_nbdesigner_enable'.$variation_id, true);
        if($variation_enable){
            $data['product'] = unserialize(get_post_meta($variation_id, '_designer_setting'.$variation_id, true));
        }
    }
    if($task == 'redesign'){
        $path = NBDESIGNER_CUSTOMER_DIR . '/' .$user_id. '/' .$order_id. '/' .$order_item_folder ;
    }
    else if($task == 'create_template' || $task == 'edit_template'){
        if($template_folder != ''){
            $path = NBDESIGNER_ADMINDESIGN_DIR . '/' . $product_id . '/' . $template_folder;
        }    
    }else {
        if($reference_product != ''){
            $path = NBDESIGNER_CUSTOMER_DIR. '/' .$user_id. '/nb_order/' .$reference_product;
            $data['ref'] = unserialize(get_post_meta($reference_product, '_designer_setting', true));
        }else{
            $option = unserialize(get_post_meta($product_id, '_nbdesigner_option', true));   
			if(isset($option['admindesign']) && $option['admindesign']){
                if($template_folder != ''){
                    $path = NBDESIGNER_ADMINDESIGN_DIR . '/' . $product_id . '/' . $template_folder;
                }else {
                    $path = NBDESIGNER_ADMINDESIGN_DIR . '/' . $product_id . '/primary';
                }              
            }            
        }
    }  
    $data['design'] = nbd_get_data_from_json($path . '/design.json');
    $data['fonts'] = nbd_get_data_from_json($path . '/used_font.json');
    $data['config'] = nbd_get_data_from_json($path . '/config.json');
    return $data;
}
function nbd_get_data_from_json($path = ''){
    if ($path != '' && file_exists($path)) {
        return json_decode(file_get_contents($path));           
    }    
    return '';
}
function nbd_update_config_product_160($settings){
    return $settings;
}
function nbd_get_woo_version(){
    if ( ! function_exists( 'get_plugins' ) )
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    $plugin_folder = get_plugins( '/' . 'woocommerce' );
    $plugin_file = 'woocommerce.php';     
    if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
            return $plugin_folder[$plugin_file]['Version'];
    } else {
            return 0;
    }        
}
function is_woo_v3(){
    $woo_ver = nbd_get_woo_version(); 
    if( version_compare( $woo_ver, "3.0", "<" )) return false;
    return true;
}