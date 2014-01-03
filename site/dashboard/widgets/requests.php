<?php

/*
 * Script to print some stats on home page....
 *********************************************/

/* required functions */
if(!function_exists('getSubnetStatsDashboard')) {
require_once( dirname(__FILE__) . '/../../../functions/functions.php' );
}

/* get all */
$allActiveRequests = getAllActiveIPrequests();
?>



<?php
if(sizeof($allActiveRequests)==0) {
	print "<blockquote style='margin-top:20px;margin-left:20px;'>";
	print "<small>"._("No IP address requests available")."!</small><br>";
	print "</blockquote>";
} 
# print
else {
?>

<table id="requestedIPaddresses" class="table table-condensed table-hover table-top">

<!-- headers -->
<tr>
	<th></th>
	<th><?php print _('Subnet'); ?></th>
	<th><?php print _('Hostname'); ?></th>
	<th><?php print _('Description'); ?></th>
	<th><?php print _('Requested by'); ?></th>
</tr>

<?php 
	# print requests
	foreach($allActiveRequests as $request) {
	
	//get subnet details
	$subnet = getSubnetDetailsById ($request['subnetId']);
	
	print '<tr>'. "\n";
	print "	<td><button class='btn btn-small' data-requestid='$request[id]'><i class='icon-gray icon-pencil'></i></button></td>";
	print '	<td>'. Transform2long($subnet['subnet']) .'/'. $subnet['mask'] .' ('. $subnet['description'] .')</td>'. "\n";
	print '	<td>'. $request['dns_name'] .'</td>'. "\n";
	print '	<td>'. $request['description'] .'</td>'. "\n";
	print '	<td>'. $request['requester'] .'</td>'. "\n";
	print '</tr>'. "\n";
	}
?>

</table>

<?php } ?>

