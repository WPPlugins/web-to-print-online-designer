<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div id="tool-top">
    <span class="fa fa-book shadow help first_visitor nbd-tooltip-i18n" data-lang="HELPDESK" data-placement="left"></span>
    <span class="fa fa-globe shadow translate nbd-tooltip-i18n" data-lang="LANGUAGE" data-placement="left" style="font-size: 20px;"></span>
<!--    <span id="show_grid" ng-hide="modeMobile" class="fa fa-th shadow hover-shadow" ng-click="showGrid()"></span>-->    
<!--    <span id="mobile" ng-show="modeMobile" class="fa fa-eye shadow hover-shadow"></span>-->
    <span id="debug" ng-show="state == 'dev'" class="fa fa-magic shadow hover-shadow" ng-click="debug()"></span>
    <span id="show_grid" ng-hide="modeMobile" class="fa fa-search shadow hover-shadow nbd-tooltip-i18n" data-lang="PREVIEW" data-placement="left"  data-toggle="modal" data-target="#dg-preview" ng-click="preview()"></span>    
    <span class="fa fa-plus shadow hover-shadow nbd-tooltip-i18n" aria-hidden="true" data-lang="ZOOM_IN" data-placement="left"  ng-click="zoomIn()"></span>
    <span class="fa fa-minus shadow hover-shadow nbd-tooltip-i18n" aria-hidden="true" data-lang="ZOOM_OUT" data-placement="left"  ng-click="zoomOut()"></span>	
    <span id="expand_feature" class="fa fa-ellipsis-h shadow hover-shadow nbd-tooltip-i18n" data-lang="TEMPLATE" data-placement="left"  data-toggle="modal" data-target="#dg-expand-feature" ></span>
    <span class="fa fa-paint-brush shadow hover-shadow nbd-tooltip-i18n" aria-hidden="true" data-lang="DISABLE_DRAW_MODE" data-placement="left" ng-click="disableDrawMode()" ng-show="canvas.isDrawingMode" ng-class="canvas.isDrawingMode ? 'disabledraw' : ''"></span>  
    <span class="fa fa-object-group shadow hover-shadow deactive-group nbd-tooltip-i18n" data-lang="DESELECT_GROUP" data-placement="left" aria-hidden="true"  ng-click="deactiveGroup()" ng-show="showAlignToolbar"></span>
</div>
<div class="first_message hover-shadow">
    {{(langs['HI_THERE']) ? langs['HI_THERE'] : "Hi there"}}, <br />
    {{(langs['IM_HELPER']) ? langs['IM_HELPER'] : "I'm Helper! If you need any help"}}...
</div>
<div class="translate-switch hover-shadow shadow" ng-show="langCategories.length > 1">
    <ul>
        <li ng-repeat="cat in langCategories" ng-click="loadLanguage(cat.code)" ng-class="{open : currentCatLang === cat.code}">{{cat.name}}</li>
    </ul>
</div>