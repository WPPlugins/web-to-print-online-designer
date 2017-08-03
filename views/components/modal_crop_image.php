<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div id="dg-crop-image" class="modal fade nbdesigner_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 15px;">
                <b>{{(langs['CROP_IMAGE']) ? langs['CROP_IMAGE'] : "Crop image"}}</b>
                <button style="margin-top: 0;" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>	
            </div>
            <div class="modal-body container_crop_canvas">
                <canvas id="crop_canvas"></canvas>
                <canvas id="shape_canvas" style="visibility: hidden;"></canvas>
            </div>
            <div class="modal-footer">
                <div class="pull-left nbdesigner_crop_footer">
                    <span class="nbdesigner_button  shadow hover-shadow fa fa-crop" ng-click="cropTool()" ng-show="cropToolMode"></span>
                    <span class="nbdesigner_button  shadow hover-shadow fa fa-pencil" ng-click="lassoTool()" data-toggle="tooltip" title="Lasso Toll" ng-show="cropToolMode"></span>
                    <span class="shap_mark" ng-click="clipPath(1)" ng-show="clipPathMode"><img src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/images/mark/mark_01.svg'; ?>" /></span>
                    <span class="shap_mark" ng-click="clipPath(2)" ng-show="clipPathMode"><img src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/images/mark/mark_02.svg'; ?>" /></span>
                    <span class="shap_mark" ng-click="clipPath(3)" ng-show="clipPathMode"><img src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/images/mark/mark_03.svg'; ?>" /></span>
                    <span class="shap_mark" ng-click="clipPath(4)" ng-show="clipPathMode"><img src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/images/mark/mark_04.svg'; ?>" /></span>
                    <span class="shap_mark" ng-click="clipPath(5)" ng-show="clipPathMode"><img src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/images/mark/mark_05.svg'; ?>" /></span>
                    <span class="shap_mark" ng-click="clipPath(6)" ng-show="clipPathMode"><img src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/images/mark/mark_06.svg'; ?>" /></span>
                    <span class="shap_mark" ng-click="clipPath(7)" ng-show="clipPathMode"><img src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/images/mark/mark_07.svg'; ?>" /></span>
                    <span class="shap_mark" ng-click="clipPath(8)" ng-show="clipPathMode"><img src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/images/mark/mark_08.svg'; ?>" /></span>
                    <span class="shap_mark" ng-click="clipPath(9)" ng-show="clipPathMode"><img src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/images/mark/mark_09.svg'; ?>" /></span>
                    <span class="shap_mark" ng-click="clipPath(10)" ng-show="clipPathMode"><img src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/images/mark/mark_10.svg'; ?>" /></span>
                    <span class="shap_mark" ng-click="clipPath(11)" ng-show="clipPathMode"><img src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/images/mark/mark_11.svg'; ?>" /></span>
                    <span class="shap_mark" ng-click="clipPath(12)" ng-show="clipPathMode"><img src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/images/mark/mark_12.svg'; ?>" /></span>
                    <span class="shap_mark" ng-click="clipPath(13)" ng-show="clipPathMode"><img src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/images/mark/mark_13.svg'; ?>" /></span>
                </div>
                <div class="pull-right">
                    <span style="background: #2bd4a5; border-radius: 50%;" class="nbdesigner_button  shadow hover-shadow fa fa-check" ng-click="cropImage()"></span>
                </div>
            </div>
        </div>
    </div>
</div>