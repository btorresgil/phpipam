<?php

/**
 * HomePage display script
 *  	show somw statistics, links, help,...
 *******************************************/

/* verify login and permissions */
isUserAuthenticated(); 

?>
<script type="text/javascript">
//show clock
$(function($) {
	$('span.jclock').jclock();
});
</script>


<script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
<script>
$(document).ready(function() {
	// initialize sortable
	$(document).on("click",'td.w-lock', function() {
		//remove class
		$(this).removeClass('w-lock').addClass('w-unlock');
		$(this).find('i').removeClass('icon-move').addClass('icon-ok');	//change icon
		$(this).find('a').attr('data-original-title','Click to save widgets order');
		$('#dashboard .inner i').fadeIn('fast');
		$('#dashboard .add-widgets').fadeIn('fast');
		$('#dashboard .inner').addClass('movable');
		//start
		$('#dashboard .row-fluid').sortable({
			connectWith: ".row-fluid",
			start: function( event, ui ) {
				var iid = $(ui.item).attr('id');
				$('#'+iid).addClass('drag');
			},
			stop: function( event, ui ) {
				var iid = $(ui.item).attr('id');
				$('#'+iid).removeClass('drag');		
			}		
		});
		return false;
	});
	//lock sortable back
	$(document).on("click",'td.w-unlock', function() {
		//remove class
		$(this).removeClass('w-unlock').addClass('w-lock');
		$(this).find('i').removeClass('icon-move').addClass('icon-move');	//change icon
		$(this).find('a').attr('data-original-title','Clik to reorder widgets');	
		$('#dashboard .inner .icon-action').fadeOut('fast');
		$('#dashboard .add-widgets').fadeOut('fast');
		$('#dashboard .inner').removeClass('movable');

		//get all ids that are checked
		var widgets = $('#dashboard .widget-dash').map(function(i,n) {
			//only checked
			return $(n).attr('id').slice(2);	
		}).get().join(';');
		
		//save user widgets
		$.post('site/tools/userMenuSetWidgets.php', {widgets:widgets}, function(data) {});

		//remove sortable class
		$('#dashboard .row-fluid').sortable("destroy");
		
		return false;
	});
});
</script>



<!-- charts -->
<script language="javascript" type="text/javascript" src="js/flot/jquery.flot.js"></script>
<script language="javascript" type="text/javascript" src="js/flot/jquery.flot.categories.js"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/flot/excanvas.min.js"></script><![endif]-->


<div class="welcome">
<b><?php $user = getActiveUserDetails(); print_r($user['real_name']); ?></b>, <?php print _('welcome to your IPAM dashboard'); ?>. <span class="jclock pull-right"></span>
</div>

<?php
/* print number of requests if admin and if they exist */
$requestNum = countRequestedIPaddresses();
if( ($requestNum != 0) && (checkAdmin(false,false))) {
	print '<div class="alert alert-info">'._('There are').' <b><a href="administration/manageRequests/" id="adminRequestNotif">'. $requestNum .' '._('requests').'</a></b> '._('for IP address waiting for your approval').'!</div>';
}
?>


<?php

# get all widgets
if($user['role']=="Administrator") 	{ $widgets = getAllWidgets(true,  false); }
else								{ $widgets = getAllWidgets(false, false); } 

# show user-selected widgets
$uwidgets = array_filter(explode(";",$user['widgets']));

# split widgets to rows (chunks)
$currSize = 0;					//to calculate size
$m=0;							//to calculate chunk index

foreach($uwidgets as $uk=>$uv) {
	//get fetails
	$wdet = $widgets[$uv];
	if(strlen($wdet['wsize'])==0)	{ $wsize = 6; }
	else							{ $wsize = $wdet['wsize']; }
	
	//calculate current size
	$currSize = $currSize + $wsize;
	
	//ok, we have sizes, we need to split them into chunks of 12
	if($currSize > 12) { 
		$m++; 					//new index
		$currSize = $wsize; 	//reset size
	}
	
	//add to array
	$uwidgetschunk[$m][] = $uv;
}


# print
print "<div class='add-widgets' style='display:none'>";
print "	<a href='' class='btn btn-small btn-success add-new-widget'><i class='icon-plus icon-white'></i> Add new widget</a>";
print "</div>";

if(sizeof($uwidgets)>1) {
	foreach($uwidgetschunk as $w) {
	
		print '<div class="row-fluid">';
	
		# print itams in a row
		foreach($w as $c) {
	
			/* print items */
			$wdet = $widgets[$c];
			if(array_key_exists($c, $widgets)) {
				//reset size if not set
				if(strlen($wdet['wsize'])==0)	{ $wdet['wsize'] = 6; }
			
				print "	<div class='span$wdet[wsize] widget-dash' id='w-$wdet[wfile]'>";
				print "	<div class='inner'><i class='icon-remove remove-widget icon-action icon-gray pull-right'></i>";
				// href?
				if($wdet['whref']=="yes")	{ print "<a href='widgets/$wdet[wfile]/'> <h4>"._($wdet['wtitle'])."</h4></a>"; }
				else						{ print "<h4>"._($wdet['wtitle'])."</h4>"; }
				print "		<div class='hContent'>";
				print "			<div style='text-align:center;padding-top:50px;'><strong>"._('Loading statistics')."</strong><br><img src='css/images/loading_dash.gif'></div>";
				print "		</div>";
				print "	</div>";
				print "	</div>";
				
			}
			# invalid widget
			else {
				print "	<div class='span6' id='w-$c'>";
				print "	<div class='inner'>";
				print "		<blockquote style='margin-top:20px;margin-left:20px;'><p>Invalid widget $c</p></blockquote>";
				print "	</div>";
				print "	</div>";
			}
		
		}	
		
		print "</div>";
	}
}
# empty
else {
	print "<br><div class='alert alert-warning'><strong>"._('No widgets selected')."!</strong> <hr>"._('Please select widgets to be displayed on dashboard on user menu page')."!</div>";
}

?>
<hr>