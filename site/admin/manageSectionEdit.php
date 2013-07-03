<?php

/*
 * Print edit sections form
 *************************************************/

/* required functions */
require_once('../../functions/functions.php'); 

/* verify that user is admin */
checkAdmin();

/* verify post */
CheckReferrer();

/**
 * Fetch section info
 */
$section = getSectionDetailsById ($_POST['sectionId']);

?>



<!-- header -->
<div class="pHeader"><?php print ucwords(_($_POST['action'])); ?> <?php print _('Section'); ?></div>


<!-- content -->
<div class="pContent">

	<!-- form -->
	<form id="sectionEdit" name="sectionEdit">

		<!-- edit table -->
		<table class="table table-condensed table-noborder">
	
		<!-- section name -->
		<tr>
			<td><?php print _('Name'); ?></td>
			<td colspan="2">
				<input type="text" class='input-xlarge' name="name" value="<?php print $section['name']; ?>" size="30" <?php if ($_POST['action'] == "delete" ) { print ' readonly '; } ?> placeholder="<?php print _('Section name'); ?>">
				<!-- hidden -->
				<input type="hidden" name="action" 	value="<?php print $_POST['action']; ?>">
				<input type="hidden" name="id" 		value="<?php print $_POST['sectionId']; ?>">
			</td>
		</tr>

		<!-- description -->
		<tr>
			<td><?php print _('Description'); ?></td>
			<td colspan="2">
				<input type="text" class='input-xlarge' name="description" value="<?php print $section['description']; ?>" size="30" <?php if ($_POST['action'] == "delete") { print " readonly ";}?> placeholder="<?php print _('Section description'); ?>">
			</td>
		</tr>

		<!-- Strict Mode -->
		<tr>
			<td><?php print _('Strict Mode'); ?></td>
			<td colspan="2">
				<select name="strictMode" class="input-small" <?php if($_POST['action']=="delete") print 'disabled="disabled"'; ?>>
					<option value="1"><?php print _('Yes'); ?></option>
					<option value="0" <?php if($section['strictMode'] == "0") print "selected='selected'"; ?>><?php print _('No'); ?></option>
				</select>
				<span class="help-inline"><?php print _('No disables overlapping subnet checks. Subnets can be nested/created randomly. Anarchy.'); ?></span>
			</td>
		</tr>

		<!-- Subnet ordering -->
		<tr>
			<td class="title"><?php print _('Subnet ordering'); ?></td>
			<td colspan="2">
				<select name="subnetOrdering" style="width:auto;">
					<?php
					$opts = array(
						"default"			=> _("Default"),
						"subnet,asc"		=> _("Subnet, ascending"),
						"subnet,desc"		=> _("Subnet, descending"),
						"description,asc"	=> _("Description, ascending"),
						"description,desc"	=> _("Description, descending"),
					);
					
					foreach($opts as $key=>$line) {
						if($section['subnetOrdering'] == $key) 	{ print "<option value='$key' selected>$line</option>"; }
						else 									{ print "<option value='$key'>$line</option>"; }
					}
					
					?>
				</select>
				<span class="help-inline"><?php print _('How to order display of subnets'); ?></span>
			</td>
		</tr>

		<tr>
			<td colspan="3">
				<hr>
			</td>
		</tr>		
		<!-- permissions -->
		<?php
		if(strlen($section['permissions'])>1) {
			$permissions = parseSectionPermissions($section['permissions']);
		}
		else {
			$permissions = "";
		}
		# print for each group
		$groups = getAllGroups();
		$m = 0;
		
		foreach($groups as $g) {
			# structure
			print "<tr>";
			# title
			if($m == 0) { print "<td>"._('Permissions')."</td>"; }
			else		{ print "<td></td>"; }			
			
			# name
			print "<td>$g[g_name]</td>";
				
			# line
			print "<td>";			
			print "<label class='checkbox inline noborder'>";			

			print "	<input type='radio' name='group$g[g_id]' value='0' checked> na";
			if($permissions[$g['g_id']] == "1")	{ print " <input type='radio' name='group$g[g_id]' value='1' checked> ro"; }			
			else								{ print " <input type='radio' name='group$g[g_id]' value='1'> ro"; }	
			if($permissions[$g['g_id']] == "2")	{ print " <input type='radio' name='group$g[g_id]' value='2' checked> rw"; }			
			else								{ print " <input type='radio' name='group$g[g_id]' value='2'> rw"; }			
			if($permissions[$g['g_id']] == "3")	{ print " <input type='radio' name='group$g[g_id]' value='3' checked> rwa"; }			
			else								{ print " <input type='radio' name='group$g[g_id]' value='3'> rwa"; }	
			print "</label>";
			print "</td>";
			
			print "</tr>";			
			
			$m++;
		}
		?>
		
		<?php 
		if($_POST['action'] == "edit") { ?>
		<!-- Apply to subnets -->
		<tr>
			<td colspan="3">
				<hr>
			</td>
		</tr>
		<tr>
			<td><?php print _('Delegate'); ?></td>
			<td colspan="2">
				<input type="checkbox" name="delegate" value="1" style="margin-top:0px;"><span class="help help-inline"><?php print _('Check to delegate permissions to all subnets in section'); ?></span>
			</td>
		</tr>
		<?php } ?>
		
		</table>	<!-- end table -->
	</form>		<!-- end form -->
	
	<!-- delete warning -->
	<?php
	if ($_POST['action'] == "delete") {
		print '<div class="alert alert-warn"><b>'._('Warning').'!</b><br>'._('Deleting Section will delete all belonging subnets and IP addresses').'!</div>' . "\n";
	}
	?>
</div>


<!-- footer -->
<div class="pFooter">
	<button class="btn btn-small hidePopups"><?php print _('Cancel'); ?></button>
	<button class="btn btn-small <?php if($_POST['action']=="delete") { print "btn-danger";} else { print "btn-success"; } ?>" id="editSectionSubmit"><i class="icon-white <?php if($_POST['action']=="add") { print "icon-plus"; } else if ($_POST['action']=="delete") { print "icon-trash"; } else { print "icon-ok"; } ?>"></i> <?php print ucwords(_($_POST['action'])); ?></button>

	<!-- result holder -->
	<div class="sectionEditResult"></div>
</div>	
		