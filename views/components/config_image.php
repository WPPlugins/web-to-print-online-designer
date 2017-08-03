<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div id="config_image" class="shadow od_tab nbdesigner_config" ng-style="{'display': pop.art}">
    <ul class="config_list" id="image_config_list">
        <li><a href="#image_dimension"><span class="fa fa-picture-o" aria-hidden="true"></span></a></li>       
        <li><a href="#image_filter1"><span class="filter1 fa fa-filter" aria-hidden="true"></span></a></li>
        <li><a href="#image_filter2"><span class="filter2 fa fa-filter" aria-hidden="true"></span></a></li>
        <li><a href="#image_filter3"><span class="filter3 fa fa-filter" aria-hidden="true"></span></a></li>
        <li><a href="#image_filter4"><span class="filter4 fa fa-filter" aria-hidden="true"></span></a></li>	
        <li><a href="#image_general"><span class="fa fa-cog" aria-hidden="true"></span></a></li>
    </ul>    
    <div class="list-indicator"></div>
    <div id="image_dimension" class="nbdesigner_config_content content">
        <div class="nb-col-30" ng-show="settings['nbdesigner_image_unlock_proportion'] == 1">
            <p class="label-config">{{(langs['UNLOCK_PROPORTION']) ? langs['UNLOCK_PROPORTION'] : "Unlock proportion"}}</p>
            <div class="switch">
                <input id="text-lock" class="cmn-toggle cmn-toggle-round" type="checkbox" ng-model="lockProportion"  ng-change="unlockProportion()">
                <label for="text-lock"></label>
            </div>  
        </div>	        
        <div class="nb-col-40 has-popover-option" style="padding-left: 15px;" ng-show="settings['nbdesigner_image_shadow'] == 1">
            <p class="label-config">{{(langs['SHADOW']) ? langs['SHADOW'] : "Shadow"}}</p>
                <?php  if($enableColor == 'yes'): ?>
                <input readonly="true" disabled class="jscolor shadow hover-shadow" ng-model="shadow.color" ng-change="changeShadow()">
                <?php else: ?>
                <spectrum-colorpicker
                    ng-model="shadow.color" 
                    ng-change="changeShadow()" 
                    options="{
                        showPaletteOnly: true, 
                        togglePaletteOnly: false, 
                        hideAfterPaletteSelect:true,
                        palette: colorPalette}">
                </spectrum-colorpicker>
                <?php endif; ?>                   
                <span class="toggle-popup-option fa fa-chevron-down hover-shadow e-shadow c-option"></span>
                <div class="popup-option">
                    <div class="inner">
                        <div class="nb-col-3">
                            <p class="label-config">{{(langs['DIMENSION_X']) ? langs['DIMENSION_X'] : "Dimension X"}}</p>
                            <div class="container-dg-slider"><div class="dg-slider" id="image_shadow_x"></div></div>					
                        </div>
                        <div class="nb-col-3">
                            <p class="label-config">{{(langs['DIMENSION_Y']) ? langs['DIMENSION_Y'] : "Dimension y"}}</p>
                            <div class="container-dg-slider"><div class="dg-slider" id="image_shadow_y"></div></div>					
                        </div>
                        <div class="nb-col-3">
                            <p class="label-config">{{(langs['SHADOW_BLUR']) ? langs['SHADOW_BLUR'] : "Shadow blur"}}</p>
                            <div class="container-dg-slider"><div class="dg-slider" id="image_shadow_blur"></div></div>					
                        </div>
                        <div class="nb-col-3">
                            <p class="label-config">{{(langs['OPACITY']) ? langs['OPACITY'] : "Opacity"}}</p>
                            <div class="container-dg-slider"><div class="dg-slider" id="image_shadow_alpha"></div></div>						
                        </div>				
                    </div>
                    <div class="after"></div>
                </div>            
        </div>
        <div class="nb-col-30" ng-show="settings['nbdesigner_image_opacity'] == 1">
            <p class="label-config">{{(langs['OPACITY']) ? langs['OPACITY'] : "Opacity"}}</p>
            <div class="container-dg-slider"><div class="dg-slider" id="image_opacity"></div></div>						
        </div>
    </div>
    <div id="image_general" class="content">
        <div class="nb-col-4" ng-show="settings['nbdesigner_image_rotate'] == 1">
            <p class="label-config">{{(langs['ROTATE']) ? langs['ROTATE'] : "Rotate"}}</p>
            <div class="rotation-text"><input type="text" id="rotation-image" data-min="0" data-max="359"></div>
        </div> 
        <div class="nb-col-8">
            <p class="label-config">{{(langs['GENERAL_SETTING']) ? langs['GENERAL_SETTING'] : "General Setting"}}</p>
            <div>
                <span ng-show="settings['nbdesigner_image_crop'] == 1" class="text-update btn btn-default btn-xs fa fa-crop" data-target="#dg-crop-image" data-toggle="modal" title="{{(langs['CROP_IMAGE']) ? langs['CROP_IMAGE'] : 'Crop Image'}}" ng-click="initCropCanvas('crop')">&nbsp;{{(langs['CROP_IMAGE']) ? langs['CROP_IMAGE'] : "Crop Image"}}</span>  
                <span ng-show="settings['nbdesigner_image_shapecrop'] == 1" class="text-update btn btn-default btn-xs fa fa-star" data-target="#dg-crop-image" data-toggle="modal" title="{{(langs['SHAPE_IMAGE']) ? langs['SHAPE_IMAGE'] : 'Shape Image'}}" ng-click="initCropCanvas('shape')">&nbsp;{{(langs['SHAPE_IMAGE']) ? langs['SHAPE_IMAGE'] : "Shape Image"}}</span>  
                <span class="text-update btn btn-default btn-xs  fa fa-eraser" data-toggle="tooltip" title="Back to original image" ng-click="resetImage()">&nbsp;{{(langs['RESET_IMAGE']) ? langs['RESET_IMAGE'] : "Reset Image"}}</span>                
            </div>
