<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<div id="helpdesk" class="shadow">
    <h3>{{(langs['HELPDESK']) ? langs['HELPDESK'] : "Helpdesk"}}</h3>
    <div class="od_tabs inner-help">
        <ul>
            <li><a href="#general-help">{{(langs['GENERAL']) ? langs['GENERAL'] : "General"}}</a></li>
            <li><a href="#design-help">{{(langs['DESIGN']) ? langs['DESIGN'] : "Design"}}</a></li>
            <li><a href="#tool-help">{{(langs['TOOL_LAYER']) ? langs['TOOL_LAYER'] : "Tool-Layer"}}</a></li>
            <li><a href="#shortcuts">{{(langs['HOTKEYS']) ? langs['HOTKEYS'] : "Hot Keys"}}</a></li>
        </ul>
        <div id="general-help">
            <img src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/images/helpdesk01.jpg'; ?>" />
            <img src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/images/helpdesk02.jpg'; ?>" />
        </div>
        <div id="design-help">
            <img src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/images/helpdesk04.jpg'; ?>" />
            <img src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/images/helpdesk05.jpg'; ?>" />
        </div>	
        <div id="tool-help">           
            <img src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/images/helpdesk06.jpg'; ?>" />
            <img src="<?php echo NBDESIGNER_PLUGIN_URL .'assets/images/helpdesk03.jpg'; ?>" />
        </div>	
        <div id="shortcuts">    
            <p><span class="shortkey left"><span class="key nbd-icon-mouse"></span></span><span class="shortkey right">{{(langs['SELECT_LAYER']) ? langs['SELECT_LAYER'] : "Select layer (item)"}}</span></p>
            <p><span class="shortkey left"><span class="key">&larr;</span></span><span class="shortkey right">{{(langs['MOVE_LEFT']) ? langs['MOVE_LEFT'] : "Move left"}}</span></p>
            <p><span class="shortkey left"><span class="key">&rarr;</span></span><span class="shortkey right">{{(langs['MOVE_RIGHT']) ? langs['MOVE_RIGHT'] : "Move right"}}</span></p>
            <p><span class="shortkey left"><span class="key">&uarr;</span></span><span class="shortkey right">{{(langs['MOVE_UP']) ? langs['MOVE_UP'] : "Move up"}}</span></p>
            <p><span class="shortkey left"><span class="key">&darr;</span></span><span class="shortkey right">{{(langs['MOVE_DOWN']) ? langs['MOVE_DOWN'] : "Move down"}}</span></p>
            <p><span class="shortkey left"><span class="key long">Delete</span></span><span class="shortkey right">{{(langs['DELETE_LAYER']) ? langs['DELETE_LAYER'] : "Delete layer (item)"}}</span></p>
            <p><span class="shortkey left"><span class="key long">Shift</span><span class="key">-</span></span><span class="shortkey right">{{(langs['ITEM_ZOOM_IN']) ? langs['ITEM_ZOOM_IN'] : "Zoom in item"}}</span></p>
            <p><span class="shortkey left"><span class="key long">Shift</span><span class="key">+</span></span><span class="shortkey right">{{(langs['ITEM_ZOOM_OUT']) ? langs['ITEM_ZOOM_OUT'] : "Zoom out item"}}</span></p>
            <p><span class="shortkey left"><span class="key long">Ctrl</span><span class="key">Z</span></span><span class="shortkey right">{{(langs['UNDO_DESIGN']) ? langs['UNDO_DESIGN'] : "Undo"}}</span></p>
            <p><span class="shortkey left"><span class="key long">Ctrl</span><span class="key">Y</span></span><span class="shortkey right">{{(langs['REDO_DESIGN']) ? langs['REDO_DESIGN'] : "Redo"}}</span></p>
            <p><span class="shortkey left"><span class="key long">Shift</span><span class="key nbd-icon-mouse"></span></span><span class="shortkey right">{{(langs['GROUP_ITEM']) ? langs['GROUP_ITEM'] : "Group Items (left mouse)"}}</span></p>
        </div>        
    </div>
    <span class="close-helpdesk fa fa-angle-double-right"></span>
</div>