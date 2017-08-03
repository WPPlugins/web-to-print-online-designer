<?php
/**
 * @package Nbdesigner
 */
/*
Plugin Name: Nbdesigner
Plugin URI: https://cmsmart.net/wordpress-plugins/woocommerce-online-product-designer-plugin
Description: Allow customer design product before purchase.
Version: 1.6.2
Author: Netbaseteam
Author URI: http://netbaseteam.com/
License: GPLv2 or later
Text Domain: nbdesigner
Domain Path: /langs
*/

if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

$upload_dir = wp_upload_dir();
$basedir = $upload_dir['basedir'];
$baseurl = $upload_dir['baseurl'];

if (!defined('NBDESIGNER_VERSION')) {
    define('NBDESIGNER_VERSION', '1.6.2');
}
if (!defined('NBDESIGNER_NUMBER_VERSION')) {
    define('NBDESIGNER_NUMBER_VERSION', 162);
}
if (!defined('NBDESIGNER_MINIMUM_WP_VERSION')) {
    define('NBDESIGNER_MINIMUM_WP_VERSION', '4.1.1');
}
if (!defined('NBDESIGNER_MINIMUM_PHP_VERSION')) {
    define('NBDESIGNER_MINIMUM_PHP_VERSION', '5.4');
}
if (!defined('NBDESIGNER_PLUGIN_URL')) {
    define('NBDESIGNER_PLUGIN_URL', plugin_dir_url(__FILE__));
}
if (!defined('NBDESIGNER_PLUGIN_DIR')) {
    define('NBDESIGNER_PLUGIN_DIR', plugin_dir_path(__FILE__));
}
if (!defined('NBDESIGNER_PLUGIN_BASENAME')) {
    define('NBDESIGNER_PLUGIN_BASENAME', plugin_basename(__FILE__));
}
if (!defined('NBDESIGNER_MODE_DEBUG')) {
    define('NBDESIGNER_MODE_DEBUG', 'dev');
}
if (!defined('NBDESIGNER_DATA_DIR')) {   
    define('NBDESIGNER_DATA_DIR', $basedir . '/nbdesigner');
}
if (!defined('NBDESIGNER_DATA_URL')) {   
    define('NBDESIGNER_DATA_URL', $baseurl . '/nbdesigner');
}
if (!defined('NBDESIGNER_FONT_DIR')) {   
    define('NBDESIGNER_FONT_DIR', NBDESIGNER_DATA_DIR . '/fonts');
}
if (!defined('NBDESIGNER_FONT_URL')) {   
    define('NBDESIGNER_FONT_URL', NBDESIGNER_DATA_URL . '/fonts');
}
if (!defined('NBDESIGNER_DOWNLOAD_DIR')) {   
    define('NBDESIGNER_DOWNLOAD_DIR', NBDESIGNER_DATA_DIR . '/download');
}
if (!defined('NBDESIGNER_DOWNLOAD_URL')) {   
    define('NBDESIGNER_DOWNLOAD_URL', NBDESIGNER_DATA_URL . '/download');
}
if (!defined('NBDESIGNER_TEMP_DIR')) {   
    define('NBDESIGNER_TEMP_DIR', NBDESIGNER_DATA_DIR . '/temp');
}
if (!defined('NBDESIGNER_TEMP_URL')) {   
    define('NBDESIGNER_TEMP_URL', NBDESIGNER_DATA_URL . '/temp');
}
if (!defined('NBDESIGNER_ADMINDESIGN_DIR')) {   
    define('NBDESIGNER_ADMINDESIGN_DIR', NBDESIGNER_DATA_DIR . '/admindesign');
}
if (!defined('NBDESIGNER_ADMINDESIGN_URL')) {   
    define('NBDESIGNER_ADMINDESIGN_URL', NBDESIGNER_DATA_URL . '/admindesign');
}
if (!defined('NBDESIGNER_PDF_DIR')) {   
    define('NBDESIGNER_PDF_DIR', NBDESIGNER_DATA_DIR . '/pdfs');
}
if (!defined('NBDESIGNER_PDF_URL')) {   
    define('NBDESIGNER_PDF_URL', NBDESIGNER_DATA_URL . '/pdfs');
}
if (!defined('NBDESIGNER_CUSTOMER_DIR')) {   
    define('NBDESIGNER_CUSTOMER_DIR', NBDESIGNER_DATA_DIR . '/designs');
}
if (!defined('NBDESIGNER_CUSTOMER_URL')) {   
    define('NBDESIGNER_CUSTOMER_URL', NBDESIGNER_DATA_URL . '/designs');
}
if (!defined('NBDESIGNER_SUGGEST_DESIGN_DIR')) {   
    define('NBDESIGNER_SUGGEST_DESIGN_DIR', NBDESIGNER_DATA_DIR . '/suggest_designs');
}
if (!defined('NBDESIGNER_SUGGEST_DESIGN_URL')) {   
    define('NBDESIGNER_SUGGEST_DESIGN_URL', NBDESIGNER_DATA_URL . '/suggest_designs');
}
if (!defined('NBDESIGNER_DATA_CONFIG_DIR')) {   
    define('NBDESIGNER_DATA_CONFIG_DIR', NBDESIGNER_DATA_DIR . '/data');
}
if (!defined('NBDESIGNER_DATA_CONFIG_URL')) {   
    define('NBDESIGNER_DATA_CONFIG_URL', NBDESIGNER_DATA_URL . '/data');
}
if (!defined('NBDESIGNER_JS_URL')) {   
    define('NBDESIGNER_JS_URL', NBDESIGNER_PLUGIN_URL . 'assets/js/');
}
if (!defined('NBDESIGNER_CSS_URL')) {   
    define('NBDESIGNER_CSS_URL', NBDESIGNER_PLUGIN_URL . 'assets/css/');
}
if (!defined('NBDESIGNER_PRODUCT_TEMPLATES')) {   
    define('NBDESIGNER_TEMPLATES', 'nbdesigner_templates');
}
if (!defined('NBDESIGNER_CATEGORY_TEMPLATES')) {   
    define('NBDESIGNER_CATEGORY_TEMPLATES', 'nbdesigner_category_templates');
}
if (!defined('NBDESIGNER_AUTHOR_SITE')) {   
    define('NBDESIGNER_AUTHOR_SITE', 'https://cmsmart.net/');
}
if (!defined('NBDESIGNER_SKU')) {   
    define('NBDESIGNER_SKU', 'WPP1074');
}
if (!defined('NBDESIGNER_PAGE_REDESIGN')) {   
    define('NBDESIGNER_PAGE_REDESIGN', 'customer-redesign');
}
if (!defined('NBDESIGNER_PAGE_CREATE_TEMPLATE')) {   
    define('NBDESIGNER_PAGE_CREATE_TEMPLATE', 'designer-create-template');
}
if (!defined('NBDESIGNER_PAGE_STUDIO')) {   
    define('NBDESIGNER_PAGE_STUDIO', 'designer-studio');
}

