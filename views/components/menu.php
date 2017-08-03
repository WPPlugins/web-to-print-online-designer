<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div id="main_menu">	
    <ul class="tool_draw">
        <li ng-show="settings['enable_text'] == 'yes'">
            <a class="add_text shadow" ng-click="addText()">
                <i class="fa fa-font" aria-hidden="true"></i>
                <span class="after">{{(langs['ADD_TEXT']) ? langs['ADD_TEXT'] : "Add Text"}}</span>
            </a>
        </li>
        <li ng-show="settings['enable_clipart'] == 'yes'">
            <a class="add_art shadow" data-toggle="modal" data-target="#dg-cliparts" ng-click="loadArt()">
                <i class="fa fa-picture-o" aria-hidden="true"></i>
                <span class="after">{{(langs['ADD_CLIPART']) ? langs['ADD_CLIPART'] : "Add Clipart"}}</span>
            </a>
        </li>
        <li ng-show="settings['enable_image'] == 'yes'">
            <a class="add_image shadow" data-toggle="modal" data-target="#dg-myclipart" ng-click="loadLocalStorageImage()">
                <i class="fa fa-camera-retro" aria-hidden="true"></i>
                <span class="after">{{(langs['ADD_IMAGE']) ? langs['ADD_IMAGE'] : "Add Image"}}</span>
            </a>
        </li>
        <li ng-show="settings['enable_draw'] == 'yes'">
            <a class="draw_free shadow" ng-click="showDrawConfig()">
                <i class="fa fa-paint-brush" aria-hidden="true"></i>
                <span class="after">{{(langs['FREE_DRAW']) ? langs['FREE_DRAW'] : "Free Draw"}}</span>
            </a>
        </li>
        <li ng-show="settings['enable_qrcode'] == 'yes'">
            <a class="add_code shadow" data-toggle="modal" data-target="#dg-qrcode">
                <i class="fa fa-qrcode" aria-hidden="true"></i>
                <span class="after">{{(langs['ADD_QRCODE']) ? langs['ADD_QRCODE'] : "Add QRCode"}}</span>
            </a>
        </li>
    </ul>
    <div class="container_menu shadow hover-shadow">	
        <div id="menu">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div id="layer" class="shadow hover-shadow">
        <div class="nav_layer">
            <span></span>
            <span></span>
            <span></span>		
        </div>
        <span class="layer_after">{{(langs['LAYERS']) ? langs['LAYERS'] : "Layers"}}</span>
    </div>
    <div id="gesture" class="hover-shadow">
        <div class="menu_gesture shadow">
            <span class="fa fa-hand-o-up m-center" aria-hidden="true"></span>
            <span class="fa fa-chevron-left shadow left" ng-click="ShiftLeft()"></span>
            <span class="fa fa-chevron-right shadow right" ng-click="ShiftRight()"></span>
            <span class="fa fa-chevron-up shadow up" ng-click="ShiftUp()"></span>
            <span class="fa fa-chevron-down shadow down" ng-click="ShiftDown()"></span>
            <span class="fa fa-exchange flip-ver"  ng-click="flipVertical()"></span>
            <span class="fa fa-exchange rotate90 flip-hoz"  ng-click="flipHorizontal()"></span>
            <span class="glyphicon glyphicon-object-align-vertical set-ver" ng-click="setHorizontalCenter()"></span>
            <span class="glyphicon glyphicon-object-align-horizontal set-hoz" ng-click="setVerticalCenter()"></span>
            <span class="fa fa-trash-o delete"  onclick="deleteObject()"></span>
            <span class="fa fa-files-o refresh"  ng-click="duplicateItem()"></span>
            <span class="fa fa fa-plus zoom-out" ng-click="scaleItem('+')"></span>
            <span class="fa fa fa-minus zoom-in" ng-click="scaleItem('-')"></span>
        </div>
        <span class="gesture_after">{{(langs['TOOL']) ? langs['TOOL'] : "Tool"}}</span>
    </div>	
    <div id="info" class="shadow hover-shadow">
        <div class="container_info">
            <span class="fa fa-floppy-o menu_cart" ng-click="storeDesign()"></span>			
        </div>
        <span class="info_after">{{(langs['SAVE']) ? langs['SAVE'] : "Save"}}</span>
    </div>
</div>