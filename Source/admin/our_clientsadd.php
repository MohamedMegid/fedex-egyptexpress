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

$our_clients_add = NULL; // Initialize page object first

class cour_clients_add extends cour_clients {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'our_clients';

	// Page object name
	var $PageObjName = 'our_clients_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
					$this->Page_Terminate("our_clientslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "our_clientsview.php")
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
		$this->off_image->Upload->Index = $objForm->Index;
		$this->off_image->Upload->UploadFile();
		$this->off_image->CurrentValue = $this->off_image->Upload->FileName;
		$this->on_image->Upload->Index = $objForm->Index;
		$this->on_image->Upload->UploadFile();
		$this->on_image->CurrentValue = $this->on_image->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->off_image->Upload->DbValue = NULL;
		$this->off_image->OldValue = $this->off_image->Upload->DbValue;
		$this->off_image->CurrentValue = NULL; // Clear file related field
		$this->on_image->Upload->DbValue = NULL;
		$this->on_image->OldValue = $this->on_image->Upload->DbValue;
		$this->on_image->CurrentValue = NULL; // Clear file related field
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// off_image
			$this->off_image->EditAttrs["class"] = "form-control";
			$this->off_image->EditCustomAttributes = "";
			$this->off_image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->off_image->Upload->DbValue)) {
				$this->off_image->ImageWidth = 150;
				$this->off_image->ImageHeight = 0;
				$this->off_image->ImageAlt = $this->off_image->FldAlt();
				$this->off_image->EditValue = ew_UploadPathEx(FALSE, $this->off_image->UploadPath) . $this->off_image->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->off_image->EditValue = ew_UploadPathEx(TRUE, $this->off_image->UploadPath) . $this->off_image->Upload->DbValue;
				}
			} else {
				$this->off_image->EditValue = "";
			}
			if (!ew_Empty($this->off_image->CurrentValue))
				$this->off_image->Upload->FileName = $this->off_image->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->off_image);

			// on_image
			$this->on_image->EditAttrs["class"] = "form-control";
			$this->on_image->EditCustomAttributes = "";
			$this->on_image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->on_image->Upload->DbValue)) {
				$this->on_image->ImageWidth = 150;
				$this->on_image->ImageHeight = 0;
				$this->on_image->ImageAlt = $this->on_image->FldAlt();
				$this->on_image->EditValue = ew_UploadPathEx(FALSE, $this->on_image->UploadPath) . $this->on_image->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->on_image->EditValue = ew_UploadPathEx(TRUE, $this->on_image->UploadPath) . $this->on_image->Upload->DbValue;
				}
			} else {
				$this->on_image->EditValue = "";
			}
			if (!ew_Empty($this->on_image->CurrentValue))
				$this->on_image->Upload->FileName = $this->on_image->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->on_image);

			// Edit refer script
			// off_image

			$this->off_image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->off_image->Upload->DbValue)) {
				$this->off_image->HrefValue = ew_UploadPathEx(FALSE, $this->off_image->UploadPath) . $this->off_image->Upload->DbValue; // Add prefix/suffix
				$this->off_image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->off_image->HrefValue = ew_ConvertFullUrl($this->off_image->HrefValue);
			} else {
				$this->off_image->HrefValue = "";
			}
			$this->off_image->HrefValue2 = $this->off_image->UploadPath . $this->off_image->Upload->DbValue;

			// on_image
			$this->on_image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->on_image->Upload->DbValue)) {
				$this->on_image->HrefValue = ew_UploadPathEx(FALSE, $this->on_image->UploadPath) . $this->on_image->Upload->DbValue; // Add prefix/suffix
				$this->on_image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->on_image->HrefValue = ew_ConvertFullUrl($this->on_image->HrefValue);
			} else {
				$this->on_image->HrefValue = "";
			}
			$this->on_image->HrefValue2 = $this->on_image->UploadPath . $this->on_image->Upload->DbValue;
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
		if ($this->off_image->Upload->FileName == "" && !$this->off_image->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->off_image->FldCaption(), $this->off_image->ReqErrMsg));
		}
		if ($this->on_image->Upload->FileName == "" && !$this->on_image->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->on_image->FldCaption(), $this->on_image->ReqErrMsg));
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
			$this->off_image->OldUploadPath = '../webroot/uploads/images/';
			$this->off_image->UploadPath = $this->off_image->OldUploadPath;
			$this->on_image->OldUploadPath = '../webroot/uploads/images/';
			$this->on_image->UploadPath = $this->on_image->OldUploadPath;
		}
		$rsnew = array();

		// off_image
		if (!$this->off_image->Upload->KeepFile) {
			$this->off_image->Upload->DbValue = ""; // No need to delete old file
			if ($this->off_image->Upload->FileName == "") {
				$rsnew['off_image'] = NULL;
			} else {
				$rsnew['off_image'] = $this->off_image->Upload->FileName;
			}
		}

		// on_image
		if (!$this->on_image->Upload->KeepFile) {
			$this->on_image->Upload->DbValue = ""; // No need to delete old file
			if ($this->on_image->Upload->FileName == "") {
				$rsnew['on_image'] = NULL;
			} else {
				$rsnew['on_image'] = $this->on_image->Upload->FileName;
			}
		}
		if (!$this->off_image->Upload->KeepFile) {
			$this->off_image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->off_image->Upload->Value)) {
				if ($this->off_image->Upload->FileName == $this->off_image->Upload->DbValue) { // Overwrite if same file name
					$this->off_image->Upload->DbValue = ""; // No need to delete any more
				} else {
					$rsnew['off_image'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->off_image->UploadPath), $rsnew['off_image']); // Get new file name
				}
			}
		}
		if (!$this->on_image->Upload->KeepFile) {
			$this->on_image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->on_image->Upload->Value)) {
				if ($this->on_image->Upload->FileName == $this->on_image->Upload->DbValue) { // Overwrite if same file name
					$this->on_image->Upload->DbValue = ""; // No need to delete any more
				} else {
					$rsnew['on_image'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->on_image->UploadPath), $rsnew['on_image']); // Get new file name
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
				if (!$this->off_image->Upload->KeepFile) {
					if (!ew_Empty($this->off_image->Upload->Value)) {
						$this->off_image->Upload->SaveToFile($this->off_image->UploadPath, $rsnew['off_image'], TRUE);
					}
					if ($this->off_image->Upload->DbValue <> "")
						@unlink(ew_UploadPathEx(TRUE, $this->off_image->OldUploadPath) . $this->off_image->Upload->DbValue);
				}
				if (!$this->on_image->Upload->KeepFile) {
					if (!ew_Empty($this->on_image->Upload->Value)) {
						$this->on_image->Upload->SaveToFile($this->on_image->UploadPath, $rsnew['on_image'], TRUE);
					}
					if ($this->on_image->Upload->DbValue <> "")
						@unlink(ew_UploadPathEx(TRUE, $this->on_image->OldUploadPath) . $this->on_image->Upload->DbValue);
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

		// off_image
		ew_CleanUploadTempPath($this->off_image, $this->off_image->Upload->Index);

		// on_image
		ew_CleanUploadTempPath($this->on_image, $this->on_image->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "our_clientslist.php", "", $this->TableVar, TRUE);
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
if (!isset($our_clients_add)) $our_clients_add = new cour_clients_add();

// Page init
$our_clients_add->Page_Init();

// Page main
$our_clients_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$our_clients_add->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var our_clients_add = new ew_Page("our_clients_add");
our_clients_add.PageID = "add"; // Page ID
var EW_PAGE_ID = our_clients_add.PageID; // For backward compatibility

// Form object
var four_clientsadd = new ew_Form("four_clientsadd");

// Validate form
four_clientsadd.Validate = function() {
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
			felm = this.GetElements("x" + infix + "_off_image");
			elm = this.GetElements("fn_x" + infix + "_off_image");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $our_clients->off_image->FldCaption(), $our_clients->off_image->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_on_image");
			elm = this.GetElements("fn_x" + infix + "_on_image");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $our_clients->on_image->FldCaption(), $our_clients->on_image->ReqErrMsg)) ?>");

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
four_clientsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
four_clientsadd.ValidateRequired = true;
<?php } else { ?>
four_clientsadd.ValidateRequired = false; 
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
<?php $our_clients_add->ShowPageHeader(); ?>
<?php
$our_clients_add->ShowMessage();
?>
<form name="four_clientsadd" id="four_clientsadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($our_clients_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $our_clients_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="our_clients">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($our_clients->off_image->Visible) { // off_image ?>
	<div id="r_off_image" class="form-group">
		<label id="elh_our_clients_off_image" class="col-sm-2 control-label ewLabel"><?php echo $our_clients->off_image->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $our_clients->off_image->CellAttributes() ?>>
<span id="el_our_clients_off_image">
<div id="fd_x_off_image">
<span title="<?php echo $our_clients->off_image->FldTitle() ? $our_clients->off_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($our_clients->off_image->ReadOnly || $our_clients->off_image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_off_image" name="x_off_image" id="x_off_image">
</span>
<input type="hidden" name="fn_x_off_image" id= "fn_x_off_image" value="<?php echo $our_clients->off_image->Upload->FileName ?>">
<input type="hidden" name="fa_x_off_image" id= "fa_x_off_image" value="0">
<input type="hidden" name="fs_x_off_image" id= "fs_x_off_image" value="255">
<input type="hidden" name="fx_x_off_image" id= "fx_x_off_image" value="<?php echo $our_clients->off_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_off_image" id= "fm_x_off_image" value="<?php echo $our_clients->off_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x_off_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $our_clients->off_image->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($our_clients->on_image->Visible) { // on_image ?>
	<div id="r_on_image" class="form-group">
		<label id="elh_our_clients_on_image" class="col-sm-2 control-label ewLabel"><?php echo $our_clients->on_image->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $our_clients->on_image->CellAttributes() ?>>
<span id="el_our_clients_on_image">
<div id="fd_x_on_image">
<span title="<?php echo $our_clients->on_image->FldTitle() ? $our_clients->on_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($our_clients->on_image->ReadOnly || $our_clients->on_image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_on_image" name="x_on_image" id="x_on_image">
</span>
<input type="hidden" name="fn_x_on_image" id= "fn_x_on_image" value="<?php echo $our_clients->on_image->Upload->FileName ?>">
<input type="hidden" name="fa_x_on_image" id= "fa_x_on_image" value="0">
<input type="hidden" name="fs_x_on_image" id= "fs_x_on_image" value="255">
<input type="hidden" name="fx_x_on_image" id= "fx_x_on_image" value="<?php echo $our_clients->on_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_on_image" id= "fm_x_on_image" value="<?php echo $our_clients->on_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x_on_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $our_clients->on_image->CustomMsg ?></div></div>
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
four_clientsadd.Init();
</script>
<?php
$our_clients_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$our_clients_add->Page_Terminate();
?>
