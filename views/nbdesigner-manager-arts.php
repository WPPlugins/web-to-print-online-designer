<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<h1><?php _e('Manager Cliparts', 'nbdesigner'); ?></h1>
<?php echo $notice; ?>
<div class="wrap nbdesigner-container">
	<div class="nbdesigner-content-full">
		<form name="post" action="" method="post" enctype="multipart/form-data" autocomplete="off">
			<div class="nbdesigner-content-left postbox">
				<div class="inside">	
					<?php wp_nonce_field($this->plugin_id, $this->plugin_id.'_hidden'); ?>		
					<table class="form-table">
						<tr valign="top">
							<th scope="row" class="titledesc"><?php echo __("Clipart name", 'nbdesigner'); ?> </th>
							<td class="forminp-text">
								<input type="text" name="nbdesigner_art_name" value="<?php $name = isset($art_data->name) ?  $art_data->name : ''; echo $name; ?>" />						
							</td>
						</tr>				
						<tr valign="top">
							<th scope="row" class="titledesc"><?php echo __("File upload", 'nbdesigner'); ?> </th>
							<td class="forminp-text">
								<input type="file" name="svg" value="" accept=".svg"/><br />
								<div style="font-size: 11px; font-style: italic;"><?php _e('Allow extensions: svg', 'nbdesigner'); ?></div>
							</td>
						</tr>				
					</table>
					<input type="hidden" name="nbdesigner_art_id" value="<?php echo $art_id; ?>"/>
					<p class="submit">
						<input type="submit" name="Submit" class="button-primary" value="<?php _e('Save', 'nbdesigner'); ?>" />
						<a href="?page=nbdesigner_manager_fonts" class="button-primary" style="<?php $style = (isset($_GET['id'])) ? '' : 'display:none;';echo $style; ?>"><?php _e('Add New', 'nbdesigner'); ?></a>
					</p>				
				</div>
			</div>
			<div class="nbdesigner-content-side">
				<div class="postbox">
                                    <h3><?php _e('Categories', 'nbdesigner'); ?><img src="<?php echo NBDESIGNER_PLUGIN_URL . 'assets/images/loading.gif'; ?>" class="nbdesigner_editcat_loading nbdesigner_loaded" style="margin-left: 15px;"/></h3>
					<div class="inside">
						<ul id="nbdesigner_list_art_cats">
						<?php if(is_array($cat) && (sizeof($cat) > 0)): ?>
							<?php foreach($cat as $val): ?>
								<li id="nbdesigner_cat_art_<?php echo $val->id; ?>" class="nbdesigner_action_delete_art_cat">
                                                                    <label>
                                                                        <input value="<?php echo $val->id; ?>" type="checkbox" name="nbdesigner_art_cat[]" <?php if($update && (sizeof($cats) > 0 )) if(in_array($val->id, $cats)) echo "checked";  ?> />
                                                                    </label>
                                                                    <span class="nbdesigner-right nbdesigner-delete-item dashicons dashicons-no-alt" onclick="NBDESIGNADMIN.delete_cat_art(this)"></span>
                                                                    <span class="dashicons dashicons-edit nbdesigner-right nbdesigner-delete-item" onclick="NBDESIGNADMIN.edit_cat_art(this)"></span>
                                                                    <a href="<?php echo add_query_arg(array('cat_id' => $val->id), admin_url('admin.php?page=nbdesigner_manager_arts')); ?>" class="nbdesigner-cat-link"><?php echo $val->name; ?></a>
                                                                    <input value="<?php echo $val->name; ?>" class="nbdesigner-editcat-name" type="text"/>
                                                                    <span class="dashicons dashicons-yes nbdesigner-delete-item nbdesigner-editcat-name" onclick="NBDESIGNADMIN.save_cat_art(this)"></span>
                                                                    <span class="dashicons dashicons-no nbdesigner-delete-item nbdesigner-editcat-name" onclick="NBDESIGNADMIN.remove_action_cat_art(this)"></span>
								</li>
							<?php endforeach; ?>							
						<?php else: ?> 
							<li><?php _e('You don\'t have any category.', 'nbdesigner'); ?></li>
						<?php endif; ?>						
						</ul>
						<input type="hidden" id="nbdesigner_current_art_cat_id" value="<?php echo $current_art_cat_id; ?>"/>
						<p><a id="nbdesigner_add_art_cat"><?php _e('+ Add new art category', 'nbdesigner'); ?></a></p>
						<div id="nbdesigner_art_newcat" class="category-add"></div>	
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="clear"></div>
	<div class="postbox">
            <h3><?php echo __('List arts ', 'nbdesigner').$name_current_cat; ?><a class="nbdesigner-right" href="<?php echo admin_url('admin.php?page=nbdesigner_manager_arts'); ?>"><?php _e('All arts', 'nbdesigner'); ?></a></h3>
		<div class="nbdesigner-list-fonts inside">				
                    <div class="nbdesigner-list-arts-container">
                            <?php if(is_array($_list) && (sizeof($_list) > 0)): ?>
                                    <?php foreach($_list as $val): ?>
                                            <span class="nbdesigner_art_link "><img src="<?php echo $val->url; ?>" /><span class="nbdesigner_action_delete_art" data-index="<?php echo $val->id; ?>" onclick="NBDESIGNADMIN.delete_art(this)">&times;</span></span>
                                    <?php endforeach; ?>
                            <?php else: ?>
                                    <?php _e('You don\'t have any art.', 'nbdesigner');?>
                            <?php  endif; ?>
                    </div>
                    <div class="tablenav top">
                        <div class="tablenav-pages">
                            <span class="displaying-num"><?php echo $total.' '. __('arts', 'nbdesigner'); ?></span>
                            <?php echo $paging->html();  ?>
                        </div>
                    </div>                       
		</div>
	</div>	
</div>