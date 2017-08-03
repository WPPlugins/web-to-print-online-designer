<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if( !class_exists('Nbdesigner_Settings_General') ) {
    class Nbdesigner_Settings_General {
        public static function get_options() {
            return apply_filters('nbdesigner_general_settings', array(
                'general-settings' => array(      
                    array(
                        'title' => __('Position of button design', 'nbdesigner'),
                        'id' => 'nbdesigner_position_button_product_detail',
                        'default' => '1',
                        'description' => __( 'The position of the product button designer in the product page', 'nbdesigner' ),
                        'type' => 'radio',
                        'options' => array(
                            '1' => __('Before add to cart button and after variantions option', 'nbdesigner'),
                            '2' => __('Before variantions option', 'nbdesigner'),
                            '3' => __('After add to cart button', 'nbdesigner'),
                            '4' => __('Custom Hook, <code>echo do_shortcode( \'[nbdesigner_button id="Product ID"]\' );</code>', 'nbdesigner')
                        )
                    ),  
                    array(
                        'title' => __('Position of button in the catalog', 'nbdesigner'),
                        'id' => 'nbdesigner_position_button_in_catalog',
                        'default' => '1',
                        'description' => __( 'The position of the button in the catalog listing.', 'nbdesigner' ),
                        'type' => 'radio',
                        'options' => array(
                            '1' => __('Replace Add-to-Cart button', 'nbdesigner'),
                            '2' => __('End of catalog item', 'nbdesigner'),
                            '3' => __('Don\'t show', 'nbdesigner')
                        )
                    ),                    
                    array(
                        'title' => __( 'Preview thumbnail width', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_thumbnail_width',
                        'css'         => 'width: 65px',
                        'default'	=> '100',
                        'subfix'        => ' px',
                        'type' 		=> 'number'
                    ),
                    array(
                        'title' => __( 'Preview thumbnail height', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_thumbnail_height',
                        'css'         => 'width: 65px',
                        'default'	=> '100',
                        'subfix'        => ' px',
                        'type' 		=> 'number'
                    ),                    
                    array(
                        'title' => __( 'Thumbnail quality', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_thumbnail_quality',
                        'description' 	=> __('Quality of the generated thumbnails between 0 - 100', 'nbdesigner'),
                        'css'         => 'width: 65px',
                        'default'	=> '60',
                        'subfix'        => ' %',
                        'type' 		=> 'number'
                    ),
                    array(
                        'title' => __( 'Default output DPI', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_default_dpi',
                        'css'         => 'width: 65px',
                        'default'	=> '150',
                        'type' 		=> 'number'
                    ),                    
                    array(
                        'title' => __( 'Show customer design in cart', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_show_in_cart',
                        'description' 	=> __('Show the thumbnail of the customized product in the cart.', 'nbdesigner'),
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'nbdesigner'),
                            'no' => __('No', 'nbdesigner')
                        )                        
                    ),
                    array(
                        'title' => __( 'Show customer design in order', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_show_in_order',
                        'description' 	=> __('Show the thumbnail of the customized product in the order.', 'nbdesigner'),
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'nbdesigner'),
                            'no' => __('No', 'nbdesigner')
                        )                        
                    ),
                    array(
                        'title' => __( 'Dimensions Unit', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_dimensions_unit',
                        'description' 	=> __('This controls what unit you will define lengths in.', 'nbdesigner'),
                        'default'	=> 'cm',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'cm' => __('cm', 'nbdesigner'),
                            'in' => __('inch', 'nbdesigner'),
                            'mm' => __('mm', 'nbdesigner')
                        )                        
                    ),         
                    array(
                        'title' => __('Hide On Smartphones', 'nbdesigner'),
                        'description' => __('Hide product designer on smartphones and display an information instead.', 'nbdesigner'),
                        'id' => 'nbdesigner_disable_on_smartphones',
                        'default' => 'no',
                        'type' => 'radio',
                        'options' => array(
                            'yes' => __('Yes', 'nbdesigner'),
                            'no' => __('No', 'nbdesigner'),
                        )
                    )
                ),
                'admin-notifications' => array(
                    array(
                        'title' => __( 'Admin notifications', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_notifications',
                        'description' 	=> __('Send a message to the admin when customer design saved / changed.', 'nbdesigner'),
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'nbdesigner'),
                            'no' => __('No', 'nbdesigner')
                        )                        
                    ),
                    array(
                        'title' => __( 'Recurrence', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_notifications_recurrence',
                        'description' 	=> __('Choose how many times you want to receive an e-mail.', 'nbdesigner'),
                        'default'	=> 'hourly',
                        'type' 		=> 'select',
                        'options'   => array(
                            'hourly' => __('Hourly', 'nbdesigner'),
                            'twicedaily' => __('Twice a day', 'nbdesigner'),
                            'daily' => __('Daily', 'nbdesigner')
                        )
                    ),   
                    array(
                        'title' => __( 'Recipients', 'nbdesigner' ),
                        'description' 		=> __( 'Enter recipients (comma separated) for this email. Defaults to ', 'nbdesigner' ).'<code>'.get_option('admin_email').'</code>',
                        'id' 		=> 'nbdesigner_notifications_emails',
                        'class'         => 'regular-text',
                        'default'	=> '',
                        'type' 		=> 'text',
                        'placeholder'   => 'Enter your email'
                    ),                      
                ),
                'application'       => array(
                    array(
                        'title' => __( 'Facebook App-ID', 'nbdesigner' ),
                        'description' 		=> __( 'Enter a Facebook App-ID to allow customer use Facebook photos.', 'nbdesigner' ).' <a href="#" id="nbdesigner_show_helper">'.__("Where do I get this info?", 'nbdesigner').'</a>',
                        'id' 		=> 'nbdesigner_facebook_app_id',
                        'class'         => 'regular-text',
                        'default'	=> '',
                        'type' 		=> 'text'
                    )                   
                )
            ));
        }
    }
}