<!--            <div class="switch">
                <input id="remove-color-filter" data-filter="Remove color" class="cmn-toggle cmn-toggle-round" type="checkbox">
                <label for="remove-color-filter"></label>                
            </div>            -->
        </div>         
    </div>
    <div id="image_filter1" class="content">
        <div class="nb-col-3" ng-show="settings['nbdesigner_image_grayscale'] == 1">
            <p class="label-config">{{(langs['GRAYSCALE']) ? langs['GRAYSCALE'] : "Grayscale"}}</p>
            <div class="switch">
                <input id="grayscale-filter" data-filter="Grayscale" class="cmn-toggle cmn-toggle-round" type="checkbox">
                <label for="grayscale-filter"></label>                  
            </div>
        </div>  
        <div class="nb-col-3" ng-show="settings['nbdesigner_image_invert'] == 1">
            <p class="label-config">{{(langs['INVERT']) ? langs['INVERT'] : "Invert"}}</p>
            <div class="switch">
                <input id="invert-filter" data-filter="Invert" class="cmn-toggle cmn-toggle-round" type="checkbox">
                <label for="invert-filter"></label>                
            </div>
        </div>  
        <div class="nb-col-3" ng-show="settings['nbdesigner_image_sepia'] == 1">
            <p class="label-config">{{(langs['SEPIA']) ? langs['SEPIA'] : "Sepia"}}</p>
            <div class="switch">
                <input id="sepia-filter" data-filter="Sepia" class="cmn-toggle cmn-toggle-round" type="checkbox">
                <label for="sepia-filter"></label>                 
            </div>
        </div>  
        <div class="nb-col-3" ng-show="settings['nbdesigner_image_sepia2'] == 1">
            <p class="label-config">{{(langs['SEPIA']) ? langs['SEPIA'] : "Sepia"}} 2</p>
            <div class="switch">
                <input id="sepia2-filter" data-filter="Sepia 2" class="cmn-toggle cmn-toggle-round" type="checkbox">
                <label for="sepia2-filter"></label>                
            </div>
        </div> 
    </div>
    <div id="image_filter2" class="content">
        <div class="nb-col-3 has-popover-option" ng-show="settings['nbdesigner_image_remove_white'] == 1">
            <p class="label-config">{{(langs['REMOVE_WHITE']) ? langs['REMOVE_WHITE'] : "Remove white"}}</p>
            <div class="switch nb-col-6 nbdesigner_mg5">
                <input id="remove-white-filter" data-filter="Remove white" class="cmn-toggle cmn-toggle-round" type="checkbox">
                <label for="remove-white-filter"></label>             
            </div>
            <span class="toggle-popup-option fa fa-chevron-down hover-shadow e-shadow c-option" style="margin-left: 7px;"></span>
            <div class="popup-option">
                <div class="inner">
                    <div class="nb-col-4">
                        <p class="label-config">{{(langs['THRESHOLD']) ? langs['THRESHOLD'] : "Threshold"}}</p>
                        <div class="filter_slider dg-slider remove-white-threshold"></div>
                        <input  type="hidden" data-parent="remove-white" data-type="threshold" id="remove-white-threshold" value="0">					
                    </div>
                    <div class="nb-col-4">
                        <p class="label-config">{{(langs['DISTANCE']) ? langs['DISTANCE'] : "Distance"}}</p>
                        <div class="filter_slider dg-slider remove-white-distance"></div>
                        <input type="hidden" data-parent="remove-white" data-type="distance" id="remove-white-distance" value="0"> 					
                    </div>				
                </div>
                <div class="after"></div>
            </div>            
        </div> 
        <div class="nb-col-3 has-popover-option" ng-show="settings['nbdesigner_image_transparency'] == 1">
            <p class="label-config">{{(langs['TRANSPARENCY']) ? langs['TRANSPARENCY'] : "Transparency"}}</p>
            <div class="switch nb-col-6 nbdesigner_mg5">
                <input id="gradient-transparency-filter" data-filter="Gradient Transparency" class="cmn-toggle cmn-toggle-round" type="checkbox">
                <label for="gradient-transparency-filter"></label>     
            </div>
            <span class="toggle-popup-option fa fa-chevron-down hover-shadow e-shadow c-option" style="margin-left: 7px;"></span>
            <div class="popup-option">
                <div class="inner">
                    <div class="nb-col-4">
                        <p class="label-config">{{(langs['VALUE']) ? langs['VALUE'] : "Value"}}</p>
                        <div class="filter_slider dg-slider gradient-transparency-value"></div>
                        <input type="hidden" data-parent="gradient-transparency"  data-type="threshold" id="gradient-transparency-value" value="0">   					
                    </div>                    
                </div>
            </div>          
        </div>
        <div class="nb-col-3 has-popover-option" ng-show="settings['nbdesigner_image_tint'] == 1">
            <p class="label-config">{{(langs['TINT']) ? langs['TINT'] : "Tint"}}</p>
            <div class="switch nb-col-6 nbdesigner_mg5">
                <input id="tint-filter" data-filter="Tint" class="cmn-toggle cmn-toggle-round" type="checkbox">
                <label for="tint-filter"></label>                  
            </div>
            <span class="toggle-popup-option fa fa-chevron-down hover-shadow e-shadow c-option" style="margin-left: 7px;"></span>
            <div class="popup-option">
                <div class="inner">
                    <div class="nb-col-4">
                        <p class="label-config">{{(langs['COLOR']) ? langs['COLOR'] : "Color"}}</p>
                        <div>
                            <input readonly="true" disabled class="ip_shadow jscolor" data-parent="tint" data-type="color" id="tint-color" value="F98332">
                        </div>				
                    </div>
                    <div class="nb-col-4">
                        <p class="label-config">{{(langs['OPACITY']) ? langs['OPACITY'] : "Opacity"}}</p>
                        <div class="filter_slider dg-slider tint-opacity"></div>
                        <input type="hidden"  data-parent="tint" data-type="opacity" id="tint-opacity" value="0">					
                    </div>				
                </div>
                <div class="after"></div>
            </div>             
        </div>
        <div class="nb-col-3 has-popover-option" ng-show="settings['nbdesigner_image_blend'] == 1">
            <p class="label-config">{{(langs['BLEND']) ? langs['BLEND'] : "Blend"}}</p>
            <div class="switch nb-col-6 nbdesigner_mg5">
                <input id="blend-filter" data-filter="Blend" class="cmn-toggle cmn-toggle-round" type="checkbox">
                <label for="blend-filter"></label>                  
            </div>
            <span class="toggle-popup-option fa fa-chevron-down hover-shadow e-shadow c-option" style="margin-left: 7px;"></span>  
            <div class="popup-option">
                <div class="inner">
                    <div class="nb-col-3">
                        <p class="label-config">{{(langs['COLOR']) ? langs['COLOR'] : "Color"}}</p>
                        <div>
                            <input readonly="true" disabled class="ip_shadow jscolor" data-parent="blend" data-type="color" id="blend-color" value="F98332">
                        </div>				
                    </div>   
                    <div class="nb-col-6">   
                        <div class="btn-group blend_mode_option">
                            <button class="btn btn-primary dropdown-toggle shadow hover-shadow" type="button" data-toggle="dropdown">{{(langs['BLEND_MODE']) ? langs['BLEND_MODE'] : "Blend mode"}}&nbsp;<span class="caret"></span></button>
                            <ul class="dropdown-menu dropup  shadow hover-shadow">
                                <li><a href="javascript:void(0);" onclick="changeBlendMode('add')">{{(langs['ADD']) ? langs['ADD'] : "Add"}}</a></li>
                                <li><a href="javascript:void(0);" onclick="changeBlendMode('diff')">{{(langs['DIFF']) ? langs['DIFF'] : "Diff"}}</a></li>
                                <li><a href="javascript:void(0);" onclick="changeBlendMode('subtract')">{{(langs['SUBTRACT']) ? langs['SUBTRACT'] : "Subtract"}}</a></li>
                                <li><a href="javascript:void(0);" onclick="changeBlendMode('multiply')">{{(langs['MULTIPLY']) ? langs['MULTIPLY'] : "Multiply"}}</a></li>
                                <li><a href="javascript:void(0);" onclick="changeBlendMode('screen')">{{(langs['SCREEN']) ? langs['SCREEN'] : "Screen"}}</a></li>
                                <li><a href="javascript:void(0);" onclick="changeBlendMode('lighten')">{{(langs['LIGHTTEN']) ? langs['LIGHTTEN'] : "Lighten"}}</a></li>                                        
                                <li><a href="javascript:void(0);" onclick="changeBlendMode('darken')">{{(langs['DARKEN']) ? langs['DARKEN'] : "Darken"}}</a></li>                                        
                            </ul>
                        </div>                          
                    </div>				
                </div>
                <div class="after"></div>
            </div>             
        </div>
    </div>
    <div id="image_filter3" class="content">
        <div class="nb-col-3" ng-show="settings['nbdesigner_image_brightness'] == 1">
            <p class="label-config">{{(langs['BRIGHTNESS']) ? langs['BRIGHTNESS'] : "Brightness"}}</p>
            <div class="switch">
                <input id="brightness-filter" data-filter="Brightness" class="cmn-toggle cmn-toggle-round" type="checkbox">
                <label for="brightness-filter"></label>         
            </div>
            <p class="label-config">{{(langs['VALUE']) ? langs['VALUE'] : "Value"}}</p>
            <div class="filter_slider dg-slider brightness-value"></div>
            <input type="hidden" data-parent="brightness"  data-type="brightness" id="brightness-value" value="0">            
        </div> 
        <div class="nb-col-3" ng-show="settings['nbdesigner_image_noise'] == 1">
            <p class="label-config">{{(langs['NOISE']) ? langs['NOISE'] : "Noise"}}</p>
            <div class="switch">
                <input id="noise-filter" data-filter="Noise" class="cmn-toggle cmn-toggle-round" type="checkbox">
                <label for="noise-filter"></label>         
            </div>
            <p class="label-config">{{(langs['VALUE']) ? langs['VALUE'] : "Value"}}</p>
            <div class="filter_slider dg-slider noise-value"></div>
            <input type="hidden"  data-parent="noise"  data-type="noise" id="noise-value" value="0">           
        </div> 
        <div class="nb-col-3" ng-show="settings['nbdesigner_image_pixelate'] == 1">
            <p class="label-config">{{(langs['PIXELATE']) ? langs['PIXELATE'] : "Pixelate"}}</p>
            <div class="switch">
                <input id="pixelate-filter" data-filter="Pixelate" class="cmn-toggle cmn-toggle-round" type="checkbox">
                <label for="pixelate-filter"></label>         
            </div>
            <p class="label-config">{{(langs['VALUE']) ? langs['VALUE'] : "Value"}}</p>
            <div class="filter_slider dg-slider pixelate-value"></div>
            <input type="hidden"  data-parent="pixelate"  data-type="blocksize" id="pixelate-value" value="0">          
        </div> 
        <div class="nb-col-3" ng-show="settings['nbdesigner_image_multiply'] == 1">
            <p class="label-config">{{(langs['MULTIPLY']) ? langs['MULTIPLY'] : "Multiply"}}</p>
            <div class="switch">
                <input id="multiply-filter" data-filter="Multiply" class="cmn-toggle cmn-toggle-round" type="checkbox">
                <label for="multiply-filter"></label>         
            </div>
            <p class="label-config">{{(langs['COLOR']) ? langs['COLOR'] : "Color"}}</p>
            <input readonly="true" disabled class="ip_shadow jscolor" data-parent="multiply" data-type="color" id="multiply-color" value="F98332">        
        </div>        
    </div>
    <div id="image_filter4" class="content">
        <p style="color: red; font-size: 11px">
            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;{{(langs['FILTER_WARNING']) ? langs['FILTER_WARNING'] : "Filters bellow need more time to process! Happy wait"}}!</p>
        <div class="nb-col-3" ng-show="settings['nbdesigner_image_blur'] == 1">
            <p class="label-config">{{(langs['BLUR']) ? langs['BLUR'] : "Blur"}}</p>
            <div class="switch">
                <input id="blur-filter" data-filter="Blur" class="cmn-toggle cmn-toggle-round" type="checkbox">
                <label for="blur-filter"></label>       
            </div>            
        </div> 
        <div class="nb-col-3" ng-show="settings['nbdesigner_image_sharpen'] == 1">
            <p class="label-config">{{(langs['SHARPEN']) ? langs['SHARPEN'] : "Sharpen"}}</p>
            <div class="switch">
                <input id="sharpen-filter" data-filter="Sharpen" class="cmn-toggle cmn-toggle-round" type="checkbox">
                <label for="sharpen-filter"></label>      
            </div>            
        </div>         
        <div class="nb-col-3" ng-show="settings['nbdesigner_image_emboss'] == 1">
            <p class="label-config">{{(langs['EMBROSS']) ? langs['EMBROSS'] : "Emboss"}}</p>
            <div class="switch">
                <input id="emboss-filter" data-filter="Emboss" class="cmn-toggle cmn-toggle-round" type="checkbox">
                <label for="emboss-filter"></label>        
            </div>            
        </div> 
        <div class="nb-col-3" ng-show="settings['nbdesigner_image_edge_enhance'] == 1">
            <p class="label-config">{{(langs['EDGE_ENHANCE']) ? langs['EDGE_ENHANCE'] : "Edge enhance"}}</p>
            <div class="switch">
                <input id="edge-enhance-filter" data-filter="Edge enhance" class="cmn-toggle cmn-toggle-round" type="checkbox">
                <label for="edge-enhance-filter"></label>         
            </div>            
        </div>        
    </div>
</div>