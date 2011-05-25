<div class="wrap">
	<div class="icon32" id="icon-users"><br></div>
    <h2 id="add-new-user">Set editing permissions</h2>
    <p>Set the user levels that can edit the slider settings (administrator can edit by default).</p>
    
    <form method="post" action="admin.php?page=featured-articles-lite/wp_featured_articles.php/FA_permissions&noheader=1">
        <?php wp_nonce_field('FA_permissions', 'FA_perm');?>
        <table class="edit-form">
        <tbody>
        <tr class="form-field">
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
        </tbody>
        </table>
<p class="submit">
    <input type="submit" value="Set users level" class="button-primary" id="addusersub" name="adduser">
</p>        
    </form>
</div>