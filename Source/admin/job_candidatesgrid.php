<?php include_once $EW_RELATIVE_PATH . "administratorinfo.php" ?>
<?php

// Create page object
if (!isset($job_candidates_grid)) $job_candidates_grid = new cjob_candidates_grid();

// Page init
$job_candidates_grid->Page_Init();

// Page main
$job_candidates_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$job_candidates_grid->Page_Render();
?>
<?php if ($job_candidates->Export == "") { ?>
<script type="text/javascript">

// Page object
var job_candidates_grid = new ew_Page("job_candidates_grid");
job_candidates_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = job_candidates_grid.PageID; // For backward compatibility

// Form object
var fjob_candidatesgrid = new ew_Form("fjob_candidatesgrid");
fjob_candidatesgrid.FormKeyCountName = '<?php echo $job_candidates_grid->FormKeyCountName ?>';

// Validate form
fjob_candidatesgrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_job_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $job_candidates->job_id->FldCaption(), $job_candidates->job_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $job_candidates->name->FldCaption(), $job_candidates->name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $job_candidates->_email->FldCaption(), $job_candidates->_email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_mobile");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $job_candidates->mobile->FldCaption(), $job_candidates->mobile->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_cv");
			elm = this.GetElements("fn_x" + infix + "_cv");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $job_candidates->cv->FldCaption(), $job_candidates->cv->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_applied_date");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($job_candidates->applied_date->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fjob_candidatesgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "job_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "name", false)) return false;
	if (ew_ValueChanged(fobj, infix, "_email", false)) return false;
	if (ew_ValueChanged(fobj, infix, "mobile", false)) return false;
	if (ew_ValueChanged(fobj, infix, "cv", false)) return false;
	if (ew_ValueChanged(fobj, infix, "applied_date", false)) return false;
	return true;
}

