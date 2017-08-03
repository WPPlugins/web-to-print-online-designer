<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div class=" shadow popup" id="container_layer">
    <h3>{{(langs['LAYERS']) ? langs['LAYERS'] : "Layers"}}
        <span class="pull-right">
            <span class="fa fa-angle-up close-popover shadow" ng-click="setStackPosition('bringForward')"></span>
            <span class="fa fa-angle-double-up close-popover shadow" ng-click="setStackPosition('bringToFront')"></span>
            <span class="fa fa-angle-double-down close-popover shadow" ng-click="setStackPosition('sendToBack')"></span>
            <span class="fa fa-angle-down close-popover shadow" ng-click="setStackPosition('sendBackwards')"></span>
            <span class="fa fa-trash close-popover shadow delete-all" ng-click="deleteAllItem()"></span>
        </span>
        <span id="_close_popover" class="fa fa-backward"></span>
    </h3>
    <div id="dg-layers" class="has-croll">
        <ul id="layers">
            <li class="shadow layer" id="layer-{{layer.index}}" ng-repeat="layer in currentLayers| reverse" ng-class="{active: currentLayerActive === layer.index, 'lock' : layer.class === 'lock'}" ng-click="activeLayer(layer)">
                <i class="fa fa-font" aria-hidden="true" ng-show="layer.type === 'text'"></i>  		
                <img alt="image uploaded" ng-src="{{layer.src}}" ng-hide="layer.type === 'text'" width="20" height="20" class="layer_thumb"/>
                <span>{{layer.name}}</span>
                <span class="pull-right">
                    <a style="margin-right: 10px;" class="nbdesigner_visible_layer" href="javascript:void(0)" ng-click="toggleVisibleLayer(layer)"><i class="fa" aria-hidden="true" ng-class="layer.class === 'lock' ? 'fa-eye-slash' : 'fa-eye'"></i></a>
                    <a style="margin-right: 10px;" class="nbdesigner_lock_layer" href="javascript:void(0)" ng-click="toggleLockLayer(layer)"><i class="fa" aria-hidden="true" ng-class="layer.class === 'lock' ? 'fa-lock' : 'fa-unlock-alt'"></i></a>
                    <a ng-click="deleteLayer(layer)" href="javascript:void(0)">
                        <i class="fa fa-trash-o delete"></i>
                    </a>       
                </span>
            </li>                                               
        </ul>
    </div>
    <div class="container-dg-slider" ng-show="currentLayers.length > 5"><div class="dg-slider" id="scroll-layer-slider"></div></div>
</div>