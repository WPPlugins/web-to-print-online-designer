<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  ?>
<h1><?php _e('Manager Fonts', 'nbdesigner'); ?></h1>
<h2><?php echo __('Custom Fonts', 'nbdesigner'); ?></h2>
<?php echo $notice; ?>
<div class="wrap nbdesigner-container">
	<div class="nbdesigner-content-full">
		<form name="post" action="" method="post" enctype="multipart/form-data" autocomplete="off">
			<div class="nbdesigner-content-left postbox">
				<h3><?php echo __('Add font', 'nbdesigner'); ?></h3>
				<div class="inside nbdesigner_font_info">						
					<?php wp_nonce_field($this->plugin_id, $this->plugin_id.'_hidden'); ?>		
					<table class="form-table">
						<tr valign="top">
							<th scope="row" class="titledesc"><?php echo __("Font name", 'nbdesigner'); ?> </th>
							<td class="forminp-text">
								<input type="text" name="nbdesigner_font_name" value="<?php $name = isset($font_data->name) ?  $font_data->name : ''; echo $name; ?>" />						
							</td>
						</tr>				
						<tr valign="top">
							<th scope="row" class="titledesc"><?php echo __("Font file", 'nbdesigner'); ?> </th>
							<td class="forminp-text">
								<input type="file" name="woff" value="" /><br />
								<div style="font-size: 11px; font-style: italic;"><?php _e('Allow extensions: woff, ttf', 'nbdesigner'); ?></div>
							</td>
						</tr>				
					</table>
					<input type="hidden" name="nbdesigner_font_id" value="<?php echo $font_id; ?>"/>
					<p class="submit">
						<input type="submit" name="Submit" class="button-primary" value="<?php _e('Save', 'nbdesigner'); ?>" />
						<a href="?page=nbdesigner_manager_fonts" class="button-primary" style="<?php $style = (isset($_GET['id'])) ? '' : 'display:none;';echo $style; ?>"><?php _e('Add New', 'nbdesigner'); ?></a>
					</p>									
				</div>	
				<?php if(isset($font_data)): ?>
				<div class="nbdesigner_font_preview">
					<h4><?php _e('Preview', 'nbdesigner'); ?></h4>
					<div>
						<style type='text/css'>
							@font-face {font-family: <?php echo $font_data->alias; ?>;src: local('☺'), url('<?php echo $font_data->url; ?>')}
						</style>
						<span style="font-family: <?php echo "'".$font_data->alias."', sans-serif"; ?>;font-size: 30px;">Abc Xyz</span>
					</div>
				</div>
				<?php endif; ?>
			</div>
			<div class="nbdesigner-content-side">
				<div class="postbox">
					<h3><?php _e('Categories', 'nbdesigner'); ?><img src="<?php echo NBDESIGNER_PLUGIN_URL . 'assets/images/loading.gif'; ?>" class="nbdesigner_editcat_loading nbdesigner_loaded" style="margin-left: 15px;"/></h3>
					<div class="inside">
						<ul id="nbdesigner_list_cats">
						<?php if(is_array($cat) && (sizeof($cat) > 0)): ?>							
							<?php foreach($cat as $val): ?>
								<li id="nbdesigner_cat_font_<?php echo $val->id; ?>" class="nbdesigner_action_delete_cf">
                                                                    <label>
                                                                        <input value="<?php echo $val->id; ?>" type="checkbox" name="nbdesigner_font_cat[]" <?php if($update && (sizeof($cats) > 0 )) if(in_array($val->id, $cats)) echo "checked";  ?> />
                                                                    </label>
                                                                    <span class="nbdesigner-right nbdesigner-delete-item dashicons dashicons-no-alt" onclick="NBDESIGNADMIN.delete_cat_font(this)"></span>
                                                                    <span class="dashicons dashicons-edit nbdesigner-right nbdesigner-delete-item" onclick="NBDESIGNADMIN.edit_cat_font(this)"></span>
                                                                    <a href="<?php echo add_query_arg(array('cat_id' => $val->id), admin_url('admin.php?page=nbdesigner_manager_fonts')) ?>" class="nbdesigner-cat-link"><?php echo $val->name; ?></a>
                                                                    <input value="<?php echo $val->name; ?>" class="nbdesigner-editcat-name" type="text"/>
                                                                    <span class="dashicons dashicons-yes nbdesigner-delete-item nbdesigner-editcat-name" onclick="NBDESIGNADMIN.save_cat_font(this)"></span>
                                                                    <span class="dashicons dashicons-no nbdesigner-delete-item nbdesigner-editcat-name" onclick="NBDESIGNADMIN.remove_action_cat_font(this)"></span>                                                                           
								</li>
							<?php endforeach; ?>							
						<?php else: ?> 
							<li><?php _e('You don\'t have any category.', 'nbdesigner'); ?></li>
						<?php endif; ?>
						</ul>
						<input type="hidden" id="nbdesigner_current_font_cat_id" value="<?php echo $current_font_cat_id; ?>"/>
						<p><a id="nbdesigner_add_font_cat"><?php _e('+ Add new font category', 'nbdesigner'); ?></a></p>
						<div id="nbdesigner_font_newcat" class="category-add"></div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="clear"></div>
	<div class="postbox">
		<h3><?php echo __('List fonts: ', 'nbdesigner').$name_current_cat; ?><a class="nbdesigner-right" href="<?php echo admin_url('admin.php?page=nbdesigner_manager_fonts'); ?>"><?php _e('All fonts', 'nbdesigner'); ?></a></h3>
		<div class="nbdesigner-list-fonts inside">				
			<div class="nbdesigner-list-fonts-container">
				<?php if(is_array($list) && (sizeof($list) > 0)): ?>
					<?php foreach($list as $val): ?>
						<span class="nbdesigner_google_link "><a href="?page=nbdesigner_manager_fonts&id=<?php echo $val->id ?><?php if(isset($current_cat)) echo '&cat_id='.$current_cat; ?>"><span><?php echo $val->name; ?></span></a><span class="nbdesigner_action_delete_cfont" data-index="<?php echo $val->id; ?>" onclick="NBDESIGNADMIN.delete_font('custom',this)">&times;</span></span>
					<?php endforeach; ?>
				<?php else: ?>
					<?php _e('You don\'t have any custom font.', 'nbdesigner');?>
				<?php  endif; ?>
			</div>
		</div>
	</div>
