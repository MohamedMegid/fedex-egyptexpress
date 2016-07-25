<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "our_clientsinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "administratorinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$our_clients_delete = NULL; // Initialize page object first

class cour_clients_delete extends cour_clients {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'our_clients';

	// Page object name
	var $PageObjName = 'our_clients_delete';

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

		// Table object (our_clients)
		if (!isset($GLOBALS["our_clients"]) || get_class($GLOBALS["our_clients"]) == "cour_clients") {
			$GLOBALS["our_clients"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["our_clients"];
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
			define("EW_TABLE_NAME", 'our_clients', TRUE);

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
		global $EW_EXPORT, $our_clients;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($our_clients);
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
			$this->Page_Terminate("our_clientslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in our_clients class, our_clientsinfo.php

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
		$this->off_image->Upload->DbValue = $rs->fields('off_image');
		$this->off_image->CurrentValue = $this->off_image->Upload->DbValue;
		$this->on_image->Upload->DbValue = $rs->fields('on_image');
		$this->on_image->CurrentValue = $this->on_image->Upload->DbValue;
		$this->created->setDbValue($rs->fields('created'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->off_image->Upload->DbValue = $row['off_image'];
		$this->on_image->Upload->DbValue = $row['on_image'];
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
		// off_image
		// on_image
		// created

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// off_image
			$this->off_image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->off_image->Upload->DbValue)) {
				$this->off_image->ImageWidth = 150;
				$this->off_image->ImageHeight = 0;
				$this->off_image->ImageAlt = $this->off_image->FldAlt();
				$this->off_image->ViewValue = ew_UploadPathEx(FALSE, $this->off_image->UploadPath) . $this->off_image->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->off_image->ViewValue = ew_UploadPathEx(TRUE, $this->off_image->UploadPath) . $this->off_image->Upload->DbValue;
				}
			} else {
				$this->off_image->ViewValue = "";
			}
			$this->off_image->ViewCustomAttributes = "";

			// on_image
			$this->on_image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->on_image->Upload->DbValue)) {
				$this->on_image->ImageWidth = 150;
				$this->on_image->ImageHeight = 0;
				$this->on_image->ImageAlt = $this->on_image->FldAlt();
				$this->on_image->ViewValue = ew_UploadPathEx(FALSE, $this->on_image->UploadPath) . $this->on_image->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->on_image->ViewValue = ew_UploadPathEx(TRUE, $this->on_image->UploadPath) . $this->on_image->Upload->DbValue;
				}
			} else {
				$this->on_image->ViewValue = "";
			}
			$this->on_image->ViewCustomAttributes = "";

			// created
			$this->created->ViewValue = $this->created->CurrentValue;
			$this->created->ViewValue = ew_FormatDateTime($this->created->ViewValue, 7);
			$this->created->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// off_image
			$this->off_image->LinkCustomAttributes = "";
			$this->off_image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->off_image->Upload->DbValue)) {
				$this->off_image->HrefValue = ew_UploadPathEx(FALSE, $this->off_image->UploadPath) . $this->off_image->Upload->DbValue; // Add prefix/suffix
				$this->off_image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->off_image->HrefValue = ew_ConvertFullUrl($this->off_image->HrefValue);
			} else {
				$this->off_image->HrefValue = "";
			}
			$this->off_image->HrefValue2 = $this->off_image->UploadPath . $this->off_image->Upload->DbValue;
			$this->off_image->TooltipValue = "";
			if ($this->off_image->UseColorbox) {
				$this->off_image->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->off_image->LinkAttrs["data-rel"] = "our_clients_x_off_image";
				$this->off_image->LinkAttrs["class"] = "ewLightbox";
			}

			// on_image
			$this->on_image->LinkCustomAttributes = "";
			$this->on_image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->on_image->Upload->DbValue)) {
				$this->on_image->HrefValue = ew_UploadPathEx(FALSE, $this->on_image->UploadPath) . $this->on_image->Upload->DbValue; // Add prefix/suffix
				$this->on_image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->on_image->HrefValue = ew_ConvertFullUrl($this->on_image->HrefValue);
			} else {
				$this->on_image->HrefValue = "";
			}
			$this->on_image->HrefValue2 = $this->on_image->UploadPath . $this->on_image->Upload->DbValue;
			$this->on_image->TooltipValue = "";
			if ($this->on_image->UseColorbox) {
				$this->on_image->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->on_image->LinkAttrs["data-rel"] = "our_clients_x_on_image";
				$this->on_image->LinkAttrs["class"] = "ewLightbox";
			}

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
				$this->off_image->OldUploadPath = '../webroot/uploads/images/';
				@unlink(ew_UploadPathEx(TRUE, $this->off_image->OldUploadPath) . $row['off_image']);
				$this->on_image->OldUploadPath = '../webroot/uploads/images/';
				@unlink(ew_UploadPathEx(TRUE, $this->on_image->OldUploadPath) . $row['on_image']);
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
		$Breadcrumb->Add("list", $this->TableVar, "our_clientslist.php", "", $this->TableVar, TRUE);
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
if (!isset($our_clients_delete)) $our_clients_delete = new cour_clients_delete();

