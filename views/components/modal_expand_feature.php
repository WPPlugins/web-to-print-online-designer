<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div id="dg-expand-feature"  class="modal fade nbdesigner_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 15px;">
                <b>{{(langs['TEMPLATE_DESIGNS']) ? langs['TEMPLATE_DESIGNS'] : "Template Designs"}}</b>
                <button style="margin-top: 0;" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>	
            </div>            
            <div class="modal-body" >
                <span ng-click="resetAdminDesign()" class="shadow hover-shadow feature-button" data-toggle="tooltip" data-original-title="{{(langs['RESET_TEMPLATE']) ? langs['RESET_TEMPLATE'] : 'Reset Template'}}"><i class="fa fa-refresh" aria-hidden="true"></i></span>
                <span ng-click="loadAdminListDesign()" class="shadow hover-shadow feature-button" data-toggle="tooltip" data-original-title="{{(langs['LOAD_TEMPLATE']) ? langs['LOAD_TEMPLATE'] : 'Load Template'}}"><i class="fa fa-search" aria-hidden="true"></i></span>
                <span ng-click="exportDesign()" class="shadow hover-shadow feature-button" data-toggle="tooltip" data-original-title="{{(langs['EXPORT_DESIGN_TO_JSON']) ? langs['EXPORT_DESIGN_TO_JSON'] : 'Export design to JSON'}}"><i class="fa fa-cloud-download" aria-hidden="true"></i></span>
                <span ng-click="toggleImportArea()" class="shadow hover-shadow feature-button" data-toggle="tooltip" data-original-title="{{(langs['IMPORT_DESIGN_FROM_JSON']) ? langs['IMPORT_DESIGN_FROM_JSON'] : 'Import design from JSON'}}"><i class="fa fa-cloud-upload" aria-hidden="true"></i></span>
                <div class="design-editor-container" id="design-editor-container">
                    <textarea class="design-editor" rows="10" id="design-json-content" placeholder="{{(langs['PASTE_CONTENT_JSON_FILE']) ? langs['PASTE_CONTENT_JSON_FILE'] : 'Paste content in file design.json'}}"></textarea>
                    <button ng-click="importDesign()" class="btn btn-primary shadow nbdesigner_upload btn-dialog">{{(langs['APPLY']) ? langs['APPLY'] : "Apply"}}</button>
                </div>
                <div class="nbdesigner-list-template" id="nbdesigner-list-template">  
                    <h3>Have {{_.size(adminListTemplate)}} templates</h3>
                    <img ng-repeat="tem in adminListTemplate | limitTo : adminTemplatePageSize" ng-src="{{tem['src']}}" class="nbdesigner-template shadow hover-shadow" ng-click="loadExtraAdminDesign(tem['id'])"/>
                    <div ng-show="_.size(adminListTemplate) > 8 && _.size(adminListTemplate) > adminTemplatePageSize">
                        <button class="btn btn-primary shadow nbdesigner_upload btn-dialog" ng-click="adminTemplatePageSize = adminTemplatePageSize + 8">{{(langs['MORE']) ? langs['MORE'] : "More"}}</button>
                        &nbsp;{{(langs['DISPLAYED']) ? langs['DISPLAYED'] : "Displayed"}} {{(adminTemplatePageSize < _.size(adminListTemplate)) ? adminTemplatePageSize : _.size(adminListTemplate)}}/{{_.size(adminListTemplate)}}
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>