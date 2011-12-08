<?php
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors[at]codeflavors.com )
 * @version 2.4
 */

/* set the current page url */
$current_page = menu_page_url('featured-articles-lite/settings.php', false);

if( isset( $_POST['fa_options'] ) && !empty($_POST['fa_options']) ){
	if( !wp_verify_nonce($_POST['fa_options'],'featured-articles-set-options') ){
		die('Sorry, your action is invalid.');
	}else{
		
		$plugin_options = array(
			'complete_unistall'=>0
		);		
		foreach( $plugin_options as $option=>$value ){
			if( isset( $_POST[$option] ) ){
				$plugin_options = $_POST[$option];
			}	
		}		
		// get wordpress roles
		$roles = $wp_roles->get_names();
		foreach( $roles as $role=>$name ){
			// administrator has default access so skip this role
			if( 'administrator' == $role ) continue;
			// add/remove editing capabilities
			if( isset( $_POST['role'][$role] ) ){
				$wp_roles->add_cap($role, FA_CAPABILITY);
			}else{
				$wp_roles->remove_cap($role, FA_CAPABILITY);
			}
		}
		
		FA_plugin_options();		
		wp_redirect( $current_page );	
		exit();	
	}	
}

$options = FA_plugin_options();
?>
<div class="wrap">
	<div class="icon32" id="icon-options-general"><br></div>
    <h2 id="add-new-user">Featured Articles - plugin settings</h2>
    <form method="post" action="<?php echo $current_page;?>&amp;noheader=1">
        <?php wp_nonce_field('featured-articles-set-options', 'fa_options');?>
        <table class="form-table">
        <tbody>
        <tr valign="top">
            <th scope="row">
            	<label for="">Set plugin access: </label><br />
                <span class="description">Admins have default access</span>
            </th>
            <td>            	
                <?php
					$roles = $wp_roles->get_names();
					foreach( $roles as $role=>$name ):
						if( 'administrator' == $role ){
							continue;
						}	
						$r = $wp_roles->get_role( $role );
						$checked = array_key_exists( FA_CAPABILITY, $r->capabilities ) ? ' checked="checked"' : '';							
				?>
                	<label><input type="checkbox" name="role[<?php echo $role;?>]" value="1"<?php echo $checked;?> style="width:auto;" /> <?php echo $name;?></label><br />
                 
                <?php endforeach;?>
            </td>
        </tr>
        <tr valign="top">
        	<th scope="row">
            	<label for="complete_uninstall">Enable full uninstall:<br />
                <?php if($options['complete_uninstall']):?>
                <span style="color:red;">While we don't expect anything bad to happen we recommended that you first back-up your database before completely removing the plugin.</span>
                <?php else:?>
            	<span class="description">If checked, when the plugin is uninstalled from plugins page all data (sliders, slides, options and meta fields) will also be removed from database.</span>
                <?php endif;?>
            </th>
            <td><input type="checkbox" name="complete_uninstall" id="complete_uninstall" value="1"<?php if($options['complete_uninstall']):?> checked="checked"<?php endif;?> /></td>
        </tr>
        <tr valign="top">
        	<th scope="row">
            	<label for="complete_uninstall">Enable automatic slider insertion:<br />
                <span class="description">
					When enabled it will display on slider editing/creation a new panel that allows insertion into category pages, home page and pages of slides without the need of additional code.<br />
					Please note that this kind of slider insertion in your pages will display the slider before the loop you have in those pages. For more precise display into your pages we recommend using the manual insertion or the shortcode insertion.
				</span>                
            </th>
            <td><input type="checkbox" name="auto_insert" id="complete_uninstall" value="1"<?php if($options['auto_insert']):?> checked="checked"<?php endif;?> /></td>
        </tr>
        </tbody>
        </table>
<p class="submit">
    <input type="submit" value="Save settings" class="button-primary" id="addusersub" name="adduser">
</p>        
    </form>
</div>