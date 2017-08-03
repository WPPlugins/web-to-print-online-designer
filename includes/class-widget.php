<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class NBDesigner_Widget extends WP_Widget {
    public $textdomain;
    function __construct() {
        parent::__construct(
                'nbdesigner_widget', 
                esc_html__('Product design', 'nbdesigner'),
                array('description' => esc_html__('Product design suggest', 'nbdesigner')) 
        );        
    }
    public function widget($args, $instance) {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        if (empty($instance['number'])){
            $number = 3;
        } else {
            $number = $instance['number'];
        }      
        $args_query = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'meta_key' => '_nbdesigner_enable',
            'orderby' => 'date',
            'orderby' => 'rand',
            'posts_per_page'=>$number,
            'meta_query' => array(
                array(
                    'key' => '_nbdesigner_enable',
                    'value' => 1,
                )
            )
        ); 
        $posts = get_posts($args_query);  
        if(is_array($posts)){    
            ob_start();            
            nbdesigner_get_template('widget-suggest-design.php', array('products' => $posts));
            $content = ob_get_clean();
            echo $content;
        }
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Related product design', 'nbdesigner');
        $number = !empty($instance['number']) ? $instance['number'] : 3;
        ?>
            <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'nbdesigner'); ?></label> 
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_attr_e('Number of products to show', 'nbdesigner'); ?></label> 
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="text" value="<?php echo esc_attr($number); ?>">
            </p>            
        <?php
    }
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        $instance['number'] = (!empty($new_instance['number']) ) ? absint($new_instance['number']) : 3;
        return $instance;
    }
}
function register_nbdesigner_widget() {
    register_widget( 'NBDesigner_Widget' );
}
add_action( 'widgets_init', 'register_nbdesigner_widget' );