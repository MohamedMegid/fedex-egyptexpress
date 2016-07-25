<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "jobsinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "administratorinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$jobs_delete = NULL; // Initialize page object first

class cjobs_delete extends cjobs {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'jobs';

	// Page object name
	var $PageObjName = 'jobs_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME]);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (jobs)
		if (!isset($GLOBALS["jobs"]) || get_class($GLOBALS["jobs"]) == "cjobs") {
			$GLOBALS["jobs"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["jobs"];
		}

		// Table object (administrator)
		if (!isset($GLOBALS['administrator'])) $GLOBALS['administrator'] = new cadministrator();

		// User table object (administrator)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cadministrator();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'jobs', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {

			// Process auto fill for detail table 'job_candidates'
			if (@$_POST["grid"] == "fjob_candidatesgrid") {
				if (!isset($GLOBALS["job_candidates_grid"])) $GLOBALS["job_candidates_grid"] = new cjob_candidates_grid;
				$GLOBALS["job_candidates_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn, $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $jobs;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($jobs);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("jobslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in jobs class, jobsinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "D"; // Delete record directly
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Load List page SQL
		$sSql = $this->SelectSQL();

		// Load recordset
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
		$conn->raiseErrorFn = '';

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->id->setDbValue($rs->fields('id'));
		$this->image->Upload->DbValue = $rs->fields('image');
		$this->image->CurrentValue = $this->image->Upload->DbValue;
		$this->title_en->setDbValue($rs->fields('title_en'));
		$this->title_ar->setDbValue($rs->fields('title_ar'));
		$this->position_en->setDbValue($rs->fields('position_en'));
		$this->position_ar->setDbValue($rs->fields('position_ar'));
		$this->experience->setDbValue($rs->fields('experience'));
		$this->gender->setDbValue($rs->fields('gender'));
		$this->applied->setDbValue($rs->fields('applied'));
		$this->desc_en->setDbValue($rs->fields('desc_en'));
		$this->desc_ar->setDbValue($rs->fields('desc_ar'));
		$this->created->setDbValue($rs->fields('created'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->image->Upload->DbValue = $row['image'];
		$this->title_en->DbValue = $row['title_en'];
		$this->title_ar->DbValue = $row['title_ar'];
		$this->position_en->DbValue = $row['position_en'];
		$this->position_ar->DbValue = $row['position_ar'];
		$this->experience->DbValue = $row['experience'];
		$this->gender->DbValue = $row['gender'];
		$this->applied->DbValue = $row['applied'];
		$this->desc_en->DbValue = $row['desc_en'];
		$this->desc_ar->DbValue = $row['desc_ar'];
		$this->created->DbValue = $row['created'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// image
		// title_en
		// title_ar
		// position_en
		// position_ar
		// experience
		// gender
		// applied
		// desc_en
		// desc_ar
		// created

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// image
			$this->image->UploadPath = '../webroot/uploads/jobs/';
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->ImageWidth = 100;
				$this->image->ImageHeight = 0;
				$this->image->ImageAlt = $this->image->FldAlt();
				$this->image->ViewValue = ew_UploadPathEx(FALSE, $this->image->UploadPath) . $this->image->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->image->ViewValue = ew_UploadPathEx(TRUE, $this->image->UploadPath) . $this->image->Upload->DbValue;
				}
			} else {
				$this->image->ViewValue = "";
			}
			$this->image->ViewCustomAttributes = "";

			// title_en
			$this->title_en->ViewValue = $this->title_en->CurrentValue;
			$this->title_en->ViewCustomAttributes = "";

			// title_ar
			$this->title_ar->ViewValue = $this->title_ar->CurrentValue;
			$this->title_ar->ViewCustomAttributes = "";

			// position_en
			$this->position_en->ViewValue = $this->position_en->CurrentValue;
			$this->position_en->ViewCustomAttributes = "";

			// position_ar
			$this->position_ar->ViewValue = $this->position_ar->CurrentValue;
			$this->position_ar->ViewCustomAttributes = "";

			// experience
			$this->experience->ViewValue = $this->experience->CurrentValue;
			$this->experience->ViewCustomAttributes = "";

			// gender
			if (strval($this->gender->CurrentValue) <> "") {
				switch ($this->gender->CurrentValue) {
					case $this->gender->FldTagValue(1):
						$this->gender->ViewValue = $this->gender->FldTagCaption(1) <> "" ? $this->gender->FldTagCaption(1) : $this->gender->CurrentValue;
						break;
					case $this->gender->FldTagValue(2):
						$this->gender->ViewValue = $this->gender->FldTagCaption(2) <> "" ? $this->gender->FldTagCaption(2) : $this->gender->CurrentValue;
						break;
					case $this->gender->FldTagValue(3):
						$this->gender->ViewValue = $this->gender->FldTagCaption(3) <> "" ? $this->gender->FldTagCaption(3) : $this->gender->CurrentValue;
						break;
					default:
						$this->gender->ViewValue = $this->gender->CurrentValue;
				}
			} else {
				$this->gender->ViewValue = NULL;
			}
			$this->gender->ViewCustomAttributes = "";

			// applied
			$this->applied->ViewValue = $this->applied->CurrentValue;
			$this->applied->ViewCustomAttributes = "";

			// desc_en
			$this->desc_en->ViewValue = $this->desc_en->CurrentValue;
			$this->desc_en->ViewCustomAttributes = "";

			// desc_ar
			$this->desc_ar->ViewValue = $this->desc_ar->CurrentValue;
			$this->desc_ar->ViewCustomAttributes = "";

			// created
			$this->created->ViewValue = $this->created->CurrentValue;
			$this->created->ViewValue = ew_FormatDateTime($this->created->ViewValue, 7);
			$this->created->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->UploadPath = '../webroot/uploads/jobs/';
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->HrefValue = ew_UploadPathEx(FALSE, $this->image->UploadPath) . $this->image->Upload->DbValue; // Add prefix/suffix
				$this->image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->image->HrefValue = ew_ConvertFullUrl($this->image->HrefValue);
			} else {
				$this->image->HrefValue = "";
			}
			$this->image->HrefValue2 = $this->image->UploadPath . $this->image->Upload->DbValue;
			$this->image->TooltipValue = "";
			if ($this->image->UseColorbox) {
				$this->image->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->image->LinkAttrs["data-rel"] = "jobs_x_image";
				$this->image->LinkAttrs["class"] = "ewLightbox";
			}

			// title_en
			$this->title_en->LinkCustomAttributes = "";
			$this->title_en->HrefValue = "";
			$this->title_en->TooltipValue = "";

			// title_ar
			$this->title_ar->LinkCustomAttributes = "";
			$this->title_ar->HrefValue = "";
			$this->title_ar->TooltipValue = "";

			// position_en
			$this->position_en->LinkCustomAttributes = "";
			$this->position_en->HrefValue = "";
			$this->position_en->TooltipValue = "";

			// experience
			$this->experience->LinkCustomAttributes = "";
			$this->experience->HrefValue = "";
			$this->experience->TooltipValue = "";

			// gender
			$this->gender->LinkCustomAttributes = "";
			$this->gender->HrefValue = "";
			$this->gender->TooltipValue = "";

			// applied
			$this->applied->LinkCustomAttributes = "";
			$this->applied->HrefValue = "";
			$this->applied->TooltipValue = "";

			// created
			$this->created->LinkCustomAttributes = "";
			$this->created->HrefValue = "";
			$this->created->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();

		// Check if records exist for detail table 'job_candidates'
		if (!isset($GLOBALS["job_candidates"])) $GLOBALS["job_candidates"] = new cjob_candidates();
		foreach ($rows as $row) {
			$rsdetail = $GLOBALS["job_candidates"]->LoadRs("`job_id` = " . ew_QuotedValue($row['id'], EW_DATATYPE_NUMBER));
			if ($rsdetail && !$rsdetail->EOF) {
				$sRelatedRecordMsg = str_replace("%t", "job_candidates", $Language->Phrase("RelatedRecordExists"));
				$this->setFailureMessage($sRelatedRecordMsg);
				return FALSE;
			}
		}
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$this->LoadDbValues($row);
				$this->image->OldUploadPath = '../webroot/uploads/jobs/';
				@unlink(ew_UploadPathEx(TRUE, $this->image->OldUploadPath) . $row['image']);
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "jobslist.php", "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, ew_CurrentUrl());
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($jobs_delete)) $jobs_delete = new cjobs_delete();

// Page init
$jobs_delete->Page_Init();

// Page main
$jobs_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$jobs_delete->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var jobs_delete = new ew_Page("jobs_delete");
jobs_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = jobs_delete.PageID; // For backward compatibility

// Form object
var fjobsdelete = new ew_Form("fjobsdelete");

// Form_CustomValidate event
fjobsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fjobsdelete.ValidateRequired = true;
<?php } else { ?>
fjobsdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($jobs_delete->Recordset = $jobs_delete->LoadRecordset())
	$jobs_deleteTotalRecs = $jobs_delete->Recordset->RecordCount(); // Get record count
if ($jobs_deleteTotalRecs <= 0) { // No record found, exit
	if ($jobs_delete->Recordset)
		$jobs_delete->Recordset->Close();
	$jobs_delete->Page_Terminate("jobslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $jobs_delete->ShowPageHeader(); ?>
<?php
$jobs_delete->ShowMessage();
?>
<form name="fjobsdelete" id="fjobsdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($jobs_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $jobs_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="jobs">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($jobs_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $jobs->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($jobs->id->Visible) { // id ?>
		<th><span id="elh_jobs_id" class="jobs_id"><?php echo $jobs->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jobs->image->Visible) { // image ?>
		<th><span id="elh_jobs_image" class="jobs_image"><?php echo $jobs->image->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jobs->title_en->Visible) { // title_en ?>
		<th><span id="elh_jobs_title_en" class="jobs_title_en"><?php echo $jobs->title_en->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jobs->title_ar->Visible) { // title_ar ?>
		<th><span id="elh_jobs_title_ar" class="jobs_title_ar"><?php echo $jobs->title_ar->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jobs->position_en->Visible) { // position_en ?>
		<th><span id="elh_jobs_position_en" class="jobs_position_en"><?php echo $jobs->position_en->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jobs->experience->Visible) { // experience ?>
		<th><span id="elh_jobs_experience" class="jobs_experience"><?php echo $jobs->experience->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jobs->gender->Visible) { // gender ?>
		<th><span id="elh_jobs_gender" class="jobs_gender"><?php echo $jobs->gender->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jobs->applied->Visible) { // applied ?>
		<th><span id="elh_jobs_applied" class="jobs_applied"><?php echo $jobs->applied->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jobs->created->Visible) { // created ?>
		<th><span id="elh_jobs_created" class="jobs_created"><?php echo $jobs->created->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$jobs_delete->RecCnt = 0;
$i = 0;
while (!$jobs_delete->Recordset->EOF) {
	$jobs_delete->RecCnt++;
	$jobs_delete->RowCnt++;

	// Set row properties
	$jobs->ResetAttrs();
	$jobs->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$jobs_delete->LoadRowValues($jobs_delete->Recordset);

	// Render row
	$jobs_delete->RenderRow();
?>
	<tr<?php echo $jobs->RowAttributes() ?>>
<?php if ($jobs->id->Visible) { // id ?>
		<td<?php echo $jobs->id->CellAttributes() ?>>
<span id="el<?php echo $jobs_delete->RowCnt ?>_jobs_id" class="form-group jobs_id">
<span<?php echo $jobs->id->ViewAttributes() ?>>
<?php echo $jobs->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jobs->image->Visible) { // image ?>
		<td<?php echo $jobs->image->CellAttributes() ?>>
<span id="el<?php echo $jobs_delete->RowCnt ?>_jobs_image" class="form-group jobs_image">
<span>
<?php echo ew_GetFileViewTag($jobs->image, $jobs->image->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($jobs->title_en->Visible) { // title_en ?>
		<td<?php echo $jobs->title_en->CellAttributes() ?>>
<span id="el<?php echo $jobs_delete->RowCnt ?>_jobs_title_en" class="form-group jobs_title_en">
<span<?php echo $jobs->title_en->ViewAttributes() ?>>
<?php echo $jobs->title_en->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jobs->title_ar->Visible) { // title_ar ?>
		<td<?php echo $jobs->title_ar->CellAttributes() ?>>
<span id="el<?php echo $jobs_delete->RowCnt ?>_jobs_title_ar" class="form-group jobs_title_ar">
<span<?php echo $jobs->title_ar->ViewAttributes() ?>>
<?php echo $jobs->title_ar->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jobs->position_en->Visible) { // position_en ?>
		<td<?php echo $jobs->position_en->CellAttributes() ?>>
<span id="el<?php echo $jobs_delete->RowCnt ?>_jobs_position_en" class="form-group jobs_position_en">
<span<?php echo $jobs->position_en->ViewAttributes() ?>>
<?php echo $jobs->position_en->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jobs->experience->Visible) { // experience ?>
		<td<?php echo $jobs->experience->CellAttributes() ?>>
<span id="el<?php echo $jobs_delete->RowCnt ?>_jobs_experience" class="form-group jobs_experience">
<span<?php echo $jobs->experience->ViewAttributes() ?>>
<?php echo $jobs->experience->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jobs->gender->Visible) { // gender ?>
		<td<?php echo $jobs->gender->CellAttributes() ?>>
<span id="el<?php echo $jobs_delete->RowCnt ?>_jobs_gender" class="form-group jobs_gender">
<span<?php echo $jobs->gender->ViewAttributes() ?>>
<?php echo $jobs->gender->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jobs->applied->Visible) { // applied ?>
		<td<?php echo $jobs->applied->CellAttributes() ?>>
<span id="el<?php echo $jobs_delete->RowCnt ?>_jobs_applied" class="form-group jobs_applied">
<span<?php echo $jobs->applied->ViewAttributes() ?>>
<?php echo $jobs->applied->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jobs->created->Visible) { // created ?>
		<td<?php echo $jobs->created->CellAttributes() ?>>
<span id="el<?php echo $jobs_delete->RowCnt ?>_jobs_created" class="form-group jobs_created">
<span<?php echo $jobs->created->ViewAttributes() ?>>
<?php echo $jobs->created->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$jobs_delete->Recordset->MoveNext();
}
$jobs_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fjobsdelete.Init();
</script>
<?php
$jobs_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$jobs_delete->Page_Terminate();
?>
