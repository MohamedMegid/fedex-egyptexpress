<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "accountsinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "administratorinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$accounts_delete = NULL; // Initialize page object first

class caccounts_delete extends caccounts {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'accounts';

	// Page object name
	var $PageObjName = 'accounts_delete';

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

		// Table object (accounts)
		if (!isset($GLOBALS["accounts"]) || get_class($GLOBALS["accounts"]) == "caccounts") {
			$GLOBALS["accounts"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["accounts"];
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
			define("EW_TABLE_NAME", 'accounts', TRUE);

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
		global $EW_EXPORT, $accounts;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($accounts);
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
			$this->Page_Terminate("accountslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in accounts class, accountsinfo.php

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
		$this->avatar->Upload->DbValue = $rs->fields('avatar');
		$this->avatar->CurrentValue = $this->avatar->Upload->DbValue;
		$this->first_name->setDbValue($rs->fields('first_name'));
		$this->last_name->setDbValue($rs->fields('last_name'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->integra_account_id->setDbValue($rs->fields('integra_account_id'));
		$this->company->setDbValue($rs->fields('company'));
		$this->job_title->setDbValue($rs->fields('job_title'));
		$this->phone->setDbValue($rs->fields('phone'));
		$this->address->setDbValue($rs->fields('address'));
		$this->ship_monthly->setDbValue($rs->fields('ship_monthly'));
		$this->commercial_register->Upload->DbValue = $rs->fields('commercial_register');
		$this->commercial_register->CurrentValue = $this->commercial_register->Upload->DbValue;
		$this->password->setDbValue($rs->fields('password'));
		$this->status->setDbValue($rs->fields('status'));
		$this->created->setDbValue($rs->fields('created'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->avatar->Upload->DbValue = $row['avatar'];
		$this->first_name->DbValue = $row['first_name'];
		$this->last_name->DbValue = $row['last_name'];
		$this->_email->DbValue = $row['email'];
		$this->integra_account_id->DbValue = $row['integra_account_id'];
		$this->company->DbValue = $row['company'];
		$this->job_title->DbValue = $row['job_title'];
		$this->phone->DbValue = $row['phone'];
		$this->address->DbValue = $row['address'];
		$this->ship_monthly->DbValue = $row['ship_monthly'];
		$this->commercial_register->Upload->DbValue = $row['commercial_register'];
		$this->password->DbValue = $row['password'];
		$this->status->DbValue = $row['status'];
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
		// avatar
		// first_name
		// last_name
		// email
		// integra_account_id
		// company
		// job_title
		// phone
		// address
		// ship_monthly
		// commercial_register
		// password
		// status
		// created

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// avatar
			$this->avatar->UploadPath = '../webroot/uploads/accounts/';
			if (!ew_Empty($this->avatar->Upload->DbValue)) {
				$this->avatar->ImageWidth = 100;
				$this->avatar->ImageHeight = 0;
				$this->avatar->ImageAlt = $this->avatar->FldAlt();
				$this->avatar->ViewValue = ew_UploadPathEx(FALSE, $this->avatar->UploadPath) . $this->avatar->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->avatar->ViewValue = ew_UploadPathEx(TRUE, $this->avatar->UploadPath) . $this->avatar->Upload->DbValue;
				}
			} else {
				$this->avatar->ViewValue = "";
			}
			$this->avatar->ViewCustomAttributes = "";

			// first_name
			$this->first_name->ViewValue = $this->first_name->CurrentValue;
			$this->first_name->ViewCustomAttributes = "";

			// last_name
			$this->last_name->ViewValue = $this->last_name->CurrentValue;
			$this->last_name->ViewCustomAttributes = "";

			// email
			$this->_email->ViewValue = $this->_email->CurrentValue;
			$this->_email->ViewCustomAttributes = "";

			// integra_account_id
			$this->integra_account_id->ViewValue = $this->integra_account_id->CurrentValue;
			$this->integra_account_id->ViewCustomAttributes = "";

			// company
			$this->company->ViewValue = $this->company->CurrentValue;
			$this->company->ViewCustomAttributes = "";

			// job_title
			$this->job_title->ViewValue = $this->job_title->CurrentValue;
			$this->job_title->ViewCustomAttributes = "";

			// phone
			$this->phone->ViewValue = $this->phone->CurrentValue;
			$this->phone->ViewCustomAttributes = "";

			// address
			$this->address->ViewValue = $this->address->CurrentValue;
			$this->address->ViewCustomAttributes = "";

			// ship_monthly
			$this->ship_monthly->ViewValue = $this->ship_monthly->CurrentValue;
			$this->ship_monthly->ViewCustomAttributes = "";

			// commercial_register
			$this->commercial_register->UploadPath = '../webroot/uploads/accounts/';
			if (!ew_Empty($this->commercial_register->Upload->DbValue)) {
				$this->commercial_register->ViewValue = $this->commercial_register->Upload->DbValue;
			} else {
				$this->commercial_register->ViewValue = "";
			}
			$this->commercial_register->ViewCustomAttributes = "";

			// password
			$this->password->ViewValue = $this->password->CurrentValue;
			$this->password->ViewCustomAttributes = "";

			// status
			if (strval($this->status->CurrentValue) <> "") {
				switch ($this->status->CurrentValue) {
					case $this->status->FldTagValue(1):
						$this->status->ViewValue = $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : $this->status->CurrentValue;
						break;
					case $this->status->FldTagValue(2):
						$this->status->ViewValue = $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : $this->status->CurrentValue;
						break;
					case $this->status->FldTagValue(3):
						$this->status->ViewValue = $this->status->FldTagCaption(3) <> "" ? $this->status->FldTagCaption(3) : $this->status->CurrentValue;
						break;
					default:
						$this->status->ViewValue = $this->status->CurrentValue;
				}
			} else {
				$this->status->ViewValue = NULL;
			}
			$this->status->ViewCustomAttributes = "";

			// created
			$this->created->ViewValue = $this->created->CurrentValue;
			$this->created->ViewValue = ew_FormatDateTime($this->created->ViewValue, 7);
			$this->created->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// avatar
			$this->avatar->LinkCustomAttributes = "";
			$this->avatar->UploadPath = '../webroot/uploads/accounts/';
			if (!ew_Empty($this->avatar->Upload->DbValue)) {
				$this->avatar->HrefValue = ew_UploadPathEx(FALSE, $this->avatar->UploadPath) . $this->avatar->Upload->DbValue; // Add prefix/suffix
				$this->avatar->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->avatar->HrefValue = ew_ConvertFullUrl($this->avatar->HrefValue);
			} else {
				$this->avatar->HrefValue = "";
			}
			$this->avatar->HrefValue2 = $this->avatar->UploadPath . $this->avatar->Upload->DbValue;
			$this->avatar->TooltipValue = "";
			if ($this->avatar->UseColorbox) {
				$this->avatar->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->avatar->LinkAttrs["data-rel"] = "accounts_x_avatar";
				$this->avatar->LinkAttrs["class"] = "ewLightbox";
			}

			// first_name
			$this->first_name->LinkCustomAttributes = "";
			$this->first_name->HrefValue = "";
			$this->first_name->TooltipValue = "";

			// last_name
			$this->last_name->LinkCustomAttributes = "";
			$this->last_name->HrefValue = "";
			$this->last_name->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// integra_account_id
			$this->integra_account_id->LinkCustomAttributes = "";
			$this->integra_account_id->HrefValue = "";
			$this->integra_account_id->TooltipValue = "";

			// company
			$this->company->LinkCustomAttributes = "";
			$this->company->HrefValue = "";
			$this->company->TooltipValue = "";

			// job_title
			$this->job_title->LinkCustomAttributes = "";
			$this->job_title->HrefValue = "";
			$this->job_title->TooltipValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";
			$this->phone->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

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
				$this->avatar->OldUploadPath = '../webroot/uploads/accounts/';
				@unlink(ew_UploadPathEx(TRUE, $this->avatar->OldUploadPath) . $row['avatar']);
				$this->commercial_register->OldUploadPath = '../webroot/uploads/accounts/';
				@unlink(ew_UploadPathEx(TRUE, $this->commercial_register->OldUploadPath) . $row['commercial_register']);
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
		$Breadcrumb->Add("list", $this->TableVar, "accountslist.php", "", $this->TableVar, TRUE);
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
if (!isset($accounts_delete)) $accounts_delete = new caccounts_delete();

// Page init
$accounts_delete->Page_Init();

// Page main
$accounts_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$accounts_delete->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var accounts_delete = new ew_Page("accounts_delete");
accounts_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = accounts_delete.PageID; // For backward compatibility

// Form object
var faccountsdelete = new ew_Form("faccountsdelete");

// Form_CustomValidate event
faccountsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
faccountsdelete.ValidateRequired = true;
<?php } else { ?>
faccountsdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($accounts_delete->Recordset = $accounts_delete->LoadRecordset())
	$accounts_deleteTotalRecs = $accounts_delete->Recordset->RecordCount(); // Get record count
if ($accounts_deleteTotalRecs <= 0) { // No record found, exit
	if ($accounts_delete->Recordset)
		$accounts_delete->Recordset->Close();
	$accounts_delete->Page_Terminate("accountslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $accounts_delete->ShowPageHeader(); ?>
<?php
$accounts_delete->ShowMessage();
?>
<form name="faccountsdelete" id="faccountsdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($accounts_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $accounts_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="accounts">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($accounts_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $accounts->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($accounts->id->Visible) { // id ?>
		<th><span id="elh_accounts_id" class="accounts_id"><?php echo $accounts->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($accounts->avatar->Visible) { // avatar ?>
		<th><span id="elh_accounts_avatar" class="accounts_avatar"><?php echo $accounts->avatar->FldCaption() ?></span></th>
<?php } ?>
<?php if ($accounts->first_name->Visible) { // first_name ?>
		<th><span id="elh_accounts_first_name" class="accounts_first_name"><?php echo $accounts->first_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($accounts->last_name->Visible) { // last_name ?>
		<th><span id="elh_accounts_last_name" class="accounts_last_name"><?php echo $accounts->last_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($accounts->_email->Visible) { // email ?>
		<th><span id="elh_accounts__email" class="accounts__email"><?php echo $accounts->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($accounts->integra_account_id->Visible) { // integra_account_id ?>
		<th><span id="elh_accounts_integra_account_id" class="accounts_integra_account_id"><?php echo $accounts->integra_account_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($accounts->company->Visible) { // company ?>
		<th><span id="elh_accounts_company" class="accounts_company"><?php echo $accounts->company->FldCaption() ?></span></th>
<?php } ?>
<?php if ($accounts->job_title->Visible) { // job_title ?>
		<th><span id="elh_accounts_job_title" class="accounts_job_title"><?php echo $accounts->job_title->FldCaption() ?></span></th>
<?php } ?>
<?php if ($accounts->phone->Visible) { // phone ?>
		<th><span id="elh_accounts_phone" class="accounts_phone"><?php echo $accounts->phone->FldCaption() ?></span></th>
<?php } ?>
<?php if ($accounts->status->Visible) { // status ?>
		<th><span id="elh_accounts_status" class="accounts_status"><?php echo $accounts->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($accounts->created->Visible) { // created ?>
		<th><span id="elh_accounts_created" class="accounts_created"><?php echo $accounts->created->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$accounts_delete->RecCnt = 0;
$i = 0;
while (!$accounts_delete->Recordset->EOF) {
	$accounts_delete->RecCnt++;
	$accounts_delete->RowCnt++;

	// Set row properties
	$accounts->ResetAttrs();
	$accounts->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$accounts_delete->LoadRowValues($accounts_delete->Recordset);

	// Render row
	$accounts_delete->RenderRow();
?>
	<tr<?php echo $accounts->RowAttributes() ?>>
<?php if ($accounts->id->Visible) { // id ?>
		<td<?php echo $accounts->id->CellAttributes() ?>>
<span id="el<?php echo $accounts_delete->RowCnt ?>_accounts_id" class="form-group accounts_id">
<span<?php echo $accounts->id->ViewAttributes() ?>>
<?php echo $accounts->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($accounts->avatar->Visible) { // avatar ?>
		<td<?php echo $accounts->avatar->CellAttributes() ?>>
<span id="el<?php echo $accounts_delete->RowCnt ?>_accounts_avatar" class="form-group accounts_avatar">
<span>
<?php echo ew_GetFileViewTag($accounts->avatar, $accounts->avatar->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($accounts->first_name->Visible) { // first_name ?>
		<td<?php echo $accounts->first_name->CellAttributes() ?>>
<span id="el<?php echo $accounts_delete->RowCnt ?>_accounts_first_name" class="form-group accounts_first_name">
<span<?php echo $accounts->first_name->ViewAttributes() ?>>
<?php echo $accounts->first_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($accounts->last_name->Visible) { // last_name ?>
		<td<?php echo $accounts->last_name->CellAttributes() ?>>
<span id="el<?php echo $accounts_delete->RowCnt ?>_accounts_last_name" class="form-group accounts_last_name">
<span<?php echo $accounts->last_name->ViewAttributes() ?>>
<?php echo $accounts->last_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($accounts->_email->Visible) { // email ?>
		<td<?php echo $accounts->_email->CellAttributes() ?>>
<span id="el<?php echo $accounts_delete->RowCnt ?>_accounts__email" class="form-group accounts__email">
<span<?php echo $accounts->_email->ViewAttributes() ?>>
<?php echo $accounts->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($accounts->integra_account_id->Visible) { // integra_account_id ?>
		<td<?php echo $accounts->integra_account_id->CellAttributes() ?>>
<span id="el<?php echo $accounts_delete->RowCnt ?>_accounts_integra_account_id" class="form-group accounts_integra_account_id">
<span<?php echo $accounts->integra_account_id->ViewAttributes() ?>>
<?php echo $accounts->integra_account_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($accounts->company->Visible) { // company ?>
		<td<?php echo $accounts->company->CellAttributes() ?>>
<span id="el<?php echo $accounts_delete->RowCnt ?>_accounts_company" class="form-group accounts_company">
<span<?php echo $accounts->company->ViewAttributes() ?>>
<?php echo $accounts->company->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($accounts->job_title->Visible) { // job_title ?>
		<td<?php echo $accounts->job_title->CellAttributes() ?>>
<span id="el<?php echo $accounts_delete->RowCnt ?>_accounts_job_title" class="form-group accounts_job_title">
<span<?php echo $accounts->job_title->ViewAttributes() ?>>
<?php echo $accounts->job_title->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($accounts->phone->Visible) { // phone ?>
		<td<?php echo $accounts->phone->CellAttributes() ?>>
<span id="el<?php echo $accounts_delete->RowCnt ?>_accounts_phone" class="form-group accounts_phone">
<span<?php echo $accounts->phone->ViewAttributes() ?>>
<?php echo $accounts->phone->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($accounts->status->Visible) { // status ?>
		<td<?php echo $accounts->status->CellAttributes() ?>>
<span id="el<?php echo $accounts_delete->RowCnt ?>_accounts_status" class="form-group accounts_status">
<span<?php echo $accounts->status->ViewAttributes() ?>>
<?php echo $accounts->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($accounts->created->Visible) { // created ?>
		<td<?php echo $accounts->created->CellAttributes() ?>>
<span id="el<?php echo $accounts_delete->RowCnt ?>_accounts_created" class="form-group accounts_created">
<span<?php echo $accounts->created->ViewAttributes() ?>>
<?php echo $accounts->created->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$accounts_delete->Recordset->MoveNext();
}
$accounts_delete->Recordset->Close();
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
faccountsdelete.Init();
</script>
<?php
$accounts_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$accounts_delete->Page_Terminate();
?>
