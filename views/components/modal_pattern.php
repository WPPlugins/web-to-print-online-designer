<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div class="modal fade" id="dg-pattern">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 15px;">
                <b>{{(langs['PATTERN']) ? langs['PATTERN'] : "Pattern"}}</b>
                <button style="margin-top: 0;" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>						
            </div>
            <div class="modal-body" style="padding: 15px;">
                <div class="list-pattern" id="pattern-boddy">
                    <img class="pattern_img shadow hover-shadow" ng-repeat="pattern in patterns | limitTo : patternPageSize" ng-src="{{ajustUrl(pattern.path)}}" spinner-on-load ng-click="changePattern(pattern.path)"/>
                </div>
                <p><button class="btn btn-primary shadow nbdesigner_upload" style="margin-right: 15px; margin-top: 10px;" ng-show="patterns.length > patternPageSize" ng-click="patternPageSize = patternPageSize + 10">{{(langs['MORE']) ? langs['MORE'] : "More"}}</button>
                    <img id="loading_pattern" class="hidden" src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/css/images/ajax-loader.gif'; ?>" /></p>
            </div>
        </div>
    </div>
</div>