// Form_CustomValidate event
fjob_candidatesgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fjob_candidatesgrid.ValidateRequired = true;
<?php } else { ?>
fjob_candidatesgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fjob_candidatesgrid.Lists["x_job_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_title_en","x_title_ar","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($job_candidates->CurrentAction == "gridadd") {
	if ($job_candidates->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$job_candidates_grid->TotalRecs = $job_candidates->SelectRecordCount();
			$job_candidates_grid->Recordset = $job_candidates_grid->LoadRecordset($job_candidates_grid->StartRec-1, $job_candidates_grid->DisplayRecs);
		} else {
			if ($job_candidates_grid->Recordset = $job_candidates_grid->LoadRecordset())
				$job_candidates_grid->TotalRecs = $job_candidates_grid->Recordset->RecordCount();
		}
		$job_candidates_grid->StartRec = 1;
		$job_candidates_grid->DisplayRecs = $job_candidates_grid->TotalRecs;
	} else {
		$job_candidates->CurrentFilter = "0=1";
		$job_candidates_grid->StartRec = 1;
		$job_candidates_grid->DisplayRecs = $job_candidates->GridAddRowCount;
	}
	$job_candidates_grid->TotalRecs = $job_candidates_grid->DisplayRecs;
	$job_candidates_grid->StopRec = $job_candidates_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$job_candidates_grid->TotalRecs = $job_candidates->SelectRecordCount();
	} else {
		if ($job_candidates_grid->Recordset = $job_candidates_grid->LoadRecordset())
			$job_candidates_grid->TotalRecs = $job_candidates_grid->Recordset->RecordCount();
	}
	$job_candidates_grid->StartRec = 1;
	$job_candidates_grid->DisplayRecs = $job_candidates_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$job_candidates_grid->Recordset = $job_candidates_grid->LoadRecordset($job_candidates_grid->StartRec-1, $job_candidates_grid->DisplayRecs);

	// Set no record found message
	if ($job_candidates->CurrentAction == "" && $job_candidates_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$job_candidates_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($job_candidates_grid->SearchWhere == "0=101")
			$job_candidates_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$job_candidates_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$job_candidates_grid->RenderOtherOptions();
?>
<?php $job_candidates_grid->ShowPageHeader(); ?>
<?php
$job_candidates_grid->ShowMessage();
?>
<?php if ($job_candidates_grid->TotalRecs > 0 || $job_candidates->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fjob_candidatesgrid" class="ewForm form-inline">
<?php if ($job_candidates_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel">
<?php
	foreach ($job_candidates_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_job_candidates" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_job_candidatesgrid" class="table ewTable">
<?php echo $job_candidates->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$job_candidates_grid->RenderListOptions();

// Render list options (header, left)
$job_candidates_grid->ListOptions->Render("header", "left");
?>
<?php if ($job_candidates->id->Visible) { // id ?>
	<?php if ($job_candidates->SortUrl($job_candidates->id) == "") { ?>
		<th data-name="id"><div id="elh_job_candidates_id" class="job_candidates_id"><div class="ewTableHeaderCaption"><?php echo $job_candidates->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div><div id="elh_job_candidates_id" class="job_candidates_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $job_candidates->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($job_candidates->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($job_candidates->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($job_candidates->job_id->Visible) { // job_id ?>
	<?php if ($job_candidates->SortUrl($job_candidates->job_id) == "") { ?>
		<th data-name="job_id"><div id="elh_job_candidates_job_id" class="job_candidates_job_id"><div class="ewTableHeaderCaption"><?php echo $job_candidates->job_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="job_id"><div><div id="elh_job_candidates_job_id" class="job_candidates_job_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $job_candidates->job_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($job_candidates->job_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($job_candidates->job_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($job_candidates->name->Visible) { // name ?>
	<?php if ($job_candidates->SortUrl($job_candidates->name) == "") { ?>
		<th data-name="name"><div id="elh_job_candidates_name" class="job_candidates_name"><div class="ewTableHeaderCaption"><?php echo $job_candidates->name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="name"><div><div id="elh_job_candidates_name" class="job_candidates_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $job_candidates->name->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($job_candidates->name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($job_candidates->name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($job_candidates->_email->Visible) { // email ?>
	<?php if ($job_candidates->SortUrl($job_candidates->_email) == "") { ?>
		<th data-name="_email"><div id="elh_job_candidates__email" class="job_candidates__email"><div class="ewTableHeaderCaption"><?php echo $job_candidates->_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_email"><div><div id="elh_job_candidates__email" class="job_candidates__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $job_candidates->_email->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($job_candidates->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($job_candidates->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($job_candidates->mobile->Visible) { // mobile ?>
	<?php if ($job_candidates->SortUrl($job_candidates->mobile) == "") { ?>
		<th data-name="mobile"><div id="elh_job_candidates_mobile" class="job_candidates_mobile"><div class="ewTableHeaderCaption"><?php echo $job_candidates->mobile->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mobile"><div><div id="elh_job_candidates_mobile" class="job_candidates_mobile">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $job_candidates->mobile->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($job_candidates->mobile->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($job_candidates->mobile->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($job_candidates->cv->Visible) { // cv ?>
	<?php if ($job_candidates->SortUrl($job_candidates->cv) == "") { ?>
		<th data-name="cv"><div id="elh_job_candidates_cv" class="job_candidates_cv"><div class="ewTableHeaderCaption"><?php echo $job_candidates->cv->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="cv"><div><div id="elh_job_candidates_cv" class="job_candidates_cv">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $job_candidates->cv->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($job_candidates->cv->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($job_candidates->cv->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($job_candidates->applied_date->Visible) { // applied_date ?>
	<?php if ($job_candidates->SortUrl($job_candidates->applied_date) == "") { ?>
		<th data-name="applied_date"><div id="elh_job_candidates_applied_date" class="job_candidates_applied_date"><div class="ewTableHeaderCaption"><?php echo $job_candidates->applied_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="applied_date"><div><div id="elh_job_candidates_applied_date" class="job_candidates_applied_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $job_candidates->applied_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($job_candidates->applied_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($job_candidates->applied_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$job_candidates_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$job_candidates_grid->StartRec = 1;
$job_candidates_grid->StopRec = $job_candidates_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($job_candidates_grid->FormKeyCountName) && ($job_candidates->CurrentAction == "gridadd" || $job_candidates->CurrentAction == "gridedit" || $job_candidates->CurrentAction == "F")) {
		$job_candidates_grid->KeyCount = $objForm->GetValue($job_candidates_grid->FormKeyCountName);
		$job_candidates_grid->StopRec = $job_candidates_grid->StartRec + $job_candidates_grid->KeyCount - 1;
	}
}
$job_candidates_grid->RecCnt = $job_candidates_grid->StartRec - 1;
if ($job_candidates_grid->Recordset && !$job_candidates_grid->Recordset->EOF) {
	$job_candidates_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $job_candidates_grid->StartRec > 1)
		$job_candidates_grid->Recordset->Move($job_candidates_grid->StartRec - 1);
} elseif (!$job_candidates->AllowAddDeleteRow && $job_candidates_grid->StopRec == 0) {
	$job_candidates_grid->StopRec = $job_candidates->GridAddRowCount;
}

// Initialize aggregate
$job_candidates->RowType = EW_ROWTYPE_AGGREGATEINIT;
$job_candidates->ResetAttrs();
$job_candidates_grid->RenderRow();
if ($job_candidates->CurrentAction == "gridadd")
	$job_candidates_grid->RowIndex = 0;
if ($job_candidates->CurrentAction == "gridedit")
	$job_candidates_grid->RowIndex = 0;
while ($job_candidates_grid->RecCnt < $job_candidates_grid->StopRec) {
	$job_candidates_grid->RecCnt++;
	if (intval($job_candidates_grid->RecCnt) >= intval($job_candidates_grid->StartRec)) {
		$job_candidates_grid->RowCnt++;
		if ($job_candidates->CurrentAction == "gridadd" || $job_candidates->CurrentAction == "gridedit" || $job_candidates->CurrentAction == "F") {
			$job_candidates_grid->RowIndex++;
			$objForm->Index = $job_candidates_grid->RowIndex;
			if ($objForm->HasValue($job_candidates_grid->FormActionName))
				$job_candidates_grid->RowAction = strval($objForm->GetValue($job_candidates_grid->FormActionName));
			elseif ($job_candidates->CurrentAction == "gridadd")
				$job_candidates_grid->RowAction = "insert";
			else
				$job_candidates_grid->RowAction = "";
		}

		// Set up key count
		$job_candidates_grid->KeyCount = $job_candidates_grid->RowIndex;

		// Init row class and style
		$job_candidates->ResetAttrs();
		$job_candidates->CssClass = "";
		if ($job_candidates->CurrentAction == "gridadd") {
			if ($job_candidates->CurrentMode == "copy") {
				$job_candidates_grid->LoadRowValues($job_candidates_grid->Recordset); // Load row values
				$job_candidates_grid->SetRecordKey($job_candidates_grid->RowOldKey, $job_candidates_grid->Recordset); // Set old record key
			} else {
				$job_candidates_grid->LoadDefaultValues(); // Load default values
				$job_candidates_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$job_candidates_grid->LoadRowValues($job_candidates_grid->Recordset); // Load row values
		}
		$job_candidates->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($job_candidates->CurrentAction == "gridadd") // Grid add
			$job_candidates->RowType = EW_ROWTYPE_ADD; // Render add
		if ($job_candidates->CurrentAction == "gridadd" && $job_candidates->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$job_candidates_grid->RestoreCurrentRowFormValues($job_candidates_grid->RowIndex); // Restore form values
		if ($job_candidates->CurrentAction == "gridedit") { // Grid edit
			if ($job_candidates->EventCancelled) {
				$job_candidates_grid->RestoreCurrentRowFormValues($job_candidates_grid->RowIndex); // Restore form values
			}
			if ($job_candidates_grid->RowAction == "insert")
				$job_candidates->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$job_candidates->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($job_candidates->CurrentAction == "gridedit" && ($job_candidates->RowType == EW_ROWTYPE_EDIT || $job_candidates->RowType == EW_ROWTYPE_ADD) && $job_candidates->EventCancelled) // Update failed
			$job_candidates_grid->RestoreCurrentRowFormValues($job_candidates_grid->RowIndex); // Restore form values
		if ($job_candidates->RowType == EW_ROWTYPE_EDIT) // Edit row
			$job_candidates_grid->EditRowCnt++;
		if ($job_candidates->CurrentAction == "F") // Confirm row
			$job_candidates_grid->RestoreCurrentRowFormValues($job_candidates_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$job_candidates->RowAttrs = array_merge($job_candidates->RowAttrs, array('data-rowindex'=>$job_candidates_grid->RowCnt, 'id'=>'r' . $job_candidates_grid->RowCnt . '_job_candidates', 'data-rowtype'=>$job_candidates->RowType));

		// Render row
		$job_candidates_grid->RenderRow();

		// Render list options
		$job_candidates_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($job_candidates_grid->RowAction <> "delete" && $job_candidates_grid->RowAction <> "insertdelete" && !($job_candidates_grid->RowAction == "insert" && $job_candidates->CurrentAction == "F" && $job_candidates_grid->EmptyRow())) {
?>
	<tr<?php echo $job_candidates->RowAttributes() ?>>
<?php

// Render list options (body, left)
$job_candidates_grid->ListOptions->Render("body", "left", $job_candidates_grid->RowCnt);
?>
	<?php if ($job_candidates->id->Visible) { // id ?>
		<td data-name="id"<?php echo $job_candidates->id->CellAttributes() ?>>
<?php if ($job_candidates->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_id" name="o<?php echo $job_candidates_grid->RowIndex ?>_id" id="o<?php echo $job_candidates_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($job_candidates->id->OldValue) ?>">
<?php } ?>
<?php if ($job_candidates->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $job_candidates_grid->RowCnt ?>_job_candidates_id" class="form-group job_candidates_id">
<span<?php echo $job_candidates->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $job_candidates->id->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_id" name="x<?php echo $job_candidates_grid->RowIndex ?>_id" id="x<?php echo $job_candidates_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($job_candidates->id->CurrentValue) ?>">
<?php } ?>
<?php if ($job_candidates->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $job_candidates->id->ViewAttributes() ?>>
<?php echo $job_candidates->id->ListViewValue() ?></span>
<input type="hidden" data-field="x_id" name="x<?php echo $job_candidates_grid->RowIndex ?>_id" id="x<?php echo $job_candidates_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($job_candidates->id->FormValue) ?>">
<input type="hidden" data-field="x_id" name="o<?php echo $job_candidates_grid->RowIndex ?>_id" id="o<?php echo $job_candidates_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($job_candidates->id->OldValue) ?>">
<?php } ?>
<a id="<?php echo $job_candidates_grid->PageObjName . "_row_" . $job_candidates_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($job_candidates->job_id->Visible) { // job_id ?>
		<td data-name="job_id"<?php echo $job_candidates->job_id->CellAttributes() ?>>
<?php if ($job_candidates->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($job_candidates->job_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $job_candidates_grid->RowCnt ?>_job_candidates_job_id" class="form-group job_candidates_job_id">
<span<?php echo $job_candidates->job_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $job_candidates->job_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $job_candidates_grid->RowIndex ?>_job_id" name="x<?php echo $job_candidates_grid->RowIndex ?>_job_id" value="<?php echo ew_HtmlEncode($job_candidates->job_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $job_candidates_grid->RowCnt ?>_job_candidates_job_id" class="form-group job_candidates_job_id">
<select data-field="x_job_id" id="x<?php echo $job_candidates_grid->RowIndex ?>_job_id" name="x<?php echo $job_candidates_grid->RowIndex ?>_job_id"<?php echo $job_candidates->job_id->EditAttributes() ?>>
<?php
if (is_array($job_candidates->job_id->EditValue)) {
	$arwrk = $job_candidates->job_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($job_candidates->job_id->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$job_candidates->job_id) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $job_candidates->job_id->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT DISTINCT `id`, `title_en` AS `DispFld`, `title_ar` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `jobs`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $job_candidates->Lookup_Selecting($job_candidates->job_id, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `title_en` ASC";
?>
<input type="hidden" name="s_x<?php echo $job_candidates_grid->RowIndex ?>_job_id" id="s_x<?php echo $job_candidates_grid->RowIndex ?>_job_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&amp;t0=19">
</span>
<?php } ?>
<input type="hidden" data-field="x_job_id" name="o<?php echo $job_candidates_grid->RowIndex ?>_job_id" id="o<?php echo $job_candidates_grid->RowIndex ?>_job_id" value="<?php echo ew_HtmlEncode($job_candidates->job_id->OldValue) ?>">
<?php } ?>
<?php if ($job_candidates->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($job_candidates->job_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $job_candidates_grid->RowCnt ?>_job_candidates_job_id" class="form-group job_candidates_job_id">
<span<?php echo $job_candidates->job_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $job_candidates->job_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $job_candidates_grid->RowIndex ?>_job_id" name="x<?php echo $job_candidates_grid->RowIndex ?>_job_id" value="<?php echo ew_HtmlEncode($job_candidates->job_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $job_candidates_grid->RowCnt ?>_job_candidates_job_id" class="form-group job_candidates_job_id">
<select data-field="x_job_id" id="x<?php echo $job_candidates_grid->RowIndex ?>_job_id" name="x<?php echo $job_candidates_grid->RowIndex ?>_job_id"<?php echo $job_candidates->job_id->EditAttributes() ?>>
<?php
if (is_array($job_candidates->job_id->EditValue)) {
	$arwrk = $job_candidates->job_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($job_candidates->job_id->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$job_candidates->job_id) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $job_candidates->job_id->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT DISTINCT `id`, `title_en` AS `DispFld`, `title_ar` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `jobs`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $job_candidates->Lookup_Selecting($job_candidates->job_id, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `title_en` ASC";
?>
<input type="hidden" name="s_x<?php echo $job_candidates_grid->RowIndex ?>_job_id" id="s_x<?php echo $job_candidates_grid->RowIndex ?>_job_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&amp;t0=19">
</span>
<?php } ?>
<?php } ?>
<?php if ($job_candidates->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $job_candidates->job_id->ViewAttributes() ?>>
<?php echo $job_candidates->job_id->ListViewValue() ?></span>
<input type="hidden" data-field="x_job_id" name="x<?php echo $job_candidates_grid->RowIndex ?>_job_id" id="x<?php echo $job_candidates_grid->RowIndex ?>_job_id" value="<?php echo ew_HtmlEncode($job_candidates->job_id->FormValue) ?>">
<input type="hidden" data-field="x_job_id" name="o<?php echo $job_candidates_grid->RowIndex ?>_job_id" id="o<?php echo $job_candidates_grid->RowIndex ?>_job_id" value="<?php echo ew_HtmlEncode($job_candidates->job_id->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($job_candidates->name->Visible) { // name ?>
		<td data-name="name"<?php echo $job_candidates->name->CellAttributes() ?>>
<?php if ($job_candidates->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $job_candidates_grid->RowCnt ?>_job_candidates_name" class="form-group job_candidates_name">
<input type="text" data-field="x_name" name="x<?php echo $job_candidates_grid->RowIndex ?>_name" id="x<?php echo $job_candidates_grid->RowIndex ?>_name" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($job_candidates->name->PlaceHolder) ?>" value="<?php echo $job_candidates->name->EditValue ?>"<?php echo $job_candidates->name->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_name" name="o<?php echo $job_candidates_grid->RowIndex ?>_name" id="o<?php echo $job_candidates_grid->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($job_candidates->name->OldValue) ?>">
<?php } ?>
<?php if ($job_candidates->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $job_candidates_grid->RowCnt ?>_job_candidates_name" class="form-group job_candidates_name">
<input type="text" data-field="x_name" name="x<?php echo $job_candidates_grid->RowIndex ?>_name" id="x<?php echo $job_candidates_grid->RowIndex ?>_name" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($job_candidates->name->PlaceHolder) ?>" value="<?php echo $job_candidates->name->EditValue ?>"<?php echo $job_candidates->name->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($job_candidates->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $job_candidates->name->ViewAttributes() ?>>
<?php echo $job_candidates->name->ListViewValue() ?></span>
<input type="hidden" data-field="x_name" name="x<?php echo $job_candidates_grid->RowIndex ?>_name" id="x<?php echo $job_candidates_grid->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($job_candidates->name->FormValue) ?>">
<input type="hidden" data-field="x_name" name="o<?php echo $job_candidates_grid->RowIndex ?>_name" id="o<?php echo $job_candidates_grid->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($job_candidates->name->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($job_candidates->_email->Visible) { // email ?>
		<td data-name="_email"<?php echo $job_candidates->_email->CellAttributes() ?>>
<?php if ($job_candidates->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $job_candidates_grid->RowCnt ?>_job_candidates__email" class="form-group job_candidates__email">
<input type="text" data-field="x__email" name="x<?php echo $job_candidates_grid->RowIndex ?>__email" id="x<?php echo $job_candidates_grid->RowIndex ?>__email" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($job_candidates->_email->PlaceHolder) ?>" value="<?php echo $job_candidates->_email->EditValue ?>"<?php echo $job_candidates->_email->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x__email" name="o<?php echo $job_candidates_grid->RowIndex ?>__email" id="o<?php echo $job_candidates_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($job_candidates->_email->OldValue) ?>">
<?php } ?>
<?php if ($job_candidates->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $job_candidates_grid->RowCnt ?>_job_candidates__email" class="form-group job_candidates__email">
<input type="text" data-field="x__email" name="x<?php echo $job_candidates_grid->RowIndex ?>__email" id="x<?php echo $job_candidates_grid->RowIndex ?>__email" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($job_candidates->_email->PlaceHolder) ?>" value="<?php echo $job_candidates->_email->EditValue ?>"<?php echo $job_candidates->_email->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($job_candidates->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $job_candidates->_email->ViewAttributes() ?>>
<?php echo $job_candidates->_email->ListViewValue() ?></span>
<input type="hidden" data-field="x__email" name="x<?php echo $job_candidates_grid->RowIndex ?>__email" id="x<?php echo $job_candidates_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($job_candidates->_email->FormValue) ?>">
<input type="hidden" data-field="x__email" name="o<?php echo $job_candidates_grid->RowIndex ?>__email" id="o<?php echo $job_candidates_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($job_candidates->_email->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($job_candidates->mobile->Visible) { // mobile ?>
		<td data-name="mobile"<?php echo $job_candidates->mobile->CellAttributes() ?>>
<?php if ($job_candidates->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $job_candidates_grid->RowCnt ?>_job_candidates_mobile" class="form-group job_candidates_mobile">
<input type="text" data-field="x_mobile" name="x<?php echo $job_candidates_grid->RowIndex ?>_mobile" id="x<?php echo $job_candidates_grid->RowIndex ?>_mobile" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($job_candidates->mobile->PlaceHolder) ?>" value="<?php echo $job_candidates->mobile->EditValue ?>"<?php echo $job_candidates->mobile->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_mobile" name="o<?php echo $job_candidates_grid->RowIndex ?>_mobile" id="o<?php echo $job_candidates_grid->RowIndex ?>_mobile" value="<?php echo ew_HtmlEncode($job_candidates->mobile->OldValue) ?>">
<?php } ?>
<?php if ($job_candidates->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $job_candidates_grid->RowCnt ?>_job_candidates_mobile" class="form-group job_candidates_mobile">
<input type="text" data-field="x_mobile" name="x<?php echo $job_candidates_grid->RowIndex ?>_mobile" id="x<?php echo $job_candidates_grid->RowIndex ?>_mobile" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($job_candidates->mobile->PlaceHolder) ?>" value="<?php echo $job_candidates->mobile->EditValue ?>"<?php echo $job_candidates->mobile->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($job_candidates->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $job_candidates->mobile->ViewAttributes() ?>>
<?php echo $job_candidates->mobile->ListViewValue() ?></span>
<input type="hidden" data-field="x_mobile" name="x<?php echo $job_candidates_grid->RowIndex ?>_mobile" id="x<?php echo $job_candidates_grid->RowIndex ?>_mobile" value="<?php echo ew_HtmlEncode($job_candidates->mobile->FormValue) ?>">
<input type="hidden" data-field="x_mobile" name="o<?php echo $job_candidates_grid->RowIndex ?>_mobile" id="o<?php echo $job_candidates_grid->RowIndex ?>_mobile" value="<?php echo ew_HtmlEncode($job_candidates->mobile->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($job_candidates->cv->Visible) { // cv ?>
		<td data-name="cv"<?php echo $job_candidates->cv->CellAttributes() ?>>
<?php if ($job_candidates_grid->RowAction == "insert") { // Add record ?>
<span id="el<?php echo $job_candidates_grid->RowCnt ?>_job_candidates_cv" class="form-group job_candidates_cv">
<div id="fd_x<?php echo $job_candidates_grid->RowIndex ?>_cv">
<span title="<?php echo $job_candidates->cv->FldTitle() ? $job_candidates->cv->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($job_candidates->cv->ReadOnly || $job_candidates->cv->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_cv" name="x<?php echo $job_candidates_grid->RowIndex ?>_cv" id="x<?php echo $job_candidates_grid->RowIndex ?>_cv">
</span>
<input type="hidden" name="fn_x<?php echo $job_candidates_grid->RowIndex ?>_cv" id= "fn_x<?php echo $job_candidates_grid->RowIndex ?>_cv" value="<?php echo $job_candidates->cv->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $job_candidates_grid->RowIndex ?>_cv" id= "fa_x<?php echo $job_candidates_grid->RowIndex ?>_cv" value="0">
<input type="hidden" name="fs_x<?php echo $job_candidates_grid->RowIndex ?>_cv" id= "fs_x<?php echo $job_candidates_grid->RowIndex ?>_cv" value="255">
<input type="hidden" name="fx_x<?php echo $job_candidates_grid->RowIndex ?>_cv" id= "fx_x<?php echo $job_candidates_grid->RowIndex ?>_cv" value="<?php echo $job_candidates->cv->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $job_candidates_grid->RowIndex ?>_cv" id= "fm_x<?php echo $job_candidates_grid->RowIndex ?>_cv" value="<?php echo $job_candidates->cv->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $job_candidates_grid->RowIndex ?>_cv" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-field="x_cv" name="o<?php echo $job_candidates_grid->RowIndex ?>_cv" id="o<?php echo $job_candidates_grid->RowIndex ?>_cv" value="<?php echo ew_HtmlEncode($job_candidates->cv->OldValue) ?>">
<?php } elseif ($job_candidates->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $job_candidates->cv->ViewAttributes() ?>>
<?php if ($job_candidates->cv->LinkAttributes() <> "") { ?>
<?php if (!empty($job_candidates->cv->Upload->DbValue)) { ?>
<a<?php echo $job_candidates->cv->LinkAttributes() ?>><?php echo $job_candidates->cv->ListViewValue() ?></a>
<?php } elseif (!in_array($job_candidates->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($job_candidates->cv->Upload->DbValue)) { ?>
<?php echo $job_candidates->cv->ListViewValue() ?>
<?php } elseif (!in_array($job_candidates->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
<?php } else  { // Edit record ?>
<span id="el<?php echo $job_candidates_grid->RowCnt ?>_job_candidates_cv" class="form-group job_candidates_cv">
<div id="fd_x<?php echo $job_candidates_grid->RowIndex ?>_cv">
<span title="<?php echo $job_candidates->cv->FldTitle() ? $job_candidates->cv->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($job_candidates->cv->ReadOnly || $job_candidates->cv->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_cv" name="x<?php echo $job_candidates_grid->RowIndex ?>_cv" id="x<?php echo $job_candidates_grid->RowIndex ?>_cv">
</span>
<input type="hidden" name="fn_x<?php echo $job_candidates_grid->RowIndex ?>_cv" id= "fn_x<?php echo $job_candidates_grid->RowIndex ?>_cv" value="<?php echo $job_candidates->cv->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $job_candidates_grid->RowIndex ?>_cv"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $job_candidates_grid->RowIndex ?>_cv" id= "fa_x<?php echo $job_candidates_grid->RowIndex ?>_cv" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $job_candidates_grid->RowIndex ?>_cv" id= "fa_x<?php echo $job_candidates_grid->RowIndex ?>_cv" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $job_candidates_grid->RowIndex ?>_cv" id= "fs_x<?php echo $job_candidates_grid->RowIndex ?>_cv" value="255">
<input type="hidden" name="fx_x<?php echo $job_candidates_grid->RowIndex ?>_cv" id= "fx_x<?php echo $job_candidates_grid->RowIndex ?>_cv" value="<?php echo $job_candidates->cv->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $job_candidates_grid->RowIndex ?>_cv" id= "fm_x<?php echo $job_candidates_grid->RowIndex ?>_cv" value="<?php echo $job_candidates->cv->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $job_candidates_grid->RowIndex ?>_cv" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($job_candidates->applied_date->Visible) { // applied_date ?>
		<td data-name="applied_date"<?php echo $job_candidates->applied_date->CellAttributes() ?>>
<?php if ($job_candidates->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $job_candidates_grid->RowCnt ?>_job_candidates_applied_date" class="form-group job_candidates_applied_date">
<input type="text" data-field="x_applied_date" name="x<?php echo $job_candidates_grid->RowIndex ?>_applied_date" id="x<?php echo $job_candidates_grid->RowIndex ?>_applied_date" placeholder="<?php echo ew_HtmlEncode($job_candidates->applied_date->PlaceHolder) ?>" value="<?php echo $job_candidates->applied_date->EditValue ?>"<?php echo $job_candidates->applied_date->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_applied_date" name="o<?php echo $job_candidates_grid->RowIndex ?>_applied_date" id="o<?php echo $job_candidates_grid->RowIndex ?>_applied_date" value="<?php echo ew_HtmlEncode($job_candidates->applied_date->OldValue) ?>">
<?php } ?>
<?php if ($job_candidates->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $job_candidates_grid->RowCnt ?>_job_candidates_applied_date" class="form-group job_candidates_applied_date">
<input type="text" data-field="x_applied_date" name="x<?php echo $job_candidates_grid->RowIndex ?>_applied_date" id="x<?php echo $job_candidates_grid->RowIndex ?>_applied_date" placeholder="<?php echo ew_HtmlEncode($job_candidates->applied_date->PlaceHolder) ?>" value="<?php echo $job_candidates->applied_date->EditValue ?>"<?php echo $job_candidates->applied_date->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($job_candidates->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $job_candidates->applied_date->ViewAttributes() ?>>
<?php echo $job_candidates->applied_date->ListViewValue() ?></span>
<input type="hidden" data-field="x_applied_date" name="x<?php echo $job_candidates_grid->RowIndex ?>_applied_date" id="x<?php echo $job_candidates_grid->RowIndex ?>_applied_date" value="<?php echo ew_HtmlEncode($job_candidates->applied_date->FormValue) ?>">
<input type="hidden" data-field="x_applied_date" name="o<?php echo $job_candidates_grid->RowIndex ?>_applied_date" id="o<?php echo $job_candidates_grid->RowIndex ?>_applied_date" value="<?php echo ew_HtmlEncode($job_candidates->applied_date->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$job_candidates_grid->ListOptions->Render("body", "right", $job_candidates_grid->RowCnt);
?>
	</tr>
<?php if ($job_candidates->RowType == EW_ROWTYPE_ADD || $job_candidates->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fjob_candidatesgrid.UpdateOpts(<?php echo $job_candidates_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($job_candidates->CurrentAction <> "gridadd" || $job_candidates->CurrentMode == "copy")
		if (!$job_candidates_grid->Recordset->EOF) $job_candidates_grid->Recordset->MoveNext();
}
?>
<?php
	if ($job_candidates->CurrentMode == "add" || $job_candidates->CurrentMode == "copy" || $job_candidates->CurrentMode == "edit") {
		$job_candidates_grid->RowIndex = '$rowindex$';
		$job_candidates_grid->LoadDefaultValues();

		// Set row properties
		$job_candidates->ResetAttrs();
		$job_candidates->RowAttrs = array_merge($job_candidates->RowAttrs, array('data-rowindex'=>$job_candidates_grid->RowIndex, 'id'=>'r0_job_candidates', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($job_candidates->RowAttrs["class"], "ewTemplate");
		$job_candidates->RowType = EW_ROWTYPE_ADD;

		// Render row
		$job_candidates_grid->RenderRow();

		// Render list options
		$job_candidates_grid->RenderListOptions();
		$job_candidates_grid->StartRowCnt = 0;
?>
	<tr<?php echo $job_candidates->RowAttributes() ?>>
<?php

// Render list options (body, left)
$job_candidates_grid->ListOptions->Render("body", "left", $job_candidates_grid->RowIndex);
?>
	<?php if ($job_candidates->id->Visible) { // id ?>
		<td>
<?php if ($job_candidates->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_job_candidates_id" class="form-group job_candidates_id">
<span<?php echo $job_candidates->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $job_candidates->id->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id" name="x<?php echo $job_candidates_grid->RowIndex ?>_id" id="x<?php echo $job_candidates_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($job_candidates->id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id" name="o<?php echo $job_candidates_grid->RowIndex ?>_id" id="o<?php echo $job_candidates_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($job_candidates->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($job_candidates->job_id->Visible) { // job_id ?>
		<td>
<?php if ($job_candidates->CurrentAction <> "F") { ?>
<?php if ($job_candidates->job_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_job_candidates_job_id" class="form-group job_candidates_job_id">
<span<?php echo $job_candidates->job_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $job_candidates->job_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $job_candidates_grid->RowIndex ?>_job_id" name="x<?php echo $job_candidates_grid->RowIndex ?>_job_id" value="<?php echo ew_HtmlEncode($job_candidates->job_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_job_candidates_job_id" class="form-group job_candidates_job_id">
<select data-field="x_job_id" id="x<?php echo $job_candidates_grid->RowIndex ?>_job_id" name="x<?php echo $job_candidates_grid->RowIndex ?>_job_id"<?php echo $job_candidates->job_id->EditAttributes() ?>>
<?php
if (is_array($job_candidates->job_id->EditValue)) {
	$arwrk = $job_candidates->job_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($job_candidates->job_id->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$job_candidates->job_id) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $job_candidates->job_id->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT DISTINCT `id`, `title_en` AS `DispFld`, `title_ar` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `jobs`";
 $sWhereWrk = "";

 // Call Lookup selecting
 $job_candidates->Lookup_Selecting($job_candidates->job_id, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY `title_en` ASC";
?>
<input type="hidden" name="s_x<?php echo $job_candidates_grid->RowIndex ?>_job_id" id="s_x<?php echo $job_candidates_grid->RowIndex ?>_job_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&amp;t0=19">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_job_candidates_job_id" class="form-group job_candidates_job_id">
<span<?php echo $job_candidates->job_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $job_candidates->job_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_job_id" name="x<?php echo $job_candidates_grid->RowIndex ?>_job_id" id="x<?php echo $job_candidates_grid->RowIndex ?>_job_id" value="<?php echo ew_HtmlEncode($job_candidates->job_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_job_id" name="o<?php echo $job_candidates_grid->RowIndex ?>_job_id" id="o<?php echo $job_candidates_grid->RowIndex ?>_job_id" value="<?php echo ew_HtmlEncode($job_candidates->job_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($job_candidates->name->Visible) { // name ?>
		<td>
<?php if ($job_candidates->CurrentAction <> "F") { ?>
<span id="el$rowindex$_job_candidates_name" class="form-group job_candidates_name">
<input type="text" data-field="x_name" name="x<?php echo $job_candidates_grid->RowIndex ?>_name" id="x<?php echo $job_candidates_grid->RowIndex ?>_name" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($job_candidates->name->PlaceHolder) ?>" value="<?php echo $job_candidates->name->EditValue ?>"<?php echo $job_candidates->name->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_job_candidates_name" class="form-group job_candidates_name">
<span<?php echo $job_candidates->name->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $job_candidates->name->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_name" name="x<?php echo $job_candidates_grid->RowIndex ?>_name" id="x<?php echo $job_candidates_grid->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($job_candidates->name->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_name" name="o<?php echo $job_candidates_grid->RowIndex ?>_name" id="o<?php echo $job_candidates_grid->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($job_candidates->name->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($job_candidates->_email->Visible) { // email ?>
		<td>
<?php if ($job_candidates->CurrentAction <> "F") { ?>
<span id="el$rowindex$_job_candidates__email" class="form-group job_candidates__email">
<input type="text" data-field="x__email" name="x<?php echo $job_candidates_grid->RowIndex ?>__email" id="x<?php echo $job_candidates_grid->RowIndex ?>__email" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($job_candidates->_email->PlaceHolder) ?>" value="<?php echo $job_candidates->_email->EditValue ?>"<?php echo $job_candidates->_email->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_job_candidates__email" class="form-group job_candidates__email">
<span<?php echo $job_candidates->_email->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $job_candidates->_email->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__email" name="x<?php echo $job_candidates_grid->RowIndex ?>__email" id="x<?php echo $job_candidates_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($job_candidates->_email->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x__email" name="o<?php echo $job_candidates_grid->RowIndex ?>__email" id="o<?php echo $job_candidates_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($job_candidates->_email->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($job_candidates->mobile->Visible) { // mobile ?>
		<td>
<?php if ($job_candidates->CurrentAction <> "F") { ?>
<span id="el$rowindex$_job_candidates_mobile" class="form-group job_candidates_mobile">
<input type="text" data-field="x_mobile" name="x<?php echo $job_candidates_grid->RowIndex ?>_mobile" id="x<?php echo $job_candidates_grid->RowIndex ?>_mobile" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($job_candidates->mobile->PlaceHolder) ?>" value="<?php echo $job_candidates->mobile->EditValue ?>"<?php echo $job_candidates->mobile->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_job_candidates_mobile" class="form-group job_candidates_mobile">
<span<?php echo $job_candidates->mobile->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $job_candidates->mobile->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_mobile" name="x<?php echo $job_candidates_grid->RowIndex ?>_mobile" id="x<?php echo $job_candidates_grid->RowIndex ?>_mobile" value="<?php echo ew_HtmlEncode($job_candidates->mobile->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_mobile" name="o<?php echo $job_candidates_grid->RowIndex ?>_mobile" id="o<?php echo $job_candidates_grid->RowIndex ?>_mobile" value="<?php echo ew_HtmlEncode($job_candidates->mobile->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($job_candidates->cv->Visible) { // cv ?>
		<td>
<span id="el$rowindex$_job_candidates_cv" class="form-group job_candidates_cv">
<div id="fd_x<?php echo $job_candidates_grid->RowIndex ?>_cv">
<span title="<?php echo $job_candidates->cv->FldTitle() ? $job_candidates->cv->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($job_candidates->cv->ReadOnly || $job_candidates->cv->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_cv" name="x<?php echo $job_candidates_grid->RowIndex ?>_cv" id="x<?php echo $job_candidates_grid->RowIndex ?>_cv">
</span>
<input type="hidden" name="fn_x<?php echo $job_candidates_grid->RowIndex ?>_cv" id= "fn_x<?php echo $job_candidates_grid->RowIndex ?>_cv" value="<?php echo $job_candidates->cv->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $job_candidates_grid->RowIndex ?>_cv" id= "fa_x<?php echo $job_candidates_grid->RowIndex ?>_cv" value="0">
<input type="hidden" name="fs_x<?php echo $job_candidates_grid->RowIndex ?>_cv" id= "fs_x<?php echo $job_candidates_grid->RowIndex ?>_cv" value="255">
<input type="hidden" name="fx_x<?php echo $job_candidates_grid->RowIndex ?>_cv" id= "fx_x<?php echo $job_candidates_grid->RowIndex ?>_cv" value="<?php echo $job_candidates->cv->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $job_candidates_grid->RowIndex ?>_cv" id= "fm_x<?php echo $job_candidates_grid->RowIndex ?>_cv" value="<?php echo $job_candidates->cv->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $job_candidates_grid->RowIndex ?>_cv" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-field="x_cv" name="o<?php echo $job_candidates_grid->RowIndex ?>_cv" id="o<?php echo $job_candidates_grid->RowIndex ?>_cv" value="<?php echo ew_HtmlEncode($job_candidates->cv->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($job_candidates->applied_date->Visible) { // applied_date ?>
		<td>
<?php if ($job_candidates->CurrentAction <> "F") { ?>
<span id="el$rowindex$_job_candidates_applied_date" class="form-group job_candidates_applied_date">
<input type="text" data-field="x_applied_date" name="x<?php echo $job_candidates_grid->RowIndex ?>_applied_date" id="x<?php echo $job_candidates_grid->RowIndex ?>_applied_date" placeholder="<?php echo ew_HtmlEncode($job_candidates->applied_date->PlaceHolder) ?>" value="<?php echo $job_candidates->applied_date->EditValue ?>"<?php echo $job_candidates->applied_date->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_job_candidates_applied_date" class="form-group job_candidates_applied_date">
<span<?php echo $job_candidates->applied_date->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $job_candidates->applied_date->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_applied_date" name="x<?php echo $job_candidates_grid->RowIndex ?>_applied_date" id="x<?php echo $job_candidates_grid->RowIndex ?>_applied_date" value="<?php echo ew_HtmlEncode($job_candidates->applied_date->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_applied_date" name="o<?php echo $job_candidates_grid->RowIndex ?>_applied_date" id="o<?php echo $job_candidates_grid->RowIndex ?>_applied_date" value="<?php echo ew_HtmlEncode($job_candidates->applied_date->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$job_candidates_grid->ListOptions->Render("body", "right", $job_candidates_grid->RowCnt);
?>
<script type="text/javascript">
fjob_candidatesgrid.UpdateOpts(<?php echo $job_candidates_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($job_candidates->CurrentMode == "add" || $job_candidates->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $job_candidates_grid->FormKeyCountName ?>" id="<?php echo $job_candidates_grid->FormKeyCountName ?>" value="<?php echo $job_candidates_grid->KeyCount ?>">
<?php echo $job_candidates_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($job_candidates->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $job_candidates_grid->FormKeyCountName ?>" id="<?php echo $job_candidates_grid->FormKeyCountName ?>" value="<?php echo $job_candidates_grid->KeyCount ?>">
<?php echo $job_candidates_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($job_candidates->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fjob_candidatesgrid">
</div>
<?php

// Close recordset
if ($job_candidates_grid->Recordset)
	$job_candidates_grid->Recordset->Close();
?>
<?php if ($job_candidates_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($job_candidates_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($job_candidates_grid->TotalRecs == 0 && $job_candidates->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($job_candidates_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($job_candidates->Export == "") { ?>
<script type="text/javascript">
fjob_candidatesgrid.Init();
</script>
<?php } ?>
<?php
$job_candidates_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$job_candidates_grid->Page_Terminate();
?>