require_once(NBDESIGNER_PLUGIN_DIR . 'includes/class-util.php');
require_once(NBDESIGNER_PLUGIN_DIR . 'includes/class-settings.php');
require_once(NBDESIGNER_PLUGIN_DIR . 'includes/class-debug.php');
require_once(NBDESIGNER_PLUGIN_DIR . 'includes/class-helper.php');
require_once(NBDESIGNER_PLUGIN_DIR . 'includes/table/class.product.templates.php');
require_once(NBDESIGNER_PLUGIN_DIR . 'includes/class.nbdesigner.php');

register_activation_hook( __FILE__, array( 'Nbdesigner_Plugin', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Nbdesigner_Plugin', 'plugin_deactivation' ) );
$prefix = is_network_admin() ? 'network_admin_' : '';       
add_filter( $prefix.'plugin_action_links_' . NBDESIGNER_PLUGIN_BASENAME, array('Nbdesigner_Plugin', 'nbdesigner_add_action_links') );
add_filter( 'plugin_row_meta', array( 'Nbdesigner_Plugin', 'nbdesigner_plugin_row_meta' ), 10, 2 );
if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}
$nb_designer = new Nbdesigner_Plugin();
$nb_designer->init();

require_once(NBDESIGNER_PLUGIN_DIR . 'includes/class-widget.php');

/**
 * With the upgrade to WordPress 4.7.1, some non-image files fail to upload on certain server setups. 
 * This will be fixed in 4.7.3, see the Trac ticket: https://core.trac.wordpress.org/ticket/39550
 * 
 */
if (version_compare($GLOBALS['wp_version'], '4.7.2', '<=')) {
    add_filter( 'wp_check_filetype_and_ext', 'wp39550_disable_real_mime_check', 10, 4 );
}
function wp39550_disable_real_mime_check( $data, $file, $filename, $mimes ) {
    $wp_filetype = wp_check_filetype( $filename, $mimes );
    $ext = $wp_filetype['ext'];
    $type = $wp_filetype['type'];
    $proper_filename = $data['proper_filename'];
    return compact( 'ext', 'type', 'proper_filename' );
}