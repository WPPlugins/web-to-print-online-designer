<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div id="dg-config-art" class="modal fade nbdesigner_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 15px;">
                <b>{{(langs['SVG_PATH_MANAGER']) ? langs['SVG_PATH_MANAGER'] : "SVG Path Manager"}}</b>
                <button style="margin-top: 0;" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>	
            </div>   
            <div class="modal-body nbdesigner_config_svg">
                <?php  if($enableColor == 'yes'): ?>
               <input readonly="true"  data-jscolor="{zIndex: 9999}" disabled class="jscolor shadow hover-shadow" ng-repeat="color in editable.paths " ng-model="pathColor[color.key]" ng-change="updatePathColor(color)" ng-style="{'background-color': getColorCode(color.fill)}" path-art-directive>                                  
               <?php else: ?>
                <spectrum-colorpicker
                    ng-repeat="color in editable.paths"
                    ng-model="pathColor[color.key]" 
                    ng-change="updatePathColor(color)"                     
                    options="{
                        showPaletteOnly: true, 
                        togglePaletteOnly: false, 
                        hideAfterPaletteSelect:true,
                        palette: colorPalette}">
                </spectrum-colorpicker>    
               <?php endif; ?>  
            </div>
        </div>
    </div>
</div>