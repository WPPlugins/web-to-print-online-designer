<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div class="viewport" ng-style="{'width': designerWidth, 'height': designerHeight, 'left': offset,
                                           'min-width' : '320px',
                                           'min-height' : '320px'}">
    <div class="view_container">
        <div class="design-image" >
            <div class="container-image" ng-repeat="img in currentVariant.activeImages"
                 ng-style="{
                    'width' : calcDimension(img.img_src_width),
                    'height' : calcDimension(img.img_src_height),
                    'left' : calcLeft(img.img_src_left),
                    'top' : calcLeft(img.img_src_top),
                    'background' : (img.bg_type == 'color') ? img.bg_color_value : ''
                 }"
            >
                <img ng-show="img.bg_type == 'image'" ng-src="{{img.img_src}}"  spinner-on-load ng-style="{'width': '100%', 'height': '100%'}" />
            </div>                               
        </div>
        <div class="grid-area">
            <canvas id="grid"></canvas>
        </div>	

        <div class="design-aria" 
             ng-style="{'width': currentVariant.designArea['area_design_width'] * zoom * designScale,
					   'height' : currentVariant.designArea['area_design_height'] * zoom * designScale,
					   'top' : calcLeft(currentVariant.designArea['area_design_top']),
					   'left' : calcLeft(currentVariant.designArea['area_design_left'])
				}"
             >
            <canvas id="designer-canvas" width="500" height="500"></canvas> 
        </div>	
        <div id="replace-element-upload">
            <i class="fa fa-share-square-o nbd-tooltip-i18n" aria-hidden="true" data-lang="REPLACE_IMAGE" ng-click="preReplaceImage()"></i>
        </div>        
    </div>
