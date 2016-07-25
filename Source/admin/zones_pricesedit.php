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

$zones_prices_edit = NULL; // Initialize page object first

class czones_prices_edit extends czones_prices {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'zones_prices';

	// Page object name
	var $PageObjName = 'zones_prices_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (@$_GET["weight"] <> "") {
			$this->weight->setQueryStringValue($_GET["weight"]);
			$this->RecKey["weight"] = $this->weight->QueryStringValue;
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
			$this->Page_Terminate("zones_priceslist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->weight->CurrentValue) == strval($this->Recordset->fields('weight'))) {
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
					$this->Page_Terminate("zones_priceslist.php"); // Return to list page
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
		if (!$this->weight->FldIsDetailKey) {
			$this->weight->setFormValue($objForm->GetValue("x_weight"));
		}
		if (!$this->zone1->FldIsDetailKey) {
			$this->zone1->setFormValue($objForm->GetValue("x_zone1"));
		}
		if (!$this->zone2->FldIsDetailKey) {
			$this->zone2->setFormValue($objForm->GetValue("x_zone2"));
		}
		if (!$this->zone3->FldIsDetailKey) {
			$this->zone3->setFormValue($objForm->GetValue("x_zone3"));
		}
		if (!$this->zone4->FldIsDetailKey) {
			$this->zone4->setFormValue($objForm->GetValue("x_zone4"));
		}
		if (!$this->zone5->FldIsDetailKey) {
			$this->zone5->setFormValue($objForm->GetValue("x_zone5"));
		}
		if (!$this->zone6->FldIsDetailKey) {
			$this->zone6->setFormValue($objForm->GetValue("x_zone6"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->weight->CurrentValue = $this->weight->FormValue;
		$this->zone1->CurrentValue = $this->zone1->FormValue;
		$this->zone2->CurrentValue = $this->zone2->FormValue;
		$this->zone3->CurrentValue = $this->zone3->FormValue;
		$this->zone4->CurrentValue = $this->zone4->FormValue;
		$this->zone5->CurrentValue = $this->zone5->FormValue;
		$this->zone6->CurrentValue = $this->zone6->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// weight
			$this->weight->EditAttrs["class"] = "form-control";
			$this->weight->EditCustomAttributes = "";
			$this->weight->EditValue = $this->weight->CurrentValue;
			$this->weight->ViewCustomAttributes = "";

			// zone1
			$this->zone1->EditAttrs["class"] = "form-control";
			$this->zone1->EditCustomAttributes = "";
			$this->zone1->EditValue = ew_HtmlEncode($this->zone1->CurrentValue);
			$this->zone1->PlaceHolder = ew_RemoveHtml($this->zone1->FldCaption());
			if (strval($this->zone1->EditValue) <> "" && is_numeric($this->zone1->EditValue)) $this->zone1->EditValue = ew_FormatNumber($this->zone1->EditValue, -2, -1, -2, 0);

			// zone2
			$this->zone2->EditAttrs["class"] = "form-control";
			$this->zone2->EditCustomAttributes = "";
			$this->zone2->EditValue = ew_HtmlEncode($this->zone2->CurrentValue);
			$this->zone2->PlaceHolder = ew_RemoveHtml($this->zone2->FldCaption());
			if (strval($this->zone2->EditValue) <> "" && is_numeric($this->zone2->EditValue)) $this->zone2->EditValue = ew_FormatNumber($this->zone2->EditValue, -2, -1, -2, 0);

			// zone3
			$this->zone3->EditAttrs["class"] = "form-control";
			$this->zone3->EditCustomAttributes = "";
			$this->zone3->EditValue = ew_HtmlEncode($this->zone3->CurrentValue);
			$this->zone3->PlaceHolder = ew_RemoveHtml($this->zone3->FldCaption());
			if (strval($this->zone3->EditValue) <> "" && is_numeric($this->zone3->EditValue)) $this->zone3->EditValue = ew_FormatNumber($this->zone3->EditValue, -2, -1, -2, 0);

			// zone4
			$this->zone4->EditAttrs["class"] = "form-control";
			$this->zone4->EditCustomAttributes = "";
			$this->zone4->EditValue = ew_HtmlEncode($this->zone4->CurrentValue);
			$this->zone4->PlaceHolder = ew_RemoveHtml($this->zone4->FldCaption());
			if (strval($this->zone4->EditValue) <> "" && is_numeric($this->zone4->EditValue)) $this->zone4->EditValue = ew_FormatNumber($this->zone4->EditValue, -2, -1, -2, 0);

			// zone5
			$this->zone5->EditAttrs["class"] = "form-control";
			$this->zone5->EditCustomAttributes = "";
			$this->zone5->EditValue = ew_HtmlEncode($this->zone5->CurrentValue);
			$this->zone5->PlaceHolder = ew_RemoveHtml($this->zone5->FldCaption());
			if (strval($this->zone5->EditValue) <> "" && is_numeric($this->zone5->EditValue)) $this->zone5->EditValue = ew_FormatNumber($this->zone5->EditValue, -2, -1, -2, 0);

			// zone6
			$this->zone6->EditAttrs["class"] = "form-control";
			$this->zone6->EditCustomAttributes = "";
			$this->zone6->EditValue = ew_HtmlEncode($this->zone6->CurrentValue);
			$this->zone6->PlaceHolder = ew_RemoveHtml($this->zone6->FldCaption());
			if (strval($this->zone6->EditValue) <> "" && is_numeric($this->zone6->EditValue)) $this->zone6->EditValue = ew_FormatNumber($this->zone6->EditValue, -2, -1, -2, 0);

			// Edit refer script
			// weight

			$this->weight->HrefValue = "";

			// zone1
			$this->zone1->HrefValue = "";

			// zone2
			$this->zone2->HrefValue = "";

			// zone3
			$this->zone3->HrefValue = "";

			// zone4
			$this->zone4->HrefValue = "";

			// zone5
			$this->zone5->HrefValue = "";

			// zone6
			$this->zone6->HrefValue = "";
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
		if (!$this->weight->FldIsDetailKey && !is_null($this->weight->FormValue) && $this->weight->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->weight->FldCaption(), $this->weight->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->weight->FormValue)) {
			ew_AddMessage($gsFormError, $this->weight->FldErrMsg());
		}
		if (!$this->zone1->FldIsDetailKey && !is_null($this->zone1->FormValue) && $this->zone1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->zone1->FldCaption(), $this->zone1->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->zone1->FormValue)) {
			ew_AddMessage($gsFormError, $this->zone1->FldErrMsg());
		}
		if (!$this->zone2->FldIsDetailKey && !is_null($this->zone2->FormValue) && $this->zone2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->zone2->FldCaption(), $this->zone2->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->zone2->FormValue)) {
			ew_AddMessage($gsFormError, $this->zone2->FldErrMsg());
		}
		if (!$this->zone3->FldIsDetailKey && !is_null($this->zone3->FormValue) && $this->zone3->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->zone3->FldCaption(), $this->zone3->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->zone3->FormValue)) {
			ew_AddMessage($gsFormError, $this->zone3->FldErrMsg());
		}
		if (!$this->zone4->FldIsDetailKey && !is_null($this->zone4->FormValue) && $this->zone4->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->zone4->FldCaption(), $this->zone4->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->zone4->FormValue)) {
			ew_AddMessage($gsFormError, $this->zone4->FldErrMsg());
		}
		if (!$this->zone5->FldIsDetailKey && !is_null($this->zone5->FormValue) && $this->zone5->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->zone5->FldCaption(), $this->zone5->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->zone5->FormValue)) {
			ew_AddMessage($gsFormError, $this->zone5->FldErrMsg());
		}
		if (!$this->zone6->FldIsDetailKey && !is_null($this->zone6->FormValue) && $this->zone6->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->zone6->FldCaption(), $this->zone6->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->zone6->FormValue)) {
			ew_AddMessage($gsFormError, $this->zone6->FldErrMsg());
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

			// weight
			// zone1

			$this->zone1->SetDbValueDef($rsnew, $this->zone1->CurrentValue, 0, $this->zone1->ReadOnly);

			// zone2
			$this->zone2->SetDbValueDef($rsnew, $this->zone2->CurrentValue, 0, $this->zone2->ReadOnly);

			// zone3
			$this->zone3->SetDbValueDef($rsnew, $this->zone3->CurrentValue, 0, $this->zone3->ReadOnly);

			// zone4
			$this->zone4->SetDbValueDef($rsnew, $this->zone4->CurrentValue, 0, $this->zone4->ReadOnly);

			// zone5
			$this->zone5->SetDbValueDef($rsnew, $this->zone5->CurrentValue, 0, $this->zone5->ReadOnly);

			// zone6
			$this->zone6->SetDbValueDef($rsnew, $this->zone6->CurrentValue, 0, $this->zone6->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "zones_priceslist.php", "", $this->TableVar, TRUE);
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
if (!isset($zones_prices_edit)) $zones_prices_edit = new czones_prices_edit();

// Page init
$zones_prices_edit->Page_Init();

// Page main
$zones_prices_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$zones_prices_edit->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var zones_prices_edit = new ew_Page("zones_prices_edit");
zones_prices_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = zones_prices_edit.PageID; // For backward compatibility

// Form object
var fzones_pricesedit = new ew_Form("fzones_pricesedit");

// Validate form
fzones_pricesedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_weight");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zones_prices->weight->FldCaption(), $zones_prices->weight->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_weight");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zones_prices->weight->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_zone1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zones_prices->zone1->FldCaption(), $zones_prices->zone1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_zone1");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zones_prices->zone1->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_zone2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zones_prices->zone2->FldCaption(), $zones_prices->zone2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_zone2");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zones_prices->zone2->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_zone3");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zones_prices->zone3->FldCaption(), $zones_prices->zone3->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_zone3");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zones_prices->zone3->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_zone4");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zones_prices->zone4->FldCaption(), $zones_prices->zone4->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_zone4");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zones_prices->zone4->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_zone5");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zones_prices->zone5->FldCaption(), $zones_prices->zone5->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_zone5");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zones_prices->zone5->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_zone6");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zones_prices->zone6->FldCaption(), $zones_prices->zone6->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_zone6");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zones_prices->zone6->FldErrMsg()) ?>");

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
fzones_pricesedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fzones_pricesedit.ValidateRequired = true;
<?php } else { ?>
fzones_pricesedit.ValidateRequired = false; 
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
<?php $zones_prices_edit->ShowPageHeader(); ?>
<?php
$zones_prices_edit->ShowMessage();
?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($zones_prices_edit->Pager)) $zones_prices_edit->Pager = new cNumericPager($zones_prices_edit->StartRec, $zones_prices_edit->DisplayRecs, $zones_prices_edit->TotalRecs, $zones_prices_edit->RecRange) ?>
<?php if ($zones_prices_edit->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($zones_prices_edit->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $zones_prices_edit->PageUrl() ?>start=<?php echo $zones_prices_edit->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($zones_prices_edit->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $zones_prices_edit->PageUrl() ?>start=<?php echo $zones_prices_edit->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($zones_prices_edit->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $zones_prices_edit->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($zones_prices_edit->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $zones_prices_edit->PageUrl() ?>start=<?php echo $zones_prices_edit->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($zones_prices_edit->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $zones_prices_edit->PageUrl() ?>start=<?php echo $zones_prices_edit->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<form name="fzones_pricesedit" id="fzones_pricesedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($zones_prices_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $zones_prices_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="zones_prices">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($zones_prices->weight->Visible) { // weight ?>
	<div id="r_weight" class="form-group">
		<label id="elh_zones_prices_weight" for="x_weight" class="col-sm-2 control-label ewLabel"><?php echo $zones_prices->weight->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zones_prices->weight->CellAttributes() ?>>
<span id="el_zones_prices_weight">
<span<?php echo $zones_prices->weight->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $zones_prices->weight->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_weight" name="x_weight" id="x_weight" value="<?php echo ew_HtmlEncode($zones_prices->weight->CurrentValue) ?>">
<?php echo $zones_prices->weight->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zones_prices->zone1->Visible) { // zone1 ?>
	<div id="r_zone1" class="form-group">
		<label id="elh_zones_prices_zone1" for="x_zone1" class="col-sm-2 control-label ewLabel"><?php echo $zones_prices->zone1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zones_prices->zone1->CellAttributes() ?>>
<span id="el_zones_prices_zone1">
<input type="text" data-field="x_zone1" name="x_zone1" id="x_zone1" size="30" placeholder="<?php echo ew_HtmlEncode($zones_prices->zone1->PlaceHolder) ?>" value="<?php echo $zones_prices->zone1->EditValue ?>"<?php echo $zones_prices->zone1->EditAttributes() ?>>
</span>
<?php echo $zones_prices->zone1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zones_prices->zone2->Visible) { // zone2 ?>
	<div id="r_zone2" class="form-group">
		<label id="elh_zones_prices_zone2" for="x_zone2" class="col-sm-2 control-label ewLabel"><?php echo $zones_prices->zone2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zones_prices->zone2->CellAttributes() ?>>
<span id="el_zones_prices_zone2">
<input type="text" data-field="x_zone2" name="x_zone2" id="x_zone2" size="30" placeholder="<?php echo ew_HtmlEncode($zones_prices->zone2->PlaceHolder) ?>" value="<?php echo $zones_prices->zone2->EditValue ?>"<?php echo $zones_prices->zone2->EditAttributes() ?>>
</span>
<?php echo $zones_prices->zone2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zones_prices->zone3->Visible) { // zone3 ?>
	<div id="r_zone3" class="form-group">
		<label id="elh_zones_prices_zone3" for="x_zone3" class="col-sm-2 control-label ewLabel"><?php echo $zones_prices->zone3->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zones_prices->zone3->CellAttributes() ?>>
<span id="el_zones_prices_zone3">
<input type="text" data-field="x_zone3" name="x_zone3" id="x_zone3" size="30" placeholder="<?php echo ew_HtmlEncode($zones_prices->zone3->PlaceHolder) ?>" value="<?php echo $zones_prices->zone3->EditValue ?>"<?php echo $zones_prices->zone3->EditAttributes() ?>>
</span>
<?php echo $zones_prices->zone3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zones_prices->zone4->Visible) { // zone4 ?>
	<div id="r_zone4" class="form-group">
		<label id="elh_zones_prices_zone4" for="x_zone4" class="col-sm-2 control-label ewLabel"><?php echo $zones_prices->zone4->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zones_prices->zone4->CellAttributes() ?>>
<span id="el_zones_prices_zone4">
<input type="text" data-field="x_zone4" name="x_zone4" id="x_zone4" size="30" placeholder="<?php echo ew_HtmlEncode($zones_prices->zone4->PlaceHolder) ?>" value="<?php echo $zones_prices->zone4->EditValue ?>"<?php echo $zones_prices->zone4->EditAttributes() ?>>
</span>
<?php echo $zones_prices->zone4->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zones_prices->zone5->Visible) { // zone5 ?>
	<div id="r_zone5" class="form-group">
		<label id="elh_zones_prices_zone5" for="x_zone5" class="col-sm-2 control-label ewLabel"><?php echo $zones_prices->zone5->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zones_prices->zone5->CellAttributes() ?>>
<span id="el_zones_prices_zone5">
<input type="text" data-field="x_zone5" name="x_zone5" id="x_zone5" size="30" placeholder="<?php echo ew_HtmlEncode($zones_prices->zone5->PlaceHolder) ?>" value="<?php echo $zones_prices->zone5->EditValue ?>"<?php echo $zones_prices->zone5->EditAttributes() ?>>
</span>
<?php echo $zones_prices->zone5->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zones_prices->zone6->Visible) { // zone6 ?>
	<div id="r_zone6" class="form-group">
		<label id="elh_zones_prices_zone6" for="x_zone6" class="col-sm-2 control-label ewLabel"><?php echo $zones_prices->zone6->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zones_prices->zone6->CellAttributes() ?>>
<span id="el_zones_prices_zone6">
<input type="text" data-field="x_zone6" name="x_zone6" id="x_zone6" size="30" placeholder="<?php echo ew_HtmlEncode($zones_prices->zone6->PlaceHolder) ?>" value="<?php echo $zones_prices->zone6->EditValue ?>"<?php echo $zones_prices->zone6->EditAttributes() ?>>
</span>
<?php echo $zones_prices->zone6->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
<?php if (!isset($zones_prices_edit->Pager)) $zones_prices_edit->Pager = new cNumericPager($zones_prices_edit->StartRec, $zones_prices_edit->DisplayRecs, $zones_prices_edit->TotalRecs, $zones_prices_edit->RecRange) ?>
<?php if ($zones_prices_edit->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($zones_prices_edit->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $zones_prices_edit->PageUrl() ?>start=<?php echo $zones_prices_edit->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($zones_prices_edit->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $zones_prices_edit->PageUrl() ?>start=<?php echo $zones_prices_edit->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($zones_prices_edit->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $zones_prices_edit->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($zones_prices_edit->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $zones_prices_edit->PageUrl() ?>start=<?php echo $zones_prices_edit->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($zones_prices_edit->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $zones_prices_edit->PageUrl() ?>start=<?php echo $zones_prices_edit->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<script type="text/javascript">
fzones_pricesedit.Init();
</script>
<?php
$zones_prices_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$zones_prices_edit->Page_Terminate();
?>
