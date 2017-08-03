<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(!class_exists('Nbdesigner_Settings_Frontend')){
    class Nbdesigner_Settings_Frontend {
        public static function get_options() {
            return apply_filters('nbdesigner_design_tool_settings', array(
                'tool-text' => array(
                    array(
                        'title' => __( 'Enable tool Add Text', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_enable_text',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'nbdesigner'),
                            'no' => __('No', 'nbdesigner')
                        ) 
                    ),  
                    array(
                        'title' => __( 'Default text', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_default_text',
                        'default'	=> 'Text here',
                        'description' 		=> __( 'Default text when user add text', 'nbdesigner' ),
                        'type' 		=> 'text',
                        'class'         => 'regular-text',
                    ),  
                    array(
                        'title' => __( 'Default color', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_default_color',
                        'default'	=> '#cc324b',
                        'description' 		=> sprintf(__( 'Default color text when user add text. If you\'re using limited color palette, make sure <a href="%s">this color</a> has been defined', 'nbdesigner' ), esc_url(admin_url('admin.php?page=nbdesigner&tab=color'))),
                        'type' 		=> 'colorpicker'
                    ),                     
                    array(
                        'title' => __( 'Enable Curved Text', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_enable_curvedtext',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'nbdesigner'),
                            'no' => __('No', 'nbdesigner')
                        ) 
                    ),   
                    array(
                        'title' => __( 'Enable Text pattern', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_enable_textpattern',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'nbdesigner'),
                            'no' => __('No', 'nbdesigner')
                        ) 
                    ),  
                    array(
                        'title' => __( 'Show/hide features', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_option_text',
                        'default'	=> 'text',
                        'description' 	=> __( 'Show/hide features in frontend', 'nbdesigner' ),
                        'type' 		=> 'multicheckbox',
                        'class'         => 'regular-text',
                        'options'   => array(
                            'nbdesigner_text_change_font' => __('Change font', 'nbdesigner'),
                            'nbdesigner_text_italic' => __('Italic', 'nbdesigner'),
                            'nbdesigner_text_bold' => __('Bold', 'nbdesigner'),
                            'nbdesigner_text_underline' => __('Underline', 'nbdesigner'),
                            'nbdesigner_text_through' => __('Line-through', 'nbdesigner'),
                            'nbdesigner_text_overline' => __('Overline', 'nbdesigner'),
                            'nbdesigner_text_align_left' => __('Align left', 'nbdesigner'),
                            'nbdesigner_text_align_right' => __('Align right', 'nbdesigner'),
                            'nbdesigner_text_align_center' => __('Align center', 'nbdesigner'),
                            'nbdesigner_text_color' => __('Text color', 'nbdesigner'),
                            'nbdesigner_text_background' => __('Text background', 'nbdesigner'),
                            'nbdesigner_text_shadow' => __('Text shadow', 'nbdesigner'),
                            'nbdesigner_text_line_height' => __('Line height', 'nbdesigner'),
                            'nbdesigner_text_font_size' => __('Font size', 'nbdesigner'),
                            'nbdesigner_text_opacity' => __('Opacity', 'nbdesigner'),
                            'nbdesigner_text_outline' => __('Outline', 'nbdesigner'),
                            'nbdesigner_text_proportion' => __('Unlock proportion', 'nbdesigner'),
                            'nbdesigner_text_rotate' => __('Rotate', 'nbdesigner')
                        ),
                        'css' => 'margin: 0 15px 10px 5px;'
                    )                      
                ),
                'tool-clipart' => array(
                    array(
                        'title' => __( 'Enable tool Add Clipart', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_enable_clipart',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'nbdesigner'),
                            'no' => __('No', 'nbdesigner')
                        ) 
                    ),  
                    array(
                        'title' => __( 'Show/hide features', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_option_clipart',
                        'default'	=> 'clipart',
                        'description' 	=> __( 'Show/hide features in frontend', 'nbdesigner' ),
                        'type' 		=> 'multicheckbox',
                        'class'         => 'regular-text',
                        'options'   => array(
                            'nbdesigner_clipart_change_path_color' => __( 'Change color path', 'nbdesigner' ),      
                            'nbdesigner_clipart_rotate' => __( 'Rotate', 'nbdesigner' ),      
                            'nbdesigner_clipart_opacity' => __( 'Opacity', 'nbdesigner' )
                        ),
                        'css' => 'margin: 0 15px 10px 5px;'
                    )                     
                ),
                'tool-image' => array(
                    array(
                        'title' => __( 'Enable tool Add Image', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_enable_image',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'nbdesigner'),
                            'no' => __('No', 'nbdesigner')
                        ) 
                    ), 
                    array(
                        'title' => __( 'Enable upload image', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_enable_upload_image',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'nbdesigner'),
                            'no' => __('No', 'nbdesigner')
                        ) 
                    ),           
                    array(
                        'title' => __('Login Required', 'nbdesigner'),
                        'description' => __('Users must create an account in your Wordpress site and need to be logged-in to upload images.', 'nbdesigner'),
                        'id' => 'nbdesigner_upload_designs_php_logged_in',
                        'default' => 'no',
                        'type' => 'checkbox'
                    ),                    
                    array(
                        'title' => __( 'Max size upload', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_maxsize_upload',
                        'css'         => 'width: 65px',
                        'default'	=> '5',
                        'subfix'        => ' MB',
                        'type' 		=> 'number'
                    ),    
                    array(
                        'title' => __( 'Min size upload', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_minsize_upload',
                        'css'         => 'width: 65px',
                        'default'	=> '0',
                        'subfix'        => ' MB',
                        'type' 		=> 'number'
                    ),
                    array(
                        'title' => __( 'Enable images from url', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_enable_image_url',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'nbdesigner'),
                            'no' => __('No', 'nbdesigner')
                        ) 
                    ), 
                    array(
                        'title' => __( 'Enable capture images by webcam', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_enable_image_webcam',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'nbdesigner'),
                            'no' => __('No', 'nbdesigner')
                        ) 
                    ),                    
                    array(
                        'title' => __( 'Enable Facebook photos', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_enable_facebook_photo',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'nbdesigner'),
                            'no' => __('No', 'nbdesigner')
                        ) 
                    ),    
                    array(
                        'title' => __('Show terms and conditions', 'nbdesigner'),
                        'description' => __('Show term and conditions upload image.', 'nbdesigner'),
                        'id' => 'nbdesigner_upload_show_term',
                        'default' => 'no',
                        'type' => 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'nbdesigner'),
                            'no' => __('No', 'nbdesigner')
                        )                         
                    ),                    
                    array(
                        'title' => __( 'Terms and conditions upload image', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_upload_term',
                        'default'	=> 'example.com',
                        'type' 		=> 'textarea',
                        'description'      => __('HTML Tags Supported', 'nbdesigner'),
                        'css'         => 'width: 25em; height: 5em;'
                    ),  
                    array(
                        'title' => __( 'Show/hide features', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_option_image',
                        'default'	=> 'image',
                        'description' 	=> __( 'Show/hide features in frontend', 'nbdesigner' ),
                        'type' 		=> 'multicheckbox',
                        'class'         => 'regular-text',
                        'options'   => array(
                            'nbdesigner_image_unlock_proportion' => __( 'Unlock proportion', 'nbdesigner' ),        
                            'nbdesigner_image_shadow' => __( 'Shadow', 'nbdesigner' ),        
                            'nbdesigner_image_opacity' => __( 'Opacity', 'nbdesigner' ),          
                            'nbdesigner_image_grayscale' => __( 'Grayscale', 'nbdesigner' ),          
                            'nbdesigner_image_invert' => __( 'Invert', 'nbdesigner' ),         
                            'nbdesigner_image_sepia' => __( 'Sepia', 'nbdesigner' ),         
                            'nbdesigner_image_sepia2' => __( 'Sepia 2', 'nbdesigner' ),           
                            'nbdesigner_image_remove_white' => __( 'Remove white', 'nbdesigner' ),     
                            'nbdesigner_image_transparency' => __( 'Transparency', 'nbdesigner' ),           
                            'nbdesigner_image_tint' => __( 'Tint', 'nbdesigner' ),          
                            'nbdesigner_image_blend' => __( 'Blend mode', 'nbdesigner' ),           
                            'nbdesigner_image_brightness' => __( 'Brightness', 'nbdesigner' ),          
                            'nbdesigner_image_noise' => __( 'Noise', 'nbdesigner' ),         
                            'nbdesigner_image_pixelate' => __( 'Pixelate', 'nbdesigner' ),         
                            'nbdesigner_image_multiply' => __( 'Multiply', 'nbdesigner' ),     
                            'nbdesigner_image_blur' => __( 'Blur', 'nbdesigner' ),          
                            'nbdesigner_image_sharpen' => __( 'Sharpen', 'nbdesigner' ),         
                            'nbdesigner_image_emboss' => __( 'Emboss', 'nbdesigner' ),         
                            'nbdesigner_image_edge_enhance' => __( 'Edge enhance', 'nbdesigner' ),          
                            'nbdesigner_image_rotate' => __( 'Rotate', 'nbdesigner' ),         
                            'nbdesigner_image_crop' => __( 'Crop image', 'nbdesigner' ),         
                            'nbdesigner_image_shapecrop' => __( 'Shape crop', 'nbdesigner' ),  
                        ),
                        'css' => 'margin: 0 15px 10px 5px;'
                    )                    
                ),
                'tool-draw' => array(
                    array(
                        'title' => __( 'Enable Free Draw', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_enable_draw',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'nbdesigner'),
                            'no' => __('No', 'nbdesigner')
                        ) 
                    ),    
                    array(
                        'title' => __( 'Show/hide features', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_option_clipart',
                        'default'	=> 'clipart',
                        'description' 	=> __( 'Show/hide features in frontend', 'nbdesigner' ),
                        'type' 		=> 'multicheckbox',
                        'class'         => 'regular-text',
                        'options'   => array(
                            'nbdesigner_draw_brush' => __('Brush', 'nbdesigner'),         
                            'nbdesigner_draw_shape' => __('Geometrical shape', 'nbdesigner')
                        ),
                        'css' => 'margin: 0 15px 10px 5px;'
                    )                       
                ),   
                'tool-qrcode' => array(
                    array(
                        'title' => __( 'Enable QRCode', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_enable_qrcode',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'nbdesigner'),
                            'no' => __('No', 'nbdesigner')
                        ) 
                    ),
                    array(
                        'title' => __( 'Default text', 'nbdesigner' ),
                        'id' 		=> 'nbdesigner_default_qrcode',
                        'default'	=> 'example.com',
                        'description' 	=> __( 'Default text for QRCode', 'nbdesigner' ),
                        'type' 		=> 'text',
                        'class'         => 'regular-text',
                    )                     
                ),                
            ));
        }
    }    
}