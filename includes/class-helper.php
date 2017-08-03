<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(!class_exists('Nbdesigner_Helper')){
    class Nbdesigner_Helper{
        public static function settings_helper(){
            $screen = get_current_screen();
            $screen->add_help_tab( array(
                'id'		=> 'backend',
                'title'		=> __('Backend', 'nbdesigner'),
                'content'	=>
                    '<h2>' . __('Backend setting', 'nbdesigner') . '</h2>' .
                    '<iframe width="560" height="315" src="https://www.youtube.com/embed/zegY2it0w3k?rel=0" frameborder="0" allowfullscreen></iframe>'      
            ));
            $screen->add_help_tab( array(
                'id'		=> 'frontend',
                'title'		=> __('Frontend', 'nbdesigner'),
                'content'	=>
                    '<h2>' . __('Frontend setting', 'nbdesigner') . '</h2>' .
                    '<iframe width="560" height="315" src="https://www.youtube.com/embed/FLv_kMV3jv0?rel=0" frameborder="0" allowfullscreen></iframe>'          
            ));       
            $screen->add_help_tab( array(
                'id'		=> 'facebook',
                'title'		=> __('Facebook API', 'nbdesigner'),
                'content'	=>
                    '<h2>'. __('How to get Facebook API ID', 'nbdesigner') .'</h3>'
                    . __('<span style="color:#CB4B16;">Application</span> Key and Secret (also sometimes referred as <span style="color:#CB4B16;">Consumer</span> key and secret or <span style="color:#CB4B16;">Client</span> id and secret) are what we call an application credentials.', 'nbdesigner')
                    . __('This application will link your website ','nbdesigner').'<code>'.$_SERVER["SERVER_NAME"].'</code> '. __('to <code>Facebook API</code> and these credentials are needed in order for <b>NBDesigner</b> users to use they Facebook photos.','nbdesigner')
                    .'<br />'. __("These credentials may also differ in format, name and content depending on the social network.", 'nbdesigner')
                    .'<br /><br />'. __('To enable authentication with this provider and to register a new <b>Facebook API Application</b>, follow the steps', 'nbdesigner') .':<br />'
                    .'<div style="margin-left:40px;"><p><b>1. </b>'. __('First go to: ', 'nbdesigner').'<a href="https://developers.facebook.com/apps">https://developers.facebook.com/apps</a></p>'
                    .'<p><b>2. </b>'. __('Select <b>Add a New App</b> from the <b>Apps</b> menu at the top', 'nbdesigner') . '</p>'
                    .'<p><b>3. </b>'. __('Fill out Display Name, Namespace, choose a category and click <b>Create App</b>', 'nbdesigner') .  '</p>'
                    .'<p><b>4. </b>' . __('Go to Settings page and click on <b>Add Platform</b>. Choose website and enter in the new screen your website url in <b>App Domains</b> and <b>Site URL</b> fields', 'nbdesigner')
                    . __('They should match with the current hostname', 'nbdesigner') .' <em style="color:#CB4B16;">'.$_SERVER["SERVER_NAME"]. '</em>.</p>'
                    .'<p><b>5. </b>' . __('Go to the <b>App Review</b> page and choose <b>yes</b> where it says <b>Do you want to make this app and all its live features available to the general public?</b>', 'nbdesigner')
                    . __('In section "Submit Items for Approval" click <b>Start a Submission</b>, in popup check "user_photos" and complete all steps', 'nbdesigner') . '</p>'
                    .'<p><b>6. </b>'. __('Go back to the <b>Dashboard</b> page and past the created application credentials (APP ID and Secret) into the boxes above', 'nbdesigner').'</p>'
                    . '</div><hr /><div><p><b>'. __("And that's it! ", 'nbdesigner')
                    . __( 'If for some reason you still can\'t manage to create an application for Facebook, first try to <a href="https://www.google.com/search?q=Facebook API create application" target="_blank">Google it</a>, then check it on <a href="http://www.youtube.com/results?search_query=Facebook API create application " target="_blank">Youtube</a>, and if nothing works <a href="https://cmsmart.net/support_ticket">ask for support</a>', 'nbdesigner')
                    . '</p></div>'
            ));
            $screen->set_help_sidebar(
                '<p><strong>' . __('For more information', 'nbdesigner') . ':</strong></p>' .
                '<p>' . __('<a class="button" href="https://cmsmart.net/support_ticket" target="_blank">Support ticket</a>') . '</p>' .
                '<p>' . __('<a class="button" href="https://cmsmart.net/forum/" target="_blank">Forum</a>') . '</p>' 
            );               
        }
        public static function setting_product_helper(){
            //TODO
        }
    }
}