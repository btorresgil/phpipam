<?php

/**
 * Script to display available VLANs
 */

/* verify that user is authenticated! */
isUserAuthenticated ();

/* required functions */
if(!function_exists('getSubnetStatsDashboard')) {
require_once( dirname(__FILE__) . '/../../../functions/functions.php' );
}

/* print last 5 access logs */
$favs = getFavouriteSubnets();

# title
print "<h4>"._('Favourite subnets')."</h4>";
print "<hr>";

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
	print "	<th>"._('Used')."</th>";
	print "	<th style='width:5px;'></th>";
	print "</tr>";
	
	# logs
	foreach($favs as $f) {
		# if subnet already removed (doesnt exist) dont print it!
		if(sizeof($f)>0) {
			print "<tr class='favSubnet-$f[subnetId]'>";
			
			if($f['isFolder']==1) {
				print "	<td><a href='folder/$f[sectionId]/$f[subnetId]/'><i class='icon-folder-close icon-gray'></i> $f[description]</a></td>";
			}
			else {
				print "	<td><a href='subnets/$f[sectionId]/$f[subnetId]/'>".transform2long($f['subnet'])."/$f[mask]</a></td>";		
			}
			
			print "	<td>$f[description]</td>";
			print "	<td><a href='subnets/$f[sectionId]/'>$f[section]</a></td>";
			if(strlen($f['vlanId'])>0) {
			# get vlan info
			$vlan = getVlanById($f['vlanId']);
			print "	<td>$vlan[number]</td>";
			} else {
			print "	<td>/</td>";
			}
			
			# used
			
			# masterSubnet
			if( $f['masterSubnetId']==0 || empty($f['masterSubnetId']))  	{ $masterSubnet = true; }		# check if it is master
			else 														 	{ $masterSubnet = false; }
	
			if($f['isFolder']==1) {
				print  '<td></td>';
			}
			elseif( (!$masterSubnet) || (!subnetContainsSlaves($f['subnetId']))) {
	    		$ipCount = countIpAddressesBySubnetId ($f['subnetId']);
	    		$calculate = calculateSubnetDetails ( gmp_strval($ipCount), $f['mask'], $f['subnet'] );
	
	    		print ' <td class="used">'. reformatNumber($calculate['used']) .'/'. reformatNumber($calculate['maxhosts']) .' ('.reformatNumber($calculate['freehosts_percent']) .' %)</td>';
	    	}
	    	else {
				print '<td></td>'. "\n";
			}	
			
			# remove
			print "	<td><a class='btn btn-small editFavourite' data-subnetId='$f[subnetId]' data-action='remove' data-from='widget'><i class='icon-star favourite-$f[subnetId]' rel='tooltip' title='"._('Click to remove from favourites')."'></i></a></td>";
		
			print "</tr>";
		}
	}
	
	print "</table>";	
}
?>