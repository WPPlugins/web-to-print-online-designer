<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div id="dg-cliparts" class="modal fade nbdesigner_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button style="margin-top: 0;" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>	
                <div class="nbdesigner_art_modal_header">
                    <span>{{(langs['CLIPARTS']) ? langs['CLIPARTS'] : "Cliparts"}}</span>
                    <input type="search" class="form-control hover-shadow" placeholder="{{(langs['SEARCH_ART']) ? langs['SEARCH_ART'] : 'Search Art'}}" ng-model="artName"/>
                    <div class="btn-group">
                        <button class="btn btn-primary dropdown-toggle shadow hover-shadow" type="button" data-toggle="dropdown">{{currentCatArtName}}&nbsp;<span class="caret"></span></button>
                        <ul class="dropdown-menu dropup  shadow hover-shadow nbd-cat">
                            <li ng-repeat="cat in artCat">
                                <a ng-click="changeArtCat(cat)">{{cat.name}}<span class="nbd-align-right">{{cat.amount}}</span></a>
                            </li>                            
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div id="nbdesigner_art_container">
                    <span class="view-thumb nbdesigner_thumb" width="100" ng-repeat="art in arts | filterCat : curentCatArt | filter : artName| limitTo : artPageSize">
                        <img style="max-width: 100px; max-height: 100px;" class="img-responsive img-thumbnail nbdesigner_upload_image shadow hover-shadow" ng-src="{{art.url}}" ng-click="addArt(art)"  spinner-on-load/>
                    </span>
                </div>
                <div>
                    <button ng-show="(countArt > 10) && (countArt > artPageSize)" style="margin-right: 15px; margin-top: 10px;" id="art-load-more" type="button" class="btn btn-primary shadow nbdesigner_upload" ng-click="loadMoreArt()">{{(langs['MORE']) ? langs['MORE'] : "More"}}</button>
                    <img id="loading_art_upload" class="hidden" src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/css/images/ajax-loader.gif'; ?>" />
                </div>
            </div>
        </div>
    </div>
</div>