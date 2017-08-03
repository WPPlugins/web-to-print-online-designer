<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div id="dg-preview"  class="modal fade nbdesigner_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 15px;">
                <b>{{(langs['PREVIEW_DESIGN']) ? langs['PREVIEW_DESIGN'] : "Preview Design"}}</b>
                <button style="margin-top: 0;" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>	
            </div>            
            <div class="modal-body" >
                <img ng-src="{{currentPreview}}" class="img_preview magniflier" id="img_preview"/>
                <span class="shadow hover-shadow full_screen_preview fa fa-crosshairs"></span>
            </div>
        </div>
    </div>
</div>