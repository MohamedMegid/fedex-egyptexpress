<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "zones_pricesinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "administratorinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$zones_prices_delete = NULL; // Initialize page object first

class czones_prices_delete extends czones_prices {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'zones_prices';

	// Page object name
	var $PageObjName = 'zones_prices_delete';

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

		// Table object (zones_prices)
		if (!isset($GLOBALS["zones_prices"]) || get_class($GLOBALS["zones_prices"]) == "czones_prices") {
			$GLOBALS["zones_prices"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["zones_prices"];
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
			define("EW_TABLE_NAME", 'zones_prices', TRUE);

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
		global $EW_EXPORT, $zones_prices;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($zones_prices);
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
			$this->Page_Terminate("zones_priceslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in zones_prices class, zones_pricesinfo.php

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
		$this->weight->setDbValue($rs->fields('weight'));
		$this->zone1->setDbValue($rs->fields('zone1'));
		$this->zone2->setDbValue($rs->fields('zone2'));
		$this->zone3->setDbValue($rs->fields('zone3'));
		$this->zone4->setDbValue($rs->fields('zone4'));
		$this->zone5->setDbValue($rs->fields('zone5'));
		$this->zone6->setDbValue($rs->fields('zone6'));
		$this->last_modified->setDbValue($rs->fields('last_modified'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->weight->DbValue = $row['weight'];
		$this->zone1->DbValue = $row['zone1'];
		$this->zone2->DbValue = $row['zone2'];
		$this->zone3->DbValue = $row['zone3'];
		$this->zone4->DbValue = $row['zone4'];
		$this->zone5->DbValue = $row['zone5'];
		$this->zone6->DbValue = $row['zone6'];
		$this->last_modified->DbValue = $row['last_modified'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->zone1->FormValue == $this->zone1->CurrentValue && is_numeric(ew_StrToFloat($this->zone1->CurrentValue)))
			$this->zone1->CurrentValue = ew_StrToFloat($this->zone1->CurrentValue);

		// Convert decimal values if posted back
		if ($this->zone2->FormValue == $this->zone2->CurrentValue && is_numeric(ew_StrToFloat($this->zone2->CurrentValue)))
			$this->zone2->CurrentValue = ew_StrToFloat($this->zone2->CurrentValue);

		// Convert decimal values if posted back
		if ($this->zone3->FormValue == $this->zone3->CurrentValue && is_numeric(ew_StrToFloat($this->zone3->CurrentValue)))
			$this->zone3->CurrentValue = ew_StrToFloat($this->zone3->CurrentValue);

		// Convert decimal values if posted back
		if ($this->zone4->FormValue == $this->zone4->CurrentValue && is_numeric(ew_StrToFloat($this->zone4->CurrentValue)))
			$this->zone4->CurrentValue = ew_StrToFloat($this->zone4->CurrentValue);

		// Convert decimal values if posted back
		if ($this->zone5->FormValue == $this->zone5->CurrentValue && is_numeric(ew_StrToFloat($this->zone5->CurrentValue)))
			$this->zone5->CurrentValue = ew_StrToFloat($this->zone5->CurrentValue);

		// Convert decimal values if posted back
		if ($this->zone6->FormValue == $this->zone6->CurrentValue && is_numeric(ew_StrToFloat($this->zone6->CurrentValue)))
			$this->zone6->CurrentValue = ew_StrToFloat($this->zone6->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// weight
		// zone1
		// zone2
		// zone3
		// zone4
		// zone5
		// zone6
		// last_modified

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// weight
			$this->weight->ViewValue = $this->weight->CurrentValue;
			$this->weight->ViewCustomAttributes = "";

			// zone1
			$this->zone1->ViewValue = $this->zone1->CurrentValue;
			$this->zone1->ViewCustomAttributes = "";

			// zone2
			$this->zone2->ViewValue = $this->zone2->CurrentValue;
			$this->zone2->ViewCustomAttributes = "";

			// zone3
			$this->zone3->ViewValue = $this->zone3->CurrentValue;
			$this->zone3->ViewCustomAttributes = "";

			// zone4
			$this->zone4->ViewValue = $this->zone4->CurrentValue;
			$this->zone4->ViewCustomAttributes = "";

			// zone5
			$this->zone5->ViewValue = $this->zone5->CurrentValue;
			$this->zone5->ViewCustomAttributes = "";

			// zone6
			$this->zone6->ViewValue = $this->zone6->CurrentValue;
			$this->zone6->ViewCustomAttributes = "";

			// last_modified
			$this->last_modified->ViewValue = $this->last_modified->CurrentValue;
			$this->last_modified->ViewValue = ew_FormatDateTime($this->last_modified->ViewValue, 7);
			$this->last_modified->ViewCustomAttributes = "";

			// weight
			$this->weight->LinkCustomAttributes = "";
			$this->weight->HrefValue = "";
			$this->weight->TooltipValue = "";

			// zone1
			$this->zone1->LinkCustomAttributes = "";
			$this->zone1->HrefValue = "";
			$this->zone1->TooltipValue = "";

			// zone2
			$this->zone2->LinkCustomAttributes = "";
			$this->zone2->HrefValue = "";
			$this->zone2->TooltipValue = "";

			// zone3
			$this->zone3->LinkCustomAttributes = "";
			$this->zone3->HrefValue = "";
			$this->zone3->TooltipValue = "";

			// zone4
			$this->zone4->LinkCustomAttributes = "";
			$this->zone4->HrefValue = "";
			$this->zone4->TooltipValue = "";

			// zone5
			$this->zone5->LinkCustomAttributes = "";
			$this->zone5->HrefValue = "";
			$this->zone5->TooltipValue = "";

			// zone6
			$this->zone6->LinkCustomAttributes = "";
			$this->zone6->HrefValue = "";
			$this->zone6->TooltipValue = "";

			// last_modified
			$this->last_modified->LinkCustomAttributes = "";
			$this->last_modified->HrefValue = "";
			$this->last_modified->TooltipValue = "";
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
				$sThisKey .= $row['weight'];
				$this->LoadDbValues($row);
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
		$Breadcrumb->Add("list", $this->TableVar, "zones_priceslist.php", "", $this->TableVar, TRUE);
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
if (!isset($zones_prices_delete)) $zones_prices_delete = new czones_prices_delete();

// Page init
$zones_prices_delete->Page_Init();

// Page main
$zones_prices_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$zones_prices_delete->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var zones_prices_delete = new ew_Page("zones_prices_delete");
zones_prices_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = zones_prices_delete.PageID; // For backward compatibility

// Form object
var fzones_pricesdelete = new ew_Form("fzones_pricesdelete");

// Form_CustomValidate event
fzones_pricesdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fzones_pricesdelete.ValidateRequired = true;
<?php } else { ?>
fzones_pricesdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($zones_prices_delete->Recordset = $zones_prices_delete->LoadRecordset())
	$zones_prices_deleteTotalRecs = $zones_prices_delete->Recordset->RecordCount(); // Get record count
if ($zones_prices_deleteTotalRecs <= 0) { // No record found, exit
	if ($zones_prices_delete->Recordset)
		$zones_prices_delete->Recordset->Close();
	$zones_prices_delete->Page_Terminate("zones_priceslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $zones_prices_delete->ShowPageHeader(); ?>
<?php
$zones_prices_delete->ShowMessage();
?>
<form name="fzones_pricesdelete" id="fzones_pricesdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($zones_prices_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $zones_prices_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="zones_prices">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($zones_prices_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $zones_prices->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($zones_prices->weight->Visible) { // weight ?>
		<th><span id="elh_zones_prices_weight" class="zones_prices_weight"><?php echo $zones_prices->weight->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zones_prices->zone1->Visible) { // zone1 ?>
		<th><span id="elh_zones_prices_zone1" class="zones_prices_zone1"><?php echo $zones_prices->zone1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zones_prices->zone2->Visible) { // zone2 ?>
		<th><span id="elh_zones_prices_zone2" class="zones_prices_zone2"><?php echo $zones_prices->zone2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zones_prices->zone3->Visible) { // zone3 ?>
		<th><span id="elh_zones_prices_zone3" class="zones_prices_zone3"><?php echo $zones_prices->zone3->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zones_prices->zone4->Visible) { // zone4 ?>
		<th><span id="elh_zones_prices_zone4" class="zones_prices_zone4"><?php echo $zones_prices->zone4->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zones_prices->zone5->Visible) { // zone5 ?>
		<th><span id="elh_zones_prices_zone5" class="zones_prices_zone5"><?php echo $zones_prices->zone5->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zones_prices->zone6->Visible) { // zone6 ?>
		<th><span id="elh_zones_prices_zone6" class="zones_prices_zone6"><?php echo $zones_prices->zone6->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zones_prices->last_modified->Visible) { // last_modified ?>
		<th><span id="elh_zones_prices_last_modified" class="zones_prices_last_modified"><?php echo $zones_prices->last_modified->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$zones_prices_delete->RecCnt = 0;
$i = 0;
while (!$zones_prices_delete->Recordset->EOF) {
	$zones_prices_delete->RecCnt++;
	$zones_prices_delete->RowCnt++;

	// Set row properties
	$zones_prices->ResetAttrs();
	$zones_prices->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$zones_prices_delete->LoadRowValues($zones_prices_delete->Recordset);

	// Render row
	$zones_prices_delete->RenderRow();
?>
	<tr<?php echo $zones_prices->RowAttributes() ?>>
<?php if ($zones_prices->weight->Visible) { // weight ?>
		<td<?php echo $zones_prices->weight->CellAttributes() ?>>
<span id="el<?php echo $zones_prices_delete->RowCnt ?>_zones_prices_weight" class="form-group zones_prices_weight">
<span<?php echo $zones_prices->weight->ViewAttributes() ?>>
<?php echo $zones_prices->weight->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zones_prices->zone1->Visible) { // zone1 ?>
		<td<?php echo $zones_prices->zone1->CellAttributes() ?>>
<span id="el<?php echo $zones_prices_delete->RowCnt ?>_zones_prices_zone1" class="form-group zones_prices_zone1">
<span<?php echo $zones_prices->zone1->ViewAttributes() ?>>
<?php echo $zones_prices->zone1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zones_prices->zone2->Visible) { // zone2 ?>
		<td<?php echo $zones_prices->zone2->CellAttributes() ?>>
<span id="el<?php echo $zones_prices_delete->RowCnt ?>_zones_prices_zone2" class="form-group zones_prices_zone2">
<span<?php echo $zones_prices->zone2->ViewAttributes() ?>>
<?php echo $zones_prices->zone2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zones_prices->zone3->Visible) { // zone3 ?>
		<td<?php echo $zones_prices->zone3->CellAttributes() ?>>
<span id="el<?php echo $zones_prices_delete->RowCnt ?>_zones_prices_zone3" class="form-group zones_prices_zone3">
<span<?php echo $zones_prices->zone3->ViewAttributes() ?>>
<?php echo $zones_prices->zone3->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zones_prices->zone4->Visible) { // zone4 ?>
		<td<?php echo $zones_prices->zone4->CellAttributes() ?>>
<span id="el<?php echo $zones_prices_delete->RowCnt ?>_zones_prices_zone4" class="form-group zones_prices_zone4">
<span<?php echo $zones_prices->zone4->ViewAttributes() ?>>
<?php echo $zones_prices->zone4->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zones_prices->zone5->Visible) { // zone5 ?>
		<td<?php echo $zones_prices->zone5->CellAttributes() ?>>
<span id="el<?php echo $zones_prices_delete->RowCnt ?>_zones_prices_zone5" class="form-group zones_prices_zone5">
<span<?php echo $zones_prices->zone5->ViewAttributes() ?>>
<?php echo $zones_prices->zone5->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zones_prices->zone6->Visible) { // zone6 ?>
		<td<?php echo $zones_prices->zone6->CellAttributes() ?>>
<span id="el<?php echo $zones_prices_delete->RowCnt ?>_zones_prices_zone6" class="form-group zones_prices_zone6">
<span<?php echo $zones_prices->zone6->ViewAttributes() ?>>
<?php echo $zones_prices->zone6->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zones_prices->last_modified->Visible) { // last_modified ?>
		<td<?php echo $zones_prices->last_modified->CellAttributes() ?>>
<span id="el<?php echo $zones_prices_delete->RowCnt ?>_zones_prices_last_modified" class="form-group zones_prices_last_modified">
<span<?php echo $zones_prices->last_modified->ViewAttributes() ?>>
<?php echo $zones_prices->last_modified->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$zones_prices_delete->Recordset->MoveNext();
}
$zones_prices_delete->Recordset->Close();
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
fzones_pricesdelete.Init();
</script>
<?php
$zones_prices_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$zones_prices_delete->Page_Terminate();
?>
