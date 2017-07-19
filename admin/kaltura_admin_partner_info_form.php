<?php
	if (!defined("WP_ADMIN"))
		die();
		
	if (@$_POST['is_postback'] == "postback")
	{
		$permissions_add = $_POST["permissions_add"];
		$permissions_edit = $_POST["permissions_edit"];
		$enable_video_comments = @$_POST["enable_video_comments"] ? true : false;
		$allow_anonymous_comments = @$_POST["allow_anonymous_comments"] ? true : false;
		$default_player_type = $_POST["default_player_type"];
		$comments_player_type = $_POST["comments_player_type"];
		$default_playlist_type = $_POST["default_playlist_type"];
		$admin_player_type = $_POST["admin_player_type"];
		$default_kcw_type = $_POST["default_kcw_type"];
		$allow_posts = @$_POST["allow_posts"] ? true : false;
		$allow_standard = @$_POST["allow_standard"] ? true : false;
		$allow_advanced = @$_POST["allow_advanced"] ? true : false;
		$enable_html5 = @$_POST["enable_html5"] ? true : false;
		
		update_option("kaltura_permissions_add", $permissions_add);
		update_option("kaltura_permissions_edit", $permissions_edit);
		update_option("kaltura_enable_video_comments", $enable_video_comments);
		update_option("kaltura_allow_anonymous_comments", $allow_anonymous_comments);
		update_option("kaltura_default_player_type", $default_player_type);
		update_option("kaltura_comments_player_type", $comments_player_type);
		update_option("x7uiconfid", $default_player_type);
		update_option("x7pluiconfid", $default_playlist_type);
		update_option("x7adminuiconfid", $admin_player_type);
		update_option("x7kcwuiconfid", $default_kcw_type);
		update_option("x7allowposts", $allow_posts);
		update_option("x7allowstandard", $allow_standard);
		update_option("x7allowadvanced", $allow_advanced);
		update_option("x7html5enabled", $enable_html5);
		
		$viewData["showMessage"] = true;
	}
	else
	{
		// try to create new session to make sure that the details are ok
		$userId 	    = KalturaHelpers::getLoggedUserID();
		$config 		= KalturaHelpers::getKalturaConfiguration();
		$secret 		= get_option("kaltura_secret");
		$adminSecret	= get_option("kaltura_admin_secret");
		$kalturaClient 	= new KalturaClient($config);
		
		$kmodel = KalturaModel::getInstance();
		
		$ks = $kmodel->getAdminSession();
		if ($kmodel->getLastError())
		{
			$error = $kmodel->getLastError();
		    $viewData["error"] = $error["message"] . ' - ' . $error["code"];
		}   
		
		$ks = $kmodel->getClientSideSession();
		if ($kmodel->getLastError())
		{
			$error = $kmodel->getLastError();
		    $viewData["error"] = $error["message"] . ' - ' . $error["code"];
		}
	}
?>


<?php if ($viewData["error"]): ?>

<div class="wrap">
	<h2><?php _e('x7Host Videox7 UGC Plugin Settings'); ?></h2>
	<br />
	<div id="message" class="updated"><p><strong><?php _e('Failed to verify partner details'); ?></strong> (<?php echo $viewData["error"]; ?>)</p></div>
	<form name="form1" method="post" />
	<p class="submit" style="text-align: left; "><input type="button" value="<?php _e('Click here to edit partner details manually'); ?>" onclick="window.location = 'options-general.php?page=interactive_video&partner_login=true';"></input></p>
	<input type="hidden" id="manual_edit" name="manual_edit" value="true" />
	</form>
</div>

