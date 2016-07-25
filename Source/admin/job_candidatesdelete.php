<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "job_candidatesinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "jobsinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "administratorinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$job_candidates_delete = NULL; // Initialize page object first

class cjob_candidates_delete extends cjob_candidates {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'job_candidates';

	// Page object name
	var $PageObjName = 'job_candidates_delete';

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

		// Table object (job_candidates)
		if (!isset($GLOBALS["job_candidates"]) || get_class($GLOBALS["job_candidates"]) == "cjob_candidates") {
			$GLOBALS["job_candidates"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["job_candidates"];
		}

		// Table object (jobs)
		if (!isset($GLOBALS['jobs'])) $GLOBALS['jobs'] = new cjobs();

		// Table object (administrator)
		if (!isset($GLOBALS['administrator'])) $GLOBALS['administrator'] = new cadministrator();

		// User table object (administrator)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cadministrator();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'job_candidates', TRUE);

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
		global $EW_EXPORT, $job_candidates;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($job_candidates);
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

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("job_candidateslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in job_candidates class, job_candidatesinfo.php

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
		$this->job_id->setDbValue($rs->fields('job_id'));
		if (array_key_exists('EV__job_id', $rs->fields)) {
			$this->job_id->VirtualValue = $rs->fields('EV__job_id'); // Set up virtual field value
		} else {
			$this->job_id->VirtualValue = ""; // Clear value
		}
		$this->name->setDbValue($rs->fields('name'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->mobile->setDbValue($rs->fields('mobile'));
		$this->cv->Upload->DbValue = $rs->fields('cv');
		$this->cv->CurrentValue = $this->cv->Upload->DbValue;
		$this->applied_date->setDbValue($rs->fields('applied_date'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->job_id->DbValue = $row['job_id'];
		$this->name->DbValue = $row['name'];
		$this->_email->DbValue = $row['email'];
		$this->mobile->DbValue = $row['mobile'];
		$this->cv->Upload->DbValue = $row['cv'];
		$this->applied_date->DbValue = $row['applied_date'];
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
		// job_id
		// name
		// email
		// mobile
		// cv
		// applied_date

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// job_id
			if ($this->job_id->VirtualValue <> "") {
				$this->job_id->ViewValue = $this->job_id->VirtualValue;
			} else {
			if (strval($this->job_id->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->job_id->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT DISTINCT `id`, `title_en` AS `DispFld`, `title_ar` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `jobs`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->job_id, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `title_en` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->job_id->ViewValue = $rswrk->fields('DispFld');
					$this->job_id->ViewValue .= ew_ValueSeparator(1,$this->job_id) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->job_id->ViewValue = $this->job_id->CurrentValue;
				}
			} else {
				$this->job_id->ViewValue = NULL;
			}
			}
			$this->job_id->ViewCustomAttributes = "";

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// email
			$this->_email->ViewValue = $this->_email->CurrentValue;
			$this->_email->ViewCustomAttributes = "";

			// mobile
			$this->mobile->ViewValue = $this->mobile->CurrentValue;
			$this->mobile->ViewCustomAttributes = "";

			// cv
			$this->cv->UploadPath = '../webroot/uploads/jobs/';
			if (!ew_Empty($this->cv->Upload->DbValue)) {
				$this->cv->ViewValue = $this->cv->Upload->DbValue;
			} else {
				$this->cv->ViewValue = "";
			}
			$this->cv->ViewCustomAttributes = "";

			// applied_date
			$this->applied_date->ViewValue = $this->applied_date->CurrentValue;
			$this->applied_date->ViewValue = ew_FormatDateTime($this->applied_date->ViewValue, 7);
			$this->applied_date->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// job_id
			$this->job_id->LinkCustomAttributes = "";
			$this->job_id->HrefValue = "";
			$this->job_id->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// mobile
			$this->mobile->LinkCustomAttributes = "";
			$this->mobile->HrefValue = "";
			$this->mobile->TooltipValue = "";

			// cv
			$this->cv->LinkCustomAttributes = "";
			$this->cv->UploadPath = '../webroot/uploads/jobs/';
			if (!ew_Empty($this->cv->Upload->DbValue)) {
				$this->cv->HrefValue = ew_UploadPathEx(FALSE, $this->cv->UploadPath) . $this->cv->Upload->DbValue; // Add prefix/suffix
				$this->cv->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->cv->HrefValue = ew_ConvertFullUrl($this->cv->HrefValue);
			} else {
				$this->cv->HrefValue = "";
			}
			$this->cv->HrefValue2 = $this->cv->UploadPath . $this->cv->Upload->DbValue;
			$this->cv->TooltipValue = "";

			// applied_date
			$this->applied_date->LinkCustomAttributes = "";
			$this->applied_date->HrefValue = "";
			$this->applied_date->TooltipValue = "";
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
				$this->cv->OldUploadPath = '../webroot/uploads/jobs/';
				@unlink(ew_UploadPathEx(TRUE, $this->cv->OldUploadPath) . $row['cv']);
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

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "jobs") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_id"] <> "") {
					$GLOBALS["jobs"]->id->setQueryStringValue($_GET["fk_id"]);
					$this->job_id->setQueryStringValue($GLOBALS["jobs"]->id->QueryStringValue);
					$this->job_id->setSessionValue($this->job_id->QueryStringValue);
					if (!is_numeric($GLOBALS["jobs"]->id->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "jobs") {
				if ($this->job_id->QueryStringValue == "") $this->job_id->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "job_candidateslist.php", "", $this->TableVar, TRUE);
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
if (!isset($job_candidates_delete)) $job_candidates_delete = new cjob_candidates_delete();

// Page init
$job_candidates_delete->Page_Init();

// Page main
$job_candidates_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$job_candidates_delete->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var job_candidates_delete = new ew_Page("job_candidates_delete");
job_candidates_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = job_candidates_delete.PageID; // For backward compatibility

// Form object
var fjob_candidatesdelete = new ew_Form("fjob_candidatesdelete");

// Form_CustomValidate event
fjob_candidatesdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fjob_candidatesdelete.ValidateRequired = true;
<?php } else { ?>
fjob_candidatesdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fjob_candidatesdelete.Lists["x_job_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_title_en","x_title_ar","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($job_candidates_delete->Recordset = $job_candidates_delete->LoadRecordset())
	$job_candidates_deleteTotalRecs = $job_candidates_delete->Recordset->RecordCount(); // Get record count
if ($job_candidates_deleteTotalRecs <= 0) { // No record found, exit
	if ($job_candidates_delete->Recordset)
		$job_candidates_delete->Recordset->Close();
	$job_candidates_delete->Page_Terminate("job_candidateslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $job_candidates_delete->ShowPageHeader(); ?>
<?php
$job_candidates_delete->ShowMessage();
?>
<form name="fjob_candidatesdelete" id="fjob_candidatesdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($job_candidates_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $job_candidates_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="job_candidates">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($job_candidates_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $job_candidates->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($job_candidates->id->Visible) { // id ?>
		<th><span id="elh_job_candidates_id" class="job_candidates_id"><?php echo $job_candidates->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($job_candidates->job_id->Visible) { // job_id ?>
		<th><span id="elh_job_candidates_job_id" class="job_candidates_job_id"><?php echo $job_candidates->job_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($job_candidates->name->Visible) { // name ?>
		<th><span id="elh_job_candidates_name" class="job_candidates_name"><?php echo $job_candidates->name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($job_candidates->_email->Visible) { // email ?>
		<th><span id="elh_job_candidates__email" class="job_candidates__email"><?php echo $job_candidates->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($job_candidates->mobile->Visible) { // mobile ?>
		<th><span id="elh_job_candidates_mobile" class="job_candidates_mobile"><?php echo $job_candidates->mobile->FldCaption() ?></span></th>
<?php } ?>
<?php if ($job_candidates->cv->Visible) { // cv ?>
		<th><span id="elh_job_candidates_cv" class="job_candidates_cv"><?php echo $job_candidates->cv->FldCaption() ?></span></th>
<?php } ?>
<?php if ($job_candidates->applied_date->Visible) { // applied_date ?>
		<th><span id="elh_job_candidates_applied_date" class="job_candidates_applied_date"><?php echo $job_candidates->applied_date->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$job_candidates_delete->RecCnt = 0;
$i = 0;
while (!$job_candidates_delete->Recordset->EOF) {
	$job_candidates_delete->RecCnt++;
	$job_candidates_delete->RowCnt++;

	// Set row properties
	$job_candidates->ResetAttrs();
	$job_candidates->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$job_candidates_delete->LoadRowValues($job_candidates_delete->Recordset);

	// Render row
	$job_candidates_delete->RenderRow();
?>
	<tr<?php echo $job_candidates->RowAttributes() ?>>
<?php if ($job_candidates->id->Visible) { // id ?>
		<td<?php echo $job_candidates->id->CellAttributes() ?>>
<span id="el<?php echo $job_candidates_delete->RowCnt ?>_job_candidates_id" class="form-group job_candidates_id">
<span<?php echo $job_candidates->id->ViewAttributes() ?>>
<?php echo $job_candidates->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($job_candidates->job_id->Visible) { // job_id ?>
		<td<?php echo $job_candidates->job_id->CellAttributes() ?>>
<span id="el<?php echo $job_candidates_delete->RowCnt ?>_job_candidates_job_id" class="form-group job_candidates_job_id">
<span<?php echo $job_candidates->job_id->ViewAttributes() ?>>
<?php echo $job_candidates->job_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($job_candidates->name->Visible) { // name ?>
		<td<?php echo $job_candidates->name->CellAttributes() ?>>
<span id="el<?php echo $job_candidates_delete->RowCnt ?>_job_candidates_name" class="form-group job_candidates_name">
<span<?php echo $job_candidates->name->ViewAttributes() ?>>
<?php echo $job_candidates->name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($job_candidates->_email->Visible) { // email ?>
		<td<?php echo $job_candidates->_email->CellAttributes() ?>>
<span id="el<?php echo $job_candidates_delete->RowCnt ?>_job_candidates__email" class="form-group job_candidates__email">
<span<?php echo $job_candidates->_email->ViewAttributes() ?>>
<?php echo $job_candidates->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($job_candidates->mobile->Visible) { // mobile ?>
		<td<?php echo $job_candidates->mobile->CellAttributes() ?>>
<span id="el<?php echo $job_candidates_delete->RowCnt ?>_job_candidates_mobile" class="form-group job_candidates_mobile">
<span<?php echo $job_candidates->mobile->ViewAttributes() ?>>
<?php echo $job_candidates->mobile->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($job_candidates->cv->Visible) { // cv ?>
		<td<?php echo $job_candidates->cv->CellAttributes() ?>>
<span id="el<?php echo $job_candidates_delete->RowCnt ?>_job_candidates_cv" class="form-group job_candidates_cv">
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
</span>
</td>
<?php } ?>
<?php if ($job_candidates->applied_date->Visible) { // applied_date ?>
		<td<?php echo $job_candidates->applied_date->CellAttributes() ?>>
<span id="el<?php echo $job_candidates_delete->RowCnt ?>_job_candidates_applied_date" class="form-group job_candidates_applied_date">
<span<?php echo $job_candidates->applied_date->ViewAttributes() ?>>
<?php echo $job_candidates->applied_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$job_candidates_delete->Recordset->MoveNext();
}
$job_candidates_delete->Recordset->Close();
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
fjob_candidatesdelete.Init();
</script>
<?php
$job_candidates_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$job_candidates_delete->Page_Terminate();
?>
