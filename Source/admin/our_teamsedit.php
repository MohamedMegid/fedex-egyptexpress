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

$our_teams_edit = NULL; // Initialize page object first

class cour_teams_edit extends cour_teams {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'our_teams';

	// Page object name
	var $PageObjName = 'our_teams_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
			$this->Page_Terminate("our_teamslist.php"); // Return to list page
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
					$this->Page_Terminate("our_teamslist.php"); // Return to list page
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
		$this->image->Upload->Index = $objForm->Index;
		$this->image->Upload->UploadFile();
		$this->image->CurrentValue = $this->image->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->name_en->FldIsDetailKey) {
			$this->name_en->setFormValue($objForm->GetValue("x_name_en"));
		}
		if (!$this->name_ar->FldIsDetailKey) {
			$this->name_ar->setFormValue($objForm->GetValue("x_name_ar"));
		}
		if (!$this->title_en->FldIsDetailKey) {
			$this->title_en->setFormValue($objForm->GetValue("x_title_en"));
		}
		if (!$this->title_ar->FldIsDetailKey) {
			$this->title_ar->setFormValue($objForm->GetValue("x_title_ar"));
		}
		if (!$this->bio_en->FldIsDetailKey) {
			$this->bio_en->setFormValue($objForm->GetValue("x_bio_en"));
		}
		if (!$this->bio_ar->FldIsDetailKey) {
			$this->bio_ar->setFormValue($objForm->GetValue("x_bio_ar"));
		}
		if (!$this->facebook->FldIsDetailKey) {
			$this->facebook->setFormValue($objForm->GetValue("x_facebook"));
		}
		if (!$this->twitter->FldIsDetailKey) {
			$this->twitter->setFormValue($objForm->GetValue("x_twitter"));
		}
		if (!$this->linkedin->FldIsDetailKey) {
			$this->linkedin->setFormValue($objForm->GetValue("x_linkedin"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->name_en->CurrentValue = $this->name_en->FormValue;
		$this->name_ar->CurrentValue = $this->name_ar->FormValue;
		$this->title_en->CurrentValue = $this->title_en->FormValue;
		$this->title_ar->CurrentValue = $this->title_ar->FormValue;
		$this->bio_en->CurrentValue = $this->bio_en->FormValue;
		$this->bio_ar->CurrentValue = $this->bio_ar->FormValue;
		$this->facebook->CurrentValue = $this->facebook->FormValue;
		$this->twitter->CurrentValue = $this->twitter->FormValue;
		$this->linkedin->CurrentValue = $this->linkedin->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// image
			$this->image->EditAttrs["class"] = "form-control";
			$this->image->EditCustomAttributes = "";
			$this->image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->ImageWidth = 100;
				$this->image->ImageHeight = 0;
				$this->image->ImageAlt = $this->image->FldAlt();
				$this->image->EditValue = ew_UploadPathEx(FALSE, $this->image->UploadPath) . $this->image->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->image->EditValue = ew_UploadPathEx(TRUE, $this->image->UploadPath) . $this->image->Upload->DbValue;
				}
			} else {
				$this->image->EditValue = "";
			}
			if (!ew_Empty($this->image->CurrentValue))
				$this->image->Upload->FileName = $this->image->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->image);

			// name_en
			$this->name_en->EditAttrs["class"] = "form-control";
			$this->name_en->EditCustomAttributes = "";
			$this->name_en->EditValue = ew_HtmlEncode($this->name_en->CurrentValue);
			$this->name_en->PlaceHolder = ew_RemoveHtml($this->name_en->FldCaption());

			// name_ar
			$this->name_ar->EditAttrs["class"] = "form-control";
			$this->name_ar->EditCustomAttributes = "";
			$this->name_ar->EditValue = ew_HtmlEncode($this->name_ar->CurrentValue);
			$this->name_ar->PlaceHolder = ew_RemoveHtml($this->name_ar->FldCaption());

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

			// bio_en
			$this->bio_en->EditAttrs["class"] = "form-control";
			$this->bio_en->EditCustomAttributes = "";
			$this->bio_en->EditValue = ew_HtmlEncode($this->bio_en->CurrentValue);
			$this->bio_en->PlaceHolder = ew_RemoveHtml($this->bio_en->FldCaption());

			// bio_ar
			$this->bio_ar->EditAttrs["class"] = "form-control";
			$this->bio_ar->EditCustomAttributes = "";
			$this->bio_ar->EditValue = ew_HtmlEncode($this->bio_ar->CurrentValue);
			$this->bio_ar->PlaceHolder = ew_RemoveHtml($this->bio_ar->FldCaption());

			// facebook
			$this->facebook->EditAttrs["class"] = "form-control";
			$this->facebook->EditCustomAttributes = "";
			$this->facebook->EditValue = ew_HtmlEncode($this->facebook->CurrentValue);
			$this->facebook->PlaceHolder = ew_RemoveHtml($this->facebook->FldCaption());

			// twitter
			$this->twitter->EditAttrs["class"] = "form-control";
			$this->twitter->EditCustomAttributes = "";
			$this->twitter->EditValue = ew_HtmlEncode($this->twitter->CurrentValue);
			$this->twitter->PlaceHolder = ew_RemoveHtml($this->twitter->FldCaption());

			// linkedin
			$this->linkedin->EditAttrs["class"] = "form-control";
			$this->linkedin->EditCustomAttributes = "";
			$this->linkedin->EditValue = ew_HtmlEncode($this->linkedin->CurrentValue);
			$this->linkedin->PlaceHolder = ew_RemoveHtml($this->linkedin->FldCaption());

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// image
			$this->image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->HrefValue = ew_UploadPathEx(FALSE, $this->image->UploadPath) . $this->image->Upload->DbValue; // Add prefix/suffix
				$this->image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->image->HrefValue = ew_ConvertFullUrl($this->image->HrefValue);
			} else {
				$this->image->HrefValue = "";
			}
			$this->image->HrefValue2 = $this->image->UploadPath . $this->image->Upload->DbValue;

			// name_en
			$this->name_en->HrefValue = "";

			// name_ar
			$this->name_ar->HrefValue = "";

			// title_en
			$this->title_en->HrefValue = "";

			// title_ar
			$this->title_ar->HrefValue = "";

			// bio_en
			$this->bio_en->HrefValue = "";

			// bio_ar
			$this->bio_ar->HrefValue = "";

			// facebook
			if (!ew_Empty($this->facebook->CurrentValue)) {
				$this->facebook->HrefValue = $this->facebook->CurrentValue; // Add prefix/suffix
				$this->facebook->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->facebook->HrefValue = ew_ConvertFullUrl($this->facebook->HrefValue);
			} else {
				$this->facebook->HrefValue = "";
			}

			// twitter
			if (!ew_Empty($this->twitter->CurrentValue)) {
				$this->twitter->HrefValue = $this->twitter->CurrentValue; // Add prefix/suffix
				$this->twitter->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->twitter->HrefValue = ew_ConvertFullUrl($this->twitter->HrefValue);
			} else {
				$this->twitter->HrefValue = "";
			}

			// linkedin
			if (!ew_Empty($this->linkedin->CurrentValue)) {
				$this->linkedin->HrefValue = $this->linkedin->CurrentValue; // Add prefix/suffix
				$this->linkedin->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->linkedin->HrefValue = ew_ConvertFullUrl($this->linkedin->HrefValue);
			} else {
				$this->linkedin->HrefValue = "";
			}
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
		if ($this->image->Upload->FileName == "" && !$this->image->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->image->FldCaption(), $this->image->ReqErrMsg));
		}
		if (!$this->name_en->FldIsDetailKey && !is_null($this->name_en->FormValue) && $this->name_en->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->name_en->FldCaption(), $this->name_en->ReqErrMsg));
		}
		if (!$this->name_ar->FldIsDetailKey && !is_null($this->name_ar->FormValue) && $this->name_ar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->name_ar->FldCaption(), $this->name_ar->ReqErrMsg));
		}
		if (!$this->title_en->FldIsDetailKey && !is_null($this->title_en->FormValue) && $this->title_en->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->title_en->FldCaption(), $this->title_en->ReqErrMsg));
		}
		if (!$this->title_ar->FldIsDetailKey && !is_null($this->title_ar->FormValue) && $this->title_ar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->title_ar->FldCaption(), $this->title_ar->ReqErrMsg));
		}
		if (!$this->bio_en->FldIsDetailKey && !is_null($this->bio_en->FormValue) && $this->bio_en->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bio_en->FldCaption(), $this->bio_en->ReqErrMsg));
		}
		if (!$this->bio_ar->FldIsDetailKey && !is_null($this->bio_ar->FormValue) && $this->bio_ar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bio_ar->FldCaption(), $this->bio_ar->ReqErrMsg));
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
			$this->image->OldUploadPath = '../webroot/uploads/images/';
			$this->image->UploadPath = $this->image->OldUploadPath;
			$rsnew = array();

			// image
			if (!($this->image->ReadOnly) && !$this->image->Upload->KeepFile) {
				$this->image->Upload->DbValue = $rsold['image']; // Get original value
				if ($this->image->Upload->FileName == "") {
					$rsnew['image'] = NULL;
				} else {
					$rsnew['image'] = $this->image->Upload->FileName;
				}
			}

			// name_en
			$this->name_en->SetDbValueDef($rsnew, $this->name_en->CurrentValue, "", $this->name_en->ReadOnly);

			// name_ar
			$this->name_ar->SetDbValueDef($rsnew, $this->name_ar->CurrentValue, "", $this->name_ar->ReadOnly);

			// title_en
			$this->title_en->SetDbValueDef($rsnew, $this->title_en->CurrentValue, "", $this->title_en->ReadOnly);

			// title_ar
			$this->title_ar->SetDbValueDef($rsnew, $this->title_ar->CurrentValue, "", $this->title_ar->ReadOnly);

			// bio_en
			$this->bio_en->SetDbValueDef($rsnew, $this->bio_en->CurrentValue, "", $this->bio_en->ReadOnly);

			// bio_ar
			$this->bio_ar->SetDbValueDef($rsnew, $this->bio_ar->CurrentValue, "", $this->bio_ar->ReadOnly);

			// facebook
			$this->facebook->SetDbValueDef($rsnew, $this->facebook->CurrentValue, NULL, $this->facebook->ReadOnly);

			// twitter
			$this->twitter->SetDbValueDef($rsnew, $this->twitter->CurrentValue, NULL, $this->twitter->ReadOnly);

			// linkedin
			$this->linkedin->SetDbValueDef($rsnew, $this->linkedin->CurrentValue, NULL, $this->linkedin->ReadOnly);
			if (!$this->image->Upload->KeepFile) {
				$this->image->UploadPath = '../webroot/uploads/images/';
				if (!ew_Empty($this->image->Upload->Value)) {
					if ($this->image->Upload->FileName == $this->image->Upload->DbValue) { // Overwrite if same file name
						$this->image->Upload->DbValue = ""; // No need to delete any more
					} else {
						$rsnew['image'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->image->UploadPath), $rsnew['image']); // Get new file name
					}
				}
			}

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
					if (!$this->image->Upload->KeepFile) {
						if (!ew_Empty($this->image->Upload->Value)) {
							$this->image->Upload->SaveToFile($this->image->UploadPath, $rsnew['image'], TRUE);
						}
						if ($this->image->Upload->DbValue <> "")
							@unlink(ew_UploadPathEx(TRUE, $this->image->OldUploadPath) . $this->image->Upload->DbValue);
					}
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

		// image
		ew_CleanUploadTempPath($this->image, $this->image->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "our_teamslist.php", "", $this->TableVar, TRUE);
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
if (!isset($our_teams_edit)) $our_teams_edit = new cour_teams_edit();

