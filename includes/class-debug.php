<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Nbdesigner_DebugTool {
    /**
     * Before use log() enable config log in wp-config.php in root folder
     * If can't modified wp-config.php use function wirite_log() or manual_write_debug()
     * @param type $data
     */
    static private $_path = NBDESIGNER_PLUGIN_DIR;
    public function __construct($path = ''){
        if($path != ''){
            self::$_path = $path;
        }else{
            self::$_path = NBDESIGNER_PLUGIN_DIR;
        }       
    }
    public static function log($data){
        if(NBDESIGNER_MODE_DEBUG === 'dev'){
            ob_start();
            var_dump($data);
            error_log(ob_get_clean());
        }else{
            return FALSE;
        }
    }
    public static function wirite_log($data){
        if(NBDESIGNER_MODE_DEBUG === 'dev'){
            error_reporting( E_ALL );
            ini_set('log_errors', 1);
            ini_set('error_log', self::$_path . 'debug.log');           
            error_log(basename(__FILE__) . ': Start debug.');
            ob_start();
            var_dump($data);
            error_log(ob_get_clean());            
            error_log(basename(__FILE__) . ': End debug.');
        }else{
            return FALSE;
        }        
    }
    public static function manual_write_debug($data){
        $path = self::$_path . 'debug.txt';
        $data = print_r($data, true);
        if (NBDESIGNER_MODE_DEBUG === 'dev') {
            if (!$fp = fopen($path, 'w')) {
                return FALSE;
            }
            flock($fp, LOCK_EX);
            fwrite($fp, $data);
            flock($fp, LOCK_UN);
            fclose($fp);
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public static function manual_write_debug2($data){
        $data = print_r($data, true);
        $path = self::$_path . 'debug.txt';    
        file_put_contents($path, $data);
    }
    public static function console_log($data){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';        
    }
    public static function theme_check_hook(){
        if (!wp_verify_nonce($_POST['_nbdesigner_check_theme_nonce'], 'nbdesigner-check-theme-key') || !current_user_can('administrator')) {
            die('Security error');
        }       
        $result = array();
        $theme_path = get_template_directory();
        $theme = wp_get_theme();
        $result['html'] = '';
        $list_filter = array(
            'woocommerce_before_add_to_cart_button' => '/single-product/add-to-cart/grouped.php', 
            'woocommerce_before_add_to_cart_button' => '/single-product/add-to-cart/external.php', 
            'woocommerce_before_add_to_cart_button' => '/single-product/add-to-cart/simple.php', 
            'woocommerce_before_add_to_cart_button' => '/single-product/add-to-cart/variable.php', 
            'woocommerce_cart_item_name' => '/cart/cart.php', 
            'woocommerce_order_item_name' => '/order/order-details-item.php', 
            'woocommerce_order_item_quantity_html' => '/order/order-details-item.php');
        $folder_woo = $theme_path . '/woocommerce';
        if(!file_exists($folder_woo)){
            $result['flag'] = 'ok';
            $result['html'] .= '<p style="background: #e3f2dd; padding: 15px; display: inline-block; font-weight: bold;">Your theme ('.esc_html( $theme['Name']).') compatible with plugin.</p>';
        }else{
            $result['flag'] = 'ok';
            $result['html'] .= '<h3>Your theme "'.esc_html( $theme['Name']).'"</h3>';
            foreach ($list_filter as $key => $val){
                $path = $folder_woo . $val;
                if(file_exists($path)){
                    $fp = fopen( $path, 'r' );
                    $file_data = fread($fp, filesize($path));
                    fclose( $fp );
                    $pattern = '/'.$key.'/';
                    if ( preg_match($pattern, $file_data, $match)){
                        $result['html'] .= '<p style="background: #e3f2dd; padding: 15px;"><span style="font-weight: bold;">'.$key.'</span> was found</p>';
                    }else{
                        $result['html'] .= '<div style="background: #eecff0; padding: 15px;"><p><span style="font-weight: bold;">'.$key.'</span> is missing</p>';
                        $result['html'] .= 'The '.$val.' in the woocommerce templates of your theme does not include the required action/filter: '.$key.'<p></p></div>';
                    }
                }
            }
        }
        echo json_encode($result);
        wp_die();   
    }
    public static function update_data_migrate_domain(){
        $result = array(
                'mes'   =>  __('You do not have permission to update data!', 'nbdesigner'),
                'flag'  => 0
            );	        
        if (!wp_verify_nonce($_POST['_nbdesigner_migrate_nonce'], 'nbdesigner-migrate-key') || !current_user_can('update_nbd_data')) {
            echo json_encode($data);
            wp_die();
        } 
        if(isset($_POST['old_domain']) && $_POST['old_domain'] != '' && isset($_POST['new_domain']) && $_POST['new_domain'] != ''){
            $old_domain = rtrim($_POST['old_domain'], '/');
            $new_domain = rtrim($_POST['new_domain'], '/');
            $upload_dir = wp_upload_dir();
            $path = $upload_dir['basedir'] . '/nbdesigner/';            
            $files = array("arts", "fonts");
            $path_backup_folder = $path . 'backup';
            if(!file_exists($path_backup_folder)) wp_mkdir_p ($path_backup_folder);
            $_files = glob($path_backup_folder.'/*');
            foreach($_files as $file){ 
              if(is_file($file)) unlink($file); 
            }   
            $result['flag'] = 1;
            $result['mes'] = __("Success!", 'nbdesigner');             
            foreach ($files as $file){
                $fullname = $path . $file . '.json';    
                if (file_exists($fullname)) {
                    $backup_file = $path_backup_folder . '/' . $file . '.json';
                    if(copy($fullname,$backup_file)){
                        $list = json_decode(file_get_contents($fullname));  
                        foreach ($list as $l){
                            $name_arr = explode('/uploads/', $l->file);
                            $new_file_name = $upload_dir['basedir'] . '/' . $name_arr[1];
                            $new_url = str_replace($old_domain, $new_domain, $l->url);
                            $l->file = $new_file_name;
                            $l->url = $new_url;
                        }
                        if(!file_put_contents($fullname, json_encode($list))){
                            $result['flag'] = 0;
                            $result['mes'] = __("Erorr write data!", 'nbdesigner');                             
                        }
                    }else{
                        $result['flag'] = 0;
                        $result['mes'] = __("Erorr backup!", 'nbdesigner');                        
                    }
                }
            }           
        }else{
            $result['flag'] = 0;
            $result['mes'] = __("Invalid info!", 'nbdesigner');   
        }
        echo json_encode($result);
        wp_die();
    }
    public static function restore_data_migrate_domain(){
        $result = array(
                'mes'   =>  __('You do not have permission to update data!', 'nbdesigner'),
                'flag'  => 0
            );	         
        if (!wp_verify_nonce($_POST['nonce'], 'nbdesigner_add_cat') || !current_user_can('update_nbd_data')) {
            echo json_encode($result);
            wp_die();
        } 
        $result = array();
        $result['flag'] = 1;
        $result['mes'] = "Restore success!";    
        $upload_dir = wp_upload_dir();
        $path = $upload_dir['basedir'] . '/nbdesigner/';          
        $files = array("arts", "fonts");
        foreach ($files as $file){
            $fullname = $path . $file . '.json';    
            $backup = $path .'backup/'. $file . '.json';    
            if (file_exists($fullname) && file_exists($backup)) {
                if(unlink($fullname)){
                    copy($backup,$fullname);
                }
            }else{
                $result['flag'] = 0;
                $result['mes'] = "Files not exist!";                 
            }
        }
        echo json_encode($result);
        wp_die();        
    }
    public static function save_custom_css(){
        $result = array(
                'mes'   =>  __('You do not have permission to update data!', 'nbdesigner'),
                'flag'  => 0
            );	        
        if (!wp_verify_nonce($_POST['_nbdesigner_custom_css'], 'nbdesigner-custom-css') || !current_user_can('administrator')) {
            echo json_encode($result);
            wp_die();   
        } 
        $custom_css = '';
        $path = NBDESIGNER_PLUGIN_DIR . 'assets/css/custom.css';
        if(isset($_POST['content'])){
            $custom_css = $_POST['content'];
            $fp = fopen($path, "w");
            fwrite($fp, $custom_css);
            fclose($fp);
            $result['flag'] = 1;
            $result['mes'] = __('Your CSS has been saved!', 'nbdesigner');
        }
        echo json_encode($result);
        wp_die();           
    }
    public static function get_custom_css(){
        $custom_css = '';
        $path = NBDESIGNER_PLUGIN_DIR . 'assets/css/custom.css';
        if(file_exists($path)){
            $fp = fopen( $path, 'r' );
            $custom_css = fread($fp, filesize($path));
            fclose( $fp );            
        }
        return $custom_css;
    }   
}