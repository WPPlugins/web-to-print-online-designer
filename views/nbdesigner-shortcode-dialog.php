<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div id="nbdesiger-tiny-mce-dialog" tabindex="-1" action="" title="" style="display: none; ">
    <div class="nbdesign-shortcode-row">
        <label for="nbdesigner-shortcode-number"><?php echo _e('Number of templates per row', 'nbdesigner'); ?></label>
        <input class="short" id="nbdesigner-shortcode-number" type="number" min="1" step="1" value="3">
    </div>
    <div class="nbdesign-shortcode-row">
        <label for="nbdesigner-pagination"><?php echo _e('Pagination', 'nbdesigner'); ?></label>
        <input id="nbdesigner-pagination" type="checkbox" checked="checked"> 
    </div>    
    <div id="nbdesigner-number-row" class="nbdesign-shortcode-row">
        <label for="nbdesigner-shortcode-row"><?php echo _e('Number of rows per page', 'nbdesigner'); ?></label>
        <input class="short" id="nbdesigner-shortcode-number-row" type="number" min="1" step="1" value="5"> 
    </div>
    <div class="nbdesign-shortcode-row">
        <button class="button-primary" id="nbdesigner-shortcode-create"><?php echo _e('Create', 'nbdesigner'); ?></button>
    </div>
</div>