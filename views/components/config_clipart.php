<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div id="config_art" class="shadow od_tab nbdesigner_config" ng-style="{'display': pop.clipArt}">
    <ul class="config_list" id="art_config_list">
        <li ng-show="settings['nbdesigner_clipart_change_path_color'] == 1"><a href="#svg_path"><span class="fa fa-tint" aria-hidden="true"></span></a></li>
        <li><a href="#svg_config"><span class="fa fa-cog" aria-hidden="true"></span></a></li>        
    </ul>
    <div class="list-indicator"></div>
    <div id="svg_path" class="nbdesigner_config_content content" ng-show="settings['nbdesigner_clipart_change_path_color'] == 1">
        <span class="shadow hover-shadow text-item" ng-click="showPathConfig()" data-target="#dg-config-art" data-toggle="modal">{{(langs['MANAGER_PATH']) ? langs['MANAGER_PATH'] : "Manager Path"}}</span>
    </div>
    <div id="svg_config" class="nbdesigner_config_content content" style="padding-bottom: 0;">
        <div class="nb-col-30" ng-show="settings['nbdesigner_clipart_rotate'] == 1">
            <p class="label-config label-rotate">{{(langs['ROTATE']) ? langs['ROTATE'] : "Rotate"}}</p>
            <div class="rotation-text"><input type="text" id="rotation-svg" data-min="0" data-max="359"></div>
        </div>  
        <div class="nb-col-30" ng-show="settings['nbdesigner_clipart_opacity'] == 1">
            <p class="label-config">{{(langs['OPACITY']) ? langs['OPACITY'] : "Opacity"}}</p>
            <div class="container-dg-slider"><div class="opacity-slider dg-slider" id="opacity_svg"></div></div>					
        </div>         
    </div>
</div>