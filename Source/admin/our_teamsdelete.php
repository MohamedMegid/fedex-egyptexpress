<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "our_teamsinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "administratorinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$our_teams_delete = NULL; // Initialize page object first

class cour_teams_delete extends cour_teams {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'our_teams';

	// Page object name
	var $PageObjName = 'our_teams_delete';

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

		// Table object (our_teams)
		if (!isset($GLOBALS["our_teams"]) || get_class($GLOBALS["our_teams"]) == "cour_teams") {
			$GLOBALS["our_teams"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["our_teams"];
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
			define("EW_TABLE_NAME", 'our_teams', TRUE);

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
		global $EW_EXPORT, $our_teams;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($our_teams);
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
			$this->Page_Terminate("our_teamslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in our_teams class, our_teamsinfo.php

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
		$this->name_en->setDbValue($rs->fields('name_en'));
		$this->name_ar->setDbValue($rs->fields('name_ar'));
		$this->title_en->setDbValue($rs->fields('title_en'));
		$this->title_ar->setDbValue($rs->fields('title_ar'));
		$this->bio_en->setDbValue($rs->fields('bio_en'));
		$this->bio_ar->setDbValue($rs->fields('bio_ar'));
		$this->facebook->setDbValue($rs->fields('facebook'));
		$this->twitter->setDbValue($rs->fields('twitter'));
		$this->linkedin->setDbValue($rs->fields('linkedin'));
		$this->last_modified->setDbValue($rs->fields('last_modified'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->image->Upload->DbValue = $row['image'];
		$this->name_en->DbValue = $row['name_en'];
		$this->name_ar->DbValue = $row['name_ar'];
		$this->title_en->DbValue = $row['title_en'];
		$this->title_ar->DbValue = $row['title_ar'];
		$this->bio_en->DbValue = $row['bio_en'];
		$this->bio_ar->DbValue = $row['bio_ar'];
		$this->facebook->DbValue = $row['facebook'];
		$this->twitter->DbValue = $row['twitter'];
		$this->linkedin->DbValue = $row['linkedin'];
		$this->last_modified->DbValue = $row['last_modified'];
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
		// name_en
		// name_ar
		// title_en
		// title_ar
		// bio_en
		// bio_ar
		// facebook
		// twitter
		// linkedin
		// last_modified

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// image
			$this->image->UploadPath = '../webroot/uploads/images/';
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

			// name_en
			$this->name_en->ViewValue = $this->name_en->CurrentValue;
			$this->name_en->ViewCustomAttributes = "";

			// name_ar
			$this->name_ar->ViewValue = $this->name_ar->CurrentValue;
			$this->name_ar->ViewCustomAttributes = "";

			// title_en
			$this->title_en->ViewValue = $this->title_en->CurrentValue;
			$this->title_en->ViewCustomAttributes = "";

			// title_ar
			$this->title_ar->ViewValue = $this->title_ar->CurrentValue;
			$this->title_ar->ViewCustomAttributes = "";

			// bio_en
			$this->bio_en->ViewValue = $this->bio_en->CurrentValue;
			$this->bio_en->ViewCustomAttributes = "";

			// bio_ar
			$this->bio_ar->ViewValue = $this->bio_ar->CurrentValue;
			$this->bio_ar->ViewCustomAttributes = "";

			// facebook
			$this->facebook->ViewValue = $this->facebook->CurrentValue;
			$this->facebook->ViewCustomAttributes = "";

			// twitter
			$this->twitter->ViewValue = $this->twitter->CurrentValue;
			$this->twitter->ViewCustomAttributes = "";

			// linkedin
			$this->linkedin->ViewValue = $this->linkedin->CurrentValue;
			$this->linkedin->ViewCustomAttributes = "";

			// last_modified
			$this->last_modified->ViewValue = $this->last_modified->CurrentValue;
			$this->last_modified->ViewValue = ew_FormatDateTime($this->last_modified->ViewValue, 7);
			$this->last_modified->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->UploadPath = '../webroot/uploads/images/';
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
				$this->image->LinkAttrs["data-rel"] = "our_teams_x_image";
				$this->image->LinkAttrs["class"] = "ewLightbox";
			}

			// name_en
			$this->name_en->LinkCustomAttributes = "";
			$this->name_en->HrefValue = "";
			$this->name_en->TooltipValue = "";

			// name_ar
			$this->name_ar->LinkCustomAttributes = "";
			$this->name_ar->HrefValue = "";
			$this->name_ar->TooltipValue = "";

			// title_en
			$this->title_en->LinkCustomAttributes = "";
			$this->title_en->HrefValue = "";
			$this->title_en->TooltipValue = "";

			// title_ar
			$this->title_ar->LinkCustomAttributes = "";
			$this->title_ar->HrefValue = "";
			$this->title_ar->TooltipValue = "";

			// bio_en
			$this->bio_en->LinkCustomAttributes = "";
			$this->bio_en->HrefValue = "";
			$this->bio_en->TooltipValue = "";

			// bio_ar
			$this->bio_ar->LinkCustomAttributes = "";
			$this->bio_ar->HrefValue = "";
			$this->bio_ar->TooltipValue = "";

			// facebook
			$this->facebook->LinkCustomAttributes = "";
			if (!ew_Empty($this->facebook->CurrentValue)) {
				$this->facebook->HrefValue = $this->facebook->CurrentValue; // Add prefix/suffix
				$this->facebook->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->facebook->HrefValue = ew_ConvertFullUrl($this->facebook->HrefValue);
			} else {
				$this->facebook->HrefValue = "";
			}
			$this->facebook->TooltipValue = "";

			// twitter
			$this->twitter->LinkCustomAttributes = "";
			if (!ew_Empty($this->twitter->CurrentValue)) {
				$this->twitter->HrefValue = $this->twitter->CurrentValue; // Add prefix/suffix
				$this->twitter->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->twitter->HrefValue = ew_ConvertFullUrl($this->twitter->HrefValue);
			} else {
				$this->twitter->HrefValue = "";
			}
			$this->twitter->TooltipValue = "";

			// linkedin
			$this->linkedin->LinkCustomAttributes = "";
			if (!ew_Empty($this->linkedin->CurrentValue)) {
				$this->linkedin->HrefValue = $this->linkedin->CurrentValue; // Add prefix/suffix
				$this->linkedin->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->linkedin->HrefValue = ew_ConvertFullUrl($this->linkedin->HrefValue);
			} else {
				$this->linkedin->HrefValue = "";
			}
			$this->linkedin->TooltipValue = "";

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
				$sThisKey .= $row['id'];
				$this->LoadDbValues($row);
				$this->image->OldUploadPath = '../webroot/uploads/images/';
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
		$Breadcrumb->Add("list", $this->TableVar, "our_teamslist.php", "", $this->TableVar, TRUE);
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
if (!isset($our_teams_delete)) $our_teams_delete = new cour_teams_delete();

// Page init
$our_teams_delete->Page_Init();

// Page main
$our_teams_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$our_teams_delete->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var our_teams_delete = new ew_Page("our_teams_delete");
our_teams_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = our_teams_delete.PageID; // For backward compatibility

// Form object
var four_teamsdelete = new ew_Form("four_teamsdelete");

// Form_CustomValidate event
four_teamsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
four_teamsdelete.ValidateRequired = true;
<?php } else { ?>
four_teamsdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($our_teams_delete->Recordset = $our_teams_delete->LoadRecordset())
	$our_teams_deleteTotalRecs = $our_teams_delete->Recordset->RecordCount(); // Get record count
if ($our_teams_deleteTotalRecs <= 0) { // No record found, exit
	if ($our_teams_delete->Recordset)
		$our_teams_delete->Recordset->Close();
	$our_teams_delete->Page_Terminate("our_teamslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $our_teams_delete->ShowPageHeader(); ?>
<?php
$our_teams_delete->ShowMessage();
?>
<form name="four_teamsdelete" id="four_teamsdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($our_teams_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $our_teams_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="our_teams">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($our_teams_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $our_teams->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($our_teams->id->Visible) { // id ?>
		<th><span id="elh_our_teams_id" class="our_teams_id"><?php echo $our_teams->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($our_teams->image->Visible) { // image ?>
		<th><span id="elh_our_teams_image" class="our_teams_image"><?php echo $our_teams->image->FldCaption() ?></span></th>
<?php } ?>
<?php if ($our_teams->name_en->Visible) { // name_en ?>
		<th><span id="elh_our_teams_name_en" class="our_teams_name_en"><?php echo $our_teams->name_en->FldCaption() ?></span></th>
<?php } ?>
<?php if ($our_teams->name_ar->Visible) { // name_ar ?>
		<th><span id="elh_our_teams_name_ar" class="our_teams_name_ar"><?php echo $our_teams->name_ar->FldCaption() ?></span></th>
<?php } ?>
<?php if ($our_teams->title_en->Visible) { // title_en ?>
		<th><span id="elh_our_teams_title_en" class="our_teams_title_en"><?php echo $our_teams->title_en->FldCaption() ?></span></th>
<?php } ?>
<?php if ($our_teams->title_ar->Visible) { // title_ar ?>
		<th><span id="elh_our_teams_title_ar" class="our_teams_title_ar"><?php echo $our_teams->title_ar->FldCaption() ?></span></th>
<?php } ?>
<?php if ($our_teams->bio_en->Visible) { // bio_en ?>
		<th><span id="elh_our_teams_bio_en" class="our_teams_bio_en"><?php echo $our_teams->bio_en->FldCaption() ?></span></th>
<?php } ?>
<?php if ($our_teams->bio_ar->Visible) { // bio_ar ?>
		<th><span id="elh_our_teams_bio_ar" class="our_teams_bio_ar"><?php echo $our_teams->bio_ar->FldCaption() ?></span></th>
<?php } ?>
<?php if ($our_teams->facebook->Visible) { // facebook ?>
		<th><span id="elh_our_teams_facebook" class="our_teams_facebook"><?php echo $our_teams->facebook->FldCaption() ?></span></th>
<?php } ?>
<?php if ($our_teams->twitter->Visible) { // twitter ?>
		<th><span id="elh_our_teams_twitter" class="our_teams_twitter"><?php echo $our_teams->twitter->FldCaption() ?></span></th>
<?php } ?>
<?php if ($our_teams->linkedin->Visible) { // linkedin ?>
		<th><span id="elh_our_teams_linkedin" class="our_teams_linkedin"><?php echo $our_teams->linkedin->FldCaption() ?></span></th>
<?php } ?>
<?php if ($our_teams->last_modified->Visible) { // last_modified ?>
		<th><span id="elh_our_teams_last_modified" class="our_teams_last_modified"><?php echo $our_teams->last_modified->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$our_teams_delete->RecCnt = 0;
$i = 0;
while (!$our_teams_delete->Recordset->EOF) {
	$our_teams_delete->RecCnt++;
	$our_teams_delete->RowCnt++;

	// Set row properties
	$our_teams->ResetAttrs();
	$our_teams->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$our_teams_delete->LoadRowValues($our_teams_delete->Recordset);

	// Render row
	$our_teams_delete->RenderRow();
?>
	<tr<?php echo $our_teams->RowAttributes() ?>>
<?php if ($our_teams->id->Visible) { // id ?>
		<td<?php echo $our_teams->id->CellAttributes() ?>>
<span id="el<?php echo $our_teams_delete->RowCnt ?>_our_teams_id" class="form-group our_teams_id">
<span<?php echo $our_teams->id->ViewAttributes() ?>>
<?php echo $our_teams->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($our_teams->image->Visible) { // image ?>
		<td<?php echo $our_teams->image->CellAttributes() ?>>
<span id="el<?php echo $our_teams_delete->RowCnt ?>_our_teams_image" class="form-group our_teams_image">
<span>
<?php echo ew_GetFileViewTag($our_teams->image, $our_teams->image->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($our_teams->name_en->Visible) { // name_en ?>
		<td<?php echo $our_teams->name_en->CellAttributes() ?>>
<span id="el<?php echo $our_teams_delete->RowCnt ?>_our_teams_name_en" class="form-group our_teams_name_en">
<span<?php echo $our_teams->name_en->ViewAttributes() ?>>
<?php echo $our_teams->name_en->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($our_teams->name_ar->Visible) { // name_ar ?>
		<td<?php echo $our_teams->name_ar->CellAttributes() ?>>
<span id="el<?php echo $our_teams_delete->RowCnt ?>_our_teams_name_ar" class="form-group our_teams_name_ar">
<span<?php echo $our_teams->name_ar->ViewAttributes() ?>>
<?php echo $our_teams->name_ar->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($our_teams->title_en->Visible) { // title_en ?>
		<td<?php echo $our_teams->title_en->CellAttributes() ?>>
<span id="el<?php echo $our_teams_delete->RowCnt ?>_our_teams_title_en" class="form-group our_teams_title_en">
<span<?php echo $our_teams->title_en->ViewAttributes() ?>>
<?php echo $our_teams->title_en->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($our_teams->title_ar->Visible) { // title_ar ?>
		<td<?php echo $our_teams->title_ar->CellAttributes() ?>>
<span id="el<?php echo $our_teams_delete->RowCnt ?>_our_teams_title_ar" class="form-group our_teams_title_ar">
<span<?php echo $our_teams->title_ar->ViewAttributes() ?>>
<?php echo $our_teams->title_ar->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($our_teams->bio_en->Visible) { // bio_en ?>
		<td<?php echo $our_teams->bio_en->CellAttributes() ?>>
<span id="el<?php echo $our_teams_delete->RowCnt ?>_our_teams_bio_en" class="form-group our_teams_bio_en">
<span<?php echo $our_teams->bio_en->ViewAttributes() ?>>
<?php echo $our_teams->bio_en->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($our_teams->bio_ar->Visible) { // bio_ar ?>
		<td<?php echo $our_teams->bio_ar->CellAttributes() ?>>
<span id="el<?php echo $our_teams_delete->RowCnt ?>_our_teams_bio_ar" class="form-group our_teams_bio_ar">
<span<?php echo $our_teams->bio_ar->ViewAttributes() ?>>
<?php echo $our_teams->bio_ar->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($our_teams->facebook->Visible) { // facebook ?>
		<td<?php echo $our_teams->facebook->CellAttributes() ?>>
<span id="el<?php echo $our_teams_delete->RowCnt ?>_our_teams_facebook" class="form-group our_teams_facebook">
<span<?php echo $our_teams->facebook->ViewAttributes() ?>>
<?php if (!ew_EmptyStr($our_teams->facebook->ListViewValue()) && $our_teams->facebook->LinkAttributes() <> "") { ?>
<a<?php echo $our_teams->facebook->LinkAttributes() ?>><?php echo $our_teams->facebook->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $our_teams->facebook->ListViewValue() ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($our_teams->twitter->Visible) { // twitter ?>
		<td<?php echo $our_teams->twitter->CellAttributes() ?>>
<span id="el<?php echo $our_teams_delete->RowCnt ?>_our_teams_twitter" class="form-group our_teams_twitter">
<span<?php echo $our_teams->twitter->ViewAttributes() ?>>
<?php if (!ew_EmptyStr($our_teams->twitter->ListViewValue()) && $our_teams->twitter->LinkAttributes() <> "") { ?>
<a<?php echo $our_teams->twitter->LinkAttributes() ?>><?php echo $our_teams->twitter->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $our_teams->twitter->ListViewValue() ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($our_teams->linkedin->Visible) { // linkedin ?>
		<td<?php echo $our_teams->linkedin->CellAttributes() ?>>
<span id="el<?php echo $our_teams_delete->RowCnt ?>_our_teams_linkedin" class="form-group our_teams_linkedin">
<span<?php echo $our_teams->linkedin->ViewAttributes() ?>>
<?php if (!ew_EmptyStr($our_teams->linkedin->ListViewValue()) && $our_teams->linkedin->LinkAttributes() <> "") { ?>
<a<?php echo $our_teams->linkedin->LinkAttributes() ?>><?php echo $our_teams->linkedin->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $our_teams->linkedin->ListViewValue() ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($our_teams->last_modified->Visible) { // last_modified ?>
		<td<?php echo $our_teams->last_modified->CellAttributes() ?>>
<span id="el<?php echo $our_teams_delete->RowCnt ?>_our_teams_last_modified" class="form-group our_teams_last_modified">
<span<?php echo $our_teams->last_modified->ViewAttributes() ?>>
<?php echo $our_teams->last_modified->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$our_teams_delete->Recordset->MoveNext();
}
$our_teams_delete->Recordset->Close();
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
four_teamsdelete.Init();
</script>
<?php
$our_teams_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$our_teams_delete->Page_Terminate();
?>
