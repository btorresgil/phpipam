<?php

/**
 * Script to display widget edit
 *************************************/
 
/* required functions */
require_once('../../functions/functions.php'); 

/* verify that user is admin */
checkAdmin();

/* Remove .php form wfile if it is present */
$_POST['wfile'] = str_replace(".php","",trim(@$_POST['wfile']));

/* try to execute */
if(!modifyWidget($_POST)) 	{ print "<div class='alert alert-error'  >"._("Widget $_POST[action] error")."!</div>"; }
else 						{ print "<div class='alert alert-success'>"._("Widget $_POST[action] success")."!</div>"; }

?>