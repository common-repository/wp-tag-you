<div id="wpty-settings-container-main">
	<div id="wpty-settings-container-inner" style="">
		<h3><em>General Settings :</em></h3>
		<form action="" method="post">
		<table class="wp-list-table widefat fixed striped">
			<tr>
				<th>Enable/Disable Tag "@" Feature</th>
				<td>
					<input type="radio" name="on_off_option" value="on" <?php echo ($params['switch'] == 'on') ? 'checked' : '' ?>> ON &nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="on_off_option" value="off" <?php echo ($params['switch'] == 'off') ? 'checked' : '' ?>> OFF
				</td>
			</tr>

			<!-- <tr>
				<th>Enter the classes and IDs ("," comma separated) of elements there you want to enable this tag "@" feature. </th>
				<td>
					<textarea name="classes_to_use"><?php //echo (!empty(trim($params['classes'])) ? trim($params['classes']) : ''); ?></textarea>
					<br>
					<em>(Use .(dot) before class name and #(hash) before ID of element. ex: #comment, .biography)</em>
				</td>
			</tr> -->

			<tr>
				<th>Who can use this feature ?</th>
				<td>
					<select name="who_can_use">
						<option value="1" <?php echo ((int)$params['type'] == 1) ? 'selected' : '' ?>>Logged-in Users</option>
						<option value="2" <?php echo ((int)$params['type'] == 2) ? 'selected' : '' ?>>Non Logged-in Users</option>
						<option value="3" <?php echo ((int)$params['type'] == 3) ? 'selected' : '' ?>>Logged-in + Non Logged-in Users</option>
					</select>
				</td>
			</tr>

			<tr>
				<th>Select User Roles who can use this feature</th>
				<td>
					<?php
					  global $wp_roles;
					  $ur_list='';
     					$roles = $wp_roles->get_names();
     					foreach ($roles as $key => $name) {
     						$ur_list.='<li><input type="checkbox" name="selected_roles[]" value="'.$key.'" '.(!empty($params['roles']) && in_array($key,$params['roles']) ? 'checked' : '').'>'.$name.'</li>';
     					}
     					echo $ur_list;
					?>
				</td>
			</tr>

			<!-- <tr>
				<th>Notification Email Body</th>
				<td>
					<?php //wp_editor();?>
				</td>
			</tr> -->
			<tr>
				<td><input type="hidden" name="wptys_being_submit" value="1"></td>
				<td><input type="submit" name="wptys_submit" value="Submit" class="button button-primary"></td>
			</tr>
		</table>
		</form>
	</div>
</div>