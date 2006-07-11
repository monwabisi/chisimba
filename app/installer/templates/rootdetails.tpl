<?php
$objHelp = new domtt();
?>
<table class="install-table">
<tbody valign="top">
<tr valign="top">
	<td>
	<?php echo $objHelp->show('Select a name for your System','This name will appear as a title in your application');?>
</td>
	<td width="50%">
	System Name.
	</td>
	<td width="50%" >
	<?php echo $sys_name?>
	</td>
</tr>
<tr valign="top">
	<td>
	<?php echo $objHelp->show('Select a Server Name','This name will be a unique name when it come into contact with other Chisimba Systems');?>
	</td>
	<td width="50%">
	Server Name.
	</td>
	<td width="50%" >
	<?php echo $serverName?>
	<input type="hidden" name="generatedName" value="FALSE" />
    </td>
</tr>
<tr valign="top">
	<td>
	<?php echo $objHelp->show('Select a Location','Where is your server located');?>
	</td>
	<td width="50%">
	Server location?
	</td>
	<td width="50%" >
	<?php echo $serverLocation;?>
	</td>
</tr>
<tr valign="top">
	<td>
		<?php echo $objHelp->show('System Owner','This person is normally the administrator of the system and will play a super user role within the system');?>

	</td>
	<td>
	System Owner.
	</td>
	<td >
	<?php echo $sys_owner?>
	</td>
	
</tr>
<tr valign="top">
	<td>
		<?php echo $objHelp->show('System email address','This can be the System Owners email or one that can be created for this Chisimba installation. Updates and error logs will be forwarded to this email address');?>

	</td>
	<td >
	Default Email Address.
	</td>
	<td >
	<?php echo $root_email?>
	</td>
	
</tr>
<tr valign="top">
	<td>
	<?php echo $objHelp->show('Log Activity','Select this if you want the system to log the activity of the user');?>
	</td>
	<td >
	Log user activity?
	</td>
	<td >
	<?php echo $log_enable?>
	</td>
	
</tr>

</tr>
<tr valign="top">
	<td>
		<?php echo $objHelp->show('Debug','This will prevent error messegers from appearing');?>
	</td>
	<td >
	Debug enable?
	</td>
	<td >
	<?php echo $debug_enable?>
	</td>
	
</tr>

<tr valign="top">
	<td>
	<?php echo $objHelp->show('Debug Path','Select the path to where you want the log file to be stored');?>
	</td>
	<td >
	Error Log Path
	</td>
	<td >
	<?php echo $log_path?>
	</td>
	
</tr>
<tr valign="top">
	
	<td>
		<?php echo $objHelp->show('Post Login Module','This module will be used after a user logs into the system');?>
	</td>
	<td >
	Default postlogin module?
	</td>
	<td>
	<?php echo $postLogin?>
	</td>
	
</tr>
<tr valign="top">
	
	<td>
	<?php echo $objHelp->show('Skin','Choose a default skin');?>	</td>
	<td >
	Defaul Skin
	</td>
	<td><?php echo $defaultskin ?>
    </td>
	
</tr>
<tr>
	<td>
	<?php echo $objHelp->show('Post Login Module','The Site root URL is normally localhost or for example www.yoursite.co.za or www.yoursite.co.za/chisimab');?>
	</td>
	<td >
	Site root URL. 
	</td>
	<td >
	<?php echo $site_url ?>
	</td>
	
</tr>
<tr>
	<td>
	<?php echo $objHelp->show('Proxy Settings','If you are using a proxy then add your proxy in the format provided');?>
	</td>
	<td >
	Proxy Settings. 
	</td>
	<td >
	<?php echo $proxy ?>
	</td>
	
</tr>
<?php $contentPath ?>
<?php $relContentPath ?>
</tbody>
</table>
