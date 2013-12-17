<script type="text/javascript">
$(document).ready(function() {
	if ($("[rel=tooltip]").length) { $("[rel=tooltip]").tooltip(); }
	
	return false;
});
</script>

<?php

/* required functions */
if(!function_exists('getSubnetStatsDashboard')) {
require_once( dirname(__FILE__) . '/../../../functions/functions.php' );
}

/* print last 5 access logs */
$favs = getFavouriteSubnets();

# print if none
if(sizeof($favs) == 0 || !isset($favs[0])) {
	print "<blockquote style='margin-top:20px;margin-left:20px;'>";
	print "<p>"._("No favourite subnets selected")."</p><br>";
	print "<small>"._("You can add subnets to favourites by clicking star icon in subnet details")."!</small><br>";
	print "</blockquote>";
}
else {
	print "<table class='table table-condensed table-hover table-top'>";
	
	# headers
	print "<tr>";
	print "	<th>"._('Subnet')."</th>";
	print "	<th>"._('Description')."</th>";
	print "	<th>"._('Section')."</th>";
	print "	<th>"._('VLAN')."</th>";
	print "	<th style='width:5px;'></th>";
	print "</tr>";
	
	# logs
	foreach($favs as $f) {
		print "<tr class='favSubnet-$f[subnetId]'>";
		
		if($f['isFolder']==1) {
			print "	<td><a href='folder/$f[sectionId]/$f[subnetId]/'><i class='icon-folder-close icon-gray'></i> $f[description]</a></td>";
		}
		else {
			print "	<td><a href='subnets/$f[sectionId]/$f[subnetId]/'>".transform2long($f['subnet'])."/$f[mask]</a></td>";		
		}
		print "	<td>$f[description]</td>";
		print "	<td>$f[section]</td>";
		if(strlen($f['vlanId'])>0) {
		# get vlan info
		$vlan = getVlanById($f['vlanId']);
		print "	<td>$vlan[number]</td>";
		} else {
		print "	<td>/</td>";
		}
		
		# remove
		print "	<td><a class='btn btn-small editFavourite' data-subnetId='$f[subnetId]' data-action='remove' data-from='widget'><i class='icon-star favourite-$f[subnetId]' rel='tooltip' title='"._('Click to remove from favourites')."'></i></a></td>";
	
		print "</tr>";
	}
	
	print "</table>";	
}
?>