<?php
/**
 * Options page
 */
function nlws_wu_options() {
	global $wp_roles;
	$roles = $wp_roles->get_names();
	$display_name_options = array(
		'0' => array(
			'value' =>	'user_login',
			'label' => __( 'Username' )
		),
		'1' => array(
			'value' =>	'user_email',
			'label' => __( 'Email Address' )
		),
		'2' => array(
			'value' => 'user_firstname',
			'label' => __( 'First Name' )
		),
		'3' => array(
			'value' => 'user_lastname',
			'label' => __( 'Last Name' )
		),
		'4' => array(
			'value' => 'user_fullname',
			'label' => __( 'Full Name' )
		),
		'5' => array(
			'value' => 'display_name',
			'label' => __( 'Display Name' )
		)
	);
	
	$link_display_options = array(
		'0' => array(
			'value' =>	'register_admin',
			'label' => __( 'Register / Admin' )
		),
		'1' => array(
			'value' =>	'login_logout',
			'label' => __( 'Login / Logout' )
		)
	);
	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false;

	?>
	<div class="wrap">
		<div id="icon-users" class="icon32"></div><h2>Welcome User! Settings</h2>
		<p>This handy little plugin is designed to output a customizable linear user welcome message.&nbsp; This is typically seen on big sites like <a href="http://ebay.com" target="_blank">ebay</a> and <a href="http://amazon.com" target="_blank">Amazon</a>.</p>
		<p>There are three tags that can be inserted into the display templates: <br /><br />
			<strong>[USERNAME]</strong> - <em>Inserts the user's display name.</em><br />
			<strong>[REGISTERADMIN]</strong> - <em>Inserts the wp_register('', '') function.&nbsp; This will generate a register link or site admin link.</em><br />
			<strong>[LOGINOUT]</strong> - <em>Inserts the wp_loginout() function.&nbsp; This will generate a login or logout link depending on the user's status.</em>
		</p>
		<p>To use this plugin simple insert <code>&lt;?php nlws_wu(); ?&gt;</code> somewhere in your theme.&nbsp; Welcome User! will do the rest.</p>
		<?php if ( false !== $_REQUEST['updated'] ) : ?>
			
			<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
		<?php endif; ?>
		
		<form method="post" action="options.php">
			<?php
			settings_fields( 'nlws_wu_settings' );
			$options = get_option( 'nlws_wu_settings' );
			?>
			<h3>Non Logged In Settings</h3>
			<table class="form-table">
				<tr valign="top"><th scope="row"><?php _e( 'Non-Logged in display template' ); ?></th>
					<td>
						<textarea id="nlws_wu_settings[non_logged_in]" class="large-text" cols="50" rows="3" name="nlws_wu_settings[non_logged_in]"><?php echo stripslashes( $options['non_logged_in'] ); ?></textarea>
						<label class="description" for="nlws_wu_settings[non_logged_in]"><?php _e( 'Enter a string that will be your non-logged in display' ); ?></label>
					</td>
				</tr>
				
				<tr valign="top"><th scope="row"><?php _e( 'Register link text' ); ?></th>
					<td>
						<input id="nlws_wu_settings[register_link]" class="regular-text" type="text" name="nlws_wu_settings[register_link]" value="<?php esc_attr_e( $options['register_link'] ); ?>" />
						<label class="description" for="nlws_wu_settings[register_link]"><?php _e( 'The link text that is displayed to a non-logged in user, encouraging them to register for this site.' ); ?></label>
					</td>
				</tr>
				
				<tr valign="top"><th scope="row"><?php _e( 'Login link text' ); ?></th>
					<td>
						<input id="nlws_wu_settings[login_link]" class="regular-text" type="text" name="nlws_wu_settings[login_link]" value="<?php esc_attr_e( $options['login_link'] ); ?>" />
						<label class="description" for="nlws_wu_settings[login_link]"><?php _e( 'The link text that is displayed to a non-logged in user, encouraging them to login to the site.' ); ?></label>
					</td>
				</tr>
			</table>
			<h3>Logged In Settings</h3>
			<div id="tabs">
				<ul>
					<?php foreach ($roles as $role) : ?>
						<li><a href="#tab-<?php echo $role; ?>"><?php echo $role; ?></a></li>
					<?php endforeach; ?>
				</ul>
				<?php foreach ($roles as $role) : ?>
					<div id="tab-<?php echo $role; ?>">
					
						<table class="form-table">	
						
							<tr valign="top"><th scope="row"><?php _e( 'Logged in display template' ); ?></th>
								<td>
									<textarea id="nlws_wu_settings[<?php echo $role; ?>_logged_in]" class="large-text" cols="50" rows="3" name="nlws_wu_settings[<?php echo $role; ?>_logged_in]"><?php echo stripslashes( $options[$role . '_' . 'logged_in'] ); ?></textarea>
									<label class="description" for="nlws_wu_settings[<?php echo $role; ?>_logged_in]"><?php _e( 'Enter a string that will be your logged in display' ); ?></label>
								</td>
							</tr>
							
							<tr valign="top"><th scope="row"><?php _e( 'User display type' ); ?></th>
								<td>
									<select name="nlws_wu_settings[<?php echo $role; ?>_display_name]">
										<?php
											$selected = $options[$role . '_' . 'display_name'];
											$p = '';
											$r = '';

											foreach ( $display_name_options as $option ) {
												$label = $option['label'];
												if ( $selected == $option['value'] ) // Make default first in list
													$p = "\n\t<option style=\"padding-right: 10px;\" selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
												else
													$r .= "\n\t<option style=\"padding-right: 10px;\" value='" . esc_attr( $option['value'] ) . "'>$label</option>";
											}
											echo $p . $r;
										?>
									</select>
									<label class="description" for="nlws_wu_settings[<?php echo $role; ?>_display_name]"><?php _e( 'Choose how your want the username to be displayed' ); ?></label>
								</td>
							</tr>
							
							<tr valign="top"><th scope="row"><?php _e( 'Site Admin link text' ); ?></th>
								<td>
									<input id="nlws_wu_settings[<?php echo $role; ?>_siteadmin_link]" class="regular-text" type="text" name="nlws_wu_settings[<?php echo $role; ?>_siteadmin_link]" value="<?php esc_attr_e( $options[$role . '_' . 'siteadmin_link'] ); ?>" />
									<label class="description" for="nlws_wu_settings[<?php echo $role; ?>_siteadmin_link]"><?php _e( 'The link text that is displayed to a logged in user, giving them admin / profile access.' ); ?></label>
								</td>
							</tr>
							
							<tr valign="top"><th scope="row"><?php _e( 'Logout link text' ); ?></th>
								<td>
									<input id="nlws_wu_settings[<?php echo $role; ?>_logout_link]" class="regular-text" type="text" name="nlws_wu_settings[<?php echo $role; ?>_logout_link]" value="<?php esc_attr_e( $options[$role . '_' . 'logout_link'] ); ?>" />
									<label class="description" for="nlws_wu_settings[<?php echo $role; ?>_logout_link]"><?php _e( 'The link text that is displayed to a logged in user, logging them out of the site.' ); ?></label>
								</td>
							</tr>
						</table>
					</div>
				<?php endforeach; ?>
			</div>
			
			<p class="submit">
		    	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		    </p>
		</form>
	</div><!-- .wrap -->
<?php }