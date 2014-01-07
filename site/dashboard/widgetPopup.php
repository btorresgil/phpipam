<?php

/* show available widgets */
require(dirname(__FILE__) . '../../../functions/functions.php');

/* verify that user is authenticated! */
isUserAuthenticated ();

/* get username */
$ipamusername = getActiveUserDetails ();

//user widgets form database
$uwidgets = explode(";",$ipamusername['widgets']);	//selected
$uwidgets = array_filter($uwidgets);

# get all widgets
if($ipamusername['role']=="Administrator") 	{ $widgets  = getAllWidgets(true, false); } 
else 										{ $widgets  = getAllWidgets(false, false); }
		
?>

<!-- header -->
<div class="pHeader"><?php print _('Add new widget to dashboard'); ?></div>

<!-- content -->
<div class="pContent">
	<?php
	print "<ul id='sortablePopup' class='sortable'>";
	# print widghets that are not yet selected
	$w = 0;
	foreach($widgets as $k=>$w) {
		if(!in_array($k, $uwidgets))	{ 
			$wtmp = $widgets[$k];
			//size fix
			if(strlen($wtmp['wsize'])==0)	{ $wtmp['wsize']=6; }
			print "<li id='$k'><a href='' class='btn btn-small widget-add' id='w-$wtmp[wfile]' data-size='$wtmp[wsize]' data-htitle='$wtmp[wtitle]'><i class='icon icon-plus'></i></a> $wtmp[wtitle]</li>"; 
			$w++;
		}
	}	
	print "</ul>";
	
	# print empty
	if($w==0)	{ print _("<div class='alert alert-info'>All widgets are already on dashboard")."!</div>"; }
	?>
</div>

<!-- footer -->
<div class="pFooter">
	<button class="btn btn-small hidePopups"><?php print _('Cancel'); ?></button>
</div>