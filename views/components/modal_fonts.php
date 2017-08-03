<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div class="modal fade nbdesigner_modal" id="dg-fonts">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button style="margin-top: 0;" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>	
                <div class="nbdesigner_art_modal_header">
                    <span>{{(langs['FONTS']) ? langs['FONTS'] : "Fonts"}}</span>
                    <input type="search" class="form-control hover-shadow" placeholder="Search Font" ng-model="fontName"/>
                    <div class="btn-group">
                        <button class="btn btn-primary dropdown-toggle shadow hover-shadow" type="button" data-toggle="dropdown">{{currentCatFontName}}&nbsp;<span class="caret"></span></button>
                        <ul class="dropdown-menu dropup  shadow hover-shadow">
                            <li ><a ng-click="loadGoogleFont()">{{(langs['GOOGLE_FONT']) ? langs['GOOGLE_FONT'] : "Google Font"}}</a></li>
                            <li role="separator" class="divider"></li>
                            <li ng-repeat="cat in fontCat">
                                <a ng-click="changeFontCat(cat)">{{cat.name}}</a>
                            </li>                            
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div id="nbdesigner_font_container">
                    <span class="nbdesigner_font" width="100" ng-repeat="font in AllFonts | filterCat : curentCatFont | filter : fontName| limitTo : fontPageSize" font-on-load>
                        <a ng-click="changeFont(font)"><span class="nbdesigner_font_name" ng-style="{'font-family': (font.alias) ? font.alias : font.name}" ng-attr-data-font="{{(font.alias) ? font.alias : font.name}}"><b>ABC xyz</b></span><br />
                        <span>{{font.name}}</span></a>
                    </span>
                </div>
                <div>
                    <button ng-show="(countFont > 10) && (countFont > fontPageSize)" style="margin-right: 15px; margin-top: 10px;" id="font-load-more" type="button" class="btn btn-primary shadow nbdesigner_upload" ng-click="changeFontPageSize(false)">{{(langs['MORE']) ? langs['MORE'] : "More"}}</button>
                    <img id="loading_font_upload" class="hidden" src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/css/images/ajax-loader.gif'; ?>" />
                </div>
            </div>
        </div>
    </div>
</div>