<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "jobsinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "administratorinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "job_candidatesgridcls.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$jobs_add = NULL; // Initialize page object first

class cjobs_add extends cjobs {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'jobs';

	// Page object name
	var $PageObjName = 'jobs_add';

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

		// Table object (jobs)
		if (!isset($GLOBALS["jobs"]) || get_class($GLOBALS["jobs"]) == "cjobs") {
			$GLOBALS["jobs"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["jobs"];
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
			define("EW_TABLE_NAME", 'jobs', TRUE);

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

			// Process auto fill for detail table 'job_candidates'
			if (@$_POST["grid"] == "fjob_candidatesgrid") {
				if (!isset($GLOBALS["job_candidates_grid"])) $GLOBALS["job_candidates_grid"] = new cjob_candidates_grid;
				$GLOBALS["job_candidates_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $jobs;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($jobs);
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

		// Set up detail parameters
		$this->SetUpDetailParms();

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
					$this->Page_Terminate("jobslist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					if ($this->getCurrentDetailTable() <> "") // Master/detail add
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "jobsview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetUpDetailParms();
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
		$this->image->Upload->Index = $objForm->Index;
		$this->image->Upload->UploadFile();
		$this->image->CurrentValue = $this->image->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->image->Upload->DbValue = NULL;
		$this->image->OldValue = $this->image->Upload->DbValue;
		$this->image->CurrentValue = NULL; // Clear file related field
		$this->title_en->CurrentValue = NULL;
		$this->title_en->OldValue = $this->title_en->CurrentValue;
		$this->title_ar->CurrentValue = NULL;
		$this->title_ar->OldValue = $this->title_ar->CurrentValue;
		$this->position_en->CurrentValue = NULL;
		$this->position_en->OldValue = $this->position_en->CurrentValue;
		$this->position_ar->CurrentValue = NULL;
		$this->position_ar->OldValue = $this->position_ar->CurrentValue;
		$this->experience->CurrentValue = NULL;
		$this->experience->OldValue = $this->experience->CurrentValue;
		$this->gender->CurrentValue = "any";
		$this->applied->CurrentValue = NULL;
		$this->applied->OldValue = $this->applied->CurrentValue;
		$this->desc_en->CurrentValue = NULL;
		$this->desc_en->OldValue = $this->desc_en->CurrentValue;
		$this->desc_ar->CurrentValue = NULL;
		$this->desc_ar->OldValue = $this->desc_ar->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->title_en->FldIsDetailKey) {
			$this->title_en->setFormValue($objForm->GetValue("x_title_en"));
		}
		if (!$this->title_ar->FldIsDetailKey) {
			$this->title_ar->setFormValue($objForm->GetValue("x_title_ar"));
		}
		if (!$this->position_en->FldIsDetailKey) {
			$this->position_en->setFormValue($objForm->GetValue("x_position_en"));
		}
		if (!$this->position_ar->FldIsDetailKey) {
			$this->position_ar->setFormValue($objForm->GetValue("x_position_ar"));
		}
		if (!$this->experience->FldIsDetailKey) {
			$this->experience->setFormValue($objForm->GetValue("x_experience"));
		}
		if (!$this->gender->FldIsDetailKey) {
			$this->gender->setFormValue($objForm->GetValue("x_gender"));
		}
		if (!$this->applied->FldIsDetailKey) {
			$this->applied->setFormValue($objForm->GetValue("x_applied"));
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
		$this->LoadOldRecord();
		$this->title_en->CurrentValue = $this->title_en->FormValue;
		$this->title_ar->CurrentValue = $this->title_ar->FormValue;
		$this->position_en->CurrentValue = $this->position_en->FormValue;
		$this->position_ar->CurrentValue = $this->position_ar->FormValue;
		$this->experience->CurrentValue = $this->experience->FormValue;
		$this->gender->CurrentValue = $this->gender->FormValue;
		$this->applied->CurrentValue = $this->applied->FormValue;
		$this->desc_en->CurrentValue = $this->desc_en->FormValue;
		$this->desc_ar->CurrentValue = $this->desc_ar->FormValue;
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
		$this->title_en->setDbValue($rs->fields('title_en'));
		$this->title_ar->setDbValue($rs->fields('title_ar'));
		$this->position_en->setDbValue($rs->fields('position_en'));
		$this->position_ar->setDbValue($rs->fields('position_ar'));
		$this->experience->setDbValue($rs->fields('experience'));
		$this->gender->setDbValue($rs->fields('gender'));
		$this->applied->setDbValue($rs->fields('applied'));
		$this->desc_en->setDbValue($rs->fields('desc_en'));
		$this->desc_ar->setDbValue($rs->fields('desc_ar'));
		$this->created->setDbValue($rs->fields('created'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->image->Upload->DbValue = $row['image'];
		$this->title_en->DbValue = $row['title_en'];
		$this->title_ar->DbValue = $row['title_ar'];
		$this->position_en->DbValue = $row['position_en'];
		$this->position_ar->DbValue = $row['position_ar'];
		$this->experience->DbValue = $row['experience'];
		$this->gender->DbValue = $row['gender'];
		$this->applied->DbValue = $row['applied'];
		$this->desc_en->DbValue = $row['desc_en'];
		$this->desc_ar->DbValue = $row['desc_ar'];
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
		// image
		// title_en
		// title_ar
		// position_en
		// position_ar
		// experience
		// gender
		// applied
		// desc_en
		// desc_ar
		// created

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// image
			$this->image->UploadPath = '../webroot/uploads/jobs/';
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

			// title_en
			$this->title_en->ViewValue = $this->title_en->CurrentValue;
			$this->title_en->ViewCustomAttributes = "";

			// title_ar
			$this->title_ar->ViewValue = $this->title_ar->CurrentValue;
			$this->title_ar->ViewCustomAttributes = "";

			// position_en
			$this->position_en->ViewValue = $this->position_en->CurrentValue;
			$this->position_en->ViewCustomAttributes = "";

			// position_ar
			$this->position_ar->ViewValue = $this->position_ar->CurrentValue;
			$this->position_ar->ViewCustomAttributes = "";

			// experience
			$this->experience->ViewValue = $this->experience->CurrentValue;
			$this->experience->ViewCustomAttributes = "";

			// gender
			if (strval($this->gender->CurrentValue) <> "") {
				switch ($this->gender->CurrentValue) {
					case $this->gender->FldTagValue(1):
						$this->gender->ViewValue = $this->gender->FldTagCaption(1) <> "" ? $this->gender->FldTagCaption(1) : $this->gender->CurrentValue;
						break;
					case $this->gender->FldTagValue(2):
						$this->gender->ViewValue = $this->gender->FldTagCaption(2) <> "" ? $this->gender->FldTagCaption(2) : $this->gender->CurrentValue;
						break;
					case $this->gender->FldTagValue(3):
						$this->gender->ViewValue = $this->gender->FldTagCaption(3) <> "" ? $this->gender->FldTagCaption(3) : $this->gender->CurrentValue;
						break;
					default:
						$this->gender->ViewValue = $this->gender->CurrentValue;
				}
			} else {
				$this->gender->ViewValue = NULL;
			}
			$this->gender->ViewCustomAttributes = "";

			// applied
			$this->applied->ViewValue = $this->applied->CurrentValue;
			$this->applied->ViewCustomAttributes = "";

			// desc_en
			$this->desc_en->ViewValue = $this->desc_en->CurrentValue;
			$this->desc_en->ViewCustomAttributes = "";

			// desc_ar
			$this->desc_ar->ViewValue = $this->desc_ar->CurrentValue;
			$this->desc_ar->ViewCustomAttributes = "";

			// created
			$this->created->ViewValue = $this->created->CurrentValue;
			$this->created->ViewValue = ew_FormatDateTime($this->created->ViewValue, 7);
			$this->created->ViewCustomAttributes = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->UploadPath = '../webroot/uploads/jobs/';
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
				$this->image->LinkAttrs["data-rel"] = "jobs_x_image";
				$this->image->LinkAttrs["class"] = "ewLightbox";
			}

			// title_en
			$this->title_en->LinkCustomAttributes = "";
			$this->title_en->HrefValue = "";
			$this->title_en->TooltipValue = "";

			// title_ar
			$this->title_ar->LinkCustomAttributes = "";
			$this->title_ar->HrefValue = "";
			$this->title_ar->TooltipValue = "";

			// position_en
			$this->position_en->LinkCustomAttributes = "";
			$this->position_en->HrefValue = "";
			$this->position_en->TooltipValue = "";

			// position_ar
			$this->position_ar->LinkCustomAttributes = "";
			$this->position_ar->HrefValue = "";
			$this->position_ar->TooltipValue = "";

			// experience
			$this->experience->LinkCustomAttributes = "";
			$this->experience->HrefValue = "";
			$this->experience->TooltipValue = "";

			// gender
			$this->gender->LinkCustomAttributes = "";
			$this->gender->HrefValue = "";
			$this->gender->TooltipValue = "";

			// applied
			$this->applied->LinkCustomAttributes = "";
			$this->applied->HrefValue = "";
			$this->applied->TooltipValue = "";

			// desc_en
			$this->desc_en->LinkCustomAttributes = "";
			$this->desc_en->HrefValue = "";
			$this->desc_en->TooltipValue = "";

			// desc_ar
			$this->desc_ar->LinkCustomAttributes = "";
			$this->desc_ar->HrefValue = "";
			$this->desc_ar->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// image
			$this->image->EditAttrs["class"] = "form-control";
			$this->image->EditCustomAttributes = "";
			$this->image->UploadPath = '../webroot/uploads/jobs/';
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
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->image);

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

			// position_en
			$this->position_en->EditAttrs["class"] = "form-control";
			$this->position_en->EditCustomAttributes = "";
			$this->position_en->EditValue = ew_HtmlEncode($this->position_en->CurrentValue);
			$this->position_en->PlaceHolder = ew_RemoveHtml($this->position_en->FldCaption());

			// position_ar
			$this->position_ar->EditAttrs["class"] = "form-control";
			$this->position_ar->EditCustomAttributes = "";
			$this->position_ar->EditValue = ew_HtmlEncode($this->position_ar->CurrentValue);
			$this->position_ar->PlaceHolder = ew_RemoveHtml($this->position_ar->FldCaption());

			// experience
			$this->experience->EditAttrs["class"] = "form-control";
			$this->experience->EditCustomAttributes = "";
			$this->experience->EditValue = ew_HtmlEncode($this->experience->CurrentValue);
			$this->experience->PlaceHolder = ew_RemoveHtml($this->experience->FldCaption());

			// gender
			$this->gender->EditAttrs["class"] = "form-control";
			$this->gender->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->gender->FldTagValue(1), $this->gender->FldTagCaption(1) <> "" ? $this->gender->FldTagCaption(1) : $this->gender->FldTagValue(1));
			$arwrk[] = array($this->gender->FldTagValue(2), $this->gender->FldTagCaption(2) <> "" ? $this->gender->FldTagCaption(2) : $this->gender->FldTagValue(2));
			$arwrk[] = array($this->gender->FldTagValue(3), $this->gender->FldTagCaption(3) <> "" ? $this->gender->FldTagCaption(3) : $this->gender->FldTagValue(3));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->gender->EditValue = $arwrk;

			// applied
			$this->applied->EditAttrs["class"] = "form-control";
			$this->applied->EditCustomAttributes = "";
			$this->applied->EditValue = ew_HtmlEncode($this->applied->CurrentValue);
			$this->applied->PlaceHolder = ew_RemoveHtml($this->applied->FldCaption());

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
			// image

			$this->image->UploadPath = '../webroot/uploads/jobs/';
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->HrefValue = ew_UploadPathEx(FALSE, $this->image->UploadPath) . $this->image->Upload->DbValue; // Add prefix/suffix
				$this->image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->image->HrefValue = ew_ConvertFullUrl($this->image->HrefValue);
			} else {
				$this->image->HrefValue = "";
			}
			$this->image->HrefValue2 = $this->image->UploadPath . $this->image->Upload->DbValue;

			// title_en
			$this->title_en->HrefValue = "";

			// title_ar
			$this->title_ar->HrefValue = "";

			// position_en
			$this->position_en->HrefValue = "";

			// position_ar
			$this->position_ar->HrefValue = "";

			// experience
			$this->experience->HrefValue = "";

			// gender
			$this->gender->HrefValue = "";

			// applied
			$this->applied->HrefValue = "";

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
		if ($this->image->Upload->FileName == "" && !$this->image->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->image->FldCaption(), $this->image->ReqErrMsg));
		}
		if (!$this->title_en->FldIsDetailKey && !is_null($this->title_en->FormValue) && $this->title_en->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->title_en->FldCaption(), $this->title_en->ReqErrMsg));
		}
		if (!$this->title_ar->FldIsDetailKey && !is_null($this->title_ar->FormValue) && $this->title_ar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->title_ar->FldCaption(), $this->title_ar->ReqErrMsg));
		}
		if (!$this->position_en->FldIsDetailKey && !is_null($this->position_en->FormValue) && $this->position_en->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->position_en->FldCaption(), $this->position_en->ReqErrMsg));
		}
		if (!$this->position_ar->FldIsDetailKey && !is_null($this->position_ar->FormValue) && $this->position_ar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->position_ar->FldCaption(), $this->position_ar->ReqErrMsg));
		}
		if (!$this->experience->FldIsDetailKey && !is_null($this->experience->FormValue) && $this->experience->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->experience->FldCaption(), $this->experience->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->experience->FormValue)) {
			ew_AddMessage($gsFormError, $this->experience->FldErrMsg());
		}
		if (!$this->gender->FldIsDetailKey && !is_null($this->gender->FormValue) && $this->gender->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->gender->FldCaption(), $this->gender->ReqErrMsg));
		}
		if (!$this->applied->FldIsDetailKey && !is_null($this->applied->FormValue) && $this->applied->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->applied->FldCaption(), $this->applied->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->applied->FormValue)) {
			ew_AddMessage($gsFormError, $this->applied->FldErrMsg());
		}
		if (!$this->desc_en->FldIsDetailKey && !is_null($this->desc_en->FormValue) && $this->desc_en->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->desc_en->FldCaption(), $this->desc_en->ReqErrMsg));
		}
		if (!$this->desc_ar->FldIsDetailKey && !is_null($this->desc_ar->FormValue) && $this->desc_ar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->desc_ar->FldCaption(), $this->desc_ar->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("job_candidates", $DetailTblVar) && $GLOBALS["job_candidates"]->DetailAdd) {
			if (!isset($GLOBALS["job_candidates_grid"])) $GLOBALS["job_candidates_grid"] = new cjob_candidates_grid(); // get detail page object
			$GLOBALS["job_candidates_grid"]->ValidateGridForm();
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

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
			$this->image->OldUploadPath = '../webroot/uploads/jobs/';
			$this->image->UploadPath = $this->image->OldUploadPath;
		}
		$rsnew = array();

		// image
		if (!$this->image->Upload->KeepFile) {
			$this->image->Upload->DbValue = ""; // No need to delete old file
			if ($this->image->Upload->FileName == "") {
				$rsnew['image'] = NULL;
			} else {
				$rsnew['image'] = $this->image->Upload->FileName;
			}
		}

		// title_en
		$this->title_en->SetDbValueDef($rsnew, $this->title_en->CurrentValue, "", FALSE);

		// title_ar
		$this->title_ar->SetDbValueDef($rsnew, $this->title_ar->CurrentValue, "", FALSE);

		// position_en
		$this->position_en->SetDbValueDef($rsnew, $this->position_en->CurrentValue, "", FALSE);

		// position_ar
		$this->position_ar->SetDbValueDef($rsnew, $this->position_ar->CurrentValue, "", FALSE);

		// experience
		$this->experience->SetDbValueDef($rsnew, $this->experience->CurrentValue, 0, FALSE);

		// gender
		$this->gender->SetDbValueDef($rsnew, $this->gender->CurrentValue, "", strval($this->gender->CurrentValue) == "");

		// applied
		$this->applied->SetDbValueDef($rsnew, $this->applied->CurrentValue, 0, FALSE);

		// desc_en
		$this->desc_en->SetDbValueDef($rsnew, $this->desc_en->CurrentValue, "", FALSE);

		// desc_ar
		$this->desc_ar->SetDbValueDef($rsnew, $this->desc_ar->CurrentValue, "", FALSE);
		if (!$this->image->Upload->KeepFile) {
			$this->image->UploadPath = '../webroot/uploads/jobs/';
			if (!ew_Empty($this->image->Upload->Value)) {
				if ($this->image->Upload->FileName == $this->image->Upload->DbValue) { // Overwrite if same file name
					$this->image->Upload->DbValue = ""; // No need to delete any more
				} else {
					$rsnew['image'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->image->UploadPath), $rsnew['image']); // Get new file name
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
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->id->setDbValue($conn->Insert_ID());
			$rsnew['id'] = $this->id->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("job_candidates", $DetailTblVar) && $GLOBALS["job_candidates"]->DetailAdd) {
				$GLOBALS["job_candidates"]->job_id->setSessionValue($this->id->CurrentValue); // Set master key
				if (!isset($GLOBALS["job_candidates_grid"])) $GLOBALS["job_candidates_grid"] = new cjob_candidates_grid(); // Get detail page object
				$AddRow = $GLOBALS["job_candidates_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["job_candidates"]->job_id->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// image
		ew_CleanUploadTempPath($this->image, $this->image->Upload->Index);
		return $AddRow;
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("job_candidates", $DetailTblVar)) {
				if (!isset($GLOBALS["job_candidates_grid"]))
					$GLOBALS["job_candidates_grid"] = new cjob_candidates_grid;
				if ($GLOBALS["job_candidates_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["job_candidates_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["job_candidates_grid"]->CurrentMode = "add";
					$GLOBALS["job_candidates_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["job_candidates_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["job_candidates_grid"]->setStartRecordNumber(1);
					$GLOBALS["job_candidates_grid"]->job_id->FldIsDetailKey = TRUE;
					$GLOBALS["job_candidates_grid"]->job_id->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["job_candidates_grid"]->job_id->setSessionValue($GLOBALS["job_candidates_grid"]->job_id->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "jobslist.php", "", $this->TableVar, TRUE);
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
if (!isset($jobs_add)) $jobs_add = new cjobs_add();

// Page init
$jobs_add->Page_Init();

// Page main
$jobs_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$jobs_add->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var jobs_add = new ew_Page("jobs_add");
jobs_add.PageID = "add"; // Page ID
var EW_PAGE_ID = jobs_add.PageID; // For backward compatibility

// Form object
var fjobsadd = new ew_Form("fjobsadd");

// Validate form
fjobsadd.Validate = function() {
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
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $jobs->image->FldCaption(), $jobs->image->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_title_en");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jobs->title_en->FldCaption(), $jobs->title_en->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_title_ar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jobs->title_ar->FldCaption(), $jobs->title_ar->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_position_en");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jobs->position_en->FldCaption(), $jobs->position_en->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_position_ar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jobs->position_ar->FldCaption(), $jobs->position_ar->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_experience");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jobs->experience->FldCaption(), $jobs->experience->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_experience");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jobs->experience->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_gender");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jobs->gender->FldCaption(), $jobs->gender->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_applied");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jobs->applied->FldCaption(), $jobs->applied->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_applied");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jobs->applied->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_desc_en");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jobs->desc_en->FldCaption(), $jobs->desc_en->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_desc_ar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jobs->desc_ar->FldCaption(), $jobs->desc_ar->ReqErrMsg)) ?>");

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
fjobsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fjobsadd.ValidateRequired = true;
<?php } else { ?>
fjobsadd.ValidateRequired = false; 
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
<?php $jobs_add->ShowPageHeader(); ?>
<?php
$jobs_add->ShowMessage();
?>
<form name="fjobsadd" id="fjobsadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($jobs_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $jobs_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="jobs">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($jobs->image->Visible) { // image ?>
	<div id="r_image" class="form-group">
		<label id="elh_jobs_image" class="col-sm-2 control-label ewLabel"><?php echo $jobs->image->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jobs->image->CellAttributes() ?>>
<span id="el_jobs_image">
<div id="fd_x_image">
<span title="<?php echo $jobs->image->FldTitle() ? $jobs->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($jobs->image->ReadOnly || $jobs->image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_image" name="x_image" id="x_image">
</span>
<input type="hidden" name="fn_x_image" id= "fn_x_image" value="<?php echo $jobs->image->Upload->FileName ?>">
<input type="hidden" name="fa_x_image" id= "fa_x_image" value="0">
<input type="hidden" name="fs_x_image" id= "fs_x_image" value="255">
<input type="hidden" name="fx_x_image" id= "fx_x_image" value="<?php echo $jobs->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_image" id= "fm_x_image" value="<?php echo $jobs->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $jobs->image->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jobs->title_en->Visible) { // title_en ?>
	<div id="r_title_en" class="form-group">
		<label id="elh_jobs_title_en" for="x_title_en" class="col-sm-2 control-label ewLabel"><?php echo $jobs->title_en->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jobs->title_en->CellAttributes() ?>>
<span id="el_jobs_title_en">
<input type="text" data-field="x_title_en" name="x_title_en" id="x_title_en" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($jobs->title_en->PlaceHolder) ?>" value="<?php echo $jobs->title_en->EditValue ?>"<?php echo $jobs->title_en->EditAttributes() ?>>
</span>
<?php echo $jobs->title_en->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jobs->title_ar->Visible) { // title_ar ?>
	<div id="r_title_ar" class="form-group">
		<label id="elh_jobs_title_ar" for="x_title_ar" class="col-sm-2 control-label ewLabel"><?php echo $jobs->title_ar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jobs->title_ar->CellAttributes() ?>>
<span id="el_jobs_title_ar">
<input type="text" data-field="x_title_ar" name="x_title_ar" id="x_title_ar" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($jobs->title_ar->PlaceHolder) ?>" value="<?php echo $jobs->title_ar->EditValue ?>"<?php echo $jobs->title_ar->EditAttributes() ?>>
</span>
<?php echo $jobs->title_ar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jobs->position_en->Visible) { // position_en ?>
	<div id="r_position_en" class="form-group">
		<label id="elh_jobs_position_en" for="x_position_en" class="col-sm-2 control-label ewLabel"><?php echo $jobs->position_en->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jobs->position_en->CellAttributes() ?>>
<span id="el_jobs_position_en">
<input type="text" data-field="x_position_en" name="x_position_en" id="x_position_en" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($jobs->position_en->PlaceHolder) ?>" value="<?php echo $jobs->position_en->EditValue ?>"<?php echo $jobs->position_en->EditAttributes() ?>>
</span>
<?php echo $jobs->position_en->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jobs->position_ar->Visible) { // position_ar ?>
	<div id="r_position_ar" class="form-group">
		<label id="elh_jobs_position_ar" for="x_position_ar" class="col-sm-2 control-label ewLabel"><?php echo $jobs->position_ar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jobs->position_ar->CellAttributes() ?>>
<span id="el_jobs_position_ar">
<input type="text" data-field="x_position_ar" name="x_position_ar" id="x_position_ar" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($jobs->position_ar->PlaceHolder) ?>" value="<?php echo $jobs->position_ar->EditValue ?>"<?php echo $jobs->position_ar->EditAttributes() ?>>
</span>
<?php echo $jobs->position_ar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jobs->experience->Visible) { // experience ?>
	<div id="r_experience" class="form-group">
		<label id="elh_jobs_experience" for="x_experience" class="col-sm-2 control-label ewLabel"><?php echo $jobs->experience->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jobs->experience->CellAttributes() ?>>
<span id="el_jobs_experience">
<input type="text" data-field="x_experience" name="x_experience" id="x_experience" size="70" placeholder="<?php echo ew_HtmlEncode($jobs->experience->PlaceHolder) ?>" value="<?php echo $jobs->experience->EditValue ?>"<?php echo $jobs->experience->EditAttributes() ?>>
</span>
<?php echo $jobs->experience->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jobs->gender->Visible) { // gender ?>
	<div id="r_gender" class="form-group">
		<label id="elh_jobs_gender" for="x_gender" class="col-sm-2 control-label ewLabel"><?php echo $jobs->gender->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jobs->gender->CellAttributes() ?>>
<span id="el_jobs_gender">
<select data-field="x_gender" id="x_gender" name="x_gender"<?php echo $jobs->gender->EditAttributes() ?>>
<?php
if (is_array($jobs->gender->EditValue)) {
	$arwrk = $jobs->gender->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($jobs->gender->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $jobs->gender->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jobs->applied->Visible) { // applied ?>
	<div id="r_applied" class="form-group">
		<label id="elh_jobs_applied" for="x_applied" class="col-sm-2 control-label ewLabel"><?php echo $jobs->applied->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jobs->applied->CellAttributes() ?>>
<span id="el_jobs_applied">
<input type="text" data-field="x_applied" name="x_applied" id="x_applied" size="30" placeholder="<?php echo ew_HtmlEncode($jobs->applied->PlaceHolder) ?>" value="<?php echo $jobs->applied->EditValue ?>"<?php echo $jobs->applied->EditAttributes() ?>>
</span>
<?php echo $jobs->applied->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jobs->desc_en->Visible) { // desc_en ?>
	<div id="r_desc_en" class="form-group">
		<label id="elh_jobs_desc_en" class="col-sm-2 control-label ewLabel"><?php echo $jobs->desc_en->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jobs->desc_en->CellAttributes() ?>>
<span id="el_jobs_desc_en">
<textarea data-field="x_desc_en" class="editor" name="x_desc_en" id="x_desc_en" cols="60" rows="6" placeholder="<?php echo ew_HtmlEncode($jobs->desc_en->PlaceHolder) ?>"<?php echo $jobs->desc_en->EditAttributes() ?>><?php echo $jobs->desc_en->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fjobsadd", "x_desc_en", 60, 6, <?php echo ($jobs->desc_en->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $jobs->desc_en->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jobs->desc_ar->Visible) { // desc_ar ?>
	<div id="r_desc_ar" class="form-group">
		<label id="elh_jobs_desc_ar" class="col-sm-2 control-label ewLabel"><?php echo $jobs->desc_ar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jobs->desc_ar->CellAttributes() ?>>
<span id="el_jobs_desc_ar">
<textarea data-field="x_desc_ar" class="editor" name="x_desc_ar" id="x_desc_ar" cols="60" rows="6" placeholder="<?php echo ew_HtmlEncode($jobs->desc_ar->PlaceHolder) ?>"<?php echo $jobs->desc_ar->EditAttributes() ?>><?php echo $jobs->desc_ar->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fjobsadd", "x_desc_ar", 60, 6, <?php echo ($jobs->desc_ar->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $jobs->desc_ar->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("job_candidates", explode(",", $jobs->getCurrentDetailTable())) && $job_candidates->DetailAdd) {
?>
<?php if ($jobs->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("job_candidates", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "job_candidatesgrid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fjobsadd.Init();
</script>
<?php
$jobs_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$jobs_add->Page_Terminate();
?>