<?php else: ?>
<div class="wrap">
<?php if ($viewData["showMessage"]): ?>
	<div id="message" class="updated"><p><strong><?php _e('The x7Host Videox7 UGC Plugin settings have been saved.'); ?></strong></p></div>
	<?php endif; ?>
	<h2><?php _e('x7Host Videox7 UGC Plugin Settings'); ?></h2>
	<form name="form1" method="post" />
		<br />
		<table id="kalturaCmsLogin">
			<tr class="kalturaFirstRow">
				<th align="left"><?php _e('Partner ID'); ?>:</th>
				<td style="padding-right: 90px;"><strong><?php echo get_option("kaltura_partner_id"); ?></strong></td>
			</tr>
			<tr>
				<th align="left"><?php _e('KMC username'); ?>:</th>
				<td style="padding-right: 90px;"><strong><?php echo get_option("kaltura_cms_user"); ?></strong></td>
			</tr>
			<tr class="kalturaLastRow">
				<td colspan="2" align="left" style="padding-top: 10px;padding-left:10px">
					<a href="http://www.kaltura.com/kmc" target="_blank">Login</a> to the Kaltura.com SaaS Management Console (KMC) if using.<br />
					Or, <a href="<?php echo($x7server); ?>/kmc" target="_blank">Login</a> to your KalturaCE Management Console (KMC) if using.<br />
				</td>
			</tr>
		</table>
		<table>
			<tr valign="top">
				<td width="200"><?php _e("Who can edit videos?"); ?></td>
				<td>
					<input type="radio" id="perm_admins_edit" name="permissions_edit" value="3" <?php echo @get_option("kaltura_permissions_edit") == "3" ? "checked=\"checked\"" : ""; ?> /> <label for="perm_admins_edit"><?php _e("Blog Administrators"); ?></label><br />
					<input type="radio" id="perm_editors_edit" name="permissions_edit" value="2" <?php echo @get_option("kaltura_permissions_edit") == "2" ? "checked=\"checked\"" : ""; ?> /> <label for="perm_editors_edit"><?php _e("Blog Editors/Contributors & Authors"); ?></label><br />
					<input type="radio" id="perm_subscribers_edit" name="permissions_edit" value="1" <?php echo @get_option("kaltura_permissions_edit") == "1" ? "checked=\"checked\"" : ""; ?> /> <label for="perm_subscribers_edit"><?php _e("Blog Subscribers"); ?></label><br />
					<input type="radio" id="perm_everybody_edit" name="permissions_edit" value="0" <?php echo @get_option("kaltura_permissions_edit") == "0" ? "checked=\"checked\"" : ""; ?> /> <label for="perm_everybody_edit"><?php _e("Everybody"); ?></label><br />
					<br />
				</td>
			</tr>
			<tr valign="top">
				<td><?php _e("Who can add to videos?"); ?></td>
				<td>
					<input type="radio" id="perm_admins_add" name="permissions_add" value="3" <?php echo @get_option("kaltura_permissions_add") == "3" ? "checked=\"checked\"" : ""; ?> /> <label for="perm_admins_add"><?php _e("Blog Administrators"); ?></label><br />
					<input type="radio" id="perm_editors_add" name="permissions_add" value="2" <?php echo @get_option("kaltura_permissions_add") == "2" ? "checked=\"checked\"" : ""; ?> /> <label for="perm_editors_add"><?php _e("Blog Editors/Contributors & Authors"); ?></label><br />
					<input type="radio" id="perm_subscribers_add" name="permissions_add" value="1" <?php echo @get_option("kaltura_permissions_add") == "1" ? "checked=\"checked\"" : ""; ?> /> <label for="perm_subscribers_add"><?php _e("Blog Subscribers"); ?></label><br />
					<input type="radio" id="perm_everybody_add" name="permissions_add" value="0" <?php echo @get_option("kaltura_permissions_add") == "0" ? "checked=\"checked\"" : ""; ?> /> <label for="perm_everybody_add"><?php _e("Everybody"); ?></label><br />
					<br />
				</td>
			</tr>
			<tr valign="top">
				<td><?php _e("Enable video comments?"); ?></td>
				<td>
					<input type="checkbox" id="enable_video_comments" name="enable_video_comments" <?php echo @get_option("kaltura_enable_video_comments") ? "checked=\"checked\"" : ""; ?> />
					<br />
				</td>
			</tr>
			<tr valign="top">
				<td><?php _e("Allow anonymous video comments?"); ?></td>
				<td>
					<input type="checkbox" id="allow_anonymous_comments" name="allow_anonymous_comments" <?php echo @get_option("kaltura_allow_anonymous_comments") ? "checked=\"checked\"" : ""; ?> />
					<br />
				</td>
			</tr>
			<tr valign="top">
				<td><?php _e("Enable user posts?"); ?></td>
				<td>
					<input type="checkbox" id="allow_posts" name="allow_posts" <?php echo @get_option("x7allowposts") ? "checked=\"checked\"" : ""; ?> />
					<br />
				</td>
			</tr>
			<tr valign="top">
				<td><?php _e("Enable standard editor use?"); ?></td>
				<td>
					<input type="checkbox" id="allow_standard" name="allow_standard" <?php echo @get_option("x7allowstandard") ? "checked=\"checked\"" : ""; ?> />
					<br />
				</td>
			</tr>
			<tr valign="top">
				<td><?php _e("Enable advanced editor use?"); ?></td>
				<td>
					<input type="checkbox" id="allow_advanced" name="allow_advanced" <?php echo @get_option("x7allowadvanced") ? "checked=\"checked\"" : ""; ?> />
					<br />
				</td>
			</tr>
			<tr valign="top">
				<td><?php _e("Enable HTML5 Javascript Library?"); ?></td>
				<td>
					<input type="checkbox" id="enable_html5" name="enable_html5" <?php echo @get_option("x7html5enabled") ? "checked=\"checked\"" : ""; ?> />
					<br />
				</td>
			</tr>
			<?php
				$kmodel = KalturaModel::getInstance();
				$players = $kmodel->listPlayersUiConfs(); 
			?>
			<tr valign="top">
				<td><?php _e("Video comments player design:"); ?></td>
				<td>
					<?php foreach($players->objects as $player): ?>
						<input type="radio" name="comments_player_type" id="comments_player_type_<?php echo $player->id; ?>" value="<?php echo $player->id; ?>" <?php echo @get_option("kaltura_comments_player_type") == $player->id ? "checked=\"checked\"" : ""; ?>/>&nbsp;&nbsp;<label for="comments_player_type_<?php echo $player->id; ?>"><?php echo $player->name; ?></label><br />
					<?php endforeach; ?>
				</td>
			</tr>
			<tr valign="top">
				<td><?php _e("Default player design:"); ?></td>
				<td>
					<?php foreach($players->objects as $player): ?>
						<input type="radio" name="default_player_type" id="default_player_type_<?php echo $player->id; ?>" value="<?php echo $player->id; ?>" <?php echo @get_option("kaltura_default_player_type") == $player->id ? "checked=\"checked\"" : ""; ?>/>&nbsp;&nbsp;<label for="default_player_type_<?php echo $player->id; ?>"><?php echo $player->name; ?></label><br />
					<?php endforeach; ?>
				</td>
			</tr>
			<tr valign="top">
				<td><?php _e("Default playlist design:"); ?></td>
				<td>
					<?php foreach($players->objects as $player): ?>
						<input type="radio" name="default_playlist_type" id="default_playlist_type_<?php echo $player->id; ?>" value="<?php echo $player->id; ?>" <?php echo @get_option("x7pluiconfid") == $player->id ? "checked=\"checked\"" : ""; ?>/>&nbsp;&nbsp;<label for="default_playlist_type_<?php echo $player->id; ?>"><?php echo $player->name; ?></label><br />
					<?php endforeach; ?>
				</td>
			</tr>
			<tr valign="top">
				<td><?php _e("Default admin player design for logged in users:"); ?></td>
				<td>
					<?php foreach($players->objects as $player): ?>
						<input type="radio" name="admin_player_type" id="admin_player_type_<?php echo $player->id; ?>" value="<?php echo $player->id; ?>" <?php echo @get_option("x7adminuiconfid") == $player->id ? "checked=\"checked\"" : ""; ?>/>&nbsp;&nbsp;<label for="admin_player_type_<?php echo $player->id; ?>"><?php echo $player->name; ?></label><br />
					<?php endforeach; ?>
				</td>
			</tr>
			<tr valign="top">
				<td><?php _e("Default Contribution Wizard UIConfID:"); ?></td>
				<td>
					<input type="text" name="default_kcw_type" id="default_kcw_type" value="<?php echo(get_option("x7kcwuiconfid")); ?>"/>&nbsp;&nbsp;<br />
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<br />
					<p class="submit" style="text-align: left; "><input type="submit" name="update" value="<?php _e('Update') ?>" /></p>
				</td>
			</tr>
		</table>
		<br />
		<br />
	
		Please feel free to contact <a href="mailto:support@kaltura.com">support@kaltura.com</a> with any questions.
		<input type="hidden" name="is_postback" value="postback" />
	</form>
	
	<script type="text/javascript">
	
		function updateFormState() {
			// permissions
			jQuery("input[type=radio][name=permissions_add]").attr('disabled', false);
			
			var checkedEdit = jQuery("input[name=permissions_edit][checked]");
			if (checkedEdit.size() == 0)
				return;
			
			var permValue = Number(checkedEdit.get(0).value);
			for(var i = 3; i > permValue; i--)
			{
				jQuery("input[type=radio][name=permissions_add][value="+i+"]").attr('disabled', true);
			}
			var checkedAdd = jQuery("input[type=radio][name=permissions_add][checked]");
			if (checkedAdd.size() > 0)
			{
				if (checkedAdd.attr('disabled'))
				{
					jQuery("input[type=radio][name=permissions_add][value="+i+"]").attr('checked', 'checked');
				}
			}
			else
			{
				jQuery("input[type=radio][name=permissions_add][value="+i+"]").attr('checked', 'checked');
			}
			
			// video comments settings 
			var enableVideoComments = jQuery("input[type=checkbox][id=enable_video_comments]").attr('checked');
			if (enableVideoComments)
			{
				jQuery("input[type=checkbox][id=allow_anonymous_comments]").attr('disabled', false);
			}
			else
			{
				jQuery("input[type=checkbox][id=allow_anonymous_comments]").attr('disabled', true);
				jQuery("input[type=checkbox][id=allow_anonymous_comments]").attr('checked', false);
			}
		}
		
		jQuery("input[type=radio][name=permissions_edit]").click(updateFormState);
		jQuery("input[type=checkbox]").click(updateFormState);
		
		// simulate the click to restore the ui state as it should be
		jQuery("input[type=radio][name=permissions_edit][checked]").click();
	</script>
</div>
<?php endif; ?>