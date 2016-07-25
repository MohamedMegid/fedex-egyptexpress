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

$accounts_add = NULL; // Initialize page object first

class caccounts_add extends caccounts {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'accounts';

	// Page object name
	var $PageObjName = 'accounts_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
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
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("accountslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "accountsview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->avatar->Upload->Index = $objForm->Index;
		$this->avatar->Upload->UploadFile();
		$this->avatar->CurrentValue = $this->avatar->Upload->FileName;
		$this->commercial_register->Upload->Index = $objForm->Index;
		$this->commercial_register->Upload->UploadFile();
		$this->commercial_register->CurrentValue = $this->commercial_register->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->avatar->Upload->DbValue = NULL;
		$this->avatar->OldValue = $this->avatar->Upload->DbValue;
		$this->avatar->CurrentValue = NULL; // Clear file related field
		$this->first_name->CurrentValue = NULL;
		$this->first_name->OldValue = $this->first_name->CurrentValue;
		$this->last_name->CurrentValue = NULL;
		$this->last_name->OldValue = $this->last_name->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->integra_account_id->CurrentValue = NULL;
		$this->integra_account_id->OldValue = $this->integra_account_id->CurrentValue;
		$this->company->CurrentValue = NULL;
		$this->company->OldValue = $this->company->CurrentValue;
		$this->job_title->CurrentValue = NULL;
		$this->job_title->OldValue = $this->job_title->CurrentValue;
		$this->phone->CurrentValue = NULL;
		$this->phone->OldValue = $this->phone->CurrentValue;
		$this->address->CurrentValue = NULL;
		$this->address->OldValue = $this->address->CurrentValue;
		$this->ship_monthly->CurrentValue = NULL;
		$this->ship_monthly->OldValue = $this->ship_monthly->CurrentValue;
		$this->commercial_register->Upload->DbValue = NULL;
		$this->commercial_register->OldValue = $this->commercial_register->Upload->DbValue;
		$this->commercial_register->CurrentValue = NULL; // Clear file related field
		$this->status->CurrentValue = "pending";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->first_name->FldIsDetailKey) {
			$this->first_name->setFormValue($objForm->GetValue("x_first_name"));
		}
		if (!$this->last_name->FldIsDetailKey) {
			$this->last_name->setFormValue($objForm->GetValue("x_last_name"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->integra_account_id->FldIsDetailKey) {
			$this->integra_account_id->setFormValue($objForm->GetValue("x_integra_account_id"));
		}
		if (!$this->company->FldIsDetailKey) {
			$this->company->setFormValue($objForm->GetValue("x_company"));
		}
		if (!$this->job_title->FldIsDetailKey) {
			$this->job_title->setFormValue($objForm->GetValue("x_job_title"));
		}
		if (!$this->phone->FldIsDetailKey) {
			$this->phone->setFormValue($objForm->GetValue("x_phone"));
		}
		if (!$this->address->FldIsDetailKey) {
			$this->address->setFormValue($objForm->GetValue("x_address"));
		}
		if (!$this->ship_monthly->FldIsDetailKey) {
			$this->ship_monthly->setFormValue($objForm->GetValue("x_ship_monthly"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->first_name->CurrentValue = $this->first_name->FormValue;
		$this->last_name->CurrentValue = $this->last_name->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->integra_account_id->CurrentValue = $this->integra_account_id->FormValue;
		$this->company->CurrentValue = $this->company->FormValue;
		$this->job_title->CurrentValue = $this->job_title->FormValue;
		$this->phone->CurrentValue = $this->phone->FormValue;
		$this->address->CurrentValue = $this->address->FormValue;
		$this->ship_monthly->CurrentValue = $this->ship_monthly->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";
			$this->address->TooltipValue = "";

			// ship_monthly
			$this->ship_monthly->LinkCustomAttributes = "";
			$this->ship_monthly->HrefValue = "";
			$this->ship_monthly->TooltipValue = "";

			// commercial_register
			$this->commercial_register->LinkCustomAttributes = "";
			$this->commercial_register->UploadPath = '../webroot/uploads/accounts/';
			if (!ew_Empty($this->commercial_register->Upload->DbValue)) {
				$this->commercial_register->HrefValue = ew_UploadPathEx(FALSE, $this->commercial_register->UploadPath) . $this->commercial_register->Upload->DbValue; // Add prefix/suffix
				$this->commercial_register->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->commercial_register->HrefValue = ew_ConvertFullUrl($this->commercial_register->HrefValue);
			} else {
				$this->commercial_register->HrefValue = "";
			}
			$this->commercial_register->HrefValue2 = $this->commercial_register->UploadPath . $this->commercial_register->Upload->DbValue;
			$this->commercial_register->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// avatar
			$this->avatar->EditAttrs["class"] = "form-control";
			$this->avatar->EditCustomAttributes = "";
			$this->avatar->UploadPath = '../webroot/uploads/accounts/';
			if (!ew_Empty($this->avatar->Upload->DbValue)) {
				$this->avatar->ImageWidth = 100;
				$this->avatar->ImageHeight = 0;
				$this->avatar->ImageAlt = $this->avatar->FldAlt();
				$this->avatar->EditValue = ew_UploadPathEx(FALSE, $this->avatar->UploadPath) . $this->avatar->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->avatar->EditValue = ew_UploadPathEx(TRUE, $this->avatar->UploadPath) . $this->avatar->Upload->DbValue;
				}
			} else {
				$this->avatar->EditValue = "";
			}
			if (!ew_Empty($this->avatar->CurrentValue))
				$this->avatar->Upload->FileName = $this->avatar->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->avatar);

			// first_name
			$this->first_name->EditAttrs["class"] = "form-control";
			$this->first_name->EditCustomAttributes = "";
			$this->first_name->EditValue = ew_HtmlEncode($this->first_name->CurrentValue);
			$this->first_name->PlaceHolder = ew_RemoveHtml($this->first_name->FldCaption());

			// last_name
			$this->last_name->EditAttrs["class"] = "form-control";
			$this->last_name->EditCustomAttributes = "";
			$this->last_name->EditValue = ew_HtmlEncode($this->last_name->CurrentValue);
			$this->last_name->PlaceHolder = ew_RemoveHtml($this->last_name->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// integra_account_id
			$this->integra_account_id->EditAttrs["class"] = "form-control";
			$this->integra_account_id->EditCustomAttributes = "";
			$this->integra_account_id->EditValue = ew_HtmlEncode($this->integra_account_id->CurrentValue);
			$this->integra_account_id->PlaceHolder = ew_RemoveHtml($this->integra_account_id->FldCaption());

			// company
			$this->company->EditAttrs["class"] = "form-control";
			$this->company->EditCustomAttributes = "";
			$this->company->EditValue = ew_HtmlEncode($this->company->CurrentValue);
			$this->company->PlaceHolder = ew_RemoveHtml($this->company->FldCaption());

			// job_title
			$this->job_title->EditAttrs["class"] = "form-control";
			$this->job_title->EditCustomAttributes = "";
			$this->job_title->EditValue = ew_HtmlEncode($this->job_title->CurrentValue);
			$this->job_title->PlaceHolder = ew_RemoveHtml($this->job_title->FldCaption());

			// phone
			$this->phone->EditAttrs["class"] = "form-control";
			$this->phone->EditCustomAttributes = "";
			$this->phone->EditValue = ew_HtmlEncode($this->phone->CurrentValue);
			$this->phone->PlaceHolder = ew_RemoveHtml($this->phone->FldCaption());

			// address
			$this->address->EditAttrs["class"] = "form-control";
			$this->address->EditCustomAttributes = "";
			$this->address->EditValue = ew_HtmlEncode($this->address->CurrentValue);
			$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

			// ship_monthly
			$this->ship_monthly->EditAttrs["class"] = "form-control";
			$this->ship_monthly->EditCustomAttributes = "";
			$this->ship_monthly->EditValue = ew_HtmlEncode($this->ship_monthly->CurrentValue);
			$this->ship_monthly->PlaceHolder = ew_RemoveHtml($this->ship_monthly->FldCaption());

			// commercial_register
			$this->commercial_register->EditAttrs["class"] = "form-control";
			$this->commercial_register->EditCustomAttributes = "";
			$this->commercial_register->UploadPath = '../webroot/uploads/accounts/';
			if (!ew_Empty($this->commercial_register->Upload->DbValue)) {
				$this->commercial_register->EditValue = $this->commercial_register->Upload->DbValue;
			} else {
				$this->commercial_register->EditValue = "";
			}
			if (!ew_Empty($this->commercial_register->CurrentValue))
				$this->commercial_register->Upload->FileName = $this->commercial_register->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->commercial_register);

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->status->FldTagValue(1), $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : $this->status->FldTagValue(1));
			$arwrk[] = array($this->status->FldTagValue(2), $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : $this->status->FldTagValue(2));
			$arwrk[] = array($this->status->FldTagValue(3), $this->status->FldTagCaption(3) <> "" ? $this->status->FldTagCaption(3) : $this->status->FldTagValue(3));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->status->EditValue = $arwrk;

			// Edit refer script
			// avatar

			$this->avatar->UploadPath = '../webroot/uploads/accounts/';
			if (!ew_Empty($this->avatar->Upload->DbValue)) {
				$this->avatar->HrefValue = ew_UploadPathEx(FALSE, $this->avatar->UploadPath) . $this->avatar->Upload->DbValue; // Add prefix/suffix
				$this->avatar->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->avatar->HrefValue = ew_ConvertFullUrl($this->avatar->HrefValue);
			} else {
				$this->avatar->HrefValue = "";
			}
			$this->avatar->HrefValue2 = $this->avatar->UploadPath . $this->avatar->Upload->DbValue;

			// first_name
			$this->first_name->HrefValue = "";

			// last_name
			$this->last_name->HrefValue = "";

			// email
			$this->_email->HrefValue = "";

			// integra_account_id
			$this->integra_account_id->HrefValue = "";

			// company
			$this->company->HrefValue = "";

			// job_title
			$this->job_title->HrefValue = "";

			// phone
			$this->phone->HrefValue = "";

			// address
			$this->address->HrefValue = "";

			// ship_monthly
			$this->ship_monthly->HrefValue = "";

			// commercial_register
			$this->commercial_register->UploadPath = '../webroot/uploads/accounts/';
			if (!ew_Empty($this->commercial_register->Upload->DbValue)) {
				$this->commercial_register->HrefValue = ew_UploadPathEx(FALSE, $this->commercial_register->UploadPath) . $this->commercial_register->Upload->DbValue; // Add prefix/suffix
				$this->commercial_register->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->commercial_register->HrefValue = ew_ConvertFullUrl($this->commercial_register->HrefValue);
			} else {
				$this->commercial_register->HrefValue = "";
			}
			$this->commercial_register->HrefValue2 = $this->commercial_register->UploadPath . $this->commercial_register->Upload->DbValue;

			// status
			$this->status->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->first_name->FldIsDetailKey && !is_null($this->first_name->FormValue) && $this->first_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->first_name->FldCaption(), $this->first_name->ReqErrMsg));
		}
		if (!$this->last_name->FldIsDetailKey && !is_null($this->last_name->FormValue) && $this->last_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->last_name->FldCaption(), $this->last_name->ReqErrMsg));
		}
		if (!$this->_email->FldIsDetailKey && !is_null($this->_email->FormValue) && $this->_email->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_email->FldCaption(), $this->_email->ReqErrMsg));
		}
		if (!$this->company->FldIsDetailKey && !is_null($this->company->FormValue) && $this->company->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->company->FldCaption(), $this->company->ReqErrMsg));
		}
		if (!$this->job_title->FldIsDetailKey && !is_null($this->job_title->FormValue) && $this->job_title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->job_title->FldCaption(), $this->job_title->ReqErrMsg));
		}
		if (!$this->phone->FldIsDetailKey && !is_null($this->phone->FormValue) && $this->phone->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->phone->FldCaption(), $this->phone->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->phone->FormValue)) {
			ew_AddMessage($gsFormError, $this->phone->FldErrMsg());
		}
		if (!$this->address->FldIsDetailKey && !is_null($this->address->FormValue) && $this->address->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->address->FldCaption(), $this->address->ReqErrMsg));
		}
		if (!$this->ship_monthly->FldIsDetailKey && !is_null($this->ship_monthly->FormValue) && $this->ship_monthly->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ship_monthly->FldCaption(), $this->ship_monthly->ReqErrMsg));
		}
		if ($this->commercial_register->Upload->FileName == "" && !$this->commercial_register->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->commercial_register->FldCaption(), $this->commercial_register->ReqErrMsg));
		}
		if (!$this->status->FldIsDetailKey && !is_null($this->status->FormValue) && $this->status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status->FldCaption(), $this->status->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
			$this->avatar->OldUploadPath = '../webroot/uploads/accounts/';
			$this->avatar->UploadPath = $this->avatar->OldUploadPath;
			$this->commercial_register->OldUploadPath = '../webroot/uploads/accounts/';
			$this->commercial_register->UploadPath = $this->commercial_register->OldUploadPath;
		}
		$rsnew = array();

		// avatar
		if (!(strval($this->avatar->CurrentValue) == "") && !$this->avatar->Upload->KeepFile) {
			$this->avatar->Upload->DbValue = ""; // No need to delete old file
			if ($this->avatar->Upload->FileName == "") {
				$rsnew['avatar'] = NULL;
			} else {
				$rsnew['avatar'] = $this->avatar->Upload->FileName;
			}
		}

		// first_name
		$this->first_name->SetDbValueDef($rsnew, $this->first_name->CurrentValue, "", FALSE);

		// last_name
		$this->last_name->SetDbValueDef($rsnew, $this->last_name->CurrentValue, "", FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, "", FALSE);

		// integra_account_id
		$this->integra_account_id->SetDbValueDef($rsnew, $this->integra_account_id->CurrentValue, NULL, FALSE);

		// company
		$this->company->SetDbValueDef($rsnew, $this->company->CurrentValue, "", FALSE);

		// job_title
		$this->job_title->SetDbValueDef($rsnew, $this->job_title->CurrentValue, "", FALSE);

		// phone
		$this->phone->SetDbValueDef($rsnew, $this->phone->CurrentValue, 0, FALSE);

		// address
		$this->address->SetDbValueDef($rsnew, $this->address->CurrentValue, "", FALSE);

		// ship_monthly
		$this->ship_monthly->SetDbValueDef($rsnew, $this->ship_monthly->CurrentValue, "", FALSE);

		// commercial_register
		if (!$this->commercial_register->Upload->KeepFile) {
			$this->commercial_register->Upload->DbValue = ""; // No need to delete old file
			if ($this->commercial_register->Upload->FileName == "") {
				$rsnew['commercial_register'] = NULL;
			} else {
				$rsnew['commercial_register'] = $this->commercial_register->Upload->FileName;
			}
		}

		// status
		$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, "", strval($this->status->CurrentValue) == "");
		if (!$this->avatar->Upload->KeepFile) {
			$this->avatar->UploadPath = '../webroot/uploads/accounts/';
			if (!ew_Empty($this->avatar->Upload->Value)) {
				if ($this->avatar->Upload->FileName == $this->avatar->Upload->DbValue) { // Overwrite if same file name
					$this->avatar->Upload->DbValue = ""; // No need to delete any more
				} else {
					$rsnew['avatar'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->avatar->UploadPath), $rsnew['avatar']); // Get new file name
				}
			}
		}
		if (!$this->commercial_register->Upload->KeepFile) {
			$this->commercial_register->UploadPath = '../webroot/uploads/accounts/';
			if (!ew_Empty($this->commercial_register->Upload->Value)) {
				if ($this->commercial_register->Upload->FileName == $this->commercial_register->Upload->DbValue) { // Overwrite if same file name
					$this->commercial_register->Upload->DbValue = ""; // No need to delete any more
				} else {
					$rsnew['commercial_register'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->commercial_register->UploadPath), $rsnew['commercial_register']); // Get new file name
				}
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if (!$this->avatar->Upload->KeepFile) {
					if (!ew_Empty($this->avatar->Upload->Value)) {
						$this->avatar->Upload->SaveToFile($this->avatar->UploadPath, $rsnew['avatar'], TRUE);
					}
					if ($this->avatar->Upload->DbValue <> "")
						@unlink(ew_UploadPathEx(TRUE, $this->avatar->OldUploadPath) . $this->avatar->Upload->DbValue);
				}
				if (!$this->commercial_register->Upload->KeepFile) {
					if (!ew_Empty($this->commercial_register->Upload->Value)) {
						$this->commercial_register->Upload->SaveToFile($this->commercial_register->UploadPath, $rsnew['commercial_register'], TRUE);
					}
					if ($this->commercial_register->Upload->DbValue <> "")
						@unlink(ew_UploadPathEx(TRUE, $this->commercial_register->OldUploadPath) . $this->commercial_register->Upload->DbValue);
				}
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->id->setDbValue($conn->Insert_ID());
			$rsnew['id'] = $this->id->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// avatar
		ew_CleanUploadTempPath($this->avatar, $this->avatar->Upload->Index);

		// commercial_register
		ew_CleanUploadTempPath($this->commercial_register, $this->commercial_register->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "accountslist.php", "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, ew_CurrentUrl());
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($accounts_add)) $accounts_add = new caccounts_add();

// Page init
$accounts_add->Page_Init();

// Page main
$accounts_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$accounts_add->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var accounts_add = new ew_Page("accounts_add");
accounts_add.PageID = "add"; // Page ID
var EW_PAGE_ID = accounts_add.PageID; // For backward compatibility

// Form object
var faccountsadd = new ew_Form("faccountsadd");

// Validate form
faccountsadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_first_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $accounts->first_name->FldCaption(), $accounts->first_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_last_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $accounts->last_name->FldCaption(), $accounts->last_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $accounts->_email->FldCaption(), $accounts->_email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_company");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $accounts->company->FldCaption(), $accounts->company->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_job_title");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $accounts->job_title->FldCaption(), $accounts->job_title->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_phone");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $accounts->phone->FldCaption(), $accounts->phone->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_phone");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($accounts->phone->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_address");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $accounts->address->FldCaption(), $accounts->address->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ship_monthly");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $accounts->ship_monthly->FldCaption(), $accounts->ship_monthly->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_commercial_register");
			elm = this.GetElements("fn_x" + infix + "_commercial_register");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $accounts->commercial_register->FldCaption(), $accounts->commercial_register->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $accounts->status->FldCaption(), $accounts->status->ReqErrMsg)) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
faccountsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
faccountsadd.ValidateRequired = true;
<?php } else { ?>
faccountsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $accounts_add->ShowPageHeader(); ?>
<?php
$accounts_add->ShowMessage();
?>
<form name="faccountsadd" id="faccountsadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($accounts_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $accounts_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="accounts">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($accounts->avatar->Visible) { // avatar ?>
	<div id="r_avatar" class="form-group">
		<label id="elh_accounts_avatar" class="col-sm-2 control-label ewLabel"><?php echo $accounts->avatar->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $accounts->avatar->CellAttributes() ?>>
<span id="el_accounts_avatar">
<div id="fd_x_avatar">
<span title="<?php echo $accounts->avatar->FldTitle() ? $accounts->avatar->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($accounts->avatar->ReadOnly || $accounts->avatar->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_avatar" name="x_avatar" id="x_avatar">
</span>
<input type="hidden" name="fn_x_avatar" id= "fn_x_avatar" value="<?php echo $accounts->avatar->Upload->FileName ?>">
<input type="hidden" name="fa_x_avatar" id= "fa_x_avatar" value="0">
<input type="hidden" name="fs_x_avatar" id= "fs_x_avatar" value="255">
<input type="hidden" name="fx_x_avatar" id= "fx_x_avatar" value="<?php echo $accounts->avatar->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_avatar" id= "fm_x_avatar" value="<?php echo $accounts->avatar->UploadMaxFileSize ?>">
</div>
<table id="ft_x_avatar" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $accounts->avatar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($accounts->first_name->Visible) { // first_name ?>
	<div id="r_first_name" class="form-group">
		<label id="elh_accounts_first_name" for="x_first_name" class="col-sm-2 control-label ewLabel"><?php echo $accounts->first_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $accounts->first_name->CellAttributes() ?>>
<span id="el_accounts_first_name">
<input type="text" data-field="x_first_name" name="x_first_name" id="x_first_name" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($accounts->first_name->PlaceHolder) ?>" value="<?php echo $accounts->first_name->EditValue ?>"<?php echo $accounts->first_name->EditAttributes() ?>>
</span>
<?php echo $accounts->first_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($accounts->last_name->Visible) { // last_name ?>
	<div id="r_last_name" class="form-group">
		<label id="elh_accounts_last_name" for="x_last_name" class="col-sm-2 control-label ewLabel"><?php echo $accounts->last_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $accounts->last_name->CellAttributes() ?>>
<span id="el_accounts_last_name">
<input type="text" data-field="x_last_name" name="x_last_name" id="x_last_name" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($accounts->last_name->PlaceHolder) ?>" value="<?php echo $accounts->last_name->EditValue ?>"<?php echo $accounts->last_name->EditAttributes() ?>>
</span>
<?php echo $accounts->last_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($accounts->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_accounts__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $accounts->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $accounts->_email->CellAttributes() ?>>
<span id="el_accounts__email">
<input type="text" data-field="x__email" name="x__email" id="x__email" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($accounts->_email->PlaceHolder) ?>" value="<?php echo $accounts->_email->EditValue ?>"<?php echo $accounts->_email->EditAttributes() ?>>
</span>
<?php echo $accounts->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($accounts->integra_account_id->Visible) { // integra_account_id ?>
	<div id="r_integra_account_id" class="form-group">
		<label id="elh_accounts_integra_account_id" for="x_integra_account_id" class="col-sm-2 control-label ewLabel"><?php echo $accounts->integra_account_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $accounts->integra_account_id->CellAttributes() ?>>
<span id="el_accounts_integra_account_id">
<input type="text" data-field="x_integra_account_id" name="x_integra_account_id" id="x_integra_account_id" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($accounts->integra_account_id->PlaceHolder) ?>" value="<?php echo $accounts->integra_account_id->EditValue ?>"<?php echo $accounts->integra_account_id->EditAttributes() ?>>
</span>
<?php echo $accounts->integra_account_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($accounts->company->Visible) { // company ?>
	<div id="r_company" class="form-group">
		<label id="elh_accounts_company" for="x_company" class="col-sm-2 control-label ewLabel"><?php echo $accounts->company->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $accounts->company->CellAttributes() ?>>
<span id="el_accounts_company">
<input type="text" data-field="x_company" name="x_company" id="x_company" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($accounts->company->PlaceHolder) ?>" value="<?php echo $accounts->company->EditValue ?>"<?php echo $accounts->company->EditAttributes() ?>>
</span>
<?php echo $accounts->company->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($accounts->job_title->Visible) { // job_title ?>
	<div id="r_job_title" class="form-group">
		<label id="elh_accounts_job_title" for="x_job_title" class="col-sm-2 control-label ewLabel"><?php echo $accounts->job_title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $accounts->job_title->CellAttributes() ?>>
<span id="el_accounts_job_title">
<input type="text" data-field="x_job_title" name="x_job_title" id="x_job_title" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($accounts->job_title->PlaceHolder) ?>" value="<?php echo $accounts->job_title->EditValue ?>"<?php echo $accounts->job_title->EditAttributes() ?>>
</span>
<?php echo $accounts->job_title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($accounts->phone->Visible) { // phone ?>
	<div id="r_phone" class="form-group">
		<label id="elh_accounts_phone" for="x_phone" class="col-sm-2 control-label ewLabel"><?php echo $accounts->phone->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $accounts->phone->CellAttributes() ?>>
<span id="el_accounts_phone">
<input type="text" data-field="x_phone" name="x_phone" id="x_phone" size="70" placeholder="<?php echo ew_HtmlEncode($accounts->phone->PlaceHolder) ?>" value="<?php echo $accounts->phone->EditValue ?>"<?php echo $accounts->phone->EditAttributes() ?>>
</span>
<?php echo $accounts->phone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($accounts->address->Visible) { // address ?>
	<div id="r_address" class="form-group">
		<label id="elh_accounts_address" for="x_address" class="col-sm-2 control-label ewLabel"><?php echo $accounts->address->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $accounts->address->CellAttributes() ?>>
<span id="el_accounts_address">
<input type="text" data-field="x_address" name="x_address" id="x_address" size="110" maxlength="255" placeholder="<?php echo ew_HtmlEncode($accounts->address->PlaceHolder) ?>" value="<?php echo $accounts->address->EditValue ?>"<?php echo $accounts->address->EditAttributes() ?>>
</span>
<?php echo $accounts->address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($accounts->ship_monthly->Visible) { // ship_monthly ?>
	<div id="r_ship_monthly" class="form-group">
		<label id="elh_accounts_ship_monthly" for="x_ship_monthly" class="col-sm-2 control-label ewLabel"><?php echo $accounts->ship_monthly->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $accounts->ship_monthly->CellAttributes() ?>>
<span id="el_accounts_ship_monthly">
<input type="text" data-field="x_ship_monthly" name="x_ship_monthly" id="x_ship_monthly" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($accounts->ship_monthly->PlaceHolder) ?>" value="<?php echo $accounts->ship_monthly->EditValue ?>"<?php echo $accounts->ship_monthly->EditAttributes() ?>>
</span>
<?php echo $accounts->ship_monthly->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($accounts->commercial_register->Visible) { // commercial_register ?>
	<div id="r_commercial_register" class="form-group">
		<label id="elh_accounts_commercial_register" class="col-sm-2 control-label ewLabel"><?php echo $accounts->commercial_register->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $accounts->commercial_register->CellAttributes() ?>>
<span id="el_accounts_commercial_register">
<div id="fd_x_commercial_register">
<span title="<?php echo $accounts->commercial_register->FldTitle() ? $accounts->commercial_register->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($accounts->commercial_register->ReadOnly || $accounts->commercial_register->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_commercial_register" name="x_commercial_register" id="x_commercial_register">
</span>
<input type="hidden" name="fn_x_commercial_register" id= "fn_x_commercial_register" value="<?php echo $accounts->commercial_register->Upload->FileName ?>">
<input type="hidden" name="fa_x_commercial_register" id= "fa_x_commercial_register" value="0">
<input type="hidden" name="fs_x_commercial_register" id= "fs_x_commercial_register" value="255">
<input type="hidden" name="fx_x_commercial_register" id= "fx_x_commercial_register" value="<?php echo $accounts->commercial_register->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_commercial_register" id= "fm_x_commercial_register" value="<?php echo $accounts->commercial_register->UploadMaxFileSize ?>">
</div>
<table id="ft_x_commercial_register" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $accounts->commercial_register->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($accounts->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_accounts_status" for="x_status" class="col-sm-2 control-label ewLabel"><?php echo $accounts->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $accounts->status->CellAttributes() ?>>
<span id="el_accounts_status">
<select data-field="x_status" id="x_status" name="x_status"<?php echo $accounts->status->EditAttributes() ?>>
<?php
if (is_array($accounts->status->EditValue)) {
	$arwrk = $accounts->status->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($accounts->status->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
<?php echo $accounts->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
faccountsadd.Init();
</script>
<?php
$accounts_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$accounts_add->Page_Terminate();
?>
