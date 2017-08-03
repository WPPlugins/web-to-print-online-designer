<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<?php
if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class Product_Template_List_Table extends WP_List_Table {
    public function __construct() {
        parent::__construct(array(
            'singular' => __('Template', 'nbdesigner'), 
            'plural' => __('Templates', 'nbdesigner'), 
            'ajax' => false 
        ));
    }
    /**
     * Retrieve template's data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function get_templates($per_page = 5, $page_number = 1) {
        global $wpdb;
        $sql = "SELECT * FROM {$wpdb->prefix}nbdesigner_templates";
        if (!empty($_REQUEST['pid'])) {
            $sql .= " WHERE product_id = " . esc_sql($_REQUEST['pid']);
        }   
        if (!empty($_REQUEST['nbdesigner_filter']) && -1 != $_REQUEST['nbdesigner_filter']) {
            if($_REQUEST['nbdesigner_filter'] == 'unpublish'){
                $sql .= " AND publish = 0";
            }else {
                $sql .= " AND ".esc_sql($_REQUEST['nbdesigner_filter'])." = 1";
            }            
        }          
        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .=!empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        } else {
            $sql .= ' ORDER BY created_date DESC';
        }       
        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
        $result = $wpdb->get_results($sql, 'ARRAY_A');    
        return $result;
    }
    /**
     * Delete a template record.
     *
     * @param int $id template ID
     */
    public static function delete_template($id) {
        if(current_user_can('delete_nbd_template')){
            global $wpdb;
            $item = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}nbdesigner_templates WHERE id = $id");
            if($item->folder == 'primary') return;
            $path = NBDESIGNER_ADMINDESIGN_DIR. '/' .$item->product_id. '/' .$item->folder;
            if (Nbdesigner_IO::delete_folder($path)) {
                $wpdb->delete("{$wpdb->prefix}nbdesigner_templates", array('id' => $id), array('%d'));
            }            
        }
    }
    public static function make_primary_template($id, $pid){
        global $wpdb;
        $item = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}nbdesigner_templates WHERE id = $id");
        $item_primary = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}nbdesigner_templates WHERE product_id = $pid AND folder = 'primary'");
        $path_primary = NBDESIGNER_ADMINDESIGN_DIR . '/' . $pid . '/primary'; 
        $path_primary_old = NBDESIGNER_ADMINDESIGN_DIR . '/' . $pid . '/primary_old'; 
        $path_primary_new = NBDESIGNER_ADMINDESIGN_DIR . '/' . $pid . '/' .$item->folder; 
        if(!rename($path_primary, $path_primary_old)) return 1; 
        if(!rename($path_primary_new, $path_primary)) return 1; 
        if(!rename($path_primary_old, $path_primary_new)) return 1;          
        self::update_template($id, array('priority' => 1, 'folder' => 'primary'));
        self::update_template($item_primary->id, array('priority' => 0, 'folder' => $item->folder));
    }
    public static function update_template($id, $arr){
        global $wpdb;
        $wpdb->update("{$wpdb->prefix}nbdesigner_templates", $arr, array( 'id' => $id) );
    }  
    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count() {
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}nbdesigner_templates";
        if (!empty($_REQUEST['pid'])) {
            $sql .= " WHERE product_id = " . esc_sql($_REQUEST['pid']);
        }      
        if (!empty($_REQUEST['nbdesigner_filter']) && -1 != $_REQUEST['nbdesigner_filter']) {
            if($_REQUEST['nbdesigner_filter'] == 'unpublish'){
                $sql .= " AND publish = 0";
            }else {
                $sql .= " AND ".esc_sql($_REQUEST['nbdesigner_filter'])." = 1";
            }            
        }         
        return $wpdb->get_var($sql);
    }
    /** Text displayed when no template data is available */
    public function no_items() {
        _e( 'No templates avaliable.', 'nbdesigner' );
    }
    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    function column_product_id($item) {
        $priority = $item['folder'] == 'primary' ? 'primary' : 'extra';
        $link_admindesign = getUrlPageNBD('template').'?product_id='.$item['product_id'].'&priority='.$priority.'&template_folder='.$item['folder'].'&task=edit_template';
        $_nonce = wp_create_nonce('nbdesigner_template_nonce');
        $title = '<strong>' . $item['folder'] . '</strong>';     
        $actions = array(
            'delete' => sprintf('<a href="?page=%s&action=%s&template=%s&_wpnonce=%s&pid=%s&paged=%s&view=templates">'.__('Delete', 'nbdesigner').'</a>', esc_attr($_REQUEST['page']), 'delete', absint($item['id']), $_nonce, esc_attr($_REQUEST['pid']), $this->get_pagenum()),
            'primary' => sprintf('<a href="?page=%s&action=%s&template=%s&pid=%s&_wpnonce=%s&paged=%s&view=templates">'.__('Primary', 'nbdesigner').'</a>', esc_attr($_REQUEST['page']), 'primary', absint($item['id']), esc_attr($_REQUEST['pid']), $_nonce, $this->get_pagenum()),
            'edit' => sprintf('<a href="%s" target="_blank">'.__('Edit', 'nbdesigner').'</a>', $link_admindesign)
        );     
        if($item['priority']){
            unset($actions['delete']);
            unset($actions['primary']);
        }
        return $title . $this->row_actions($actions);
    }
    function column_priority($item){
        if($item['priority']){
            return '<span class="primary">&#9733;</span>';
        }else{
            return '<span>&#9734;</span>';
        }     
    }
    function column_default($item, $column_name){
        return $item[$column_name];
    }    
    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     *
     * @return string
     */
    function column_cb($item) {
        return sprintf( '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id'] );
    }
    function column_folder($item){
        $html = '';
        $list_design = array(); 
        $mid_path = $item['product_id']. '/' .$item['folder']. '/preview/'; 
        $listThumb = Nbdesigner_IO::get_list_thumbs(NBDESIGNER_ADMINDESIGN_DIR. '/' .$item['product_id']. '/' .$item['folder']. '/preview/', 1);
        if(count($listThumb)){
            foreach ($listThumb as $img){
                $name = basename($img);
                $url = NBDESIGNER_ADMINDESIGN_URL.'/'.$mid_path.$name;
                $list_design[] = $url;
            }	                            
        } 
        foreach ($list_design as $src){
            $html .= '<img style="width: 60px; margin-right: 5px;" src="'.$src.'"/>';
        }
        return $html;
    }
    function column_user_id($item){
        $user = get_user_by( 'id', $item['user_id'] );
        return $user->display_name;
    }
    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />',            
            'folder' => __('Preview', 'nbdesigner'),
            'priority' => __('Primary', 'nbdesigner'),
            'product_id' => __('Folder', 'nbdesigner'),
            'user_id' => __('Created By', 'nbdesigner'),
            'created_date' => __('Created', 'nbdesigner')
        );
        return $columns;
    }
    function extra_tablenav( $which ) {
        global $wpdb;        
        if ($which == 'top') {
            ?>
            <select id="nbdesigner-admin-template-filter" name="nbdesigner_filter">
                <option value="-1"><?php _e('Show all design', 'nbdesigner'); ?></option>
                <option value="publish"><?php _e('Publish design', 'nbdesigner'); ?></option>
                <option value="unpublish"><?php _e('Unpublish design', 'nbdesigner'); ?></option>
                <option value="private"><?php _e('Private design', 'nbdesigner'); ?></option>
                <option value="priority"><?php _e('Primary design', 'nbdesigner'); ?></option>
            </select>
            <?php wp_nonce_field($this->plugin_id, $this->plugin_id . '_hidden'); ?>	
            <button class="button-primary" type="submit"><?php _e('Filter', 'nbdesigner'); ?></button>
            <?php
        }
    }    
    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'user_id' => array('user_id', true),
            'created_date' => array('created_date', true)
        );
        return $sortable_columns;
    }
    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions() {
        $actions = array(            
            'bulk-publish' => 'Publish',
            'bulk-unpublish' => 'Unpublish',
            'bulk-private' => 'Private',
            'bulk-delete' => 'Delete'
        );
        return $actions;
    }
    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        /** Process bulk action */
        $this->process_bulk_action();
        $per_page = $this->get_items_per_page('templates_per_page', 10);
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();
        $this->set_pagination_args(array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page' => $per_page //WE have to determine how many items to show on a page
        ));
        $this->items = self::get_templates($per_page, $current_page);
    }
    public function process_bulk_action() {
        if ('delete' === $this->current_action()) {    
            $nonce = esc_attr($_REQUEST['_wpnonce']);
            if (!wp_verify_nonce($nonce, 'nbdesigner_template_nonce')) {
                die('Go get a life script kiddies');
            }            
            self::delete_template(absint($_GET['template']));
            wp_redirect(esc_url_raw(add_query_arg(array('pid' => $_REQUEST['pid'], 'paged' => $this->get_pagenum(), 'view'  => 'templates'), admin_url('admin.php?page=nbdesigner_manager_product'))));
            exit;
        }      
        if ('primary' === $this->current_action()) {    
            $nonce = esc_attr($_REQUEST['_wpnonce']);
            if (!wp_verify_nonce($nonce, 'nbdesigner_template_nonce')) {
                die('Go get a life script kiddies');
            }            
            self::make_primary_template(absint($_GET['template']), absint($_GET['pid']));
            wp_redirect(esc_url_raw(add_query_arg(array('pid' => $_REQUEST['pid'], 'paged' => $this->get_pagenum(), 'view'  => 'templates'), admin_url('admin.php?page=nbdesigner_manager_product'))));
            exit;
        }          
        if (( isset($_POST['action']) && $_POST['action'] == 'bulk-delete' ) || ( isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete' )) {
            $delete_ids = esc_sql($_POST['bulk-delete']);
            foreach ($delete_ids as $id) {
                self::delete_template($id);
            }
            wp_redirect(esc_url_raw(add_query_arg('','')));
            exit;
        }
        if (( isset($_POST['action']) && $_POST['action'] == 'bulk-publish' ) || ( isset($_POST['action2']) && $_POST['action2'] == 'bulk-publish' )) {
            $delete_ids = esc_sql($_POST['bulk-delete']);
            foreach ($delete_ids as $id) {
                self::update_template($id, array('publish' => 1));
            }
            wp_redirect(esc_url_raw(add_query_arg('','')));
            exit;
        }        
        if (( isset($_POST['action']) && $_POST['action'] == 'bulk-unpublish' ) || ( isset($_POST['action2']) && $_POST['action2'] == 'bulk-unpublish' )) {
            $delete_ids = esc_sql($_POST['bulk-delete']);
            foreach ($delete_ids as $id) {
                self::update_template($id, array('publish' => 0));
            }
            wp_redirect(esc_url_raw(add_query_arg('','')));
            exit;
        } 
        if (( isset($_POST['action']) && $_POST['action'] == 'bulk-private' ) || ( isset($_POST['action2']) && $_POST['action2'] == 'bulk-private' )) {
            $delete_ids = esc_sql($_POST['bulk-delete']);
            foreach ($delete_ids as $id) {
                self::update_template($id, array('private' => 1, 'publish' => 0));
            }
            wp_redirect(esc_url_raw(add_query_arg('','')));
            exit;
        }         
    }

}