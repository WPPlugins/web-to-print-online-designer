<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({
        appId      : '<?php echo nbdesigner_get_option('nbdesigner_facebook_app_id'); ?>',
        status     : true, 
        cookie     : true,      
        xfbml      : true,
        version    : 'v2.7'
    });
  };

  (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));    
</script>
<div class="fb-login-button" data-max-rows="1" data-size="medium" data-show-faces="false" data-auto-logout-link="false" data-scope="user_photos" onlogin="nbdesigner_fb1(null)"></div>