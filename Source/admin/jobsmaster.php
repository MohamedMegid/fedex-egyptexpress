<?php

// id
// image
// title_en
// title_ar
// position_en
// experience
// gender
// applied
// created

?>
<?php if ($jobs->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $jobs->TableCaption() ?></h4> -->
<table id="tbl_jobsmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($jobs->id->Visible) { // id ?>
		<tr id="r_id">
			<td><?php echo $jobs->id->FldCaption() ?></td>
			<td<?php echo $jobs->id->CellAttributes() ?>>
<span id="el_jobs_id" class="form-group">
<span<?php echo $jobs->id->ViewAttributes() ?>>
<?php echo $jobs->id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($jobs->image->Visible) { // image ?>
		<tr id="r_image">
			<td><?php echo $jobs->image->FldCaption() ?></td>
			<td<?php echo $jobs->image->CellAttributes() ?>>
<span id="el_jobs_image" class="form-group">
<span>
<?php echo ew_GetFileViewTag($jobs->image, $jobs->image->ListViewValue()) ?>
</span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($jobs->title_en->Visible) { // title_en ?>
		<tr id="r_title_en">
			<td><?php echo $jobs->title_en->FldCaption() ?></td>
			<td<?php echo $jobs->title_en->CellAttributes() ?>>
<span id="el_jobs_title_en" class="form-group">
<span<?php echo $jobs->title_en->ViewAttributes() ?>>
<?php echo $jobs->title_en->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($jobs->title_ar->Visible) { // title_ar ?>
		<tr id="r_title_ar">
			<td><?php echo $jobs->title_ar->FldCaption() ?></td>
			<td<?php echo $jobs->title_ar->CellAttributes() ?>>
<span id="el_jobs_title_ar" class="form-group">
<span<?php echo $jobs->title_ar->ViewAttributes() ?>>
<?php echo $jobs->title_ar->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($jobs->position_en->Visible) { // position_en ?>
		<tr id="r_position_en">
			<td><?php echo $jobs->position_en->FldCaption() ?></td>
			<td<?php echo $jobs->position_en->CellAttributes() ?>>
<span id="el_jobs_position_en" class="form-group">
<span<?php echo $jobs->position_en->ViewAttributes() ?>>
<?php echo $jobs->position_en->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($jobs->experience->Visible) { // experience ?>
		<tr id="r_experience">
			<td><?php echo $jobs->experience->FldCaption() ?></td>
			<td<?php echo $jobs->experience->CellAttributes() ?>>
<span id="el_jobs_experience" class="form-group">
<span<?php echo $jobs->experience->ViewAttributes() ?>>
<?php echo $jobs->experience->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($jobs->gender->Visible) { // gender ?>
		<tr id="r_gender">
			<td><?php echo $jobs->gender->FldCaption() ?></td>
			<td<?php echo $jobs->gender->CellAttributes() ?>>
<span id="el_jobs_gender" class="form-group">
<span<?php echo $jobs->gender->ViewAttributes() ?>>
<?php echo $jobs->gender->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($jobs->applied->Visible) { // applied ?>
		<tr id="r_applied">
			<td><?php echo $jobs->applied->FldCaption() ?></td>
			<td<?php echo $jobs->applied->CellAttributes() ?>>
<span id="el_jobs_applied" class="form-group">
<span<?php echo $jobs->applied->ViewAttributes() ?>>
<?php echo $jobs->applied->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($jobs->created->Visible) { // created ?>
		<tr id="r_created">
			<td><?php echo $jobs->created->FldCaption() ?></td>
			<td<?php echo $jobs->created->CellAttributes() ?>>
<span id="el_jobs_created" class="form-group">
<span<?php echo $jobs->created->ViewAttributes() ?>>
<?php echo $jobs->created->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
