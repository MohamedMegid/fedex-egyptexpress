<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "pickup_requestsinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "administratorinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$pickup_requests_add = NULL; // Initialize page object first

class cpickup_requests_add extends cpickup_requests {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'pickup_requests';

	// Page object name
	var $PageObjName = 'pickup_requests_add';

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

		// Table object (pickup_requests)
		if (!isset($GLOBALS["pickup_requests"]) || get_class($GLOBALS["pickup_requests"]) == "cpickup_requests") {
			$GLOBALS["pickup_requests"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pickup_requests"];
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
			define("EW_TABLE_NAME", 'pickup_requests', TRUE);

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
		global $EW_EXPORT, $pickup_requests;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($pickup_requests);
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
					$this->Page_Terminate("pickup_requestslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "pickup_requestsview.php")
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
	}

	// Load default values
	function LoadDefaultValues() {
		$this->account_id->CurrentValue = NULL;
		$this->account_id->OldValue = $this->account_id->CurrentValue;
		$this->from_time->CurrentValue = NULL;
		$this->from_time->OldValue = $this->from_time->CurrentValue;
		$this->to_time->CurrentValue = NULL;
		$this->to_time->OldValue = $this->to_time->CurrentValue;
		$this->contact_name->CurrentValue = NULL;
		$this->contact_name->OldValue = $this->contact_name->CurrentValue;
		$this->account_type->CurrentValue = NULL;
		$this->account_type->OldValue = $this->account_type->CurrentValue;
		$this->account_number->CurrentValue = NULL;
		$this->account_number->OldValue = $this->account_number->CurrentValue;
		$this->company->CurrentValue = NULL;
		$this->company->OldValue = $this->company->CurrentValue;
		$this->contact_phone->CurrentValue = NULL;
		$this->contact_phone->OldValue = $this->contact_phone->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->content->CurrentValue = NULL;
		$this->content->OldValue = $this->content->CurrentValue;
		$this->weight->CurrentValue = NULL;
		$this->weight->OldValue = $this->weight->CurrentValue;
		$this->source_pickup_address->CurrentValue = NULL;
		$this->source_pickup_address->OldValue = $this->source_pickup_address->CurrentValue;
		$this->source_pickup_city->CurrentValue = NULL;
		$this->source_pickup_city->OldValue = $this->source_pickup_city->CurrentValue;
		$this->source_governorate->CurrentValue = NULL;
		$this->source_governorate->OldValue = $this->source_governorate->CurrentValue;
		$this->destination_pickup_address->CurrentValue = NULL;
		$this->destination_pickup_address->OldValue = $this->destination_pickup_address->CurrentValue;
		$this->destination_pickup_city->CurrentValue = NULL;
		$this->destination_pickup_city->OldValue = $this->destination_pickup_city->CurrentValue;
		$this->destination_governorate->CurrentValue = NULL;
		$this->destination_governorate->OldValue = $this->destination_governorate->CurrentValue;
		$this->no_of_pieces->CurrentValue = NULL;
		$this->no_of_pieces->OldValue = $this->no_of_pieces->CurrentValue;
		$this->pickup_date->CurrentValue = NULL;
		$this->pickup_date->OldValue = $this->pickup_date->CurrentValue;
		$this->product_type->CurrentValue = NULL;
		$this->product_type->OldValue = $this->product_type->CurrentValue;
		$this->status->CurrentValue = "pending";
		$this->created->CurrentValue = NULL;
		$this->created->OldValue = $this->created->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->account_id->FldIsDetailKey) {
			$this->account_id->setFormValue($objForm->GetValue("x_account_id"));
		}
		if (!$this->from_time->FldIsDetailKey) {
			$this->from_time->setFormValue($objForm->GetValue("x_from_time"));
		}
		if (!$this->to_time->FldIsDetailKey) {
			$this->to_time->setFormValue($objForm->GetValue("x_to_time"));
		}
		if (!$this->contact_name->FldIsDetailKey) {
			$this->contact_name->setFormValue($objForm->GetValue("x_contact_name"));
		}
		if (!$this->account_type->FldIsDetailKey) {
			$this->account_type->setFormValue($objForm->GetValue("x_account_type"));
		}
		if (!$this->account_number->FldIsDetailKey) {
			$this->account_number->setFormValue($objForm->GetValue("x_account_number"));
		}
		if (!$this->company->FldIsDetailKey) {
			$this->company->setFormValue($objForm->GetValue("x_company"));
		}
		if (!$this->contact_phone->FldIsDetailKey) {
			$this->contact_phone->setFormValue($objForm->GetValue("x_contact_phone"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->content->FldIsDetailKey) {
			$this->content->setFormValue($objForm->GetValue("x_content"));
		}
		if (!$this->weight->FldIsDetailKey) {
			$this->weight->setFormValue($objForm->GetValue("x_weight"));
		}
		if (!$this->source_pickup_address->FldIsDetailKey) {
			$this->source_pickup_address->setFormValue($objForm->GetValue("x_source_pickup_address"));
		}
		if (!$this->source_pickup_city->FldIsDetailKey) {
			$this->source_pickup_city->setFormValue($objForm->GetValue("x_source_pickup_city"));
		}
		if (!$this->source_governorate->FldIsDetailKey) {
			$this->source_governorate->setFormValue($objForm->GetValue("x_source_governorate"));
		}
		if (!$this->destination_pickup_address->FldIsDetailKey) {
			$this->destination_pickup_address->setFormValue($objForm->GetValue("x_destination_pickup_address"));
		}
		if (!$this->destination_pickup_city->FldIsDetailKey) {
			$this->destination_pickup_city->setFormValue($objForm->GetValue("x_destination_pickup_city"));
		}
		if (!$this->destination_governorate->FldIsDetailKey) {
			$this->destination_governorate->setFormValue($objForm->GetValue("x_destination_governorate"));
		}
		if (!$this->no_of_pieces->FldIsDetailKey) {
			$this->no_of_pieces->setFormValue($objForm->GetValue("x_no_of_pieces"));
		}
		if (!$this->pickup_date->FldIsDetailKey) {
			$this->pickup_date->setFormValue($objForm->GetValue("x_pickup_date"));
			$this->pickup_date->CurrentValue = ew_UnFormatDateTime($this->pickup_date->CurrentValue, 7);
		}
		if (!$this->product_type->FldIsDetailKey) {
			$this->product_type->setFormValue($objForm->GetValue("x_product_type"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->created->FldIsDetailKey) {
			$this->created->setFormValue($objForm->GetValue("x_created"));
			$this->created->CurrentValue = ew_UnFormatDateTime($this->created->CurrentValue, 7);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->account_id->CurrentValue = $this->account_id->FormValue;
		$this->from_time->CurrentValue = $this->from_time->FormValue;
		$this->to_time->CurrentValue = $this->to_time->FormValue;
		$this->contact_name->CurrentValue = $this->contact_name->FormValue;
		$this->account_type->CurrentValue = $this->account_type->FormValue;
		$this->account_number->CurrentValue = $this->account_number->FormValue;
		$this->company->CurrentValue = $this->company->FormValue;
		$this->contact_phone->CurrentValue = $this->contact_phone->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->content->CurrentValue = $this->content->FormValue;
		$this->weight->CurrentValue = $this->weight->FormValue;
		$this->source_pickup_address->CurrentValue = $this->source_pickup_address->FormValue;
		$this->source_pickup_city->CurrentValue = $this->source_pickup_city->FormValue;
		$this->source_governorate->CurrentValue = $this->source_governorate->FormValue;
		$this->destination_pickup_address->CurrentValue = $this->destination_pickup_address->FormValue;
		$this->destination_pickup_city->CurrentValue = $this->destination_pickup_city->FormValue;
		$this->destination_governorate->CurrentValue = $this->destination_governorate->FormValue;
		$this->no_of_pieces->CurrentValue = $this->no_of_pieces->FormValue;
		$this->pickup_date->CurrentValue = $this->pickup_date->FormValue;
		$this->pickup_date->CurrentValue = ew_UnFormatDateTime($this->pickup_date->CurrentValue, 7);
		$this->product_type->CurrentValue = $this->product_type->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->created->CurrentValue = $this->created->FormValue;
		$this->created->CurrentValue = ew_UnFormatDateTime($this->created->CurrentValue, 7);
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
		$this->account_id->setDbValue($rs->fields('account_id'));
		if (array_key_exists('EV__account_id', $rs->fields)) {
			$this->account_id->VirtualValue = $rs->fields('EV__account_id'); // Set up virtual field value
		} else {
			$this->account_id->VirtualValue = ""; // Clear value
		}
		$this->from_time->setDbValue($rs->fields('from_time'));
		$this->to_time->setDbValue($rs->fields('to_time'));
		$this->contact_name->setDbValue($rs->fields('contact_name'));
		$this->account_type->setDbValue($rs->fields('account_type'));
		$this->account_number->setDbValue($rs->fields('account_number'));
		$this->company->setDbValue($rs->fields('company'));
		$this->contact_phone->setDbValue($rs->fields('contact_phone'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->content->setDbValue($rs->fields('content'));
		$this->weight->setDbValue($rs->fields('weight'));
		$this->source_pickup_address->setDbValue($rs->fields('source_pickup_address'));
		$this->source_pickup_city->setDbValue($rs->fields('source_pickup_city'));
		$this->source_governorate->setDbValue($rs->fields('source_governorate'));
		$this->destination_pickup_address->setDbValue($rs->fields('destination_pickup_address'));
		$this->destination_pickup_city->setDbValue($rs->fields('destination_pickup_city'));
		$this->destination_governorate->setDbValue($rs->fields('destination_governorate'));
		$this->no_of_pieces->setDbValue($rs->fields('no_of_pieces'));
		$this->pickup_date->setDbValue($rs->fields('pickup_date'));
		$this->product_type->setDbValue($rs->fields('product_type'));
		$this->status->setDbValue($rs->fields('status'));
		$this->created->setDbValue($rs->fields('created'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->account_id->DbValue = $row['account_id'];
		$this->from_time->DbValue = $row['from_time'];
		$this->to_time->DbValue = $row['to_time'];
		$this->contact_name->DbValue = $row['contact_name'];
		$this->account_type->DbValue = $row['account_type'];
		$this->account_number->DbValue = $row['account_number'];
		$this->company->DbValue = $row['company'];
		$this->contact_phone->DbValue = $row['contact_phone'];
		$this->_email->DbValue = $row['email'];
		$this->content->DbValue = $row['content'];
		$this->weight->DbValue = $row['weight'];
		$this->source_pickup_address->DbValue = $row['source_pickup_address'];
		$this->source_pickup_city->DbValue = $row['source_pickup_city'];
		$this->source_governorate->DbValue = $row['source_governorate'];
		$this->destination_pickup_address->DbValue = $row['destination_pickup_address'];
		$this->destination_pickup_city->DbValue = $row['destination_pickup_city'];
		$this->destination_governorate->DbValue = $row['destination_governorate'];
		$this->no_of_pieces->DbValue = $row['no_of_pieces'];
		$this->pickup_date->DbValue = $row['pickup_date'];
		$this->product_type->DbValue = $row['product_type'];
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
		// Convert decimal values if posted back

		if ($this->weight->FormValue == $this->weight->CurrentValue && is_numeric(ew_StrToFloat($this->weight->CurrentValue)))
			$this->weight->CurrentValue = ew_StrToFloat($this->weight->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// account_id
		// from_time
		// to_time
		// contact_name
		// account_type
		// account_number
		// company
		// contact_phone
		// email
		// content
		// weight
		// source_pickup_address
		// source_pickup_city
		// source_governorate
		// destination_pickup_address
		// destination_pickup_city
		// destination_governorate
		// no_of_pieces
		// pickup_date
		// product_type
		// status
		// created

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// account_id
			if ($this->account_id->VirtualValue <> "") {
				$this->account_id->ViewValue = $this->account_id->VirtualValue;
			} else {
			if (strval($this->account_id->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->account_id->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT DISTINCT `id`, `first_name` AS `DispFld`, `last_name` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `accounts`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->account_id, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->account_id->ViewValue = $rswrk->fields('DispFld');
					$this->account_id->ViewValue .= ew_ValueSeparator(1,$this->account_id) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->account_id->ViewValue = $this->account_id->CurrentValue;
				}
			} else {
				$this->account_id->ViewValue = NULL;
			}
			}
			$this->account_id->ViewCustomAttributes = "";

			// from_time
			$this->from_time->ViewValue = $this->from_time->CurrentValue;
			$this->from_time->ViewCustomAttributes = "";

			// to_time
			$this->to_time->ViewValue = $this->to_time->CurrentValue;
			$this->to_time->ViewCustomAttributes = "";

			// contact_name
			$this->contact_name->ViewValue = $this->contact_name->CurrentValue;
			$this->contact_name->ViewCustomAttributes = "";

			// account_type
			if (strval($this->account_type->CurrentValue) <> "") {
				$sFilterWrk = "`account_type`" . ew_SearchString("=", $this->account_type->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT DISTINCT `account_type`, `account_type` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pickup_requests`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->account_type, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->account_type->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->account_type->ViewValue = $this->account_type->CurrentValue;
				}
			} else {
				$this->account_type->ViewValue = NULL;
			}
			$this->account_type->ViewCustomAttributes = "";

			// account_number
			$this->account_number->ViewValue = $this->account_number->CurrentValue;
			$this->account_number->ViewCustomAttributes = "";

			// company
			$this->company->ViewValue = $this->company->CurrentValue;
			$this->company->ViewCustomAttributes = "";

			// contact_phone
			$this->contact_phone->ViewValue = $this->contact_phone->CurrentValue;
			$this->contact_phone->ViewCustomAttributes = "";

			// email
			$this->_email->ViewValue = $this->_email->CurrentValue;
			$this->_email->ViewCustomAttributes = "";

			// content
			$this->content->ViewValue = $this->content->CurrentValue;
			$this->content->ViewCustomAttributes = "";

			// weight
			$this->weight->ViewValue = $this->weight->CurrentValue;
			$this->weight->ViewCustomAttributes = "";

			// source_pickup_address
			$this->source_pickup_address->ViewValue = $this->source_pickup_address->CurrentValue;
			$this->source_pickup_address->ViewCustomAttributes = "";

			// source_pickup_city
			$this->source_pickup_city->ViewValue = $this->source_pickup_city->CurrentValue;
			$this->source_pickup_city->ViewCustomAttributes = "";

			// source_governorate
			$this->source_governorate->ViewValue = $this->source_governorate->CurrentValue;
			$this->source_governorate->ViewCustomAttributes = "";

			// destination_pickup_address
			$this->destination_pickup_address->ViewValue = $this->destination_pickup_address->CurrentValue;
			$this->destination_pickup_address->ViewCustomAttributes = "";

			// destination_pickup_city
			$this->destination_pickup_city->ViewValue = $this->destination_pickup_city->CurrentValue;
			$this->destination_pickup_city->ViewCustomAttributes = "";

			// destination_governorate
			$this->destination_governorate->ViewValue = $this->destination_governorate->CurrentValue;
			$this->destination_governorate->ViewCustomAttributes = "";

			// no_of_pieces
			$this->no_of_pieces->ViewValue = $this->no_of_pieces->CurrentValue;
			$this->no_of_pieces->ViewCustomAttributes = "";

			// pickup_date
			$this->pickup_date->ViewValue = $this->pickup_date->CurrentValue;
			$this->pickup_date->ViewValue = ew_FormatDateTime($this->pickup_date->ViewValue, 7);
			$this->pickup_date->ViewCustomAttributes = "";

			// product_type
			$this->product_type->ViewValue = $this->product_type->CurrentValue;
			$this->product_type->ViewCustomAttributes = "";

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

			// account_id
			$this->account_id->LinkCustomAttributes = "";
			$this->account_id->HrefValue = "";
			$this->account_id->TooltipValue = "";

			// from_time
			$this->from_time->LinkCustomAttributes = "";
			$this->from_time->HrefValue = "";
			$this->from_time->TooltipValue = "";

			// to_time
			$this->to_time->LinkCustomAttributes = "";
			$this->to_time->HrefValue = "";
			$this->to_time->TooltipValue = "";

			// contact_name
			$this->contact_name->LinkCustomAttributes = "";
			$this->contact_name->HrefValue = "";
			$this->contact_name->TooltipValue = "";

			// account_type
			$this->account_type->LinkCustomAttributes = "";
			$this->account_type->HrefValue = "";
			$this->account_type->TooltipValue = "";

			// account_number
			$this->account_number->LinkCustomAttributes = "";
			$this->account_number->HrefValue = "";
			$this->account_number->TooltipValue = "";

			// company
			$this->company->LinkCustomAttributes = "";
			$this->company->HrefValue = "";
			$this->company->TooltipValue = "";

			// contact_phone
			$this->contact_phone->LinkCustomAttributes = "";
			$this->contact_phone->HrefValue = "";
			$this->contact_phone->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// content
			$this->content->LinkCustomAttributes = "";
			$this->content->HrefValue = "";
			$this->content->TooltipValue = "";

			// weight
			$this->weight->LinkCustomAttributes = "";
			$this->weight->HrefValue = "";
			$this->weight->TooltipValue = "";

			// source_pickup_address
			$this->source_pickup_address->LinkCustomAttributes = "";
			$this->source_pickup_address->HrefValue = "";
			$this->source_pickup_address->TooltipValue = "";

			// source_pickup_city
			$this->source_pickup_city->LinkCustomAttributes = "";
			$this->source_pickup_city->HrefValue = "";
			$this->source_pickup_city->TooltipValue = "";

			// source_governorate
			$this->source_governorate->LinkCustomAttributes = "";
			$this->source_governorate->HrefValue = "";
			$this->source_governorate->TooltipValue = "";

			// destination_pickup_address
			$this->destination_pickup_address->LinkCustomAttributes = "";
			$this->destination_pickup_address->HrefValue = "";
			$this->destination_pickup_address->TooltipValue = "";

			// destination_pickup_city
			$this->destination_pickup_city->LinkCustomAttributes = "";
			$this->destination_pickup_city->HrefValue = "";
			$this->destination_pickup_city->TooltipValue = "";

			// destination_governorate
			$this->destination_governorate->LinkCustomAttributes = "";
			$this->destination_governorate->HrefValue = "";
			$this->destination_governorate->TooltipValue = "";

			// no_of_pieces
			$this->no_of_pieces->LinkCustomAttributes = "";
			$this->no_of_pieces->HrefValue = "";
			$this->no_of_pieces->TooltipValue = "";

			// pickup_date
			$this->pickup_date->LinkCustomAttributes = "";
			$this->pickup_date->HrefValue = "";
			$this->pickup_date->TooltipValue = "";

			// product_type
			$this->product_type->LinkCustomAttributes = "";
			$this->product_type->HrefValue = "";
			$this->product_type->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// created
			$this->created->LinkCustomAttributes = "";
			$this->created->HrefValue = "";
			$this->created->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// account_id
			$this->account_id->EditAttrs["class"] = "form-control";
			$this->account_id->EditCustomAttributes = "";
			if (trim(strval($this->account_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->account_id->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT DISTINCT `id`, `first_name` AS `DispFld`, `last_name` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `accounts`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->account_id, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->account_id->EditValue = $arwrk;

			// from_time
			$this->from_time->EditAttrs["class"] = "form-control";
			$this->from_time->EditCustomAttributes = "";
			$this->from_time->EditValue = ew_HtmlEncode($this->from_time->CurrentValue);
			$this->from_time->PlaceHolder = ew_RemoveHtml($this->from_time->FldCaption());

			// to_time
			$this->to_time->EditAttrs["class"] = "form-control";
			$this->to_time->EditCustomAttributes = "";
			$this->to_time->EditValue = ew_HtmlEncode($this->to_time->CurrentValue);
			$this->to_time->PlaceHolder = ew_RemoveHtml($this->to_time->FldCaption());

			// contact_name
			$this->contact_name->EditAttrs["class"] = "form-control";
			$this->contact_name->EditCustomAttributes = "";
			$this->contact_name->EditValue = ew_HtmlEncode($this->contact_name->CurrentValue);
			$this->contact_name->PlaceHolder = ew_RemoveHtml($this->contact_name->FldCaption());

			// account_type
			$this->account_type->EditAttrs["class"] = "form-control";
			$this->account_type->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT DISTINCT `account_type`, `account_type` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `pickup_requests`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->account_type, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->account_type->EditValue = $arwrk;

			// account_number
			$this->account_number->EditAttrs["class"] = "form-control";
			$this->account_number->EditCustomAttributes = "";
			$this->account_number->EditValue = ew_HtmlEncode($this->account_number->CurrentValue);
			$this->account_number->PlaceHolder = ew_RemoveHtml($this->account_number->FldCaption());

			// company
			$this->company->EditAttrs["class"] = "form-control";
			$this->company->EditCustomAttributes = "";
			$this->company->EditValue = ew_HtmlEncode($this->company->CurrentValue);
			$this->company->PlaceHolder = ew_RemoveHtml($this->company->FldCaption());

			// contact_phone
			$this->contact_phone->EditAttrs["class"] = "form-control";
			$this->contact_phone->EditCustomAttributes = "";
			$this->contact_phone->EditValue = ew_HtmlEncode($this->contact_phone->CurrentValue);
			$this->contact_phone->PlaceHolder = ew_RemoveHtml($this->contact_phone->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// content
			$this->content->EditAttrs["class"] = "form-control";
			$this->content->EditCustomAttributes = "";
			$this->content->EditValue = ew_HtmlEncode($this->content->CurrentValue);
			$this->content->PlaceHolder = ew_RemoveHtml($this->content->FldCaption());

			// weight
			$this->weight->EditAttrs["class"] = "form-control";
			$this->weight->EditCustomAttributes = "";
			$this->weight->EditValue = ew_HtmlEncode($this->weight->CurrentValue);
			$this->weight->PlaceHolder = ew_RemoveHtml($this->weight->FldCaption());
			if (strval($this->weight->EditValue) <> "" && is_numeric($this->weight->EditValue)) $this->weight->EditValue = ew_FormatNumber($this->weight->EditValue, -2, -1, -2, 0);

			// source_pickup_address
			$this->source_pickup_address->EditAttrs["class"] = "form-control";
			$this->source_pickup_address->EditCustomAttributes = "";
			$this->source_pickup_address->EditValue = ew_HtmlEncode($this->source_pickup_address->CurrentValue);
			$this->source_pickup_address->PlaceHolder = ew_RemoveHtml($this->source_pickup_address->FldCaption());

			// source_pickup_city
			$this->source_pickup_city->EditAttrs["class"] = "form-control";
			$this->source_pickup_city->EditCustomAttributes = "";
			$this->source_pickup_city->EditValue = ew_HtmlEncode($this->source_pickup_city->CurrentValue);
			$this->source_pickup_city->PlaceHolder = ew_RemoveHtml($this->source_pickup_city->FldCaption());

			// source_governorate
			$this->source_governorate->EditAttrs["class"] = "form-control";
			$this->source_governorate->EditCustomAttributes = "";
			$this->source_governorate->EditValue = ew_HtmlEncode($this->source_governorate->CurrentValue);
			$this->source_governorate->PlaceHolder = ew_RemoveHtml($this->source_governorate->FldCaption());

			// destination_pickup_address
			$this->destination_pickup_address->EditAttrs["class"] = "form-control";
			$this->destination_pickup_address->EditCustomAttributes = "";
			$this->destination_pickup_address->EditValue = ew_HtmlEncode($this->destination_pickup_address->CurrentValue);
			$this->destination_pickup_address->PlaceHolder = ew_RemoveHtml($this->destination_pickup_address->FldCaption());

			// destination_pickup_city
			$this->destination_pickup_city->EditAttrs["class"] = "form-control";
			$this->destination_pickup_city->EditCustomAttributes = "";
			$this->destination_pickup_city->EditValue = ew_HtmlEncode($this->destination_pickup_city->CurrentValue);
			$this->destination_pickup_city->PlaceHolder = ew_RemoveHtml($this->destination_pickup_city->FldCaption());

			// destination_governorate
			$this->destination_governorate->EditAttrs["class"] = "form-control";
			$this->destination_governorate->EditCustomAttributes = "";
			$this->destination_governorate->EditValue = ew_HtmlEncode($this->destination_governorate->CurrentValue);
			$this->destination_governorate->PlaceHolder = ew_RemoveHtml($this->destination_governorate->FldCaption());

			// no_of_pieces
			$this->no_of_pieces->EditAttrs["class"] = "form-control";
			$this->no_of_pieces->EditCustomAttributes = "";
			$this->no_of_pieces->EditValue = ew_HtmlEncode($this->no_of_pieces->CurrentValue);
			$this->no_of_pieces->PlaceHolder = ew_RemoveHtml($this->no_of_pieces->FldCaption());

			// pickup_date
			$this->pickup_date->EditAttrs["class"] = "form-control";
			$this->pickup_date->EditCustomAttributes = "";
			$this->pickup_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->pickup_date->CurrentValue, 7));
			$this->pickup_date->PlaceHolder = ew_RemoveHtml($this->pickup_date->FldCaption());

			// product_type
			$this->product_type->EditAttrs["class"] = "form-control";
			$this->product_type->EditCustomAttributes = "";
			$this->product_type->EditValue = ew_HtmlEncode($this->product_type->CurrentValue);
			$this->product_type->PlaceHolder = ew_RemoveHtml($this->product_type->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->status->FldTagValue(1), $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : $this->status->FldTagValue(1));
			$arwrk[] = array($this->status->FldTagValue(2), $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : $this->status->FldTagValue(2));
			$arwrk[] = array($this->status->FldTagValue(3), $this->status->FldTagCaption(3) <> "" ? $this->status->FldTagCaption(3) : $this->status->FldTagValue(3));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->status->EditValue = $arwrk;

			// created
			$this->created->EditAttrs["class"] = "form-control";
			$this->created->EditCustomAttributes = "";
			$this->created->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->created->CurrentValue, 7));
			$this->created->PlaceHolder = ew_RemoveHtml($this->created->FldCaption());

			// Edit refer script
			// account_id

			$this->account_id->HrefValue = "";

			// from_time
			$this->from_time->HrefValue = "";

			// to_time
			$this->to_time->HrefValue = "";

			// contact_name
			$this->contact_name->HrefValue = "";

			// account_type
			$this->account_type->HrefValue = "";

			// account_number
			$this->account_number->HrefValue = "";

			// company
			$this->company->HrefValue = "";

			// contact_phone
			$this->contact_phone->HrefValue = "";

			// email
			$this->_email->HrefValue = "";

			// content
			$this->content->HrefValue = "";

			// weight
			$this->weight->HrefValue = "";

			// source_pickup_address
			$this->source_pickup_address->HrefValue = "";

			// source_pickup_city
			$this->source_pickup_city->HrefValue = "";

			// source_governorate
			$this->source_governorate->HrefValue = "";

			// destination_pickup_address
			$this->destination_pickup_address->HrefValue = "";

			// destination_pickup_city
			$this->destination_pickup_city->HrefValue = "";

			// destination_governorate
			$this->destination_governorate->HrefValue = "";

			// no_of_pieces
			$this->no_of_pieces->HrefValue = "";

			// pickup_date
			$this->pickup_date->HrefValue = "";

			// product_type
			$this->product_type->HrefValue = "";

			// status
			$this->status->HrefValue = "";

			// created
			$this->created->HrefValue = "";
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
		if (!$this->from_time->FldIsDetailKey && !is_null($this->from_time->FormValue) && $this->from_time->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->from_time->FldCaption(), $this->from_time->ReqErrMsg));
		}
		if (!$this->to_time->FldIsDetailKey && !is_null($this->to_time->FormValue) && $this->to_time->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->to_time->FldCaption(), $this->to_time->ReqErrMsg));
		}
		if (!$this->contact_name->FldIsDetailKey && !is_null($this->contact_name->FormValue) && $this->contact_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->contact_name->FldCaption(), $this->contact_name->ReqErrMsg));
		}
		if (!$this->account_type->FldIsDetailKey && !is_null($this->account_type->FormValue) && $this->account_type->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->account_type->FldCaption(), $this->account_type->ReqErrMsg));
		}
		if (!$this->company->FldIsDetailKey && !is_null($this->company->FormValue) && $this->company->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->company->FldCaption(), $this->company->ReqErrMsg));
		}
		if (!$this->contact_phone->FldIsDetailKey && !is_null($this->contact_phone->FormValue) && $this->contact_phone->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->contact_phone->FldCaption(), $this->contact_phone->ReqErrMsg));
		}
		if (!$this->_email->FldIsDetailKey && !is_null($this->_email->FormValue) && $this->_email->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_email->FldCaption(), $this->_email->ReqErrMsg));
		}
		if (!$this->content->FldIsDetailKey && !is_null($this->content->FormValue) && $this->content->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->content->FldCaption(), $this->content->ReqErrMsg));
		}
		if (!$this->weight->FldIsDetailKey && !is_null($this->weight->FormValue) && $this->weight->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->weight->FldCaption(), $this->weight->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->weight->FormValue)) {
			ew_AddMessage($gsFormError, $this->weight->FldErrMsg());
		}
		if (!$this->source_pickup_address->FldIsDetailKey && !is_null($this->source_pickup_address->FormValue) && $this->source_pickup_address->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->source_pickup_address->FldCaption(), $this->source_pickup_address->ReqErrMsg));
		}
		if (!$this->source_pickup_city->FldIsDetailKey && !is_null($this->source_pickup_city->FormValue) && $this->source_pickup_city->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->source_pickup_city->FldCaption(), $this->source_pickup_city->ReqErrMsg));
		}
		if (!$this->source_governorate->FldIsDetailKey && !is_null($this->source_governorate->FormValue) && $this->source_governorate->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->source_governorate->FldCaption(), $this->source_governorate->ReqErrMsg));
		}
		if (!$this->destination_pickup_address->FldIsDetailKey && !is_null($this->destination_pickup_address->FormValue) && $this->destination_pickup_address->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->destination_pickup_address->FldCaption(), $this->destination_pickup_address->ReqErrMsg));
		}
		if (!$this->destination_pickup_city->FldIsDetailKey && !is_null($this->destination_pickup_city->FormValue) && $this->destination_pickup_city->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->destination_pickup_city->FldCaption(), $this->destination_pickup_city->ReqErrMsg));
		}
		if (!$this->destination_governorate->FldIsDetailKey && !is_null($this->destination_governorate->FormValue) && $this->destination_governorate->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->destination_governorate->FldCaption(), $this->destination_governorate->ReqErrMsg));
		}
		if (!$this->no_of_pieces->FldIsDetailKey && !is_null($this->no_of_pieces->FormValue) && $this->no_of_pieces->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->no_of_pieces->FldCaption(), $this->no_of_pieces->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->no_of_pieces->FormValue)) {
			ew_AddMessage($gsFormError, $this->no_of_pieces->FldErrMsg());
		}
		if (!$this->pickup_date->FldIsDetailKey && !is_null($this->pickup_date->FormValue) && $this->pickup_date->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pickup_date->FldCaption(), $this->pickup_date->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->pickup_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->pickup_date->FldErrMsg());
		}
		if (!$this->product_type->FldIsDetailKey && !is_null($this->product_type->FormValue) && $this->product_type->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_type->FldCaption(), $this->product_type->ReqErrMsg));
		}
		if (!$this->status->FldIsDetailKey && !is_null($this->status->FormValue) && $this->status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status->FldCaption(), $this->status->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->created->FormValue)) {
			ew_AddMessage($gsFormError, $this->created->FldErrMsg());
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
		}
		$rsnew = array();

		// account_id
		$this->account_id->SetDbValueDef($rsnew, $this->account_id->CurrentValue, NULL, FALSE);

		// from_time
		$this->from_time->SetDbValueDef($rsnew, $this->from_time->CurrentValue, "", FALSE);

		// to_time
		$this->to_time->SetDbValueDef($rsnew, $this->to_time->CurrentValue, "", FALSE);

		// contact_name
		$this->contact_name->SetDbValueDef($rsnew, $this->contact_name->CurrentValue, "", FALSE);

		// account_type
		$this->account_type->SetDbValueDef($rsnew, $this->account_type->CurrentValue, "", FALSE);

		// account_number
		$this->account_number->SetDbValueDef($rsnew, $this->account_number->CurrentValue, NULL, FALSE);

		// company
		$this->company->SetDbValueDef($rsnew, $this->company->CurrentValue, "", FALSE);

		// contact_phone
		$this->contact_phone->SetDbValueDef($rsnew, $this->contact_phone->CurrentValue, "", FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, "", FALSE);

		// content
		$this->content->SetDbValueDef($rsnew, $this->content->CurrentValue, "", FALSE);

		// weight
		$this->weight->SetDbValueDef($rsnew, $this->weight->CurrentValue, 0, FALSE);

		// source_pickup_address
		$this->source_pickup_address->SetDbValueDef($rsnew, $this->source_pickup_address->CurrentValue, "", FALSE);

		// source_pickup_city
		$this->source_pickup_city->SetDbValueDef($rsnew, $this->source_pickup_city->CurrentValue, "", FALSE);

		// source_governorate
		$this->source_governorate->SetDbValueDef($rsnew, $this->source_governorate->CurrentValue, "", FALSE);

		// destination_pickup_address
		$this->destination_pickup_address->SetDbValueDef($rsnew, $this->destination_pickup_address->CurrentValue, "", FALSE);

		// destination_pickup_city
		$this->destination_pickup_city->SetDbValueDef($rsnew, $this->destination_pickup_city->CurrentValue, "", FALSE);

		// destination_governorate
		$this->destination_governorate->SetDbValueDef($rsnew, $this->destination_governorate->CurrentValue, "", FALSE);

		// no_of_pieces
		$this->no_of_pieces->SetDbValueDef($rsnew, $this->no_of_pieces->CurrentValue, 0, FALSE);

		// pickup_date
		$this->pickup_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->pickup_date->CurrentValue, 7), ew_CurrentDate(), FALSE);

		// product_type
		$this->product_type->SetDbValueDef($rsnew, $this->product_type->CurrentValue, "", FALSE);

		// status
		$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, "", strval($this->status->CurrentValue) == "");

		// created
		$this->created->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->created->CurrentValue, 7), NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
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
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "pickup_requestslist.php", "", $this->TableVar, TRUE);
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
if (!isset($pickup_requests_add)) $pickup_requests_add = new cpickup_requests_add();

// Page init
$pickup_requests_add->Page_Init();

// Page main
$pickup_requests_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pickup_requests_add->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var pickup_requests_add = new ew_Page("pickup_requests_add");
pickup_requests_add.PageID = "add"; // Page ID
var EW_PAGE_ID = pickup_requests_add.PageID; // For backward compatibility

// Form object
var fpickup_requestsadd = new ew_Form("fpickup_requestsadd");

// Validate form
fpickup_requestsadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_from_time");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pickup_requests->from_time->FldCaption(), $pickup_requests->from_time->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_to_time");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pickup_requests->to_time->FldCaption(), $pickup_requests->to_time->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_contact_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pickup_requests->contact_name->FldCaption(), $pickup_requests->contact_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_account_type");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pickup_requests->account_type->FldCaption(), $pickup_requests->account_type->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_company");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pickup_requests->company->FldCaption(), $pickup_requests->company->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_contact_phone");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pickup_requests->contact_phone->FldCaption(), $pickup_requests->contact_phone->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pickup_requests->_email->FldCaption(), $pickup_requests->_email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_content");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pickup_requests->content->FldCaption(), $pickup_requests->content->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_weight");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pickup_requests->weight->FldCaption(), $pickup_requests->weight->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_weight");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pickup_requests->weight->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_source_pickup_address");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pickup_requests->source_pickup_address->FldCaption(), $pickup_requests->source_pickup_address->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_source_pickup_city");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pickup_requests->source_pickup_city->FldCaption(), $pickup_requests->source_pickup_city->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_source_governorate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pickup_requests->source_governorate->FldCaption(), $pickup_requests->source_governorate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_destination_pickup_address");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pickup_requests->destination_pickup_address->FldCaption(), $pickup_requests->destination_pickup_address->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_destination_pickup_city");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pickup_requests->destination_pickup_city->FldCaption(), $pickup_requests->destination_pickup_city->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_destination_governorate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pickup_requests->destination_governorate->FldCaption(), $pickup_requests->destination_governorate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_no_of_pieces");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pickup_requests->no_of_pieces->FldCaption(), $pickup_requests->no_of_pieces->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_no_of_pieces");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pickup_requests->no_of_pieces->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pickup_date");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pickup_requests->pickup_date->FldCaption(), $pickup_requests->pickup_date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pickup_date");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pickup_requests->pickup_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_product_type");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pickup_requests->product_type->FldCaption(), $pickup_requests->product_type->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pickup_requests->status->FldCaption(), $pickup_requests->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_created");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pickup_requests->created->FldErrMsg()) ?>");

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
fpickup_requestsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpickup_requestsadd.ValidateRequired = true;
<?php } else { ?>
fpickup_requestsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpickup_requestsadd.Lists["x_account_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_first_name","x_last_name","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fpickup_requestsadd.Lists["x_account_type"] = {"LinkField":"x_account_type","Ajax":null,"AutoFill":false,"DisplayFields":["x_account_type","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $pickup_requests_add->ShowPageHeader(); ?>
<?php
$pickup_requests_add->ShowMessage();
?>
<form name="fpickup_requestsadd" id="fpickup_requestsadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pickup_requests_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pickup_requests_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pickup_requests">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($pickup_requests->account_id->Visible) { // account_id ?>
	<div id="r_account_id" class="form-group">
		<label id="elh_pickup_requests_account_id" for="x_account_id" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->account_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->account_id->CellAttributes() ?>>
<span id="el_pickup_requests_account_id">
<select data-field="x_account_id" id="x_account_id" name="x_account_id"<?php echo $pickup_requests->account_id->EditAttributes() ?>>
<?php
if (is_array($pickup_requests->account_id->EditValue)) {
	$arwrk = $pickup_requests->account_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pickup_requests->account_id->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$pickup_requests->account_id) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT DISTINCT `id`, `first_name` AS `DispFld`, `last_name` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `accounts`";
$sWhereWrk = "";

// Call Lookup selecting
$pickup_requests->Lookup_Selecting($pickup_requests->account_id, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_account_id" id="s_x_account_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&amp;t0=3">
</span>
<?php echo $pickup_requests->account_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pickup_requests->from_time->Visible) { // from_time ?>
	<div id="r_from_time" class="form-group">
		<label id="elh_pickup_requests_from_time" for="x_from_time" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->from_time->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->from_time->CellAttributes() ?>>
<span id="el_pickup_requests_from_time">
<input type="text" data-field="x_from_time" name="x_from_time" id="x_from_time" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pickup_requests->from_time->PlaceHolder) ?>" value="<?php echo $pickup_requests->from_time->EditValue ?>"<?php echo $pickup_requests->from_time->EditAttributes() ?>>
</span>
<?php echo $pickup_requests->from_time->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pickup_requests->to_time->Visible) { // to_time ?>
	<div id="r_to_time" class="form-group">
		<label id="elh_pickup_requests_to_time" for="x_to_time" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->to_time->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->to_time->CellAttributes() ?>>
<span id="el_pickup_requests_to_time">
<input type="text" data-field="x_to_time" name="x_to_time" id="x_to_time" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pickup_requests->to_time->PlaceHolder) ?>" value="<?php echo $pickup_requests->to_time->EditValue ?>"<?php echo $pickup_requests->to_time->EditAttributes() ?>>
</span>
<?php echo $pickup_requests->to_time->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pickup_requests->contact_name->Visible) { // contact_name ?>
	<div id="r_contact_name" class="form-group">
		<label id="elh_pickup_requests_contact_name" for="x_contact_name" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->contact_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->contact_name->CellAttributes() ?>>
<span id="el_pickup_requests_contact_name">
<input type="text" data-field="x_contact_name" name="x_contact_name" id="x_contact_name" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pickup_requests->contact_name->PlaceHolder) ?>" value="<?php echo $pickup_requests->contact_name->EditValue ?>"<?php echo $pickup_requests->contact_name->EditAttributes() ?>>
</span>
<?php echo $pickup_requests->contact_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pickup_requests->account_type->Visible) { // account_type ?>
	<div id="r_account_type" class="form-group">
		<label id="elh_pickup_requests_account_type" for="x_account_type" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->account_type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->account_type->CellAttributes() ?>>
<span id="el_pickup_requests_account_type">
<select data-field="x_account_type" id="x_account_type" name="x_account_type"<?php echo $pickup_requests->account_type->EditAttributes() ?>>
<?php
if (is_array($pickup_requests->account_type->EditValue)) {
	$arwrk = $pickup_requests->account_type->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pickup_requests->account_type->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<script type="text/javascript">
fpickup_requestsadd.Lists["x_account_type"].Options = <?php echo (is_array($pickup_requests->account_type->EditValue)) ? ew_ArrayToJson($pickup_requests->account_type->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $pickup_requests->account_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pickup_requests->account_number->Visible) { // account_number ?>
	<div id="r_account_number" class="form-group">
		<label id="elh_pickup_requests_account_number" for="x_account_number" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->account_number->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->account_number->CellAttributes() ?>>
<span id="el_pickup_requests_account_number">
<input type="text" data-field="x_account_number" name="x_account_number" id="x_account_number" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pickup_requests->account_number->PlaceHolder) ?>" value="<?php echo $pickup_requests->account_number->EditValue ?>"<?php echo $pickup_requests->account_number->EditAttributes() ?>>
</span>
<?php echo $pickup_requests->account_number->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pickup_requests->company->Visible) { // company ?>
	<div id="r_company" class="form-group">
		<label id="elh_pickup_requests_company" for="x_company" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->company->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->company->CellAttributes() ?>>
<span id="el_pickup_requests_company">
<input type="text" data-field="x_company" name="x_company" id="x_company" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pickup_requests->company->PlaceHolder) ?>" value="<?php echo $pickup_requests->company->EditValue ?>"<?php echo $pickup_requests->company->EditAttributes() ?>>
</span>
<?php echo $pickup_requests->company->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pickup_requests->contact_phone->Visible) { // contact_phone ?>
	<div id="r_contact_phone" class="form-group">
		<label id="elh_pickup_requests_contact_phone" for="x_contact_phone" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->contact_phone->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->contact_phone->CellAttributes() ?>>
<span id="el_pickup_requests_contact_phone">
<input type="text" data-field="x_contact_phone" name="x_contact_phone" id="x_contact_phone" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pickup_requests->contact_phone->PlaceHolder) ?>" value="<?php echo $pickup_requests->contact_phone->EditValue ?>"<?php echo $pickup_requests->contact_phone->EditAttributes() ?>>
</span>
<?php echo $pickup_requests->contact_phone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pickup_requests->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_pickup_requests__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->_email->CellAttributes() ?>>
<span id="el_pickup_requests__email">
<input type="text" data-field="x__email" name="x__email" id="x__email" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pickup_requests->_email->PlaceHolder) ?>" value="<?php echo $pickup_requests->_email->EditValue ?>"<?php echo $pickup_requests->_email->EditAttributes() ?>>
</span>
<?php echo $pickup_requests->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pickup_requests->content->Visible) { // content ?>
	<div id="r_content" class="form-group">
		<label id="elh_pickup_requests_content" for="x_content" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->content->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->content->CellAttributes() ?>>
<span id="el_pickup_requests_content">
<textarea data-field="x_content" name="x_content" id="x_content" cols="50" rows="6" placeholder="<?php echo ew_HtmlEncode($pickup_requests->content->PlaceHolder) ?>"<?php echo $pickup_requests->content->EditAttributes() ?>><?php echo $pickup_requests->content->EditValue ?></textarea>
</span>
<?php echo $pickup_requests->content->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pickup_requests->weight->Visible) { // weight ?>
	<div id="r_weight" class="form-group">
		<label id="elh_pickup_requests_weight" for="x_weight" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->weight->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->weight->CellAttributes() ?>>
<span id="el_pickup_requests_weight">
<input type="text" data-field="x_weight" name="x_weight" id="x_weight" size="30" placeholder="<?php echo ew_HtmlEncode($pickup_requests->weight->PlaceHolder) ?>" value="<?php echo $pickup_requests->weight->EditValue ?>"<?php echo $pickup_requests->weight->EditAttributes() ?>>
</span>
<?php echo $pickup_requests->weight->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pickup_requests->source_pickup_address->Visible) { // source_pickup_address ?>
	<div id="r_source_pickup_address" class="form-group">
		<label id="elh_pickup_requests_source_pickup_address" for="x_source_pickup_address" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->source_pickup_address->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->source_pickup_address->CellAttributes() ?>>
<span id="el_pickup_requests_source_pickup_address">
<textarea data-field="x_source_pickup_address" name="x_source_pickup_address" id="x_source_pickup_address" cols="45" rows="5" placeholder="<?php echo ew_HtmlEncode($pickup_requests->source_pickup_address->PlaceHolder) ?>"<?php echo $pickup_requests->source_pickup_address->EditAttributes() ?>><?php echo $pickup_requests->source_pickup_address->EditValue ?></textarea>
</span>
<?php echo $pickup_requests->source_pickup_address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pickup_requests->source_pickup_city->Visible) { // source_pickup_city ?>
	<div id="r_source_pickup_city" class="form-group">
		<label id="elh_pickup_requests_source_pickup_city" for="x_source_pickup_city" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->source_pickup_city->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->source_pickup_city->CellAttributes() ?>>
<span id="el_pickup_requests_source_pickup_city">
<input type="text" data-field="x_source_pickup_city" name="x_source_pickup_city" id="x_source_pickup_city" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pickup_requests->source_pickup_city->PlaceHolder) ?>" value="<?php echo $pickup_requests->source_pickup_city->EditValue ?>"<?php echo $pickup_requests->source_pickup_city->EditAttributes() ?>>
</span>
<?php echo $pickup_requests->source_pickup_city->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pickup_requests->source_governorate->Visible) { // source_governorate ?>
	<div id="r_source_governorate" class="form-group">
		<label id="elh_pickup_requests_source_governorate" for="x_source_governorate" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->source_governorate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->source_governorate->CellAttributes() ?>>
<span id="el_pickup_requests_source_governorate">
<input type="text" data-field="x_source_governorate" name="x_source_governorate" id="x_source_governorate" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pickup_requests->source_governorate->PlaceHolder) ?>" value="<?php echo $pickup_requests->source_governorate->EditValue ?>"<?php echo $pickup_requests->source_governorate->EditAttributes() ?>>
</span>
<?php echo $pickup_requests->source_governorate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pickup_requests->destination_pickup_address->Visible) { // destination_pickup_address ?>
	<div id="r_destination_pickup_address" class="form-group">
		<label id="elh_pickup_requests_destination_pickup_address" for="x_destination_pickup_address" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->destination_pickup_address->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->destination_pickup_address->CellAttributes() ?>>
<span id="el_pickup_requests_destination_pickup_address">
<textarea data-field="x_destination_pickup_address" name="x_destination_pickup_address" id="x_destination_pickup_address" cols="45" rows="5" placeholder="<?php echo ew_HtmlEncode($pickup_requests->destination_pickup_address->PlaceHolder) ?>"<?php echo $pickup_requests->destination_pickup_address->EditAttributes() ?>><?php echo $pickup_requests->destination_pickup_address->EditValue ?></textarea>
</span>
<?php echo $pickup_requests->destination_pickup_address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pickup_requests->destination_pickup_city->Visible) { // destination_pickup_city ?>
	<div id="r_destination_pickup_city" class="form-group">
		<label id="elh_pickup_requests_destination_pickup_city" for="x_destination_pickup_city" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->destination_pickup_city->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->destination_pickup_city->CellAttributes() ?>>
<span id="el_pickup_requests_destination_pickup_city">
<input type="text" data-field="x_destination_pickup_city" name="x_destination_pickup_city" id="x_destination_pickup_city" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pickup_requests->destination_pickup_city->PlaceHolder) ?>" value="<?php echo $pickup_requests->destination_pickup_city->EditValue ?>"<?php echo $pickup_requests->destination_pickup_city->EditAttributes() ?>>
</span>
<?php echo $pickup_requests->destination_pickup_city->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pickup_requests->destination_governorate->Visible) { // destination_governorate ?>
	<div id="r_destination_governorate" class="form-group">
		<label id="elh_pickup_requests_destination_governorate" for="x_destination_governorate" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->destination_governorate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->destination_governorate->CellAttributes() ?>>
<span id="el_pickup_requests_destination_governorate">
<input type="text" data-field="x_destination_governorate" name="x_destination_governorate" id="x_destination_governorate" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pickup_requests->destination_governorate->PlaceHolder) ?>" value="<?php echo $pickup_requests->destination_governorate->EditValue ?>"<?php echo $pickup_requests->destination_governorate->EditAttributes() ?>>
</span>
<?php echo $pickup_requests->destination_governorate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pickup_requests->no_of_pieces->Visible) { // no_of_pieces ?>
	<div id="r_no_of_pieces" class="form-group">
		<label id="elh_pickup_requests_no_of_pieces" for="x_no_of_pieces" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->no_of_pieces->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->no_of_pieces->CellAttributes() ?>>
<span id="el_pickup_requests_no_of_pieces">
<input type="text" data-field="x_no_of_pieces" name="x_no_of_pieces" id="x_no_of_pieces" size="30" placeholder="<?php echo ew_HtmlEncode($pickup_requests->no_of_pieces->PlaceHolder) ?>" value="<?php echo $pickup_requests->no_of_pieces->EditValue ?>"<?php echo $pickup_requests->no_of_pieces->EditAttributes() ?>>
</span>
<?php echo $pickup_requests->no_of_pieces->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pickup_requests->pickup_date->Visible) { // pickup_date ?>
	<div id="r_pickup_date" class="form-group">
		<label id="elh_pickup_requests_pickup_date" for="x_pickup_date" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->pickup_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->pickup_date->CellAttributes() ?>>
<span id="el_pickup_requests_pickup_date">
<input type="text" data-field="x_pickup_date" name="x_pickup_date" id="x_pickup_date" placeholder="<?php echo ew_HtmlEncode($pickup_requests->pickup_date->PlaceHolder) ?>" value="<?php echo $pickup_requests->pickup_date->EditValue ?>"<?php echo $pickup_requests->pickup_date->EditAttributes() ?>>
<?php if (!$pickup_requests->pickup_date->ReadOnly && !$pickup_requests->pickup_date->Disabled && @$pickup_requests->pickup_date->EditAttrs["readonly"] == "" && @$pickup_requests->pickup_date->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
ew_CreateCalendar("fpickup_requestsadd", "x_pickup_date", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $pickup_requests->pickup_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pickup_requests->product_type->Visible) { // product_type ?>
	<div id="r_product_type" class="form-group">
		<label id="elh_pickup_requests_product_type" for="x_product_type" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->product_type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->product_type->CellAttributes() ?>>
<span id="el_pickup_requests_product_type">
<input type="text" data-field="x_product_type" name="x_product_type" id="x_product_type" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pickup_requests->product_type->PlaceHolder) ?>" value="<?php echo $pickup_requests->product_type->EditValue ?>"<?php echo $pickup_requests->product_type->EditAttributes() ?>>
</span>
<?php echo $pickup_requests->product_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pickup_requests->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_pickup_requests_status" for="x_status" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->status->CellAttributes() ?>>
<span id="el_pickup_requests_status">
<select data-field="x_status" id="x_status" name="x_status"<?php echo $pickup_requests->status->EditAttributes() ?>>
<?php
if (is_array($pickup_requests->status->EditValue)) {
	$arwrk = $pickup_requests->status->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pickup_requests->status->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $pickup_requests->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pickup_requests->created->Visible) { // created ?>
	<div id="r_created" class="form-group">
		<label id="elh_pickup_requests_created" for="x_created" class="col-sm-2 control-label ewLabel"><?php echo $pickup_requests->created->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pickup_requests->created->CellAttributes() ?>>
<span id="el_pickup_requests_created">
<input type="text" data-field="x_created" name="x_created" id="x_created" placeholder="<?php echo ew_HtmlEncode($pickup_requests->created->PlaceHolder) ?>" value="<?php echo $pickup_requests->created->EditValue ?>"<?php echo $pickup_requests->created->EditAttributes() ?>>
</span>
<?php echo $pickup_requests->created->CustomMsg ?></div></div>
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
fpickup_requestsadd.Init();
</script>
<?php
$pickup_requests_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$pickup_requests_add->Page_Terminate();
?>
