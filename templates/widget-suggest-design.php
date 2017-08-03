<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
echo '<ul class="nbdesigner_list_widget">';
foreach ($products as $p){
    $pro = wc_get_product($p->ID);
?>
    <li class="nbdesigner-related-product">
        <div class="nbdesigner-related-product-item">
            <a data-id="<?php echo $pro->id; ?>" href="<?php echo esc_url( get_permalink( $pro->id ) ); ?>" class="nbdesigner-related-product-image">
                <?php echo $pro->get_image(); ?>
            </a>
            <a href="<?php echo esc_url( get_permalink( $pro->id ) ); ?>"><span class="nbdesigner-related-product-title"><?php echo esc_attr( $pro->get_title() ); ?></span></a>
            <div class="nbdesigner-overlay">
                <img src="<?php echo NBDESIGNER_PLUGIN_URL . 'assets/images/loading.png' ?>" class="rotating"/>
            </div>
        </div>    
    </li>
<?php
}
echo '</ul>';
?>