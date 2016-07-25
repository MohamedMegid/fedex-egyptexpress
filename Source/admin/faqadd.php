<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "faqinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "administratorinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$faq_add = NULL; // Initialize page object first

class cfaq_add extends cfaq {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'faq';

	// Page object name
	var $PageObjName = 'faq_add';

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

		// Table object (faq)
		if (!isset($GLOBALS["faq"]) || get_class($GLOBALS["faq"]) == "cfaq") {
			$GLOBALS["faq"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["faq"];
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
			define("EW_TABLE_NAME", 'faq', TRUE);

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
		global $EW_EXPORT, $faq;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($faq);
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
					$this->Page_Terminate("faqlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "faqview.php")
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
		$this->question_en->CurrentValue = NULL;
		$this->question_en->OldValue = $this->question_en->CurrentValue;
		$this->question_ar->CurrentValue = NULL;
		$this->question_ar->OldValue = $this->question_ar->CurrentValue;
		$this->answer_en->CurrentValue = NULL;
		$this->answer_en->OldValue = $this->answer_en->CurrentValue;
		$this->answer_ar->CurrentValue = NULL;
		$this->answer_ar->OldValue = $this->answer_ar->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->question_en->FldIsDetailKey) {
			$this->question_en->setFormValue($objForm->GetValue("x_question_en"));
		}
		if (!$this->question_ar->FldIsDetailKey) {
			$this->question_ar->setFormValue($objForm->GetValue("x_question_ar"));
		}
		if (!$this->answer_en->FldIsDetailKey) {
			$this->answer_en->setFormValue($objForm->GetValue("x_answer_en"));
		}
		if (!$this->answer_ar->FldIsDetailKey) {
			$this->answer_ar->setFormValue($objForm->GetValue("x_answer_ar"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->question_en->CurrentValue = $this->question_en->FormValue;
		$this->question_ar->CurrentValue = $this->question_ar->FormValue;
		$this->answer_en->CurrentValue = $this->answer_en->FormValue;
		$this->answer_ar->CurrentValue = $this->answer_ar->FormValue;
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
		$this->question_en->setDbValue($rs->fields('question_en'));
		$this->question_ar->setDbValue($rs->fields('question_ar'));
		$this->answer_en->setDbValue($rs->fields('answer_en'));
		$this->answer_ar->setDbValue($rs->fields('answer_ar'));
		$this->last_modified->setDbValue($rs->fields('last_modified'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->question_en->DbValue = $row['question_en'];
		$this->question_ar->DbValue = $row['question_ar'];
		$this->answer_en->DbValue = $row['answer_en'];
		$this->answer_ar->DbValue = $row['answer_ar'];
		$this->last_modified->DbValue = $row['last_modified'];
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
		// question_en
		// question_ar
		// answer_en
		// answer_ar
		// last_modified

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// question_en
			$this->question_en->ViewValue = $this->question_en->CurrentValue;
			$this->question_en->ViewCustomAttributes = "";

			// question_ar
			$this->question_ar->ViewValue = $this->question_ar->CurrentValue;
			$this->question_ar->ViewCustomAttributes = "";

			// answer_en
			$this->answer_en->ViewValue = $this->answer_en->CurrentValue;
			$this->answer_en->ViewCustomAttributes = "";

			// answer_ar
			$this->answer_ar->ViewValue = $this->answer_ar->CurrentValue;
			$this->answer_ar->ViewCustomAttributes = "";

			// last_modified
			$this->last_modified->ViewValue = $this->last_modified->CurrentValue;
			$this->last_modified->ViewValue = ew_FormatDateTime($this->last_modified->ViewValue, 7);
			$this->last_modified->ViewCustomAttributes = "";

			// question_en
			$this->question_en->LinkCustomAttributes = "";
			$this->question_en->HrefValue = "";
			$this->question_en->TooltipValue = "";

			// question_ar
			$this->question_ar->LinkCustomAttributes = "";
			$this->question_ar->HrefValue = "";
			$this->question_ar->TooltipValue = "";

			// answer_en
			$this->answer_en->LinkCustomAttributes = "";
			$this->answer_en->HrefValue = "";
			$this->answer_en->TooltipValue = "";

			// answer_ar
			$this->answer_ar->LinkCustomAttributes = "";
			$this->answer_ar->HrefValue = "";
			$this->answer_ar->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// question_en
			$this->question_en->EditAttrs["class"] = "form-control";
			$this->question_en->EditCustomAttributes = "";
			$this->question_en->EditValue = ew_HtmlEncode($this->question_en->CurrentValue);
			$this->question_en->PlaceHolder = ew_RemoveHtml($this->question_en->FldCaption());

			// question_ar
			$this->question_ar->EditAttrs["class"] = "form-control";
			$this->question_ar->EditCustomAttributes = "";
			$this->question_ar->EditValue = ew_HtmlEncode($this->question_ar->CurrentValue);
			$this->question_ar->PlaceHolder = ew_RemoveHtml($this->question_ar->FldCaption());

			// answer_en
			$this->answer_en->EditAttrs["class"] = "form-control";
			$this->answer_en->EditCustomAttributes = "";
			$this->answer_en->EditValue = ew_HtmlEncode($this->answer_en->CurrentValue);
			$this->answer_en->PlaceHolder = ew_RemoveHtml($this->answer_en->FldCaption());

			// answer_ar
			$this->answer_ar->EditAttrs["class"] = "form-control";
			$this->answer_ar->EditCustomAttributes = "";
			$this->answer_ar->EditValue = ew_HtmlEncode($this->answer_ar->CurrentValue);
			$this->answer_ar->PlaceHolder = ew_RemoveHtml($this->answer_ar->FldCaption());

			// Edit refer script
			// question_en

			$this->question_en->HrefValue = "";

			// question_ar
			$this->question_ar->HrefValue = "";

			// answer_en
			$this->answer_en->HrefValue = "";

			// answer_ar
			$this->answer_ar->HrefValue = "";
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
		if (!$this->question_en->FldIsDetailKey && !is_null($this->question_en->FormValue) && $this->question_en->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->question_en->FldCaption(), $this->question_en->ReqErrMsg));
		}
		if (!$this->question_ar->FldIsDetailKey && !is_null($this->question_ar->FormValue) && $this->question_ar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->question_ar->FldCaption(), $this->question_ar->ReqErrMsg));
		}
		if (!$this->answer_en->FldIsDetailKey && !is_null($this->answer_en->FormValue) && $this->answer_en->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->answer_en->FldCaption(), $this->answer_en->ReqErrMsg));
		}
		if (!$this->answer_ar->FldIsDetailKey && !is_null($this->answer_ar->FormValue) && $this->answer_ar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->answer_ar->FldCaption(), $this->answer_ar->ReqErrMsg));
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