</div>
<div class="top-center-menu" ng-style="{'width': designerWidth, 'left': offset}">   
    <i class="toolbar-menu undo-redo nbd-icon-undo2 nbd-tooltip-i18n" data-placement="bottom" data-lang="UNDO" ng-click="undoDesign()" ng-class="orientationActiveUndoStatus ? 'ready' : ''"></i>
    <i class="toolbar-menu undo-redo nbd-icon-redo2 nbd-tooltip-i18n" data-placement="bottom" data-lang="REDO" ng-click="redoDesign()" ng-class="orientationActiveRedoStatus ? 'ready' : ''"></i>
    
    <span class="toolbar-menu fa fa-arrows nbd-tooltip nbd-tooltip-i18n" aria-hidden="true" data-tooltip-content="#tooltip_group_align" data-lang="ALIGN" data-placement="bottom" ng-show="showAlignToolbar"></span>
    <span class="toolbar-menu fa fa-cloud-download nbd-tooltip nbd-tooltip-i18n" aria-hidden="true" data-tooltip-content="#tooltip_download_preview" data-lang="DOWNLOAD" data-placement="bottom" ng-show="state == 'dev'"></span>
    <span class="toolbar-menu fa fa-th nbd-tooltip-i18n" aria-hidden="true"  data-lang="SNAP_GRID" data-placement="bottom" ng-click="snapGrid()"></span>
    <span class="toolbar-menu fa fa-lock nbd-tooltip nbd-tooltip-i18n" aria-hidden="true" data-tooltip-content="#tooltip_lock_param"  data-lang="LOCK" data-placement="bottom" ng-click="getStatusItem()" ng-show="canvas.getActiveObject() && (task === 'create_template' || task === 'edit_template')"></span>
    <span class="toolbar-menu fa fa-cloud-upload nbd-tooltip-i18n" aria-hidden="true"  data-lang="ELEMENT_UPLOAD" data-placement="bottom" ng-show="editableItem !== null && (editable.type === 'image' || editable.type === 'custom-image') && (task === 'create_template' || task === 'edit_template')" ng-click="setElementUpload()"></span>
    
    <div style="display: none;">
        <div id="tooltip_group_align">
            <i class="toolbar-menu align-group nbd-icon-align-left nbd-tooltip-i18n" data-lang="ALIGN_LEFT" data-placement="top" ng-click="alignGroupLeft()"></i>
            <i class="toolbar-menu align-group nbd-icon-align-right nbd-tooltip-i18n" data-lang="ALIGN_RIGHT" data-placement="top" ng-click="alignGroupRight()"></i>
            <i class="toolbar-menu align-group nbd-icon-align-top nbd-tooltip-i18n" data-lang="ALIGN_TOP" data-placement="top" ng-click="alignGroupTop()"></i>
            <i class="toolbar-menu align-group  nbd-icon-align-bottom nbd-tooltip-i18n" data-lang="ALIGN_BOTTOM" data-placement="top"  ng-click="alignGroupBottom()"></i>
            <i class="toolbar-menu align-group  nbd-icon-align-vertical-middle nbd-tooltip-i18n" data-lang="ALIGN_MIDDLE_VERTICAL" data-placement="top" ng-click="alignGroupVer()"></i>
            <i class="toolbar-menu align-group  nbd-icon-align-horizontal-middle nbd-tooltip-i18n" data-lang="ALIGN_MIDDLE_HORIZONTAL" data-placement="top" data-container="body" ng-click="alignGroupHor()"></i>
        </div>
        <div id="tooltip_download_preview">
            <i class="toolbar-menu download-file nbd-icon-document-file-png nbd-tooltip-i18n" data-lang="PNG" data-placement="top" ng-click="downloadPNG()"></i>
            <i class="toolbar-menu download-file nbd-icon-document-file-jpg nbd-tooltip-i18n" data-lang="JPG" data-placement="top" ng-click="downloadJPG()"></i>
            <i class="toolbar-menu download-file nbd-icon-document-file-pdf nbd-tooltip-i18n" data-lang="PDF" data-placement="top" ng-click="downloadPDF()"></i>
        </div>
        <div id="tooltip_lock_param">
            <i class="toolbar-menu fa fa-lock nbd-tooltip-i18n" data-lang="LOCK_ALL_ADJUSMENT" data-placement="top" ng-click="lockItem('a')" ng-class="!editable.selectable ? 'active' : ''"></i>
            <i class="toolbar-menu fa fa-arrows-h nbd-tooltip-i18n" data-lang="LOCK_HORIZONTAL_MOVEMENT" data-placement="top" ng-click="lockItem('x')" ng-class="editable.lockMovementX ? 'active' : ''"></i>
            <i class="toolbar-menu fa fa-arrows-v nbd-tooltip-i18n" data-lang="LOCK_VERTITAL_MOVEMENT" data-placement="top" ng-click="lockItem('y')" ng-class="editable.lockMovementY ? 'active' : ''"></i>
            <i class="toolbar-menu fa fa-expand nbd-tooltip-i18n" data-lang="LOCK_HORIZONTAL_SCALING" data-placement="top" ng-click="lockItem('sx')" ng-class="editable.lockScalingX ? 'active' : ''"><sub>x</sub></i>
            <i class="toolbar-menu fa fa-expand nbd-tooltip-i18n" data-lang="LOCK_VERTITAL_SCALING" data-placement="top" ng-click="lockItem('sy')" ng-class="editable.lockScalingY ? 'active' : ''"><sub>y</sub></i>
            <i class="toolbar-menu fa fa-undo nbd-tooltip-i18n" data-lang="LOCK_ROTATION" data-placement="top" ng-click="lockItem('r')" ng-class="editable.lockRotation ? 'active' : ''"></i>
        </div>
    </div>      
</div>
<div id="frame" ng-style="{'top': designerWidth + 2, 'width': calcWidthThumb(_.size(currentVariant.info)) * 50, 'margin-left': -(calcWidthThumb(_.size(currentVariant.info)) * 50)/2}">
    <div class="container_frame">
        <span class="fa fa-angle-left left shadow" aria-hidden="true" ng-show="currentVariant.numberFrame > 4"></span>
        <span class="fa fa-angle-right right shadow" aria-hidden="true" ng-show="currentVariant.numberFrame > 4"></span>
        <div class="container-inner-frame">
            <div class="container_item">
                <a class="box-thumb" ng-class="{active: currentVariant.orientationActive == orientation.name}" ng-repeat="orientation in currentVariant.info" ng-click="changeOrientation(orientation)">
                    <img width="40" height="40" ng-show="orientation.source['bg_type'] == 'image'" ng-src="{{orientation.source['img_src']}}"  spinner-on-load/>
                    <i ng-show="orientation.source['bg_type'] == 'color'" 
                       ng-style="{'background': orientation.source['bg_color_value']}" ></i>
                    <i ng-show="orientation.source['bg_type'] == 'tran'" 
                       class="background-transparent" ></i>
                </a>
            </div>
        </div>
    </div>
</div>