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

$job_candidates_add = NULL; // Initialize page object first

class cjob_candidates_add extends cjob_candidates {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'job_candidates';

	// Page object name
	var $PageObjName = 'job_candidates_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Set up master/detail parameters
		$this->SetUpMasterParms();

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
					$this->Page_Terminate("job_candidateslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "job_candidatesview.php")
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
		$this->cv->Upload->Index = $objForm->Index;
		$this->cv->Upload->UploadFile();
		$this->cv->CurrentValue = $this->cv->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->job_id->CurrentValue = NULL;
		$this->job_id->OldValue = $this->job_id->CurrentValue;
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->mobile->CurrentValue = NULL;
		$this->mobile->OldValue = $this->mobile->CurrentValue;
		$this->cv->Upload->DbValue = NULL;
		$this->cv->OldValue = $this->cv->Upload->DbValue;
		$this->cv->CurrentValue = NULL; // Clear file related field
		$this->applied_date->CurrentValue = NULL;
		$this->applied_date->OldValue = $this->applied_date->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->job_id->FldIsDetailKey) {
			$this->job_id->setFormValue($objForm->GetValue("x_job_id"));
		}
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->mobile->FldIsDetailKey) {
			$this->mobile->setFormValue($objForm->GetValue("x_mobile"));
		}
		if (!$this->applied_date->FldIsDetailKey) {
			$this->applied_date->setFormValue($objForm->GetValue("x_applied_date"));
			$this->applied_date->CurrentValue = ew_UnFormatDateTime($this->applied_date->CurrentValue, 7);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->job_id->CurrentValue = $this->job_id->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->mobile->CurrentValue = $this->mobile->FormValue;
		$this->applied_date->CurrentValue = $this->applied_date->FormValue;
		$this->applied_date->CurrentValue = ew_UnFormatDateTime($this->applied_date->CurrentValue, 7);
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// job_id
			$this->job_id->EditAttrs["class"] = "form-control";
			$this->job_id->EditCustomAttributes = "";
			if ($this->job_id->getSessionValue() <> "") {
				$this->job_id->CurrentValue = $this->job_id->getSessionValue();
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
			} else {
			if (trim(strval($this->job_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->job_id->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT DISTINCT `id`, `title_en` AS `DispFld`, `title_ar` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `jobs`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->job_id, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `title_en` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->job_id->EditValue = $arwrk;
			}

			// name
			$this->name->EditAttrs["class"] = "form-control";
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// mobile
			$this->mobile->EditAttrs["class"] = "form-control";
			$this->mobile->EditCustomAttributes = "";
			$this->mobile->EditValue = ew_HtmlEncode($this->mobile->CurrentValue);
			$this->mobile->PlaceHolder = ew_RemoveHtml($this->mobile->FldCaption());

			// cv
			$this->cv->EditAttrs["class"] = "form-control";
			$this->cv->EditCustomAttributes = "";
			$this->cv->UploadPath = '../webroot/uploads/jobs/';
			if (!ew_Empty($this->cv->Upload->DbValue)) {
				$this->cv->EditValue = $this->cv->Upload->DbValue;
			} else {
				$this->cv->EditValue = "";
			}
			if (!ew_Empty($this->cv->CurrentValue))
				$this->cv->Upload->FileName = $this->cv->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->cv);

			// applied_date
			$this->applied_date->EditAttrs["class"] = "form-control";
			$this->applied_date->EditCustomAttributes = "";
			$this->applied_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->applied_date->CurrentValue, 7));
			$this->applied_date->PlaceHolder = ew_RemoveHtml($this->applied_date->FldCaption());

			// Edit refer script
			// job_id

			$this->job_id->HrefValue = "";

			// name
			$this->name->HrefValue = "";

			// email
			$this->_email->HrefValue = "";

			// mobile
			$this->mobile->HrefValue = "";

			// cv
			$this->cv->UploadPath = '../webroot/uploads/jobs/';
			if (!ew_Empty($this->cv->Upload->DbValue)) {
				$this->cv->HrefValue = ew_UploadPathEx(FALSE, $this->cv->UploadPath) . $this->cv->Upload->DbValue; // Add prefix/suffix
				$this->cv->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->cv->HrefValue = ew_ConvertFullUrl($this->cv->HrefValue);
			} else {
				$this->cv->HrefValue = "";
			}
			$this->cv->HrefValue2 = $this->cv->UploadPath . $this->cv->Upload->DbValue;

			// applied_date
			$this->applied_date->HrefValue = "";
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
		if (!$this->job_id->FldIsDetailKey && !is_null($this->job_id->FormValue) && $this->job_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->job_id->FldCaption(), $this->job_id->ReqErrMsg));
		}
		if (!$this->name->FldIsDetailKey && !is_null($this->name->FormValue) && $this->name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->name->FldCaption(), $this->name->ReqErrMsg));
		}
		if (!$this->_email->FldIsDetailKey && !is_null($this->_email->FormValue) && $this->_email->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_email->FldCaption(), $this->_email->ReqErrMsg));
		}
		if (!$this->mobile->FldIsDetailKey && !is_null($this->mobile->FormValue) && $this->mobile->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->mobile->FldCaption(), $this->mobile->ReqErrMsg));
		}
		if ($this->cv->Upload->FileName == "" && !$this->cv->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cv->FldCaption(), $this->cv->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->applied_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->applied_date->FldErrMsg());
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

		// Check referential integrity for master table 'jobs'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_jobs();
		if (strval($this->job_id->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@id@", ew_AdjustSql($this->job_id->CurrentValue), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			$rsmaster = $GLOBALS["jobs"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "jobs", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
			$this->cv->OldUploadPath = '../webroot/uploads/jobs/';
			$this->cv->UploadPath = $this->cv->OldUploadPath;
		}
		$rsnew = array();

		// job_id
		$this->job_id->SetDbValueDef($rsnew, $this->job_id->CurrentValue, 0, FALSE);

		// name
		$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, "", FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, "", FALSE);

		// mobile
		$this->mobile->SetDbValueDef($rsnew, $this->mobile->CurrentValue, "", FALSE);

		// cv
		if (!$this->cv->Upload->KeepFile) {
			$this->cv->Upload->DbValue = ""; // No need to delete old file
			if ($this->cv->Upload->FileName == "") {
				$rsnew['cv'] = NULL;
			} else {
				$rsnew['cv'] = $this->cv->Upload->FileName;
			}
		}

		// applied_date
		$this->applied_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->applied_date->CurrentValue, 7), NULL, FALSE);
		if (!$this->cv->Upload->KeepFile) {
			$this->cv->UploadPath = '../webroot/uploads/jobs/';
			if (!ew_Empty($this->cv->Upload->Value)) {
				if ($this->cv->Upload->FileName == $this->cv->Upload->DbValue) { // Overwrite if same file name
					$this->cv->Upload->DbValue = ""; // No need to delete any more
				} else {
					$rsnew['cv'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->cv->UploadPath), $rsnew['cv']); // Get new file name
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
				if (!$this->cv->Upload->KeepFile) {
					if (!ew_Empty($this->cv->Upload->Value)) {
						$this->cv->Upload->SaveToFile($this->cv->UploadPath, $rsnew['cv'], TRUE);
					}
					if ($this->cv->Upload->DbValue <> "")
						@unlink(ew_UploadPathEx(TRUE, $this->cv->OldUploadPath) . $this->cv->Upload->DbValue);
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

		// cv
		ew_CleanUploadTempPath($this->cv, $this->cv->Upload->Index);
		return $AddRow;
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
if (!isset($job_candidates_add)) $job_candidates_add = new cjob_candidates_add();

// Page init
$job_candidates_add->Page_Init();

// Page main
$job_candidates_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$job_candidates_add->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var job_candidates_add = new ew_Page("job_candidates_add");
job_candidates_add.PageID = "add"; // Page ID
var EW_PAGE_ID = job_candidates_add.PageID; // For backward compatibility

// Form object
var fjob_candidatesadd = new ew_Form("fjob_candidatesadd");

// Validate form
fjob_candidatesadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_job_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $job_candidates->job_id->FldCaption(), $job_candidates->job_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $job_candidates->name->FldCaption(), $job_candidates->name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $job_candidates->_email->FldCaption(), $job_candidates->_email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_mobile");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $job_candidates->mobile->FldCaption(), $job_candidates->mobile->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_cv");
			elm = this.GetElements("fn_x" + infix + "_cv");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $job_candidates->cv->FldCaption(), $job_candidates->cv->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_applied_date");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($job_candidates->applied_date->FldErrMsg()) ?>");

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
fjob_candidatesadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fjob_candidatesadd.ValidateRequired = true;
<?php } else { ?>
fjob_candidatesadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fjob_candidatesadd.Lists["x_job_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_title_en","x_title_ar","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $job_candidates_add->ShowPageHeader(); ?>
<?php
$job_candidates_add->ShowMessage();
?>
<form name="fjob_candidatesadd" id="fjob_candidatesadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($job_candidates_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $job_candidates_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="job_candidates">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($job_candidates->job_id->Visible) { // job_id ?>
	<div id="r_job_id" class="form-group">
		<label id="elh_job_candidates_job_id" for="x_job_id" class="col-sm-2 control-label ewLabel"><?php echo $job_candidates->job_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $job_candidates->job_id->CellAttributes() ?>>
<?php if ($job_candidates->job_id->getSessionValue() <> "") { ?>
<span id="el_job_candidates_job_id">
<span<?php echo $job_candidates->job_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $job_candidates->job_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_job_id" name="x_job_id" value="<?php echo ew_HtmlEncode($job_candidates->job_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el_job_candidates_job_id">
<select data-field="x_job_id" id="x_job_id" name="x_job_id"<?php echo $job_candidates->job_id->EditAttributes() ?>>
<?php
if (is_array($job_candidates->job_id->EditValue)) {
	$arwrk = $job_candidates->job_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($job_candidates->job_id->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$job_candidates->job_id) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT DISTINCT `id`, `title_en` AS `DispFld`, `title_ar` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `jobs`";
$sWhereWrk = "";

// Call Lookup selecting
$job_candidates->Lookup_Selecting($job_candidates->job_id, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `title_en` ASC";
?>
<input type="hidden" name="s_x_job_id" id="s_x_job_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&amp;t0=19">
</span>
<?php } ?>
<?php echo $job_candidates->job_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($job_candidates->name->Visible) { // name ?>
	<div id="r_name" class="form-group">
		<label id="elh_job_candidates_name" for="x_name" class="col-sm-2 control-label ewLabel"><?php echo $job_candidates->name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $job_candidates->name->CellAttributes() ?>>
<span id="el_job_candidates_name">
<input type="text" data-field="x_name" name="x_name" id="x_name" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($job_candidates->name->PlaceHolder) ?>" value="<?php echo $job_candidates->name->EditValue ?>"<?php echo $job_candidates->name->EditAttributes() ?>>
</span>
<?php echo $job_candidates->name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($job_candidates->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_job_candidates__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $job_candidates->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $job_candidates->_email->CellAttributes() ?>>
<span id="el_job_candidates__email">
<input type="text" data-field="x__email" name="x__email" id="x__email" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($job_candidates->_email->PlaceHolder) ?>" value="<?php echo $job_candidates->_email->EditValue ?>"<?php echo $job_candidates->_email->EditAttributes() ?>>
</span>
<?php echo $job_candidates->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($job_candidates->mobile->Visible) { // mobile ?>
	<div id="r_mobile" class="form-group">
		<label id="elh_job_candidates_mobile" for="x_mobile" class="col-sm-2 control-label ewLabel"><?php echo $job_candidates->mobile->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $job_candidates->mobile->CellAttributes() ?>>
<span id="el_job_candidates_mobile">
<input type="text" data-field="x_mobile" name="x_mobile" id="x_mobile" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($job_candidates->mobile->PlaceHolder) ?>" value="<?php echo $job_candidates->mobile->EditValue ?>"<?php echo $job_candidates->mobile->EditAttributes() ?>>
</span>
<?php echo $job_candidates->mobile->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($job_candidates->cv->Visible) { // cv ?>
	<div id="r_cv" class="form-group">
		<label id="elh_job_candidates_cv" class="col-sm-2 control-label ewLabel"><?php echo $job_candidates->cv->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $job_candidates->cv->CellAttributes() ?>>
<span id="el_job_candidates_cv">
<div id="fd_x_cv">
<span title="<?php echo $job_candidates->cv->FldTitle() ? $job_candidates->cv->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($job_candidates->cv->ReadOnly || $job_candidates->cv->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_cv" name="x_cv" id="x_cv">
</span>
<input type="hidden" name="fn_x_cv" id= "fn_x_cv" value="<?php echo $job_candidates->cv->Upload->FileName ?>">
<input type="hidden" name="fa_x_cv" id= "fa_x_cv" value="0">
<input type="hidden" name="fs_x_cv" id= "fs_x_cv" value="255">
<input type="hidden" name="fx_x_cv" id= "fx_x_cv" value="<?php echo $job_candidates->cv->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_cv" id= "fm_x_cv" value="<?php echo $job_candidates->cv->UploadMaxFileSize ?>">
</div>
<table id="ft_x_cv" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $job_candidates->cv->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($job_candidates->applied_date->Visible) { // applied_date ?>
	<div id="r_applied_date" class="form-group">
		<label id="elh_job_candidates_applied_date" for="x_applied_date" class="col-sm-2 control-label ewLabel"><?php echo $job_candidates->applied_date->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $job_candidates->applied_date->CellAttributes() ?>>
<span id="el_job_candidates_applied_date">
<input type="text" data-field="x_applied_date" name="x_applied_date" id="x_applied_date" placeholder="<?php echo ew_HtmlEncode($job_candidates->applied_date->PlaceHolder) ?>" value="<?php echo $job_candidates->applied_date->EditValue ?>"<?php echo $job_candidates->applied_date->EditAttributes() ?>>
</span>
<?php echo $job_candidates->applied_date->CustomMsg ?></div></div>
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
fjob_candidatesadd.Init();
</script>
<?php
$job_candidates_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$job_candidates_add->Page_Terminate();
?>
