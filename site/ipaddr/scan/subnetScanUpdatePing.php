<?php

/*
 * Update alive status of all hosts in subnet
 ***************************/

/* required functions */
require_once('../../../functions/functions.php'); 

/* verify that user is logged in */
isUserAuthenticated(true);

/* verify that user has write permissions for subnet */
$subnetPerm = checkSubnetPermission ($_REQUEST['subnetId']);
if($subnetPerm < 2) 	{ die('<div class="alert alert-error">'._('You do not have permissions to modify hosts in this subnet').'!</div>'); }

/* verify post */
CheckReferrer();

# get subnet details
$subnet = getSubnetDetailsById ($_POST['subnetId']);

# get all existing IP addresses
$addresses = getIpAddressesBySubnetId ($_POST['subnetId']);

# rekey - replace array key with IP address, needed for future matchung
foreach($addresses as $k=>$a) {
	$aout[$a['ip_addr']] = $a;
}
$addresses = $aout;

# exclude those marked as don't ping
$n=0;
$excluded = array();
foreach($addresses as $m=>$ipaddr) {
	if($ipaddr['excludePing']=="1") {
		//set result
		$ipa = $ipaddr['ip_addr'];
		$excluded[$ipa]['ip_addr'] = $ipaddr['ip_addr'];
		$excluded[$ipa]['code'] = 100;
		$excluded[$ipa]['status'] = "Excluded from check";
	
		//remove
		unset($addresses[$m]);
		//next
		$n++;
	}	
	# create ip's from ip array for ones that need to be checked
	else {
		$ip[] = $ipaddr['ip_addr'];
	}
}


# check if any ips are present and scan
if($ip) {
	# create 1 line for $argv
	$ip = implode(";", $ip);
	
	# get php exec path
	if(!$phpPath = getPHPExecutableFromPath()) {
		die('<div class="alert alert-error">Cannot access php executable!</div>');
	}
	# set script
	$script = dirname(__FILE__) . '/../../../functions/scan/scanIPAddressesScript.php';
	
	# invoke CLI with threading support
	$cmd = "$phpPath $script '$ip'";
	
	# save result to $output
	exec($cmd, $output, $retval);
		
	# die of error
	if($retval != 0) {
		die("<div class='alert alert-error'>Error executing scan! Error code - $retval</div>");
	}	
			
	# format result - alive
	$result = json_decode(trim($output[0]), true);
	
	# if not numeric means error, print it!
	if(!is_numeric($result[0]))	{
		$error = $result[0];
	}
}

# recode to same array with statuses 
$m=0;
foreach($result as $k=>$r) {

	foreach($r as $ip) {
		# format output
		$res[$ip]['ip_addr'] = $ip;
		
		if($k=="dead")	{ 
			$res[$ip]['status'] = "Offline";			
			$res[$ip]['code']=1; 
		}
		else { 
			$res[$ip]['status'] = "Online";
			$res[$ip]['code']=0; 
		}			
		$m++;
	}
}
# add skipped
$res = $res + $excluded;

# order by IP address
ksort($res);
?>


<h5><?php print _('Scan results');?>:</h5>
<hr>

<?php
//empty
if(!isset($res)) {
	print "<div class='alert alert-info'>"._('Subnet is empty')."</div>";
}
else {
	//table
	print "<table class='table table-condensed'>";
	
	//headers
	print "<tr>";
	print "	<th>"._('IP')."</th>";
	print "	<th>"._('Description')."</th>";
	print "	<th>"._('status')."</th>";
	print "	<th>"._('hostname')."</th>";
	print "</tr>";
	
	//loop
	foreach($res as $r) {
		//set class
		if($r['code']==0)		{ $class='success'; }
		elseif($r['code']==100)	{ $class='warning'; }		
		else					{ $class='error'; }
	
		print "<tr class='$class'>";
		print "	<td>".transform2long($r['ip_addr'])."</td>";
		print "	<td>".$addresses[$r['ip_addr']]['description']."</td>";
		print "	<td>"._("$r[status]")."</td>";
		print "	<td>".$addresses[$r['ip_addr']]['dns_name']."</td>";

		print "</tr>";
	}
	
	print "</table>";
}
?>