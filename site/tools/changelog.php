<?php

/**
 * Script to display available VLANs
 */

/* required functions */
if(!function_exists('getSubnetStatsDashboard')) {
require_once( dirname(__FILE__) . '/../../../functions/functions.php' );
}

/* verify that user is authenticated! */
isUserAuthenticated ();

# header
print "<h4 style='margin-top:30px;'>"._('Changelog')."</h4><hr>";

# if enabled
if($settings['enableChangelog'] == 1) {	
	# set default size
	if(!isset($_REQUEST['climit']))	{ $_REQUEST['climit'] = 50; }
	
	# filter
	print "<div class='input-append pull-right' style='margin-bottom:20px;'>";
	print "	<form name='cform' id='cform'>";
	print "	<select name='climit' class='input-small climit'>";
	$printLimits = array(50,100,250,500);
	foreach($printLimits as $l) {
		if($l == $_REQUEST['climit'])	{ print "<option value='$l' selected='selected'>$l</option>"; }
		else							{ print "<option value='$l'>$l</option>"; }
	}
	print "	</select>";
	print "	<input class='span2 cfilter' id='appendedInputButton' name='cfilter' value='$_REQUEST[cfilter]' type='text' style='width:150px;'><input type='submit' class='btn' value='"._('Search')."'>";
	print " </form>";
	print "</div>";
	
	# printout
	include_once('changelogPrint.php');
}
else {
	print "<div class='alert alert-info'>"._("Change logging is disabled. You can enable it under administration")."!</div>";
}
?>