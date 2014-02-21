<?php

/**
 *	Edit switch details
 ************************/

/* required functions */
require_once('../../functions/functions.php'); 

/* verify that user is admin */
if (!checkAdmin()) die('');

/* get custom fields */
$custom = getCustomFields('switches');

/* get switch detaild by Id! */
if( ($_POST['action'] == "edit") || ($_POST['action'] == "delete") ) {
	$switch = getSwitchDetailsById($_POST['switchId']);
}

if ($_POST['action'] == "delete") 	{ $readonly = "readonly"; }
else 								{ $readonly = ""; }
?>


<!-- header -->
<div class="pHeader"><?php print ucwords(_("$_POST[action]")); ?> <?php print _('device'); ?></div>


<!-- content -->
<div class="pContent">

	<form id="switchManagementEdit">
	<table class="table table-noborder table-condensed">

	<!-- hostname  -->
	<tr>
		<td><?php print _('Hostname'); ?></td>
		<td>
			<input type="text" name="hostname" class="form-control input-sm" placeholder="<?php print _('Hostname'); ?>" value="<?php if(isset($switch['hostname'])) print $switch['hostname']; ?>" <?php print $readonly; ?>>
		</td>
	</tr>

	<!-- IP address -->
	<tr>
		<td><?php print _('IP address'); ?></td>
		<td>
			<input type="text" name="ip_addr" class="form-control input-sm" placeholder="<?php print _('IP address'); ?>" value="<?php if(isset($switch['ip_addr'])) print $switch['ip_addr']; ?>" <?php print $readonly; ?>>
		</td>
	</tr>

	<!-- Type -->
	<tr>
		<td><?php print _('Device type'); ?></td>
		<td>
			<select name="type" class="form-control input-sm input-w-auto">
			<?php
			$types = getSwitchTypes();
			foreach($types as $key=>$name) {
				if($switch['type'] == $key)	{ print "<option value='$key' selected='selected'>$name</option>"; }
				else						{ print "<option value='$key' >$name</option>"; }
			}
			?>
			</select>
		</td>
	</tr>

	<!-- Vendor -->
	<tr>
		<td><?php print _('Vendor'); ?></td>
		<td>
			<input type="text" name="vendor" class="form-control input-sm" placeholder="<?php print _('Vendor'); ?>" value="<?php if(isset($switch['vendor'])) print $switch['vendor']; ?>" <?php print $readonly; ?>>
		</td>
	</tr>

	<!-- Model -->
	<tr>
		<td><?php print _('Model'); ?></td>
		<td>
			<input type="text" name="model" class="form-control input-sm" placeholder="<?php print _('Model'); ?>" value="<?php if(isset($switch['model'])) print $switch['model']; ?>" <?php print $readonly; ?>>
		</td>
	</tr>

	<!-- Version -->
	<tr>
		<td><?php print _('SW version'); ?></td>
		<td>
			<input type="text" name="version" class="form-control input-sm" placeholder="<?php print _('Software version'); ?>" value="<?php if(isset($switch['version'])) print $switch['version']; ?>" <?php print $readonly; ?>>
		</td>
	</tr>

	<!-- Description -->
	<tr>
		<td><?php print _('Description'); ?></td>
		<td>
			<textarea name="description" class="form-control input-sm" placeholder="<?php print _('Description'); ?>" <?php print $readonly; ?>><?php if(isset($switch['description'])) print $switch['description']; ?></textarea>
			<?php
			if( ($_POST['action'] == "edit") || ($_POST['action'] == "delete") ) {
				print '<input type="hidden" name="switchId" value="'. $_POST['switchId'] .'">'. "\n";
			} ?>
			<input type="hidden" name="action" value="<?php print $_POST['action']; ?>">
		</td>
	</tr>

	<!-- Custom -->
	<?php
	if(sizeof($custom) > 0) {

		print '<tr>';
		print '	<td colspan="2"><hr></td>';
		print '</tr>';

		foreach($custom as $field) {
		
			# replace spaces
		    $field['nameNew'] = str_replace(" ", "___", $field['name']);
			
			print "<tr>";
			print "	<td>$field[name]</td>";
			print "	<td>";
			print "		<input type='text' class='form-control input-sm' name='$field[nameNew]' value='".$switch[$field['name']]."' $readonly>";
			print "	</td>";
			print "</tr>";
		}
	}
	
	?>

	<!-- Sections -->
	<tr>
		<td colspan="2">
			<hr>
		</td>
	</tr>
	<tr>
		<td colspan="2"><?php print _('Sections to display device in'); ?>:</td>
	</tr>
	<tr>
		<td></td>
		<td>
		<?php
		$sections = fetchSections();
		
		/* reformat switch sections */ 
		$switchSections = reformatSwitchSections($switch['sections']);
		
		foreach($sections as $section) {
			if(in_array($section['id'], $switchSections)) 	{ print '<div class="checkbox" style="margin:0px;"><input type="checkbox" name="section-'. $section['id'] .'" value="on" checked> '. $section['name'] .'</div>'. "\n"; }
			else 											{ print '<div class="checkbox" style="margin:0px;"><input type="checkbox" name="section-'. $section['id'] .'" value="on">'. $section['name'] .'</span></div>'. "\n"; }
		}
		?>
		</td>
	</tr>

	</table>
	</form>
</div>


<!-- footer -->
<div class="pFooter">
	<div class="btn-group">
		<button class="btn btn-sm btn-default hidePopups"><?php print _('Cancel'); ?></button>
		<button class="btn btn-sm btn-default <?php if($_POST['action']=="delete") { print "btn-danger"; } else { print "btn-success"; } ?>" id="editSwitchsubmit"><i class="fa <?php if($_POST['action']=="add") { print "fa-plus"; } else if ($_POST['action']=="delete") { print "fa-trash-o"; } else { print "fa-check"; } ?>"></i> <?php print ucwords(_($_POST['action'])); ?></button>
	</div>

	<!-- result -->
	<div class="switchManagementEditResult"></div>
</div>