// Page init
$our_teams_edit->Page_Init();

// Page main
$our_teams_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$our_teams_edit->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var our_teams_edit = new ew_Page("our_teams_edit");
our_teams_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = our_teams_edit.PageID; // For backward compatibility

// Form object
var four_teamsedit = new ew_Form("four_teamsedit");

// Validate form
four_teamsedit.Validate = function() {
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
			felm = this.GetElements("x" + infix + "_image");
			elm = this.GetElements("fn_x" + infix + "_image");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $our_teams->image->FldCaption(), $our_teams->image->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_name_en");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $our_teams->name_en->FldCaption(), $our_teams->name_en->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_name_ar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $our_teams->name_ar->FldCaption(), $our_teams->name_ar->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_title_en");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $our_teams->title_en->FldCaption(), $our_teams->title_en->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_title_ar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $our_teams->title_ar->FldCaption(), $our_teams->title_ar->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bio_en");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $our_teams->bio_en->FldCaption(), $our_teams->bio_en->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bio_ar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $our_teams->bio_ar->FldCaption(), $our_teams->bio_ar->ReqErrMsg)) ?>");

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
four_teamsedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
four_teamsedit.ValidateRequired = true;
<?php } else { ?>
four_teamsedit.ValidateRequired = false; 
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
<?php $our_teams_edit->ShowPageHeader(); ?>
<?php
$our_teams_edit->ShowMessage();
?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($our_teams_edit->Pager)) $our_teams_edit->Pager = new cNumericPager($our_teams_edit->StartRec, $our_teams_edit->DisplayRecs, $our_teams_edit->TotalRecs, $our_teams_edit->RecRange) ?>
<?php if ($our_teams_edit->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($our_teams_edit->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $our_teams_edit->PageUrl() ?>start=<?php echo $our_teams_edit->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($our_teams_edit->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $our_teams_edit->PageUrl() ?>start=<?php echo $our_teams_edit->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($our_teams_edit->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $our_teams_edit->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($our_teams_edit->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $our_teams_edit->PageUrl() ?>start=<?php echo $our_teams_edit->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($our_teams_edit->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $our_teams_edit->PageUrl() ?>start=<?php echo $our_teams_edit->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<form name="four_teamsedit" id="four_teamsedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($our_teams_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $our_teams_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="our_teams">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($our_teams->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_our_teams_id" class="col-sm-2 control-label ewLabel"><?php echo $our_teams->id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $our_teams->id->CellAttributes() ?>>
<span id="el_our_teams_id">
<span<?php echo $our_teams->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $our_teams->id->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($our_teams->id->CurrentValue) ?>">
<?php echo $our_teams->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($our_teams->image->Visible) { // image ?>
	<div id="r_image" class="form-group">
		<label id="elh_our_teams_image" class="col-sm-2 control-label ewLabel"><?php echo $our_teams->image->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $our_teams->image->CellAttributes() ?>>
<span id="el_our_teams_image">
<div id="fd_x_image">
<span title="<?php echo $our_teams->image->FldTitle() ? $our_teams->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($our_teams->image->ReadOnly || $our_teams->image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_image" name="x_image" id="x_image">
</span>
<input type="hidden" name="fn_x_image" id= "fn_x_image" value="<?php echo $our_teams->image->Upload->FileName ?>">
<?php if (@$_POST["fa_x_image"] == "0") { ?>
<input type="hidden" name="fa_x_image" id= "fa_x_image" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_image" id= "fa_x_image" value="1">
<?php } ?>
<input type="hidden" name="fs_x_image" id= "fs_x_image" value="255">
<input type="hidden" name="fx_x_image" id= "fx_x_image" value="<?php echo $our_teams->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_image" id= "fm_x_image" value="<?php echo $our_teams->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $our_teams->image->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($our_teams->name_en->Visible) { // name_en ?>
	<div id="r_name_en" class="form-group">
		<label id="elh_our_teams_name_en" for="x_name_en" class="col-sm-2 control-label ewLabel"><?php echo $our_teams->name_en->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $our_teams->name_en->CellAttributes() ?>>
<span id="el_our_teams_name_en">
<input type="text" data-field="x_name_en" name="x_name_en" id="x_name_en" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->name_en->PlaceHolder) ?>" value="<?php echo $our_teams->name_en->EditValue ?>"<?php echo $our_teams->name_en->EditAttributes() ?>>
</span>
<?php echo $our_teams->name_en->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($our_teams->name_ar->Visible) { // name_ar ?>
	<div id="r_name_ar" class="form-group">
		<label id="elh_our_teams_name_ar" for="x_name_ar" class="col-sm-2 control-label ewLabel"><?php echo $our_teams->name_ar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $our_teams->name_ar->CellAttributes() ?>>
<span id="el_our_teams_name_ar">
<input type="text" data-field="x_name_ar" name="x_name_ar" id="x_name_ar" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->name_ar->PlaceHolder) ?>" value="<?php echo $our_teams->name_ar->EditValue ?>"<?php echo $our_teams->name_ar->EditAttributes() ?>>
</span>
<?php echo $our_teams->name_ar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($our_teams->title_en->Visible) { // title_en ?>
	<div id="r_title_en" class="form-group">
		<label id="elh_our_teams_title_en" for="x_title_en" class="col-sm-2 control-label ewLabel"><?php echo $our_teams->title_en->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $our_teams->title_en->CellAttributes() ?>>
<span id="el_our_teams_title_en">
<input type="text" data-field="x_title_en" name="x_title_en" id="x_title_en" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->title_en->PlaceHolder) ?>" value="<?php echo $our_teams->title_en->EditValue ?>"<?php echo $our_teams->title_en->EditAttributes() ?>>
</span>
<?php echo $our_teams->title_en->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($our_teams->title_ar->Visible) { // title_ar ?>
	<div id="r_title_ar" class="form-group">
		<label id="elh_our_teams_title_ar" for="x_title_ar" class="col-sm-2 control-label ewLabel"><?php echo $our_teams->title_ar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $our_teams->title_ar->CellAttributes() ?>>
<span id="el_our_teams_title_ar">
<input type="text" data-field="x_title_ar" name="x_title_ar" id="x_title_ar" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->title_ar->PlaceHolder) ?>" value="<?php echo $our_teams->title_ar->EditValue ?>"<?php echo $our_teams->title_ar->EditAttributes() ?>>
</span>
<?php echo $our_teams->title_ar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($our_teams->bio_en->Visible) { // bio_en ?>
	<div id="r_bio_en" class="form-group">
		<label id="elh_our_teams_bio_en" for="x_bio_en" class="col-sm-2 control-label ewLabel"><?php echo $our_teams->bio_en->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $our_teams->bio_en->CellAttributes() ?>>
<span id="el_our_teams_bio_en">
<input type="text" data-field="x_bio_en" name="x_bio_en" id="x_bio_en" size="150" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->bio_en->PlaceHolder) ?>" value="<?php echo $our_teams->bio_en->EditValue ?>"<?php echo $our_teams->bio_en->EditAttributes() ?>>
</span>
<?php echo $our_teams->bio_en->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($our_teams->bio_ar->Visible) { // bio_ar ?>
	<div id="r_bio_ar" class="form-group">
		<label id="elh_our_teams_bio_ar" for="x_bio_ar" class="col-sm-2 control-label ewLabel"><?php echo $our_teams->bio_ar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $our_teams->bio_ar->CellAttributes() ?>>
<span id="el_our_teams_bio_ar">
<input type="text" data-field="x_bio_ar" name="x_bio_ar" id="x_bio_ar" size="150" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->bio_ar->PlaceHolder) ?>" value="<?php echo $our_teams->bio_ar->EditValue ?>"<?php echo $our_teams->bio_ar->EditAttributes() ?>>
</span>
<?php echo $our_teams->bio_ar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($our_teams->facebook->Visible) { // facebook ?>
	<div id="r_facebook" class="form-group">
		<label id="elh_our_teams_facebook" for="x_facebook" class="col-sm-2 control-label ewLabel"><?php echo $our_teams->facebook->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $our_teams->facebook->CellAttributes() ?>>
<span id="el_our_teams_facebook">
<input type="text" data-field="x_facebook" name="x_facebook" id="x_facebook" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->facebook->PlaceHolder) ?>" value="<?php echo $our_teams->facebook->EditValue ?>"<?php echo $our_teams->facebook->EditAttributes() ?>>
</span>
<?php echo $our_teams->facebook->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($our_teams->twitter->Visible) { // twitter ?>
	<div id="r_twitter" class="form-group">
		<label id="elh_our_teams_twitter" for="x_twitter" class="col-sm-2 control-label ewLabel"><?php echo $our_teams->twitter->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $our_teams->twitter->CellAttributes() ?>>
<span id="el_our_teams_twitter">
<input type="text" data-field="x_twitter" name="x_twitter" id="x_twitter" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->twitter->PlaceHolder) ?>" value="<?php echo $our_teams->twitter->EditValue ?>"<?php echo $our_teams->twitter->EditAttributes() ?>>
</span>
<?php echo $our_teams->twitter->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($our_teams->linkedin->Visible) { // linkedin ?>
	<div id="r_linkedin" class="form-group">
		<label id="elh_our_teams_linkedin" for="x_linkedin" class="col-sm-2 control-label ewLabel"><?php echo $our_teams->linkedin->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $our_teams->linkedin->CellAttributes() ?>>
<span id="el_our_teams_linkedin">
<input type="text" data-field="x_linkedin" name="x_linkedin" id="x_linkedin" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->linkedin->PlaceHolder) ?>" value="<?php echo $our_teams->linkedin->EditValue ?>"<?php echo $our_teams->linkedin->EditAttributes() ?>>
</span>
<?php echo $our_teams->linkedin->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
<?php if (!isset($our_teams_edit->Pager)) $our_teams_edit->Pager = new cNumericPager($our_teams_edit->StartRec, $our_teams_edit->DisplayRecs, $our_teams_edit->TotalRecs, $our_teams_edit->RecRange) ?>
<?php if ($our_teams_edit->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($our_teams_edit->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $our_teams_edit->PageUrl() ?>start=<?php echo $our_teams_edit->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($our_teams_edit->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $our_teams_edit->PageUrl() ?>start=<?php echo $our_teams_edit->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($our_teams_edit->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $our_teams_edit->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($our_teams_edit->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $our_teams_edit->PageUrl() ?>start=<?php echo $our_teams_edit->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($our_teams_edit->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $our_teams_edit->PageUrl() ?>start=<?php echo $our_teams_edit->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<script type="text/javascript">
four_teamsedit.Init();
</script>
<?php
$our_teams_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$our_teams_edit->Page_Terminate();
?>
