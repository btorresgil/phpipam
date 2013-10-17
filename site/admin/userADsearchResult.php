<?php

/**
 * Script to display usermod result
 *************************************/
 
/* required functions */
require_once('../../functions/functions.php'); 

/* verify that user is admin */
checkAdmin();

//include AD script
include (dirname(__FILE__) . "/../../functions/adLDAP/src/adLDAP.php");

// get All settings
$settings = getAllSettings();


//open connection
try {
	//get settings for connection
	$ad = getADSettings();
	
	//AD
	$adldap = new adLDAP(array( 'base_dn'=>$ad['base_dn'], 'account_suffix'=>$ad['account_suffix'], 
								'domain_controllers'=>$ad['domain_controllers'], 'use_ssl'=>$ad['use_ssl'],
								'use_tls'=> $ad['use_tls'], 'ad_port'=> $ad['ad_port']
								));
	
	// set OpenLDAP flag
	if($settings['domainAuth'] == "2") { $adldap->setUseOpenLDAP(true); }
	
}
catch (adLDAPException $e) {
	die('<div class="alert alert-error">'. $e .'</div>');
}


//at least 2 chars
if(strlen($_POST['dname'])<2) {
	die("<div class='alert alert-warning'>"._('Please enter at least 2 characters')."</div>");
}

//search for domain user!
$userinfo = $adldap->user_info("$_POST[dname]*", array("*"));

//check for found
if($userinfo['count']=="0") {
	print "<div class='alert alert-info'>"._('No users found')."!</div>";
} else {
	print _("Following users were found").": ($userinfo[count]):<hr>";
	
	print "<table class='table table-striped'>";
	
	unset($userinfo['count']);
	foreach($userinfo as $u) {
		print "<tr>";
		print "	<td>".$u['displayname'][0]." (".$u['description'][0].")";
		if(strlen($u['title'][0])>0) {
			print "<div class='help-block'>(".$u['title'][0].")</div>";
		}
		print "</td>";
		print "	<td>".$u['samaccountname'][0]."</td>";
		print "	<td>".$u['mail'][0]."</td>";
		//actions
		print " <td style='width:10px;'>";
		print "		<a href='' class='btn btn-small btn-success userselect' data-uname='".$u['displayname'][0]."' data-username='".$u['samaccountname'][0]."'>"._('Select')."</a>";
		print "	</td>";
		print "</tr>";
	}
	
	print "</table>";
}


?>