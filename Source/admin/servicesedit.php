<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "servicesinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "administratorinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$services_edit = NULL; // Initialize page object first

class cservices_edit extends cservices {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'services';

	// Page object name
	var $PageObjName = 'services_edit';

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

		// Table object (services)
		if (!isset($GLOBALS["services"]) || get_class($GLOBALS["services"]) == "cservices") {
			$GLOBALS["services"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["services"];
		}

		// Table object (administrator)
		if (!isset($GLOBALS['administrator'])) $GLOBALS['administrator'] = new cadministrator();

		// User table object (administrator)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cadministrator();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'services', TRUE);

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
		global $EW_EXPORT, $services;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($services);
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
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Load key from QueryString
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
			$this->RecKey["id"] = $this->id->QueryStringValue;
		} else {
			$bLoadCurrentRecord = TRUE;
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load recordset
		$this->StartRec = 1; // Initialize start position
		if ($this->Recordset = $this->LoadRecordset()) // Load records
			$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
		if ($this->TotalRecs <= 0) { // No record found
			if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$this->Page_Terminate("serviceslist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->id->CurrentValue) == strval($this->Recordset->fields('id'))) {
					$this->setStartRecordNumber($this->StartRec); // Save record position
					$bMatchRecord = TRUE;
					break;
				} else {
					$this->StartRec++;
					$this->Recordset->MoveNext();
				}
			}
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$bMatchRecord) {
					if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
						$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
					$this->Page_Terminate("serviceslist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->title_en->FldIsDetailKey) {
			$this->title_en->setFormValue($objForm->GetValue("x_title_en"));
		}
		if (!$this->title_ar->FldIsDetailKey) {
			$this->title_ar->setFormValue($objForm->GetValue("x_title_ar"));
		}
		if (!$this->brief_en->FldIsDetailKey) {
			$this->brief_en->setFormValue($objForm->GetValue("x_brief_en"));
		}
		if (!$this->brief_ar->FldIsDetailKey) {
			$this->brief_ar->setFormValue($objForm->GetValue("x_brief_ar"));
		}
		if (!$this->type->FldIsDetailKey) {
			$this->type->setFormValue($objForm->GetValue("x_type"));
		}
		if (!$this->color->FldIsDetailKey) {
			$this->color->setFormValue($objForm->GetValue("x_color"));
		}
		if (!$this->desc_en->FldIsDetailKey) {
			$this->desc_en->setFormValue($objForm->GetValue("x_desc_en"));
		}
		if (!$this->desc_ar->FldIsDetailKey) {
			$this->desc_ar->setFormValue($objForm->GetValue("x_desc_ar"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->title_en->CurrentValue = $this->title_en->FormValue;
		$this->title_ar->CurrentValue = $this->title_ar->FormValue;
		$this->brief_en->CurrentValue = $this->brief_en->FormValue;
		$this->brief_ar->CurrentValue = $this->brief_ar->FormValue;
		$this->type->CurrentValue = $this->type->FormValue;
		$this->color->CurrentValue = $this->color->FormValue;
		$this->desc_en->CurrentValue = $this->desc_en->FormValue;
		$this->desc_ar->CurrentValue = $this->desc_ar->FormValue;
	}

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
		$this->title_en->setDbValue($rs->fields('title_en'));
		$this->title_ar->setDbValue($rs->fields('title_ar'));
		$this->brief_en->setDbValue($rs->fields('brief_en'));
		$this->brief_ar->setDbValue($rs->fields('brief_ar'));
		$this->type->setDbValue($rs->fields('type'));
		$this->color->setDbValue($rs->fields('color'));
		$this->desc_en->setDbValue($rs->fields('desc_en'));
		$this->desc_ar->setDbValue($rs->fields('desc_ar'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->title_en->DbValue = $row['title_en'];
		$this->title_ar->DbValue = $row['title_ar'];
		$this->brief_en->DbValue = $row['brief_en'];
		$this->brief_ar->DbValue = $row['brief_ar'];
		$this->type->DbValue = $row['type'];
		$this->color->DbValue = $row['color'];
		$this->desc_en->DbValue = $row['desc_en'];
		$this->desc_ar->DbValue = $row['desc_ar'];
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
		// title_en
		// title_ar
		// brief_en
		// brief_ar
		// type
		// color
		// desc_en
		// desc_ar

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// title_en
			$this->title_en->ViewValue = $this->title_en->CurrentValue;
			$this->title_en->ViewCustomAttributes = "";

			// title_ar
			$this->title_ar->ViewValue = $this->title_ar->CurrentValue;
			$this->title_ar->ViewCustomAttributes = "";

			// brief_en
			$this->brief_en->ViewValue = $this->brief_en->CurrentValue;
			$this->brief_en->ViewCustomAttributes = "";

			// brief_ar
			$this->brief_ar->ViewValue = $this->brief_ar->CurrentValue;
			$this->brief_ar->ViewCustomAttributes = "";

			// type
			if (strval($this->type->CurrentValue) <> "") {
				switch ($this->type->CurrentValue) {
					case $this->type->FldTagValue(1):
						$this->type->ViewValue = $this->type->FldTagCaption(1) <> "" ? $this->type->FldTagCaption(1) : $this->type->CurrentValue;
						break;
					case $this->type->FldTagValue(2):
						$this->type->ViewValue = $this->type->FldTagCaption(2) <> "" ? $this->type->FldTagCaption(2) : $this->type->CurrentValue;
						break;
					default:
						$this->type->ViewValue = $this->type->CurrentValue;
				}
			} else {
				$this->type->ViewValue = NULL;
			}
			$this->type->ViewCustomAttributes = "";

			// color
			if (strval($this->color->CurrentValue) <> "") {
				switch ($this->color->CurrentValue) {
					case $this->color->FldTagValue(1):
						$this->color->ViewValue = $this->color->FldTagCaption(1) <> "" ? $this->color->FldTagCaption(1) : $this->color->CurrentValue;
						break;
					case $this->color->FldTagValue(2):
						$this->color->ViewValue = $this->color->FldTagCaption(2) <> "" ? $this->color->FldTagCaption(2) : $this->color->CurrentValue;
						break;
					case $this->color->FldTagValue(3):
						$this->color->ViewValue = $this->color->FldTagCaption(3) <> "" ? $this->color->FldTagCaption(3) : $this->color->CurrentValue;
						break;
					case $this->color->FldTagValue(4):
						$this->color->ViewValue = $this->color->FldTagCaption(4) <> "" ? $this->color->FldTagCaption(4) : $this->color->CurrentValue;
						break;
					case $this->color->FldTagValue(5):
						$this->color->ViewValue = $this->color->FldTagCaption(5) <> "" ? $this->color->FldTagCaption(5) : $this->color->CurrentValue;
						break;
					case $this->color->FldTagValue(6):
						$this->color->ViewValue = $this->color->FldTagCaption(6) <> "" ? $this->color->FldTagCaption(6) : $this->color->CurrentValue;
						break;
					case $this->color->FldTagValue(7):
						$this->color->ViewValue = $this->color->FldTagCaption(7) <> "" ? $this->color->FldTagCaption(7) : $this->color->CurrentValue;
						break;
					case $this->color->FldTagValue(8):
						$this->color->ViewValue = $this->color->FldTagCaption(8) <> "" ? $this->color->FldTagCaption(8) : $this->color->CurrentValue;
						break;
					default:
						$this->color->ViewValue = $this->color->CurrentValue;
				}
			} else {
				$this->color->ViewValue = NULL;
			}
			$this->color->ViewCustomAttributes = "";

			// desc_en
			$this->desc_en->ViewValue = $this->desc_en->CurrentValue;
			$this->desc_en->ViewCustomAttributes = "";

			// desc_ar
			$this->desc_ar->ViewValue = $this->desc_ar->CurrentValue;
			$this->desc_ar->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// title_en
			$this->title_en->LinkCustomAttributes = "";
			$this->title_en->HrefValue = "";
			$this->title_en->TooltipValue = "";

			// title_ar
			$this->title_ar->LinkCustomAttributes = "";
			$this->title_ar->HrefValue = "";
			$this->title_ar->TooltipValue = "";

			// brief_en
			$this->brief_en->LinkCustomAttributes = "";
			$this->brief_en->HrefValue = "";
			$this->brief_en->TooltipValue = "";

			// brief_ar
			$this->brief_ar->LinkCustomAttributes = "";
			$this->brief_ar->HrefValue = "";
			$this->brief_ar->TooltipValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";

			// color
			$this->color->LinkCustomAttributes = "";
			$this->color->HrefValue = "";
			$this->color->TooltipValue = "";

			// desc_en
			$this->desc_en->LinkCustomAttributes = "";
			$this->desc_en->HrefValue = "";
			$this->desc_en->TooltipValue = "";

			// desc_ar
			$this->desc_ar->LinkCustomAttributes = "";
			$this->desc_ar->HrefValue = "";
			$this->desc_ar->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// title_en
			$this->title_en->EditAttrs["class"] = "form-control";
			$this->title_en->EditCustomAttributes = "";
			$this->title_en->EditValue = ew_HtmlEncode($this->title_en->CurrentValue);
			$this->title_en->PlaceHolder = ew_RemoveHtml($this->title_en->FldCaption());

			// title_ar
			$this->title_ar->EditAttrs["class"] = "form-control";
			$this->title_ar->EditCustomAttributes = "";
			$this->title_ar->EditValue = ew_HtmlEncode($this->title_ar->CurrentValue);
			$this->title_ar->PlaceHolder = ew_RemoveHtml($this->title_ar->FldCaption());

			// brief_en
			$this->brief_en->EditAttrs["class"] = "form-control";
			$this->brief_en->EditCustomAttributes = "";
			$this->brief_en->EditValue = ew_HtmlEncode($this->brief_en->CurrentValue);
			$this->brief_en->PlaceHolder = ew_RemoveHtml($this->brief_en->FldCaption());

			// brief_ar
			$this->brief_ar->EditAttrs["class"] = "form-control";
			$this->brief_ar->EditCustomAttributes = "";
			$this->brief_ar->EditValue = ew_HtmlEncode($this->brief_ar->CurrentValue);
			$this->brief_ar->PlaceHolder = ew_RemoveHtml($this->brief_ar->FldCaption());

			// type
			$this->type->EditAttrs["class"] = "form-control";
			$this->type->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->type->FldTagValue(1), $this->type->FldTagCaption(1) <> "" ? $this->type->FldTagCaption(1) : $this->type->FldTagValue(1));
			$arwrk[] = array($this->type->FldTagValue(2), $this->type->FldTagCaption(2) <> "" ? $this->type->FldTagCaption(2) : $this->type->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->type->EditValue = $arwrk;

			// color
			$this->color->EditAttrs["class"] = "form-control";
			$this->color->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->color->FldTagValue(1), $this->color->FldTagCaption(1) <> "" ? $this->color->FldTagCaption(1) : $this->color->FldTagValue(1));
			$arwrk[] = array($this->color->FldTagValue(2), $this->color->FldTagCaption(2) <> "" ? $this->color->FldTagCaption(2) : $this->color->FldTagValue(2));
			$arwrk[] = array($this->color->FldTagValue(3), $this->color->FldTagCaption(3) <> "" ? $this->color->FldTagCaption(3) : $this->color->FldTagValue(3));
			$arwrk[] = array($this->color->FldTagValue(4), $this->color->FldTagCaption(4) <> "" ? $this->color->FldTagCaption(4) : $this->color->FldTagValue(4));
			$arwrk[] = array($this->color->FldTagValue(5), $this->color->FldTagCaption(5) <> "" ? $this->color->FldTagCaption(5) : $this->color->FldTagValue(5));
			$arwrk[] = array($this->color->FldTagValue(6), $this->color->FldTagCaption(6) <> "" ? $this->color->FldTagCaption(6) : $this->color->FldTagValue(6));
			$arwrk[] = array($this->color->FldTagValue(7), $this->color->FldTagCaption(7) <> "" ? $this->color->FldTagCaption(7) : $this->color->FldTagValue(7));
			$arwrk[] = array($this->color->FldTagValue(8), $this->color->FldTagCaption(8) <> "" ? $this->color->FldTagCaption(8) : $this->color->FldTagValue(8));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->color->EditValue = $arwrk;

			// desc_en
			$this->desc_en->EditAttrs["class"] = "form-control";
			$this->desc_en->EditCustomAttributes = "";
			$this->desc_en->EditValue = ew_HtmlEncode($this->desc_en->CurrentValue);
			$this->desc_en->PlaceHolder = ew_RemoveHtml($this->desc_en->FldCaption());

			// desc_ar
			$this->desc_ar->EditAttrs["class"] = "form-control";
			$this->desc_ar->EditCustomAttributes = "";
			$this->desc_ar->EditValue = ew_HtmlEncode($this->desc_ar->CurrentValue);
			$this->desc_ar->PlaceHolder = ew_RemoveHtml($this->desc_ar->FldCaption());

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// title_en
			$this->title_en->HrefValue = "";

			// title_ar
			$this->title_ar->HrefValue = "";

			// brief_en
			$this->brief_en->HrefValue = "";

			// brief_ar
			$this->brief_ar->HrefValue = "";

			// type
			$this->type->HrefValue = "";

			// color
			$this->color->HrefValue = "";

			// desc_en
			$this->desc_en->HrefValue = "";

			// desc_ar
			$this->desc_ar->HrefValue = "";
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
		if (!$this->title_en->FldIsDetailKey && !is_null($this->title_en->FormValue) && $this->title_en->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->title_en->FldCaption(), $this->title_en->ReqErrMsg));
		}
		if (!$this->title_ar->FldIsDetailKey && !is_null($this->title_ar->FormValue) && $this->title_ar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->title_ar->FldCaption(), $this->title_ar->ReqErrMsg));
		}
		if (!$this->brief_en->FldIsDetailKey && !is_null($this->brief_en->FormValue) && $this->brief_en->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->brief_en->FldCaption(), $this->brief_en->ReqErrMsg));
		}
		if (!$this->brief_ar->FldIsDetailKey && !is_null($this->brief_ar->FormValue) && $this->brief_ar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->brief_ar->FldCaption(), $this->brief_ar->ReqErrMsg));
		}
		if (!$this->type->FldIsDetailKey && !is_null($this->type->FormValue) && $this->type->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->type->FldCaption(), $this->type->ReqErrMsg));
		}
		if (!$this->color->FldIsDetailKey && !is_null($this->color->FormValue) && $this->color->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->color->FldCaption(), $this->color->ReqErrMsg));
		}
		if (!$this->desc_en->FldIsDetailKey && !is_null($this->desc_en->FormValue) && $this->desc_en->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->desc_en->FldCaption(), $this->desc_en->ReqErrMsg));
		}
		if (!$this->desc_ar->FldIsDetailKey && !is_null($this->desc_ar->FormValue) && $this->desc_ar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->desc_ar->FldCaption(), $this->desc_ar->ReqErrMsg));
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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// title_en
			$this->title_en->SetDbValueDef($rsnew, $this->title_en->CurrentValue, "", $this->title_en->ReadOnly);

			// title_ar
			$this->title_ar->SetDbValueDef($rsnew, $this->title_ar->CurrentValue, "", $this->title_ar->ReadOnly);

			// brief_en
			$this->brief_en->SetDbValueDef($rsnew, $this->brief_en->CurrentValue, "", $this->brief_en->ReadOnly);

			// brief_ar
			$this->brief_ar->SetDbValueDef($rsnew, $this->brief_ar->CurrentValue, "", $this->brief_ar->ReadOnly);

			// type
			$this->type->SetDbValueDef($rsnew, $this->type->CurrentValue, "", $this->type->ReadOnly);

			// color
			$this->color->SetDbValueDef($rsnew, $this->color->CurrentValue, "", $this->color->ReadOnly);

			// desc_en
			$this->desc_en->SetDbValueDef($rsnew, $this->desc_en->CurrentValue, "", $this->desc_en->ReadOnly);

			// desc_ar
			$this->desc_ar->SetDbValueDef($rsnew, $this->desc_ar->CurrentValue, "", $this->desc_ar->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "serviceslist.php", "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, ew_CurrentUrl());
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
if (!isset($services_edit)) $services_edit = new cservices_edit();

// Page init
$services_edit->Page_Init();

// Page main
$services_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$services_edit->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var services_edit = new ew_Page("services_edit");
services_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = services_edit.PageID; // For backward compatibility

// Form object
var fservicesedit = new ew_Form("fservicesedit");

// Validate form
fservicesedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_title_en");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $services->title_en->FldCaption(), $services->title_en->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_title_ar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $services->title_ar->FldCaption(), $services->title_ar->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_brief_en");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $services->brief_en->FldCaption(), $services->brief_en->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_brief_ar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $services->brief_ar->FldCaption(), $services->brief_ar->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_type");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $services->type->FldCaption(), $services->type->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_color");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $services->color->FldCaption(), $services->color->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_desc_en");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $services->desc_en->FldCaption(), $services->desc_en->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_desc_ar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $services->desc_ar->FldCaption(), $services->desc_ar->ReqErrMsg)) ?>");

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
fservicesedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fservicesedit.ValidateRequired = true;
<?php } else { ?>
fservicesedit.ValidateRequired = false; 
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
<?php $services_edit->ShowPageHeader(); ?>
<?php
$services_edit->ShowMessage();
?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($services_edit->Pager)) $services_edit->Pager = new cNumericPager($services_edit->StartRec, $services_edit->DisplayRecs, $services_edit->TotalRecs, $services_edit->RecRange) ?>
<?php if ($services_edit->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($services_edit->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $services_edit->PageUrl() ?>start=<?php echo $services_edit->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($services_edit->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $services_edit->PageUrl() ?>start=<?php echo $services_edit->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($services_edit->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $services_edit->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($services_edit->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $services_edit->PageUrl() ?>start=<?php echo $services_edit->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($services_edit->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $services_edit->PageUrl() ?>start=<?php echo $services_edit->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<form name="fservicesedit" id="fservicesedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($services_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $services_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="services">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($services->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_services_id" class="col-sm-2 control-label ewLabel"><?php echo $services->id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $services->id->CellAttributes() ?>>
<span id="el_services_id">
<span<?php echo $services->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $services->id->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($services->id->CurrentValue) ?>">
<?php echo $services->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($services->title_en->Visible) { // title_en ?>
	<div id="r_title_en" class="form-group">
		<label id="elh_services_title_en" for="x_title_en" class="col-sm-2 control-label ewLabel"><?php echo $services->title_en->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $services->title_en->CellAttributes() ?>>
<span id="el_services_title_en">
<input type="text" data-field="x_title_en" name="x_title_en" id="x_title_en" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($services->title_en->PlaceHolder) ?>" value="<?php echo $services->title_en->EditValue ?>"<?php echo $services->title_en->EditAttributes() ?>>
</span>
<?php echo $services->title_en->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($services->title_ar->Visible) { // title_ar ?>
	<div id="r_title_ar" class="form-group">
		<label id="elh_services_title_ar" for="x_title_ar" class="col-sm-2 control-label ewLabel"><?php echo $services->title_ar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $services->title_ar->CellAttributes() ?>>
<span id="el_services_title_ar">
<input type="text" data-field="x_title_ar" name="x_title_ar" id="x_title_ar" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($services->title_ar->PlaceHolder) ?>" value="<?php echo $services->title_ar->EditValue ?>"<?php echo $services->title_ar->EditAttributes() ?>>
</span>
<?php echo $services->title_ar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($services->brief_en->Visible) { // brief_en ?>
	<div id="r_brief_en" class="form-group">
		<label id="elh_services_brief_en" for="x_brief_en" class="col-sm-2 control-label ewLabel"><?php echo $services->brief_en->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $services->brief_en->CellAttributes() ?>>
<span id="el_services_brief_en">
<textarea data-field="x_brief_en" name="x_brief_en" id="x_brief_en" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($services->brief_en->PlaceHolder) ?>"<?php echo $services->brief_en->EditAttributes() ?>><?php echo $services->brief_en->EditValue ?></textarea>
</span>
<?php echo $services->brief_en->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($services->brief_ar->Visible) { // brief_ar ?>
	<div id="r_brief_ar" class="form-group">
		<label id="elh_services_brief_ar" for="x_brief_ar" class="col-sm-2 control-label ewLabel"><?php echo $services->brief_ar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $services->brief_ar->CellAttributes() ?>>
<span id="el_services_brief_ar">
<textarea data-field="x_brief_ar" name="x_brief_ar" id="x_brief_ar" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($services->brief_ar->PlaceHolder) ?>"<?php echo $services->brief_ar->EditAttributes() ?>><?php echo $services->brief_ar->EditValue ?></textarea>
</span>
<?php echo $services->brief_ar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($services->type->Visible) { // type ?>
	<div id="r_type" class="form-group">
		<label id="elh_services_type" for="x_type" class="col-sm-2 control-label ewLabel"><?php echo $services->type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $services->type->CellAttributes() ?>>
<span id="el_services_type">
<select data-field="x_type" id="x_type" name="x_type"<?php echo $services->type->EditAttributes() ?>>
<?php
if (is_array($services->type->EditValue)) {
	$arwrk = $services->type->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($services->type->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $services->type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($services->color->Visible) { // color ?>
	<div id="r_color" class="form-group">
		<label id="elh_services_color" for="x_color" class="col-sm-2 control-label ewLabel"><?php echo $services->color->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $services->color->CellAttributes() ?>>
<span id="el_services_color">
<select data-field="x_color" id="x_color" name="x_color"<?php echo $services->color->EditAttributes() ?>>
<?php
if (is_array($services->color->EditValue)) {
	$arwrk = $services->color->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($services->color->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $services->color->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($services->desc_en->Visible) { // desc_en ?>
	<div id="r_desc_en" class="form-group">
		<label id="elh_services_desc_en" class="col-sm-2 control-label ewLabel"><?php echo $services->desc_en->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $services->desc_en->CellAttributes() ?>>
<span id="el_services_desc_en">
<textarea data-field="x_desc_en" class="editor" name="x_desc_en" id="x_desc_en" cols="50" rows="5" placeholder="<?php echo ew_HtmlEncode($services->desc_en->PlaceHolder) ?>"<?php echo $services->desc_en->EditAttributes() ?>><?php echo $services->desc_en->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fservicesedit", "x_desc_en", 50, 5, <?php echo ($services->desc_en->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $services->desc_en->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($services->desc_ar->Visible) { // desc_ar ?>
	<div id="r_desc_ar" class="form-group">
		<label id="elh_services_desc_ar" class="col-sm-2 control-label ewLabel"><?php echo $services->desc_ar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $services->desc_ar->CellAttributes() ?>>
<span id="el_services_desc_ar">
<textarea data-field="x_desc_ar" class="editor" name="x_desc_ar" id="x_desc_ar" cols="50" rows="5" placeholder="<?php echo ew_HtmlEncode($services->desc_ar->PlaceHolder) ?>"<?php echo $services->desc_ar->EditAttributes() ?>><?php echo $services->desc_ar->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fservicesedit", "x_desc_ar", 50, 5, <?php echo ($services->desc_ar->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $services->desc_ar->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
<?php if (!isset($services_edit->Pager)) $services_edit->Pager = new cNumericPager($services_edit->StartRec, $services_edit->DisplayRecs, $services_edit->TotalRecs, $services_edit->RecRange) ?>
<?php if ($services_edit->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($services_edit->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $services_edit->PageUrl() ?>start=<?php echo $services_edit->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($services_edit->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $services_edit->PageUrl() ?>start=<?php echo $services_edit->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($services_edit->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $services_edit->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($services_edit->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $services_edit->PageUrl() ?>start=<?php echo $services_edit->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($services_edit->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $services_edit->PageUrl() ?>start=<?php echo $services_edit->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<script type="text/javascript">
fservicesedit.Init();
</script>
<?php
$services_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$services_edit->Page_Terminate();
?>
