<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly ?>
<div class="nbdesigner_frontend_container">
<p>
    <?php 
        $label = __('Create Template', 'nbdesigner'); 
        if((isset($_GET['p']) && $_GET['p'] == 'primary') || isset($_GET['redesign'])) $label = __('Edit Template', 'nbdesigner'); 
    ?>
    <a class="button nbdesign-button nbdesigner-disable" id="triggerDesign" ><img class="nbdesigner-img-loading rotating" src="<?php  echo NBDESIGNER_PLUGIN_URL.'assets/images/loading.png'; ?>"/><?php echo $label; ?></a>    
    <a class="button" id="nbdesign-new-template" style="display: none;"><?php _e('Add Template', 'nbdesigner'); ?></a>    
</p>
<div style="position: fixed; top: 0; left: 0; z-index: 999999; opacity: 0; width: 100%; height: 100%;" id="container-online-designer">
    <iframe id="onlinedesigner-designer"  
            width="100%" height="100%" 
            scrolling="no" frameborder="0" noresize="noresize" 
            allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true" src="<?php echo $src_iframe; ?>">               
    </iframe><span id="closeFrameDesign"  class="nbdesigner_pp_close">&times;</span>    
</div>
<h4><?php _e('Preview your design', 'nbdesigner'); ?></h4>    
<div id="nbdesigner_frontend_area">
<?php foreach ($list_image as $img): 
    $src = Nbdesigner_IO::secret_image_url($img);
?>
    <div class="img-con"><img src="<?php echo $src; ?>" /></div>
<?php endforeach;  ?>
</div>
</div>
<div class="nbdesigner-list-template">
    
</div>