// Page init
$our_clients_delete->Page_Init();

// Page main
$our_clients_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$our_clients_delete->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var our_clients_delete = new ew_Page("our_clients_delete");
our_clients_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = our_clients_delete.PageID; // For backward compatibility

// Form object
var four_clientsdelete = new ew_Form("four_clientsdelete");

// Form_CustomValidate event
four_clientsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
four_clientsdelete.ValidateRequired = true;
<?php } else { ?>
four_clientsdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($our_clients_delete->Recordset = $our_clients_delete->LoadRecordset())
	$our_clients_deleteTotalRecs = $our_clients_delete->Recordset->RecordCount(); // Get record count
if ($our_clients_deleteTotalRecs <= 0) { // No record found, exit
	if ($our_clients_delete->Recordset)
		$our_clients_delete->Recordset->Close();
	$our_clients_delete->Page_Terminate("our_clientslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $our_clients_delete->ShowPageHeader(); ?>
<?php
$our_clients_delete->ShowMessage();
?>
<form name="four_clientsdelete" id="four_clientsdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($our_clients_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $our_clients_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="our_clients">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($our_clients_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $our_clients->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($our_clients->id->Visible) { // id ?>
		<th><span id="elh_our_clients_id" class="our_clients_id"><?php echo $our_clients->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($our_clients->off_image->Visible) { // off_image ?>
		<th><span id="elh_our_clients_off_image" class="our_clients_off_image"><?php echo $our_clients->off_image->FldCaption() ?></span></th>
<?php } ?>
<?php if ($our_clients->on_image->Visible) { // on_image ?>
		<th><span id="elh_our_clients_on_image" class="our_clients_on_image"><?php echo $our_clients->on_image->FldCaption() ?></span></th>
<?php } ?>
<?php if ($our_clients->created->Visible) { // created ?>
		<th><span id="elh_our_clients_created" class="our_clients_created"><?php echo $our_clients->created->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$our_clients_delete->RecCnt = 0;
$i = 0;
while (!$our_clients_delete->Recordset->EOF) {
	$our_clients_delete->RecCnt++;
	$our_clients_delete->RowCnt++;

	// Set row properties
	$our_clients->ResetAttrs();
	$our_clients->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$our_clients_delete->LoadRowValues($our_clients_delete->Recordset);

	// Render row
	$our_clients_delete->RenderRow();
?>
	<tr<?php echo $our_clients->RowAttributes() ?>>
<?php if ($our_clients->id->Visible) { // id ?>
		<td<?php echo $our_clients->id->CellAttributes() ?>>
<span id="el<?php echo $our_clients_delete->RowCnt ?>_our_clients_id" class="form-group our_clients_id">
<span<?php echo $our_clients->id->ViewAttributes() ?>>
<?php echo $our_clients->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($our_clients->off_image->Visible) { // off_image ?>
		<td<?php echo $our_clients->off_image->CellAttributes() ?>>
<span id="el<?php echo $our_clients_delete->RowCnt ?>_our_clients_off_image" class="form-group our_clients_off_image">
<span>
<?php echo ew_GetFileViewTag($our_clients->off_image, $our_clients->off_image->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($our_clients->on_image->Visible) { // on_image ?>
		<td<?php echo $our_clients->on_image->CellAttributes() ?>>
<span id="el<?php echo $our_clients_delete->RowCnt ?>_our_clients_on_image" class="form-group our_clients_on_image">
<span>
<?php echo ew_GetFileViewTag($our_clients->on_image, $our_clients->on_image->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($our_clients->created->Visible) { // created ?>
		<td<?php echo $our_clients->created->CellAttributes() ?>>
<span id="el<?php echo $our_clients_delete->RowCnt ?>_our_clients_created" class="form-group our_clients_created">
<span<?php echo $our_clients->created->ViewAttributes() ?>>
<?php echo $our_clients->created->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$our_clients_delete->Recordset->MoveNext();
}
$our_clients_delete->Recordset->Close();
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
four_clientsdelete.Init();
</script>
<?php
$our_clients_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$our_clients_delete->Page_Terminate();
?>
