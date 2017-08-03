<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div id="dg-qrcode"  class="modal fade nbdesigner_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>{{(langs['QR_CODE']) ? langs['QR_CODE'] : "QR Code"}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>			
                <div class="modal-body">
                    <div>
                        <textarea class="form-control nbdesigner_textarea" id="qrcode-content" ng-model="qrCodeContent"></textarea>
                    </div>
                    <div class="text-center">
                        <button class="nbdesigner_button shadow hover-shadow" ng-click="getQrCode()">{{(langs['CREATE_QRCODE']) ? langs['CREATE_QRCODE'] : "Create QRCode"}}</button>
                        <img id="loading_qrcode" style="margin-left: 15px;" class="hidden" src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/css/images/ajax-loader.gif'; ?>" />	
                    </div>
                    <div id="qrcode-img"></div>                    
                </div>
            </div>            
        </div>
    </div>
</div>