<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div id="config-style" class="shadow">  
    <div class="config-style-con">
        <div>
            <div class="bg-style">
                <h3>{{(langs['CHOOSE_STYLE']) ? langs['CHOOSE_STYLE'] : "Choose style"}}</h3>
                <img ng-click="changeBackgroundId(n)" ng-repeat="n in [] | range: 55" ng-src="<?php echo NBDESIGNER_PLUGIN_URL ?>assets/images/background/{{n}}.png" class="bg-style-tem"/>      
            </div>
        </div>    
        <span id="toggle-config-style"><i class="fa fa-cog"></i></span>   
    </div>    
</div>