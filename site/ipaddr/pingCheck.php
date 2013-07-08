<?php

/**
 *	Script that checks if IP is alive
 */


/* include required scripts */
require_once('../../functions/functions.php');

/* verify that user is logged in */
isUserAuthenticated(false);

// verify that user has write access
$subnetPerm = checkSubnetPermission ($_POST['subnetId']);
if($subnetPerm < 2) {
	echo _("error").":"._("Insufficient permissions");
	die();
}

//get IP address details
$ip = getIpAddrDetailsById ($_POST['id']);

//verify that pign path is correct
if(!file_exists($pathPing)) { $pingError = true; }

//try to ping it
if(pingHost($ip['ip_addr'], 1) == '0')  {
	$status = "Online";
	@updateLastSeen($_POST['id']);
}
else {
	$status = "Offline";
}
?>


<!-- header -->
<div class="pHeader"><?php print _('Ping check result'); ?></div>

<!-- content -->
<div class="pContent">
	<?php if($pingError) { ?>
		<div class="alert alert-error"><?php print _("Invalid ping path")."<hr>". _("You can set parameters for scan under functions/scan/config-scan.php"); ?></div>
	<?php } elseif($status == "Online") { ?>
		<div class="alert alert-success"><?php print _("IP address")." ".$ip['ip_addr']." "._("is alive"); ?></div>
	<?php } else { ?>
		<div class="alert alert-error"><?php print _("IP address")." ".$ip['ip_addr']." "._("is not alive"); ?></div>
	<?php } ?>
</div>

<!-- footer -->
<div class="pFooter">
	<button class="btn btn-small hidePopups"><?php print _('Close window'); ?></button>
</div>