</div>
<h2><?php echo __('Google Fonts', 'nbdesigner'); ?></h2>
<div class="wrap nbdesigner-container">
	<div class="postbox">
		<div class="inside nbdesigner_google_added">
			<div class="ui-widget"><input type="search" id="nbdesigner_google_font_seach"/><input onclick="NBDESIGNADMIN.add_google_font(this)" type="button" value="<?php _e('Add', 'nbdesigner'); ?>" class="button"/><img src="<?php echo NBDESIGNER_PLUGIN_URL.'assets/images/loading.gif' ?>" class="nbdesigner_loaded" id="nbdesigner_google_font_loading" style="margin-left: 15px;"/></div>		
			<div style="margin-top: 15px;" id="nbdesigner_container_list_google_font">
			<?php if(is_array($data_font_google)): ?>
				<?php foreach($data_font_google as $val):?>
					<span class="nbdesigner_google_link "><a href="https://fonts.google.com/specimen/<?php echo $val->name ?>" target="_blank" class=" "><span><?php echo $val->name; ?></span></a><span class="nbdesigner_action_delete_gf" data-index="<?php echo $val->id; ?>" onclick="NBDESIGNADMIN.delete_font('google',this)">&times;</span></span>
				<?php endforeach; ?>
			<?php else: ?>
				<p id="nbdesigner_no_google_font"><?php _e('You don\'t have any google font', 'nbdesigner'); ?></p>
			<?php endif; ?>
			<input type="hidden" id="nbdesigner_current_index_google_font" value="<?php if(is_array($data_font_google))echo sizeof($data_font_google); else echo "0"; ?>"/>
			</div>
		</div>
		<div class="nbdesigner_google_preview" style="display: none;">
			<h4><?php _e('Preview', 'nbdesigner'); ?></h4>
			<div id="nbdesigner_google_preview">
				
			</div>		
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($){
		var google_font  = <?php echo $list_all_google_font; ?>;
		$( "#nbdesigner_google_font_seach" ).autocomplete({
			source: google_font,
			select: function(event, ui){
				$('.nbdesigner_google_preview').show();
				$('#nbdesigner_google_preview').html('');
				$('#nbdesigner_head').remove();
				var _name = ui.item.value;
				name1 = _name.replace(' ', '+');
				var head    = '<link id="nbdesigner_head" href="https://fonts.googleapis.com/css?family='+ name1 +'" rel="stylesheet" type="text/css">';
				var html    = '<span style="font-family: \''+ _name +'\', sans-serif ;font-size: 30px;">Abc Xyz</span>';
				$('head').append(head);
				$('#nbdesigner_google_preview').append(html);
			}
		});			
	});
</script>