		// question_en
		$this->question_en->SetDbValueDef($rsnew, $this->question_en->CurrentValue, "", FALSE);

		// question_ar
		$this->question_ar->SetDbValueDef($rsnew, $this->question_ar->CurrentValue, "", FALSE);

		// answer_en
		$this->answer_en->SetDbValueDef($rsnew, $this->answer_en->CurrentValue, "", FALSE);

		// answer_ar
		$this->answer_ar->SetDbValueDef($rsnew, $this->answer_ar->CurrentValue, "", FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, "faqlist.php", "", $this->TableVar, TRUE);
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
if (!isset($faq_add)) $faq_add = new cfaq_add();

// Page init
$faq_add->Page_Init();

// Page main
$faq_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$faq_add->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var faq_add = new ew_Page("faq_add");
faq_add.PageID = "add"; // Page ID
var EW_PAGE_ID = faq_add.PageID; // For backward compatibility

// Form object
var ffaqadd = new ew_Form("ffaqadd");

// Validate form
ffaqadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_question_en");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $faq->question_en->FldCaption(), $faq->question_en->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_question_ar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $faq->question_ar->FldCaption(), $faq->question_ar->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_answer_en");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $faq->answer_en->FldCaption(), $faq->answer_en->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_answer_ar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $faq->answer_ar->FldCaption(), $faq->answer_ar->ReqErrMsg)) ?>");

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
ffaqadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffaqadd.ValidateRequired = true;
<?php } else { ?>
ffaqadd.ValidateRequired = false; 
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
<?php $faq_add->ShowPageHeader(); ?>
<?php
$faq_add->ShowMessage();
?>
<form name="ffaqadd" id="ffaqadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($faq_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $faq_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="faq">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($faq->question_en->Visible) { // question_en ?>
	<div id="r_question_en" class="form-group">
		<label id="elh_faq_question_en" for="x_question_en" class="col-sm-2 control-label ewLabel"><?php echo $faq->question_en->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $faq->question_en->CellAttributes() ?>>
<span id="el_faq_question_en">
<input type="text" data-field="x_question_en" name="x_question_en" id="x_question_en" size="110" maxlength="255" placeholder="<?php echo ew_HtmlEncode($faq->question_en->PlaceHolder) ?>" value="<?php echo $faq->question_en->EditValue ?>"<?php echo $faq->question_en->EditAttributes() ?>>
</span>
<?php echo $faq->question_en->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($faq->question_ar->Visible) { // question_ar ?>
	<div id="r_question_ar" class="form-group">
		<label id="elh_faq_question_ar" for="x_question_ar" class="col-sm-2 control-label ewLabel"><?php echo $faq->question_ar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $faq->question_ar->CellAttributes() ?>>
<span id="el_faq_question_ar">
<input type="text" data-field="x_question_ar" name="x_question_ar" id="x_question_ar" size="110" maxlength="255" placeholder="<?php echo ew_HtmlEncode($faq->question_ar->PlaceHolder) ?>" value="<?php echo $faq->question_ar->EditValue ?>"<?php echo $faq->question_ar->EditAttributes() ?>>
</span>
<?php echo $faq->question_ar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($faq->answer_en->Visible) { // answer_en ?>
	<div id="r_answer_en" class="form-group">
		<label id="elh_faq_answer_en" class="col-sm-2 control-label ewLabel"><?php echo $faq->answer_en->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $faq->answer_en->CellAttributes() ?>>
<span id="el_faq_answer_en">
<textarea data-field="x_answer_en" class="editor" name="x_answer_en" id="x_answer_en" cols="50" rows="6" placeholder="<?php echo ew_HtmlEncode($faq->answer_en->PlaceHolder) ?>"<?php echo $faq->answer_en->EditAttributes() ?>><?php echo $faq->answer_en->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("ffaqadd", "x_answer_en", 50, 6, <?php echo ($faq->answer_en->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $faq->answer_en->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($faq->answer_ar->Visible) { // answer_ar ?>
	<div id="r_answer_ar" class="form-group">
		<label id="elh_faq_answer_ar" class="col-sm-2 control-label ewLabel"><?php echo $faq->answer_ar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $faq->answer_ar->CellAttributes() ?>>
<span id="el_faq_answer_ar">
<textarea data-field="x_answer_ar" class="editor" name="x_answer_ar" id="x_answer_ar" cols="50" rows="6" placeholder="<?php echo ew_HtmlEncode($faq->answer_ar->PlaceHolder) ?>"<?php echo $faq->answer_ar->EditAttributes() ?>><?php echo $faq->answer_ar->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("ffaqadd", "x_answer_ar", 50, 6, <?php echo ($faq->answer_ar->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $faq->answer_ar->CustomMsg ?></div></div>
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
ffaqadd.Init();
</script>
<?php
$faq_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$faq_add->Page_Terminate();
?>
